<?php

namespace App\Interfaces;

interface  ReadStatus
{
    const STATUS_WAIT_SEND = 'STATUS_WAIT_SEND';     //通告单没法送
    const STATUS_SENDED    = 'STATUS_SENDED';       //通告单已经发送
    const STATUS_WAIT_READ = 'STATUS_WAIT_READ';     //消息未读
    const STATUS_READED    = 'STATUS_READED';        //消息已读

    //获取某一个消息对于用户的状态
    public function getStatusForUser($userId);
}
