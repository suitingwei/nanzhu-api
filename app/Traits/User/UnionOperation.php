<?php

namespace App\Traits\User;

use App\Models\Message;
use App\Models\Union;

trait  UnionOperation
{
    /**
     * @param int $unionId
     * @return boolean
     */
    public function isUnionMember($unionId = Union::GUANGDIAN_UNION_TYPE)
    {
        return Union::where('user_id', $this->FID)->where('union_id', $unionId)->where('type', 'normal')->count() > 0;
    }

    /**
     * @param int $unionId
     * @return int
     */
    public function unionUnReadCount($unionId = Union::GUANGDIAN_UNION_TYPE)
    {
        if (!$this->isUnionMember($unionId)) {
            return 0;
        }

        return Message::where('type', 'like', '%UNION%')
                      ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                      ->where("message_receivers.receiver_id", $this->FID)
                      ->where('message_receivers.is_read', 0)
                      ->count();
    }
}
