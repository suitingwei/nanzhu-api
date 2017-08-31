<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>每日通告单</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
body {
    background-color: #fff;
}

.mui-bar {
    background-color: #3bb8a3;
    box-shadow: 0 0 0 #fff;
    padding: 8px 15px;
    height: 60px;
    position: static;
}

.mui-bar .mui-btn-link {
    color: #fff;
}

.mui-title .ipt-data {
    display: block;
    width: 50%;
    padding-left: 0;
    padding-right: 0;
    font-weight: normal;
    text-align: center;
    margin: 0 auto;
    border-color: #3bb8a3;
    background-color: #3bb8a3;
    color: #fff;
}

/* .sheet {
    padding-top: 60px;
} */

.box-a .box-hd {
    height: 56px;
    line-height: 56px;
    padding: 0 15px;
}

.sheet-list ul li {
    background-color: #f5f5f5;
}

.tips {
    padding-top: 50%;
    line-height: 1.6;
}

.tips .i-tips {
    color: #bbbec4;
    font-size: 60px;
}

.sheet .mui-table-view .mui-media, .sheet .mui-table-view .mui-media-body {
    padding-right: 50px;
}

.sheet .mui-table-view-chevron .mui-table-view-cell {
    padding-right: 0;
}

.sheet .mui-table-view-cell > a:not(.mui-btn) {
    margin: 10px 0 10px 15px;
}

.mui-media-body p {
    color: #333;
    font-size: 16px;
    margin-top: 10px;
}

.mui-table-view-cell:after {
    left: 0;
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
    background-color: #fff;
}

.tgd-option {
    margin: 10px 15px;
    border-top: 1px solid #e8e8e8;
    padding-top: 10px;
}

.tgd-option .mui-btn-outlined {
    margin-left: 10px;
    border-color: #999;
    min-width: 70px;
}

.sheet .mui-table-view .mui-media-object {
    color: #fff;
    width: 60px;
    height: 60px;
    max-width: 100%;
    overflow: hidden;
    border-radius: 5px;
    text-align: center;
    margin-right: 15px;
}

.mui-media-object .if {
    font-size: 60px;
}

.mui-table-view:before, .mui-table-view:after {
    background-color: #fff;
}

.mui-navigate-right:after, .mui-push-left:after, .mui-push-right:after {
    font-size: 24px;
}

.send-user span {
    display: inline-block;
    vertical-align: middle;
}

.send-user .g9 {
    width: 74%;
}

.read-no {
    background-color: #f15252;
}

.read-ok {
    background-color: #7e9da4;
}

.read-send {
    background-color: #ebc05e;
}

.read-sent {
    background-color: #8ead63;
}
@media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2){
    .mui-bar {
        padding: 8px 10px;
    }
    .mui-title  .ipt-data {
        width: 52.5%;
    }
}
</style>
</head>

<body>
<div class="sheet">
    <div class="mui-bar mui-bar-nav">
        <h1 class="mui-title">
            <input id="date" class="ipt-data" value="{{$day}}" type="date">
        </h1>
        <a href="/mobile/notices?type={{$type}}&movie_id={{$movie_id}}&day={{date('Y-m-d',strtotime($day)-86400)}}&user_id={{$user_id}}"
           class="mui-btn mui-btn-link mui-btn-nav mui-pull-left">
            <span class="mui-icon mui-icon-arrowleft"></span>前一天
        </a>
        <a href="/mobile/notices?type={{$type}}&movie_id={{$movie_id}}&day={{date('Y-m-d',strtotime($day)+86400)}}&user_id={{$user_id}}"
           class="mui-btn mui-btn-link mui-btn-nav mui-pull-right">
            后一天<span class="mui-icon mui-icon-arrowright"></span>
        </a>
    </div><!--/end-->
    @if(count($excels) == 0)
        <div class="tips tc">
            <p><i class="mui-icon if i-tips"></i></p>
            <p class="f16 g9">暂无有效通告单</p>
        </div>
    @else
        <?php $arr = ['A', 'B', 'C', 'D', 'E']?>
        @foreach($excels as $key => $excel)
            <div class="box-a">
                <div class="box-hd f18">
                    {{$arr[$excel->FNUMBER-1]}}组通告单
                </div>
                <div class="box-bd">

                    <div class="sheet-list">
                        <ul class="mui-table-view mui-table-view-chevron">
                            <li class="mui-table-view-cell mui-media">
                                <a url="/mobile/notices/{{$notice->FID}}?excel_id={{$excel->FID}}&user_id={{$user_id}}&filename={{$excel->FFILEADD}}"
                                   onclick="jumpWithReload(this)"
                                   class="mui-navigate-right">
                                    @if(App\Models\MessageReceiver::is_read($notice->FID,$excel->FID,$user_id))
                                        <div class="read-ok mui-media-object mui-pull-left">
                                            <i class="mui-icon if i-mopen"></i>
                                        </div>

                                    @else
                                        <div class="read-no mui-media-object mui-pull-left">
                                            <i class="mui-icon if i-mclose"></i>
                                        </div>
                                    @endif
                                    <div class="mui-media-body">
                                        <p class="mui-ellipsis">{{$excel->FFILENAME}}</p>

                                        @if($is_show_receivers > 0)
                                            <span class="g9 f14">{{App\Models\MessageReceiver::read_rate($notice->FID,$excel->FID)}}</span>
                                        @endif
                                    </div>
                                </a>
                                @if($is_show_receivers > 0)
                                <div class="tgd-option tr">
                                        <a href="/mobile/notices/{{$notice->FID}}/receivers?movie_id={{$movie_id}}&excel_id={{$excel->FID}}"
                                           class="mui-btn mui-btn-outlined">接收详情</a>
                                </div>
                                @endif
                            </li>

                        </ul>
                    </div><!--/end-->
                </div>
            </div><!--/end-->
        @endforeach
    @endif
</div><!--/end-->

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>

    $(function () {
        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if (isAndroid) {
            $("#date").change(function () {
                window.location.href = "/mobile/notices?type={{$type}}&movie_id={{$movie_id}}&user_id={{$user_id}}&day=" + ($(this).val());
            });
        }
        if (isIOS) {
            $("#date").blur(function () {
                window.location.href = "/mobile/notices?type={{$type}}&movie_id={{$movie_id}}&user_id={{$user_id}}&day=" + ($(this).val());
            });
        }
    });

    function history_back(){
        window.nanzhu.backHome();
    }

    function jumpWithReload(aTag){
        var url = $(aTag).attr('url');

        setTimeout('window.location.reload()',1000);

        window.location.href=url;
    }
</script>

</body>
</html>
