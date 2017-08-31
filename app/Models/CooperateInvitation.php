<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed       type
 * @property Company     company
 * @property TradeScript script
 */
class CooperateInvitation extends Model
{
    const  TYPE_COMPANY = 'COMPANY';
    const  TYPE_SCRIPT  = 'SCRIPT';

    public $guarded = [];

    /**
     * 邀约的类型是否公司
     * @return bool
     */
    public function isCompanyType()
    {
        return $this->type == self::TYPE_COMPANY;
    }

    /**
     * 邀约的类型是否可交易ip
     * @return bool
     */
    public function isScriptType()
    {
        return $this->type == self::TYPE_SCRIPT;
    }

    /**
     *  一个邀约可能有一个公司
     *
     */
    public function company()
    {
        if (!$this->isCompanyType()) {
            return null;
        }

        return Company::find($this->receiver_id);
    }

    /**
     * 一个邀约可能有一个剧本
     */
    public function script()
    {
        if (!$this->isScriptType()) {
            return null;
        }

        return TradeScript::find($this->receiver_id);
    }

    /**
     * ---------------------
     * 监听一下事件
     * ----------------------
     * 1. 创建,创建后给要约的公司或者剧本的联系人发送push
     */
    public static function boot()
    {
        parent::boot();

        //合作要约创建完毕之后要发送puswh
        static::created(function ($coopInvitation) {

            //发送push到受约方联系人
            $coopInvitation->pushReceiverContacts();
        });
    }


    /**
     *
     */
    public function sendSystemToContacts()
    {

    }


    /**
     * 发送push到受约方的联系人
     */
    public function pushReceiverContacts()
    {
        $receiver = null;

        if ($this->isCompanyType()) {
            $receiver = $this->company();
        }

        if ($this->isScriptType()) {
            $receiver = $this->script();
        }

        if (!$receiver) {
            return;
        }

        $notifyMessage = $this->createCooperateInvitationMessage($receiver->contact_user_ids);

        $this->pushSystemMessageToContactsUsers($receiver, $notifyMessage);
    }

    /**
     * 创建合作要约的系统通知消息
     *
     * @param $notifyUserIds
     *
     * @return Message
     */
    private function createCooperateInvitationMessage($notifyUserIds)
    {
        $message = Message::create([
            'from'      => $this->applier_id,
            'type'      => Message::TYPE_SYSTEM,
            'content'   => "您有新的合作邀约",
            'title'     => '南竹通告单',
            'scope'     => 1,
            'notice'    => '',
            'scope_ids' => $notifyUserIds
        ]);

        $message->update(['uri' => env('APP_URL') . "/mobile/messages/{$message->id}"]);

        //创建消息接受接受
        foreach (explode(',', $notifyUserIds) as $userId) {
            MessageReceiver::create([
                'receiver_id' => $userId,
                'message_id'  => $message->id,
                'is_read'     => 0
            ]);
        }

        return $message;
    }

    /**
     * @param $receiver
     * @param $notifyMessage
     */
    private function pushSystemMessageToContactsUsers($receiver, $notifyMessage)
    {
        PushRecord::sendManyByUserIds(
            $receiver->contact_user_ids,
            $notifyMessage->title,
            $notifyMessage->title,
            [
                'uri'  => $notifyMessage->uri,
                'type' => $notifyMessage->type
            ]
        );
    }


}
