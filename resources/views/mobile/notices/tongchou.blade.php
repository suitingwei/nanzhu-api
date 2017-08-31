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

        input[type='submit'] {
            background-color: transparent;
            color: inherit;
        }

        input[type='submit']:enabled:active {
            color: #fff;
            background-color: #929292;
            border-color: #999;
        }

        .tgd-option .mui-btn-outlined {
            background-color: transparent;
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

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .mui-bar {
                padding: 8px 10px;
            }

            .mui-title .ipt-data {
                width: 52.5%;
            }
        }
    </style>
</head>
<body>

<div class="sheet">

    <div class="mui-bar mui-bar-nav">
        <h1 class="mui-title">
            <input class="ipt-data" name="FDATE" id="date" value="{{$day}}" type="date">
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

    @if(!$notice)
        <div class="tips tc">
            <p><i class="mui-icon if i-tips"></i></p>
            <p class="f16 g9">暂无有效通告单</p>
        </div>
    @else
        <?php $arr = ['A', 'B', 'C', 'D', 'E']?>
        @foreach($notice->excelinfos() as $key => $excel)
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

                                    @if($excel->is_send() && !App\Models\Message::is_undo($notice->FID,$excel->FID))

                                        @if(App\Models\MessageReceiver::is_read($notice->FID,$excel->FID,$user_id))
                                            <div class="read-ok mui-media-object mui-pull-left">
                                                <i class="mui-icon if i-mopen"></i>
                                            </div>

                                        @else
                                            <div class="read-no mui-media-object mui-pull-left">
                                                <i class="mui-icon if i-mclose"></i>
                                            </div>
                                        @endif

                                    @else
                                        <div class="read-send mui-media-object mui-pull-left">
                                            <i class="mui-icon if i-plane"></i>
                                        </div>
                                    @endif

                                    <div class="mui-media-body">
                                        <p class="mui-ellipsis">{{$excel->FFILENAME}}</p>
                                        @if($excel->is_send() && !App\Models\Message::is_undo($notice->FID,$excel->FID) && $is_show_receivers > 0)
                                            <span class="g9 f14">{{App\Models\MessageReceiver::read_rate($notice->FID,$excel->FID)}}</span>
                                        @endif
                                    </div>
                                </a>
                                @if( ($excel->is_send() && !App\Models\Message::is_undo($notice->FID,$excel->FID) && $is_show_receivers > 0) ||
                                     ($excel->is_send() && !App\Models\Message::is_undo($notice->FID,$excel->FID) ) ||
                                     (!$excel->is_send() || App\Models\Message::is_undo($notice->FID,$excel->FID))
                                 )
                                    <div class="tgd-option tr">

                                        @if($excel->is_send() && !App\Models\Message::is_undo($notice->FID,$excel->FID) && $is_show_receivers > 0 )
                                            <a href="/mobile/notices/{{$notice->FID}}/receivers?movie_id={{ $movie_id }}&excel_id={{$excel->FID}}"
                                               class="mui-btn mui-btn-outlined">接收详情</a>
                                        @endif

                                        @if($excel->is_send() && !App\Models\Message::is_undo($notice->FID,$excel->FID))
                                            <form action="/mobile/notices/redo" class="dib" method="post"
                                                  id="cancelSendForm{{ $key }}"
                                                  accept-charset="utf-8">
                                                <input type="hidden" name="movie_id" value="{{$movie_id}}">
                                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                                <input type="hidden" name="title"
                                                       value="{{App\Models\Movie::where('FID',$movie_id)->first()->FNAME}}:通告单已经撤销。">
                                                <input type="hidden" name="content" value="{{$notice->FNAME}}">
                                                <input type="hidden" name="day" value="{{$day}}">
                                                <input type="hidden" name="filename" value="{{$excel->FFILENAME}}">
                                                <input type="hidden" name="uri"
                                                       value="/mobile/notices/{{$notice->FID}}?excel_id={{$excel->FID}}&user_id={{$user_id}}&filename={{$excel->FFILEADD}}">
                                                <input type="hidden" name="notice_id" value="{{$notice->FID}}">
                                                <input type="hidden" name="notice_file_id" value="{{$excel->FID}}">
                                                <input type="hidden" name="type" value="{{$type}}">
                                                <input type="hidden" name="notice_type" value="{{$type}}">
                                                <input id="confirmBtn" type="button" class="mui-btn mui-btn-outlined"
                                                       value="撤销发送"
                                                       onclick="confirmCancelSend('{{ $key }}','{{ $day }}','{{ $arr[$excel->FNUMBER-1] }}')">
                                            </form>
                                        @endif

                                        @if(!$excel->is_send() || App\Models\Message::is_undo($notice->FID,$excel->FID))
                                            @if($type==10)
                                                <form action="/mobile/notices/send" class="dib" method="post"
                                                      accept-charset="utf-8" onsubmit="return sendOnce()">
                                                    @else
                                                        <form action="/mobile/notices/choose" class="dib" method="post"
                                                              accept-charset="utf-8">
                                                            @endif
                                                            <input type="hidden" name="type" value="{{$type}}">
                                                            <input type="hidden" name="day" value="{{$day}}">
                                                            <input type="hidden" name="movie_id" value="{{$movie_id}}">
                                                            <input type="hidden" name="user_id" value="{{$user_id}}">
                                                            <input type="hidden" name="title"
                                                                   value="{{App\Models\Movie::where('FID',$movie_id)->first()->FNAME}}:您有新的通告单请接收。">
                                                            <input type="hidden" name="content"
                                                                   value="{{$notice->FNAME}}">
                                                            <input type="hidden" name="filename"
                                                                   value="{{$excel->FFILENAME}}">
                                                            <input type="hidden" name="uri"
                                                                   value="/mobile/notices/{{$notice->FID}}?excel_id={{$excel->FID}}&user_id={{$user_id}}&filename={{$excel->FFILEADD}}">
                                                            <input type="hidden" name="notice_id"
                                                                   value="{{$notice->FID}}">
                                                            <input type="hidden" name="notice_file_id"
                                                                   value="{{$excel->FID}}">
                                                            <input type="hidden" name="notice_type" value="{{$type}}">
                                                            <input type="submit" class="mui-btn mui-btn-outlined"
                                                                   value="发送">
                                                        </form>
                                            @endif
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
<script src="/assets/mobile/js/ui.min.js"></script>
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

    /**
     * 弹出确认是否撤销发送
     */
    function confirmCancelSend(noticeId, day, groupKey) {
        var formId = 'cancelSendForm' + noticeId;
        var btnArray = ['否', '是'];
        mui.confirm('你是否要撤销' + day + '的' + groupKey + '组通告单?', '提示', btnArray, function (e) {
            if (e.index == 1) {
                $("#" + formId).submit();
            }
        });
    }

    function history_back() {
        window.nanzhu.backHome();
    }

    /**
     * 为了防止网卡,点击多次
     */
    function sendOnce() {
        //undefined表示第一次点击
        if (typeof  sendOnce.hasSended == 'undefined') {
            return sendOnce.hasSended = true;
        }
        return false;
    }

    function jumpWithReload(aTag) {
        var url = $(aTag).attr('url');

        setTimeout('window.location.reload()', 1000);

        window.location.href = url;
    }
</script>
</body>
</html>
