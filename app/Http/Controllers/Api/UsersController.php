<?php

namespace App\Http\Controllers\Api;

use App\Consts\ResponseCodes;
use App\Exceptions\FriendException;
use App\Models\Album;
use App\Models\Blog;
use App\Models\CustomHxGroup;
use App\Models\EaseUser;
use App\Models\Favorite;
use App\Models\Group;
use App\Models\Like;
use App\Models\Message;
use App\Models\Picture;
use App\Models\Profile;
use App\Models\ProfileRecord;
use App\Models\Recruit;
use App\User;
use Illuminate\Http\Request;

/**
 * Class UsersController
 * @package App\Http\Controllers\Api
 */
class UsersController extends BaseController
{
    /**
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $current_user_id = $this->current_user($request);

		\Log::info('user detail user id: '.$current_user_id);
		\Log::info('user detail request'.json_encode($request->all()));
		\Log::info('user detail request'.json_encode($request->header()));
        $like_count = Like::where("like_id", $id)->count();
        $user       = User::where("FID", $id)->first();
        if (!$user) {
            return response()->json(["ret" => -99, "msg" => "用户不存在"]);
        }
        $profile                     = Profile::where("user_id", $user->FID)->first();
        $is_liked                    = $this->is_liked($current_user_id, "user", $id);
        $is_favorite                 = $this->is_favorite($current_user_id, "user", $id);
        $profileData                 = $profile->toArray();
        $profileData['h5_union_url'] = env('APP_URL') . '/mobile/unions?user_id=' . $current_user_id;
        return response()->json([
            "ret"                => 0,
            "like_count"         => $like_count,
            "is_liked"           => $is_liked,
            "is_favorite"        => $is_favorite,
            "profile"            => $profileData,
            'h5_union_url'       => env('APP_URL') . '/mobile/unions?user_id=' . $current_user_id,
            'union_unread_count' => $user->unionUnReadCount(),
            "msg"                => "保存成功"
        ]);
    }

    /**
     * 更新艺人个人资料
     * @param Request $request
     * @param         $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile_update(Request $request, $user_id)
    {
        $data = $request->all();

        if ($request->input('behind_position') && $request->input('before_position')) {
            return $this->responseFail('不可"台前","幕后"同时勾选');
        }

        \Log::info('user:profile_update:' . json_encode($data));
        if ($request->input('behind_position') && $request->input('before_position')) {
            return $this->responseFail('不可"台前","幕后"同时勾选');
        }
        $user = User::find($user_id);
        if ($request->get('id')) {
            $profile = Profile::find($data['id']);
        }

        //这个is_null的判断是因为ios在用户没有profile的时候会传递一个"(null)"字符串...的user_id
        //而数据库里有几条不知为什么存在的user_id是0的数据,正好可以查出来...
        if (!isset($profile) && ($user_id != '(null)')) {
            $profile = Profile::where("user_id", $user_id)->first();
        }

        //如果没有艺人资料,要创建一个新的艺人资料
        //安卓传递的参数是user_id
        //ios传递的参数是editor_id
        if (!isset($profile)) {
            if ($user_id != '(null)') {
                $profile = Profile::create(['user_id' => $user_id]);
            }
            else {
                $profile = Profile::create(['user_id' => $data['editor_id']]);
            }
        }

        $oldVideoInfo = [
            'self_video_url'       => $profile->self_video_url,
            'collection_video_url' => $profile->collection_video_url
        ];

        $profile->update($data);
        if (isset($data['name'])) {
            User::where('FID', $user_id)->update(['FNAME' => $data['name']]);
        }

        $this->snapProfileVideoShoot($profile, $oldVideoInfo);

        $album1 = $request->get("albums1");
        $this->create_ablum($album1, "形象照", $profile);
        $album2 = $request->get("albums2");
        $this->create_ablum($album2, "剧照", $profile);
        $editor_id = $request->get("editor_id");
        if ($editor_id) {
            $profile_record['user_id']    = $editor_id;
            $profile_record['profile_id'] = $profile->id;
            ProfileRecord::create($profile_record);
        }

        return response()->json(["ret" => 0, "profile" => $profile, "msg" => "保存成功"]);
    }

    /**
     * @param $album1
     * @param $title
     * @param $profile
     */
    public function create_ablum($album1, $title, $profile)
    {
        Album::where("title", $title)->where("profile_id", $profile->id)->delete();
        $arr = explode(",", $album1);
        if (count($arr) > 0) {
            $album             = new Album;
            $album->title      = $title;
            $album->color      = "#00FF00";
            $album->profile_id = $profile->id;
            $album->save();

            foreach ($arr as $a) {
                $pictrue           = new Picture;
                $pictrue->url      = $a;
                $pictrue->album_id = $album->id;
                $pictrue->save();
            }
        }
    }

