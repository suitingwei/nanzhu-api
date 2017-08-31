<?php

namespace App\Http\Controllers\Api;

require public_path('/jssdk.php');
use Illuminate\Http\Request;
use JSSDK;

class WeChatController extends BaseController
{
    /**
     * -----------------------------------------------------
     * Get the wechat share config.
     * -----------------------------------------------------
     * This info cannot be placed to the frontend h5 pages,
     * because the wechat signature key generate algorithm,
     * must be generated in the server side.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfig(Request $request)
    {
        $currentUrl = $request->input('current_url');

        $jssdk = new JSSDK("wx3611db0c8434ab01", "a0b501042641b5fde877307974082a9c");

        $signPackage = $jssdk->getSignPackageByUrl($currentUrl);

        return $this->ajaxResponseSuccess('操作成功', ['config' => $signPackage]);
    }
}
