<?php
namespace App\Traits\Movie;

trait  Types
{
    /**
     * @return array
     */
    public static function types()
    {
        return ["院线电影", "电视剧", "综艺", "网络大电影", "网剧", "广告", "演唱会", "舞台剧", " 纪录片"];
    }

    /**
     * @return array
     */
    public static function old_types()
    {
        return [
            "10"  => "院线电影",
            "20"  => "电视剧",
            "30"  => "综艺",
            "35"  => "数字电影",
            "40"  => "网络大电影",
            "50"  => "网剧",
            "60"  => "广告",
            "70"  => "演唱会",
            "80"  => "舞台剧",
            "90"  => "纪录片",
            "100" => "短片"
        ];
    }

}
