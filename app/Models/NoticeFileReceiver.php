<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeFileReceiver extends Model
{
    public $guarded = [];

    /**
     * 获取上一次接收人数组
     */
    public function getLastReceiversAttribute()
    {
        return explode(',', $this->scope_ids);
    }

}
