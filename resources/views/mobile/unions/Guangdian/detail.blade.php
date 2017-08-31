<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta name="format-detection" content="telephone=no">
    <script src="/assets/mobile/js/flexible_css.js"></script>
    <link rel="stylesheet" href="/assets/mobile/css/style.css">

    <style>
        html, body {
            min-height: 100%;
            background: #f0f0f0;
        }

        .d-t {
            font-size: 18px;
        }

        [data-dpr="2"] .d-t {
            font-size: 40px;
        }

        [data-dpr="3"] .d-t {
            font-size: 58px;
        }

        @font-face {font-family: "ifile";
            src: url('/assets/mobile/font/iconfile.eot'); /* IE9*/
            src: url('/assets/mobile/font/iconfile.eot#iefix') format('embedded-opentype'), /* IE6-IE8 */
            url('/assets/mobile/font/iconfile.woff') format('woff'), /* chrome, firefox */
            url('/assets/mobile/font/iconfile.ttf') format('truetype'), /* chrome, firefox, opera, Safari, Android, iOS 4.2+*/
            url('/assets/mobile/font/iconfile.svg#iconfont') format('svg'); /* iOS 4.1- */
        }

        .ifile-list {
            border-top: 1px solid #d1d0d4;
            margin-top: 0.5rem;
        }
        .ifile-list li {
            border-bottom: 1px solid #d1d0d4;
            background: url('http://nanzhu.oss-cn-shanghai.aliyuncs.com/pictures/419636994.png') no-repeat 98% center;
            background-size: 0.2rem auto;
            padding: 0.32rem 0;
        }
        .ifile-list li span {
            display: inline-block;
            vertical-align: middle;
            width: 75%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .ifile {
            font-family:"ifile" !important;
            font-style:normal;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-size: 50px;
            display: inline-block;
            vertical-align: middle;
        }
        [data-dpr="2"] .ifile {
            font-size: 100px;
        }

        [data-dpr="3"] .ifile {
            font-size: 150px;
        }
        .ifile-exl {
            color: #0d944d;
        }
        .ifile-ppt {
            color: #fe7338;
        }
        .ifile-word {
            color: #3980c0;
        }
        .ifile-pdf {
            color: #f04c32;
        }
        .ifile-exl:before { content: "\e677"; }
        .ifile-ppt:before { content: "\e674"; }
        .ifile-word:before { content: "\e675"; }
        .ifile-pdf:before { content: "\e676"; }
        .details-resource {
            padding: 20px 0;
        }

        .details-logo {
            width: 140px;
            height: 140px;
            border-radius: 4px;
            overflow: hidden;
            margin: 10px auto 20px;
        }

        .details-logo img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .details-title {
            font-weight: normal;
            font-size: 22px;
            padding: 0 20px;
        }

        .resource-bd {
            padding-bottom: 100px;
        }

        .resource-bd .content {
            padding-left: 20px;
            padding-right: 20px;
        }

        .resource-bd .content h4 {
            margin-bottom: 20px;
        }

        .resource-bd .content h5 {
            color: #333;
            font-size: 17px;
            font-weight: bold;
        }

        .resource-bd .img {
            margin: 0 20px;
        }

        .resource-bd img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }

        .resource-bd .content p,
        .resource-bd .content pre {
            color: #333;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            text-align: justify;
        }

        .details-fixed {
            padding: 8px 0;
            text-align: center;
            background: #fff;
        }

        .details-fixed:before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 1px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: #ddd;
        }

        .details-fixed .mui-btn-success {
            font-size: 18px;
            border-radius: 100px;
            width: 170px;
            padding: 6px 0;
        }

        .writers h2 {
            font-weight: normal;
            border-top: 1px solid #e8e8e7;
            font-size: 20px;
            padding-top: 28px;
            margin: 0 20px 22px;
        }

        .writers-item {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .writers-item .slider-img:first-child {
            margin-left: 20px;
        }

        .writers-item .slider-img {
            display: inline-block;
            width: 60px;
            margin-right: 10px;
            text-align: center;
            font-size: 16px;
        }

        .writers-item .slider-img p {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            background: #f9f9f9 url("/assets/mobile/img/logo-d.png") no-repeat center center;
            background-size: contain;
        }

        .writers-item .slider-img img {
            display: block;
            max-width: 100%;
            height: auto;
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



<div class="container">
    <div class="details pd40">
        <div class="d-hd">
            <h1 class="d-t">{{$message->title}}</h1>
            <p class="c-lg">{{$message->date}}</p>
        </div>
        <div class="d-bd c-g aj">
            @foreach($message->pictures() as $pic)
                @if(App\Models\Picture::is_from_ios($pic))
                    <img src="{{$pic}}" data-preview-src="" data-preview-group="1" style="width:100%" >
                @else
                    <img src="{{App\Models\Picture::convert_pic($pic)}}@500w_1l_80Q_1pr" data-preview-src="" data-preview-group="1" style="width:100%">
                @endif
            @endforeach

            {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($message->content) !!}
        </div>


        @if($message->files->count() > 0)
            <ul class="ifile-list">
                @foreach($message->files as $file)
                    <li onclick="openFile('{{ $file->file_url }}','{{ $file->file_name }}')">
                        <i class="ifile
                        @if(strpos($file->file_url,'xls') !== false)
                                ifile-exl
                        @elseif(strpos($file->file_url,'pdf')!== false)
                                ifile-pdf
                        @elseif(strpos($file->file_url,'doc')!== false )
                                ifile-word
                        @elseif(strpos($file->file_url,'ppt')!== false )
                                ifile-ppt
                        @endif
                                "></i>
                        <span>{{ $file->file_name}}</span>
                    </li>
                @endforeach
            </ul>
        @endif


    </div>
</div>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/m.js"></script>
<script>
    $(document).ready(function () {
        window.nanzhu.showTitle('{{$message->title}}',false,{});
    });
    function history_back(){
        window.nanzhu.showTitle('',false,{});
        if({{$detailType}}=='UNIONCOOPRAT')
        {
            window.location.href ='{{$DetailUrl}}/mobile/unions/coopration/{{$unionId}}?user_id=28623&title=联盟项目合作';
        }
        if({{$detailType}}=='UNIONBIGNEWS')
        {
            window.location.href ='{{$DetailUrl}}/mobile/unions/bignews/{{$unionId}}?user_id=28623&title=联盟大事记';
        }
        if({{$detailType}}=='UNIONNOTICE')
        {
            window.location.href ='{{$DetailUrl}}/mobile/unions/notice/{{$unionId}}?user_id=28623&title=联盟通知';
        }


    }


    function openFile(url, title) {
        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

        if (isIOS) {
            window.location.href = url;
        } else {
            window.nanzhu.openFiles(url, title);
        }
    }
    mui.init();
    mui.previewImage();
    mui('body').on('tap', 'a', function () {
        location.href = this.getAttribute('href');
    });

</script>
</body>
</html>