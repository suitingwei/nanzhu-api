<?php

namespace App\Models;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;


class MovieBasement extends Model
{
    const TYPE_PHOTOGRAPH_EQUIPMENT = 'photographEquip';
    const TYPE_BASEMENT             = 'basement';
    const TYPE_LIGHT_EQUIPMENT      = 'lightEquip';
    const TYPE_OVERSEAS_RECORD      = 'overseasRecord';
    const TYPE_MARKTING_COMPANY     = 'yinxiaoCompany';
    const TYPE_ECONOMY_COMPANY      = 'economyCompany';
    const TYPE_SPECIAL_EFFECT       = 'specialEffect';
    const TYPE_PRAY                 = 'pray';
    const TYPE_MOVIE_TOOL           = 'items';
    const TYPE_LAW_ASSIST           = 'lawassist';
    const TYPE_STUDIO               = 'studio';
    const TYPE_DESSERT              = 'dessert';
    const TYPE_UNIFORM              = 'uniform';
    const TYPE_INSURANCE            = 'insurance';
    const TYPE_HOTEL                = 'hotel';

    /**
     * @return array
     */
    public static function types()
    {
        return [
            self::TYPE_PHOTOGRAPH_EQUIPMENT,
            self::TYPE_BASEMENT,
            self::TYPE_LIGHT_EQUIPMENT,
            self::TYPE_OVERSEAS_RECORD,
            self::TYPE_MARKTING_COMPANY,
            self::TYPE_ECONOMY_COMPANY,
            self::TYPE_SPECIAL_EFFECT,
            self::TYPE_PRAY,
            self::TYPE_MOVIE_TOOL,
            self::TYPE_LAW_ASSIST,
            self::TYPE_STUDIO,
            self::TYPE_DESSERT,
            self::TYPE_UNIFORM,
            self::TYPE_INSURANCE,
            self::TYPE_HOTEL,
        ];
    }

    /**
     * 获取用于列表页的短的描述
     */
    public function getShortIntroductionAttribute()
    {
        return mb_substr(trim($this->introduction), 0, 40);
    }

    /**
     * 一个可交易IP可能有多个图片
     * 不能重复使用旧字段,因为如果blog和company都是用blog_id
     * 那么有可能重复
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class, 'basement_id', 'id');
    }

    /**
     * 获取微信分享json
     */
    public function getWechatShareJsonAttribute()
    {
        return json_encode([
            'title'   => $this->title,
            'cover'   => $this->cover,
            'content' => $this->title,
            'url'     => $this->getCompanyShowPageUrl()
        ]);
    }

    /**
     * @return string
     */
    public function getShareCoverAttribute()
    {
        return $this->attributes['cover'] . '?x-oss-process=style/icon-style';
    }

    /**
     * 获取公司的详情界面url
     * @return string
     */
    public function getCompanyShowPageUrl()
    {
        return env('APP_URL') . '/mobile/trade-resources/basements/' . $this->id . '?from=singlemessage';
    }

    /*
     * 获取介绍的markdown数据
     */
    public function getMarkdownIntroductionAttribute()
    {
        return Markdown::convertToHtml($this->content);
    }
}
