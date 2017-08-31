<?php

namespace App\Http\Controllers\Api;

use App\Models\ContactPower;
use App\Models\DailyReportPower;
use App\Models\GroupUser;
use App\Models\GroupUserFeedBackPower;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Notice;
use App\Models\PreviousProspectPower;
use App\Models\ProgressPower;
use App\Models\ReferencePlanPower;
use App\Models\Union;
use App\User;
use Illuminate\Http\Request;

class MenusController extends BaseController
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user_id = $request->get("user_id");

        $user = User::find($user_id);

        $allJoinMovies = $user->joinedNotEndMovies();

        //用户加入的所有剧组
        $allJoinedMoviesData = $this->formatJoinedNotEndMoviesInfo($user, $allJoinMovies);

        //如果用户加入了剧组,那么默认显示他最新的一个剧组
        $movieId = $allJoinMovies->count() > 0 ? $allJoinMovies->first()->FID : '';

        //如果用户指定查看了某一个剧组,查看这个剧组
        if ($request->input("movie_id")) {
            $movieId = $request->input("movie_id");
        }

        if (!$movieId) {
            return response()->json([
                "is_tongchou"         => false,
                "movies"              => $allJoinedMoviesData,
                "current_movie_menus" => [],
                'is_in_black'         => $user->is_in_black
            ]);
        }

        list($isTongchou, $menusArary, $currentMovieTotalUnReadCount) = $this->getMenus($movieId, $user, $request);

        return response()->json([
            "is_tongchou"                      => (boolean)$isTongchou,
            "movies"                           => $allJoinedMoviesData,
            'all_movies_total_unread_count'    => $allJoinedMoviesData->pluck('un_read_count')->sum(),
            'current_movie_total_unread_count' => $currentMovieTotalUnReadCount,
            "current_movie_menus"              => $menusArary,
            "union_unread_count"               => $user->unionUnReadCount(),
            'is_in_black'                      => $user->is_in_black
        ]);
    }

    /**
     * @param User $user
     * @param      $userJoinedNotEndMovies
     * @return mixed
     */
    private function formatJoinedNotEndMoviesInfo(User $user, $userJoinedNotEndMovies)
    {
        return $userJoinedNotEndMovies->map(function ($movie) use ($user) {
            $obj                = new \stdClass();
            $obj->movie_id      = $movie->FID;
            $obj->movie_name    = $movie->FNAME;
            $obj->movie_type    = $movie->FTYPE;
            $obj->un_read_count = $user->stat_manager->allUnReadMessagesCountInMovie($movie->FID);
            return $obj;
        });
    }

    /**
     * 获取每日通告单菜单url
     * @param      $movieId
     * @param User $user
     * @param      $currentMovieTotalUnReadCount
     * @return array
     */
    private function getDailyNoticeMenuUrl($movieId, User $user, &$currentMovieTotalUnReadCount)
    {
        $unreadDailyNoticeNumber      = $user->stat_manager->unReadNoticeMessageCountInMovie($movieId,
            Notice::TYPE_DAILY);
        $currentMovieTotalUnReadCount += $unreadDailyNoticeNumber;

        return [
            "url"      => "/mobile/notices?type=10&movie_id={$movieId}&user_id={$user->FID}&title=每日通告单",
            "title"    => "每日通告单",
            "number"   => $unreadDailyNoticeNumber,
            'movie_id' => $movieId
        ];
    }

    /**
     * 获取剧组通知菜单url
     * @param      $movieId
     * @param User $user
     * @param      $currentMovieTotalUnReadCount
     * @return array
     */
    private function getJuzuMenuUrl($movieId, User $user, &$currentMovieTotalUnReadCount)
    {
        $unReadJuzuNumber             = $user->stat_manager->unReadCertainTypeMessageCountInMovie($movieId,
            Message::TYPE_JUZU);
        $currentMovieTotalUnReadCount += $unReadJuzuNumber;

        return [
            "url"      => "/mobile/users/{$user->FID}/messages?type=juzu&movie_id={$movieId}&user_id={$user->FID}&title=剧组通知",
            "title"    => "剧组通知",
            "number"   => $unReadJuzuNumber,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @param      $currentMovieTotalUnReadCount
     * @return array
     * @internal param $user_id
     * @internal param $unReadBlogNumber
     */
    private function getFeiyeMenuUrl($movieId, User $user, &$currentMovieTotalUnReadCount)
    {
        $unReadFeiyeNumber            = $user->stat_manager->unReadCertainTypeMessageCountInMovie($movieId,
            Message::TYPE_BLOG);
        $currentMovieTotalUnReadCount += $unReadFeiyeNumber;
        return [
            "url"      => "/mobile/users/{$user->FID}/messages?type=blog&movie_id={$movieId}&user_id={$user->FID}&title=剧本扉页",
            "title"    => "剧本扉页",
            "number"   => $unReadFeiyeNumber,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @param      $currentMovieTotalUnReadCount
     * @return array
     */
    private function getPrepareNoticeMenuUrl($movieId, User $user, &$currentMovieTotalUnReadCount)
    {
        $unReadPrepareNoticeNumber = $user->stat_manager->unReadNoticeMessageCountInMovie($movieId,
            Notice::TYPE_PREPARE);

        $currentMovieTotalUnReadCount += $unReadPrepareNoticeNumber;
        return [
            "url"      => "/mobile/notices?type=20&movie_id={$movieId}&user_id={$user->FID}&title=预备通告单",
            "title"    => "预备通告单",
            "number"   => $unReadPrepareNoticeNumber,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     */
    private function getPublicContactMenuUrl($movieId, User $user)
    {
        return [
            "url"      => "/mobile/users/public_contact?movie_id={$movieId}&user_id={$user->FID}",
            "title"    => "公开电话",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     */
    private function getContactMenuUrl($movieId, User $user)
    {
        return [
            "url"      => "/mobile/users/contact?movie_id={$movieId}&user_id={$user->FID}",
            "title"    => "剧组通讯录",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * 参考大计划菜单url
     * @param      $movieId
     * @param User $user
     * @param      $currentMovieTotalUnReadCount
     * @return array
     */
    private function getReferencePlanMenuUrl($movieId, User $user, &$currentMovieTotalUnReadCount)
    {
        $unReadPlanNumber             = $user->stat_manager->unReadCertainTypeMessageCountInMovie($movieId,
            Message::TYPE_PLAN);
        $currentMovieTotalUnReadCount += $unReadPlanNumber;

        return [
            "url"      => "#",
            "title"    => "参考大计划",
            "number"   => $unReadPlanNumber,
            'movie_id' => $movieId
        ];
    }

    /**
     * 场记日报表菜单url
     * @param      $movieId
     * @param User $user
     * @param      $currentMovieTotalUnReadCount
     * @return array
     */
    private function getDailyReportMenuUrl($movieId, User $user, &$currentMovieTotalUnReadCount)
    {
        $unReadPlanNumber             = $user->stat_manager->unReadCertainTypeMessageCountInMovie($movieId,
            Message::TYPE_DAILY_REPORT);
        $currentMovieTotalUnReadCount += $unReadPlanNumber;

        return [
            "url"      => "#",
            "title"    => "场记日报表",
            "number"   => $unReadPlanNumber,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     */
    private function getDailyChartMenuUrl($movieId, User $user)
    {
        return [
            "url"      => "/mobile/charts/daily?movie_id={$movieId}&user_id={$user->FID}&title=每日数据",
            "title"    => "每日数据",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param $movieId
     * @return array
     */
    private function getChartShapeMenuUrl($movieId)
    {
        return [
            "url"      => "https://chart.nanzhuxinyu.com/chart/charts/progresschart.jsp?chartId=2&juzuId=" . $movieId . "&apiToken=&userToken=&chartEnv=&title=数据图形",
            "title"    => "数据图形",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     * @internal param $user_id
     */
    private function getTotalChartMenuUrl($movieId, User $user)
    {
        return [
            "url"      => "/mobile/charts/all?movie_id={$movieId}&user_id={$user->FID}&title=总数据",
            "title"    => "总数据",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     * @internal param $user_id
     */
    private function getMovieInfoMenuUrl($movieId, User $user)
    {
        return [
            "url"      => "/mobile/movies/{$movieId}&user_id={$user->FID}?title=剧组信息",
            "title"    => "剧组信息",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     */
    private function getGroupListMenuUrl($movieId, User $user)
    {
        return [
            "url"      => "/mobile/groups?movie_id={$movieId}&user_id={$user->FID}&title=部门列表",
            "title"    => "部门列表",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @param      $iosVersion
     * @param      $androidVersion
     * @return array
     * @internal param $user_id
     */
    private function getPermissionMenuUrl($movieId, User $user, $iosVersion, $androidVersion)
    {
        $title = '权限管理';
        if (version_compare($iosVersion, '3.6.7', '>=') || version_compare($androidVersion, '3.6.5', '>=')) {
            $title = '权限与设置';
        }
        return [
            "url"      => "/mobile/permissions?user_id={$user->FID}&movie_id={$movieId}&title={$title}",
            "title"    => $title,
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     */
    private function getUserInGroupMenuUrl($movieId, User $user)
    {
        return [
            "url"      => "/mobile/users/{$user->FID}/group?movie_id={$movieId}&user_id={$user->FID}",
            "title"    => "我在本组",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param      $movieId
     * @param User $user
     * @return array
     */
    private function getGroupManageMenuUrl($movieId, User $user)
    {
        $firstGroup = $user->groupsInMovie($movieId)->first();

        $groupMembersCount = $firstGroup ? $firstGroup->members()->count() : 0;

        return [
            "url"      => "/mobile/groups/manage?movie_id={$movieId}&user_id={$user->FID}&title=部门管理&percent={$groupMembersCount}",
            "title"    => "部门管理",
            "number"   => 0,
            'movie_id' => $movieId
        ];
    }

    /**
     * @param         $movieId
     * @param User    $user
     * @param Request $request
     * @return array
     */
    private function getMenus($movieId, User $user, Request $request)
    {
        $totalUnReadCount = 0;
        $isTongchou       = GroupUser::is_tongchou($movieId, $user->FID);
        $androidVersion   = $this->androidVersion($request);
        $iosVersion       = $this->iosVersion($request);

        //默认第一个为每日通告单
        $menusArary = [$this->getDailyNoticeMenuUrl($movieId, $user, $totalUnReadCount)];

        //菜单是有顺序的,所以按顺序判断
        if ($isTongchou || $user->stat_manager->canSeePrepareNoticeMenuInMovie($movieId)) {
            array_push($menusArary, $this->getPrepareNoticeMenuUrl($movieId, $user, $totalUnReadCount));
        }

        if (version_compare($iosVersion, '3.6.3', '>=') || version_compare($androidVersion, '3.6.2', '>=')) {
            if ($user->hadAssignedPowerInMovie($movieId, PreviousProspectPower::class)) {
                array_push($menusArary, $this->getPreviousProspectMenu($movieId, $user, $totalUnReadCount));
            }
        }

        //Only when client's version bigger than the reference plan version, show the menu.
        if (version_compare($iosVersion, '3.3.7', '>=') || version_compare($androidVersion, '3.3.6', '>=')) {
            //If the user is the admin of the movie,or in tongchou department.
            //Show the reference plan menu wheather she/he is assigned with the power.
            if ($user->hadAssignedPowerInMovie($movieId, ReferencePlanPower::class)) {
                array_push($menusArary, $this->getReferencePlanMenuUrl($movieId, $user, $totalUnReadCount));
            }
        }

        array_push($menusArary,
            $this->getJuzuMenuUrl($movieId, $user, $totalUnReadCount),         //剧组通知
            $this->getFeiyeMenuUrl($movieId, $user, $totalUnReadCount),        //剧本扉页
            $this->getPublicContactMenuUrl($movieId, $user) //公开电话
        );

        //剧组通讯录
        if ($user->hadAssignedPowerInMovie($movieId, ContactPower::class)) {
            array_push($menusArary, $this->getContactMenuUrl($movieId, $user));
        }

        //部门管理
        if ($user->isLeaderInMovie($movieId)) {
            array_push($menusArary, $this->getGroupManageMenuUrl($movieId, $user));
        }

        //3.4.1版本添加了场记日报表
        if (version_compare($iosVersion, '3.4.1', '>=') || version_compare($androidVersion, '3.4.1', '>=')) {
            //Just like the reference plan, show the daily report menu,when a user is in chang ji department,or
            //she's with the coresponding power.
            if ($user->hadAssignedPowerInMovie($movieId, DailyReportPower::class)) {
                array_push($menusArary, $this->getDailyReportMenuUrl($movieId, $user, $totalUnReadCount));
            }
        }

        //拍摄进度菜单
        if ($user->hadAssignedPowerInMovie($movieId, ProgressPower::class)) {
            array_push($menusArary,
                $this->getDailyChartMenuUrl($movieId, $user),
                $this->getChartShapeMenuUrl($movieId),
                $this->getTotalChartMenuUrl($movieId, $user)
            );
        }

        //建剧人看到的
        if ($user->isAdminOfMovie($movieId)) {
            array_push($menusArary,
                $this->getMovieInfoMenuUrl($movieId, $user),
                $this->getGroupListMenuUrl($movieId, $user),
                $this->getPermissionMenuUrl($movieId, $user, $iosVersion, $androidVersion)
            );
        }

        array_push($menusArary,
            $this->getUserInGroupMenuUrl($movieId, $user) //我在本组
        );

        //Only the version greater than ios:3.6.4, and android:3.6.3 can see the groupuser feedback menu.
        if (version_compare($iosVersion, '3.6.4', '>=') || version_compare($androidVersion, '3.6.3', '>=')) {
            $movie = Movie::find($movieId);
            if ($movie->is_groupuser_feedback_open &&
                $user->hadAssignedPowerInMovie($movieId, GroupUserFeedBackPower::class)
            ) {
                array_push($menusArary, $this->getGroupUserFeedbackMenu($movieId, $user, $totalUnReadCount));
            }
        }

        return [$isTongchou, $menusArary, $totalUnReadCount];
    }

    /**
     * Get the previous prospect menu.
     * @param      $movieId
     * @param User $user
     * @param      $totalUnReadCount
     * @return array
     */
    private function getPreviousProspectMenu($movieId, User $user, &$totalUnReadCount)
    {
        $unReadPlanNumber = $user->stat_manager->unReadCertainTypeMessageCountInMovie($movieId,
            Message::TYPE_PREVIOUS_PROSPECT);
        $totalUnReadCount += $unReadPlanNumber;

        return [
            "url"      => "#",
            "title"    => "勘景与资料",
            "number"   => $unReadPlanNumber,
            'movie_id' => $movieId
        ];
    }

    /**
     * Get the group user feedback menu.
     * @param      $movieId
     * @param User $user
     * @param      $totalUnReadCount
     * @return array
     */
    private function getGroupUserFeedbackMenu($movieId, User $user, &$totalUnReadCount)
    {
        $unReadPlanNumber = $user->stat_manager->unReadCertainTypeMessageCountInMovie($movieId,
            Message::TYPE_GROUPUSER_FEEDBACK);
        $totalUnReadCount += $unReadPlanNumber;
        return [
            "url"      => "#",
            "title"    => "组员反馈",
            "number"   => $unReadPlanNumber,
            'movie_id' => $movieId
        ];
    }

}
