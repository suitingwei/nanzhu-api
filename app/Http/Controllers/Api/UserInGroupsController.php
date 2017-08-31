<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use App\Models\JoinGroup;
use App\Models\Movie;
use App\User;
use Illuminate\Http\Request;

class UserInGroupsController extends BaseController
{
    /**
     * Index of the user-in group page.
     * @param         $userId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($userId, Request $request)
    {
        $user           = User::find($userId);
        $movie          = Movie::find($movieId = $request->input('movie_id'));
        $firstGroupUser = $user->firstGroupUserInMovie($movieId);

        $sharePhones = $firstGroupUser->sharePhonesInGroup()->map(function ($sharePhone) {
            return [
                'is_phone_checked' => (boolean)$sharePhone->is_open,
                'share_phone_id'   => $sharePhone->spare_phone_id,
                'phone_number'     => $sharePhone->phone_number,
            ];
        });

        $result = [
            'is_sharing_phones' => $firstGroupUser->isPhoneOpened(),
            'share_phones'      => $sharePhones,
            'groups_names'      => $user->groupNamesInMovie($movieId),
            'user_name'         => $firstGroupUser->user->FNAME,
            'position_name'     => $firstGroupUser->FREMARK,
            'room_name'         => $firstGroupUser->room,
            'can_feedback'      => (boolean)$movie->is_groupuser_feedback_open,
        ];

        return $this->responseSuccess('successfully', ['data' => $result]);
    }

    /**
     *
     */
    public function allGroups($userId, Request $request)
    {
        $user  = User::find($userId);
        $movie = Movie::find($request->input('movie_id'));

        $movieAllGroups = $movie->groups()->orderBy('FPOS', 'desc')->get()->map(function ($group) use ($user) {
            $joinStatus = null;
            if ($user->isInGroup($group->FID)) {
                $joinStatus = JoinGroup::STATUS_JOIN_SUCCESS;
            }
            elseif ($user->hadTryJoinedGroup($group->FID) && $user->isJoinGroupAuditting($group->FID)) {
                $joinStatus = JoinGroup::STATUS_WAIT_AUDIT;
            }
            else {
                $joinStatus = JoinGroup::STATUS_CAN_JOIN;
            }

            return [
                'group_id'    => $group->FID,
                'group_name'  => $group->FNAME,
                'join_status' => $joinStatus,
            ];
        });

        return $this->responseSuccess('success', ['data' => $movieAllGroups]);
    }

    /**
     * Exit the movie.
     * @param         $userId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exitMovie($userId, Request $request)
    {
        $user    = User::find($userId);
        $movieId = $request->input('movie_id');

        if ($user->isAdminOfMovie($movieId)) {
            return $this->responseFail('最高权限者不能退出剧组');
        }

        $user->exitMovie($movieId);

        $lastMovie = $user->joinedNotEndMovies()->first();

        $lastMovieId = $lastMovie ? $lastMovie->FID : '';

        return $this->responseSuccess('success', ['movie_id' => $lastMovieId]);
    }

    /**
     * Apply for join the group.
     */
    public function joinGroup($userId, Request $request)
    {
        $user         = User::find($userId);
        $movieId      = $request->input('movie_id');
        $groupIdArray = $request->input('group_id');

        if ($user && count($groupIdArray) > 0) {
            $user->tryJoinMovieGroup($groupIdArray, $movieId);
        }

        return $this->responseSuccess();
    }

    /**
     * Exit the group.
     * @param         $userId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exitGroup($userId, Request $request)
    {
        $user  = User::find($userId);
        $group = Group::find($groupId = $request->input('group_id'));

        if ($user->groupsInMovie($group->movie->FID)->count() == 1) {
            return $this->responseFail('无法退出您的最后一个部门!');
        }

        $user->exitGroup($groupId);

        return $this->responseSuccess('退出部门成功');
    }

    /**
     * Update the user's info in group.
     * @param         $userId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($userId, Request $request)
    {
        \Log::info('update the useringroup info' . json_encode($request->all()));
        $user = User::find($userId);

        $updateData                 = $request->all();
        $updateData['job_is_open']  = $request->input('is_sharing_phones', 0);
        $updateData['phoneJson']    = [];
        $updateData['phoneJson'] [] = $request->input('phoneJson1');
        $updateData['phoneJson'] [] = $request->input('phoneJson2');
        $updateData['phoneJson'] [] = $request->input('phoneJson3');
        $user->updateGroupInfo($updateData);

        return $this->responseSuccess();
    }
}

