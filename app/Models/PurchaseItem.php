<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    public $fillable = [
        'user_id',
        'title',
        'product_id',
        'size',
        'count',
        'price',
        'total',
        'cover_url',
        'purchase_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($purchaseItem) {
            //Calculate the purchase item's total price.
            $purchaseItem->update(['total' => $purchaseItem->price * $purchaseItem->count]);

            //Update the purchase total price attribute.
            $purchaseItem->purchase->increment('total_items_price', (double)$purchaseItem->total);

            //Update the purchase total items count attribue.
            $purchaseItem->purchase->increment('total_items_count', (double)$purchaseItem->count);
        });
    }

    /**
     * A purchase item have one product.
     */
    public function product()
    {
        \Log::info('connect with products tabk');
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * A purchase item belongs to a purchase.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }
}

