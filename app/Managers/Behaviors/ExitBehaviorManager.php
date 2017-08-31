<?php

namespace App\Managers\Behaviors;


use App\Managers\Powers\PowerManager;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\JoinGroup;
use App\Models\Message;
use App\Models\Movie;
use App\User;
use DB;

class ExitBehaviorManager extends UserBehaviorManager
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

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * -----------------------------------------------------------------------------------
     * Exit the group.
     * -----------------------------------------------------------------------------------
     * 1.Clear all assigned power with this group user.I.e.,daily report power,receiver
     * power. Note,we should only clear power assigned with this group user.
     *
     * 2.Clear all received message if the user is quitting the movie.
     *
     * 3.Clear the join group record,let the user can join again.
     *
     * 4.Delete the share phones if the user is quitting the movie,or transfor to the next
     * group-user the user has.
     *
     * 5.Delete the group-user.
     *
     * @param Group $group
     */
    public function exitGroup($group)
    {
        $this->init($group);

        DB::transaction(function () {
            $this->retrievePowers();
            $this->removeJoinGroupRecord();
            $this->removeMessageRecord();
            $this->removeOrTransforSharePhones();
            $this->groupUser->remove();
        });
    }

    /**
     * Init the exiting group info.
     *
     * @param $group
     */
    private function init($group)
    {
        $this->group     = Group::find($group);
        $this->movie     = $this->group->movie;
        $this->groupUser = $this->group->members()->where('FUSER', $this->user->FID)->first();

        \Log::info('用户' . $this->user->FID . '正在退出部门' . $this->group->FID);
    }

    /**
     * 删除所有这个人的接受通知
     */
    private function removeMessageRecord()
    {
        //If user is not quitting the movie,leave him alone.
        if (!$this->isUserQuittingMovie()) {
            return;
        }

        //If the user's quitting the movie,remove him from all messages receivers.
        $messages = $this->movie->messages()->where('scope_ids', 'like', '%' . $this->user->FID . '%')->get();

        foreach ($messages as $message) {
            if ($message instanceof Message) {
                $message->removeUserFromReceivers($this->user->FID);
            }
        }
    }

    /**
     * Get rid of all possible powers.
     * For now, a group user may have 5 powers in a movie,except the leader power.
     * And if she/he quit the group,we should clear all powers assigned to this
     * group user.
     */
    private function retrievePowers()
    {
        $this->retrievePowerByGroup($this->user, $this->group);
        $this->retrievePowerFromGroupUser();
        $this->retrieveLeader();
    }

    /**
     * Clear all join groups record to this group,so the user can apply to join
     * this group again.
     */
    private function removeJoinGroupRecord()
    {
        JoinGroup::where([
            'movie_id' => $this->group->FID,
            'user_id'  => $this->user->FID,
            'group_id' => $this->groupUser->FGROUP
        ])->delete();
    }

    /**
     * If the group user's corresponding user has joined many groups in this movie,then we only share
     * one pair share phones among all group users,and if the user quit some group,we will transfor
     * the current share phones' owner to the next group user.
     */
    private function removeOrTransforSharePhones()
    {
        //Get all group users in the movie.
        $groupUsers = $this->user->groupUsersInMovie($this->groupUser->FMOVIE);

        //If the user only joined one group,just clear all share phones.
        if ($groupUsers->count() <= 1) {
            $this->groupUser->sharePhones()->delete();
            return;
        }

        //If the user has joined many groups,pass the share phones to the next group user.
        if ($this->groupUser->FID === $groupUsers->first()->FID) {
            $this->groupUser->sharePhones()->update(['FGROUPUSERID' => $groupUsers[1]->FID]);
        }
    }

    /**
     * @return bool
     */
    private function isUserQuittingMovie()
    {
        return $this->user->groupsInMovie($this->movie->FID)->count() == 1;
    }

    /**
     * Exit the movie.
     *
     * @param $movieId
     */
    public function exitMovie($movieId)
    {
        foreach ($this->user->groupsInMovie($movieId) as $group) {
            $this->exitGroup($group->FID);
        }
    }

    /**
     * Retrieve the leader power from the group user.
     */
    private function retrieveLeader()
    {
        $this->groupUser->isLeader() ? $this->groupUser->removeLeaderPower() : null;
    }

    /**
     * Retrieve the powers assgined to this group user.
     */
    private function retrievePowerFromGroupUser()
    {
        PowerManager::retrieveAllPowersFromGroupUser($this->groupUser);
    }

}