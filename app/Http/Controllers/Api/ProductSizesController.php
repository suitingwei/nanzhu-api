<?php

namespace App\Http\Controllers\Api;


use App\Models\ProductSize;
use Illuminate\Http\Request;
use Validator;

/**
 * Class ProductSizesController
 * @package App\Http\Controllers\Api
 */
class ProductSizesController extends BaseController
{

    /**
     * Get the product sizes list.
     * @return \Illuminate\Http\JsonResponse
     * @internal param Request $request
     *
     */
    public function index()
    {
        $productSizes = ProductSize::orderBy('sort', 'desc')->paginate(20);

        return $this->responseSuccess('操作成功', ['produce_sizes' => $productSizes->items()]);
    }

    /**
     * Create a new product size.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'desc' => 'required|unique:product_sizes,desc',
            'sort' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->responseFail($validator->errors()->first());
        }

        ProductSize::create([
            'desc' => $request->input('desc'),
            'sort' => $request->input('sort', 0)
        ]);

        return $this->responseSuccess();
    }

    /**
     * Delete a product size.
     *
     * @param $productSizeId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($productSizeId)
    {
        $productSize = ProductSize::find($productSizeId);

        if ($productSize) {
            $productSize->delete();
            return $this->responseSuccess();
        }

        return $this->responseFail('规格不存在');
    }

    /**
     * Update the productsize.
     *
     * @param         $productSizeId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($productSizeId, Request $request)
    {
        $productSize = ProductSize::find($productSizeId);

        $productSize->update($request->only('sort', 'desc'));

        return $this->responseSuccess();
    }
}
