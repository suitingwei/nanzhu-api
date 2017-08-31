<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\ContactPower;
use App\Models\DailyReportPower;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Movie;
use App\Models\ProgressPower;
use App\Models\ReceivePower;
use App\Models\ReferencePlanPower;
use App\User;
use App\Utils\StringUtil;
use Illuminate\Http\Request;

/**
 * Class GroupsController
 * @package App\Http\Controllers\Mobile
 */
class GroupsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('mobile.user_must_in_movie', [
            'only' => [
                'index',  //部门列表
                'manage', //部门管理
            ]
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $movie_id = $request->get("movie_id");
        $groups   = Group::where("FMOVIE", $movie_id)->orderBy('FPOS', 'desc')->get();
        return view("mobile.groups.index", ["groups" => $groups, "movie_id" => $movie_id]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $group = Group::where("FID", $id)->first();
        $users = \DB::select("SELECT u.FID,u.FNAME FROM t_sys_user as u left JOIN  t_biz_groupuser gu  on gu.FUSER = u.FID  where gu.FGROUP = " . $group->FID);
        return view("mobile.groups.edit", ["group" => $group, "users" => $users]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function templates(Request $request)
    {
        $movie_id = 0;
        $groups   = Group::where("FMOVIE", $movie_id)->orderBy('FPOS', 'desc')->get();
        $user_id  = $request->get("user_id");
        return view("mobile.groups.templates",
            ["groups" => $groups, "movie_id" => $request->get("movie_id"), "user_id" => $user_id]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_movie(Request $request)
    {
        $current   = date('Y-m-d H:i:s', time());
        $group_ids = $request->get("group_ids");
        $movie_id  = $request->get("movie_id");
        $user_id   = $request->get("user_id");
        $user      = User::find($user_id);

        if (!empty($group_ids)) {
            $movie = Movie::find($movie_id);
            foreach ($group_ids as $group_id) {
                $group              = Group::where("FID", $group_id)->first();
                $data['FMOVIE']     = $movie_id;
                $data['FID']        = Group::max("FID") + 1;
                $data['FNAME']      = $group->FNAME;
                $data['FNEWDATE']   = $current;
                $data['FGROUPTYPE'] = $group->FGROUPTYPE;
                $group              = Group::create($data);
            }
        }

        return redirect()->to("/mobile/result?result=" . json_encode(["ret" => 0, "msg" => "进入成功"]));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view("mobile.groups.create",
            ["movie_id" => $request->get("movie_id"), 'user_id' => $request->input('user_id')]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = User::find($request->input('user_id'));

        $oldGroupUser = $user->firstGroupUserInMovie($request->input('movie_id'));

        $nameSameGroup = Group::where([
            'FNAME'  => $request->input('group_name'),
            'FMOVIE' => $request->input('movie_id')
        ])->first();

        if ($nameSameGroup) {
            return $this->ajaxResponseFail('部门名称不能重复');
        }

        $group = Group::create([
            'FID'        => Group::max("FID") + 1,
            'FNAME'      => $request->get("group_name"),
            'FMOVIE'     => $request->get("movie_id"),
            'FNEWDATE'   => date('Y-m-d H:i:s'),
            'FGROUPTYPE' => 20,
            'FLEADERID'  => $request->input('user_id')
        ]);

        $newGroupUser = GroupUser::create([
            'FID'            => GroupUser::max("FID") + 1,
            'FUSER'          => $request->input('user_id'),
            'FGROUP'         => $group->FID,
            'FMOVIE'         => $request->input('movie_id'),
            'FREMARK'        => '建剧人',
            'FGROUPUSERROLE' => 20,
            'FOPEN'          => 10,
            'FOPENED'        => 1,
            'FPUBLICTEL'     => 20,
            'FNEWDATE'       => date('Y-m-d H:i:s'),
            'FEDITDATE'      => date('Y-m-d H:i:s'),
        ]);


        $this->copyOldGroupUserPower($oldGroupUser, $newGroupUser, $request->input('movie_id'));

        $user->joinHxGroup($group);

        return $this->ajaxResponseSuccess('创建成功', [
            'redirect_url' => "/mobile/groups?movie_id={$group->FMOVIE}&user_id={$request->input('user_id')}"
        ]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        \Log::info($request->all());
        $group = Group::where("FID", $id)->first();


        $data['FNAME'] = $request->get("group_name");

        if ($group->FGROUPTYPE == 10 || $group->FGROUPTYPE == 30) {
            $data['FNAME'] = $group->FNAME;
        }

        $data['FLEADERID'] = $request->get("group_leader");
        if ($group) {
            Group::where("FID", $id)->update($data);
        }
        if ($group->FGROUPTYPE == 10 && $data['FLEADERID']) {
            \DB::table("t_biz_movieuser")->where("FMOVIE", $group->FMOVIE)->delete();
            \DB::table('t_biz_movieuser')->insert([
                'FUSER'  => $data['FLEADERID'],
                'FMOVIE' => $group->FMOVIE,
                "FROLE"  => 10,
                "FID"    => \DB::table("t_biz_movieuser")->max("FID") + 1
            ]);

        }
        return redirect()->to("/mobile/groups?movie_id=" . $group->FMOVIE . '&user_id=' . $request->input('user_id'));
    }

    /**
     * 删除部门
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $group = Group::find($id);

        if ($group && $group->canDelete()) {
            $admin = $group->admin();

            if ($admin) {
                return response()->json([
                    'success' => false,
                    'msg'     => "该部门中含有最高权限者:{$admin->user->FNAME}"
                ]);
            }

            //删除部门所有成员
            $groupUsers = GroupUser::where('FGROUP', $id)->get();

            foreach ($groupUsers as $groupUser) {
                \Log::info("{$group}部门被删除了,现在开始删除部门成员{$groupUser}");
                $groupUser->user->exitGroup($id);
            }

            Group::where("FID", $id)->delete();

            return response()->json(['success' => true, 'msg' => '删除成功']);
        }
    }

    /**
     * 部门管理
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function manage(Request $request)
    {
        $movieId = $request->input('movie_id');
        $groupId = $request->input('group_id');
        $user    = $this->getCurrentUserByRequest($request);
        $groups  = $user->leaderGroupsInMovie($movieId);

        if ($groupId != '') {
            //这里貌似和数据库设置有关系,有时候需要将输入参数转换为intval
            $newGroups = collect([]);
            foreach ($groups as $group) {
                if ($group->FID == $groupId) {
                    $newGroups->push($group);
                }
            }
            $groups = $newGroups;
        }

        //如果部门不存在
        if ($groups->count() == 0) {
            return response()->view('errors.user_nolonger_leader_of_group');
        }

        //如果只有一个部门
        if ($groups->count() == 1) {

            $group = $groups->first();

            $groupedUsers = StringUtil::groupByFirstChar($group->usersInGroup());

            return response()->view('mobile.groups.manage.index', compact('groupedUsers', 'group', 'movieId', 'user'));
        }

        $allGroupsApplies = [];
        foreach ($user->leaderGroupsInMovie($movieId) as $leaderGroup) {
            foreach ($leaderGroup->unAuditedApplies() as $applies) {
                $allGroupsApplies [] = $applies;
            }
        }

        return response()->view('mobile.groups.manage.list', compact('groups', 'user', 'movieId', 'allGroupsApplies'));
    }

    /**
     * 展示删除部门人员界面
     *
     * @param Request $request
     * @param         $groupId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, $groupId)
    {
        $groupUsers = StringUtil::groupByFirstChar(Group::find($groupId)->usersInGroup());

        $userId = $request->input('user_id');

        return view('mobile.groups.manage.delete', compact('groupUsers', 'groupId', 'userId'));
    }


    /**
     * 将一个手机号添加到剧组通讯录
     *
     * @param Request $request
     * @param         $groupId
     * @param         $groupUserId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPhoneToContact(Request $request, $groupId, $groupUserId)
    {
        $groupUser = GroupUser::find($groupUserId);

        if ($groupUser) {
            $groupUser->joinContacts();
        }

        //由于使用redirect->back()会返回到部门管理界面,同时X-Auth-Token,也会丢失
        //导致user_id无法获取,会报错,所以只能使用ajax请求,在js进行重载
        return response()->json(['success' => true, 'msg' => '']);
    }

    /**
     * 将某一个手机号从剧组通讯录移除
     *
     * @param Request $request
     * @param         $groupId
     * @param         $groupUserId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removePhoneFromContact(Request $request, $groupId, $groupUserId)
    {
        $groupUser = GroupUser::find($groupUserId);

        if ($groupUser) {
            $groupUser->removeContacts();
        }

        return response()->json(['success' => true, 'msg' => '']);
    }


    /**
     * 删除一个组员
     * 1.只有部门长可以删除人
     * 2.最高权限者,部门长不可以被删除
     *
     * @param Request $request
     * @param         $groupId
     * @param         $groupUserId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeMember(Request $request, $groupId, $groupUserId)
    {
        $currentUser     = $this->getCurrentUserByRequest($request);
        $group           = Group::find($groupId);
        $deleteGroupUser = GroupUser::find($groupUserId);

        if (!$group) {
            return $this->ajaxResponseFail('删除成功');
        }
        if ($currentUser->FID == $deleteGroupUser->user->FID) {
            return $this->ajaxResponseFail('不能将自己删除');
        }

        if (!$deleteGroupUser->isNotAdmin()) {
            return $this->ajaxResponseFail('该用户不能被删除');
        }

        if ($currentUser->isLeaderOfGroup($group)) {
            $deleteGroupUser->user->exitGroup($groupId);
        }

        return $this->ajaxResponseSuccess('删除成功',
            ['redirect_url' => "/mobile/groups/manage?movie_id={$group->FMOVIE}&user_id={$currentUser->FID}"]);
    }


    /**
     * 部门邀请的分享
     *
     * @param Request $request
     * @param         $groupId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wechatShare(Request $request, $groupId)
    {
        $user  = $this->getCurrentUserByRequest($request);
        $movie = Movie::find($request->input('movie_id'));
        $group = Group::find($groupId);

        return view('mobile.groups.manage.wechat_share', compact('user', 'movie', 'group'));
    }

    private function copyOldGroupUserPower($oldGroupUser, $newGroupUser, $movieId)
    {
        $powerNeedToCopyWhenJoinNewGroup = [
            ContactPower::class,
            ProgressPower::class,
            ReceivePower::class,
        ];
        foreach ($powerNeedToCopyWhenJoinNewGroup as $powerNeedToCopy) {
            $oldPower = $powerNeedToCopy::where([
                'FGROUPUSERID' => $oldGroupUser->FID,
                'FMOVIEID'     => $movieId
            ])->first();

            if ($oldPower) {
                $powerNeedToCopy::create([
                    'FID'          => $powerNeedToCopy::max('FID') + 1,
                    'FGROUPUSERID' => $newGroupUser->FID,
                    'FMOVIEID'     => $movieId
                ]);
            }
        }
        $newPowerToCopyWhenJoinNewGroup = [
            ReferencePlanPower::class,
            DailyReportPower::class
        ];

        foreach ($newPowerToCopyWhenJoinNewGroup as $powerNeedToCopy) {
            $oldPower = $powerNeedToCopy::where([
                'group_user_id' => $oldGroupUser->FID,
                'movie_id'      => $movieId
            ])->first();

            if ($oldPower) {
                $powerNeedToCopy::create([
                    'group_user_id' => $newGroupUser->FID,
                    'movie_id'      => $movieId
                ]);
            }
        }


    }
}
