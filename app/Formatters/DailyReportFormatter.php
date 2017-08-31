<?php
namespace App\Formatters;

use App\User;
use App\Utils\OssUtil;
use Illuminate\Support\Arr;

class DailyReportFormatter
{
    /**
     * 场记日报表index接口json格式化
     *
     * @param User $currentUser
     * @param      $canSeeReceivers
     *
     * @return mixed
     */
    public static function getIndexListFormatter(User $currentUser, $canSeeReceivers)
    {
        return function ($dailyReport) use ($currentUser, $canSeeReceivers) {

            $originalArray = $dailyReport->toArray();

            Arr::forget($originalArray, ['note', 'group', 'date', 'updated_at', 'created_at', 'movie_id', 'author']);

            return array_merge($originalArray, [
                'depart_time'       => $dailyReport->short_depart_time,
                'arrive_time'       => $dailyReport->short_arrive_time,
                'action_time'       => $dailyReport->short_action_time,
                'finish_time'       => $dailyReport->short_finish_time,
                'title'             => $dailyReport->title,
                'short_note'        => $dailyReport->short_note,
                'can_edit'          => $dailyReport->author == $currentUser->FID,
                'can_see_receivers' => $canSeeReceivers,
                'status'            => $dailyReport->getStatusForUser($currentUser->FID),
                'pictures_count'    => $dailyReport->pictures()->count(),
                'h5_receivers_url'  => $dailyReport->getH5ReceiversUrl(),
            ]);

        };
    }

    /**
     * 详情界面的foramtter
     */
    public static function getShowFormatter()
    {
        return function ($dailyReport) {
            $originalArray = $dailyReport->toArray();
            Arr::forget($originalArray, ['author', 'created_at', 'updated_at']);

            $pictures = $dailyReport->pictures->lists('url')->all();

            return array_merge($originalArray, [
                'title'             => $dailyReport->title,
                'pictures'          => $pictures,
                'size'              => OssUtil::getPicturesWithSizeInfo($pictures),
                'author_user'       => $dailyReport->authorUser->FNAME,
                'position'          => $dailyReport->authorUser->groupNamesInMovie($dailyReport->movie_id),
                'last_updated_at'   => $dailyReport->updated_at,
                'short_arrive_time' => $dailyReport->short_arrive_time,
                'short_depart_time' => $dailyReport->short_depart_time,
                'short_action_time' => $dailyReport->short_action_time,
                'short_finish_time' => $dailyReport->short_finish_time,
                'chinese_date'      => $dailyReport->chinese_date
            ]);
        };
    }


}
