<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WeatherController extends BaseController
{
    /**
     * 获取心知天气的签名
     * @return string
     */
    private function getSignatureName()
    {
        $key     = "lvacqqj3xpvmbmhu";
        $uid     = "U116D9CCC5";
        $keyname = "ts=" . time() . "&ttl=30&uid=" . $uid;
        $sig     = base64_encode(hash_hmac('sha1', $keyname, $key, true));
        return $keyname . "&sig=" . urlencode($sig);
    }

    /**
     * 获取需要查询的天气的地址
     *
     * @param Request $request
     *
     * @return mixed
     */
    private function getNeedToSearchLocationCode(Request $request)
    {
        //如果查询天气中有某一个用户的user_id,而且用户保存了地址信息,查询用户保存的地址
        if ($userId = $request->input('user_id')) {
            $user = User::find($userId);

            if ($user && $user->location_code) {
                return $user->location_code;
            }
        }

        if ($request->input('location')) {
            return $request->input('location');
        }

        return isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
    }

    /**
     * 获取某一地区的天气
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //获取要查询天气的地址
        $location = $this->getNeedToSearchLocationCode($request);

        //获取要查询天气的开始日期
        $startDate = $this->getSearchStartDate($request);

        $cacheKey = $location . ':' . $startDate;
        if ($cachedWeatherData = Redis::get($cacheKey)) {
            return response()->json(json_decode($cachedWeatherData));
        }

        //获取心知天气的api签名
        $signedkeyname = $this->getSignatureName();

        //获取今天的天气信息
        try {
            $todayWeatherData = $this->getTodayWeatherData($location, $startDate, $signedkeyname);
        } catch (\Exception $e) {
            return $this->responseFail('心知天气暂时不支持该城市数据');
        }

        //节气信息
        $jieqi_result = $this->getJieQiData($startDate, $signedkeyname);

        //日出日落信息
        $sun_result = $this->getSunData($location, $startDate, $signedkeyname);

        //月初月落
        $moon_result = $this->getMoonData($location, $startDate, $signedkeyname);

        $todayOtherData = $this->getTodayOtherData($location, $signedkeyname);

        $returnData = [
            "city"         => isset($todayWeatherData->results) ? $todayWeatherData->results[0]->location->name : 'Beijing',
            "one_day"      => isset($todayWeatherData->results) ? $todayWeatherData->results[0]->daily[0] : '',
            "result"       => $todayWeatherData,
            "sun_result"   => $sun_result,
            "moon_result"  => $moon_result,
            "jieqi_result" => $jieqi_result,
            'other_result' => $todayOtherData,
        ];

        Redis::setex($cacheKey, 6 * 60 * 60, json_encode($returnData));

        return response()->json($returnData);

    }

    private function get_chinese_weekday($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        return '星期' . ['日', '一', '二', '三', '四', '五', '六'][$weekday];
    }

    private function fetch_data($url)
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

    /**
     * 更新用户的位置信息
     *
     * @param         $userId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation($userId, Request $request)
    {
        User::where('FID', $userId)->update([
            'location_code' => $request->input('location')
        ]);

        return $this->ajaxResponseSuccess('位置信息更新成功');
    }

    /**
     * 获取位置信息
     */
    public function getLocation($userId)
    {
        $user = User::find($userId);

        $location = $user ? $user->location_code : '';

        return $this->responseSuccess('获取成功', ['location' => $location]);
    }

    /**
     * 获取天气的城市列表
     */
    public function getProvinces()
    {
        $allProvinces = \DB::select(<<<ALL_CITIES
SELECT city_id as value,belong as label FROM cities where grade = '中国地级市'  group by belong  order by english_belong
ALL_CITIES
        );

        return $this->ajaxResponseSuccess('操作成功', [
            'provinces' => $allProvinces
        ]);
    }

    /**
     * 获取天气的市列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $superCities = ["北京", "上海", "重庆", "天津", "香港", "澳门", "海南"];

        $province = $request->input("province");

        if (in_array($province, $superCities)) {
            $cities = \DB::select("SELECT city_id as value,name as label FROM cities  where  name = '{$province}' and grade = '中国地级市' ");
        } else {
            $cities = \DB::select("SELECT city_id as value,belong2 as label,name FROM cities where  belong='{$province}'  group by belong2");
        }

        return $this->ajaxResponseSuccess('操作成功', [
            'cities' => $cities
        ]);
    }

    /**
     * 获取所有地区
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistricts(Request $request)
    {
        $city = $request->input('city');

        $s_cityes = ["北京", "上海", "重庆", "天津", "香港", "澳门", "海南"];

        if (in_array($city, $s_cityes)) {
            $districts = \DB::select("SELECT city_id as value,name  as label FROM cities where  belong='{$city}'");
        } else {
            $districts = \DB::select("SELECT city_id as value,name  as label FROM cities  where  belong2= '{$city}' and grade = '中国其他' ");
        }

        return $this->ajaxResponseSuccess('操作成功', [
            'districts' => $districts
        ]);
    }

    /**
     * @param Request $request
     *
     * @return false|int|string
     */
    private function getSearchStartDate(Request $request)
    {
        $start = $request->input('day') ? date("Y/m/d", strtotime($request->input('day'))) : 0;
        return $start;
    }

    /**
     * 获取今天的天气信息
     *
     * @param $location
     * @param $startDate
     * @param $signatureName
     *
     * @return array
     */
    private function getTodayWeatherData($location, $startDate, $signatureName)
    {
        $url    = "https://api.thinkpage.cn/v3/weather/daily.json?key=lvacqqj3xpvmbmhu&location={$location}&language=zh-Hans&unit=c&start={$startDate}&days=10&{$signatureName}";
        $result = $this->fetch_data($url);

        \Log::info('weather request location' . $location);
        \Log::info('weather data' . json_encode($result));
        if (is_null($result) || is_null($result->results[0]) || !isset($result->results[0]->daily)) {
            throw new \Exception('weather input illegal');
        }
        foreach ($result->results[0]->daily as &$daily) {
            $daily->chinese_week_day = $this->get_chinese_weekday($daily->date);
        }

        return $result;
    }

    /**
     * 获取节气信息
     *
     * @param $startDate
     * @param $signatureName
     *
     * @return mixed
     */
    private function getJieQiData($startDate, $signatureName)
    {
        $url = "https://api.thinkpage.cn/v3/life/chinese_calendar.json?key=lvacqqj3xpvmbmhu&start={$startDate}&days=10&{$signatureName}";

        return $this->fetch_data($url);
    }

    /**
     * 获取日落日出数据
     *
     * @param $location
     * @param $startDate
     * @param $signatureName
     *
     * @return mixed
     */
    private function getSunData($location, $startDate, $signatureName)
    {
        $url = "https://api.thinkpage.cn/v3/geo/sun.json?key=lvacqqj3xpvmbmhu&location={$location}&language=zh-Hans&start={$startDate}&days=10&{$signatureName}";

        return $this->fetch_data($url);
    }

    /**
     * 获取月落信息
     *
     * @param $location
     * @param $startDate
     * @param $signatureName
     *
     * @return mixed
     */
    private function getMoonData($location, $startDate, $signatureName)
    {
        $url = "https://api.thinkpage.cn/v3/geo/moon.json?key=lvacqqj3xpvmbmhu&location={$location}&language=zh-Hans&start={$startDate}&days=10&{$signatureName}";

        return $this->fetch_data($url);
    }

    /**
     * 获取其他数据
     *
     * @param $location
     * @param $signatureName
     *
     * @return mixed
     */
    public function getTodayOtherData($location, $signatureName)
    {
        $url = "https://api.thinkpage.cn/v3/weather/now.json?key=lvacqqj3xpvmbmhu&location={$location}&language=zh-Hans&unit=c&{$signatureName}";

        return $this->fetch_data($url);
    }

}
