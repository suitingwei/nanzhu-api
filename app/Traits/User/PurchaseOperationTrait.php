<?php
namespace App\Traits\User;


use App\Models\Purchase;
use Illuminate\Database\Query\Builder;

trait PurchaseOperationTrait
{
    /**
     * A user may have many purchases.
     *
     * @return Builder
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'user_id', 'FID');
    }

}