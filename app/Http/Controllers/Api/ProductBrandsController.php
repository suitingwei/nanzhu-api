<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductBrand;
use Illuminate\Http\Request;

class ProductBrandsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $productBrands = ProductBrand::orderBy('created_at', 'desc')->paginate(15)->all();

        return $this->responseSuccess(compact('productBrands'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $brandId    = $request->input('brand_id');
        $productId  = $request->input('product_id');
        $createData = ['brand_id' => $brandId, 'product_id' => $productId];
        if (ProductBrand::where($createData)->count() > 0) {
            return $this->responseSuccess();
        }

        ProductBrand::create($createData);

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param int $id
     *
     */
    public function destroy(Request $request)
    {
        ProductBrand::where([
            'brand_id'   => $request->input('brand_id'),
            'product_id' => $request->input('product_id'),
        ])->delete();

        return $this->responseSuccess();
    }
}
