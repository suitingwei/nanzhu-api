<?php

namespace App\Models;

use App\Traits\SocialSecurityOptions;
use App\Traits\SocialSecurityValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * @property mixed user_phone
 * @property mixed hukou_address
 * @property mixed minority
 * @property mixed bank
 * @property mixed bank_card_number
 */
class SocialSecurity extends Model
{
    use SocialSecurityOptions;
    use SocialSecurityValidator;

    public $guarded = [];

    public static $storeRules = [
        'creator_id'          => 'required',
        'is_first'            => 'required',
        'user_name'           => 'required',
        'user_phone'          => 'required|size:11',
        'id_card_number'      => 'required|size:18',
        'id_card_up_image'    => 'required',
        'id_card_down_image'  => 'required',
        'id_card_photo'       => 'required_if:is_first,true',
        'hukou_address'       => 'required',
        'minority'            => 'required',
        'sub_bank'             => '',
        'bank_contact_person' => '',
        'bank'                => '',
        'bank_card_number'    => '',
    ];

    /**
     * Create a new social security record.
     *
     * @param Request $attributes
     *
     * @return static
     */
    public static function createOrFail(Collection &$attributes)
    {
        $storeData                        = static::validateStoreData($attributes);
        $security                         = static::create($storeData);
        $attributes['social_security_id'] = $security->id;
        return $security;
    }

    /**
     * A social security order may have many orders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(SocialSecurityOrder::class, 'social_security_id', 'id');
    }

    public function setIdCardPhotoAttribute($value)
    {
        $this->attributes['id_card_photo'] = $value ?: '';
    }

    public function setBankAttribute($value)
    {
        $this->attributes['bank'] = $value ?: '';
    }

    public function setBankCardNumberAttribute($value)
    {
        $this->attributes['bank_card_number'] = $value ?: '';
    }
}

