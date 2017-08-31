<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $brands = Brand::orderBy('created_at', 'desc')->paginate(15);

        return $this->responseSuccess(['brands' => $brands->all()]);
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
        Brand::create([
            'sort'         => $request->input('sort', 0),
            'title'        => $request->input('title'),
            'introduction' => $request->input('introduction'),
        ]);
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $brand = Brand::find($id);

        return $this->responseSuccess(['brand' => $brand]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        $brand->update($request->intersect(['title', 'introduction', 'sort']));

        return $this->responseSuccess(['brand' => $brand]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Brand::destroy($id);

        return $this->responseSuccess();
    }
}
