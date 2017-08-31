<?php
namespace App\Traits\Power;

use App\Models\GroupUser;
use App\User;

trait  PowerOperation
{
    /**
     * Get the database group-user key,because the old power table contains the
     * different key from the new power table.
     * @return string
     */
    abstract public static function getGroupUserKey();

    /**
     * Get the database movie key,because the old power table contains the
     * different key from the new power table.
     * @return string
     */
    abstract public static function getMovieKey();

    /**
     * Remove the group user's corresponding power.
     *
     * @param GroupUser $groupUser
     *
     * @return mixed
     */
    public static function retrieveGroupUser(GroupUser $groupUser)
    {
        static::where([
            static::getGroupUserKey() => $groupUser->FID,
            static::getMovieKey()     => $groupUser->FMOVIE,
        ])->delete();
    }

    /**
     * Assign the user with power.
     *
     * @param User $user
     *
     * @param      $movieId
     *
     * @return mixed|void
     */
    public static function assignUser(User $user, $movieId)
    {
        foreach ($user->groupUsersInMovie($movieId) as $groupUser) {
            if (static::isGroupUserAssigned($groupUser, $movieId)) {
                continue;
            }
            static::assignGroupUser($groupUser);
        }
    }

    /**
     * Is user assigned power.
     *
     * @param User $user
     * @param      $movieId
     *
     * @return boolean
     */
    public static function isUserAssignedInMovie(User $user, $movieId)
    {
        $allGroupUserIdArray = $user->groupUsersInMovie($movieId)->pluck('FID');

        return static::where(static::getMovieKey(), $movieId)
                     ->whereIn(static::getGroupUserKey(), $allGroupUserIdArray)
                     ->count() > 0;
    }

    /**
     * Assign the group user power.
     *
     * @param GroupUser $groupUser
     *
     * @return mixed
     */
    public static function assignGroupUser(GroupUser $groupUser)
    {
        static::create([
            static::getMovieKey()     => $groupUser->FMOVIE,
            static::getGroupUserKey() => $groupUser->FID
        ]);
    }

    /**
     * @param User $user
     * @param      $movieId
     *
     * @return mixed
     */
    public static function retrieveUser(User $user, $movieId)
    {
        foreach ($user->groupUsersInMovie($movieId) as $groupUser) {
            static::retrieveGroupUser($groupUser);
        }
    }

    /**
     * Is group user assigned power.
     *
     * @param GroupUser $groupUser
     * @param           $movieId
     *
     * @return mixed
     * @internal param User $user
     */
    public static function isGroupUserAssigned(GroupUser $groupUser, $movieId)
    {
        return static::where([
                static::getMovieKey()     => $movieId,
                static::getGroupUserKey() => $groupUser->FID
            ])->count() > 0;
    }

    /**
     * All user ids with power.
     *
     * @param null $movieId
     *
     * @return mixed
     */
    public static function allUserIdsInMovie($movieId)
    {
        $groupUserIds = static::where(static::getMovieKey(), $movieId)
                              ->selectRaw('distinct ' . static::getGroupUserKey())
                              ->get([static::getGroupUserKey()])
                              ->pluck(static::getGroupUserKey())
                              ->all();

        return GroupUser::whereIn('FID', $groupUserIds)->get(['FUSER'])->pluck('FUSER')->unique()->all();
    }

    /**
     * Clear a movie's all power.
     *
     * @param $id
     */
    public static function clearAllPowerInMovie($id)
    {
        static::where(static::getMovieKey(), $id)->delete();
    }

}
