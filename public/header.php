<?php

$location = null;
$userId   = $_GET['user_id'];
if (isset($_GET['location'])) {
    $location = $_GET['location'];
}

if (empty($location)) {
    $location = $_SERVER['HTTP_X_REAL_IP'];// "Beijing";
}

if(empty($location)){
   $location = $_SERVER['REMOTE_ADDR'];
}

$key           = "lvacqqj3xpvmbmhu";
$uid           = "U116D9CCC5";
$keyname       = "ts=" . time() . "&ttl=30&uid=" . $uid;
$sig           = base64_encode(hash_hmac('sha1', $keyname, $key, true));
$signedkeyname = $keyname . "&sig=" . urlencode($sig);

$start = 0;
$date  = $_GET['day'];
if ($date) {
    $start = date("Y/m/d", strtotime($date));
}

$url = "https://api.thinkpage.cn/v3/weather/daily.json?key=lvacqqj3xpvmbmhu&location=" . $location . "&language=zh-Hans&unit=c&start=" . $start . "&days=10&" . $signedkeyname;

$result = fetch_data($url);

$city = $result->results[0]->location->name;

$one_day = $result->results[0]->daily[0];

//节气 
$url = "https://api.thinkpage.cn/v3/life/chinese_calendar.json?key=lvacqqj3xpvmbmhu&start=" . $start . "&days=10" . "&" . $signedkeyname;

$jieqi_result = fetch_data($url);


//print_r($jieqi_result->results->chinese_calendar[0]->lunar_festival);

//print_r($jieqi_result->results->chinese_calendar[0]->solar_term);

//日出日落
$url = "https://api.thinkpage.cn/v3/geo/sun.json?key=lvacqqj3xpvmbmhu&location=" . $location . "&language=zh-Hans&start=" . $start . "&days=10" . "&" . $signedkeyname;

$sun_result = fetch_data($url);

//print_r($sun_result->results[0]->sun[0]->sunrise);
//print_r($sun_result->results[0]->sun[0]->sunset);


//月初月落
$url         = "https://api.thinkpage.cn/v3/geo/moon.json?key=lvacqqj3xpvmbmhu&location=" . $location . "&language=zh-Hans&start=" . $start . "&days=10" . "&" . $signedkeyname;
$moon_result = fetch_data($url);

$url        = "https://api.thinkpage.cn/v3/weather/now.json?key=lvacqqj3xpvmbmhu&location=" . $location . "&language=zh-Hans&unit=c&" . $signedkeyname;
$now_result = fetch_data($url);

$url = "https://api.thinkpage.cn/v3/air/now.json?key=lvacqqj3xpvmbmhu&location=" . $location . "&language=zh-Hans&scope=city&" . $signedkeyname;

$pm_result = fetch_data($url);


function fetch_data($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Get the response and close the channel.
    $response = curl_exec($ch);

    $result = json_decode($response);

    return $result;

    curl_close($ch);
}

function get_chinese_weekday($datetime)
{
    $weekday = date('w', strtotime($datetime));
    return '星期' . ['日', '一', '二', '三', '四', '五', '六'][$weekday];
}

?>
