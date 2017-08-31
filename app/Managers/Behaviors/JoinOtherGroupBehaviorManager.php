<?php

namespace App\Managers\Behaviors;


use App\Managers\Powers\PowerManager;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Movie;
use Carbon\Carbon;
use DB;

class JoinOtherGroupBehaviorManager extends UserBehaviorManager
{
    /**
     * @var Group
     */
    private $group;

    /**
     * @var Movie
     */
    private $movie;

    /**
     * @var GroupUser
     */
    private $groupUser;

    /**
     * @param $groupId
     */
    public function joinGroup($groupId)
    {
        $this->init($groupId);

        $this->join();
    }

    /**
     * Init the essiential info for join group.
     *
     * @param $groupId
     */
    private function init($groupId)
    {
        $this->group = Group::find($groupId);
        $this->movie = $this->group->movie;
    }

    /**
     * Act the join action.
     */
    private function join()
    {
        $firstJoinGroupUser = $this->user->groupUsersInMovie($this->movie->FID)->first();
        if ($this->user->isInGroup($this->group->FID)) {
            return;
        }

        DB::transaction(function () use ($firstJoinGroupUser) {
            $this->createNewGroupUser($firstJoinGroupUser);
            $this->copyExistingPowerToNewGroupUser();
            $this->assignPowerByGroup($this->user, $this->group);
            $this->group->addUserToShareTodos($this->user->FID);
            $this->user->joinHxGroup($this->group->FID);
        });
    }

    /**
     * Copy all existing power to another group user.
     */
    private function copyExistingPowerToNewGroupUser()
    {
        foreach (PowerManager::$allPowerModels as $powerModel) {
            if ($powerModel::isGroupUserAssigned($this->groupUser, $this->movie->FID)) {
                $powerModel::assignUser($this->user, $this->movie->FID);
            }
        }
    }

    /**
     * Join the new group.
     *
     * @param $currentGroupUser
     *
     * @return mixed
     */
    private function createNewGroupUser($currentGroupUser)
    {
        $joinGroupUser           = $currentGroupUser->replicate();
        $joinGroupUser->FID      = GroupUser::max('FID') + 1;
        $joinGroupUser->FGROUP   = $this->group->FID;
        $joinGroupUser->FMOVIE   = $this->movie->FID;
        $joinGroupUser->FNEWDATE = Carbon::now();
        $joinGroupUser->save();

        return $this->groupUser = $joinGroupUser;
    }
}