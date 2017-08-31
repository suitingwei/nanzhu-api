<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>公会组织</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        .mui-row {
            line-height: 0;
        }

        .mui-table-view a {
            display: block;
        }

        .mui-row img {
            width: 100%;
            height: auto;
        }

        .mui-row li {
            border-bottom: 2px solid #fff;
        }

        .mui-row li:nth-child(even) {
            border-left: 1px solid #fff;
        }

        .mui-row li:nth-child(odd) {
            border-right: 1px solid #fff;
        }
        .fornum{
            position: relative;
        }
        .numnotred{
            position: absolute;
            z-index: 4;
            display: block;
            height: 20px;
            min-width: 20px;
            border-radius: 10px;
            background-color: #ff584e;
            line-height: 20px;
            text-align: center;
            color: white;
            font-size: 12px;
            top:49%;
            transform: translate(-50%,-50%);
            left: 30%;
            padding: 0 5px;
        }
    </style>
</head>
<body class="bgwh">

<ul class="mui-table-view">

    <li class="mui-col-xs-12 fornum">
        <a href="/mobile/unions/16?user_id={{$user->FID}}&title=广电影视联盟">
            @if($user->unionUnReadCount())
                <span class="numnotred">{{$user->unionUnReadCount()}}</span>
            @endif

            <img class="mui"
                 src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/advertisements/%E5%B9%BF%E7%94%B5%E5%BD%B1%E8%A7%86%E8%81%94%E7%9B%9F.jpg"
                 alt="公司" style="width: 100%; max-height: 125px;">
        </a>
    </li>
    {{--<li class="mui-col-xs-12">
        <a href="/mobile/unions/18?user_id={{$userId}}&title=南竹通告">

            <img class="mui"
                 src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/albums/100/2@2x.png"
                 alt="公司" style="width: 100%; max-height: 125px;">
        </a>
    </li>--}}
</ul>
</body>
<script>
    window.document.ready(function () {
        window.nanzhu.showTitle('公会组织', false, {});
    });

    function history_back() {
        window.history.back();
    }
</script>
</html>


