<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomHxGroup;
use App\Models\EaseUser;
use App\Models\Group;
use App\Models\HxGroupPublicNotice;
use App\Models\Message;
use App\User;
use Exception;
use Illuminate\Http\Request;
use stdClass;


class HxGroupController extends BaseController
{
    /**
     * 环信群组移交
     *
     * @param $userId
     * @param $hxGroupId
     * @param $newOwnerId
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Request $request
     *
     */
    public function transforOwner($userId, $hxGroupId, $newOwnerId)
    {
        $currentUser = User::find($userId);
        $newOwner    = User::find($newOwnerId);

        if ($currentUser->hadNotRegisterHx()) {
            return $this->responseFail('当前用户没有注册环信');
        }

        if ($newOwner->hadNotRegisterHx()) {
            return $this->responseFail('要移交群主的用户没有注册环信');
        }

        if (!$currentUser->isOwnerOfHxGroup($hxGroupId)) {
            return $this->responseFail('你不是当前群组的群主');
        }

        $easeUser = new EaseUser();

        try {
            $easeUser->transforGroupOwnerToUser($hxGroupId, $newOwner->FID);

            $easeUser->groupAddUser($hxGroupId, $currentUser->FID);
        } catch (\Exception $e) {
            return $this->responseFail('移交失败', $e->getMessage());
        }

        return $this->responseSuccess('移交成功');
    }


    /**
     * 环信群组信息
     *
     * @param $userId
     * @param $hxGroupId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($userId, $hxGroupId)
    {
        $currentUser = User::find($userId);

        $groupInfo = (new EaseUser)->getGroupInfo($hxGroupId, $currentUser);

        return $this->responseSuccess('', $groupInfo);
    }


    /**
     * 群组添加用户
     *
     * @param $userId
     * @param $hxGroupId
     *
     * @param $newMemberUserId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMember($userId, $hxGroupId, $newMemberUserId)
    {
        $user = User::find($userId);

        $newMemberUserIdArray = explode(',', $newMemberUserId);

        $newMemberUserIdArray = array_filter($newMemberUserIdArray, function ($newMemberUserId) use ($userId) {
            return $newMemberUserId != $userId;
        });

        if (count($newMemberUserIdArray) == 0) {
            return $this->responseFail('邀请进群聊的人数不能为空');
        }

        foreach ($newMemberUserIdArray as $newMemberId) {
            try {
                $easeUser = new EaseUser();
                //把用户加入群聊
                $easeUser->groupAddUser($hxGroupId, $newMemberId);

                //发送邀请进群聊的环信消息
                $easeUser->sendUserJoiningGroupMsg($newMemberId, $hxGroupId);

            } catch (\Exception $e) {
                continue;
            }
        }

        return $this->responseSuccess('添加成功');
    }

    /**
     * 群组删除用户
     * 删除和退出的区别在于,
     * ---------------------------------------
     * 1.删除的时候必须判断权限,是群主才能删除别人
     * 2.退出的时候必须判断群主,如果是群主要退出,必须先移交权限
     *
     * @param $userId
     * @param $hxGroupId
     * @param $deleteMemberUserId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMember($userId, $hxGroupId, $deleteMemberUserId)
    {
        $currenUser = User::find($userId);

        if (!$currenUser->isOwnerOfHxGroup($hxGroupId)) {
            return $this->responseFail('没有权限删除用户');
        }

        $deleteMemberUserIdArray = explode(',', $deleteMemberUserId);
        if (count($deleteMemberUserIdArray) > 0) {
            foreach ($deleteMemberUserIdArray as $deleteMemberId) {
                try {
                    (new EaseUser())->groupRemoveUser($hxGroupId, $deleteMemberId);
                } catch (Exception $e) {
                    continue;
                }
            }
        }

        return $this->responseSuccess('删除成功');
    }


    /**
     * 群组成员接口
     */
    public function members($userId, $hxGroupId)
    {


    }

