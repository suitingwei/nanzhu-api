<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>部门备忘录</title>
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

<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1557122623.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1659216427.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/328709196.jpg">
<img src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1193204095.jpg">

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
            title: "南竹通告单部门备忘录使用说明",
            link: "https://apiv2.nanzhuxinyu.com/help/memo.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
        });
        wx.onMenuShareAppMessage({
            title: "南竹通告单部门备忘录使用说明",
            desc: "南竹通告单\n部门备忘录使用说明",
            link: "https://apiv2.nanzhuxinyu.com/help/memo.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
            type: 'link',
        });
    });
</script>
</body>
</html>