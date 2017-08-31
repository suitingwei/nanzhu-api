<?php require("header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $city; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<style>
body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, textarea, p, blockquote, th, td, hr, button, article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {
    margin: 0;
    padding: 0
}

sup {
    font-size: 70%;
    line-height: 0;
    position: relative;
    vertical-align: baseline;
    font-weight: normal;
    font-family: serif;
    top: -0.5em;
}

em {
    font-style: normal;
}

a, a:hover {
    color: #fff;
    text-decoration: none;
}

ul {
    list-style: none
}

body {
    font-family: -apple-system, "Helvetica Neue", Arial, "PingFang SC", "Hiragino Sans GB", STHeiti, "Microsoft YaHei", "Microsoft JhengHei", "Source Han Sans SC", "Noto Sans CJK SC", "Source Han Sans CN", "Noto Sans SC", "Source Han Sans TC", "Noto Sans CJK TC", "WenQuanYi Micro Hei", SimSun, sans-serif;
    font-size: 16px;
    background: #060206 url("http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1134871668.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
}

body:before {
    content: ' ';
    position: fixed;
    z-index: -1;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: #060206 url("http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1134871668.jpg");
    background-repeat: no-repeat;
    background-size: cover;
}

@font-face {
    font-family: uii;
    font-weight: normal;
    font-style: normal;
    src: url('assets/mobile/font/ui.ttf') format('truetype');
}

.mi {
    font-family: uii;
    font-size: 24px;
    font-weight: normal;
    font-style: normal;
    line-height: 1;
    display: inline-block;
    text-decoration: none;
    -webkit-font-smoothing: antialiased;
}

.mi-search:before {
    content: '\e466'
}

.cf:before,
.cf:after {
    content: " ";
    display: table;
}

.cf:after {
    clear: both;
}

.weather-details {
    color: #fff;
}

.today, .day, .weather a {
    display: inline-block;
    vertical-align: top;
}

.fs {
    font-size: 12px;
}

.fm {
    font-size: 14px;
}

.fl {
    font-size: 18px;
}

.flg {
    font-size: 22px;
}

.tl {
    text-align: left;
}

.tc {
    text-align: center;
}

.tr {
    text-align: right;
}

.today-info ul li {
    margin-bottom: 5px;
}

.today-c {
    padding-top: 30px;
    padding-bottom: 40px;
}

.today-c img {
    width: 50px;
    vertical-align: bottom;
    margin-right: 10px;
}

.today-c strong {
    font-weight: 100;
    font-size: 4em;
}

.day-list {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    border-top: 1px solid #999;
    padding-top: 30px;
    padding-bottom: 30px;
}

.day {
    margin-left: 15px;
    margin-right: 15px;
    text-align: center;
}

.day:first-child {
    margin-left: 30px;
}

.day:last-child {
    margin-right: 30px;
}

.day img {
    width: 50px;
    margin-top: 10px;
}

.today-data {
    text-align: center;
    margin-bottom: 30px;
}

.today-data .mod {
    display: inline-block;
    text-align: left;
}

.today-data .mod:first-child {
    margin-right: 3em;
}

em.flg span {
    margin-left: 15px;
}

.time-update {
    padding: 10px 5px;
    color: #ccc;
}
.btn-local {
    text-align: center;
    margin-bottom: 10px;
}
.btn-local a {
    display: inline-block;
    border-radius: 100px;
    padding: 8px 10px;
    background-color: rgba(0, 0, 0, .2);
}
.btn-local a img {
    width: 74.5px;
    display: block;
}
.ipt-wrap {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 50px;
    display: inline-block;
    margin: 10px 0 20px;
    padding-left: 10px;
    min-width: 210px;
}

form select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: 0;
    border: 0;
    background-color: transparent;
    min-width: 50px;
    max-width: 110px;
    text-align: center;
    padding: 8px;
    height: 36px;
    vertical-align: middle;
    font-size: 14px;
    color: #fff;
    border-radius: 0;
    -webkit-transition-duration: 0.25s;
    transition-duration: 0.25s;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
}

