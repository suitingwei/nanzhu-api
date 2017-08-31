<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  int receiver_id
 * @property  int message_id
 * @property int  is_read
 */
class MessageReceiver extends Model
{
    const RECEIVER_UNREAD = 0;      //消息未读
    const RECEIVER_READED = 1;      //消息已读

    protected $fillable = ["receiver_id", "message_id", "is_read"];

    /**
     * 一个messagereceiver属于一个用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'FID');
    }

    /**
     * 判断通告单是否阅读
     *
     * @param $notice_id
     * @param $excel_id
     * @param $user_id
     *
     * @return mixed
     */
    public static function is_read($notice_id, $excel_id, $user_id)
    {
        return static::isModelMessageRead('notice_file_id', $excel_id, $user_id);
    }

    public static function read_rate($notice_id, $excel_id)
    {
        $message = Message::where("notice_id", $notice_id)
                          ->where("notice_file_id", $excel_id)
                          ->orderby("id", "desc")
                          ->first();
        if ($message) {
            $total = MessageReceiver::where("message_id", $message->id)->count();
            $read  = MessageReceiver::where("message_id", $message->id)->where("is_read", 1)->count();
            return $read . "/" . $total;
        }

        return 0;
    }

    /**
     *
     * @return boolean
     */
    public function hadRead()
    {
        return $this->is_read == static::RECEIVER_READED;
    }

    /**
     * @return bool
     */
    public function hadNotRead()
    {
        return !$this->hadRead();
    }

    /**
     * 把消息状态置为未读
     */
    public function unRead()
    {
        $this->update(['is_read' => static::RECEIVER_UNREAD]);
    }

    /**
     * Is certain type model's message read.
     *
     * @param $forigenKey
     * @param $forigenId
     * @param $userId
     *
     * @return bool
     */
    public static function isModelMessageRead($forigenKey, $forigenId, $userId)
    {
        $message = Message::where($forigenKey, $forigenId)->orderby("id", "desc")->first();
        if (!$message) {
            return false;
        }

        $messageReceiver = $message->receivers()->where("receiver_id", $userId)->first();
        if (!$messageReceiver) {
            return false;
        }

        return $messageReceiver->hadRead();
    }

}
