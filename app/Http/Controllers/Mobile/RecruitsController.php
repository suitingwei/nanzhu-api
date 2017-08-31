<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Api\BaseController;
use App\Models\Recruit;
use Illuminate\Http\Request;

class RecruitsController extends BaseController
{
    //
    public function show(Request $request, $id)
    {
        $from    = $request->get("from");
        $recruit = Recruit::find($id);
        if ($recruit) {
            return view("mobile.recruits.show", ['from' => $from, 'recruit' => $recruit]);
        }
    }
}

