<?php
namespace App\Repositories;

use App\Models\SocialSecurity;
use App\Models\SocialSecurityOrder;
use App\Traits\SocialSecurityOptions;
use App\Utils\PingPPUtil;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Class UIRepository
 * @package App\Repositories
 */
class UIRepository
{
    use SocialSecurityOptions;
    /**
     * @var SocialSecurityOrder
     */
    private $order;

    /**
     * UIRepository constructor.
     *
     * @param SocialSecurityOrder $order
     *
     * @internal param SocialSecurityOrder $this
     */
    public function __construct(SocialSecurityOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Get the show status desc for the order.
     * @return string
     */
    public function getShowStatusAttribute()
    {
        if ($this->order->canceled) {
            return '已取消';
        }
        if (!$this->order->paid) {
            return '待付款';
        }
        //The social security order's date format is like: 2016-2 ~ 2016-4 (cost 3 months)
        //The begin is the 2016-2-1,but the end is 2016-4-30
        $endDateTime = Carbon::createFromTimestamp(strtotime($this->order->end_date))->endOfMonth();
        if ($this->order->paid && time() < $endDateTime->timestamp) {
            return '参保中';
        }
        if ($this->order->paid && time() > $endDateTime->timestamp) {
            return '已完成';
        }
    }

    /**
     * Get the show payment channel of the purchase.
     */
    public function getShowChannelAttribute()
    {
        return PingPPUtil::transforPaymentChannel($this->order->channel);
    }

    /**
     * @return false|string
     */
    public function getShowStartDateAttribute()
    {
        return date('Y年m月', strtotime($this->order->start_date));
    }

    /**
     * @return string
     */
    public function getShowCreateDateAttribute()
    {
        return $this->order->created_at->format('Y-m-d H:i');
    }

    /**
     * @return string
     */
    public function getShowCostMonthsAttribute()
    {
        return $this->order->cost_months . '个月';
    }

    /**
     * @return mixed
     */
    public function getShowHukouTypeAttribute()
    {
        return SocialSecurity::$hukouTypes[$this->order->hukou_type];
    }

    /**
     * @return string
     */
    public function getShowContinueDateAttribute()
    {
        return $this->show_start_date . '-' . $this->show_end_date;
    }

    /**
     * @return false|string
     */
    public function getShowEndDateAttribute()
    {
        return date('Y年m月', strtotime($this->order->end_date));
    }

    /**
     * @return string
     */
    public function getShowSocialSecurityPriceAttribute()
    {
        return "¥ {$this->order->social_security_price} x {$this->show_cost_months}";
    }

    /**
     * @return string
     */
    public function getShowServicePriceAttribute()
    {
        return "¥ {$this->order->service_price} x {$this->show_cost_months}";
    }

    /**
     * @return string
     */
    public function getShowTotalPriceAttribute()
    {
        return "¥ {$this->order->total_price}";
    }

    /**
     * @return string
     */
    public function getShowIsFirstAttribute()
    {
        foreach (static::$socialSecurityTypes as $type) {
            if ($type['value'] == $this->order->is_first) {
                return $type['desc'];
            }
        }
    }

    /**
     * @return string
     */
    public function getShowPensionPriceAttribute()
    {
        return "¥ {$this->order->pension_price} x {$this->show_cost_months}";
    }

    /**
     * @return string
     */
    public function getShowMedicalPriceAttribute()
    {
        return "¥ {$this->order->medical_price} x {$this->show_cost_months}";
    }

    /**
     * @return string
     */
    public function getShowWorkAccidentPriceAttribute()
    {
        return "¥ {$this->order->work_accident_price} x {$this->show_cost_months}";
    }

    /**
     * @return string
     */
    public function getShowLostJobPriceAttribute()
    {
        return "¥ {$this->order->lost_job_price} x {$this->show_cost_months}";
    }

    /**
     * @return string
     */
    public function getShowBornPriceAttribute()
    {
        return "¥ {$this->order->born_price} x {$this->show_cost_months}";
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function __get($key)
    {
        if (method_exists($this, 'get' . Str::studly($key) . 'Attribute')) {
            return $this->{'get' . Str::studly($key) . 'Attribute'}();
        }

        return '';
    }
}