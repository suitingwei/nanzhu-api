<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductPrice;
use Illuminate\Http\Request;

/**
 * Class ProductPricesController
 * @package App\Http\Controllers\Api
 */
class ProductPricesController extends BaseController
{
    /**
     * Create a
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $productId     = $request->input('product_id');
        $productSizes  = (array)$request->input('product_size_id');
        $productPrices = (array)$request->input('price');

        foreach ($productSizes as $index => $productSize) {
            ProductPrice::create([
                'product_id'      => $productId,
                'product_size_id' => $productSize,
                'price'           => $productPrices[$index]
            ]);
        }

        return $this->responseSuccess();
    }

    /**
     * Update the product price.
     *
     * @param $productPriceId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($productPriceId, Request $request)
    {
        $productPrice = ProductPrice::find($productPriceId);

        if ($productPrice) {
            $productPrice->update($request->only('price'));
        }

        return $this->responseSuccess();
    }

    /**
     * Destroy the product price.
     *
     * @param $productPriceId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($productPriceId)
    {
        $productPrice = ProductPrice::find($productPriceId);

        if ($productPrice) {
            $productPrice->delete();
            return $this->responseSuccess();
        }
        return $this->responseFail('不存在');

    }

}
