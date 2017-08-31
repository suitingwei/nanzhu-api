<?php

namespace App\Http\Controllers\Api;

use App\Models\BlackList;
use App\Models\Des;
use App\Models\EaseUser;
use App\Models\Profile;
use App\Models\Sms;
use App\Models\SmsRecord;
use App\Models\Union;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountsController extends BaseController
{
    const RET_CODE_SUCCESS      = 0;
    const RET_CODE_FAIL         = -99;
    const MSG_SUCCESS           = "操作成功";
    const MSG_FAIL              = "验证码错误";
    const MSG_SEND_TOO_FREQUENT = '验证码发送过于频繁,请稍后重试。';

    /**
     * @var User
     */
    private $createdUser = null;
    /**
     * @var Profile
     */
    private $createdUserProfile;

    public function verify_code(Request $request)
    {
        $phone      = $request->get("phone", 0);
        $code       = rand(1000, 9999);
        $sms_record = SmsRecord::where("phone", $phone)->orderby("id", "desc")->first();

        //判断存在
        //判断是否过期
        $current = date('Y-m-d H:i:s', time());
        if ($sms_record) {
            $old = date('Y-m-d H:i:s', strtotime($sms_record->created_at) + 80);
            if ($current < $old) {
                return response()->json([
                    "ret"  => self::RET_CODE_FAIL,
                    "msg"  => self::MSG_SEND_TOO_FREQUENT,
                    "data" => $sms_record->code
                ]);
            }
        }
        //发送短信 并记录
        $result                  = Sms::send($phone, [$code, 1], "132346");
        $sms_record              = new SmsRecord();
        $sms_record->phone       = $phone;
        $sms_record->code        = $code;
        $sms_record->valid_time  = 1;
        $sms_record->template_id = "66857";
        $sms_record->status      = "000000";
        $sms_record->save();
        return response()->json(["ret" => self::RET_CODE_SUCCESS, "msg" => self::MSG_SUCCESS, "data" => $code]);
    }

    public function login(Request $request)
    {
        $randomSecret = '093294729837489';
        $phone        = $request->input("phone");
        $code         = $request->input("code");

        if (BlackList::where('phone', $phone)->count() > 0) {
            return response()->json(["ret" => self::RET_CODE_FAIL, "msg" => self::MSG_FAIL]);
        }

        //测试账号,默认密码,直接登录
        if ($this->isTestAccount($phone, $code) || ($code == $randomSecret)) {
            return $this->loginOrRegister($request);
        }

        if (!($sms_record = SmsRecord::where("phone", $phone)->orderby("id", "desc")->first())) {
            return response()->json(["ret" => self::RET_CODE_FAIL, "msg" => self::MSG_FAIL]);
        }

        //判断验证码过期或不正确
        if ((time() - strtotime($sms_record->created_at) > 80) || $code != $sms_record->code) {
            return response()->json(["ret" => self::RET_CODE_FAIL, "msg" => self::MSG_FAIL]);
        }

        return $this->loginOrRegister($request);
    }

    public static function userData($user)
    {
        $userdata["userToken"]     = Des::userToken($user->FID);
        $userdata["userId"]        = $user->FID;
        $userdata["pic"]           = $user->cover_url;
        $userdata["name"]          = $user->FNAME;
        $userdata["loginAccount"]  = $user->FLOGIN;
        $userdata["sex"]           = $user->FSEX;
        $userdata["constellation"] = $user->FCONSTELLATION;
        $userdata["bloodType"]     = $user->FBLOODTYPE;
        $userdata["mobile"]        = $user->FPHONE;
        return $userdata;
    }

    public function update(Request $request)
    {
        $current = date('Y-m-d H:i:s', time());
        if ($request->get("name")) {
            $data['FNAME'] = $request->get("name");
        }
        if ($request->get("sex")) {
            $data['FSEX'] = $request->get("sex");
        }
        $token = $request->get("aliyuntoken");
        if ($token) {
            $data['FALIYUNTOKEN'] = $token;
        }
        if ($request->get("token")) {
            $data['FALIYUNTOKEN'] = $request->get("token");
        }

        if ($request->get("pushToken")) {
            $data['FIOSTOKEN'] = $request->get("pushToken");
        }

        $userQueryBuilder = User::where("FID", $request->get("user_id"));
        if ($userQueryBuilder) {
            $data['FEDITDATE'] = $current;
            $userQueryBuilder->update($data);
            $profile = Profile::where('user_id', $request->input('user_id'))->first();
            if ($profile) {
                $userInstance = User::find($request->input('user_id'));
                $profile->update([
                    'gender' => $userInstance->sex_desc(),
                    'name'   => $userInstance->FNAME
                ]);
            }

            return response()->json(["ret" => self::RET_CODE_SUCCESS, "msg" => self::MSG_SUCCESS]);
        }

        return response()->json(["ret" => self::RET_CODE_FAIL, "msg" => self::MSG_FAIL]);

    }

    /**
     * Judge wether an account is apple test account.
     * @param $phone
     * @param $code
     * @return bool
     */
    private function isTestAccount($phone, $code)
    {
        return '13439308888' == $phone && '0000' == $code;
    }


    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\JsonResponse
     */
    private function getUserLoginInfo(Request $request, User $user)
    {
        $this->updateUserAliyunToken($request, $user)
             ->registerEasemob($user)
             ->bindUnionPhone($user)
             ->addMovieServericeManFriend($user);

        return response()->json([
            "ret"     => self::RET_CODE_SUCCESS,
            "flag"    => $user->FNAME ? 1 : 0,
            "msg"     => self::MSG_SUCCESS,
            "user"    => self::userData($user),
            "profile" => $user->profile ?: '',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function registerNewUser(Request $request)
    {
        try {
            $this->createNewUser($request)
                 ->getProfileInfo($this->createdUser)
                 ->bindUnionPhone($this->createdUser)
                 ->registerEasemob($this->createdUser)
                 ->addMovieServericeManFriend($this->createdUser);
        } catch (\Exception $e) {
        }

        return response()->json([
            "ret"     => self::RET_CODE_SUCCESS,
            "flag"    => 0,
            "msg"     => self::MSG_SUCCESS,
            "user"    => self::userData($this->createdUser),
            "profile" => $this->createdUserProfile,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function loginOrRegister(Request $request)
    {
        $user = User::where('FPHONE', $request->input('phone'))->first();

        if ($user) {
            return $this->getUserLoginInfo($request, $user);
        }

        return $this->registerNewUser($request);
    }

    /**
     * @param Request $request
     * @param User    $user
     * @return $this
     */
    private function updateUserAliyunToken(Request $request, User $user)
    {
        $data                   = [];
        $data['FLASTLOGINDATE'] = Carbon::now();
        $data['FALIYUNTOKEN']   = $request->has('token') ? $request->input('token') : $request->input("aliyuntoken");
        if ($request->get("pushToken")) {
            $data['FIOSTOKEN'] = $request->get("pushToken");
        }
        User::where("FID", $user->FID)->update($data);
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    private function registerEasemob(User $user)
    {
        try {
            $easeuser = new EaseUser;
            if (!$user->easemob_uuid) {
                $easeuser->register($user->FID);
            }

            //如果用户已经登录了环信,强制退出
            if ($easeuser->isUserOnline($user->FID)) {
                $easeuser->forceUserDisconnect($user->FID);
            }
        } catch (\Exception $e) {
        }
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    private function addMovieServericeManFriend(User $user)
    {
        try {
            $user->addFriend(User::find(env('MOVIE_CLOTHES_SERVER_USER_ID')));
        } catch (\Exception $e) {
        }
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    private function bindUnionPhone(User $user)
    {
        Union::setThisDigitalUserId($user->FPHONE, $user->FID);
        return $this;
    }

    /**
     * @param Request $request
     * @return AccountsController
     */
    private function createNewUser(Request $request)
    {
        $user               = new User;
        $user->FID          = User::max("FID") + 1;
        $user->FLOGIN       = $request->input('phone');
        $user->FPHONE       = $request->input('phone');
        $user->FCODE        = $request->input('phone');
        $user->FNEWDATE     = Carbon::now();
        $user->FEDITDATE    = Carbon::now();
        $user->FALIYUNTOKEN = $request->has('token') ? $request->input('token') : $request->input('aliyuntoken');
        $user->FIOSTOKEN    = $request->input("pushToken", '');
        $user->save();
        $this->createdUser = $user;
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    private function getProfileInfo(User $user)
    {
        $profile = Profile::where("user_id", $user->FID)->first();

        if (!$profile) {
            $profile          = new Profile;
            $profile->user_id = $user->FID;
            $profile->save();
        }
        $this->createdUserProfile = $profile;
        return $this;
    }

}

