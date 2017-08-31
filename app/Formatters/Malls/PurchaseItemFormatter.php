<?php
namespace App\Formatters\Malls;

use App\Formatters\JsonFormatter;

class PurchaseItemFormatter extends JsonFormatter
{
    /**
     * Get list formatter for list method.
     */
    public static function getListFormatter()
    {
        return function ($purchase) {
            $originalArray = $purchase->toArray();

            $purchaseItems = $purchase->items->map(function ($item) {
                return [
                    'item_id'    => $item->id,
                    'item_cover' => $item->cover_url,
                    'item_price' => $item->price,
                    'item_count' => $item->count,
                    'item_total' => $item->total,
                    'item_size'  => $item->size
                ];
            });

            return array_merge($originalArray, [
                'items' => $purchaseItems
            ]);
        };
    }

    /**
     * Get detail formatter for show method.
     */
    public static function getDetailFormatter()
    {
        return function ($purchase) {
            $originalArray = $purchase->toArray();

            return array_merge($originalArray, [
                'purchase_items'   => $purchase->items,
                'purchase_address' => $purchase->address,
                'purchase_note'    => $purchase->note
            ]);
        };
    }

    /**
     * A formatter for purchase items in purchase list.
     */
    public static function getDetailFormatterForPurchaseList()
    {
        return function ($item) {
            return [
                'item_id'         => $item->id,
                'item_product_id' => $item->product_id,
                'item_title'      => $item->title,
                'item_cover'      => $item->cover_url,
                'item_price'      => $item->price,
                'item_count'      => $item->count,
                'item_total'      => $item->total,
                'item_size'       => $item->size
            ];
        };
    }
}
