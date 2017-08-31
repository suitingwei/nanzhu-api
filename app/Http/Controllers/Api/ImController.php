<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomHxGroup;
use App\Models\Group;
use App\User;
use Illuminate\Http\Request;

class ImController extends BaseController
{
    /**
     * 获取环信用户的信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUsersInfo(Request $request)
    {
        $userIds = explode(',', $request->input('user_ids'));

        $users = User::whereIn('FID', $userIds)->get();

        $result = [];
        foreach ($users as $user) {
            $result [] = $user->formatBasicClass()->get();
        }
        return $this->ajaxResponseSuccess('操作成功', ['users' => $result]);
    }

    /**
     * 获取群组信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupsInfo(Request $request)
    {
        $groupIdArray = explode(',', $request->input('group_ids'));

        $result = [];

        foreach ($groupIdArray as $hxGroupId) {
            $group = Group::where('hx_group_id', $hxGroupId)->first();

            if ($group) {
                $result [] = $group->hx_title;
            } else {
                $group = CustomHxGroup::where('hx_group_id', $hxGroupId)->first();
                if ($group) {
                    $result[] = $group->hx_title;
                } else {
                    $result [] = '';
                }
            }
        }

        return $this->ajaxResponseSuccess('操作成功', ['groups' => $result]);

    }

}

