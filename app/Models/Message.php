<?php

namespace App\Models;

use App\Interfaces\ReadStatus;
use App\Traits\Message\Getters;
use App\Traits\Message\Relationships;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

/**
 * @property string type
 * @property int    id
 * @property mixed  scope_ids
 * @property User   author
 * @property mixed  title
 * @property mixed  content
 * @property mixed  created_at
 * @property mixed  movie_id
 */
class Message extends Model implements ReadStatus
{
    use Getters;
    use Relationships;

    const TYPE_SYSTEM             = 'SYSTEM';               //系统消息
    const TYPE_BLOG               = 'BLOG';                 //剧本扉页
    const TYPE_JUZU               = 'JUZU';                 //剧组通知
    const TYPE_NOTICE             = 'NOTICE';               //通告单
    const TYPE_PLAN               = 'PLAN';                 //参考大计划
    const TYPE_DAILY_REPORT       = 'DAILY_REPORT';         //场记日报表
    const TYPE_PREVIOUS_PROSPECT  = 'PREVIOUS_PROSPECT';    //Previous prospect type message.
    const TYPE_GROUPUSER_FEEDBACK = 'GROUPUSER_FEEDBACK';    //Groupuser feedback type message.
    const TYPE_CHAT_GROUP         = 'CHATGROUP';            //聊天类型,这个用户前端消息界面显示,并不是message这个表里的
    const TYPE_FRIEND_APPLICATION = 'FRIEND_APPLICATION';   //好友申请类型,这个用户前端消息界面显示,并不是message这个表里的
    const TYPE_FRIEND             = 'FRIEND';               //好友类型
    const SCOPE_ALL               = 0;                      //消息的发送范围,全部(注意不是全部剧组成员,是全部app成员)
    const SCOPE_SOME_BODY         = 1;                      //消息的发送范围,指定接受者
    const HAD_UNDO                = 1;                      //已经撤销
    const NOT_UNDO                = 0;                      //没有撤销

    protected $fillable = [
        "notice_type",
        "notice_id",
        "notice_file_id",
        "filename",
        "movie_id",
        "scope",
        "notice",
        "scope_ids",
        "type",
        "uri",
        "title",
        "content",
        "from",
        "to_user",
        "is_undo",
        "is_delete",
        'plan_id',
        'daily_report_id',
        'previous_prospect_id',
        'groupuser_feedback_id',
        'is_read',
        'undo_operator_id'
    ];

    public $appends = ['total_read_count', 'had_read_count'];

    /**
     * 所有发送消息的类型,并不包含为了用于前端显示而添加的好友申请和环信聊天类型
     */
    public static function allMessageTypes()
    {
        return [
            self::TYPE_BLOG,
            self::TYPE_JUZU,
            self::TYPE_NOTICE,
            self::TYPE_SYSTEM,
        ];
    }

    public static function types()
    {
        return ["SYSTEM" => "系统消息", "BLOG" => "扉页消息", "JUZU" => "剧组通知", "NOTICE" => "通告单"];
    }

    public static function is_undo($notice_id, $notice_file_id = null)
    {
        $messages = Message::where("notice_id", $notice_id)->orderby("id", "desc");

        if ($notice_file_id) {
            $messages = $messages->where("notice_file_id", $notice_file_id);
        }

        $message = $messages->first();

        if ($message && $message->is_undo == 1) {
            return true;
        }

        return false;
    }

    /**
     * @param $planId
     * @return bool
     */
    public static function isMessageUndo($id, $forigenKey = 'plan_id')
    {
        $message = Message::where($forigenKey, $id)->orderby("id", "desc")->first();

        if ($message && $message->is_undo == 1) {
            return true;
        }

        return false;
    }

    /**
     * 安卓的剧组通知,剧本扉页的上传图片
     * 前段上传完oss,直接post图片url
     * @param $files
     * @param $message
     */
    private static function uploadMultipleImages($files, $message)
    {
        foreach ($files as $key => $file) {
            if ($file) {
                $picture             = new Picture;
                $picture->url        = $file;
                $picture->message_id = $message->id;
                $picture->save();
            }
        }
    }

