<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>南竹通告单</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
.container {
    padding-top: 20px;
}
.c-b {
    color: #505752;
}
.c-xb {
    color: #888;
}
.pd20 {
    padding: 20px;
}
.mgt15 {
    margin-top: 15px;
}
.cf:before, .cf:after, .r:before, .r:after {
    display: table;
    content: ' ';
}
.cf:after, .r:after {
    clear: both;
}
.r > [class*='g-'] {
    float: left;
}
.g-xs-6, .g-xs-12 {
    position: relative;
    min-height: 1px;
}
.g-xs-12 {
    width: 100%;
}
.g-xs-6 {
    width: 50%;
}
/* border */
.bdr-g {
    border-right: 0.5px solid #d9d9d9;
}
.details-bdwrap, .bdb-g, .bdt-g {
    position: relative;
}
.bdb-g:after {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    content: '';
    -webkit-transform: scaleY(.5);
    transform: scaleY(.5);
    background-color: #d9d9d9;
}
.details-bdwrap:before, .bdt-g:before {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    content: '';
    -webkit-transform: scaleY(.5);
    transform: scaleY(.5);
    background-color: #d9d9d9;
}
.mod-line .r > [class*='g-'] {
    padding: 15px 20px;
}
.details-bd {
    padding-top: 20px;
    padding-bottom: 100px;
}
.details-bd .content h4 {
    margin-bottom: 20px;
}
.details-bd .content h5 {
    color: #333;
    font-size: 17px;
    font-weight: bold;
}
.details-bd img {
    max-width: 100%;
    height: auto;
    margin-bottom: 15px;
}
p {
    color: #333;
    margin-bottom: 20px;
    font-size: 16px;
    line-height: 1.6;
    white-space: pre-wrap;
    word-wrap: break-word;
    text-align: justify;
}
.details-bd .content p,
.details-bd .content pre {
    color: #333;
    margin-bottom: 20px;
    font-size: 16px;
    line-height: 1.6;
    white-space: pre-wrap;
    word-wrap: break-word;
    text-align: justify;
}

.mui-popup-inner {
    padding: 25px 15px 15px;
}

.mui-fullscreen {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}

.mui-slider {
    position: relative;
    z-index: 1;
    overflow: hidden;
    width: 100%;
}

.mui-preview-image.mui-fullscreen {
    position: fixed;
    z-index: 20;
    background-color: #000;
}

.mui-preview-header,
.mui-preview-footer {
    position: absolute;
    width: 100%;
    left: 0;
    z-index: 10;
}

.mui-preview-header {
    height: 44px;
    top: 0;
}

.mui-preview-footer {
    height: 50px;
    bottom: 0px;
}

.mui-preview-header .mui-preview-indicator {
    display: block;
    line-height: 25px;
    color: #fff;
    text-align: center;
    margin: 15px auto 4;
    width: 50px;
    background-color: rgba(0, 0, 0, 0.4);
    border-radius: 12px;
    font-size: 16px;
    margin: 10px auto 0;
}

.mui-preview-image {
    display: none;
    -webkit-animation-duration: 0.5s;
    animation-duration: 0.5s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}

.mui-preview-image.mui-preview-in {
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
}

.mui-preview-image.mui-preview-out {
    background: none;
    -webkit-animation-name: fadeOut;
    animation-name: fadeOut;
}

.mui-preview-image.mui-preview-out .mui-preview-header,
.mui-preview-image.mui-preview-out .mui-preview-footer {
    display: none;
}

.mui-zoom-scroller {
    position: absolute;
    display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    align-items: center;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    left: 0;
    right: 0;
    bottom: 0;
    top: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    -webkit-backface-visibility: hidden;
}

.mui-zoom {
    -webkit-transform-style: preserve-3d;
    transform-style: preserve-3d;
}

.mui-slider .mui-slider-group .mui-slider-item img {
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 100%;
}

.mui-android-4-1 .mui-slider .mui-slider-group .mui-slider-item img {
    width: 100%;
}

.mui-android-4-1 .mui-slider.mui-preview-image .mui-slider-group .mui-slider-item {
    display: inline-table;
}

.mui-android-4-1 .mui-slider.mui-preview-image .mui-zoom-scroller img {
    display: table-cell;
    vertical-align: middle;
}

.mui-preview-loading {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    display: none;
}

.mui-preview-loading.mui-active {
    display: block;
}

.mui-preview-loading .mui-spinner-white {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -25px;
    margin-top: -25px;
    height: 50px;
    width: 50px;
}

.mui-preview-image img.mui-transitioning {
    -webkit-transition: -webkit-transform 0.5s ease, opacity 0.5s ease;
    transition: transform 0.5s ease, opacity 0.5s ease;
}

@-webkit-keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

@-webkit-keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

@keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

.header {
    margin-top: 64px;
    background-color: transparent;
}

.app {
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    align-items: center;
    padding: 10px 15px;
    background-color: rgba(255, 255, 255, .9);
    border-bottom: 1px solid #eee;
    width: 100%;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 5;
}

.app-icon {
    width: 50px;
    height: 50px;
    overflow: hidden;
    background: url("/assets/mobile/img/logo-lg.png") no-repeat center center;
    background-size: contain;
}

