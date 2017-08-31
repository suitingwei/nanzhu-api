<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>联盟单位会员</title>
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
@if(!$basements)
@else
<body class="bgwh">

<ul class="mui-table-view">
    @foreach($basements as $basement)
        <li class="mui-table-view-cell mui-media">
            <a href="/mobile/trade-resources/basements/{{$basement->id}}?user_id={{ request('user_id') }}&can_share=true&wechat_share_json={{ urlencode($basement->wechat_share_json)}}&title={{ $basement->title }}&from=app">

                <img class="mui-media-object mui-pull-left" src="{{$basement->cover}}">

                <div class="mui-media-body">
                    <span>{{$basement->title}} </span>
                    <p>{{$basement->introduction}}</p>
                </div>
            </a>
        </li>
    @endforeach
</ul>

</body>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        window.nanzhu.showTitle('联盟单位会员',false,{});
    })
    function history_back(){
        window.nanzhu.showTitle('广电影视联盟',false,{});
        window.history.back();
    }
</script>
@endif
</html>