    /**
     * ios上传图片,直接上传file
     * @param $files
     * @param $message
     */
    private static function uploadIosImages($files, $message)
    {
        foreach ($files as $key => $file) {
            if ($file) {
                $picture             = new Picture;
                $picture->url        = Picture::upload("pictures/" . $message->id, $file);
                $picture->message_id = $message->id;
                $picture->save();
            }
        }
    }

    /**
     * 获取推送通知的title,用于前端在app中显示
     * @param $type
     * @return string
     */
    public static function getMessageTitle($type)
    {
        $title = '';
        if ($type == 'juzu') {
            $title = '剧组通知';
        }
        elseif ($type == 'blog') {
            $title = '剧本扉页';
        }
        return urlencode($title);
    }

    public function toArray()
    {
        $array["id"]       = $this->id;
        $array["type"]     = $this->type;
        $array["title"]    = $this->title;
        $array["content"]  = $this->content;
        $array["scope"]    = $this->scope;
        $array["notice"]   = $this->notice;
        $array["filename"] = $this->filename;
        if ($this->type == "SYSTEM") {
            $array["filename"] = $this->title;
        }
        $array["uri"] = $this->uri;
        $carbon       = Carbon::createFromTimestamp(strtotime($this->created_at));
        $carbon->setLocale("zh");
        $array["created_at"] = $carbon->diffForHumans();
        $array["d"]          = $carbon->toDateString();
        $array["pictures"]   = $this->pictures();
        return $array;
    }

    /**
     * 返回通告单的中国日期
     * @return string
     */
    public function getDateAttribute()
    {
        $carbon = Carbon::createFromTimestamp(strtotime($this->created_at));
        $carbon->setLocale("zh");
        return $carbon->toDateString();
    }

    public function pictures()
    {
        $arr      = [];
        $pictures = Picture::where("message_id", $this->id)->get();

        $count = Picture::where("message_id", $this->id)->count();
        if ($count > 0) {
            foreach ($pictures as $picture) {
                $arr[] = $picture->url;
            }
        }
        return $arr;
    }

    /**
     * 创建新的剧组通知
     * @param Request $request
     * @param         $user_id
     * @return static
     */
    public static function buildMessageData(Request $request, $user_id)
    {
        $data = $request->except("pic_url");

        if ($data['type'] == "blog") {
            $data['type'] = "BLOG";
        }

        if ($data['type'] == "juzu") {
            $data['type'] = "JUZU";
        }

        $data['from']      = $user_id;
        $data['scope']     = 1;
        $data["scope_ids"] = implode(',', GroupUser::where("FMOVIE", $data['movie_id'])
                                                   ->selectRaw('distinct FUSER')
                                                   ->lists('FUSER')
                                                   ->all());
        $movie             = Movie::where("FID", $data['movie_id'])->first();
        if ($movie) {
            $data['title'] = $movie->FNAME . ":" . $data['title'];
        }

        return self::create($data);
    }

    /**
     * @param Request $request
     * @param         $message
     */
    public static function uploadImages(Request $request, $message)
    {
        $files = $request->file('pic_url');

        //新版本如果是多张图片上传,会有一个multiple_upload变量为true
        if ($request->input('multiple_upload') == "true") {
            self::uploadMultipleImages($request->input('img_url'), $message);
        }
        elseif (count($files) > 0) {
            self::uploadIosImages($files, $message);
        }
    }

    /**
     * 创建新的剧组通知
     * @param Request $request
     * @param         $userId
     */
    public static function createNewJuzuNofity(Request $request, $userId)
    {
        $message = self::buildMessageData($request, $userId);

        self::uploadImages($request, $message);

        $title = self::getMessageTitle($request->input('type'));

        $message->uri = $request->root() . "/mobile/messages/{$message->id}?title={$title}";

        $message->save();

        $message->push();
    }


    /**
     * 创建有人购买专业版视频的推送消息
     * @param $shootOrder
     * @param $needToNotifyPerson
     * @return static
     */
    public static function createProfessionalVideoPurchasedNofify($shootOrder, $needToNotifyPerson)
    {
        $order_no = strtotime($shootOrder->created_at) . $shootOrder->id;

        $message = self::create([
            'from'      => 0,
            'type'      => self::TYPE_SYSTEM,
            'content'   => "订单号为:{$order_no},下单用户名:{$shootOrder->contact},联系电话{$shootOrder->phone},客户希望录制时间为:{$shootOrder->start_date},请尽快与其联系",
            'title'     => '南竹通告单有新的订单',
            'scope'     => 1,
            'notice'    => '',
            'scope_ids' => implode(',', $needToNotifyPerson),
        ]);

        $message->update(['uri' => env('APP_URL') . "/mobile/messages/{$message->id}"]);

        //创建消息接受接受
        foreach ($needToNotifyPerson as $userId) {
            MessageReceiver::create([
                'receiver_id' => $userId,
                'message_id'  => $message->id,
                'is_read'     => 0
            ]);
        }
        return $message;
    }

