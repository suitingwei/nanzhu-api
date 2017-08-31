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

        .mui-table-view {
            padding:15px;
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
        .mui-col-xs-6{
            float:left
        }
        .mui-col-xs-6 img{
            width:1.37rem;
            height:1.37rem
        }
        .mui-col-xs-6 p{

            width:95%;
           text-align: center;
        }

    </style>
</head>
<body class="bgwh">

<ul class="mui-table-view">
    @foreach($persons as $person)
        <li class="mui-col-xs-6">
            <a href="{{$ProfileUrl}}/mobile/users/{{$person->user_id}}?from=app">
            @if($person->avatar)
            <img src="{{ $person->avatar }}">
            @else
                <img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/albums/%E9%BB%98%E8%AE%A4%E5%9B%BE.png">
             @endif
             </a>
                <?php  $name=DB::table('t_sys_user')->where('FID',$person->user_id)->value('FNAME'); ?>
                <p>{{ $name }}</p>
        </li>
    @endforeach
</ul>

</body>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        window.nanzhu.showTitle('{{$title}}',false,{});

    })
    function history_back(){
        window.nanzhu.showTitle('广电影视联盟',false,{});
        window.history.back();
    }

</script>
<script>
    function getRem(pwidth,prem){
        var html=document.getElementsByTagName('html')[0];
        var owidth=document.body.clientWidth || document.documentElement.clientWidth;
        html.style.fontSize=owidth/pwidth*prem+'px';

    }
    window.onload=function () {
        getRem(320,100)
    };
    window.onResize=function(){
        getRem(320,100)
    }
    </script>
</html>