    /**
     * @param Request $request
     * @param         $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function recruits(Request $request, $user_id)
    {
        $current_user_id = $this->current_user($request);

        $type     = $request->get("type");
        $wherearr = " author_id = '" . $user_id . "'";
        $arr      = [];
        $dates    = Recruit::selectRaw("SUBSTRING(created_at,1,10) as d")->whereRaw($wherearr)->orderby("d",
            "desc")->groupBy("d")->get();
        foreach ($dates as $date) {
            $recruits = Recruit::whereRaw($wherearr)->where("created_at", "like", $date->d . "%")->orderby("id",
                "desc")->paginate(15);
            $arr[]    = ["date" => $date->d, "data" => $recruits->toArray()['data']];
        }
        return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $arr]);
    }

    /**
     * @param Request $request
     * @param         $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function blogs(Request $request, $user_id)
    {
        $type        = $request->get("type");
        $is_approved = $request->get('is_approved');
        //Log::info($is_approved);
        $arr = [];
        if ($type) {
            $wherearr = "type = '" . $type . "' and author_id = '" . $user_id . "' and is_delete = 0  and is_approved = " . $is_approved;

            $dates = Blog::selectRaw("SUBSTRING(created_at,1,10) as d")->whereRaw($wherearr)->orderby("d",
                "desc")->groupBy("d")->get();
            foreach ($dates as $date) {
                $blogs = Blog::whereRaw($wherearr)->where("created_at", "like", $date->d . "%")->orderby("id",
                    "desc")->paginate(15);
                $arr[] = ["date" => $date->d, "data" => $blogs->toArray()['data']];
            }
            return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $arr]);
        }
    }

    /**
     * @param Request $request
     * @param         $user_id
     * @param         $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function blog_update(Request $request, $user_id, $id)
    {
        $data = $request->all();
        $blog = Blog::find($id);
        if ($blog) {
            //Log::info($data);
            $blog->update($data);
            return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $blog]);
        }
        return response()->json(["ret" => -99, "msg" => "操作失败"]);
    }

    /**
     * @param Request $request
     * @param         $user_id
     * @param         $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function blog_delete(Request $request, $user_id, $id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            $blog->delete();
            return response()->json(["ret" => 0, "msg" => "操作成功"]);
        }

        return response()->json(["ret" => -99, "msg" => "操作失败"]);
    }

    /**
     * @param Request $request
     * @param         $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function messages(Request $request, $user_id)
    {
        $searchMsgType = $request->get("type");
        $user          = User::find($user_id);

        if ($searchMsgType) {
            return $this->searchCertainMessageType($user_id, $searchMsgType);
        }

        $result = [];

        $this->getSystemMessages($user, $result);

        //兼容老版本的ios和android,不传递新的字段
        if (!$request->hasHeader('app-version') ||
            ($request->hasHeader('app-version') && version_compare($request->header('app-version'), '3.2.1', '>'))
        ) {

            $this->getFriendApplications($user, $result);

            $this->getChatGroups($user, $result);

            $this->getFriends($user, $result);
        }

        return response()->json(["messages" => $result]);
    }

    /**
     * @param Request $request
     * @param         $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorites(Request $request, $user_id)
    {
        $type = $request->get("type");

        $current_user_id = $this->current_user($request);
        $arr             = [];
        if ($type == "juzu" or $type == "news") {
            $favorite_ids = Favorite::where("type", $type)->where("user_id", $user_id)->lists("favorite_id");
            $ids          = "0";
            foreach ($favorite_ids as $id) {
                $ids .= "," . $id;
            }
            $wherearr = "type = '" . $type . "'  and is_delete = 0 and id in (" . $ids . ") ";
            $arr      = Blog::whereRaw($wherearr)->orderby("id", "desc")->paginate(15);
            $s        = [];
            $arrs     = [];
            $temp     = array();
            foreach ($arr as $item) {
                $arrs[$item->toArray()['d']] = "";
            }
            foreach ($arr as $blog) {
                if (array_key_exists($blog->toArray()['d'], $arrs)) {
                    $arrs[$blog->toArray()['d']][] = $blog;
                }
            }
            //dd($arrs);
            foreach ($arrs as $key => $a) {
                $s[] = ["date" => $key, "data" => $a];
            }

            return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $s]);

        }

        if ($type == "recruit") {

            $favorite_ids = Favorite::where("type", "like", "recruit%")->where("user_id",
                $user_id)->lists("favorite_id");
            $ids          = "0";
            foreach ($favorite_ids as $id) {
                $ids .= "," . $id;
            }
            //$wherearr = " is_delete = 0  and id in (".$ids.") ";

            $wherearr = " id in (" . $ids . ") ";
            $arr      = Recruit::whereRaw($wherearr)->orderby("id", "desc")->paginate(15);
            $s        = [];
            $arrs     = [];
            $temp     = array();
            foreach ($arr as $item) {
                $arrs[$item->toArray()['d']] = "";
            }
            foreach ($arr as $recruit) {
                if (array_key_exists($recruit->toArray()['d'], $arrs)) {
                    $recruit->is_favorite             = $this->is_favorite($current_user_id, 'recruit', $recruit->id);
                    $arrs[$recruit->toArray()['d']][] = $recruit;
                }
            }
            foreach ($arrs as $key => $a) {
                $s[] = ["date" => $key, "data" => $a];
            }
            return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $s]);

        }
        if ($type == "profile") {

            $favorite_ids = Favorite::where("type", "user")->where("user_id", $user_id)->lists("favorite_id");
            $ids          = "0";
            foreach ($favorite_ids as $id) {
                $ids .= "," . $id;
            }
            $wherearr = "  id in (" . $ids . ") ";
            //Log::info($wherearr);
            $profiles = Profile::whereRaw($wherearr)->orderby("id", "desc")->paginate(15);
            $json     = [];
            $jsons    = [];
            foreach ($profiles as $profile) {
                $json['id']            = $profile->id;
                $json['name']          = $profile->name;
                $json['is_favorite']   = $this->is_favorite($current_user_id, "user", $profile->id);
                $json['is_liked']      = $this->is_liked($current_user_id, "user", $profile->id);
                $json['like_count']    = Like::where("type", "user")->where("like_id", $profile->id)->count();
                $json['user_id']       = $profile->user_id;
                $json['type']          = $profile->type;
                $json['avatar']        = $profile->avatar;
                $json['birthday']      = $profile->birthday;
                $json['email']         = $profile->email;
                $json['hometown']      = $profile->hometown;
                $json['height']        = $profile->height;
                $json['weight']        = $profile->weight;
                $json['mobile']        = $profile->mobile;
                $json['gender']        = $profile->gender;
                $json['language']      = $profile->language;
                $json['college']       = $profile->college;
                $json['speciality']    = $profile->speciality;
                $json['introduction']  = $profile->introduction;
                $json['constellation'] = $profile->constellation;
                $json['blood_type']    = $profile->blood_type;
                $json['work_ex']       = $profile->work_ex;
                $json['prize_ex']      = $profile->prize_ex;
                $json['pic_urls']      = $profile->pic_urls();
                $jsons[]               = $json;
            }
            return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $jsons]);
        }
    }

    /**
     * @param Profile $profile
     * @param         $oldVideoInfo
     * @internal param $data
     */
    public function snapProfileVideoShoot(Profile $profile, $oldVideoInfo)
    {
        if ($profile->self_video_url != $oldVideoInfo['self_video_url']) {
            $profile->snapSelfVideoShoot();
        }

        if ($profile->collection_video_url != $oldVideoInfo['collection_video_url']) {
            $profile->snapCollectionVideoShoot();
        }
    }

