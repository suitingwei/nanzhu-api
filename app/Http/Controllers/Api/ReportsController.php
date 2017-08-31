<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    public function store(Request $request)
    {
        $data   = $request->all();
        $report = Report::create($data);
        return response()->json(["ret" => 0, "msg" => "保存成功"]);
    }
}
