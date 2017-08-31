<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Sms;
use App\Repositories\PurchaseRepository;
use DB;
use Illuminate\Http\Request;

/**
 * Class PurchasesController
 * @package App\Http\Controllers
 */
class PurchasesController extends BaseController
{
    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;

    /**
     * PurchasesController constructor.
     *
     * @param PurchaseRepository $purchaseRepository
     */
    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Return the purchase's list for both app and op.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $purchases = $this->purchaseRepository->fetchPaginated();

        return $this->responseSuccess('', ['purchases' => $purchases]);
    }

    /**
     * Create a new purchase and purchas-items,and purchcase-address.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        DB::beginTransaction();
        try {
            list($charge, $purchase) = $this->purchaseRepository->create();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFail($e->getMessage());
        }
        DB::commit();
        return response()->json(['ret' => 0, 'msg' => '成功', 'charge' => $charge, 'order_id' => $purchase->id]);
    }

    /**
     * Get the detail of the purchase.
     *
     * @param         $purchaseId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($purchaseId)
    {
        try {
            $purchase = $this->purchaseRepository->getDetail($purchaseId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        return $this->responseSuccess('操作成功', ['purchase' => $purchase]);
    }

    /**
     * Calculate the express price for the current purchase items.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateExpressPrice(Request $request)
    {
        $product           = Product::find($request->input('product_id'));
        $size              = ProductSize::find($request->input('product_size_id'));
        $price             = $product->prices()->where('product_size_id', $size->id)->first(['price']);
        $totalProductPrice = $price->price * (int)$request->input('count');

        return $this->responseSuccess('', [
            'express_price'        => 0,
            'total_products_price' => $totalProductPrice,
            'total_price'          => $totalProductPrice
        ]);
    }

    /**
     * Cancel the purchase.
     *
     * @param         $purchaseId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($purchaseId, Request $request)
    {
        \Log::info('正在更新订单状态' . $purchaseId . '参数是' . json_encode($request->all()));
        try {
            list($user, $purchase) = $this->purchaseRepository->validatePurchaseOwner($purchaseId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        $action = $request->input('action');

        $purchase->$action();

        return $this->responseSuccess();
    }

    /**
     * Delete the purchase.
     *
     * @param         $purchaseId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($purchaseId)
    {
        try {
            list($user, $purchase) = $this->purchaseRepository->validatePurchaseOwner($purchaseId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        $purchase->remove();

        return $this->responseSuccess();
    }

    /**
     * Recreate the charge object.
     *
     * @param         $purchaseId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function recharge($purchaseId)
    {
        try {
            list($user, $purchase) = $this->purchaseRepository->validatePurchaseOwner($purchaseId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        $charge = $this->purchaseRepository->createPingPPCharge($purchase);
        $purchase->update(['channel' => request()->input('channel')]);

        return response()->json(['ret' => 0, 'msg' => '成功', 'charge' => $charge, 'order_id' => $purchase->id]);
    }

    /**
     * Update the purchase's info,expect the purchase status.
     *
     * @param         $purchaseId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($purchaseId, Request $request)
    {
        try {
            list($user, $purchase) = $this->purchaseRepository->validatePurchaseOwner($purchaseId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }
        $updateData = collect($request->only([
            'express_number',
            'express_company',
        ]))->filter(function ($updateAttribute) {
            return !empty($updateAttribute);
        });

        $purchase->update($updateData->all());

        Sms::sendPurchaseShippedToCustomer($purchase);

        return $this->responseSuccess();
    }

    /**
     * Update the user purchase's address.
     *
     * @param         $purchaseId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAddress($purchaseId, Request $request)
    {
        \Log::info("updating address");
        try {
            list($user, $purchase) = $this->purchaseRepository->validatePurchaseOwner($purchaseId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        $updateData = collect($request->only([
            'province',
            'city',
            'area',
            'detail',
            'receiver_name',
            'receiver_phone',
        ]))->filter(function ($updateAttribute) {
            return !empty($updateAttribute);
        });
        $purchase->address->update($updateData->all());

        return $this->responseSuccess();
    }


    public function updateTotalprice($purchaseId, Request $request)
    {
        \Log::info("updating price");
        try {
            list($user, $purchase) = $this->purchaseRepository->validatePurchaseOwner($purchaseId);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }
        $updateData = collect($request->only([
            'total_items_price'
        ]))->filter(function ($updateAttribute) {
            return !empty($updateAttribute);
        });
        \Log::info("updating " . $purchase);
        \Log::info("updating totalprice" . json_encode($updateData->all()));
        $purchase->update($updateData->all());
        return $this->responseSuccess();
    }
}
