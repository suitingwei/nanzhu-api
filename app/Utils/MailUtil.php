<?php

namespace App\Utils;

use App\Models\Purchase;
use App\Models\SocialSecurityOrder;
use Mail;

class MailUtil
{

    public static function alertNewMallPurchase(Purchase $purchase)
    {
	\Log::info('send mail alert '.$purchase);
        Mail::send('new_malls_purchase', ['purchase' => $purchase], function ($message) {
            $message->to('weixiaodao@nanzhuxinyu.com', '您有新的商品订单,请注意查看')->subject("有人下单购买商品了");
            $message->cc('suitingwei@nanzhuxinyu.com');
            $message->from('postmaster@nanzhuxinyu.com', 'nanzhuxinyu');
        });
    }

    public static function alertNewSocialSecurityPurchase(SocialSecurityOrder $socialSecurityOrder)
    {
        Mail::send('new_social_security_order', ['order' => $socialSecurityOrder], function ($message) {
            $message->to('weixiaodao@nanzhuxinyu.com', '您有新的社保订单,请注意查看')->subject("有新的社保订单");
            $message->cc('hanyaoxin@nanzhuxinyu.com');
            $message->cc('xuejin@nanzhuxinyu.com');
            $message->cc('suitingwei@nanzhuxinyu.com');
            $message->from('postmaster@nanzhuxinyu.com', 'nanzhuxinyu');
        });
    }


}
