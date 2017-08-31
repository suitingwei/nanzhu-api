<?php

namespace App\Http\Controllers\Api;

use App\Formatters\Malls\ProductFormatter;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductsController extends BaseController
{
    private $productRepository;

    /**
     * ProductsController constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Request $request
     */
    public function index()
    {
        $products = $this->productRepository->fetchPaginated();

        return $this->responseSuccess('', ['products' => $products]);
    }

    /**
     * Create a new product.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $product = $this->createNewProduct($request);
        $product->createRelativeImages($request);
        $product->createBanners($request);
        return $this->responseSuccess();
    }

    /**
     * Create a new product.
     *
     * @param Request $request
     *
     * @return Product
     */
    private function createNewProduct(Request $request)
    {
        return Product::create([
            'title'        => $request->input('title'),
            'introduction' => $request->input('introduction'),
            'is_show'      => Product::STATUS_NOT_SHOW,
            'sort'         => $request->input('sort', 0)
        ]);
    }

    /**
     * Update the product's info.
     *
     * @param         $productId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($productId, Request $request)
    {
        $product = Product::find($productId);

        $needToUpdateInfo = collect($request->only(['is_show', 'title', 'introduction', 'sort']))->filter(function (
            $value
        ) {
            return !empty($value);
        })->all();

        $product->update($needToUpdateInfo);

        //Delete all uploaded pictures,and recreate the relative pictures.
        $productImages = $request->input('img_url', '');

        if ($productImages && count(explode(',', $productImages))) {
            $product->pictures()->delete();
            $product->createRelativeImages($request);
        }

        //Delete all uploaded banners,and recreate the relative banners.
        $productBanners = $request->input('banners', '');
        if ($productBanners && count(explode(',', $productBanners))) {
            $product->banners()->delete();
            $product->createBanners($request);
        }

        return $this->responseSuccess('更新成功');
    }

    /**
     * Get a certain product's detail info
     *
     * @param $productid
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Request $request
     *
     */
    public function show($productid)
    {
        $product   = Product::find($productid);
        $formatter = ProductFormatter::getDetailFormatter();
        return $this->responseSuccess('操作成功', ['product' => $formatter($product)]);
    }

    /**
     * Delete the product.
     *
     * @param $productId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($productId)
    {
        $product = Product::find($productId);

        if ($product) {
            $product->delete();

            return $this->responseSuccess();
        }

        return $this->responseFail('不存在');
    }

}
