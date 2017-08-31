<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>南竹通告单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="https://nanzhu.oss-cn-shanghai.aliyuncs.com/assets/mobile/css/ui.css">
    <style>
        .details {
            padding: 20px;
        }

        .d-hd {
            line-height: 1.4;
            margin-bottom: 15px;
        }

        .details-title {
            font-weight: normal;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .c-lg {
            color: #999;
        }

        .c-b {
            color: #505752;
        }

        .float-l {
            float: left;
        }

        .float-r {
            float: right;
        }

        .mgb5 {
            margin-bottom: 5px;
        }

        .cf:before, .cf:after, .r:before, .r:after {
            display: table;
            content: ' ';
        }

        .cf:after, .r:after {
            clear: both;
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
<body class="bgwh">

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

<div class="details pd40">
    <div class="d-hd">
        <h1 class="details-title lh30">{{$blog->title}}</h1>
        <p class="c-b mgb5">类型：{{$blog->type_value}}</p>
        <p class="c-b cf">
            <span class="float-l">@if ($blog->author_id) {{$blog->toArray()['author']}}  @else 南竹通告单 @endif</span>
            <span class="float-r c-lg">{{$blog->created_at}}</span>
        </p>
    </div>
    <div class="details-bd tj">
        <div class="tc">
            @foreach($blog->pictures() as $pic)
                @if($pic !="http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1681114460.png")
                    <img src="{{$pic}}" data-preview-src="" data-preview-group="1">
                @endif
            @endforeach
        </div>
        <div id="content" class="content">{!! $content !!}</div>
    </div>
</div><!-- container end -->




<script src="https://nanzhu.oss-cn-shanghai.aliyuncs.com/assets/mobile/js/m.js"></script>
<script>
    mui.init({});
    mui.previewImage();
    mui('body').on('tap', 'a', function () {
        location.href = this.getAttribute('href');
    })
</script>

<!-- share -->
<script src="https://nanzhu.oss-cn-shanghai.aliyuncs.com/assets/mobile/js/jquery.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    window.onload = function () {
        var img = document.getElementsByTagName('img');
        console.log(img);
        for (var i = 0; i < img.length; i++) {
            img[i].setAttribute('data-preview-group', "1");
            img[i].setAttribute('data-preview-src', "");
        }
    };
    function htmlDecode(input) {
        var e = document.createElement('div');
        e.innerHTML = input;
        return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;

    }


    var currentUrl = encodeURIComponent(location.href.split('#')[0]);

    $.get('/api/wechat/get-config?current_url=' + currentUrl, function (responseData) {
        if (responseData.success) {
            wx.config({
                debug: false, // true falseg
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
        var str = htmlDecode("{{$blog->title}}");
        wx.onMenuShareTimeline({
            title: str,
            link: "{{Request::root()."/mobile/blogs/".$blog->id}}",
            @if($blog->type == "news" && $blog->type_value == "新闻")
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1056252751.jpg",
            @elseif ($blog->type == "news" && $blog->type_value == "辟谣")
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1474085717.jpg",
            @elseif($blog->type == "juzu")
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/2078606269.jpg",
            @elseif($blog->images->count()>0)
            imgUrl: '{{$blog->images->first()->url}}',
            @endif
        });
        wx.onMenuShareAppMessage({
            title: str,
            desc: str,
            link: "{{Request::root()."/mobile/blogs/".$blog->id}}",
            @if($blog->images->count()>0)
            imgUrl: '{{$blog->images->first()->url}}',
            @elseif($blog->type == "news" && $blog->type_value == "新闻")
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1056252751.jpg",
            @elseif ($blog->type == "news" && $blog->type_value == "辟谣")
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/1474085717.jpg",
            @elseif($blog->type == "juzu")
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/2078606269.jpg",
            @endif
            type: 'link'
        });
    });
</script>
</body>
</html>
