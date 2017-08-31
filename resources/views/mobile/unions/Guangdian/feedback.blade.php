<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>制作公司详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
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
        .bgwh{
            background: white;
            padding:15px 18px;
        }
        .resource-bw{

            position:relative;
            background: white;
            border-radius:3px;

        }
        .readornot{
            position:absolute;
            right:0;
            top:0;
        }
    </style>
</head>
<body class="bgwh">


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">



            <div class="panel-body">
                <form  class="form-horizontal" action="/mobile/unions/feedbackpost" method="post" enctype='multipart/form-data'>
                    {!! csrf_field() !!}


                    <input type="hidden" name="unionId" value="{{$unionId}}">
                    <input type="hidden" name="userId" value="{{$userId}}">
                    <div class="form-group">
                        <label class="col-lg-2 control-label" style="line-height: 40px;">标题</label>
                        <div class="col-lg-8">
                            <input id="title" type="text" name="title" class="form-control" placeholder="名称">
                            <div id="titlediv"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">内容</label>
                        <input type="hidden" name="content" id="forContent">

                        <div class="col-lg-8">
                            <textarea id="editor"  style="width:100%;height:200px;" ></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-8">
                            <button type="submit" class="btn btn-sm btn-primary btn-submit">保存</button>
                        </div>
                    </div>
                </form>


            </div><!-- /.panel-body -->

        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->


<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/m.js"></script>
<script>

    $(document).ready(function () {
        window.nanzhu.showTitle('联盟会员反馈',false,{});

    })
    function history_back(){
        window.nanzhu.showTitle('广电影视联盟',false,{});
        window.history.back();
    }

    mui.init();
    mui.previewImage();
    mui('body').on('tap', 'a', function () {
        location.href = this.getAttribute('href');
    });
    $('#editor').change(function () {
        var p=$(this).val();
        $('#forContent').val(p);
    })
</script>
</body>
</html>