    /**
     * @param $user_id
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    private function searchCertainMessageType($user_id, $type)
    {
        $arr      = [];
        $wherearr = "type = '" . $type . "' and scope_ids like '%" . $user_id . "%'";
        if ($type == "SYSTEM") {
            $wherearr = "type = '" . $type . "' and ( scope = 0 or scope_ids like '%" . $user_id . "%')";
        }
        $arr  = Message::notUndo()->whereRaw($wherearr)->orderby("id", "desc")->paginate(15);
        $arrs = [];
        $s    = [];
        $temp = array();
        foreach ($arr as $item) {
            $arrs[$item->toArray()['d']] = "";
        }
        foreach ($arr as $message) {
            if (array_key_exists($message->toArray()['d'], $arrs)) {
                $arrs[$message->toArray()['d']][] = $message;
            }
        }
        foreach ($arrs as $key => $a) {
            $s[] = ["date" => $key, "data" => $a];
        }

        return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $s]);
    }

    /**
     * Get the system messages
     * @param User $user
     * @param      $result
     */
    private function getSystemMessages(User $user, &$result)
    {
        $message = Message::notUndo()->sendToUserWithType($user->FID, Message::TYPE_SYSTEM)->orderby("id",
            "desc")->first();

        if ($message) {
            $temp                  = $message->toArray();
            $temp['content']       = $temp['title'];
            $temp['un_read_count'] = 0;#$user->unReadMessages($messageType)->count();
            $result []             = $temp;
        }
    }

