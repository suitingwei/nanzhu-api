<?php
namespace App\Managers\Behaviors;


use App\Managers\Powers\UserPowerManager;
use App\Models\Group;
use App\User;

class UserBehaviorManager
{
    /**
     * @var User
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Retrieve power when user exit group.
     *
     * @param User  $user
     * @param Group $group
     */
    public function retrievePowerByGroup(User $user, Group $group)
    {
        UserPowerManager::retrievePowerByGroup($user, $group);
    }

    /**
     * Assign powers when user join group.
     *
     * @param User  $user
     * @param Group $group
     */
    public function assignPowerByGroup(User $user, Group $group)
    {
        UserPowerManager::assignPowerByGroup($user, $group);
    }
}