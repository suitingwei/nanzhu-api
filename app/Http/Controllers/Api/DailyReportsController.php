<?php

namespace App\Http\Controllers\Api;

use App\Formatters\DailyReportFormatter;
use App\Models\DailyReport;
use App\Models\Movie;
use App\Models\ReceivePower;
use App\User;
use App\Utils\DateUtil;
use Illuminate\Http\Request;
use Log;

class DailyReportsController extends BaseController
{
    /**
     * 场记日报表列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user                 = User::find($request->input('user_id'));
        $movie                = Movie::find($request->input('movie_id'));
        $canSeeReceivers      = $user->hadAssignedPowerInMovie($movie->FID, ReceivePower::class);
        $dailyReportPaginator = $this->getDailyReports($movie->FID, $user->FID);

        $dailyReports = DateUtil::groupByDate($dailyReportPaginator,
            DailyReportFormatter::getIndexListFormatter($user, $canSeeReceivers)
        );

        return $this->responseSuccess('操作成功', [
            'can_create'    => $user->isChangJiInMovie($movie->FID),
            'daily_reports' => $dailyReports
        ]);
    }

    /**
     * 获取日报表分页
     *
     * @param $movieId
     * @param $userId
     *
     * @return
     */
    private function getDailyReports($movieId, $userId)
    {
        return DailyReport::selectRaw("daily_reports.*")
                          ->leftjoin("messages", "messages.daily_report_id", "=", "daily_reports.id")
                          ->leftJoin("message_receivers", "message_receivers.message_id", "=", "messages.id")
                          ->whereRaw("messages.type = 'DAILY_REPORT' and  messages.movie_id = {$movieId}")
                          ->where('messages.is_undo', 0)
                          ->where("message_receivers.receiver_id", $userId)
                          ->orderby("daily_reports.created_at", "desc")
                          ->paginate(50);
    }

    /**
     * 新建日报表
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('save new dailyreport' . json_encode($request->all()));

        try {
            $newDailyReportData = $this->buildNewDailyReportData($request);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        $dailyReport = DailyReport::create($newDailyReportData);

        //Create the relative pictures.
        $dailyReport->createRelativeImages(explode(',', $request->input('img_url')));

        //Send the push messages to all receivers.
        $dailyReport->pushMessages();

        $dailyReport->record();

        return $this->responseSuccess();
    }

    /**
     * 日报表详情
     *
     * @param         $dailyReportId
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($dailyReportId, Request $request)
    {
        $userId      = $request->input('user_id');
        $dailyReport = DailyReport::find($dailyReportId);

        //用户读取这个日报表
        DailyReport::userReadMessage($dailyReportId, $userId);

        //获取详情formatter
        $formatter = DailyReportFormatter::getShowFormatter();

        return $this->responseSuccess('操作成功', [
            'daily_report' => $formatter($dailyReport)
        ]);
    }

    /**
     * 验证输入的选填时间们
     *
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function buildNewDailyReportData(Request $request)
    {
        //Validate the input times. A exception will be thrown when validation fails.
        $timeArray = $this->validateDailyReportTime($request);

        //Build the new daily report data.
        $newDailyReportData = array_merge($timeArray, [
            'movie_id' => $request->input('movie_id'),
            'author'   => $request->input('user_id'),
            'date'     => $request->input('date'),
            'group'    => $request->input('group'),
            'note'     => $request->input('note', '')
        ]);

        return $newDailyReportData;
    }

    /**
     * Update the daily report info.
     *
     * @param integer $dailyReportId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($dailyReportId, Request $request)
    {
		\Log::info('updating daily-report'.json_encode($request->all()));
		\Log::info('updating daily-report'.$dailyReportId);
        //Validate the input daily report times.
        try {
            $timeArray = $this->validateDailyReportTime($request);
        } catch (\Exception $e) {
            return $this->responseFail($e->getMessage());
        }

        $dailyReport         = DailyReport::find($dailyReportId);
        $canUpdateAttributes = array_merge($timeArray, $request->only('date', 'group', 'note'));

		\Log::info('updating daily-report'.json_encode($canUpdateAttributes));
        $dailyReport->update($canUpdateAttributes);

        //Delete all uploaded pictures,and recreate the relative pictures.
        if ($imgUrlArray = explode(',', $request->input('img_url'))) {
            $dailyReport->pictures()->delete();
            $dailyReport->createRelativeImages($imgUrlArray);
        }

        //Refresh all receivers' message read status.
        $dailyReport->refreshAllReceiversReadStatus();

        //Record the daily report update operation.
        $dailyReport->record();

        return $this->responseSuccess();
    }

    /**
     * Validate the inpt times.
     *
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     */
    private function validateDailyReportTime(Request $request)
    {
        //We must ensure if the input contains the Nth value, the before (N-1) values must be fulfilled.
        //And in order to give a niceful feedback we add a desc attribtue to describe which time is wrong and why.
        $timeArray = [
            ['key' => 'finish_time', 'desc' => '收工时间', 'time' => $request->input('finish_time')],
            ['key' => 'action_time', 'desc' => '开机时间', 'time' => $request->input('action_time')],
            ['key' => 'arrive_time', 'desc' => '到场时间', 'time' => $request->input('arrive_time')],
            ['key' => 'depart_time', 'desc' => '出发时间', 'time' => $request->input('depart_time')],
        ];

        //For quick check,we'll check from the the desc order.Because if the Nth value is null,we can simply skip it.
        foreach ($timeArray as $index => &$time) {
            //If the Nth time not fulfilled,leave it alone.
            if (is_null($time['time']) || $time['time'] == 0) {
                $time['time'] = 0;
                continue;
            }

            //If the Nth time has been fulfilled,we must check that the (N-1) values contains no null value,
            //and all not-null value must smaller than the Nth one.In order to check quickly,the subtime array
            //should be the asc order.
            foreach ($subTimeArray = array_reverse(array_slice($timeArray, $index + 1)) as $beforeTime) {
                if (is_null($beforeTime['time']) || 0 == $beforeTime['time']) {
                    throw new \Exception("{$beforeTime['desc']}必须填写完整");
                }
                if (strtotime($beforeTime['time']) >= strtotime($time['time'])) {
                    throw new \Exception("{$time['desc']}必须大于{$beforeTime['desc']}");
                }
            }
        }

        return array_combine(array_column($timeArray, 'key'), array_column($timeArray, 'time'));
    }

    /**
     * All update records in the daily report.
     */
    public function updateRecords($id)
    {
        $report = DailyReport::find($id);

        $records = $report->records()->orderBy('daily_report_records.created_at', 'desc')->paginate(20);

        return $this->responseSuccess('success', [
            'records' => $records->toArray()['data']
        ]);
    }
}
