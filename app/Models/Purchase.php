<?php

namespace App\Models;

use App\User;
use App\Utils\MailUtil;
use App\Utils\PingPPUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * @property integer    serial_number
 * @property string     channel
 * @property string     status
 * @property int        user_id
 * @property double     total_items_price
 * @property boolean    canceled
 * @property boolean    confirmed
 * @property boolean    shipped
 * @property boolean    paid
 * @property int        id
 * @property Carbon     created_at
 * @property int        express_price
 * @property string     express_number
 * @property string     express_company
 * @property boolean    deleted
 * @property Collection items
 * @property User       user
 * @property PurchaseAddress receivePhone
 * @property Carbon updated_at
 */
class Purchase extends Model
{

    public $fillable = [
        'user_id',
        'total_items_price',
        'total_items_count',
        'serial_number',
        'channel',
        'paid',
        'canceled',
        'shipped',
        'confirmed',
        'deleted',
        'express_number',
        'express_company',
        'note'
    ];

    public $casts = [
        'paid'      => 'boolean',
        'canceled'  => 'boolean',
        'shipped'   => 'boolean',
        'confirmed' => 'boolean',
        'deleted'   => 'boolean',
    ];

    public $appends = ['total'];

    //Customers have 24 hours to pay the purchase.
    private static $maxSecondsToPay = 86400;

    /**
     * ------------------------------------------------------------------------------
     * Handle the purchase event.
     * ------------------------------------------------------------------------------
     * By 2017-01-03, there are 2 hooks in purchase model.And they are aiming for
     * 1. creating: To set the default status of the purchase.
     * 2. created:  To set the purchase serial number.
     *
     * Also there are events hooks in purchase items,serve as the calculator
     * of the total price.
     *
     * @see \App\Models\PurchaseItem::boot()
     */
    public static function boot()
    {
        parent::boot();

        //Whenever create a purchase, it's status will be set to wait_pay.
        static::creating(function ($purchase) {
            $purchase->paid = false;
        });

        //Whenever a purchase created, we'll have to calculate the serial number based on
        //the purchase id.
        static::created(function ($purchase) {
            $purchase->update(['serial_number' => 10000 + $purchase->id]);
        });

        //Update
        static::updated(function ($purchase) {
            if ($purchase->isDirty('express_number') && !$purchase->shipped && !empty($purchase->express_number)) {
                $purchase->update(['shipped' => true]);
            }
        });

        static::deleting(function ($purchase) {
            \Log::info('正在删除订单' . $purchase);
        });
    }

