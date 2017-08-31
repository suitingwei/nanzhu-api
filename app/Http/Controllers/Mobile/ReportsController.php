<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Recruit;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportsController extends BaseController
{
    //
    public function create(Request $request)
    {
        $recruit_id = $request->get("recruit_id");
        $recruit    = Recruit::find($recruit_id);
        return view("mobile.reports.create", ["recruit" => $recruit]);
    }

    public function show($id)
    {
        return view("mobile.reports.show");
    }

    public function store(Request $request)
    {
        $data   = $request->all();
        $report = Report::create($data);
        return redirect()->to("/mobile/reports/" . $report->id);
    }
}
