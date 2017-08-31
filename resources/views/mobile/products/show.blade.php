<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>南竹通告单</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<link rel="stylesheet" href="/assets/mobile/css/swiper.min.css">
<style>
.banner img {
    display: block;
    max-width: 100%;
    height: auto;
    margin: 0 auto;
}
.details {
    padding: 20px;
    background: #fff;
}

.d-hd {
    line-height: 1.4;
    margin-bottom: 10px;
    background: #fff;
    padding: 20px;
}
.d-hd .details-title {
    font-weight: normal;
    font-size: 22px;
    margin: 0 0 5px 0;
}
.d-hd .c-g {
    font-size: 20px;
}

.cf:before, .cf:after, .r:before, .r:after {
    display: table;
    content: ' ';
}

.cf:after, .r:after {
    clear: both;
}

.details-bd {
    padding-top: 10px;
    padding-bottom: 60px;
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

.details-bd .content {
    margin-bottom: 20px;
    text-align: justify;
}
.details-bd .content p,
.details-bd .content pre {
    color: #333;
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
    margin-top: 70px;
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

.fixed {
    background: #fff;
    border-top: 0.5px solid #d9d9dc;
}
.btn-wrap {
    padding: 10px 20px;
}
.btn-wrap .mui-btn-block {
    margin-bottom: 0;
    padding: 10px 0;
}
</style>
</head>
<body>

@if(isset($_GET['from']) && $_GET['from'] !="app")
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

<div class="banner">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach($product->banners as $banner)
                <div class="swiper-slide"><img src="{{ $banner->cover }}"></div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div><!-- banner end -->

<div class="d-hd tc">
    <h1 class="details-title lh30">{{ $product->title }}</h1>
    <div class="c-g">¥{{ $product->price }}</div>
</div><!-- hd end -->

<div class="details pd40">
    <div class="details-bd tj">
        <div id="content" class="content">{{ $product->introduction }}</div>
        <div class="tc">
            @foreach($product->pictures as $picture)
                <img src="{{ $picture->url }}" data-preview-src="" data-preview-group="1">
            @endforeach
        </div>
    </div>
</div><!-- container end -->

<div class="fixed">
    <div class="btn-wrap">
        <a class="mui-btn mui-btn-success mui-btn-block" href="http://a.app.qq.com/o/simple.jsp?pkgname=com.zdyx.nanzhu">立即购买</a>
    </div>
</div><!-- btn end -->

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/m.js"></script>
<script src="/assets/mobile/js/swiper.jquery.min.js"></script>
<script>
mui.init({});
mui.previewImage();
mui('body').on('tap', 'a', function () {
    location.href = this.getAttribute('href');
});

var swiper = new Swiper('.swiper-container', {
    pagination: '.swiper-pagination',
    paginationClickable: true
});
</script>

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
        title: "{{ $product->title }}",
        link: "{{Request::root()."/mobile/malls/products/".$product->id}}",
        imgUrl: "{{ $product->banners->first()->cover }}",
    });
    wx.onMenuShareAppMessage({
        title: "{{ $product->title }}",
        desc: "{{ $product->title }}\n¥{{ $product->price }}",
        link: "{{Request::root()."/mobile/malls/products/".$product->id}}",
        imgUrl: "{{ $product->banners->first()->cover }}",
        type: 'link',
    });
});
</script>
</body>
</html>