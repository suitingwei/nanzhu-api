<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>超级功能汇总</title>
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<script src="/assets/mobile/js/flexible_css.js"></script>
<link rel="stylesheet" href="/assets/mobile/css/style.css">
<style>
html {
    height: 100%;
}
body {
    background-color: #fff;
}
.help-menu {
    border-top: 1px solid #ccc;
    padding: 0 0.533333rem;
}
.help-menu a {
    display: block;
    color: #333;
    padding: 0.45rem 0.1rem;
    border-bottom: 1px solid #e0e0e0;
    background: url('http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/419636994.png') no-repeat right center;
    background-size: 0.2rem auto;
}
</style>
</head>
<body>

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

<div class="help-menu">
    <a href="/help/work.php?from=app">工作台使用说明</a>
    <a href="/help/log.php?from=app">场记日报表</a>
    <a href="/help/plan.php?from=app">参考大计划、每日通告单、预备通告单</a>
    <a href="/help/chat.php?from=app">聊天发送位置</a>
    <a href="/help/group.php?from=app">剧组使用教程视频</a>
    <a href="/help/memo.php?from=app">部门备忘录</a>
    <a href="/help/edit.php?from=app">协助编辑</a>
    <a href="/help/newfunction.php?from=app">勘景与资料</a>
</div>

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
            title: "南竹通告单超级功能汇总",
            link: "https://apiv2.nanzhuxinyu.com/help/help.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
        });
        wx.onMenuShareAppMessage({
            title: "南竹通告单超级功能汇总",
            desc: "南竹通告单\n超级功能汇总",
            link: "https://apiv2.nanzhuxinyu.com/help/help.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
            type: 'link',
        });
    });
</script>
</body>
</html>