.app-text {
    margin-left: 10px;
    margin-right: auto;
}

.app-text-title {
    color: #181818;
    font-weight: normal;
    margin: 3px 0;
}

.app-text-summary {
    color: #828282;
    margin-bottom: 0;
}

.app-btn {
    display: block;
    padding: 6px 8px;
    text-align: center;
    color: #fff;
    background-color: #66c68c;
    border-radius: 3px;
    text-decoration: none;
    font-size: 16px;
}

.app-label {
    position: absolute;
    left: 0;
    top: 0;
    width: 44px;
    height: 32px;
    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFgAAABACAMAAACpzkDwAAAAY1BMVEX////kNyDkNyDkNyDkNyDkNyDkNyDkNyDkNyDkNyD99vXkNyDkNyDkNyDkNyD1xcHkNyD////99vX76+r64d/41tT2y8j0wLzztK/xqKLvm5TtjYXrf3bqcGToX1HmTTzkNyDPEQg9AAAAEXRSTlMAESIzRFVmd4iZqqq7zN3u7uYy9NoAAAHASURBVHja1dXbkoIwEATQJtyXyxJFIKiE///KJSJCVhQ3ZB62H7Sssk6Nk8agt54ijXwG2EXzJHAxxuKkScAwx5KaRS702FBTbdQnuKtrI9V3sJbFV0rezR9aIWouNzcQrKs6fOHi9i74FPG+AiHD60zmnBFuhHjPJh7eZhVuN6sVMeATuO+bRr3Ksu234TzAdh4wV5048WYTznzgD3BfV93gnvsNOPWAz+FusWJev4GzT9kX8COdKav1uL1P+gLOfWAv3KofoT/gRejAABZPPf4FJwzYDTeqGxpc+IAZrK2i5hcdjh0Yw7IUChbV9C83w7kHmMOC3+BmePIkP/QLOHGwA+74cVxFxdszr2bYZLtLuFJrVbA8XU+3VY9wxrAPburH4V34QU5wDOyE720YYHkc740BNl/DEj6oCl/VKR7lCH+7sAGX0xVXXe/H+QUr8NOV5oAEjgESOAINHIIGDkAD25h3DbbjguLcVuEYNHACGjhzaOCcgQQuXNDAAWjgGDSwxYPTYRc0cAgaOAURzIhg24uY4BxEsEcEx6CBC4cIjkADkwwMkqqNMM3AoBoYVAODphIKZkRwSuTCxz/LDzuIyYMbR8EsAAAAAElFTkSuQmCC") no-repeat;
    background-size: contain;
}
</style>
</head>
<body>

@if($from!="app")
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
@endif


<div class="container">
    <div class="mod-line cf">
        <div class="r bgwh">
            <div class="g-xs-12 bdt-g bdb-g">
                <div class="item">
                    <span class="c-xb">主题：</span>
                    <span class="c-b">{{$recruit->title}}</span>
                </div>
            </div>
            <div class="g-xs-6 bdb-g bdr-g">
                <div class="item">
                    <span class="c-xb">类型：</span>
                    <span class="c-b">{{$recruit->type}}</span>
                </div>
            </div>
            <div class="g-xs-6 bdb-g">
                <div class="item">
                    <span class="c-xb">人数：</span>
                    <span class="c-b">{{$recruit->count}}人</span>
                </div>
            </div>
            <div class="g-xs-12 bdb-g">
                <div class="item">
                    <span class="c-xb">招聘方：</span>
                    <span class="c-b">{{$recruit->employer}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="details-bdwrap bgwh bdb-g pd20 mgt15">
        <div class="c-xb">详情：</div>
        <div class="details-bd">
            {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($recruit->content) !!}
            <div class="tc">
                @foreach($recruit->pic_urls() as $pic)
                    @if(App\Models\Picture::is_from_ios($pic))
                        <img src="{{$pic}}" data-preview-src="" data-preview-group="1">
                    @else
                        <img src="{{App\Models\Picture::convert_pic($pic)}}@500w_1l_80Q_1pr" data-preview-src="" data-preview-group="1">
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @if($from=="app")
        <div style="text-align: right; padding: 20px">
            <a href="/mobile/reports/create?recruit_id={{$recruit->id}}">举报</a>
        </div>
    @endif
</div><!-- container end -->


<script src="/assets/mobile/js/m.js"></script>
<script>
    mui.init({});
    mui.previewImage();
    mui('body').on('tap', 'a', function () {
        location.href = this.getAttribute('href');
    })
</script>

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
        var str = "{{$recruit->title}}"
        wx.onMenuShareTimeline({
            title: str.replace(/&quot;/g, '"').replace(/&ldquo;/g, '“').replace(/&rdquo;/g, '”'),
            link: "{{Request::root()."/mobile/recruits/".$recruit->id}}",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png"
        });
        wx.onMenuShareAppMessage({
            title: str.replace(/&quot;/g, '"').replace(/&ldquo;/g, '“').replace(/&rdquo;/g, '”'),
            desc: str.replace(/&quot;/g, '"').replace(/&ldquo;/g, '“').replace(/&rdquo;/g, '”'),
            link: "{{Request::root()."/mobile/recruits/".$recruit->id}}",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/logo-lg.png",
            type: 'link'
        });
    });
</script>
</body>
</html>