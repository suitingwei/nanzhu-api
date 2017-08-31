<?php
namespace App\Traits\Movie;

use App\User;
use Carbon\Carbon;

trait Getters
{
    /**
     * Get the start date of the movie.
     * @return string
     */
    public function getStartDateAttribute()
    {
        return substr($this->FSTARTDATE, 0, 10);
    }

    /**
     * Get the end date of the movie.
     * @return string
     */
    public function getEndDateAttribute()
    {
        return substr($this->FENDDATE, 0, 10);
    }

    /**
     * Get the creator of the movie.
     * @return User
     */
    public function getCreatorAttribute()
    {
        return User::find($this->FNEWUSER);
    }

    /**
     *  获取拍摄进度
     */
    public function getProgressAttribute()
    {
        //如果没有填总数据,直接就是0
        if (!($progressTotalData = $this->progressTotalData)) {
            return 0;
        }

        $currentDay = Carbon::now();
        $startDay   = Carbon::createFromTimestamp(strtotime($progressTotalData->FSTARTDATE));

        $pastDays = $currentDay->diffInDays($startDay) + 1;

        //如果今天就是开拍日期,就是第一天
        if ($pastDays == 0) {
            $pastDays = 1;
        }

        return $currentDay->gt($startDay) ? $pastDays : -$pastDays;
    }


}