    /**
     * 获取环信聊天群组消息
     * @param User $user
     * @param      $result
     */
    private function getChatGroups(User $user, &$result)
    {
        $chatGroups = $user->joinedHxGroups();

        foreach ($chatGroups as $chatGroup) {
            //环信群组名为: nanzhu_group_{groupId}
            $groupName = $chatGroup['groupname'];

            //判断是剧组群组还是部门群组
            $group = EaseUser::getGroupFromName($groupName);
            if (!$group) {
                $group = CustomHxGroup::where('hx_group_id', $chatGroup['groupid'])->first();

                if (!$group) {
                    continue;
                }
                $chatGroup['type']          = Message::TYPE_CHAT_GROUP;
                $chatGroup['title']         = $group->hx_title;
                $chatGroup['cover_url']     = $group->cover_url;
                $chatGroup['members_count'] = count($group->getHxMembers());
                $chatGroup['is_juzu']       = false;
            }
            else {
                $chatGroup['type']          = Message::TYPE_CHAT_GROUP;
                $chatGroup['title']         = $group->hx_title;
                $chatGroup['cover_url']     = Group::DEPARTMENT_HX_GROUP_COVER_URL;
                $chatGroup['members_count'] = count($group->getHxMembers());
                $chatGroup['is_juzu']       = true;
            }

            $result [] = $chatGroup;
        }
    }

    /**
     * @param User $user
     * @param      $result
     * @return array
     */
    private function getFriendApplications(User $user, &$result)
    {
        $unApprovedApplications = $user->receivedApplications()->notApproved()->get();

        $count                      = $unApprovedApplications->count();
        $lastApplicationCreatedDate = '';
        if ($count > 0) {
            $lastApplicationCreatedDate = $unApprovedApplications->first()->created_at;
            $lastApplicationCreatedDate->setLocale('zh');
            $lastApplicationCreatedDate = $lastApplicationCreatedDate->diffForHumans();
        }
        $result[] = [
            'type'          => Message::TYPE_FRIEND_APPLICATION,
            'title'         => '我的好友',
            'content'       => '好友申请提醒',
            'un_read_count' => $count,
            'cover_url'     => '',
            'created_at'    => $lastApplicationCreatedDate,
            'is_read'       => !(bool)$count
        ];
    }

    /**
     * 获取用户的好友信息
     * @param User $user
     * @param      $result
     */
    private function getFriends(User $user, &$result)
    {
        $friends = $user->friendUsers();

        if ($friends->count() == 0) {
            return;
        }

        foreach ($friends as $friend) {
            $result [] = [
                'type'       => Message::TYPE_FRIEND,
                'title'      => $friend->hx_name,
                'user_id'    => $friend->FID,
                'content'    => '聊天内容',
                'cover_url'  => $friend->cover_url,
                //需要前端获取
                'created_at' => '两周前',
                'is_read'    => true,
            ];
        }

    }

    /**
     * 发送邀请(前端手机通讯录邀请界面使用)
     * 1.如果手机通讯录用户没有注册,发送短信
     * 2.如果手机通讯录用户注册了,
     *      2.1 如果是好友,跳转到好友
     *      2.2 如果不是好友,发起好友申请
     * @param         $userId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvitation($userId, Request $request)
    {
        $phone       = $request->input('phone');
        $currentUser = User::find($userId);
        $invitedUser = User::where('FPHONE', $phone)->first();

        if (!$invitedUser) {
            return $this->responseUserSendInvitation($currentUser);
        }

        if ($currentUser->isFriendOfUser($invitedUser)) {
            return $this->responseJson(ResponseCodes::USER_HAD_BEEN_FRIEND, '已经是好友', [
                'user' => $invitedUser->formatBasicClass()->get()
            ]);
        }

        try {
            $currentUser->applyUserBeFriend($invitedUser, '邀请加为好友');
        } catch (FriendException $e) {
            return $this->responseFail($e->getMessage());
        }

        return $this->responseSuccess('申请加为好友成功');
    }

    /**
     * 获取用户的信息
     * 该接口现在新增一个功能,查看用户详情的时候同时要加上关联关系.
     * 是否查看的用户是否被加入当前用户黑名单
     * @param         $userId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo($userId, Request $request)
    {
        //被查看资料的用户
        \Log::info('+++' . $userId);
        $user = User::find($userId);

        if (!$user) {
            return $this->responseFail('用户没有注册App', ['is_registered' => false]);
        }

        $returnData = $user->formatBasicClass();

        //当前用户id
        $inspectUserId = $request->input('current_user_id');

        if ($inspectUserId) {
            $returnData = $returnData
                ->withBlacklistInfo($inspectUserId)
                ->withFriendInfo($inspectUserId)
                ->withServiceInfo()
                ->withRegisterInfo();
        }

        return $this->responseSuccess('操作成功', ['user' => $returnData->get()]);
    }

    /**
     * 把im中某人加入用户的黑名单
     */
    public function userBlockUser($userId, $blockUserId)
    {
        $user = User::find($userId);

        try {
            $user->blockHxUser($blockUserId);
        } catch (\Exception $e) {
            return $this->responseFail('加入黑名单失败:' . $e->getMessage());
        }

        return $this->responseSuccess('加入黑名单成功');
    }

