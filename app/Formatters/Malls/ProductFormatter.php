<?php

namespace App\Formatters\Malls;

use App\Formatters\JsonFormatter;
use App\Models\Product;
use App\Utils\OssUtil;

class ProductFormatter extends JsonFormatter
{
    /**
     * Get the list formatter for the index method.
     */
    public static function getListFormatter()
    {
        return function ($product) {
            $pictures = $product->pictures->lists('url');
            return [
                'id'             => $product->id,
                'title'          => $product->title,
                'picture'        => count($pictures) > 0 ? $pictures[0] : '',
                'price'          => $product->price,
                'original_price' => $product->original_price,
                'product_cover'  => $product->product_cover
            ];
        };

    }

    /**
     * Get the detail foramtter for the show method.
     */
    public static function getDetailFormatter()
    {
        return function (Product $product) {
            $originalAttributeArray = $product->toArray();

            $pictures     = $product->pictures->lists('url')->all();
            $productCover = $product->product_cover;
            $sizePrices   = array_map(function ($sizePrice) use ($productCover) {
                $sizePrice['cover'] = $productCover;
                return $sizePrice;
            }, $product->allSizesAndPrices());

            return array_merge($originalAttributeArray, [
                'product_cover'            => $productCover,
                'banners'                  => $product->banners->lists('cover'),
                'size'                     => OssUtil::getPicturesWithSizeInfo($pictures),
                'pictures'                 => $pictures,
                'size_prices'              => $sizePrices,
                'share_url'                => $product->share_detail_url,
                'service_phone'            => '13701351123',
                'is_movie_clothes_product' => $product->isMovieClothesProduct(),
                'service_user_id'          => env('MOVIE_CLOTHES_SERVER_USER_ID'),
                'service_user_name'        => '南竹通告单组服定制客服',
                'brands'                   => $product->brands,
            ]);
        };
    }

    /**
     * Get list formatter for admin.
     */
    public static function getListFormatterForAdmin()
    {
        return function (Product $product) {
            $originalAttributeArray = $product->toArray();

            return array_merge($originalAttributeArray, [
                'picture'     => $product->pictures->lists('url'),
                'size_prices' => $product->allSizesAndPrices(),
                'brands'      => $product->brands,
            ]);
        };
    }

}
