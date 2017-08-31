<?php

namespace App\Models;

use DB;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    const DEFAULT_SHARE_COVER_URL = 'http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png';

    const GUANGDIAN_UNION_TYPE =16;

    /**
     *
     */
    protected $guarded = [];

    public function getShortIntroductionAttribute()
    {
        return rtrim(mb_strimwidth($this->plain_content, 0, 60, '...'));
    }

    /**
     * 一个可交易IP可能有多个图片
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class, 'message_id', 'id');
    }

    /**
     * 获取微信分享json
     */
    public function getWechatShareJsonAttribute()
    {
        return json_encode([
            'title'   => '公司详情',
            'cover'   => self::DEFAULT_SHARE_COVER_URL,
            'content' => '南竹通告单',
            'url'     => $this->getScriptShowPageUrl()
        ]);
    }

    /**
     * 获取公司的详情界面url
     * @return string
     */
    public function getScriptShowPageUrl()
    {
        return env('APP_URL') . '/mobile/trade-resources/scripts/' . $this->id . '?from=singlemessage';
    }

    /**
     * 获取介绍的markdown数据
     */
    public function getMarkdownContentAttribute()
    {
        return Markdown::convertToHtml($this->content);
    }

    public static function setThisDigitalUserId($thisDigital, $userId)
    {
        /*$allDigitals   = Union::lists('digitals')->all();
        $thisUserId    = DB::table('t_sys_user')->where('FPHONE',$thisDigital)->first();
        if($allDigitals){
            if (in_array($thisDigital, $allDigitals)) {
                DB::table('unions')->where('digitals',$thisDigital)->update('user_id',$thisUserId);
            }
        }*/

        $unionMember = Union::where('digitals', $thisDigital)->first();
        if (!$unionMember) {
            return;
        }
        if ($unionMember->user_id != 0) {
            return;
        }

        $unionMember->update(['user_id' => $userId]);
        $fid    = GroupUser::orderBy('FID', 'desc')->first();
        $newfid = $fid->FID + 1;

        $messages = Message::where('union_id', $unionMember->union_id)->get();
        foreach ($messages as $message) {
            $message->addUserToReceivers($userId);
        }

        $dataForG['FUNION'] = $unionMember->union_id;
        $dataForG['FUSER']  = $userId;
        $dataForG['FID']    = $newfid;
        GroupUser::create($dataForG);
        \Log::info('+++tohere' . $unionMember->union_id . "++++++" . $userId);
        DB::update('update profiles set union_type=?,union_id=? where user_id=?',
            ['normal', $unionMember->union_id, $userId]);
        \Log::info('+++finish');
    }

    public static function boot()
    {
        parent::boot();
        static::updated(function (Union $union) {
            if ($union->isDirty('user_id') && $union->user_id) {


                $messages = Message::where('union_id', $union->union_id)->get();
                foreach ($messages as $message) {
                    $message->addUserToReceivers($union->user_id);
                }

                $fid    = GroupUser::orderBy('FID', 'desc')->first();
                $newfid = $fid->FID + 1;


                $dataForG['FUNION'] = $union->union_id;
                $dataForG['FUSER']  = $union->user_id;
                $dataForG['FID']    = $newfid;
                GroupUser::create($dataForG);

                DB::update('update profiles set union_type=?,union_id=? where user_id=?',
                    ['normal', $union->union_id, $union->user_id]);


            }
        });
    }

    /**
     * @param $userId
     * @return int
     */
    public static function getMessageIsNotReadTotal($userId)
    {
        return Message::where('type', 'like', '%UNION%')
                      ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                      ->where("message_receivers.receiver_id", $userId)
                      ->where('message_receivers.is_read', 0)
                      ->count();
    }
}
