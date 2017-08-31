<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbacksController extends Controller
{
    //

    public function store(Request $request)
    {
        $data = $request->all();
        Feedback::create($data);
        return response()->json(["ret" => 0, "msg" => "保存成功"]);
    }
}
