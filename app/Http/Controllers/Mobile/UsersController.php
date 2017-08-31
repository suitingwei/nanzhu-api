<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\JoinGroup;
use App\Models\Like;
use App\Models\Movie;
use App\Models\Profile;
use App\Models\ProfileRecord;
use App\Models\ProfileShare;
use App\User;
use App\Utils\StringUtil;
use Illuminate\Http\Request;

class UsersController extends BaseController
{

    public function __construct()
    {
        $this->middleware('mobile.user_must_in_movie', [
            'only' => [
                'group',  //我在本组
                'contact', //剧组通讯录
                'indexPublicContact', //公开电话
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        $current_user_id = $request->get("current_user_id");
        $profile         = Profile::find($id);
        if (!$profile) {
            $profile = Profile::where("user_id", $id)->first();
        }

        $from       = $request->input("from");
        $is_like    = false;
        $like_count = Like::where("type", "user")->where("like_id", $profile->id)->count();
        if ($current_user_id) {
            $is_like = $this->is_liked($current_user_id, "user", $id);
        }

        return view("mobile.profiles.show", [
            "current_user_id" => $current_user_id,
            "profile"         => $profile,
            "like_count"      => $like_count,
            "from"            => $from,
            "is_liked"        => $is_like
        ]);
    }

    public function share(Request $request, $id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            $profile = Profile::where("user_id", $id)->first();
        }
        $user_ids = ProfileShare::where("profile_id", $profile->id)->lists("user_id");
        $users    = User::select("FID", "FNAME", "FPHONE", "FPIC")->wherein("FID", $user_ids)->get();
        $records  = ProfileRecord::where("profile_id", $profile->id)->orderBy("id", "desc")->take(10)->get();

        return view("mobile.profiles.share", ["user_id" => $id, "users" => $users, "records" => $records]);
    }

    public function add_share(Request $request, $id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            $profile = Profile::where("user_id", $id)->first();
        }
        $phone    = $request->get("phone");
        $user_ids = [];
        $shares   = ProfileShare::select("user_id")->where("profile_id", $profile->id)->lists("user_id");
        foreach ($shares as $share) {
            $user_ids[] = $share;
        }
        $users = [];
        if ($phone) {
            $users = User::select("FID", "FNAME", "FPHONE", "FPIC")->where("FPHONE", $phone)->get();
        }
        return view("mobile.profiles.add_share", ["user_id" => $id, "users" => $users, "user_ids" => $user_ids]);
    }

    public function delete_share(Request $request, $id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            $profile = Profile::where("user_id", $id)->first();
        }
        ProfileShare::where("user_id", $request->get("user_id"))->where("profile_id", $profile->id)->delete();
        return redirect()->to("/mobile/users/" . $id . "/share");
    }

    public function post_share(Request $request, $id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            $profile = Profile::where("user_id", $id)->first();
        }
        $data               = $request->all();
        $data["profile_id"] = $profile->id;

        $shares = ProfileShare::select("user_id")->where("profile_id", $profile->id)->lists("user_id");
        if (count($shares) < 3) {
            ProfileShare::create($data);
        }

