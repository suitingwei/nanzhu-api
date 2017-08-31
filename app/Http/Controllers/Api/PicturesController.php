<?php

namespace App\Http\Controllers\Api;

use App\Models\Picture;
use Illuminate\Http\Request;

class PicturesController extends BaseController
{
    //
    public function callback(Request $request)
    {
        //Log::info($request->all());
    }

    public function index()
    {
        $picture = Picture::where("is_startup", 1)->orderBy("id", "desc")->first();

        return response()->json(["picture" => $picture]);
    }


    /**
     * Upload the pictures .
     */
    public function upload(Request $request)
    {
        if (!$request->hasFile('file')) {
            return $this->responseFail();
        }

        $fileUrl = Picture::upload('pictures', $request->file('file'));

        return $this->responseSuccess('Upload successfully', ['url' => $fileUrl]);
    }
}
