<?php

namespace App\Models;

use App\Interfaces\ReadStatus;
use App\Traits\MessageReadCalator;
use Illuminate\Database\Eloquent\Model;

class NoticeExcel extends Model implements ReadStatus
{
    use MessageReadCalator;

    protected $table = "t_biz_noticeexcelsinfo";

    protected $fillable = ['FID,FFILEADD,FNUMBER,FORSEND'];

    public $timestamps = false;

    public $appends = ['group_name', 'read_rate', 'total_read_count', 'had_read_count'];

    public $hidden = ['FORSEND', 'FSENDDATE', 'FGROUP'];


    /**
     * @param $id
     *
     * @return NoticeExcel
     */
    public static function find($id)
    {
        return static::where('FID', $id)->first();
    }

    /**
     * 一个通告单文件对应一个通告单
     */
    public function notice()
    {
        return $this->belongsTo(Notice::class, 'FNOTICEEXCELID', 'FID');
    }

    /**
     * 通告单文件接受者们
     */
    public function fileReceivers()
    {
        return $this->hasMany(NoticeFileReceiver::class, 'notice_file_id', 'FID')
                    ->orderBy('notice_file_receivers.created_at', 'desc');
    }

    /**
     * 判断文件是否发送
     * @return bool
     */
    public function is_send()
    {
        return Message::where("notice_file_id", $this->FID)->count() > 0;
    }

    /**
     * 一个通告单文件可以发送多条消息
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'notice_file_id', 'FID')
                    ->where('messages.notice_id', $this->notice->FID)
                    ->orderBy('id', 'desc');
    }

    /**
     * 获取通告单文件所属的组别
     * A/B/C
     */
    public function getGroupNameAttribute()
    {
        if (!empty($this->custom_group_name)) {
            return $this->custom_group_name;
        }

        return explode(',', Notice::NOTICE_GROUPS)[$this->FNUMBER - 1] . '组通告单';
    }

    /**
     * 返回通告单接受比率
     * @return int
     */
    public function getReadRateAttribute()
    {
        return $this->readRate();
    }

    /**
     *  接受总数
     */
    public function getTotalReadCountAttribute()
    {
        return explode('/', $this->readRate())[1];
    }

    /**
     * 接受分子
     * @return mixed
     */
    public function getHadReadCountAttribute()
    {
        return explode('/', $this->readRate())[0];
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
        return env('APP_URL') . "/mobile/notices/{$this->notice->FID}?excel_id={$this->FID}&user_id={$userId}&filename={$this->FFILEADD}";
    }

    /**
     * 通告单文件的阅读比例
     */
    public function readRate()
    {
        static $movieMembersCount = null;

        $isDaily = $this->notice->isDaily();

        //如果发送文件了,获取message的阅读比例
        if ($this->messages->count() > 0) {
            $readRate = $this->messages()->first()->readRate();

            //如果通告单被撤销了,分母就成0了
            if ($isDaily && $readRate === '0/0') {
                return '0/' . $this->notice->movie->allMembersCount();
            }

            return $readRate;
        }

        //如果没法送,预备通告单这种需要选择接收人的不管
        //每日通告单返回的阅读比例,把分母总数返回
        if (!$isDaily) {
            return '0/0';
        }

        if (!$movieMembersCount) {
            $movieMembersCount = $this->notice->movie->allMembersCount();
        }

        return "0/{$movieMembersCount}";
    }

    /**
     * 获取通告单文件的发送状态
     * 1.已发送
     * 2.待发送
     * 3.已读
     * 4.未读
     *
     * @param $userId
     *
     * @return string
     */
    public function getStatusForUser($userId)
    {
        if ($this->is_send() && !Message::is_undo($this->notice->FID, $this->FID)) {

            if (MessageReceiver::is_read($this->notice->FID, $this->FID, $userId)) {
                return self::STATUS_READED;
            }
            return self::STATUS_WAIT_READ;
        }

        return self::STATUS_WAIT_SEND;
    }

    /**
     * 获取h5接受详情url
     */
    public function getH5ReceiversUrl()
    {
        return env('APP_URL') . "/mobile/notices/{$this->notice->FID}/receivers?movie_id={$this->notice->FMOVIEID}&excel_id={$this->FID}";
    }

    /**
     * 把通告单文件选择的接收人保存
     * 不能使用message_receivers,因为撤销发送会删除接受者
     *
     * @param $receiversIds
     */
    public function rememberReceivers($receiversIds)
    {
        NoticeFileReceiver::create([
            'notice_id'      => $this->notice->FID,
            'notice_file_id' => $this->FID,
            'scope_ids'      => $receiversIds,
            'message_id'     => ''
        ]);
    }


}
