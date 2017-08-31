<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string receiver_name
 */
class PurchaseAddress extends Model
{
    public $fillable = [
        'user_id',
        'purchase_id',
        'receiver_name',
        'receiver_phone',
        'province',
        'city',
        'area',
        'detail',
    ];

    public $appends = ['mixed_address'];

    /**
     * Get the mixed address.
     */
    public function getMixedAddressAttribute()
    {
        return "$this->province,$this->city,$this->area,$this->detail";
    }
}
