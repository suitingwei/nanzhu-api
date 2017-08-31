<?php

namespace App\Models;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed introduction
 */
class Company extends Model
{
    /**
     * 获取用于列表页的短的描述
     */
    public function getShortIntroductionAttribute()
    {
        return mb_substr(trim($this->plain_introduction), 0, 30);
    }

    /**
     * 一个可交易IP可能有多个图片
     * 不能重复使用旧字段,因为如果blog和company都是用blog_id
     * 那么有可能重复
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class, 'company_id', 'id');
    }

    /**
     * 合作编剧
     */
    public function editors()
    {
        return $this->hasMany(CooperateEditor::class, 'company_id', 'id');
    }

    /**
     * 获取微信分享json
     */
    public function getWechatShareJsonAttribute()
    {
        return json_encode([
            'title'   => $this->title,
            'cover'   => $this->logo,
            'content' => $this->title,
            'url'     => $this->getCompanyShowPageUrl()
        ]);
    }

    public function getShareCoverAttribute()
    {
        return $this->attributes['logo'] . '?x-oss-process=style/icon-style';
    }

    /**
     * 获取公司的详情界面url
     * @return string
     */
    public function getCompanyShowPageUrl()
    {
        return env('APP_URL') . '/mobile/trade-resources/companies/' . $this->id . '?from=singlemessage';
    }

    /**
     * 获取介绍的markdown数据
     */
    public function getMarkdownIntroductionAttribute()
    {
        return Markdown::convertToHtml($this->introduction);
    }

}
