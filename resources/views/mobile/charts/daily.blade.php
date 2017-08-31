<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>每日数据</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
body {
    background-color: #f5f5f5;
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

.box-a .box-hd {
    height: 56px;
    line-height: 56px;
    background-color: #fff;
}

.crew-list {
    padding: 0 15px;
}

.mui-input-group {
    background-color: transparent;
}

input[type='text'], input[type='number'] {
    padding: 10px 0px;
    text-align: right;
    color: #3bb8a3;
    margin-right: 5px;
    width: 100px;
    font-size: 18px;
}

.ri {
    float: right;
    width: 54%;
    text-align: right;
}

.ri span {
    font-size: 16px;
    color: #666;
    margin-right: 5px;
}

.ri .lbfocus {
    width: auto;
    float: none;
    font-size: 16px;
    color: #666;
    margin-right: 5px;
    padding: 0;
}

.crew-list .mui-input-group .mui-input-row:after {
    left: 0;
    right: 0;
    background-color: #dadad8;
}

.mui-input-row, .mui-input-row:last-child:after {
    height: 0;
}

.mui-input-row label {
    width: 46%;
    padding-right: 0;
    padding-left: 5px;
    color: #666;
}

.tips {
    padding-top: 60%;
    line-height: 1.6;
}

.tips .i-tips {
    color: #bbbec4;
    font-size: 60px;
}

.disable input[type='text'], .disable input[type='number'], input.dis {
    color: #333;
}

.data .not_enough label, .data .not_enough .ri span,
.disable .not_enough input[type='text'],
.disable .not_enough input[type='number'],
.not_enough input.dis {
    color: #f00;
}

@media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
    .mui-bar {
        padding: 8px 10px;
    }
    .mui-title  .ipt-data {
        width: 52.5%;
    }
    .mui-input-row label {
        font-size: 15px;
    }
    .ri input[type='text'], input[type='number'] {
        font-size: 17px;
        width: 84px;
    }
    .ri span, .ri .lbfocus {
        font-size: 14px;
    }

}

.not_enough {
    color :red
}

</style>
</head>
<body>


