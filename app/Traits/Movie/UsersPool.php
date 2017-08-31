<?php

namespace App\Traits\Movie;

use App\Managers\Powers\UserPowerManager;
use App\Models\GroupUser;
use App\User;
use Illuminate\Database\Eloquent\Collection;

trait UsersPool
{
    /**
     * 剧组所有用户
     * @return Collection
     */
    public function allUsersInMovie()
    {
        return User::whereIn('FID', $this->allUserIds())->get();
    }

    /**
     * 所有不重复的用户id数组
     * @return array
     */
    public function allUserIds()
    {
        return GroupUser::where([
            't_biz_groupuser.FMOVIE' => $this->FID
        ])->selectRaw('distinct FUSER')->lists('FUSER')->all();
    }

    /**
     * @param null $powerModel
     * @return Collection
     */
    public function allUsersWithPower($powerModel = null)
    {
        $distinctUserIdArray = UserPowerManager::allUserIdsInMovieWithPower($this->FID, $powerModel);

        return User::whereIn('FID', $distinctUserIdArray)->get();
    }

    /**
     * 剧组通讯录里的所有用户
     * 由于现在一个用户可能有多个组员身份.
     * 所以groupUser里可能有重复的user_id
     * 需要过滤.
     * ----------------------------------
     * @return Collection
     */
    public function allUsersInContacts()
    {
        $distinctUserIdArray = GroupUser::where([
            't_biz_groupuser.FMOVIE' => $this->FID,
            't_biz_groupuser.FOPEN'  => GroupUser::PHONE_IN_CONTACTS
        ])->selectRaw('distinct FUSER')->lists('FUSER');

        return User::whereIn('FID', $distinctUserIdArray)->get();
    }

    /**
     * 加入剧组公开电话的所有用户
     * @param bool $onlyId
     * @return Collection
     */
    public function allUsersInPublicContacts($onlyId = false)
    {
        $distinctUserIdArray = GroupUser::where([
            't_biz_groupuser.FMOVIE'     => $this->FID,
            't_biz_groupuser.FPUBLICTEL' => GroupUser::PHONE_PUBLIC
        ])->selectRaw('distinct FUSER')->lists('FUSER');

        if ($onlyId) {
            return $distinctUserIdArray;
        }

        return User::whereIn('FID', $distinctUserIdArray)->get();
    }
}
