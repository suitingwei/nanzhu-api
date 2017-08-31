<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannersController extends BaseController
{
    //
    public function index(Request $request)
    {
        $banners = Banner::orderBy("sort")->get();
        return response()->json(["banners" => $banners]);
    }
}
