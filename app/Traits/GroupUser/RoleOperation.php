<?php
namespace App\Traits\GroupUser;

use App\Models\Group;
use App\Models\Movie;
use App\Models\ProgressPower;
use Illuminate\Support\Facades\DB;

trait  RoleOperation
{
    /**
     * Judge whether a user has a zhipian role in movie.
     *
     * @param $movie_id
     * @param $user_id
     *
     * @return bool
     */
    public static function is_tongchou($movie_id, $user_id)
    {
        return static::isCharacter('统筹', $movie_id, $user_id);
    }

    /**
     * Judge whether a user has a zhipian role in movie.
     *
     * @param $movie_id
     * @param $user_id
     *
     * @return bool
     */
    public static function is_zhipian($movie_id, $user_id)
    {
        return static::isCharacter('制片', $movie_id, $user_id);
    }

    /**
     * Judge whether a user has a certain role in movie.
     *
     * @param $movie_id
     * @param $user_id
     *
     * @return bool
     */
    public static function is_director($movie_id, $user_id)
    {
        return static::isCharacter('导演', $movie_id, $user_id);
    }

    /**
     * Judge whether a user has a changji role in movie.
     *
     * @param $movie_id
     * @param $user_id
     *
     * @return bool
     */
    public static function is_changji($movie_id, $user_id)
    {
        return static::isCharacter('场记', $movie_id, $user_id);
    }

    /**
     *  Judge wheather  a user has a role in movie.
     *
     * @param $role
     * @param $movieId
     * @param $userId
     *
     * @return boolean
     */
    public static function isCharacter($role, $movieId, $userId)
    {
        return DB::table("t_biz_groupuser")
                 ->leftJoin('t_biz_group', 't_biz_groupuser.FGROUP', '=', 't_biz_group.FID')
                 ->where("t_biz_group.FMOVIE", $movieId)
                 ->where("t_biz_group.FNAME", "like", "{$role}%")
                 ->where("t_biz_groupuser.FUSER", $userId)
                 ->count() > 0;
    }

    /**
     * Judge whether the groupuser is in tongchou group.
     * @return boolean
     */
    public function isTongChou()
    {
        return mb_strpos($this->group->FNAME, '统筹') !== false;
    }

    /**
     * Judge whether a user is changji department.
     * @return bool
     */
    public function isChangJi()
    {
        return mb_strpos($this->group->FNAME, '场记') !== false;
    }

    /**
     * Judge whether the groupuser is in zhipian group.
     * @return boolean
     */
    public function isZhiPian()
    {
        return mb_strpos($this->group->FNAME, '制片') !== false;
    }

    /**
     * Judge whether the groupuser is in director group.
     * @return boolean
     */
    public function isDirector()
    {
        return mb_strpos($this->group->FNAME, '导演') !== false;
    }

    /**
     * Judge whether the groupuser is not the admin.
     * @return  boolean
     */
    public function isNotAdmin()
    {
        return !$this->isAdmin();
    }

    /**
     * Judge whether the group user is the admin.
     * @return  boolean
     */
    public function isAdmin()
    {
        return $this->FGROUPUSERROLE == Movie::ROLE_ADMIN;
    }

    /**
     * Judge whether the group user is the leader of the group.
     * @return  boolean
     */
    public function isLeader()
    {
        return $this->user->isLeaderOfGroup($this->group);
    }

    /**
     * Assign the group user to admin.
     */
    public function assignAdminPower()
    {
        DB::table($this->table)->where(['FID' => $this->FID])->update(['FGROUPUSERROLE' => Movie::ROLE_ADMIN]);
    }

    /**
     * Assign the group user to common user.
     */
    public function retrieveAdminPower()
    {
        DB::table($this->table)->where(['FID' => $this->FID])->update(['FGROUPUSERROLE' => Movie::ROLE_COMMON_USER]);
    }

    /**
     * groupuser是否具有对某个movie的进度权限
     *
     * @param $movieId
     *
     * @return bool
     */
    public function notHavProgressPowerInMovie($movieId)
    {
        return ProgressPower::where(['FMOVIEID' => $movieId, 'FGROUPUSERID' => $this->FID])->count() <= 0;
    }

    /**
     * Remove the group users' leader power if she/he owns.
     */
    public function removeLeaderPower()
    {
        Group::where('FID', $this->FGROUP)->update(['FLEADERID' => null]);
    }
}
