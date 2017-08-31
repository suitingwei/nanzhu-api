<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>可交易剧本</title>
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
}
.mui-media p {
    color: #333;
    margin-top: 5px;
    white-space: normal;
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
<body class="bgwh">
@if($scripts->count() > 0)
<ul class="mui-table-view">
    @foreach($scripts as $script)
        <li class="mui-table-view-cell mui-media">
            <a href="/mobile/trade-resources/scripts/{{ $script->id }}?user_id={{ request('user_id') }}&can_share=true&wechat_share_json={{ $script->wechat_share_json }}&title={{$script->title}}&from=app">
                @if($script->pictures()->count()>0)
                    <img class="mui-media-object mui-pull-right" src="{{ $script->pictures()->first()->url }}">
                @endif
                <div class="mui-media-body">
                    <span>{{ $script->title }}</span>
                    <p>{{ $script->short_introduction }}</p>
                    <div class="s-author mui-ellipsis g9 f12">作者：{{ $script->author }}</div>
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
    <div style="padding-top:20px;color:#999999;font-size: 15px;position: absolute;top:50%;left:50%;transform: translate(-50%,-50%)">
        <p style="text-align:center">如需入驻请发资料至</p>
        <p style="text-align:center">ynzy@nanzhuxinyu.com</p>
    </div>
@endif
</body>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>

    $(document).ready(function () {
        window.nanzhu.showTitle('可交易剧本',false,{});
    })

    function history_back(){
        window.nanzhu.showTitle('业内资源',false,{});
        window.history.back();
    }
</script>
</html>


