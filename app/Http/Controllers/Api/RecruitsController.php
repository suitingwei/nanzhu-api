<?php

namespace App\Http\Controllers\Api;

use App\Models\Picture;
use App\Models\Recruit;
use Illuminate\Http\Request;


class RecruitsController extends BaseController
{


    public function index(Request $request)
    {

        $uri  = $_SERVER['REQUEST_URI'];
        $uri  = str_replace("/", "_", $uri);
        $uri  = str_replace("=", "_", $uri);
        $uri  = str_replace("?", "_", $uri);
        $uri  = str_replace("&", "_", $uri);
        $rkey = $uri;

        //$client = new Predis\Client([
        //	'scheme' => 'tcp',
        //	'host'   => env('REDIS_HOST'),
        //	'password'   => env('REDIS_PASSWORD'),
        //	'port'   => env('REDIS_PORT'),
        //	]);

        //if ($client->get($rkey)) {
        //	return response()->json(json_decode($client->get($rkey)));
        //}

        $current_user_id = $this->current_user($request);
        $type            = $request->get("type");
        //$wherearr = " type = '".$type."' and is_approved = 1";
        $wherearr = " type = '" . $type . "' ";
        $arr      = Recruit::whereRaw($wherearr)->orderby("id", "desc")->paginate(15);
        $s        = [];
        $arrs     = [];
        $temp     = array();
        foreach ($arr as $item) {
            $arrs[$item->toArray()['d']] = "";
        }

        foreach ($arr as $recruit) {

            if (array_key_exists($recruit->toArray()['d'], $arrs)) {
                $json['type']        = $recruit->type;
                $json['title']       = $recruit->title;
                $json['content']     = $recruit->content;
                $json['id']          = $recruit->id;
                $json['author_id']   = $recruit->author_id;
                $json['is_liked']    = $this->is_liked($current_user_id, "recruit", $recruit->id);
                $json['is_favorite'] = $this->is_favorite($current_user_id, "recruit", $recruit->id);
                //Log::info($current_user_id);
                //Log::info($recruit->id);
                $json['employer']                 = $recruit->employer;
                $json['count']                    = $recruit->count;
                $arrs[$recruit->toArray()['d']][] = $json;
            }
        }
        foreach ($arrs as $key => $a) {
            $s[] = ["date" => $key, "data" => $a];
        }

        //$client->set($rkey,json_encode(["ret" => 0,"msg" => "操作成功","data" => $s]));
        return response()->json(["ret" => 0, "msg" => "操作成功", "data" => $s]);

    }

    public function store(Request $request)
    {
        $data = $request->except("pic_urls");
        \Log::info($data);
        $recruit = Recruit::create($data);
        $files   = $request->get("pic_urls");
        $arr     = explode(",", $files);
        if (count($arr) > 0) {
            foreach ($arr as $a) {
                $pictrue             = new Picture;
                $pictrue->url        = $a;
                $pictrue->recruit_id = $recruit->id;
                $pictrue->save();
            }
        }
        return response()->json(["msg" => "保存成功", "ret" => 0]);
    }

    public function show(Request $request, $id)
    {

        $current_user_id = $this->current_user($request);
        $recruit         = Recruit::find($id);
        if ($recruit) {
            $json['type']        = $recruit->type;
            $json['title']       = $recruit->title;
            $json['content']     = $recruit->content;
            $json['id']          = $recruit->id;
            $json['author_id']   = $recruit->author_id;
            $json['is_liked']    = $this->is_liked($current_user_id, "recruit", $recruit->id);
            $json['is_favorite'] = $this->is_favorite($current_user_id, "recruit", $recruit->id);
            $json['employer']    = $recruit->employer;
            $json['count']       = $recruit->count;
            return response()->json(["recruit" => $json]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        \Log::info($data);
        \Log::info($id);
        $recruit = Recruit::find($id);
        if ($recruit) {
            $recruit->update($data);
            Picture::where("recruit_id", $recruit->id)->delete();
            $files = $request->get("pic_urls");
            $arr   = explode(",", $files);
            if (count($arr) > 0) {
                foreach ($arr as $a) {
                    $pictrue             = new Picture;
                    $pictrue->url        = $a;
                    $pictrue->recruit_id = $recruit->id;
                    $pictrue->save();
                }
            }
            return response()->json(["ret" => 0, "message" => "保存成功", "recruit" => $recruit]);
        }

        return response()->json(["ret" => 0, "message" => "保存失败"]);
    }

    public function destroy(Request $request, $id)
    {
        //Log::info("----------");
        $recruit = Recruit::find($id);
        if ($recruit) {
            $recruit->delete();
            return response()->json(["ret" => 0, "msg" => "操作成功"]);

        }
        return response()->json(["ret" => 0, "msg" => "操作失败"]);
    }
}
