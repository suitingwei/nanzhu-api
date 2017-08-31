<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\GroupUser;
use App\Models\Movie;
use App\Models\ProgressDailyData;
use App\Models\ProgressDailyGroupData;
use App\Models\ProgressTotalData;
use App\Models\PushRecord;
use DB;
use Illuminate\Http\Request;

/**
 * Class ChartsController
 * @package App\Http\Controllers\Mobile
 */
class ChartsController extends BaseController
{
    public function __construct()
    {
        //用户必须在这个剧组
        $this->middleware('mobile.user_must_in_movie', ['only' => ['all', 'daily']]);

        //显示每日数据的时候检测每日数据必须先填写了总数据
        $this->middleware('mobile.daily_data_without_total', ['only' => 'daily']);

        //更新每日数据的时候,如果之前的天里有没填写的数据,不允许更新今日数据,必须要更新以前的数据
        $this->middleware('mobile.update_daily_data_must_fulfill_past', ['only' => 'update_daily']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("mobile.charts.index");
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function all(Request $request)
    {
        $userId = $this->current_user($request);

        $movie_id = $request->input("movie_id");

        $totaldata = ProgressTotalData::where("FMOVIEID", $movie_id)->first();

        $token = $request->hasHeader('X-Auth-Token') ? $request->header('X-Auth-Token') : $request->input('token');

        $isTongChou = GroupUser::is_tongchou($movie_id, $userId);

        return view("mobile.charts.all",
            [
                "user_id"    => $userId,
                'movie_id'   => $movie_id,
                "totaldata"  => $totaldata,
                'token'      => $token,
                'isTongChou' => $isTongChou
            ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_all(Request $request)
    {
        $data = $request->all();

        $data['ftotalpage']  = empty($data['ftotalpage']) ? 0 : $data['ftotalpage'];
        $data['ftotalscene'] = empty($data['ftotalscene']) ? 0 : $data['ftotalscene'];

        $totaldata = DB::table("t_biz_progresstotaldata")->where("FMOVIEID", $data['movie_id'])->first();
        if ($totaldata) {
            DB::table("t_biz_progresstotaldata")->where("FMOVIEID", $data['FMOVIEID'])->update([
                "FSTARTDATE"  => $data['fstartdate'],
                "FTOTALDAY"   => $data['ftotalday'],
                "FTOTALPAGE"  => $data['ftotalpage'],
                "FTOTALSCENE" => $data['ftotalscene']
            ]);
        }
        $data['FID'] = DB::table("t_biz_progresstotaldata")->max("FID") + 1;
        DB::table("t_biz_progresstotaldata")->insert([
            "FID"         => $data['FID'],
            "FMOVIEID"    => $data['FMOVIEID'],
            "FSTARTDATE"  => $data['fstartdate'],
            "FTOTALDAY"   => $data['ftotalday'],
            "FTOTALPAGE"  => $data['ftotalpage'],
            "FTOTALSCENE" => $data['ftotalscene']
        ]);
        return response()->json([
            'success'      => true,
            'msg'          => '保存成功',
            'redirect_url' => "/mobile/charts/all?movie_id={$data['movie_id']}&title=总数据&token={$request->input('token')}"
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily(Request $request)
    {
        $movieId = $request->get("movie_id");
        $userId  = $this->current_user($request);
        $date    = $this->currentDate($request);

        $dailyProgress = ProgressDailyData::where("FMOVIEID", $movieId)->where("FDATE", $date)->first();

        $group_arr = [];

        if ($dailyProgress) {
            //更新每日数据
            $dailyProgress->updateProgressData();

            $groupDatas = $dailyProgress->groupDatas;
            if ($groupDatas->count() > 0) {
                foreach ($groupDatas as $g) {
                    $group_arr[$g->FGROUPID] = $g;
                }
            }
        }

        return view("mobile.charts.daily", [
            "user_id"    => $userId,
            "movie_id"   => $movieId,
            "day"        => $date,
            "daily"      => $dailyProgress,
            "groupdatas" => $group_arr,
            "error"      => false
        ]);
    }

    /**
     * 更新每日数据
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_daily(Request $request)
    {
        $requestData = $request->all();

        \Log::info('更新每日数据输入参数:' . json_encode($requestData));
        $dailyProgress = ProgressDailyData::where([
            "FMOVIEID" => $requestData['movie_id'],
            "FDATE"    => $requestData['FDATE']
        ])->first();

        if ($dailyProgress) {
            $this->updateExistedDailyGroupData($dailyProgress, $requestData);
        } else {
            //同一个接口即用于新建数据,也用于更新数据
            $dailyProgress = $this->createNewDailyAndGroupData($requestData);
        }

        //计算每日数据的统计数据
        $dailyProgress->updateProgressData();

        #每日数据更新的推送
        //$this->notifyDailyDataUpdated($requestData['movie_id'],$requestData['FDATE']);

        return response()->json([
            'success'      => true,
            'msg'          => '保存成功!',
            'redirect_url' => "/mobile/charts/daily?day={$requestData['FDATE']}&movie_id={$requestData['movie_id']}&user_id={$requestData['user_id']}"
        ]);
    }


    /**
     * 通知有权限的人,每日数据更新了
     *
     * @param $movieId
     *
     * @param $day
     *
     * @return bool
     */
    private function notifyDailyDataUpdated($movieId, $day)
    {
        $shouldNotifyUsers = Movie::progressPoweredPhones($movieId);
        $movie             = Movie::find($movieId);

        $notifyMessage = "{$movie->FNAME}的每日数据更更新了";
        $extra         = [
            'uri'  => "https://dev.nanzhuxinyu.com/mobile/charts/daily?day={$day}&movie_id={$movieId}&title=" . urlencode('每日数据'),
            'type' => 'DAILY_DATA'
        ];

        $shouldNotifyUserIds = array_column($shouldNotifyUsers, 'FUSER');

        PushRecord::sendManyByUserIds($shouldNotifyUserIds, $notifyMessage, $notifyMessage, $extra);
    }

    /**
     * 更新已有的每日数据的摄影组数据
     *
     * @param $data
     * @param $dailyData
     */
    private function updateExistedDailyGroupData($dailyData, $data)
    {
        foreach ($data['group_ids'] as $key => $group_id) {
            $groupData = $dailyData->groupDatas()->where("FGROUPID", $group_id)->first();
            if ($groupData) {
                $groupData->update([
                    "FPLANSCENE"  => $data["group_fplanScene"][$key],
                    "FDAILYSCENE" => $data["group_fdailyScene"][$key],
                    "FPLANPAGE"   => $data['group_fplanPage'][$key],
                    "FDAILYPAGE"  => $data['group_fdailyPage'][$key],
                ]);
            }
        }
    }

    /**
     * 新建每日数据和每日摄影组数据
     *
     * @param $data
     *
     * @return static
     */
    private function createNewDailyAndGroupData($data)
    {
        DB::unprepared('LOCK TABLES t_biz_progressdailydata write,t_biz_progressdailygrdata write,t_biz_progresstotaldata write');

        $progressDailyData = ProgressDailyData::where([
            'FDATE'    => $data['FDATE'],
            'FMOVIEID' => $data['movie_id']
        ])->count();

        if ($progressDailyData == 0) {
            $dailyData = ProgressDailyData::create([
                'FID'      => ProgressDailyData::max('FID') + 1,
                'FDATE'    => $data['FDATE'],
                'FMOVIEID' => $data['movie_id'],
            ]);

            $progressDailyGroupData = ProgressDailyGroupData::where(['FDAILYDATAID' => $dailyData->FID])->count();

            if ($progressDailyGroupData == 0) {
                //新建每日摄影组数据
                $createdGroupKey = [];
                foreach ($data['group_ids'] as $key => $group_id) {
                    if (in_array($group_id, $createdGroupKey)) {
                        continue;
                    }

                    ProgressDailyGroupData::create([
                        'FID'          => ProgressDailyGroupData::max('FID') + 1,
                        "FGROUPID"     => $group_id,
                        "FDAILYDATAID" => $dailyData->FID,
                        "fplanScene"   => $data["group_fplanScene"][$key],
                        "FDAILYSCENE"  => $data["group_fdailyScene"][$key],
                        "FPLANPAGE"    => $data['group_fplanPage'][$key],
                        "FDAILYPAGE"   => $data['group_fdailyPage'][$key],
                    ]);

                    $createdGroupKey[] = $group_id;
                }
            }

            DB::unprepared('UNLOCK TABLES');
        }

        return ProgressDailyData::where([
            'FDATE'    => $data['FDATE'],
            'FMOVIEID' => $data['movie_id']
        ])->first();
    }


}
