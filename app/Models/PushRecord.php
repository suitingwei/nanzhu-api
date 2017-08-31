<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PushRecord extends Model
{
    const PUSH_ALL     = true;
    const PUSH_NOT_ALL = false;

    public static function send($aliyuntokens, $title, $body, $summary, $extra, $is_all)
    {
        $status               = Pusher::send($aliyuntokens, $title, $body, $summary, json_encode($extra), $is_all);
        $record               = new PushRecord;
        $record->aliyuntokens = $aliyuntokens;
        $record->title        = $title;
        $record->body         = $body;
        $record->summary      = $summary;
        $record->extra        = json_encode($extra);
        $record->status       = $status->ResponseId;
        $record->save();
    }

    /**
     * 对多个用户发送推送
     *
     * @param array $userIds
     * @param       $title
     * @param       $body
     * @param       $extra
     */
    public static function sendManyByUserIds($userIds, $title, $body, $extra = [])
    {
        if (is_string($userIds)) {
            $userIds = explode(',', $userIds);
        }

        foreach ($userIds as $notifyPersonId) {
            $user = User::find($notifyPersonId);

            if ($user && $user->FALIYUNTOKEN) {
                self::send($user->FALIYUNTOKEN, '南竹通告单', $title, $body, $extra, self::PUSH_NOT_ALL);
            }
        }
    }

    /**
     * @param       $user
     * @param       $title
     * @param       $body
     * @param array $extra
     */
    public static function sendToUser($user, $title, $body, $extra = [])
    {
        if (!($user instanceof User)) {
            $user = User::find($user);
        }

        if ($user && $user->FALIYUNTOKEN) {
            self::send($user->FALIYUNTOKEN, '南竹通告单', $title, $body, $extra, self::PUSH_NOT_ALL);
        }
    }

}
