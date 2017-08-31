<?php

namespace App\Traits\User;

use App\Models\Role;

/**
 * ----------------------------------------
 * 请不要将该trait用于除了User之外的任何地方!!!
 * 该trait只用于分解用户有关电影的操作
 * ----------------------------------------
 *
 * @package App\Traits\User
 */
trait RoleOperationTrait
{
    /**
     * @return mixed
     */
    public function roles()
    {
        return Role::leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')->where("role_user.user_id",
            $this->FID)->get();
    }

    /**
     * @param $role_id
     *
     * @return bool
     */
    public function has_role($role_id)
    {
        $role = Role::leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')->where("role_user.user_id",
            $this->FID)->where("role_user.role_id", $role_id)->first();
        if ($role) {
            return true;
        }
        return false;
    }


    /**
     * @return string
     */
    public function sex_desc()
    {
        if ($this->FSEX == 10) {
            return "男";
        }
        if ($this->FSEX == 20) {
            return "女";
        }
    }

}
