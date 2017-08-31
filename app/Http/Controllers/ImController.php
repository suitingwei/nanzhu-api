<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ImController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId = decrypt($request->input('u'));

        $user = User::find($userId);

        $coverUrl = $user->cover_url;

        $pwd = $username = 'nanzhu_' . $userId;

        $nickname = $user->hx_name;

        return view('im.index', compact('username', 'pwd', 'coverUrl', 'nickname'));
    }
}