    /**
     * 将某人从接受名单中去除
     * @param $userId
     */
    public function removeUserFromReceivers($userId)
    {
        $scopeIdArray = explode(',', $this->scope_ids);

        if (!in_array($userId, $scopeIdArray)) {
            return;
        }

        $scopeIdArray = array_filter($scopeIdArray, function ($scopeId) use ($userId) {
            return $scopeId != $userId;
        });

        $this->update(['scope_ids' => implode(',', $scopeIdArray)]);


        $receivers = $this->receivers()->where('receiver_id', $userId)->get();

        foreach ($receivers as $receiver) {
            $receiver->delete();
        }
    }

    /**
     * 把用户加入消息的接受者
     * @param $userId
     */
    public function addUserToReceivers($userId)
    {
        $scopeIdArray = explode(',', $this->scope_ids);

        if (!in_array($userId, $scopeIdArray)) {
            array_push($scopeIdArray, $userId);
        }

        $this->update(['scope_ids' => implode(',', $scopeIdArray)]);

        //If the message receiver has already been created,don't create again
        if ($this->receivers()->where('receiver_id', $userId)->count() > 0) {
            return;
        }

        MessageReceiver::create([
            'receiver_id' => $userId,
            'message_id'  => $this->id,
            'is_read'     => 0
        ]);
    }

    /**
     * 消息通知的接受者们
     * @return HasMany
     */
    public function receivers()
    {
        return $this->hasMany(MessageReceiver::class, 'message_id', 'id');
    }

    /**
     * 所有已读的接受者们
     * @return mixed
     */
    public function hadReadReceivers()
    {
        return $this->receivers()->where('message_receivers.is_read', 1);
    }

    /**
     * 查询向全体用户发送的通知
     * @param $query
     * @return
     */
    public function scopeToAll($query)
    {
        return $query->where('scope', self::SCOPE_ALL);
    }

    /**
     * 查询发给某一个用用户的
     * @param $query
     * @param $userId
     * @param $type
     */
    public function scopeSendToUserWithType($query, $userId, $type)
    {
        if ($type) {
            $query->where('type', $type);
        }

        return $query->where(function ($query) use ($userId, $type) {
            $query->where('scope_ids', 'like', "%{$userId}%");

            if ($type && $type == self::TYPE_SYSTEM) {
                $query->orWhere('scope', 0);
            }
        });
    }

    /**
     * 通告单类型
     * 每日/预备
     * @param $query
     * @param $type
     * @return
     */
    public function scopeNoticeType($query, $type)
    {
        return $query->where('notice_type', $type);
    }

    /**
     * 消息的阅读率
     */
    public function readRate()
    {
        return $this->hadReadReceivers()->count() . '/' . $this->receivers()->count();
    }

    /**
     * 接受总数
     * @return string
     */
    public function getTotalReadCountAttribute()
    {
        return explode('/', $this->readRate())[1];
    }

    /**
     * 接受分子
     * @return string
     */
    public function getHadReadCountAttribute()
    {
        return explode('/', $this->readRate())[0];
    }

    /**
     * 发送push消息
     * 如果是重新发送push,允许不创建新的接受者
     * @param bool  $createNewReceiver
     * @param array $extra
     * @return bool|void
     */
    public function push($createNewReceiver = true, $extra = [])
    {
        if (!$this->title) {
            $this->title = "新的简历";
            $this->type  = self::TYPE_SYSTEM;
            $this->save();
        }
        $extra['uri']  = $this->uri;
        $extra['type'] = $this->type;
        foreach (explode(",", $this->scope_ids) as $user_id) {
            $user = User::find($user_id);
            if ($createNewReceiver) {
                $receiver              = new MessageReceiver;
                $receiver->receiver_id = $user_id;
                $receiver->message_id  = $this->id;
                $receiver->is_read     = 0;
                $receiver->save();
            }

            if ($user && $user->FALIYUNTOKEN) {
                try {
                    PushRecord::send($user->FALIYUNTOKEN, "南竹通告单+", $this->title, $this->title, $extra, false);
                } catch (\Exception $e) {
                    \Log::info('阿里云推送失败:' . $e->getMessage());
                }
            }
        }
    }


