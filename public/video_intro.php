<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>专业版介绍视频</title>
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<script src="/assets/mobile/js/flexible_css.js"></script>
<link rel="stylesheet" href="/assets/mobile/css/style.css">
<style>
.d-t {
    font-size: 20px;
    color: #202020;
    margin-bottom: 0.4rem;
}

.d-bd p {
    margin-bottom: 0.4rem;
}

[data-dpr="2"] .d-t {
    font-size: 40px;
}

[data-dpr="3"] .d-t {
    font-size: 62px;
}

.d-t2 {
    font-size: 18px;
    color: #202020;
    margin-bottom: 0.2rem;
}

[data-dpr="2"] .d-t2 {
    font-size: 36px;
}

[data-dpr="3"] .d-t2 {
    font-size: 58px;
}

.btn-wrap {
    padding: 0.8rem 0;
}

.btn-wrap .btn-primary {
    background-color: #2183cc;
    color: #fff;
    border-color: #2183cc;
}

.video {
    margin-bottom: 0.6rem;
}

.video video {
    width: 100%;
    height: 5.000555rem;
    background: #000;
    margin-bottom: 0.4rem;
    position: relative;
    z-index: 2;
}
</style>
</head>
<body class="bg-w">

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
</div><!-- header end -->

<div class="container">
    <div class="details pd40">
        <div class="d-hd">
            <h1 class="d-t">托人找朋友？成本也不少，不如...</h1>
        </div>
        <div class="d-bd aj">
            <p>男生刚拍完清装戏，头发还没留起来？<br/>女生为了上一个角色，把长发剪掉了？<br/>赶紧来弄一个现在状态的“自我介绍”视频吧！<br/>比如下面这两款：</p>
            <div class="video ac">
                <video src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/professional/3_new.mp4"
                       poster="http://nanzhu.oss-cn-shanghai.aliyuncs.com/video/intro/new3.jpg" controls="controls">
                    您的浏览器不支持 video 标签。
                </video>
                <div>专业版视频一</div>
            </div>
            <div class="video ac">
                <video src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/professional/2.mp4" poster="http://nanzhu.oss-cn-shanghai.aliyuncs.com/video/intro/2.jpg" controls="controls">
                    您的浏览器不支持 video 标签。
                </video>
                <div>专业版视频二</div>
            </div>
            <h2 class="d-t2">专业版介绍：</h2>
            <ul>
                <li>场景：专业内景场地，</li>
                <li>灯光：面光，轮廓光，背景光，</li>
                <li>器材：5D MakeⅢ或同级别专业全高清摄像机</li>
                <li>剪辑：简单</li>
                <li>台词：标准模板</li>
                <li>拍摄方式：多角度多景别</li>
                <li>时长：1分30秒左右</li>
                <li>价格：¥1,500元</li>
            </ul>
            <div class="btn-wrap">
                <a class="btn btn-primary btn-block btn-lg" href="javascript:;">我要录制专业版</a>
                <!--<a class="btn btn-primary btn-block btn-lg" href="https://dev.nanzhuxinyu.com/mobile/shoot_orders/create">我要录制专业版</a>-->
            </div>
            <p>当然，您也可以自己录制，并从手机相册中直接上传。但需注意时长不得超过1分30秒。上传时间也许会较长，请注意您当前的网络环境。</p>
        </div>
    </div>
</div><!-- container end -->


<!-- share -->
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/assets/javascripts/jquery.min.js"></script>
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
            title: "南竹通告单专业版介绍视频",
            link: "https://dev.nanzhuxinyu.com/video-intro.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
        });
        wx.onMenuShareAppMessage({
            title: "南竹通告单专业版介绍视频",
            desc: "托人找朋友？成本也不少，不如...",
            link: "https://dev.nanzhuxinyu.com/video-intro.php",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
            type: 'link',
        });
    });
</script>
</body>
</html>