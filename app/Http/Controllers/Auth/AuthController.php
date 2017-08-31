<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;

class AuthController extends Controller
{

    public function showLoginForm(Request $request)
    {
        return redirect()->to('http://pc.nanzhuxinyu.com/login');
    }

    public function login(Request $request)
    {
        /*dd($request->session());*/
        $phone = $request->get("phone");
        $code  = $request->get("code");
        $token = $request->get("aliyuntoken");
        Log::info($phone);
        if (!$phone) {
            Session::put('message', '请输入手机号');
            return redirect()->to("/login");
        }
        if (!$code) {
            Session::put('message', '请输入验证码');
            return redirect()->to("/login");
        }
        //如果可以取到用户信息
        if ($request->session()->get("user_id")) {
            $movies = DB::select("select mu.FMOVIE,su.FNAME from t_biz_movieuser  as mu  left join t_sys_user  su on mu.FUSER=su.FID left join t_biz_movie as movie on mu.FMOVIE=movie.FID where movie.shootend=0 and su.FPHONE ='" . $phone . "'");
            //判断当前用户是否拥有正在拍摄的剧
            if (count($movies) > 0) {
                return redirect()->to("/home?movie_id=" . $movies[0]->FMOVIE);
            }
            Session::put('message', '您已经没有正在拍摄的剧');
            return redirect()->to("/login");
        }
        //没有取到用户信息的话就登陆
        $data = User::login_or_register($phone, $code, $token);
        if ($data['ret'] != 0) {
            Session::put('message', '验证码或者手机号错误');
            return redirect()->to("/login");
        }
        //login success
        $request->session()->put('user_id', $data['user']->FID);
        $request->session()->put('user', $data['user']);

        $movies = DB::select("select mu.FMOVIE,su.FNAME from t_biz_movieuser  as mu  left join t_sys_user  su on mu.FUSER=su.FID left join t_biz_movie as movie on mu.FMOVIE=movie.FID where movie.shootend=0 and su.FPHONE ='" . $phone . "'");
        //判断当前用户是否拥有正在拍摄的剧
        if (count($movies) > 0) {
            $request->session()->flash("message", $data['msg']);
            return redirect()->to("/home?movie_id=" . $movies[0]->FMOVIE)->with("message", $data['msg']);
        }
        Session::put('message', '您已经没有正在拍摄的剧');
        return redirect()->to("/login");
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->forget('user');
        return redirect()->to("/login")->with("message", "成功登出");
    }


}
