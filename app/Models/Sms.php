<?php

namespace App\Models;


class Sms
{
    //邀请短信模板
    public static $inviteMsgTemplateId = '';

    public static function send($to, $datas, $tempId)
    {
        require_once app_path('Venders/sms/CCPRestSDK.php');
        $accountSid   = 'aaf98f8952a572be0152a5c50d1500d2';
        $accountToken = '81c49a01502d4b00bd901623f2893613';
        $appId        = '8a48b55152a56fc20152a5cb7cc80118';
        $serverIP     = 'app.cloopen.com';
        $serverPort   = '8883';
        $softVersion  = '2013-12-26';

        $rest = new \REST($serverIP, $serverPort, $softVersion);
        $rest->setAccount($accountSid, $accountToken);
        $rest->setAppId($appId);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($to, $datas, $tempId);
        if ($result == null) {
            echo "result error!";
        }
        if ($result->statusCode != 0) {
            //echo "error code :" . $result->statusCode . "<br>";
            //echo "error msg :" . $result->statusMsg . "<br>";
            //TODO 添加错误处理逻辑
        } else {
            //echo "Sendind TemplateSMS success!<br/>";
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
            //echo "dateCreated:".$smsmessage->dateCreated."<br/>";
            //echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
            //TODO 添加成功处理逻辑
        }
        return $result;
    }

    /**
     * 向指定手机号发送邀请短信
     *
     * @param $receiverPhone
     * @param $data
     */
    public static function sendInviteMsg($receiverPhone, $data = [])
    {
        self::send($receiverPhone, $data, self::$inviteMsgTemplateId);

        SmsRecord::create([
            'phone'       => $receiverPhone,
            'code'        => '',
            'valid_time'  => '',
            'status'      => '',
            'template_id' => self::$inviteMsgTemplateId
        ]);
    }

    /**
     * @param Purchase $purchase
     *
     * @return mixed|\SimpleXMLElement|\内容数据
     */
    public static function sendToMovieClothServieMan(Purchase $purchase)
    {
        return self::send(env('SponsorPhone'), [$purchase->user->hx_name, $purchase->serial_number], 165251);
    }

    /**
     * @param Purchase $purchase
     *
     * @return mixed|\SimpleXMLElement|\内容数据
     */
    public static function sendPurchaseShippedToCustomer(Purchase $purchase)
    {
        return self::send($purchase->receivePhone->receiver_phone, [
            $purchase->serial_number,
            $purchase->updated_at->year,
            $purchase->updated_at->month,
            $purchase->updated_at->day,
            $purchase->express_number
        ], 165221);
    }
}