form select:hover {
    background-color: transparent;
    color: #64cf7a;
    outline: 0;
}

form button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: 0;
    background-color: transparent;
    border: 0;
    vertical-align: middle;
    font-size: 14px;
    -webkit-transition-duration: 0.25s;
    transition-duration: 0.25s;
    color: #64cf7a;
    height: 36px;
    width: 62px;
    padding: 8px 10px;
    text-align: center;
    border-radius: 0 50px 50px 0;
    position: relative;
    left: -5px;
}

form button:hover, form button:focus {
    color: #64cf7a;
    outline: 0;
}
</style>
</head>
<body>
<div class="weather weather-details">
    <div class="time-update tc">
        <span class="fs">最后更新时间：<?php print_r($now_result->results[0]->last_update); ?></span>
    </div>
    <div class="btn-local tc">
        <a onclick="resetUserLocation(<?php echo $userId ?>)"><img src="assets/mobile/img/btn-local.png" alt="切换本地"></a>
    </div>
    <div class="tc">
        <div class="fm">
            当前城市：<span class="fl"><?php echo $city; ?></span>
        </div>
        <div class="ipt-wrap">
            <form id="search_form" action="/weather_detail.php" method="get">
                <input type="hidden" name="from" value="app">
                <input type="hidden" name="user_id" value="<?php echo $userId ?>">
                <input type="hidden" name="location" id="location" value="">
                <select id="provinces">
                    <option value="">请选择省</option>
                </select><select id="cities">
                    <option value="">请选择市</option>
                </select><select id="districts">
                    <option value="">请选择区</option>
                </select>
                <button id="search_form_btn" class="btn" type="button">切换</button>
            </form>
        </div>
    </div>
    <div class="today-info">
        <ul>
            <li class="tc">
                <em class="flg"><?php echo $one_day->date; ?>
                    <span><?php echo get_chinese_weekday($one_day->date); ?></span></em>
            </li>
            <li class="tc">
				<span class="fl">
					<?php echo($jieqi_result->results->chinese_calendar[0]->lunar_month_name . $jieqi_result->results->chinese_calendar[0]->lunar_day_name); ?>
                    <?php echo $jieqi_result->results->chinese_calendar[0]->solar_term ?>
				</span>
            </li>
            <li class="tc">
                <span class="fl"><?php print_r($one_day->text_night); ?></span>
            </li>
            <li class="today-c tc">
                <img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/weather/<?php echo($one_day->code_day); ?>.png"/>
                <strong><?php echo($one_day->high); ?>/<?php echo($one_day->low); ?><sup class="c">°</sup></strong>
            </li>
        </ul>
        <div class="today-data cf">
            <div class="mod">
                <ul>
                    <li>
                        风向： <?php print_r($now_result->results[0]->now->wind_direction); ?>
                    </li>
                    <li>
                        日出时间：<?php echo $sun_result->results[0]->sun[0]->sunrise ?>
                    </li>
                    <li>
                        日落时间：<?php echo $sun_result->results[0]->sun[0]->sunset ?>
                    </li>
                    <li>
                        能见度：<?php print_r($now_result->results[0]->now->visibility); ?>km
                    </li>
                    <li>
                        湿度： <?php print_r($now_result->results[0]->now->humidity); ?>%
                    </li>
                </ul>
            </div>
            <div class="mod">
                <ul>
                    <li>
                        风力：<?php print_r($now_result->results[0]->now->wind_scale); ?>
                    </li>
                    <li>
                        月出时间：<?php echo $moon_result->results[0]->moon[0]->rise; ?>
                    </li>
                    <li>
                        月落时间：<?php echo $moon_result->results[0]->moon[0]->set; ?>
                    </li>
                    <li>
                        月相：<?php echo $moon_result->results[0]->moon[0]->phase; ?>
                    </li>
                    <li>
                        气压：<?php print_r($now_result->results[0]->now->pressure); ?>mb
                    </li>
                </ul>
            </div>
        </div>
    </div><!--/end-->

    <div class="day-list">

        <?php for ($i = 1; $i < 10; $i++) { ?>
            <div class="day">
                <a href="/weather_detail.php?from=app&current_user_id=111&day=<?php echo($result->results[0]->daily[$i]->date); ?>">
                    <ul>
                        <li>
                            <p><?php $date   = ($result->results[0]->daily[$i]->date);
                                $monthAndDay = substr($date, strpos($date, '-') + 1);
                                echo $monthAndDay;
                                ?>
                            </p>
                            <p><span
                                    class="fm"><?php echo get_chinese_weekday($result->results[0]->daily[$i]->date); ?></span>
                            </p>
                            <span
                                class="fm"><?php echo($jieqi_result->results->chinese_calendar[$i]->lunar_month_name . $jieqi_result->results->chinese_calendar[$i]->lunar_day_name); ?></span>
                        </li>
                        <li>
                            <img
                                src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/weather/<?php echo($result->results[0]->daily[$i]->code_day); ?>.png"/>
                        </li>
                        <li>
                            <p><span class="fm"><?php print_r($result->results[0]->daily[$i]->text_day); ?></span></p>
                            <span class="fs"><?php echo($result->results[0]->daily[$i]->high); ?>
                                /<?php echo($result->results[0]->daily[$i]->low); ?><sup class="c">℃</sup></span>
                        </li>
                    </ul>
                </a>
            </div>
        <?php } ?>

    </div><!--/end-->
