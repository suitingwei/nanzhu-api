<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductPrice
 * @package App\Models
 */
class ProductPrice extends Model
{
    /**
     * @var array
     */
    public $fillable = ['product_id', 'product_size_id', 'price'];


    /**
     * A product belongs to a product.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * A product size belongs to a product size.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id', 'id');
    }
}
