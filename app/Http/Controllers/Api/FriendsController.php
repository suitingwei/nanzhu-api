<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\FriendException;
use App\Models\FriendApplication;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class FriendsController extends BaseController
{
    /**
     * 用户收到的所有好友申请
     *
     * @param $userId
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function applications($userId)
    {
        $user         = User::find($userId);
        $applications = $user->receivedApplications;

        $result     = [];
        $applierIds = [];

        //用户如果申请添加之后同意又删除又申请,可能会有多个申请
        //需要过滤出每一个用户的最新的唯一一个申请
        foreach ($applications as $application) {
            if (!in_array($application->applier_id, $applierIds)) {
                $applier = User::find($application->applier_id);
                if (!$applier) {
                    continue;
                }
                $application->cover_url = $applier->cover_url;
                $application->title     = $applier->FNAME;
                $application->phone     = $applier->FPHONE;

                $applierIds [] = $application->applier_id;
                $result []     = $application;
            }
        }

        return $this->responseSuccess('操作成功', ['applications' => $result]);
    }

    /**
     * 向某一个用户发起申请
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyUserBeFriend($userId, Request $request)
    {
        $friendPhone = $request->input('friend_phone');
        $friendUser  = User::where('FPHONE', $friendPhone)->first();
        $currentUser = User::find($userId);

        if (!$friendUser) {
            return $this->responseFail('要加为好友的用户没有注册App');
        }

        try {
            $currentUser->applyUserBeFriend($friendUser, $request->input('content'));
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        return $this->responseSuccess('申请成功');
    }


    /**
     * 用户的所有好友
     *
     * @param         $userId
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function friends($userId, Request $request)
    {
        $searchValue = $request->has('phone') ? $request->input('phone') : $request->input('q');
        $searchScope = $request->has('search_scope') ? $request->input('search_scope') : 'friend';
        $user        = User::find($userId);

        $friends = User::where(function ($query) use ($searchValue) {
            if (!empty($searchValue)) {
                $searchValue = preg_replace('/\+86|\-/', null, $searchValue);
                $query->where('FPHONE', $searchValue);
            }
        });

        if ($searchScope == 'friend') {
            $friendIds = $user->friends()->lists('friend_id')->all();
            $friends->whereIn('FID', $friendIds);
        }

        $friends = $friends->get();

        $friends = $this->formatStdClass($friends, $user);

        return $this->responseSuccess('操作成功', ['friends' => $friends]);
    }


    /**
     * 同意好友申请
     *
     * @param $userId
     * @param $friendApplicationId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveApplication($userId, $friendApplicationId)
    {
        $user        = User::find($userId);
        $application = FriendApplication::find($friendApplicationId);

        if (!$application) {
            return $this->responseFail('好友申请不存在');
        }

        try {
            $user->approveFriendApplication($application);
        } catch (FriendException $e) {
            return $this->responseFail($e->getMessage());
        }

        return $this->responseSuccess();
    }

    /**
     * 删除好友
     *
     * @param $userId
     * @param $friendId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFriend($userId, $friendId)
    {
        $user = User::find($userId);

        if (!$user) {
            return $this->responseFail('用户不存在');
        }

        if (!$user->isFriendOfUser($friendId)) {
            return $this->responseFail('好友不存在');
        }

        $user->deleteFriend($friendId);

        return $this->responseSuccess('删除成功');
    }


    /**
     * @param Collection $friends
     * @param User       $user
     *
     * @return array
     */
    private function formatStdClass($friends, User $user)
    {
        $result = [];
        foreach ($friends as $friend) {
            $obj = $friend->formatBasicClass()->withFriendInfo($user)->get();

            $result[] = $obj;
        }
        return $result;
    }


}
