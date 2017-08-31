<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Product;


class ProductsController extends BaseController
{
    /**
     * Show the product detail page.
     *
     * @param $productId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($productId)
    {
        $product = Product::find($productId);

        return view('mobile.products.show', compact('product'));
    }
}
