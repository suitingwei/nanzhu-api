<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //
    public function store(Request $request)
    {
        $data = $request->all();
        //Log::info($data);
        Favorite::create($data);
        return response()->json(["ret" => 0, "msg" => "操作成功"]);
    }

    public function destroy(Request $request)
    {
        $data = $request->all();

        Favorite::where("type", $request->get("type"))
                ->where("user_id", $request->get("user_id"))
                ->where("favorite_id", $request->get("favorite_id"))
                ->delete();

        return response()->json(["ret" => 0, "msg" => "操作成功"]);
    }
}
