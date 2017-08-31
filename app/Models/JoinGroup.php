<?php

namespace App\Models;

use App\Managers\Behaviors\JoinOtherGroupBehaviorManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\User user
 * @property  Group    group
 * @property mixed     group_id
 * @property mixed     movie_id
 */
class JoinGroup extends Model
{
    /**
     * 申请进组状态
     */
    const STATUS_CAN_JOIN     = 'CAN_JOIN';
    const STATUS_WAIT_AUDIT   = 'WAIT_AUDIT';
    const STATUS_JOIN_SUCCESS = 'JOIN_SUCCESS';
    const STATUS_JOIN_FAIL    = 'JOIN_FAIL';

    public $table = 'join_group';

    public $fillable = ['id', 'user_id', 'movie_id', 'group_id', 'audit_status', 'audit_user_id', 'audit_at'];

    /**
     * 申请进组没有被处理
     */
    public function hadNotHandled()
    {
        return $this->audit_status == self::STATUS_WAIT_AUDIT;
    }

    /**
     * 创建新的入组申请
     *
     * @param              $userId
     * @param array|string $groupIds
     * @param              $movieId
     *
     * @internal param Request $request
     */
    public static function createNewJoin($userId, $groupIds, $movieId)
    {
        if (!is_array($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        $joiner    = User::find($userId);
        $joinMovie = Movie::find($movieId);

        foreach ($groupIds as $groupId) {
            self::create([
                'user_id'      => $userId,
                'movie_id'     => $movieId,
                'group_id'     => $groupId,
                'audit_status' => self::STATUS_WAIT_AUDIT
            ]);
            $group = Group::find($groupId);
            if ($group && ($leaderUser = $group->leadUser())) {
                $pushString = "{$joinMovie->FNAME}:{$joiner->FNAME}申请加入{$group->FNAME}部门";
                PushRecord::sendToUser($leaderUser, $pushString, $pushString, ['uri' => '', 'type' => 'JOIN_GROUP']);
            }
        }
    }

    /**
     * 一个申请由一个用户创建
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'FID');
    }

    /**
     * 申请的组
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'FID');
    }

    /**
     * 申请的剧
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'FID');
    }

    /**
     * 批准进组申请
     *
     * @param $userId
     */
    public function approvedByUser($userId)
    {
        self::where(['user_id' => $this->user_id, 'group_id' => $this->group_id])->update([
            'audit_status'  => self::STATUS_JOIN_SUCCESS,
            'audit_user_id' => $userId,
            'audit_at'      => Carbon::now()
        ]);

        //把这个用户添加到这个组
        (new JoinOtherGroupBehaviorManager($this->user))->joinGroup($this->group_id);
    }

    /**
     * 批准进组申请
     *
     * @param $userId
     */
    public function declinedByUser($userId)
    {
        //由于ios缓存导致的数据重复,所以申请进组会有多条重复数据
        static::where(['user_id' => $this->user_id, 'group_id' => $this->group_id])->update([
            'audit_status'  => static::STATUS_JOIN_FAIL,
            'audit_user_id' => $userId,
            'audit_at'      => Carbon::now()
        ]);
    }

}
