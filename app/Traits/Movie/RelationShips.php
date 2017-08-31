<?php
namespace App\Traits\Movie;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\Movie;
use App\Models\ProgressTotalData;
use App\Models\ReferencePlan;
use App\User;

trait RelationShips
{
    /**
     * 剧组有多个部门
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'FMOVIE', 'FID');
    }

    /**
     * 一个剧组有很多参考大计划
     */
    public function referencePlans()
    {
        return $this->hasMany(ReferencePlan::class, 'movie_id', 'FID');
    }

    /**
     * The movie's admin user.
     *
     * @return User
     */
    public function admin()
    {
        $admin = GroupUser::where(['FMOVIE' => $this->FID, 'FGROUPUSERROLE' => Movie::ROLE_ADMIN])->first();

        return $admin ? $admin->user : null;
    }

    /**
     * 获取剧组的所有部门长
     */
    public function leaders()
    {
        $distinctLeaderIds = $this->groups()->where('FLEADERID', '!=', '')
                                  ->whereNotNull('FLEADERID')
                                  ->selectRaw('distinct FLEADERID')
                                  ->lists('FLEADERID');

        return User::whereIn('FID', $distinctLeaderIds->all())->get();
    }

    /**
     * 一个剧组有多个消息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'movie_id', 'FID');
    }

    /**
     * 每一个剧组有一个总数据
     */
    public function totalData()
    {
        return $this->hasOne(ProgressTotalData::class, 'FMOVIEID', 'FID');
    }
}
