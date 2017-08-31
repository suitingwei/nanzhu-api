<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;

class GroupsController extends Controller
{

    public function index()
    {
        $groups = Group::select("FNAME")->where("FMOVIE", 0)->get();
        return response()->json(["groups" => $groups]);
    }

}
