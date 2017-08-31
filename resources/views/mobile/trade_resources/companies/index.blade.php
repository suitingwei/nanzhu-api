<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>制作公司</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
.mui-table-view-cell > a:not(.mui-btn) {
    margin: 18px;
}

.mui-table-view .mui-media-body {
    padding-top: 12px;
}

.mui-table-view .mui-media-object {
    line-height: 70px;
    max-width: 70px;
    height: 70px;
}

.mui-media img {
    border-radius: 3px;
}

.mui-table-view .mui-media-object.mui-pull-left {
    margin-right: 15px;
}

.mui-media p {
    color: #666;
    margin-top: 5px;
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
</style>
</head>
<body class="bgwh">
@if($companies->count() > 0)
<ul class="mui-table-view">
    @foreach($companies as $company)
        <li class="mui-table-view-cell mui-media">
            <a href="/mobile/trade-resources/companies/{{$company->id}}?user_id={{ request('user_id') }}&can_share=true&wechat_share_json={{ urlencode($company->wechat_share_json)}}&title={{ $company->title }}&from=app">
                <img class="mui-media-object mui-pull-left" src="{{$company->logo}}">
                <div class="mui-media-body">
                    <span class="mui-ellipsis" style="font-size: 17px;color:#333;width:100%;display: block">{{ $company->title }} </span>
                    <p class="mui-ellipsis">{{ $company->short_introduction }}</p>
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
        window.nanzhu.showTitle('制作公司',false,{});
    })


    function history_back(){
        window.nanzhu.showTitle('业内资源',false,{});
        window.history.back();
    }
</script>
</html>