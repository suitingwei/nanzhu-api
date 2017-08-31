<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Like;
use Illuminate\Http\Request;

/**
 * Class LikesController
 * @package App\Http\Controllers\Mobile
 */
class LikesController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data    = $request->all();
        $is_like = $this->is_liked($data["user_id"], "user", $data['like_id']);
        if ($is_like) {
            return redirect()->to("/mobile/users/" . $data['like_id'] . "?from=app&current_user_id=" . $data["user_id"]);
        }
        $like = Like::create($data);
        return redirect()->to("/mobile/users/" . $like->like_id . "?from=app&current_user_id=" . $like->user_id);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destory(Request $request)
    {
        $data = $request->all();
        $like = Like::where("user_id", $data["user_id"])->where("type", "user")->where("like_id",
            $data['like_id'])->first();
        if ($like) {
            $like->delete();
        }

        return redirect()->to("/mobile/users/" . $data['like_id'] . "?from=app&current_user_id=" . $data['user_id']);
    }
}