<div class="data">
    <form id="form" action="/mobile/charts/update_daily?movie_id={{$movie_id}}&user_id={{ $user_id }}" method="post"
          accept-charset="utf-8">

        <div class="mui-bar mui-bar-nav">
            <h1 class="mui-title">
                <input class="ipt-data" name="FDATE" id="date" value="{{$day}}" type="date">
            </h1>
            <a href="/mobile/charts/daily?movie_id={{$movie_id}}&day={{date('Y-m-d',strtotime($day)-86400)}}&user_id={{$user_id}}"
               class="mui-btn mui-btn-link mui-btn-nav mui-pull-left">
                <span class="mui-icon mui-icon-arrowleft"></span>前一天
            </a>
            <a href="/mobile/charts/daily?movie_id={{$movie_id}}&day={{date('Y-m-d',strtotime($day)+86400)}}&user_id={{ $user_id }}"
               class="mui-btn mui-btn-link mui-btn-nav mui-pull-right">
                后一天<span class="mui-icon mui-icon-arrowright"></span>
            </a>
        </div><!--/end-->

        <div class="box-a">
            <div class="box-hd tc f20">
                总数据
            </div>
            <div class="box-bd disable">

                <div class="crew-list">
                    <div class="mui-input-group">
                        <div class="mui-input-row">
                            <label>当日实拍场／镜</label>
                            <div class="ri">
                                <input type="number" readonly name="fdailyScene" placeholder=""
                                       @if($daily) value="{{$daily->FDAILYSCENE}}" @endif>
                                <span>场／镜</span>
                            </div>
                        </div>
                        <div class="mui-input-row">
                            <label>当日实拍页数</label>
                            <div class="ri">
                                <input type="number" readonly name="fdailyPage" placeholder=""
                                       @if($daily)  value="{{$daily->FDAILYPAGE}}" @endif>
                                <span>页</span>
                            </div>
                        </div>
                        <div class="mui-input-row">
                            <label>剩余场／镜</label>
                            <div class="ri">
                                <input type="number" readonly name="frestScene" placeholder=""
                                       @if($daily)  value="{{$daily->FRESTSCENE}}" @endif>
                                <span>场／镜</span>
                            </div>
                        </div>
                        <div class="mui-input-row">
                            <label>剩余页数</label>
                            <div class="ri">
                                <input type="number" name="frestPage" readonly placeholder=""
                                       @if($daily) value="{{$daily->FRESTPAGE}}"@endif>
                                <span>页</span>
                            </div>
                        </div>
                        <div class="mui-input-row">
                            <label>累计拍摄总场/镜</label>
                            <div class="ri">
                                <input type="number" name="fallPage" readonly placeholder=""
                                       @if($daily)  value="{{$daily->FTOTALSCENE}}"@endif>
                                <span>场 / 镜</span>
                            </div>
                        </div>
                        <div class="mui-input-row">
                            <label>累计拍摄总页数</label>
                            <div class="ri">
                                <input type="number" name="fallPage" readonly placeholder=""
                                       @if($daily)  value="{{$daily->FALLPAGE}}"@endif>
                                <span>页</span>
                            </div>
                        </div>
                        <div class="mui-input-row">
                            <label>日均场／镜</label>
                            <div class="ri">
                                <input type="number" name="faverageScene" readonly placeholder=""
                                       @if($daily) value="{{$daily->FAVERAGESCENE}}" @endif>
                                <span>场／镜</span>
                            </div>
                        </div>
                        <div class="mui-input-row">
                            <label>日均页数</label>
                            <div class="ri">
                                <input type="number" name="faveragePage" readonly placeholder=""
                                       @if($daily) value="{{$daily->FAVERAGEPAGE}}" @endif>
                                <span>页</span>
                            </div>
                        </div>
                        @if($daily)
                        <div class="mui-input-row @if($daily->FAVERAGESCENE < $daily->FNEEDDAILYSCENE) not_enough @endif">
                            <label>此后每日需达均量</label>
                            <div class="ri">
                                <input type="number" name="fneedDailyScene" readonly placeholder=""
                                       value="{{$daily->FNEEDDAILYSCENE}}">
                                <span>场／镜</span>
                            </div>
                        </div>
                        @else
                            <div class="mui-input-row">
                                <label>此后每日需达均量</label>
                                <div class="ri">
                                    <input type="number" name="fneedDailyScene" readonly placeholder="">
                                    <span>场／镜</span>
                                </div>
                            </div>
                        @endif

                        @if($daily)
                        <div class="mui-input-row @if($daily->FAVERAGEPAGE < $daily->FNEEDDAILYPAGE) not_enough @endif">
                            <label>此后每日需达均量</label>
                            <div class="ri">
                                <input type="number" name="fneedDailyPage" readonly placeholder=""
                                       value="{{$daily->FNEEDDAILYPAGE}}" >
                                <span>页</span>
                            </div>
                        </div>
                        @else
                            <div class="mui-input-row">
                                <label>此后每日需达均量</label>
                                <div class="ri">
                                    <input type="number" name="fneedDailyPage" readonly placeholder="">
                                    <span>页</span>
                                </div>
                            </div>
                        @endif

                        <div class="mui-input-row">
                            <label>剩余天数</label>
                            <div class="ri">
                                <input type="number" name="frestDay" readonly placeholder=""
                                       @if($daily) value="{{$daily->FRESTDAY}}" @endif>
                                <span>天</span>
                            </div>
                        </div>
                    </div>
                </div><!--/end-->

            </div>
        </div><!--/end-->

        <?php $arr = ["A", "B", "C", "D", "E"] ?>
        @foreach($arr as $key=>$a)
            <input type="hidden" name="group_ids[]" value="{{$key+1}}">
            <div class="box-a">
                <div class="box-hd tc f20">
                    摄制组{{$a}}
                </div>
                <div class="box-bd">

                    <div class="crew-list">
                        <div class="mui-input-group">
                            <div class="mui-input-row">
                                <label for="IDa{{$a}}">计划场／镜</label>
                                <div class="ri">
                                    <input id="IDa{{$a}}" type="number" name="group_fplanScene[]" placeholder="必填"
                                           @if(!empty($groupdatas[$key+1]))
                                           value="{{$groupdatas[$key+1]->FPLANSCENE}}"
                                           @else
                                           value="0"
                                            @endif>
                                    <label class="lbfocus" for="IDa{{$a}}">场／镜</label>
                                </div>
                            </div>
                            <div class="mui-input-row">
                                <label for="IDb{{$a}}">计划页数</label>
                                <div class="ri">
                                    <input id="IDb{{$a}}" type="number" name="group_fplanPage[]" placeholder="必填"
                                           @if(!empty($groupdatas[$key+1]))
                                           value="{{$groupdatas[$key+1]->FPLANPAGE}}"
                                           @else
                                           value="0"
                                            @endif>
                                    <label class="lbfocus" for="IDb{{$a}}">页</label>
                                </div>
                            </div>
                            <div class="mui-input-row">
                                <label for="IDc{{$a}}">实拍场／镜</label>
                                <div class="ri">
                                    <input id="IDc{{$a}}" type="number" name="group_fdailyScene[]" placeholder="必填"
                                           @if(!empty($groupdatas[$key+1]))
                                           value="{{$groupdatas[$key+1]->FDAILYSCENE}}"
                                           @else
                                           value="0"
                                            @endif>
                                    <label class="lbfocus" for="IDc{{$a}}">场／镜</label>
                                </div>
                            </div>
                            <div class="mui-input-row">
                                <label for="IDd{{$a}}">实拍页数</label>
                                <div class="ri">
                                    <input id="IDd{{$a}}" type="number" name="group_fdailyPage[]" placeholder="必填"
                                           @if(!empty($groupdatas[$key+1]))
                                           value="{{$groupdatas[$key+1]->FDAILYPAGE}}"
                                           @else
                                           value="0"
                                            @endif>
                                    <label class="lbfocus" for="IDd{{$a}}">页</label>
                                </div>
                            </div>
                            <div class="mui-input-row">
                                <label>累计实拍场／镜</label>
                                <div class="ri">
                                    <input class="dis" type="number" name="group_fsumScene[]" readonly
                                           placeholder=""
                                           @if(!empty($groupdatas[$key+1]))
                                           value="{{$groupdatas[$key+1]->FSUMSCENE}}"
                                           @else
                                           value="0"
                                            @endif>
                                    <span>场／镜</span>
                                </div>
                            </div>
                            <div class="mui-input-row">
                                <label>累计实拍页数</label>
                                <div class="ri">
                                    <input class="dis" type="number" name="group_fsumPage[]" readonly placeholder=""
                                           @if(!empty($groupdatas[$key+1]))
                                           value="{{$groupdatas[$key+1]->FSUMPAGE}}"
                                           @else
                                           value="0"
                                            @endif>
                                    <span>页</span>
                                </div>
                            </div>

                        </div>
                    </div><!--/end-->

                </div>
            </div><!--/end-->
        @endforeach

        @if(App\Models\GroupUser::is_tongchou($movie_id,$user_id))
        <div class="btn-wrap">
            <button type="button" class="mui-btn mui-btn-block mui-btn-success"
                    onclick="form_submit(this)">
                保存
            </button>
        </div><!--/end-->
        @endif
    </form>
</div><!--/end-->

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
$(function () {
    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
    var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if (isAndroid) {
        $("#date").change(function () {
            window.location.href = "/mobile/charts/daily?movie_id={{$movie_id}}&day=" + ($(this).val())+'&user_id={{ $user_id }}';
        });
    }
    if (isIOS) {
        $("#date").blur(function () {
            window.location.href = "/mobile/charts/daily?movie_id={{$movie_id}}&day=" + ($(this).val())+'&user_id={{ $user_id }}';
        });
    }
});

function form_submit() {
    var form = $("#form");

    $.ajax({
        url: form.attr('action'),
        data: form.serialize(),
        method: 'POST',
        dataType: 'json',
        success: function (response) {
            mui.toast(response.msg);
            setTimeout(function(){
                window.location.href = response.redirect_url
            },3000);
        }
    })
}


//去掉小数点后的000000
$('input[type=number]').click(function(){
    var floatVar = $(this).val();
    var intVar   = parseInt(floatVar);
    //0.00
    if(intVar === 0 ){
        $(this).val('');
    }else if(floatVar == intVar){
        $(this).val(intVar);
    }


});

</script>
<script>
function history_back(){
    window.nanzhu.backHome();
}
</script>

</html>
