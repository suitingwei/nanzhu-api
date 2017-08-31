<?php

namespace App\Managers\Powers;

use App\Models\DailyReportPower;
use App\Models\Group;
use App\Models\Movie;
use App\User;
use DB;

class UserPowerManager extends PowerManager
{
    /**
     * Retrieve the power from user by group.
     *
     * @param User  $user
     * @param Group $group
     */
    public static function retrievePowerByGroup(User $user, Group $group)
    {
        if ($group->isTongChou()) {
            static::retrieveAllPowersFromUser($user, $group->FMOVIE);
        }
        if ($group->isChangJi()) {
            static::retrieveUserPower($user, $group->FMOVIE, DailyReportPower::class);
        }


    }

    /**
     * Retrieve all powers from user.
     *
     * @param $user
     * @param $movieId
     */
    public static function retrieveAllPowersFromUser($user, $movieId)
    {
        foreach (static::$allPowerModels as $power) {
            static::retrieveUserPower($user, $movieId, $power);
        }
    }

    /**
     * Retrieve specific power from user.
     *
     * @param $user
     * @param $movieId
     * @param $powerModel
     *
     */
    public static function retrieveUserPower($user, $movieId, $powerModel)
    {
        static::isModelLegal($powerModel);

        $powerModel::retrieveUser($user, $movieId);
    }

    /**
     * Assign the power while join the group.
     *
     * @param User  $user
     * @param Group $group
     */
    public static function assignPowerByGroup(User $user, Group $group)
    {
        if ($group->isTongChou()) {
            static::assignAllPowersToUser($user, $group->FMOVIE);
        }
        if ($group->isChangJi()) {
            static::assignUserPower($user, $group->FMOVIE, DailyReportPower::class);
        }
    }

    /**
     * Assign all powers in movie to the user.
     *
     * @param $user
     * @param $movieId
     */
    public static function assignAllPowersToUser(User $user, $movieId)
    {
        foreach (static::$allPowerModels as $power) {
            static::assignUserPower($user, $movieId, $power);
        }
    }

    /**
     * Assign user the power.
     *
     * @param      $user
     * @param      $movieId
     * @param null $powerModel
     *
     * @throws \Exception
     */
    public static function assignUserPower($user, $movieId, $powerModel = null)
    {
        static::isModelLegal($powerModel);

        $powerModel::assignUser($user, $movieId);
    }

    /**
     * Transfor movie admin to a new user.
     *
     * @param $userId
     * @param $movieId
     */
    public static function transforMovieAdmin($userId, $movieId)
    {
        $newAdminUser = User::find($userId);
        $movie        = Movie::find($movieId);
        $oldAdminUser = $movie->admin();

        DB::transaction(function () use ($newAdminUser, $movie, $oldAdminUser) {
            static::retrieveAllPowersFromUser($oldAdminUser, $movie->FID);
            static::retrieveUserAdminPower($oldAdminUser, $movie->FID);

            static::assignAllPowersToUser($newAdminUser, $movie->FID);
            static::assignUserAdminPower($newAdminUser, $movie->FID);
        });
    }

    /**
     * @param User $user
     * @param      $movieId
     */
    private static function retrieveUserAdminPower(User $user, $movieId)
    {
        foreach ($user->groupUsersInMovie($movieId) as $groupUser) {
            $groupUser->retrieveAdminPower();
        }
    }

    /**
     * @param User $user
     * @param      $movieId
     */
    private static function assignUserAdminPower(User $user, $movieId)
    {
        foreach ($user->groupUsersInMovie($movieId) as $groupUser) {
            $groupUser->assignAdminPower();
        }
    }

    /**
     * @param       $movieId
     * @param array $powerModels
     *
     * @return array
     */
    public static function allUserIdsInMovieWithPower($movieId, $powerModels)
    {
        $powerModels = (array)$powerModels;

        if (empty($powerModels)) {
            return [];
        }

        $result = [];
        foreach ($powerModels as $powerModel) {
            if (!static::isModelLegal($powerModel, false)) {
                continue;
            }
            $result += $powerModel::allUserIdsInMovie($movieId);
        }
        return array_unique($result);
    }


    /**
     * @param User $user
     * @param      $movieId
     * @param      $powerModel
     *
     * @see \App\Traits\Power\PowerOperation
     * @return bool
     */
    public static function isUserAssignedPowerInMovie(User $user, $movieId, $powerModel)
    {
        if (!static::isModelLegal($powerModel, false)) {
            return false;
        }

        return $powerModel::isUserAssignedInMovie($user, $movieId);
    }
}