<?php
namespace App\Traits\GroupUser;

use App\Models\GroupUser;
use App\Models\SparePhone;
use DB;
use Illuminate\Database\Eloquent\Collection;

trait ContactsOperation
{
    /**
     * 将电话设置成私有
     */
    public function setPhonePrivate()
    {
        DB::table($this->table)->where(['FID' => $this->FID])->update(['FPUBLICTEL' => GroupUser::PHONE_PRIVATE]);
    }

    /**
     * 将电话设置成公开
     */
    public function setPhonePublic()
    {
        DB::table($this->table)->where(['FID' => $this->FID])->update(['FPUBLICTEL' => GroupUser::PHONE_PUBLIC]);
    }

    /**
     * 组员是否加入剧组通讯录
     *
     * @return boolean
     */
    public function hadJoinedContacts()
    {
        if (is_null($this->FOPEN)) {
            return false;
        }

        return $this->FOPEN == GroupUser::PHONE_IN_CONTACTS;
    }

    /**
     * 组员是否加入公开电话
     *
     * @return boolean
     */
    public function hadJoinedPublicContacts()
    {
        if (is_null($this->FPUBLICTEL)) {
            return false;
        }

        return $this->FPUBLICTEL == GroupUser::PHONE_PUBLIC;
    }

    /**
     * 把部门成员添加到剧组通讯录
     */
    public function joinContacts()
    {
        DB::table($this->table)->where(['FID' => $this->FID])->update(['FOPEN' => GroupUser::PHONE_IN_CONTACTS]);
    }

    /**
     * 把部门成员从剧组通讯录移除
     */
    public function removeContacts()
    {
        DB::table($this->table)
          ->where(['FID' => $this->FID])
          ->update([
              'FOPEN'      => GroupUser::PHONE_NOT_IN_CONTACTS,
              'FPUBLICTEL' => GroupUser::PHONE_PRIVATE
          ]);
    }

    /**
     * 电话是否公开
     */
    public function isPhoneOpened()
    {
        return $this->FOPENED == GroupUser::PHONE_OPENED;
    }

    /**
     * 获取我在本组的电话信息
     * 取三条,不足以空不全
     *
     * @return \Illuminate\Support\Collection
     */
    public function sharePhonesInGroup()
    {
        return SparePhone::where('FGROUPUSERID', $this->FID)->select(
            'FID as spare_phone_id',
            'FChecked as is_open',
            'FREGPHONE as is_register_phone',
            'FPHONE as phone_number',
            'FPOS as order'
        )->orderBy('FPOS')->take(3)->get();
    }

}
