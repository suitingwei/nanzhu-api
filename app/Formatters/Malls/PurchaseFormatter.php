<?php

namespace App\Formatters\Malls;

use App\Formatters\JsonFormatter;
use App\Models\Purchase;
use Illuminate\Support\Arr;

class PurchaseFormatter extends JsonFormatter
{
    /**
     * Get list formatter for list method.
     */
    public static function getListFormatter()
    {
        return function ($purchase) {
            $originalArray = $purchase->toArray();

            $purchaseItems = $purchase->items->map(
                PurchaseItemFormatter::getDetailFormatterForPurchaseList()
            );

            return array_merge($originalArray, [
                'chinese_status'  => $purchase->chinese_status,
                'h5_express_url'  => $purchase->h5_express_url,
                'chinese_channel' => $purchase->chinese_channel,
                'items'           => $purchaseItems,
            ]);
        };
    }

    /**
     * Get detail formatter for show method.
     */
    public static function getDetailFormatter()
    {
        return function (Purchase $purchase) {
            $originalArray = $purchase->toArray();

            $purchaseItems = $purchase->items->map(
                PurchaseItemFormatter::getDetailFormatterForPurchaseList()
            );

            Arr::forget($originalArray, ['paid', 'shipped', 'confirmed', 'canceled']);

            return array_merge($originalArray, [
                'can_delete'               => $purchase->canDelete(),
                'can_see_express'          => $purchase->shipped,
                'express_price'            => $purchase->chinese_express_price,
                'chinese_status'           => $purchase->chinese_status,
                'chinese_channel'          => $purchase->chinese_channel,
                'h5_express_url'           => $purchase->h5_express_url,
                'items'                    => $purchaseItems,
                'address'                  => $purchase->address,
                'charge'                   => json_encode($purchase->payments()->first()->charge),
                'time_left_to_pay'         => $purchase->time_left_to_pay,
                'service_phone'            => '13701351123',
                'service_user_id'          => env('MOVIE_CLOTHES_SERVER_USER_ID'),
                'service_user_name'        => '南竹通告单组服定制客服',
                'is_movie_clothes_product' => $purchase->containsMovieClothesBrand(),
            ]);
        };
    }

    /**
     * @return \Closure
     */
    public static function getListFormatterForOp()
    {
        return function ($purchase) {
            $originalArray = $purchase->toArray();

            $purchaseItems = $purchase->items->map(
                PurchaseItemFormatter::getDetailFormatterForPurchaseList()
            );

            return array_merge($originalArray, [
                'chinese_status'  => $purchase->chinese_status,
                'h5_express_url'  => $purchase->h5_express_url,
                'chinese_channel' => $purchase->chinese_channel,
                'items'           => $purchaseItems,
                'address'         => $purchase->address
            ]);
        };
    }

    /**
     * Get the purchase detail formatter for op.
     */
    public static function getDeatailFormatterForOp()
    {
        return function ($purchase) {
            $originalArray = $purchase->toArray();

            $purchaseItems = $purchase->items->map(
                PurchaseItemFormatter::getDetailFormatterForPurchaseList()
            );

            return array_merge($originalArray, [
                'can_delete'       => $purchase->canDelete(),
                'can_see_express'  => $purchase->shipped,
                'express_price'    => $purchase->chinese_express_price,
                'chinese_status'   => $purchase->chinese_status,
                'chinese_channel'  => $purchase->chinese_channel,
                'h5_express_url'   => $purchase->h5_express_url,
                'items'            => $purchaseItems,
                'time_left_to_pay' => $purchase->time_left_to_pay,
                'address'          => $purchase->address
            ]);
        };
    }

}
