<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>工作台使用说明</title>
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<script src="/assets/mobile/js/flexible_css.js"></script>
<link rel="stylesheet" href="/assets/mobile/css/style.css">
<style>
img {
    width: 10.0rem;
}
</style>
</head>
<body class="bg-w">

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

<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1230354516.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/632637260.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1740536572.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/2047230549.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1751983309.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/422281517.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/642979145.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/2089909532.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1291343529.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1400504908.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1210662183.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1044562174.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/37096761.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/4522506.jpg">

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
            title: "南竹通告单工作台使用说明",
            link: "https://apiv2.nanzhuxinyu.com/help/work.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
        });
        wx.onMenuShareAppMessage({
            title: "南竹通告单工作台使用说明",
            desc: "南竹通告单\n工作台使用说明",
            link: "https://apiv2.nanzhuxinyu.com/help/work.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
            type: 'link',
        });
    });
</script>
</body>
</html>