<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $location      = $_SERVER['REMOTE_ADDR'];// "Beijing";
        $key           = "lvacqqj3xpvmbmhu";
        $uid           = "U116D9CCC5";
        $keyname       = "ts=" . time() . "&ttl=30&uid=" . $uid;
        $sig           = base64_encode(hash_hmac('sha1', $keyname, $key, true));
        $signedkeyname = $keyname . "&sig=" . urlencode($sig);

        $start = 0;
        $date  = $request->get("day");
        if ($date) {
            $start = date("Y/m/d", strtotime($date));
        }

        $url    = "https://api.thinkpage.cn/v3/weather/daily.json?key=lvacqqj3xpvmbmhu&location=" . $location . "&language=zh-Hans&unit=c&start=" . $start . "&days=7&" . $signedkeyname;
        $result = $this->fetch_data($url);

        $city = $result->results[0]->location->name;

        $one_day = $result->results[0]->daily[0];
        //节气
        $url          = "https://api.thinkpage.cn/v3/life/chinese_calendar.json?key=lvacqqj3xpvmbmhu&start=" . $start . "&days=7" . "&" . $signedkeyname;
        $jieqi_result = $this->fetch_data($url);


        //日出日落
        $url        = "https://api.thinkpage.cn/v3/geo/sun.json?key=lvacqqj3xpvmbmhu&location=beijing&language=zh-Hans&start=" . $start . "&days=7" . "&" . $signedkeyname;
        $sun_result = $this->fetch_data($url);


        //月初月落
        $url         = "https://api.thinkpage.cn/v3/geo/moon.json?key=lvacqqj3xpvmbmhu&location=beijing&language=zh-Hans&start=" . $start . "&days=7" . "&" . $signedkeyname;
        $moon_result = $this->fetch_data($url);


        return response()->json([
            "city"         => $city,
            "one_day"      => $one_day,
            "result"       => $result,
            "sun_result"   => $sun_result,
            "moon_result"  => $moon_result,
            "jieqi_result" => $jieqi_result
        ]);

    }

    function get_chinese_weekday($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        return '星期' . ['日', '一', '二', '三', '四', '五', '六'][$weekday];
    }

    function fetch_data($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // Set so curl_exec returns the result instead of outputting it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Get the response and close the channel.
        $response = curl_exec($ch);
        $result   = json_decode($response);
        return $result;
        //	curl_close($ch);
    }
}
