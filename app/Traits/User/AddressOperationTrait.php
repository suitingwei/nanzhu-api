<?php
namespace App\Traits\User;

use App\Models\UserAddress;

/**
 *------------------------------
 * Class AddressOperationTrait
 * @package App\Traits\User
 * -----------------------------
 */
trait AddressOperationTrait
{
    /**
     * A user may have many ship addresses.
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'FID');
    }

    /**
     * Get the default ship address.
     */
    public function defaultAddress()
    {
        return $this->addresses()->where('is_default')->first();
    }


}