        return redirect()->to("/mobile/users/" . $id . "/share");
    }

    /**
     * 公开电话
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function indexPublicContact(Request $request)
    {
        $movieId = $request->input('movie_id');
        $user    = $this->getCurrentUserByRequest($request);

        $users = Movie::find($movieId)->allUsersInPublicContacts();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.groups.public_contacts.index', compact('groupedUsers', 'movieId', 'user'));
    }

    /**
     * 添加公开电话界面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createPublicContact(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInContacts();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.groups.public_contacts.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * 获取剧组通讯录
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInContacts();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.groups.contacts.index', compact('groupedUsers', 'movieId'));
    }

    /**
     * 获取我在本组的信息
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function group($userId, Request $request)
    {
        $user    = User::find($userId);
        $movieId = $request->input('movie_id');

        $groupUsers = $user->groupUsersInMovie($movieId);

        $firstJoinedGroupUser = $groupUsers->first();

        return view('mobile.groups.in_group.index', [
            'firstGroupUser' => $firstJoinedGroupUser,
            'user'           => $user,
            'movieId'        => $movieId
        ]);
    }

    public function allGroups(Request $request)
    {
        $movie = Movie::find($request->input('movie_id'));
        $user  = User::find($request->input('user_id'));

        $movieAllGroups = $movie->groups()->orderBy('FPOS', 'desc')->get();

        return view('mobile.groups.in_group.all_groups', compact('movieAllGroups', 'user', 'movie'));
    }

    public function practice()
    {
        return view('mobile.groups.in_group.practice');
    }
    /**
     * 更新我在本组的信息
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateGroupInfo($userId, Request $request)
    {
        $user = User::find($userId);

        if ($user) {
            $user->updateGroupInfo($request->all());
        }

        return response()->json([
            'success'  => true,
            'msg'      => '保存成功!',
            'redirect' => "/mobile/menus?user_id={$userId}&movie_id={$request->input('movie_id')}"
        ]);

    }

    /**
     * 删除某个公开电话
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePublicContact(Request $request)
    {
        GroupUser::find($request->input('group_user_id'))->setPhonePrivate();

        return back();
    }

    /**
     * 更新公开电话的信息
     * 添加/删除
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePublicContact(Request $request)
    {
        $publicContacts = $request->input('publicContact', []);
        $movieId        = $request->input('movie_id');
        $currentUser    = $this->getCurrentUserByRequest($request);

        foreach ($publicContacts as $publicContact) {
            $publicContact = json_decode($publicContact);

            $user = User::find($publicContact->userId);

            if ($user) {
                $publicContact->checked ? $user->setPhonePublicInMovie($movieId) : $user->setPhonePrivateInMovie($movieId);
            }
        }

        return redirect()->to('/mobile/users/public_contact?movie_id=' . $request->input('movie_id') . '&user_id=' . $currentUser->FID);
    }


    /**
     * 显示申请加入部门界面
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createJoinOtherGroup($userId, Request $request)
    {
        $movieId = $request->input('movie_id');

        $movie = Movie::find($movieId);

        $groups = $movie->groups;

        $user = User::find($userId);

        $joinedCount = 0;
        foreach ($groups as $group) {
            if (($user->isInGroup($group->FID)) ||
                ($user->hadTryJoinedGroup($group->FID) && $user->isJoinGroupAuditting($group->FID))
            ) {
                $joinedCount++;
            }
        }
        $isAllGroupsJoined = ($joinedCount == $groups->count());

        return view('mobile.groups.in_group.try_join',
            compact('movieId', 'userId', 'groups', 'user', 'isAllGroupsJoined'));
    }

    /**
     * 申请加入部门
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeJoinOtherGroup($userId, Request $request)
    {
        $user         = User::find($userId);
        $movieId      = $request->input('movie_id');
        $groupIdArray = $request->input('join_groups');

        if ($user && count($groupIdArray) > 0) {
            $user->tryJoinMovieGroup($groupIdArray, $movieId);
        }

        return $this->ajaxResponseSuccess();
    }

    /**
     * 显示申请退出剧组部门界面
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createExitGroup($userId, Request $request)
    {
        $movieId = $request->input('movie_id');

        $user = User::find($userId);

        $groups = $user->groupsInMovie($movieId);

        return view('mobile.groups.in_group.try_exit', compact('movieId', 'userId', 'groups', 'user'));
    }

    /**
     * 退出部门
     *
     * @param         $userId
     * @param         $groupId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeExitGroup($userId, $groupId, Request $request)
    {
        $user  = User::find($userId);
        $group = Group::find($groupId);

        if ($user->groupsInMovie($group->movie->FID)->count() == 1) {
            return $this->responseFail('无法退出您的最后一个部门!');
        }

        $user->exitGroup($groupId);

        return $this->ajaxResponseSuccess('退出部门成功');
    }


    /**
     * 退出剧组
     * 1.最高权限者不能退出
     *
     * @param $userId
     * @param $movieId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeExitMovie($userId, $movieId)
    {
        $user = User::find($userId);

        if (!$user) {
            return $this->ajaxResponseFail('用户不存在');
        }

        if ($user->isAdminOfMovie($movieId)) {
            return $this->ajaxResponseFail('最高权限者不能退出剧组');
        }

        $user->exitMovie($movieId);

        $lastMovie   = $user->joinedNotEndMovies()->first();

        $lastMovieId = $lastMovie ? $lastMovie->FID : '';

        return $this->ajaxResponseSuccess('success', ['movie_id' => $lastMovieId]);
    }


    /**
     * 同意入组申请
     *
     * @param $userId
     * @param $joinGroupId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveJoinGroup($userId, $joinGroupId)
    {
        $user = User::find($userId);

        $joinGroup = JoinGroup::find($joinGroupId);

        if ($joinGroup && $joinGroup->hadNotHandled()) {
            $joinGroup->approvedByUser($user);
        }

        return $this->ajaxResponseSuccess();
    }

    /**
     * 拒绝入组申请
     *
     * @param $userId
     * @param $joinGroupId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function declineJoinGroup($userId, $joinGroupId)
    {
        $user = User::find($userId);

        $joinGroup = JoinGroup::find($joinGroupId);

        if ($joinGroup) {
            $joinGroup->declinedByUser($user);
        }

        return $this->ajaxResponseSuccess();
    }

}
