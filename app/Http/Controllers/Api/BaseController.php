<?php

namespace App\Http\Controllers\Api;

use App\Consts\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Models\Des;
use App\Models\Favorite;
use App\Models\Like;
use App\Models\ProfileShare;
use App\User;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    public function getShortIntroductionAttribute()
    {
        return mb_substr(trim($this->introduction), 0, 30);
    }


    public function current_user(Request $request)
    {
        $user_token = $request->header("X-Auth-Token");
        $user_id    = Des::tokenToUserId($user_token);
        if ($user_id && $user_id != -1) {
            return $user_id;
        }
        if ($user_id != -1) {
            return $user_id;
        }

        $userId = $request->input('user_id');
        if ($userId != -1) {
            return $userId;
        }

        $url = explode('/', $_SERVER["REQUEST_URI"]);
        return isset($url[3]) ? $url[3] : -1;
    }

    /**
     * 获取当前日期
     * 如果有请求参数返回请求天数
     * @param Request $request
     * @return array|false|string
     */
    public function currentDate(Request $request)
    {
        return $request->has('day') ? $request->input('day') : date('Y-m-d');
    }

    public function is_liked($user_id, $type, $like_id)
    {
        if ($user_id) {
            $like = Like::where("like_id", $like_id)->where("type", $type)->where("user_id", $user_id)->first();
            if ($like) {
                return true;
            }
        }
        return false;
    }

    public function is_favorite($user_id, $type, $favorite_id)
    {
        if ($user_id) {
            $favorite = Favorite::where("favorite_id", $favorite_id)->where("type", "like", $type)->where("user_id",
                $user_id)->first();
            if ($favorite) {
                return true;
            }
        }
        return false;
    }

    public function is_share($user_id, $profile_id)
    {
        if ($user_id) {
            $share = ProfileShare::where("user_id", $user_id)->where("profile_id", $profile_id)->first();
            if ($share) {
                return true;
            }
        }

        return false;
    }

    /**
     * 返回成功
     * @param string $message
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($message = '操作成功', $data = [])
    {
        if (empty($data)) {
            $data = new \stdClass();
        }

        return response()->json([
            'ret'  => ResponseCodes::SUCCESS,
            'msg'  => $message,
            'data' => $data
        ]);
    }

    /**
     * 返回json
     * @param int    $returnData
     * @param string $msg
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($returnData, $msg, $data)
    {
        return response()->json([
            'ret'  => $returnData,
            'msg'  => $msg,
            'data' => $data
        ]);
    }

    /**
     * 返回失败
     * @param string $message
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseFail($message = '操作失败', $data = [])
    {
        if (empty($data)) {
            $data = new \stdClass();
        }
        return response()->json([
            'ret'  => ResponseCodes::FAIL,
            'msg'  => $message,
            'data' => $data
        ]);
    }

    /**
     * 返回Json
     * @param int    $responseCode
     * @param string $message
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseJson($responseCode, $message, $data)
    {
        return response()->json([
            'ret'  => $responseCode,
            'msg'  => $message,
            'data' => $data
        ]);
    }

    /**
     * 返回邀请用户注册的json
     * @param User|integer $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUserSendInvitation($user)
    {
        if (!($user instanceof User)) {
            $user = User::find($user);
        }

        return response()->json([
            'ret'  => ResponseCodes::INVITE_USER_REGISTER,
            'msg'  => '微信分享邀请',
            'data' => [
                'content' => "我是{$user->FNAME}，现邀您一起体验全新的剧组神器《南竹通告单＋》，快来加入！",
                'title'   => '好友邀请',
                'url'     => 'http://a.app.qq.com/o/simple.jsp?pkgname=com.zdyx.nanzhu'
            ]
        ]);

    }


    /**
     * ajax返回
     * @param string $message
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxResponseSuccess($message = '操作成功', $data = [])
    {
        return response()->json([
            'success' => ResponseCodes::AJAX_SUCCESS,
            'msg'     => $message,
            'data'    => $data
        ]);
    }


    /**
     * Ajax请求失败
     * @param string $message
     * @param array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxResponseFail($message = '操作失败', $data = [])
    {
        return response()->json([
            'success' => ResponseCodes::AJAX_FAIL,
            'msg'     => $message,
            'data'    => $data
        ]);
    }

    /**
     * 根据当前请求获取登录用户
     * ----------------------
     * 1.X-Auth-Token
     * 2.GET[userId]
     * 3.GET[token]
     * @param Request $request
     * @return User
     */
    public function getCurrentUserByRequest(Request $request)
    {
        $userId = $this->current_user($request);

        if ($userId == -1) {
            $userId = Des::tokenToUserId($request->input('token'));
        }
        if ($userId == -1) {
            $userId = $request->input('user_id');
        }

        return User::find($userId);
    }

    /**
     * ios的版本
     * @param Request $request
     * @return string
     */
    public function iosVersion(Request $request)
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if (strpos($agent, 'iphone') === false) {
            return '0';
        }

        $version = $request->header('app-version');

        if (empty($version)) {
            return '0';
        }

        return $version;
    }


    /**
     * android版本
     * @param Request $request
     * @return string
     */
    public function androidVersion(Request $request)
    {
        if ($request->has('androidVer')) {
            return $request->input('androidVer');
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') === false) {
            return '0';
        }

        return $request->header('app-version') ?: '0';
    }
}