    /**
     * 解散群组
     * 会自动删除群组的环信成员
     *
     * @param $userId
     * @param $hxGroupId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dismissGroup($userId, $hxGroupId)
    {
        $currentUser = User::find($userId);

        if (!$currentUser->isOwnerOfHxGroup($hxGroupId)) {
            return $this->responseFail('你不是当前群组的群主');
        }

        try {
            (new EaseUser())->deleteGroup($hxGroupId);
        } catch (\Exception $e) {
            return $this->responseFail('群组解散失败:' . $e->getMessage());
        }

        return $this->responseSuccess('群组删除成功');
    }


    /**
     * 成员退出群组
     * 退出和删除的区别
     * ---------------------------------------
     * 1.删除的时候必须判断权限,是群主才能删除别人
     * 2.退出的时候必须判断群主,如果是群主要退出,必须先移交权限
     *
     * @param $userId
     * @param $hxGroupId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exitGroup($userId, $hxGroupId)
    {
        $currenUser = User::find($userId);

        if ($currenUser->isOwnerOfHxGroup($hxGroupId)) {
            return $this->responseFail('必须先把群主身份转让才能退出');
        }

        try {
            (new EaseUser())->groupRemoveUser($hxGroupId, $currenUser->FID);
        } catch (Exception $e) {
            return $this->responseFail('退出失败:' . $e->getMessage());
        }

        return $this->responseSuccess('退出成功');
    }

    /**
     * 更新环信群组公告
     *
     * @param         $userId
     * @param         $hxGroupId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHxGroupInfo($userId, $hxGroupId, Request $request)
    {
        \Log::info('update hx group info : ' . json_encode($request->all()));
        $user  = User::find($userId);
        $group = Group::where('hx_group_id', $hxGroupId)->first();

        if ((!$user)) {
            return $this->responseFail('参数错误');
        }

        if ($request->has('public_notice')) {
            HxGroupPublicNotice::create([
                'content'     => $request->input('public_notice'),
                'editor_id'   => $userId,
                'group_id'    => $group ? $group->FID : '',
                'hx_group_id' => $hxGroupId
            ]);
        }

        if ($request->has('group_title')) {
            if ($group) {
                Group::where('FID', $group->FID)->update(['hx_group_title' => $request->input('group_title')]);
            }
            $customHxGroup = CustomHxGroup::where('hx_group_id', $hxGroupId)->first();
            if ($customHxGroup) {
                $customHxGroup->update(['title' => $request->input('group_title')]);
            }
        }

        return $this->responseSuccess('更新成功');
    }

    /**
     * 将用户加入环信黑名单
     *
     * @param $userId
     * @param $hxGroupId
     * @param $addToBlackListUserId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupBlockUser($userId, $hxGroupId, $addToBlackListUserId)
    {
        $blackListUserIdArray = explode(',', $addToBlackListUserId);

        try {
            (new EaseUser())->groupBlockUsers($hxGroupId, $blackListUserIdArray);
        } catch (Exception $e) {
            return $this->responseFail('加入黑名单失败:' . $e->getMessage());
        }

        return $this->responseSuccess('加入黑名单成功');
    }


    /**
     * 将用户移除换新黑名单
     *
     * @param $userId
     * @param $hxGroupId
     * @param $removeFromlackListUserId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupUnBlockUser($userId, $hxGroupId, $removeFromlackListUserId)
    {
        $blackListUserIdArray = explode(',', $removeFromlackListUserId);

        try {
            $easeUser = new EaseUser();
            $easeUser->groupUnBlockUsers($hxGroupId, $blackListUserIdArray);
            $easeUser->groupAddUsers($hxGroupId, $blackListUserIdArray);
        } catch (Exception $e) {
            return $this->responseFail('移除黑名单失败:' . $e->getMessage());
        }

        return $this->responseSuccess('移除黑名单成功');
    }

    /**
     * 获取环信黑名单列表
     */
    public function groupBlackLists($userId, $hxGroupId)
    {
        $currentUser = User::find($userId);

        $blackLists = (new EaseUser())->groupBlackLists($hxGroupId);

        return $this->responseSuccess('操作成功', ['blacklists' => $blackLists]);
    }


