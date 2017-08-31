<?php
namespace App\Traits\GroupUser;

trait Getters
{

    /**
     * 获取组员职位
     * @return mixed|string
     */
    public function getPositionAttribute()
    {
        return $this->FREMARK ? $this->FREMARK : '暂未填写职位';
    }

    /**
     * 获取groupuser的照片
     * @return mixed
     */
    public function getUserPicUrlAttribute()
    {
        //保证groupuser所属的user存在,user含有个人资料
        if ($this->user && $this->user->profile) {
            return $this->user->profile->avatar;
        }
        return '';
    }
}
