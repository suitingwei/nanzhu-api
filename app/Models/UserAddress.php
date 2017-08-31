<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int is_default
 */
class UserAddress extends Model
{
    const TYPE_DEFAULT     = 1;
    const TYPE_NOT_DEFAULT = 0;

    public $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone',
        'province',
        'city',
        'area',
        'detail',
        'is_default'
    ];

    public $appends = ['mixed_address'];

    /**
     * Judge whether a address is the default.
     * @return bool
     */
    public function isDefault()
    {
        return $this->is_default == static::TYPE_DEFAULT;
    }

    /**
     *
     */
    public function getMixedAddressAttribute()
    {
        return "$this->province,$this->city,$this->area,$this->detail";
    }

}