    /**
     * 用户在app创建群聊
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function appCreateHxGroup($userId, Request $request)
    {
        \Log::info('app_create_group' . json_encode($request->all()));
        $userIdArray = explode(',', $request->input('user_ids'));

        $users = User::whereIn('FID', $userIdArray)->get();

        $groupTitle = implode('/', array_map(function ($user) {
            return $user->hx_name;
        }, $users->all()));

        try {
            $easeUser = new EaseUser();

            $hxGroupId = $easeUser->createCustomChatGroup($userId, time(), $groupTitle)['data']['groupid'];

            $userIdArray = array_filter($userIdArray, function ($memberId) use ($userId) {
                return $memberId != $userId;
            });

            $userIdArray = array_unique($userIdArray);

            $easeUser->groupAddUsers($hxGroupId, $userIdArray);

            CustomHxGroup::create([
                'title'       => $groupTitle,
                'hx_group_id' => $hxGroupId,
                'type'        => CustomHxGroup::TYPE_CUSTOM,
                'cover_url'   => CustomHxGroup::DEFAULT_GROUP_COVER_URL,
            ]);

            return $this->responseSuccess('操作成功', [
                'group_id'      => $hxGroupId,
                'title'         => $groupTitle,
                'cover_url'     => CustomHxGroup::DEFAULT_GROUP_COVER_URL,
                'members_count' => $users->count(),
                'is_juzu'       => false
            ]);
        } catch (Exception $e) {
            return $this->responseSuccess('操作失败:' . $e->getMessage());
        }
    }

    /**
     * 获取当前用户所在的[前端创建的环信群组]
     *
     * @param $userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinedAppCreateGroups($userId)
    {
        $user = User::find($userId);

        $joinedHxGroups = $user->joinedHxGroups();

        $joinedAppCreateHxGroups = [];
        foreach ($joinedHxGroups as $joinedHxGroup) {
            $group = CustomHxGroup::where('hx_group_id', $joinedHxGroup['groupid'])->first();

            if (!$group) {
                continue;
            }

            $obj                        = new stdClass();
            $obj->title                 = $group->hx_title;
            $obj->members_count         = count($group->getHxMembers());
            $obj->hx_group_id           = $joinedHxGroup['groupid'];
            $obj->cover_url             = Group::APP_CREATE_HX_GROUP_COVER_URL;
            $obj->type                  = Message::TYPE_CHAT_GROUP;
            $joinedAppCreateHxGroups [] = $obj;
        }


        return $this->responseSuccess('操作成功', ['groups' => $joinedAppCreateHxGroups]);
    }

    /**
     * 用户加入的所有剧组的部门
     *
     * @param $userId
     *
     * @return array
     */
    public function joinedMovieGroups($userId)
    {
        $user = User::find($userId);

        $allGroups = $user->joinedHxGroups();

        $result = [];
        foreach ($allGroups as $groupInfo) {
            $group = EaseUser::getGroupFromName($groupInfo['groupname']);
            if (!$group) {
                continue;
            }
            $obj                = new stdClass();
            $obj->title         = $group->hx_title;
            $obj->members_count = count($group->getHxMembers());
            $obj->cover_url     = Group::DEPARTMENT_HX_GROUP_COVER_URL;
            $obj->hx_group_id   = $groupInfo['groupid'];
            $obj->type          = Message::TYPE_CHAT_GROUP;

            $result [] = $obj;
        }

        return $this->responseSuccess('操作成功', ['movies' => $result]);
    }


    /**
     * 加入的所有环信群聊
     */
    public function joinedAllHxGroups($userId)
    {
        $user = User::find($userId);

        $allGroups = $user->joinedHxGroups();

        $result = [];
        foreach ($allGroups as $groupInfo) {
            $is_juzu = true;
            $group   = EaseUser::getGroupFromName($groupInfo['groupname']);

            if (!$group) {
                $group   = CustomHxGroup::where('hx_group_id', $groupInfo['groupname'])->first();
                $is_juzu = false;
            }

            if (!$group) {
                continue;
            }
            $obj                = new stdClass();
            $obj->title         = $group->hx_title;
            $obj->members_count = count($group->getHxMembers());
            $obj->cover_url     = Group::DEPARTMENT_HX_GROUP_COVER_URL;
            $obj->hx_group_id   = $groupInfo['groupid'];
            $obj->type          = Message::TYPE_CHAT_GROUP;
            $obj->is_juzu       = $is_juzu;

            $result [] = $obj;
        }

        return $this->responseSuccess('操作成功', ['groups' => $result]);
    }


}
