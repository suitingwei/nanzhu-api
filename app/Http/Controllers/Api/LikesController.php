<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;

class LikesController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->all();
        Like::create($data);
        return response()->json(["ret" => 0, "msg" => "操作成功"]);
    }

    public function destroy(Request $request)
    {

        $like = Like::where("like_id", $request->get("like_id"))
                    ->where("user_id", $request->get("user_id"))
                    ->where("type", $request->get("type"))->first();
        if ($like) {
            $like->delete();
            return response()->json(["ret" => 0, "msg" => "操作成功"]);
        }
    }

}
