<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>温馨提示</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        body {
            background-color: #fff;
        }
        .tips {
            padding-top: 60%;
            line-height: 1.6;
        }
        .tips .i-tips {
            color: #bbbec4;
            font-size: 60px;
        }
    </style>
</head>
<body>
<div class="sheet">

    <div class="tips tc">
        <p><i class="mui-icon if i-tips"></i></p>
        <p class="f16 g9">对不起，您已不是本部门部门长<br>请返回工作台刷新界面!</p>
    </div><!--/end-->

</div><!--/end-->
</body>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
    var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if(isAndroid){
        setTimeout(function(){
            window.nanzhu.backHome();
        },1000);
    }
    if(isIOS){
        popToMenu();
    }
</script>
</html>
