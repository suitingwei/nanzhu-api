<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>部门设置</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            background: #fff;
        }

        .mui-row {
            padding: 10px 10px 68px;
        }

        .wrap {
            padding: 10px;
        }

        .wrap .mui-btn {
            margin-bottom: 0;
            border-color: #ddd;
            padding: 12px 0;
        }

        .mui-radio, .mui-checkbox {
            position: static;
        }

        .mui-radio input[type='radio'], .mui-checkbox input[type='checkbox'] {
            width: 100%;
            height: 100%;
            top: auto;
            bottom: 0;
            right: 0;
        }

        .mui-radio input[type='radio']:before, .mui-checkbox input[type='checkbox']:before,
        .mui-checkbox input[type='checkbox']:checked:before {
            position: absolute;
            right: 3px;
            bottom: 3px;
        }

        .mui-radio input[type='radio']:before, .mui-checkbox input[type='checkbox']:before {
            color: #fff;
        }

        .wrap .active {
            border-color: #3bb8a3;
            color: #3bb8a3;
        }

        .mui-radio input[type='radio'][disabled], .mui-checkbox input[type='checkbox'][disabled],
        .mui-radio input[type='radio'][disabled]:before, .mui-checkbox input[type='checkbox'][disabled]:before {
            -webkit-text-fill-color: rgba(59, 184, 163, 1);
            opacity: 1;
        }

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .wrap .mui-btn {
                padding: 10px 0;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="group-list group-create">
    <form action="/mobile/groups/add_movie" method="post" accept-charset="utf-8">
        <input type="hidden" name="movie_id" value="{{$movie_id}}">

        <input type="hidden" name="user_id" value="{{$user_id}}">
        <ul class="list-unstyled mui-row">
            @foreach($groups as $group)
                <li class="mui-col-xs-6">
                    <div class="wrap">
                        <div class="active mui-btn mui-btn-outlined mui-btn-block">
                            {{$group->FNAME}}
                            <div class="mui-checkbox">
                                <input type="checkbox" checked value="{{$group->FID}}"
                                       @if($group->isEssential())
                                       disabled
                                       @else
                                       name="group_ids[]"
                                        @endif >
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul><!--/end-->

        <div class="fixed">
            <button type="submit" class="btn-fixed mui-btn mui-btn-block mui-btn-success">确定</button>
        </div><!--/end-->
    </form>
</div>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    $(".mui-btn").click(function () {
        $(this).toggleClass("active");
    });

    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
    var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

    if (isAndroid) {
        window.nanzhu.setBackVisibility('生成部门');
    }
</script>
</body>
</html>