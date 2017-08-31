<?php

namespace App\Models;

use App\Managers\Behaviors\GroupUserBehaviorManager;
use App\Managers\GroupPowerManager;
use App\Traits\GroupUser\Compatibility;
use App\Traits\GroupUser\ContactsOperation;
use App\Traits\GroupUser\Getters;
use App\Traits\GroupUser\RelationShips;
use App\Traits\GroupUser\RoleOperation;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed FOPEN       剧组通讯录
 * @property mixed FPUBLICTEl  公开电话
 * @property User  user
 * @property Group group
 * @property mixed FGROUPUSERROLE
 * @property mixed FID
 * @property Movie movie
 * @property mixed FREMARK
 * @property mixed FMOVIE
 * @property mixed FGROUP
 * @property mixed room
 */
class GroupUser extends Model
{

    /**
     * Getters for model.
     */
    use Getters;

    /**
     * Judge whether the groupuser is director/changji/tongchou/...
     */
    use RoleOperation;

    /**
     * All relationships,such as movie,user,groupusers,share-phones.
     */
    use RelationShips;

    /**
     * Operations about contacts,such as join contacts,exit contacts,or
     * set phone private/public.
     */
    use ContactsOperation;

    const PHONE_PUBLIC          = 10;   //电话加入公开电话
    const PHONE_PRIVATE         = 20;   //电话没有加入公开电话
    const PHONE_IN_CONTACTS     = 10;   //电话加入剧组通讯录
    const PHONE_NOT_IN_CONTACTS = 20;   //电话没有加入剧组通讯录
    const PHONE_OPENED          = 1;    //共享电话是否被勾选
    const PHONE_NOT_OPENED      = 0;    //共享电话没有被勾选
    const ROLE_ADMIN            = 10;   //是某一个电影的最高权限者

    public $timestamps = false;
    protected $table = "t_biz_groupuser";
    protected $guarded = [];

    /**
     * @param $id
     *
     * @return GroupUser
     */
    public static function find($id)
    {
        return static::where('FID', $id)->first();
    }

    /**
     * 因为不能使用主键primarykey,会影响老代码
     * 原有delete方法无法正常执行
     * 为了方便使用delete,只能替换成该方法
     */
    public function remove()
    {
        DB::table($this->table)->where(['FID' => $this->FID])->delete();
    }

}

