<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <title>Title</title>
</head>
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
    * {
        margin: 0;
        padding: 0
    }

    #body {
        font-weight: 400;
        padding-bottom: 1.08rem;
        font-size: .26rem;
    }

    .footer {
        height: .98rem;
        background: #e7353a;
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 999;
        box-sizing: content-box
    }

    .footer .downloadnow {
        height: .98rem;
        line-height: .98rem;
        text-align: center;
        color: #ea384a;
        width: 100%;
        font-size: .28rem;
        color: white;
        font-weight: 400;
    }

    .title {
        color: #e92d3e;
        font-size: .36rem;
        line-height: .36rem;
        margin: .8rem 0 .6rem;
        text-align: center;
    }

    .con {
        color: #333;
        font-size: .26rem;
        line-height: .40rem;
    }

    .aboutus, .tables, .coopration, .join {
        padding: 0 .34rem .3rem;
    }

    video {
        width: 100%;
    }

    .ezart p {
        width: 100%;
        text-align: center;
        color: #999;
    }

    .logo img {
        width: 2.73rem;
        height: .81rem;
        margin: .76rem auto 0;
        display: block;
    }

    .avatar img {
        width: 2.04rem;
        height: 2.04rem;
        border-radius: 50%;
        margin: .76rem auto 0;
        display: block;
    }

    .ezart .lixia {
        text-align: center;
        margin-top: .28rem;
        line-height: .34rem;
        font-size: .34rem;
        color: #333;
        margin-bottom: .34rem;
    }

    .ezart {
        padding-bottom: 1rem;
    }

    .more {
        height: .18rem;
        width: 100%;
        background: #e9e9e9;
    }

    .aboutus {
        padding-bottom: .65rem;
    }

    .videoForStar {
        position: relative;
        height: 3.84rem;
        width: 100%;

    }

    .videoForStar video {
    }

    .playbtn {
        background: url('img/play.png') no-repeat;
        position: absolute;
        background-size: 1.2rem 1.2rem;
        width: 1.2rem;
        height: 1.2rem;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: 3

    }

    .canvas {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
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
</style>
<body style="background:white">
<div id="body" style="display: none">
    {{--logo--}}
    <div class="ezart">
        <p class="logo">
            <img src="/mobile/easy-educations/logo@2x.png"/>
        </p>
        <p class="avatar">
            <img src="/mobile/easy-educations/1.jpg"/>
        </p>
        <p class="lixia">李霞</p>
        <p>容艺教育创始人兼CEO</p>
    </div>
    {{--标题--}}
    <div class="more"></div>
    <div class="aboutus">
        <p class="title">
            "影视黄埔"--容艺教育
        </p>
        <p class="con">
            容艺教育，第一家专注于文创产业人才培养的专业教育机构！隶属于容艺传媒集团，由中国著名主持人，制片人李霞联合行业大咖管虎，黄渤，胡晓峰，杨东，张文伯，黄烽共同创办。2015年创立至今，容艺教育背靠强大的行业资源，用独创的市场化课程体系、高效能体验式教学模式以及全封闭式教学管理为影视行业培养“召之即来，来之能战”的实战型人才，在业界引起不小的反响，被誉为“影视黄埔”！
        </p>
    </div>

    {{--全能艺人--}}
    <div class="more"></div>
    <div class="aboutus">
        <p class="title"> 大师班简介</p>
        <p class="con" style="margin-bottom:.4rem;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp“大师班”是容艺传媒针对影视行业从业者开设的由业内顶级“大腕”亲自授课，分享成功经验的独创订制课程。由创始人李霞女士亲自邀请国内外一线大咖加盟助阵，组成“大师联盟”。旨在帮助从业人员提升实战能力、专业竞争力的同时，获取业内一手经验，迅速拓展人脉资源。
            <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp课程以“电影”为主题，围绕电影相关全流程、多环节的实战工作内容，展开“最专业”、“最前沿”的纯干货课程分享及生动趣味的互动交流。通过大师们的亲自讲解，能够使学员迅速掌握电影工业全流程的要领，为电影行业输送实战型人才。
            <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp导师团队囊括国内外业界最顶尖的大师代表，涵盖顶级导演、王牌制片人、顶尖编剧、资深影视法律顾问、权威电影大数据分析师、电影营销大咖等在内的豪华导师阵容，订制专属于影视行业从业人员的独家课程，因材施教、有的放矢。
            <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp课程内容均来自诸位顶尖级导师对亲身实战经验的珍贵总结与多年来心血成果的沉淀。享誉全球、知名作品创作的独家经验分享，业内实战宝典的层层解密，你想要的，只有大师班能够给你！ 
            <br>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp大师班分国内大师班和国外大师班，自2015年7月开班至今，已成功举办16期，大师班累积学员超过1200人，在获得业内普遍认可与赞誉的同时，更备受学员的好评和推崇！容艺创始人李霞女士更亲自带队赴好莱坞，成功举办两届好莱坞大师班，与制片人工会、华纳、派拉蒙、狮门影业、南加大及中美电影节等机构交流合作，学习好莱坞先进电影工业流程的同时，将优质项目引入国内。2017年5月，容艺更应戛纳电影节组委会邀请，带领国内优秀青年制片人参加戛纳电影节，与全球优秀制片人、电影公司交流学习。
        </p>
    </div>

    <div class="more"></div>
    <div class="img tc">
        <img data-preview-src="" data-preview-group="1"
             src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E6%A8%A1%E5%9D%971.png"
             alt="" width="100%">
        <img data-preview-src="" data-preview-group="1"
             src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E6%A8%A1%E5%9D%972.png"
             alt="" width="100%">
        <img data-preview-src="" data-preview-group="1"
             src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E6%A8%A1%E5%9D%973.png"
             alt="" width="100%">
        <img data-preview-src="" data-preview-group="1"
             src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E6%A8%A1%E5%9D%974.png"
             alt="" width="100%">
    </div>

    <div class="more"></div>
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F0.png"
         alt="" width="98%" style="margin-left: auto;margin-right:auto;display: block;">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F1.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F2.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F3.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F4.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F5.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F6.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F7.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F8.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F9.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%B8%88%E8%B5%84%E5%9B%A2%E9%98%9F10.png"
         alt="" width="100%">

    <div class="more"></div>
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B40.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B41.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B42.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B43.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B44.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B45.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B46.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B47.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B48.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B49.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E7%B2%BE%E5%BD%A9%E7%9E%AC%E9%97%B410.png"
         alt="" width="100%">

    <div class="more"></div>
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%90%8C%E5%AD%A6%E6%9E%84%E6%88%90.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%90%8C%E5%AD%A6%E6%9E%84%E6%88%902.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%90%8C%E5%AD%A6%E6%9E%84%E6%88%903.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%90%8C%E5%AD%A6%E6%9E%84%E6%88%904.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%90%8C%E5%AD%A6%E6%9E%84%E6%88%906.png"
         alt="" width="100%">
    <img src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A4%A7%E5%B8%88%E7%8F%AD-%E5%90%8C%E5%AD%A6%E6%9E%84%E6%88%907.png"
         alt="" width="100%">


    <div class="footer">
        <div class="downloadnow" onclick="jumpToJoin()">我要一对一咨询</div>
    </div>
</div>
</body>
<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/assets/mobile/js/m.js"></script>
<script>

    function jumpToJoin() {

        window.location = "/mobile/education-schools/easy-education/join";
    }

    function getRem(pwidth, prem) {
        const html = document.getElementsByTagName('html')[0];
        let owidth = document.body.clientWidth || document.documentElement.clientWidth;
        if (owidth > 500) {
            owidth = 500;
        }
        html.style.fontSize = owidth / pwidth * prem + 'px';
    }

    window.onload = function () {
        getRem(750, 100);
        $('#body').css('display', 'block');
        $('#loading').css('display', 'none');
    };
    window.onResize = function () {
        getRem(750, 100)
    };

    const currentUrl = encodeURIComponent(location.href.split('#')[0]);
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
        const title = '容艺教育';
        wx.onMenuShareTimeline({
            title: title,
            link: "https://apiv2.nanzhuxinyu.com/mobile/education-schools/easy-education",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/WechatIMG16769.jpg",
        });
        wx.onMenuShareAppMessage({
            title: title,
            desc: title,
            link: "https://apiv2.nanzhuxinyu.com/mobile/education-schools/easy-education",
            imgUrl: "http://nanzhu.oss-cn-shanghai.aliyuncs.com/WechatIMG16769.jpg",
            type: 'link'
        });
    });
</script>

</html>