<?php

namespace App\Models;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;

class TradeScript extends Model
{
    const DEFAULT_SHARE_COVER_URL = 'http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png';

    /**
     *
     */
    public function getShortIntroductionAttribute()
    {
        return rtrim(mb_strimwidth($this->plain_content, 0, 60, '...'));
    }

    /**
     * 一个可交易IP可能有多个图片
     */
    public function pictures()
    {
        return $this->hasMany(Picture::class, 'script_id', 'id');
    }

    /**
     * 获取微信分享json
     */
    public function getWechatShareJsonAttribute()
    {
        return json_encode([
            'title'   => '公司详情',
            'cover'   => self::DEFAULT_SHARE_COVER_URL,
            'content' => '南竹通告单',
            'url'     => $this->getScriptShowPageUrl()
        ]);
    }

    /**
     * 获取公司的详情界面url
     * @return string
     */
    public function getScriptShowPageUrl()
    {
        return env('APP_URL') . '/mobile/trade-resources/scripts/' . $this->id . '?from=singlemessage';
    }

    /**
     * 获取介绍的markdown数据
     */
    public function getMarkdownContentAttribute()
    {
        return Markdown::convertToHtml($this->content);
    }
}