    /**
     * 撤销消息
     * @param null $author
     */
    public function undo($author = null)
    {
        $this->update(['is_undo' => self::HAD_UNDO, 'undo_operator_id' => $author]);

        //删除消息接受者
        if ($author) {
            $this->receivers()->where('receiver_id', '<>', $author)->delete();
        }
        else {
            $this->receivers()->delete();
        }

    }

    /**
     * 没有撤回的通告单
     * @param $query
     * @return
     */
    public function scopeNotUndo($query)
    {
        return $query->where('is_undo', self::NOT_UNDO);
    }

    /**
     * 是否已经撤回
     */
    public function isUndo()
    {
        return $this->is_undo == self::HAD_UNDO;
    }

    /**
     * 获取消息对于某个用户的状态
     * 剧组通知,剧本扉页
     * ----------------------
     * 1. 未读
     * 2. 已读
     * 3. 已撤销
     * @param $userId
     * @return string
     */
    public function getStatusForUser($userId)
    {
        $messageReceiver = MessageReceiver::where(['message_id' => $this->id, 'receiver_id' => $userId])
                                          ->orderBy('created_at', 'desc')
                                          ->first();
        if (!$messageReceiver) {
            return static::STATUS_WAIT_READ;
        }

        return $messageReceiver->hadRead() ? static::STATUS_READED : static::STATUS_WAIT_READ;
    }

    /**
     * 消息的发布者
     */
    public function author()
    {
        return $this->hasOne(User::class, 'FID', 'from');
    }

    /**
     * 更新push的uri地址
     */
    public function updatePushUri()
    {
        $title = static::getMessageTitle($this->type);

        $this->uri = env('APP_URL') . "/mobile/messages/{$this->id}?title={$title}";

        $this->save();
    }

    /**
     * 获取h5接受详情url
     */
    public function getH5ReceiversUrl()
    {
        return env('APP_URL') . "/mobile/messages/{$this->id}/receivers?movie_id={$this->movie_id}&from=native";
    }

    /**
     * 获取h5详情url
     * @param $userId
     * @return string
     */
    public function getH5DetailUrl($userId)
    {
        $title = urlencode($this->isBlog() ? '剧本扉页' : '剧组通知');

        return env('APP_URL') . "/mobile/messages/{$this->id}?user_id={$userId}&type={$this->type}&title={$title}&from=native";
    }

    /**
     * 是否剧本扉页
     * @return bool
     */
    public function isBlog()
    {
        return $this->type == static::TYPE_BLOG;
    }

    /**
     * 是否剧组通知
     * @return bool
     */
    public function isJuzu()
    {
        return $this->type == static::TYPE_JUZU;
    }

    /**
     * 剧组通知剧本扉页还允许后台上传文件
     * 所以可能有关联文件
     */
    public function files()
    {
        return $this->hasMany(MessageFiles::class, 'message_id', 'id');
    }

    /**
     * @param $id
     * @return Message
     */
    public static function find($id)
    {
        return static::where('id', $id)->first();
    }

    public function fillLostReceivers()
    {
        $createdReceiverUserIds = $this->receivers->lists('receiver_id')->all();

        $needToPushReceiverUserIds = explode(',', $this->scope_ids);

        $lostReceiverUserIds = array_diff($needToPushReceiverUserIds, $createdReceiverUserIds);

        foreach ($lostReceiverUserIds as $userId) {
            $messageReceiver              = new MessageReceiver;
            $messageReceiver->receiver_id = $userId;
            $messageReceiver->message_id  = $this->id;
            $messageReceiver->is_read     = 0;
            $messageReceiver->created_at  = $this->created_at;
            $messageReceiver->updated_at  = $this->updated_at;
            $messageReceiver->save();;
        }
    }

    public function getMessageUri()
    {

        $uri       = env('MESSAGE_SEND_URL') . "/mobile/messages/" . $this->id;
        $this->uri = $uri;
        $this->save();

        return $this;
    }

}
