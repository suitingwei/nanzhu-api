<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Pingpp\Charge;
use Pingpp\Pingpp;

/**
 * @property mixed charge_id
 */
class Payment extends Model
{
    public $guarded = [];

    /**
     * @param array $attributes
     *
     * @return Charge
     */
    public static function createPurchaseCharge($attributes = [])
    {
        \Log::info('creating purchase charge' . json_encode($attributes));
        $pingPPData = Arr::except($attributes, ['purchase_id', 'user_id']);

        $charge = Charge::create($pingPPData);

        static::create([
            'purchase_id' => $attributes['purchase_id'],
            'user_id'     => $attributes['user_id'],
            'amount'      => $charge->amount,
            'paid'        => $charge->paid,
            'charge_id'   => $charge->id,
            'object'      => $charge->object,
            'created'     => $charge->created,
            'livemode'    => $charge->livemode,
        ]);

        return $charge;
    }

    /**
     * Create pingpp charge for social security order.
     *
     * @param array $attributes
     *
     * @return Charge
     * @internal param $array
     *
     */
    public static function createSocialSecurityOrderCharge($attributes = [])
    {
        \Log::info('creating social security order charge' . json_encode($attributes));
        $pingPPData = Arr::except($attributes, ['order_id', 'user_id']);

        $charge = Charge::create($pingPPData);

        static::create([
            'order_id'  => $attributes['order_id'],
            'user_id'   => $attributes['user_id'],
            'amount'    => $charge->amount,
            'paid'      => $charge->paid,
            'charge_id' => $charge->id,
            'object'    => $charge->object,
            'created'   => $charge->created,
            'livemode'  => $charge->livemode,
        ]);

        return $charge;
    }

    /**
     * @return Charge
     */
    public function getChargeAttribute()
    {
        Pingpp::setApiKey(env('PINGPP_SECRET_KEY'));
        return Charge::retrieve($this->charge_id);
    }
}
