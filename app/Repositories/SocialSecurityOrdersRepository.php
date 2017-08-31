<?php
namespace App\Repositories;

use App\Models\SocialSecurity;
use App\Models\SocialSecurityOrder;
use App\User;
use Carbon\Carbon;

/**
 * Class SocialSecurityOrdersRepository
 * @package App\Repositories
 */
class SocialSecurityOrdersRepository extends Repository
{
    /**
     * @return mixed
     */
    public function fetchListForApp()
    {
        $user = User::find($this->request->input('user_id'));

        return $user->socialSecurityOrders()
                    ->orderBy('social_security_orders.created_at', 'desc')
                    ->paginate(20);
    }

    /**
     * @return mixed
     */
    public function fetchListForOp()
    {
        return SocialSecurityOrder::orderBy('created_at', 'desc')
                                  ->where('paid', true)
                                  ->where('canceled', false)
                                  ->paginate(50);
    }

    /**
     * @param $orderId
     *
     * @return SocialSecurityOrder
     */
    public function continuePay($orderId)
    {
        $socialSecurity = $this->validateContinuePayData($orderId);
        return $this->createSocialSecurityOrder($socialSecurity);
    }

    /**
     * @param $orderId
     *
     * @return mixed
     * @throws \Exception
     */
    private function validateContinuePayData($orderId)
    {
        $order = SocialSecurityOrder::find($orderId);
        if (!$order || ($order->creator_id != $this->request->input('user_id'))) {
            throw new \Exception('保单不存在或无权限操作');
        }

        $socialSecurity = SocialSecurity::find($this->request->input('social_security_id'));
        if (!$socialSecurity) {
            throw new \Exception('社保信息不存在');
        }
        if (!$order->paid || $order->canceled) {
            throw new \Exception('订单不可续交');
        }

        $this->validateContinueStartDate();

        return $socialSecurity;
    }

    /**
     * @param SocialSecurity $socialSecurity
     *
     * @return SocialSecurityOrder
     */
    private function createSocialSecurityOrder(SocialSecurity $socialSecurity)
    {
        $attributes = collect([
            'user_phone'         => $socialSecurity->user_phone,
            'hukou_address'      => $socialSecurity->hukou_address,
            'minority'           => $socialSecurity->minority,
            'is_first'           => 0,
            'bank'               => $socialSecurity->bank,
            'bank_card_number'   => $socialSecurity->bank_card_number,
            'creator_id'         => $this->request->input('user_id'),
            'user_name'          => $this->request->input('user_name'),
            'hukou_type'         => $this->request->input('hukou_type'),
            'start_date'         => $this->request->input('start_date'),
            'cost_months'        => $this->request->input('cost_months'),
            'base_number'        => $this->request->input('base_number'),
            'social_security_id' => $this->request->input('social_security_id'),
        ]);

        return SocialSecurityOrder::createOrFail($attributes);
    }

    /**
     * @throws \Exception
     *
     */
    private function validateContinueStartDate()
    {
        $lastOrder = SocialSecurityOrder::where([
            'creator_id'         => $this->request->input('user_id'),
            'social_security_id' => $this->request->input('social_security_id'),
            'paid'               => true,
            'canceled'           => false
        ])->orderBy('created_at', 'desc')->first();

        \Log::info('last order is ' . $lastOrder);
        $lastOrderEndDate      = Carbon::createFromTimestamp(strtotime($lastOrder->end_date));
        $currentOrderStartDate = Carbon::createFromTimestamp(strtotime($this->request->input('start_date')));

        if ($currentOrderStartDate->lte($lastOrderEndDate)) {
            throw new \Exception('续交订单开始日期不合法');
        }
    }
}
