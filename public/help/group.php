<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>剧组使用教程视频</title>
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<script src="/assets/mobile/js/flexible_css.js"></script>
<link rel="stylesheet" href="/assets/mobile/css/style.css">
<style>
body {
    background-color: #fff;
}
.container {
    padding-top: 0.8rem;
}
.container .video {
    border:  1px solid #d9d9d7;
    background-color: #eee;
    padding: 0.266667rem 0.266667rem 0;
    margin-bottom: 0.533333rem;
    border-radius: 4px;
}
.container video {
    width: 100%;
    height: 5.511111rem;
    background: #000;
}
.container h1, .container h2 {
    text-align: center;
    font-weight: normal;
}
.container h1 {
    font-size: 20px;
    margin-bottom: 0.5rem;
}
[data-dpr="2"] .container h1 {
    font-size: 40px;
}
[data-dpr="3"] .container h1 {
    font-size: 60px;
}
.container h2 {
    font-size: 16px;
    padding: 0.3rem 0;
}
[data-dpr="2"] .container h2 {
    font-size: 32px;
}
[data-dpr="3"] .container h2 {
    font-size: 48px;
}
</style>
</head>
<body  class="bg-w">

<?php if ($_GET['from'] !== "app") { ?>
    <div class="header">
        <div class="app">
            <div class="app-icon"></div>
            <div class="app-text">
                <h2 class="app-text-title f18">南竹通告单</h2>
                <p class="app-text-summary f16">真懂娱乐圈的平台</p>
            </div>
            <a id="appBtn" class="app-btn f18" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.zdyx.nanzhu">立即下载</a>
            <i class="app-label"></i>
        </div>
    </div>
<?php } ?>

<div class="container pd40">
    <h1>剧组使用教程视频</h1>
    <div class="video">
        <video src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/video/help/1.mp4" poster="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1740696799.jpg" controls="controls">
            您的浏览器不支持 video 标签。
        </video>
        <h2>建剧</h2>
    </div>
    <div class="video">
        <video src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/video/help/2.mp4" poster="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1497219371.jpg" controls="controls">
            您的浏览器不支持 video 标签。
        </video>
        <h2>建剧人最高权限与设置</h2>
    </div>
    <div class="video">
        <video src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/video/help/3.mp4" poster="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1305526303.jpg" controls="controls">
            您的浏览器不支持 video 标签。
        </video>
        <h2>普通组员进组</h2>
    </div>
    <div class="video">
        <video src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/video/help/4.mp4" poster="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1371228204.jpg" controls="controls">
            您的浏览器不支持 video 标签。
        </video>
        <h2>统筹手机端发送通告</h2>
    </div>
    <div class="video">
        <video src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/video/help/5.mp4" poster="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1634425642.jpg" controls="controls">
            您的浏览器不支持 video 标签。
        </video>
        <h2>统筹轻松玩转拍摄进度</h2>
    </div>
</div><!-- container end -->

<!-- share -->
<script src="/assets/javascripts/jquery.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    var currentUrl = encodeURIComponent(location.href.split('#')[0]);

    $.get('/api/wechat/get-config?current_url=' + currentUrl, function (responseData) {
        if (responseData.success) {
            wx.config({
                debug: false, // true false
                appId: responseData.data.config.appId,
                timestamp: responseData.data.config.timestamp,
                nonceStr: responseData.data.config.nonceStr,
                signature: responseData.data.config.signature,
                jsApiList: [
                    'onMenuShareTimeline', 'onMenuShareAppMessage'
                ]
            });
        }
    });

    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: "南竹通告单剧组使用教程视频",
            link: "https://apiv2.nanzhuxinyu.com/help/group.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
        });
        wx.onMenuShareAppMessage({
            title: "南竹通告单剧组使用教程视频",
            desc: "南竹通告单\n剧组使用教程视频",
            link: "https://apiv2.nanzhuxinyu.com/help/group.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
            type: 'link',
        });
    });
</script>
</body>
</html>