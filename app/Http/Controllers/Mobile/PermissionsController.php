<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Managers\Powers\UserPowerManager;
use App\Models\ContactPower;
use App\Models\DailyReportPower;
use App\Models\GroupUserFeedBackPower;
use App\Models\Message;
use App\Models\Movie;
use App\Models\PreviousProspectPower;
use App\Models\ProgressPower;
use App\Models\ReceivePower;
use App\Models\ReferencePlanPower;
use App\User;
use App\Utils\StringUtil;
use DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class PermissionsController
 * @package App\Http\Controllers\Mobile
 */
class PermissionsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('mobile.user_must_in_movie', [
            'only' => [
                'index',  //部门管理
            ]
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $movie           = Movie::find($request->get("movie_id"));
        $iosVersion      = $this->iosVersion($request);
        $androidVersion  = $this->androidVersion($request);
        $allCount        = $movie->allMembersCount();
        $contactPercent  = $movie->allUsersWithPower(ContactPower::class)->count() . "/" . $allCount;
        $progressPercent = $movie->allUsersWithPower(ProgressPower::class)->count() . "/" . $allCount;;
        $receivePercent = $movie->allUsersWithPower(ReceivePower::class)->count() . "/" . $allCount;;
        $dailyReportPercent = $movie->allUsersWithPower(DailyReportPower::class)->count() . "/" . $allCount;;
        $planPercent = $movie->allUsersWithPower(ReferencePlanPower::class)->count() . "/" . $allCount;;
        $previousProspectPercent = $movie->allUsersWithPower(PreviousProspectPower::class)->count() . '/' . $allCount;

        return view("mobile.permissions.index", compact('contactPercent', 'progressPercent',
            'receivePercent', 'planPercent', 'dailyReportPercent', 'iosVersion', 'androidVersion',
            'previousProspectPercent'
        ));
    }

    /**
     * 剧组通讯录权限查看
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function indexContact(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersWithPower(ContactPower::class);

        $groupedUsers = StringUtil::groupByFirstChar($users->all(), 'FNAME');

        return view("mobile.permissions.contacts.index", compact('groupedUsers', 'movieId'));
    }

    /**
     * 拍摄进度权限
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function indexProgress(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersWithPower(ProgressPower::class);

        $groupedUsers = StringUtil::groupByFirstChar($users->all(), 'FNAME');

        return view("mobile.permissions.progress.index", compact('groupedUsers', 'movieId'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function indexReceive(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersWithPower(ReceivePower::class);

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view("mobile.permissions.receive.index", compact('groupedUsers', 'movieId'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function indexTransfor(Request $request)
    {
        $admin = Movie::where('FID', $request->input('movie_id'))->first()->admin();

        return view("mobile.permissions.transfor.index", compact('admin'));
    }

    /**
     * 显示添加删除剧组通讯录权限界面
     *
     * @param Request $request
     *
     * @return View
     */
    public function createContact(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.contacts.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * 更新剧组通讯录权限
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeContact(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId  = $request->input('user_id');

        //因为3.3.7版本添加的参考大计划需要做版本控制,所以需要android以及ios传递版本号
        //ios可以做到全局header,即便在h5里来回跳转都有
        //android只能做到从工作台进入h5第一次有header,所以需要手动加到url里
        $androidVersion = $this->androidVersion($request);

        //清空所有权限,添加勾选的新权限,省去了添加或者删除的时候要判断是否已经存在
        ContactPower::clearAllPowerInMovie($movieId);

        foreach ($request->input('contactPower') as $item) {
            $item = json_decode($item);
            $user = User::find($item->userId);

            if ((!$item->checked) || (!$user)) {
                continue;
            }

            UserPowerManager::assignUserPower($user, $movieId, ContactPower::class);
        }

        return redirect()->to("/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置");
    }

    /**
     * 显示添加/删除拍摄进度权限的界面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function createProgress(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.progress.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * 更新拍摄进度权限
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProgress(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId  = $request->input('user_id');

        $androidVersion = $this->androidVersion($request);

        //清空所有权限,添加勾选的新权限,省去了添加或者删除的时候要判断是否已经存在
        ProgressPower::clearAllPowerInMovie($movieId);

        foreach ($request->input('contactPower') as $item) {
            $item = json_decode($item);

            $user = User::find($item->userId);

            if ((!$item->checked) || (!$user)) {
                continue;
            }

            ProgressPower::assignUser($user, $movieId);
        }

        return redirect()->to("/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置");
    }

    /**
     * 显示添加/删除接受详情权限的界面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function createReceive(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.receive.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * 更新接受详情权限
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReceive(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId  = $request->input('user_id');

        $androidVersion = $this->androidVersion($request);

        //清空所有权限,添加勾选的新权限,省去了添加或者删除的时候要判断是否已经存在
        ReceivePower::clearAllPowerInMovie($movieId);

        foreach ($request->input('contactPower') as $item) {
            $item = json_decode($item);

            $user = User::find($item->userId);

            if ((!$item->checked) || (!$user)) {
                continue;
            }

            UserPowerManager::assignUserPower($user, $movieId, ReceivePower::class);
        }

        return redirect()->to("/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置");
    }

    /**
     * 显示最高权限转移界面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function createTransfor(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.transfor.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * 更新最高权限
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTransfor(Request $request)
    {
        $newAdminUserId = $request->input('subBox');
        $movieId        = $request->input('movie_id');
        $userId         = $request->input('user_id');

        $androidVersion = $this->androidVersion($request);

        UserPowerManager::transforMovieAdmin($newAdminUserId, $movieId);

        return response()->json([
            'success' => true,
            'msg'     => '操作成功',
            'data'    => ['redirect_url' => "/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置"]
        ]);
    }

    /**
     * 显示添加删除参考大计划权限界面
     *
     * @param Request $request
     *
     * @return View
     */
    public function createReferencePlan(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.reference_plan.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * 显示添加删除场记日报表权限界面
     *
     * @param Request $request
     *
     * @return View
     */
    public function createDailyReport(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.daily_report.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * 更新参考场记日报表
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDailyReport(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId  = $request->input('user_id');
        $movie   = Movie::find($movieId);

        $androidVersion = $this->androidVersion($request);

        //清空所有权限,添加勾选的新权限,省去了添加或者删除的时候要判断是否已经存在
        DailyReportPower::clearAllPowerInMovie($movieId);

        foreach ($request->input('daily_report_powers') as $item) {
            $item = json_decode($item);

            $user = User::find($item->userId);

            if ((!$item->checked) || (!$user)) {
                continue;
            }

            UserPowerManager::assignUserPower($user, $movieId, DailyReportPower::class);

            //Whenever a user assigned with the daily report power, show him all existing daily reports in this movie.
            $movie->addUserToMessageReceiver($userId, Message::TYPE_DAILY_REPORT);
        }

        return redirect()->to("/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置");
    }

    /**
     * 更新参考大计划权限
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReferencePlan(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId  = $request->input('user_id');

        $androidVersion = $this->androidVersion($request);

        //清空所有权限,添加勾选的新权限,省去了添加或者删除的时候要判断是否已经存在
        ReferencePlanPower::clearAllPowerInMovie($movieId);

        foreach ($request->input('reference_plan_powers') as $item) {
            $item = json_decode($item);

            $user = User::find($item->userId);

            if ((!$item->checked) || (!$user)) {
                continue;
            }

            UserPowerManager::assignUserPower($user, $movieId, ReferencePlanPower::class);
        }

        return redirect()->to("/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置");
    }

    /**
     * Show the create previous prospect power page.
     *
     * @param Request $request
     *
     * @return View
     */
    public function createPreviousProspect(Request $request)
    {
        $movieId = $request->input('movie_id');

        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.previous_prospect.create', compact('groupedUsers', 'movieId'));
    }

    /**
     * Store the new previous prospect power.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePreviousProspect(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId  = $request->input('user_id');
        $movie   = Movie::find($movieId);

        $androidVersion = $this->androidVersion($request);

        //清空所有权限,添加勾选的新权限,省去了添加或者删除的时候要判断是否已经存在
        PreviousProspectPower::clearAllPowerInMovie($movieId);
        foreach ($request->input('previous_prospect_powers') as $item) {
            $item = json_decode($item);

			$user = User::find($item->userId);


            if ((!$item->checked) || (!$user)) {
                continue;
            }

            UserPowerManager::assignUserPower($user, $movieId, PreviousProspectPower::class);

            //Whenever a user assigned with the daily report power, show him all existing daily reports in this movie.
            $movie->addUserToMessageReceiver($userId, Message::TYPE_PREVIOUS_PROSPECT);
        }

        return redirect()->to("/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置");
    }

    /**
     * Show the create previous prospect power page.
     *
     * @param Request $request
     *
     * @return View
     */
    public function createGroupuserFeedback(Request $request)
    {
        $movieId = $request->input('movie_id');

        $movie = Movie::find($movieId);
        $users = Movie::find($movieId)->allUsersInMovie();

        $groupedUsers = StringUtil::groupByFirstChar($users, 'FNAME');

        return view('mobile.permissions.groupuser_feedback.create', compact('groupedUsers', 'movieId', 'movie'));
    }

    /**
     * Store the new previous prospect power.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeGroupuserFeedback(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId  = $request->input('user_id');
        $movie   = Movie::find($movieId);

        $androidVersion = $this->androidVersion($request);

        //清空所有权限,添加勾选的新权限,省去了添加或者删除的时候要判断是否已经存在
        GroupUserFeedBackPower::clearAllPowerInMovie($movieId);

        foreach ($request->input('groupuser_feedback_powers') as $item) {
            $item = json_decode($item);

            $user = User::find($item->userId);

            if ((!$item->checked) || (!$user)) {
                continue;
            }

            UserPowerManager::assignUserPower($user, $movieId, GroupUserFeedBackPower::class);

            //Whenever a user assigned with the daily report power, show him all existing daily reports in this movie.
            $movie->addUserToMessageReceiver($userId, Message::TYPE_GROUPUSER_FEEDBACK);
        }

        return redirect()->to("/mobile/permissions?movie_id={$movieId}&user_id={$userId}&androidVer={$androidVersion}&title=权限与设置");
    }

    /**
     * Toggle the movie's groupuser feedback switch.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleGroupUserFeedback(Request $request)
    {
        Movie::where('FID', $movieId = $request->input('movie_id'))->update([
            'is_groupuser_feedback_open' => DB::raw('! is_groupuser_feedback_open')
        ]);

        return $this->ajaxResponseSuccess();
    }
}
