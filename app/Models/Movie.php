<?php

namespace App\Models;

use App\Traits\Movie\Getters;
use App\Traits\Movie\RelationShips;
use App\Traits\Movie\Types;
use App\Traits\Movie\UsersPool;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Movie
 * @property int    FID
 * @property string FNAME
 * @property mixed  FISOROPEN
 * @property mixed  FNEWUSER
 * @property int    shootend
 * @property int    is_groupuser_feedback_open
 * @package App\Models
 */
class Movie extends Model
{
    use Getters;
    use Types;
    use UsersPool;
    use RelationShips;

    const ROLE_ADMIN       = 10;       //最高管理者
    const ROLE_COMMON_USER = 40;      //普通用户
    const NOT_SHOOT_END    = 0;
    const SHOOT_END        = 1;
    const STATUS_OPEN      = 1;

    public $appends = ['progress'];

    public $hidden = [
        'FFOOTER',
        'FHEADER',
        'FCODE',
        'FEDITUSEr',
        'FLASTSYNCDATE',
        'FPICBYTE',
        'FNOTICENUM',
        'FTONGZHINUM',
        'FPUBLICPHONENUM',
        'FCONTECTPHONENUM',
        'FDEPNUM',
        'FDAILYPROGRESSDATANUM',
        'FPROGRESSGLOBALNUM',
        'FPROGRESSCHARTNUM',
        'FJUZUNUM',
        'FCONTACTTELPOWERNUM',
        'FCONTACTTELPOWERIFNUM',
        'FPROGRESSPOWERNUM',
        'FADMINPOWERNUM',
        'FGROUPNUM',
        'FPIC'
    ];

    /**
     * The essentail departments which a movie needs.
     * The sort attribute will decide the order in the groups lists.
     * @var array
     */
    public static $essentialDepartments = [
        ['name' => '制片', 'type' => Group::TYPE_ZHI_PIAN, 'sort' => 4],
        ['name' => '统筹', 'type' => Group::TYPE_TONG_CHOU, 'sort' => 3],
        ['name' => '导演', 'type' => Group::TYPE_DIRECTOR, 'sort' => 2],
        ['name' => '场记', 'type' => Group::TYPE_CHANGJI, 'sort' => 1]
    ];

    protected $table = 't_biz_movie';

    protected $fillable = [
        'FID',
        'FNAME',
        'FNEWUSER',
        'FNEWDATE',
        'FTYPE',
        'FSTARTDATE',
        'FENDDATE',
        'FPASSWORD',
        'FISOROPEN',
        "chupinfang",
        "zhizuofang",
        'hx_group_id'
    ];

    public $timestamps = false;

    public $incrementing = false;

    protected $lastProgessDay = null;

    /**
     * 一个剧所有人
     * @param bool $unique
     * @return
     */
    public function allMembersCount($unique = true)
    {
        $groupUsers = GroupUser::where('FMOVIE', $this->FID)->lists('FUSER');

        if ($unique) {
            return $groupUsers->unique()->count();
        }

        return $groupUsers->count();
    }

    /**
     * 之前剧组的每日数据是否都填写了(0)也算有
     * 1.上一个拍摄日期等于昨天
     * 2.上一个拍摄日期大于昨天
     * @param $currentDay
     * @return bool
     */
    public function isAllPastDaysProgressDataFullfiled($currentDay)
    {
        $currentDay = Carbon::createFromTimestamp(strtotime($currentDay));

        $lastProgressedDay = DB::table('t_biz_progressdailydata')->select('FDATE')
                               ->where('FMOVIEID', $this->FID)
                               ->orderBy('FDATE', 'desc')
                               ->first();
        //如果之前没有拍过
        if (!$lastProgressedDay) {
            //判断今天是不是拍摄日期
            $startDate = DB::table("t_biz_progresstotaldata")->where("FMOVIEID", $this->FID)->first()->FSTARTDATE;

            return $currentDay->timestamp == strtotime($startDate);
        }

        $lastProgressedDay = Carbon::createFromTimestamp(strtotime($lastProgressedDay->FDATE));

        $lastDay = $currentDay->subDay();

        return ($lastDay->lte($lastProgressedDay));
    }

    /**
     * 获取上一次记录每日数据的日期
     * 1.如果之前拍过,返回最后一天拍摄日期
     * 2.如果没有拍过,返回开拍日期
     */
    public function getNeedToProgressDay()
    {
        $lastProgressedDay = DB::table('t_biz_progressdailydata')->select('FDATE')
                               ->where('FMOVIEID', $this->FID)
                               ->orderBy('FDATE', 'desc')
                               ->first();
        if (!$lastProgressedDay) {
            $totaldata = DB::table("t_biz_progresstotaldata")->where("FMOVIEID", $this->FID)->first();
            return Carbon::createFromTimestamp(strtotime($totaldata->FSTARTDATE))->toDateString();
        }

        return Carbon::createFromTimestamp(strtotime($lastProgressedDay->FDATE))->addDay()->toDateString();
    }

    /**
     * 创建环信聊天室
     * @param User $user
     */
    public function createChatGroupWithOwner(User $user)
    {
        $returnData = (new EaseUser())->createMovieChatGroup($user->FID, $this->FID, $this->FNAME);

        self::where(['FID' => $this->FID])->update(['hx_group_id' => $returnData['data']['groupid']]);
    }

    /**
     * 将用户加入剧组通告单的接受者里
     * @param $userId
     * @param $messageTypes
     * @return mixed
     */
    public function addUserToMessageReceiver($userId, $messageTypes)
    {
        $unDeletedMessages = $this->messages()
                                  ->whereIn('type', (array)$messageTypes)
                                  ->where(['messages.is_delete' => 0, 'messages.is_undo' => 0,])
                                  ->get();

        foreach ($unDeletedMessages as $noticeMsg) {
            $noticeMsg->addUserToReceivers($userId);
        }
    }

    public function addUserToDailyNoticeMessageReceiver($userId)
    {
        $unDeletedMessages = $this->messages()
                                  ->where('type', Message::TYPE_NOTICE)
                                  ->where('notice_type', Notice::TYPE_DAILY)
                                  ->where(['messages.is_delete' => 0, 'messages.is_undo' => 0,])
                                  ->get();

        foreach ($unDeletedMessages as $noticeMsg) {
            $noticeMsg->addUserToReceivers($userId);
        }
    }

    /**
     * 没有杀青的剧组
     * @param $query
     * @return
     */
    public function scopeNotEnd($query)
    {
        return $query->where('shootend', static::NOT_SHOOT_END);
    }

    /**
     * @param $id
     * @return Movie
     */
    public static function find($id)
    {
        return static:: where('FID', $id)->first();
    }

    /**
     * Is movie closed for joining.
     * @return bool
     */
    public function closed()
    {
        return $this->FISOROPEN != Movie::STATUS_OPEN;
    }

    /**
     * Is movie shoot ended.
     * @return boolean
     */
    public function shootEnded()
    {
        return $this->shootend == static::SHOOT_END;
    }

    /**
     * Get a movie's certain type group.
     * @param int $groupType
     * @return null
     */
    public function getCertainTypeGroup($groupType = Group::TYPE_ZHI_PIAN)
    {
        if (!in_array($groupType, Group::$types)) {
            return null;
        }
        return $this->groups()->where('FGROUPTYPE', $groupType)->first();
    }
}



