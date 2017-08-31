<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        .mui-table-view-cell > a:not(.mui-btn) {
            margin: 18px;

        }

        .mui-table-view .mui-media-object {
            line-height: 100px;
            max-width: 100px;
            height: 100px;
        }

        .mui-table-view .mui-media-object.mui-pull-left {
            margin-right: 15px;
        }

        .mui-media span {
            color: #202020;
            line-height:30px;
            font-size:17px;
            margin-top: 5px;
        }
        .mui-media p {
            color: #333;
            margin-top: 5px;
            white-space: normal;
            overflow : hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            font-size: 14px;
        }

        .mui-table-view:after {
            right: 15px;
            left: 15px;
            height: 0;
        }
        .mui-table-view-cell:after {
            right: 15px;
            background-color: #e8e8e7;
        }
        .mui-table-view-cell:last-child:before, .mui-table-view-cell:last-child:after {
            height: 1px;
        }
        .s-author {
            margin-top: 11px;
        }
    </style>
</head>
<body class="bgwh" >
@if($basements->count() > 0)
<ul class="mui-table-view">
        @foreach($basements as $basement)
        <li class="mui-table-view-cell mui-media">
            <a href="/mobile/trade-resources/basements/{{$basement->id}}?user_id={{ request('user_id') }}&can_share=true&wechat_share_json={{ urlencode($basement->wechat_share_json) }}&title={{ $basement->title }}&from=app">

                <img class="mui-media-object mui-pull-left" src="{{$basement->cover}}">

                <div class="mui-media-body">
                    <span class="mui-ellipsis" style="font-size: 17px;color:#333;width:100%;display: block">{{ $basement->title }} </span>
                    <p>{{$basement->introduction}}</p>
                </div>
            </a>
        </li>
        @endforeach
</ul>
<div style="padding-top:20px;color:#999999;font-size: 15px;">
    <p style="text-align:center">如需入驻请发资料至</p>
    <p style="text-align:center">ynzy@nanzhuxinyu.com</p>
</div>
@else
    <div style="color:#999999;font-size: 15px;margin-top:250px">
        <p style="text-align:center">如需入驻请发资料至</p>
        <p style="text-align:center">ynzy@nanzhuxinyu.com</p>
    </div>
@endif
</body>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        window.nanzhu.showTitle('{{$title}}',false,{});
    })
    function history_back(){
        window.nanzhu.showTitle('业内资源',false,{});
        window.history.back();
    }
</script>
</html>


