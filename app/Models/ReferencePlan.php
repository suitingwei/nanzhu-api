<?php

namespace App\Models;

use App\Interfaces\ReadStatus;
use App\Traits\MessageReadCalator;
use Illuminate\Database\Eloquent\Model;

/**
 * 参考大计划
 * Class ReferencePlan
 * @property mixed  messages
 * @property Movie  movie
 * @property string file_name
 * @property int    id
 * @property string title
 * @property string file_url
 * @package App\Models
 */
class ReferencePlan extends Model implements ReadStatus
{
    //用户某一个大计划设置已读状态
    use MessageReadCalator;

    public $appends = ['is_send', 'read_rate', 'total_read_count', 'had_read_count'];

    /**
     * 一个大计划有很多的消息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'plan_id', 'id')->orderBy('messages.created_at', 'desc');
    }

    /**
     * 一个参考大计划属于一个剧组
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'FID');
    }

    /**
     * 获取大计划对于某一个用户的状态
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getStatusForUser($userId)
    {
        if ($this->isSend() && !Message::isMessageUndo($this->id)) {
            if (MessageReceiver::isModelMessageRead('plan_id',$this->id, $userId)) {
                return static::STATUS_READED;
            }

            return static::STATUS_WAIT_READ;
        }
        return static::STATUS_WAIT_SEND;
    }

    /**
     * 把参考大计划选择的接收人保存
     * 不能使用message_receivers,因为撤销发送会删除接受者
     *
     * @param $receiversIds
     *
     * @internal param $noticeUserIds
     */
    public function rememberReceivers($receiversIds)
    {
        NoticeFileReceiver::create([
            'plan_id'    => $this->id,
            'scope_ids'  => $receiversIds,
            'message_id' => ''
        ]);
    }

    /**
     * 大计划接受者们
     */
    public function fileReceivers()
    {
        return $this->hasMany(NoticeFileReceiver::class, 'plan_id', 'id')
                    ->orderBy('notice_file_receivers.created_at', 'desc');
    }

    /**
     * 获取文件url
     *
     * @param $userId
     *
     * @return string
     */
    public function getFileUrl($userId)
    {
        return env('APP_URL') . "/mobile/plans/{$this->id}?excel_id=0&user_id={$userId}&filename={$this->file_url}";
    }

    /**
     * @return ReferencePlan
     */
    public static function find($id)
    {
        return static::where('id', $id)->first();
    }

}
