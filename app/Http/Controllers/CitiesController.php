<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(Request $request)
    {
        $cities   = \DB::select("SELECT city_id as value,belong as label FROM cities where grade = '中国地级市'  group by belong  order by english_belong");
        $s_cityes = ["北京", "上海", "重庆", "天津", "香港", "澳门", "海南"];
        $province = $request->get("province");
        if ($province) {
            if (in_array($province, $s_cityes)) {
                $cities = \DB::select("SELECT city_id as value,name as label FROM cities  where  name = '" . $province . "' and grade = '中国地级市' ");
            } else {
                $cities = \DB::select("SELECT city_id as value,belong2 as label,name FROM cities where  belong='" . $province . "'  group by belong2");
            }
            return response()->json($cities);

        }

        $city = $request->get("city");

        if ($city) {
            if (in_array($city, $s_cityes)) {
                $cities = \DB::select("SELECT city_id as value,name  as label FROM cities where  belong='" . $city . "' ");
            } else {
                $cities = \DB::select("SELECT city_id as value,name  as label FROM cities  where  belong2='" . $city . "' and grade = '中国其他' ");
            }
            return response()->json($cities);
        }

        return response()->json($cities);
    }
}