    /**
     * 把im中某人移除用户的黑名单
     * @param $userId
     * @param $unBlockUserId
     * @return \Illuminate\Http\JsonResponse
     */
    public function userUnBlockUser($userId, $unBlockUserId)
    {
        $user = User::find($userId);

        try {
            $user->unblockHxUser($unBlockUserId);
        } catch (\Exception $e) {
            return $this->responseFail('移除黑名单失败:' . $e->getMessage());
        }

        return $this->responseSuccess('移除黑名单成功');
    }

    /**
     * 获取用户的im黑名单
     * @param         $userId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userBlackLists($userId, Request $request)
    {
        $blacklists = User::find($userId)->hxBlackLists();

        $searchValue = $request->input('q');

        if (empty($searchValue)) {

            return $this->responseSuccess('操作成功', compact('blacklists'));
        }

        $blacklists = array_filter($blacklists, function ($blackListUser) use ($searchValue) {

            $isNameContains = mb_strpos($blackListUser->user_name, $searchValue) !== false;

            $isPhoneContains = mb_strpos($blackListUser->phone, $searchValue) !== false;

            return $isNameContains || $isPhoneContains;

        });


        return $this->responseSuccess('操作成功', compact('blacklists'));
    }

    /**
     * 获取用户的协助编辑列表
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function canEditProfiles($userId)
    {
        $user = User::find($userId);

        $profilelists = $user->canEditProfiles();

        foreach ($profilelists as &$profile) {
            $is_liked    = $this->is_liked($userId, "user", $profile->id);
            $is_favorite = $this->is_favorite($userId, "user", $profile->id);
            $is_share    = $this->is_share($userId, $profile->id);
            $like_count  = Like::where("like_id", $profile->id)->count();

            //给安卓处理profile里的结果
            $profile->is_liked    = $is_liked;
            $profile->is_share    = $is_share;
            $profile->is_favorite = $is_favorite;
            $profile->like_count  = $like_count;
        }

        return $this->responseSuccess('获取成功', ['profiles' => $profilelists]);
    }

    /**
     *  获取手机通讯录里的联系人信息
     *  是否注册app,是否好友
     */
    public function getPhoneContactUserInfo($userId, Request $request)
    {
        $phone       = $request->input('phone');
        $currentUser = User::find($userId);
        $invitedUser = User::where('FPHONE', $phone)->first();
        $profileId   = Profile::where('user_id', $userId)->value('id');

        return $this->responseSuccess('操作成功', [
            'is_registered' => (boolean)$invitedUser,
            'phone'         => $phone,
            'profileId'     => $profileId,
            'is_friend'     => $invitedUser ? $currentUser->isFriendOfUser($invitedUser) : false,
            'user'          => $invitedUser ? $invitedUser->formatBasicClass()->withProfileInfo()->get() : null,#
            'share_data'    => [
                'content' => "我是{$currentUser->FNAME}，现邀您一起体验全新的剧组神器《南竹通告单＋》，快来加入！",
                'title'   => '好友邀请',
                'url'     => 'http://a.app.qq.com/o/simple.jsp?pkgname=com.zdyx.nanzhu'
            ],
        ]);

    }

    /**
     * Get all joined groups in movie.
     * @param         $userId
     * @param Request $request
     */
    public function joinedGroupsInMovie($userId, Request $request)
    {
        $user = User::find($userId);

        $groups = $user->groupsInMovie($request->input('movie_id'))->map(function ($group) {
            return [
                'id'   => $group->FID,
                'name' => $group->FNAME,
            ];
        });

        return $this->responseSuccess('成功', ['groups' => $groups]);
    }

    /**
     * Get the h5 union entry url.
     */
    public function indexH5UnionUrl()
    {
        return $this->responseSuccess('success', [
            'h5_union_url' => env('APP_URL') . '/mobile/unions/'
        ]);
    }
}

