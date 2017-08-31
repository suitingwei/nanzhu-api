<?php

namespace App\Models;

use App\Repositories\UIRepository;
use App\Traits\SocialSecurityOptions;
use App\Traits\SocialSecurityValidator;
use App\Utils\MailUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Pingpp\Pingpp;

/**
 * @property mixed  id
 * @property mixed  paid
 * @property mixed  canceled
 * @property mixed  creator_id
 * @property mixed  serial_number
 * @property mixed  end_date
 * @property int    cost_months
 * @property double total_price
 * @property string start_date
 * @property Carbon created_at
 * @property mixed  hukou_type
 * @property string show_end_date
 * @property string show_start_date
 * @property double social_security_price
 * @property string show_cost_months
 * @property double service_price
 * @property string channel
 * @property mixed  pension_price
 * @property mixed  medical_price
 * @property mixed  work_accident_price
 * @property mixed  lost_job_price
 * @property mixed  born_price
 * @property int is_first
 */
class SocialSecurityOrder extends Model
{
    use SocialSecurityOptions;
    use SocialSecurityValidator;

    public $guarded = [];

    public static $storeRules = [
        'creator_id'         => 'required',
        'is_first'           => 'required',
        'user_name'          => 'required',
        'user_phone'         => 'required|size:11',
        'hukou_type'         => 'required|numeric|between:0,3',
        'hukou_address'      => 'required',
        'minority'           => 'required',
        'start_date'         => 'required|date',
        'cost_months'        => 'required|numeric',
        'base_number'        => 'required|in:3082,4252',
        'social_security_id' => 'required|numeric'
    ];

    private $uiRepository = null;

    /**
     * Create a new social security record.
     *
     * @param Collection $attributes
     *
     * @return static
     */
    public static function createOrFail(Collection $attributes)
    {
        $storeData = static::validateStoreData($attributes);

        return static::create($storeData);
    }

    /**
     * @param $base_number
     * @param $hukou_type
     *
     * @return mixed
     */
    private static function getPriceForBaseNumberAndHuKouType($base_number, $hukou_type)
    {
        $price = SocialSecurityPrice::where([
            'base_number' => $base_number,
            'hukou_type'  => $hukou_type,
        ])->first();

        return $price;
    }

    /**
     * @param Request $request
     *
     * @return \Pingpp\Charge
     */
    public function createPingPPCharge(Request $request)
    {
        Pingpp::setApiKey(env('PINGPP_SECRET_KEY'));

        return Payment::createSocialSecurityOrderCharge([
            'order_id'  => $this->id,
            'user_id'   => $this->creator_id,
            'order_no'  => time() . $this->serial_number,
            'amount'    => $this->totalPriceInCent(),
            'body'      => '社保',
            'extra'     => [],
            'currency'  => 'cny',
            'subject'   => '南竹社保',
            'client_ip' => '127.0.0.1',
            'app'       => ['id' => 'app_Sy1eLCKKiL84aLW5'],
            'channel'   => $request->input("channel"),
        ]);
    }

    /**
     * Calaulate the price when creating.
     * Generate the serial number when created.
     */
    public static function boot()
    {
        parent::boot();

        //Init the order status attribtue.
        static::creating(function ($order) {
            $price                        = static::getPriceForBaseNumberAndHuKouType($order->base_number,
                $order->hukou_type);
            $order->paid                  = false;
            $order->canceled              = false;
            $order->born_price            = $price->born_price;
            $order->work_accident_price   = $price->work_accident_price;
            $order->lost_job_price        = $price->lost_job_price;
            $order->medical_price         = $price->medical_price;
            $order->pension_price         = $price->pension_price;
            $order->social_security_price = $price->total_price;
            $order->service_price         = SocialSecurityPrice::$defaultServicePrice;
            $order->total_price           = $order->cost_months * ($order->social_security_price + $order->service_price);
        });

        //Generate the serial number
        static::created(function ($order) {
            $order->serial_number = 'SHBX' . sprintf('%06d', $order->id);
            $order->end_date      = '';
            $order->save();
        });
    }

    /**
     * Get the ui repository which delegate all ui attributes.
     * @return UIRepository|null
     */
    public function getUIRepositoryAttribute()
    {
        if ($this->uiRepository) {
            return $this->uiRepository;
        }
        return $this->uiRepository = new UIRepository($this);
    }

    /**
     * Wether a order can be paid.
     * @return bool
     */
    public function getCanPayAttribute()
    {
        return !$this->canceled && !$this->paid;
    }

    /**
     * @return bool
     */
    public function getCanContinuePayAttribute()
    {
        return !$this->canceled && $this->paid;
    }

    /**
     * @return int
     */
    private function totalPriceInCent()
    {
        return $this->total_price * 100;
    }

    /**
     * Social security orders pay.
     */
    public function pay()
    {
        \Log::info('social security orders' . $this->id . 'paid successfully.');

        $this->update(['paid' => true]);

        MailUtil::alertNewSocialSecurityPurchase($this);
    }

    /**
     * Cancel the purchase.
     */
    public function cancel()
    {
        \Log::info('social security orders' . $this->id . 'canceled successfully.');

        $this->update(['canceled' => true]);
    }

    /**
     * A social security order belong to a social security.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function socialSecurity()
    {
        return $this->belongsTo(SocialSecurity::class, 'social_security_id', 'id');
    }

    /**
     * Set the start date.
     *
     * @param $inputStartDate
     *
     * @throws \Exception
     */
    public function setStartDateAttribute($inputStartDate)
    {
        $currentDate = Carbon::now();

        //Different social security order have different end date.
        $endDay =0;
        if($this->is_first == static::$typeFirst || $this->is_first == static::$typeFromOutBeijing){
            $endDay = 14;
        }

        if($this->is_first == static::$typeNotFirst){
           $endDay =18 ;
        }

        //The deadline of the social security order,is every month 20th,24:00:00
        $inputStartDate     = Carbon::createFromTimestamp(strtotime($inputStartDate));
        $everyMonthDeadDate = Carbon::create($inputStartDate->year, $inputStartDate->month, $endDay, 23, 59, 59);

        \Log::info('new order input start date is ' . $inputStartDate->toDateTimeString());
        \Log::info('new order dead date is ' . $everyMonthDeadDate->toDateTimeString());
        //If the order start date is greater than the deadline, we'll set it to the next month's first day.
        //If the order start date is before the deadline,than that's it.
        if ($currentDate->gt($everyMonthDeadDate)) {
            throw new \Exception('已经超过本月截止日期,请购买次月社保');
        }

        $this->attributes['start_date'] = $inputStartDate->toDateString();
    }

    /**
     * Calculate the end date attribtue.
     *
     * @param $value
     */
    public function setEndDateAttribute($value)
    {
        $startDate = Carbon::createFromTimestamp(strtotime($this->attributes['start_date']));

        //If the user pay for n months,then the first month is no need to calculate.
        //i.e. The order start from 2017-1-1 and cost 3 months, and it will be ,
        //2017-1-1, 2017-2-1, 2017-3-1, and we only need to add (3-1) = 2 months,
        $endDate = $startDate->addMonths($this->cost_months - 1);

        $this->attributes['end_date'] = $endDate->toDateString();
    }



}

