<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $basement->title }}</title>
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

        .writers h2 {
            font-weight: normal;
            border-top: 1px solid #e8e8e7;
            font-size: 20px;
            padding-top: 28px;
            margin: 0 20px 22px;
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

        p {
            margin-bottom: 5px !important;
            width: 100% !important;
            white-space: normal !important;
        }

        p {
            margin-bottom: 5px !important;
        }

        .sendCV {
            float: left;
            height: 35px;
            text-align: center;
            width: 45%;
            padding: 0;
            font-size: 14px;
            border-radius: 4px;
        }

        .forbutton {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px 25px;
            border-top: 1px solid #d4d4d4;
            background-color: #fff;
        }

        .sendCoopration {
            float: right;
            width: 45%;
            height: 35px;
            line-height: 35px;
            text-align: center;
            padding: 0;
            font-size: 14px;
            border: 1px solid #3bb8a3;
            color: #3bb8a3;;
            border-radius: 4px;
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

<div class="details-resource">
    <div class="details-logo tc">
        <img src="{{ $basement->cover }}">
    </div>

    <h1 class="details-title lh30 tc">{{ $basement->title }}</h1>

    <div class="resource-bd tj">
        <div class="content">
            <p>{!! $basement->markdown_introduction !!}</p>

        </div>
        <div class="img tc">
            @foreach($basement->pictures as $picture)
                <img src="{{ $picture->url }}" data-preview-src="" data-preview-group="1">
            @endforeach
        </div>
        @if($from =='app')
            @if($basement->type!='companyvip' && $basement->type!='union' && $basement->allow_cooperation==1)
                <div class="forbutton">
                    <button class="mui-btn-success sendCV"
                            onclick="sendCV('{{ request('user_id')}}','{{$basement->id}}')">发送个人简历
                    </button>
                    <a class="sendCoopration"
					   href="/mobile/trade-resources/coopration/basement?userId={{request('user_id')}}&basementId={{$basement->id}}"
                       >合作邀约</a>
                </div>
            @endif
        @endif
    </div>

</div>


<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/assets/mobile/js/m.js"></script>
<script>

	function jumpCooperationPage(){
		window.location.href='/mobile/trade-resources/coopration/basement?userId={{request('user_id')}}&basementId={{$basement->id}}';
	}

    $(document).ready(function () {
        mui.init();
        mui.previewImage();
        mui('body').on('tap', 'a', function () {
            location.href = this.getAttribute('href');
        });

        const currentUrl = encodeURIComponent(location.href.split('#')[0]);

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
                title: '{{ $basement->title }}',
                link: '{{ $basement->getCompanyShowPageUrl() }}',
                imgUrl: '{{ $basement->cover }}',
            });
            wx.onMenuShareAppMessage({
                title: '{{ $basement->title }}',
                desc: '{{ $basement->title }}',
                link: '{{ $basement->getCompanyShowPageUrl() }}',
                imgUrl: '{{ $basement->cover}}',
                type: 'link'
            });
        });

        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        if (isAndroid) {
            window.nanzhu.showTitle('{{ $basement->title }}', true, getWechatShareJson());
        }
    });

    function getWechatShareJson() {
        return JSON.stringify({
            title: '{{ $basement->title }}',
            content: '{{ $basement->title }}',
            cover: '{{ $basement->share_cover }}',
            url: '{{ $basement->getCompanyShowPageUrl()}}'
        });
    }

    function passWechatShareJsonToIOS() {
        return getWechatShareJson();
    }

    function history_back() {
        window.nanzhu.showTitle('影视基地', false, {});
        window.history.back();
    }

    function sendCV(userId, basementId) {
        console.log(userId);
        $.ajax({
            type: 'post',
            url: '/mobile/trade-resources/sendCV',
            data: {'userId': userId, 'basementId': basementId},
            success: function (res) {
                if (res.msg == 0) {
                    mui.alert('您的资料不完善，请先完善个人资料');
                }
                if (res.msg == 1) {
                    var btnArray = ['否', '是'];
                    mui.confirm('', '是否将您 的个人资料确认发给对方?', btnArray, function (e) {
                        if (e.index == 1) {
                            $.ajax({
                                type: 'post',
                                url: '/mobile/trade-resources/sendProfile/basement',
                                data: {'profileId': res.data.ProfileId, 'basementId': basementId, 'userId': userId},
                                success: function (respon) {
                                    mui.alert('发送成功!');
                                }
                            })
                        }
                    });
                }
            }
        })
    }
</script>

</body>
</html>