</div><!--/end-->

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    $.getJSON("/cities", function (data) {
        $.each(data, function (key, val) {
            $("#provinces").append("<option value=\"" + val.label + "\">" + val.label + "</option>");
        });
        $.getJSON("/cities?province=" + data[0].label, function (cdata) {
            $.each(cdata, function (key, val) {
                $("#cities").append("<option value=\"" + val.label + "\">" + val.label + "</option>");
            });
        });
    });

    $("#provinces").on("change", function () {
        $.getJSON("/cities?province=" + $(this).val(), function (data) {
            $("#cities").empty();
            $("#districts").empty();
            $.each(data, function (key, val) {
                $("#cities").append("<option value=\"" + val.label + "\">" + val.label + "</option>");
            });
            $.getJSON("/cities?city=" + data[0].label, function (data) {
                $("#districts").empty();
                $.each(data, function (key, val) {
                    $("#districts").append("<option value=\"" + val.value + "\">" + val.label + "</option>");
                });
            });
        });
    });

    $("#cities").on("change", function () {
        $.getJSON("/cities?city=" + $(this).val(), function (data) {
            $("#districts").empty();
            $.each(data, function (key, val) {
                $("#districts").append("<option value=\"" + val.value + "\">" + val.label + "</option>");
            });
        });
    });

    $("#search_form_btn").on("click", function () {
        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

        var location = $("#districts").val();
        var userId = '<?php echo $userId?>';
        $("#location").val(location);

        if (isIOS) {
            $.ajax({
                url: '/api/users/' + userId + '/weather/location',
                data: {location: location},
                method: 'put',
                datatype: 'json',
                success: function () {
                    $("#search_form").submit();
                }
            });
        } else {
            $("#search_form").submit();
        }

    });

    function history_back() {
        window.nanzhu.backHome();
    }

    /**
     * 切换本地
     */
    function resetUserLocation(userId){

        $.ajax({
            url: '/api/users/' + userId + '/weather/location',
            data: {location: ''},
            method: 'put',
            datatype: 'json',
            success: function () {
                $("#search_form").submit();
            }
        });
    }
</script>
</body>
</html>