    /**
     * All products in purchase.
     * @return Collection
     */
    public function products()
    {
        return Product::whereIn('id', $this->items()->pluck('product_id')->all())->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'FID');
    }

    public function receivePhone()
    {
        return $this->hasOne(PurchaseAddress::class,'purchase_id','id');
    }

    /**
     * A purchase may have many items.
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id');
    }

    /**
     * A purchase only have one address.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(PurchaseAddress::class, 'purchase_id', 'id');
    }

    /**
     * Purhcase deleted.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeDeleted($query)
    {
        return $query->where('deleted', true);
    }

    /**
     * Purchase not deleted.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeNotDeleted($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('deleted')
                  ->orWhere('deleted', false);
        });
    }

    /**
     * Purchase canceled.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeCanceled($query)
    {
        return $query->notDeleted()->where('canceled', true);
    }

    /**
     * Purchase not canceled.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeNotCanceled($query)
    {
        return $query->where('canceled', false);
    }

    /**
     * Purchase paid.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopePaid($query)
    {
        return $query->where('paid', true);
    }

    public function scopeNotPaid($query)
    {
        return $query->where('paid', false);
    }

    /**
     * Purchase shipped.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeShipped($query)
    {
        return $query->where('shipped', true);
    }

    /**
     * @param $query
     *
     * @return Builder
     */
    public function scopeNotShipped($query)
    {
        return $query->where('shipped', false);
    }

    public function scopeNotConfirmed($query)
    {
        return $query->where('confirmed', false);
    }

    /**
     * Purchase wait be paid.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeWaitPay($query)
    {
        return $query->notDeleted()->notCanceled()->where('paid', false);
    }

    /**
     * Purchase wait be shipped.
     *
     * @param $query
     *
     * @return Builder
     */
    public function scopeWaitShip($query)
    {
        return $query->notDeleted()->notCanceled()->paid()->notShipped()->notConfirmed();
    }

    /**
     * Purchase wait be confirmed.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeWaitConfirm($query)
    {
        return $query->notDeleted()->notCanceled()->shipped();
    }

    /**
     * All purchaes without deleted.
     *
     * @param $query
     *
     * @return
     */
    public function scopeAll($query)
    {
        return $query;
    }

    /**
     * Get total price in cent.
     */
    public function totalItemsPriceInCent()
    {
        return $this->total_items_price * 10;
    }

    /**
     * Money need to pay, in cents.
     */
    public function totalPriceInCent()
    {
        return ($this->total_items_price + $this->express_price) * 100;
    }

    /**
     * Get the chinese payment channel of the purchase.
     */
    public function getChineseChannelAttribute()
    {
        return PingPPUtil::transforPaymentChannel($this->channel);
    }

    /**
     * Get purchase's chinese status.
     * @return mixed|string
     */
    public function getChineseStatusAttribute()
    {
        if ($this->deleted) {
            return '已删除';
        }
        if ($this->canceled) {
            return '已取消';
        }
        if ($this->confirmed) {
            return '已签收';
        }
        if ($this->shipped) {
            return '已发货';
        }
        if ($this->paid) {
            return '待发货';
        }
        if (!$this->paid) {
            return '待支付';
        }
        return '订单状态异常';
    }

    /**
     * Is purchase belongs to a certain user.
     *
     * @param $user
     *
     * @return bool
     */
    public function isBelongsTo($user)
    {
        return $this->user_id == $user;
    }

    /**
     * Get the purchase h5 express url.
     */
    public function getH5ExpressUrlAttribute()
    {
        return "https://m.kuaidi100.com/index_all.html?type={$this->express_company}&postid={$this->express_number}";
    }

    /**
     * Cancel the purchase.
     */
    public function cancel()
    {
        \Log::info('正在取消订单' . $this->id);
        $this->update(['canceled' => true]);
    }

    /**
     * A purchase may have many payment records.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'purchase_id', 'id');
    }

    /**
     * Get total items price with express_price.
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->total_items_price + $this->express_price;
    }

    /**
     * Delete the purchase.
     */
    public function remove()
    {
        $this->update(['deleted' => true]);
    }

    /**
     * Get time left for customer to pay.
     */
    public function getTimeLeftToPayAttribute()
    {
        $now         = Carbon::now();
        $pastSeconds = $now->diffInSeconds($this->created_at);
        if ($pastSeconds >= static::$maxSecondsToPay) {
            return 0;
        }

        return (static::$maxSecondsToPay - $pastSeconds) * 1000;
    }

    /**
     * Get chinese express price desc.
     * @return string
     */
    public function getChineseExpressPriceAttribute()
    {
        if ($this->express_price == 0) {
            return '免费';
        }

        return '¥' . $this->express_price;
    }

    /**
     * Judge wether a purchase can be deleted.
     * A purchase can be deleted only when it has been canceled,or had ben paid.
     */
    public function canDelete()
    {
        return $this->canceled || $this->paid;
    }

    /**
     * Set purchase's paid status be true.
     */
    public function pay()
    {
        $this->update(['paid' => true]);

        if ($this->containsMovieClothesBrand()) {
            Sms::sendToMovieClothServieMan($this);
        }
        MailUtil::alertNewMallPurchase($this);
    }

    /**
     * @return bool
     */
    public function containsMovieClothesBrand()
    {
        \Log::info('containsMovieClothesBrand');
        foreach ($this->items as $purchaseItem) {
            \Log::info('return true or false------');
            if ($purchaseItem->product->isMovieClothesProduct()) {
                return true;
            }
        }
        return false;
    }

}
