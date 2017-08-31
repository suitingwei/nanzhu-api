<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class VersionsController extends BaseController
{
    const DEVICE_IOS     = 'ios';
    const DEVICE_ANDROID = 'android';

    const IOS_NEW_VERSION_URL     = 'https://itunes.apple.com/cn/app/nan-zhu-tong-gao-dan+/id1071063819?mt=8';
    const ANDROID_NEW_VERSION_URL = '';

    const NOW_VERSION = '3.7.0';

    const FORCE_UPDATE = 1;
    const ALERT_UPDATE = 2;
    const NO_UPDATE    = 3;

    const DEFAULT_UPDATE_TITLE   = '3.7.0版本更新';
    const DEFAULT_UPDATE_CONTENT = "1.增加组服定制\n 2.联系客服\n3.Fixed bug";

    public function index(Request $request)
    {
        $platForm = $request->input('dPlatform');
        $version  = $request->input('app_ver');

        $needUpdate = self::NO_UPDATE;
        $title      = self::DEFAULT_UPDATE_TITLE;
        $content    = self::DEFAULT_UPDATE_CONTENT;
        $url        = self::IOS_NEW_VERSION_URL;

        if (strtolower($platForm) == 'android') {

        }

        if (strtolower($platForm) == 'ios') {
            if (version_compare($version, self::NOW_VERSION, '<')) {
                $needUpdate = self::ALERT_UPDATE;
            }
        }

        return $this->responseSuccess('操作成功', [
            'status'  => $needUpdate,
            'title'   => $title,
            'content' => $content,
            'url'     => $url
        ]);
    }
}
