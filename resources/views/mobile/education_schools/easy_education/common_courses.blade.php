<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <title>Title</title>
</head>
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
        <p class="title"> 全能艺人简介 </p>
        <p class="con" style="margin-bottom:.4rem;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp全能艺人认知、娱乐行业体系介绍、影视表演、语言表达与情感传递、音乐素养与演唱技巧、肢体语言与舞蹈技巧、艺人造型、气质养成、镜头前的艺人、媒体应对、心理建设、演艺事业规划。
        </p>
        <video src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%85%A8%E8%83%BD%E8%89%BA%E4%BA%BA.mp4"
               playsinline
               controls="controls"
               poster="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%85%A8%E8%83%BD%E8%89%BA%E4%BA%BA%E5%B0%81%E9%9D%A2.png"></video>
    </div>

    {{--制片人--}}
    <div class="more"></div>
    <div class="aboutus">
        <p class="title"> 制片人简介</p>
        <p class="con" style="margin-bottom:.4rem;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp制片人的职责与分工，电影类型片分析，如果选择一部好剧本，电影的知识产权与原创，制片工作流程和剧组日常管理（包括电影立项，主创的确定，预算的控制，电影前期制片，拍摄期制片，进度安排及各种表格）导演创作与制片管理，摄影美学与器材，美术造型设计与制片管理，影视录音与制片管理，特效艺术与制片管理，影视后期制作、宣传发行、制片法律实务与谈判，制片财务基本常识、宣传发行，电影衍生品开发、各大电影节介绍、行业英语，个人形象包装，行业气质培养</p>
        <video poster="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%88%B6%E7%89%87%E4%BA%BA%E5%B0%81%E9%9D%A2.png"
               playsinline
               controls
               src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%88%B6%E7%89%87%E4%BA%BA.mp4"></video>
    </div>

    <!--娱乐营销-->
    <div class="more"></div>
    <div class="aboutus">
        <p class="title">娱乐营销简介：</p>
        <p class="con" style="margin-bottom: .4rem;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp娱乐营销概述、产品定位、目标受众分析、微信图文、海报、营销页、病毒视频、H5、社会化媒体、活动、媒介选择和运营、版权意识、危机公关意识、电影营销、艺人宣传、品牌营销</p>
        <video poster="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A8%B1%E4%B9%90%E8%90%A5%E9%94%80%E5%B0%81%E9%9D%A2.png"
               playsinline
               controls="controls"
               src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E5%A8%B1%E4%B9%90%E8%90%A5%E9%94%80.mp4"></video>
    </div>

    <!--经纪人-->
    <div class="more"></div>
    <div class="aboutus">
        <p class="title">经纪人简介：</p>
        <p class="con" style="margin-bottom: .4rem;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp经纪人的职业发展，国内外经纪体制差异、好莱坞五大经纪公司介绍，艺人演艺规划秘籍、如何将新人打造成明星，艺人的企划宣传（包括新媒体、H5、病毒视频等应用）粉丝经纪、各种通告的专业流程、明星的商业拓展，明星造型打造与摄影、明星心理学、如何建立电影电视剧片源，人脉的搭建、经纪相关法务合同，时尚品牌常识、行业综合素质。</p>
        <video poster="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E7%BB%8F%E7%BA%AA%E4%BA%BA%E5%B0%81%E9%9D%A2.png"
               playsinline controls="controls"
               src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E7%BB%8F%E7%BA%AA%E4%BA%BA.mp4"></video>
    </div>

    {{--节目编导就业入口--}}
    <div class="more"></div>
    <div class="aboutus">
        <p class="title">节目编导简介：</p>
        <p class="con" style="margin-bottom: .4rem;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp基本行业认知、基本从业素质、必备基础专业技能、实际操作模拟、必备跨界技能。
        </p>
        <video poster="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E8%8A%82%E7%9B%AE%E7%BC%96%E5%AF%BC%E5%B0%81%E9%9D%A2.png"
               playsinline controls="controls"
               src="http://nanzhuvideos.oss-cn-hangzhou.aliyuncs.com/easy-educations/%E8%8A%82%E7%9B%AE%E7%BC%96%E5%AF%BC.mp4"></video>
        <img src="/mobile/easy-educations/WechatIMG16769.jpg" style="width:100%;margin-top:.66rem;"/>
        <p class="title">
            - 就业出口 -
        </p>
        <p class="con">
            目前，容艺教育已与多家影视传媒公司、艺术教育机构达成“人才委托培养、实训就业合作、人才联合经纪、项目联合孵化”的战略合作伙伴关系，并与全国逾百所影视传媒集团、艺人工作室等建立学员实训就业合作伙伴关系，为影视行业培养和输送了500余名实战型人才，更有近1200名从业人员在此进行深造和提高</p>
    </div>

    <div class="more"></div>
    <div class="join">
        <p class="title">
            - 期待你们的加入 -
        </p>
        <p class="con">
            容艺教育期待与更多影视公司及机构达成合作，破局影视行业人才荒！
        </p>
    </div>

</div>
<div class="footer">
    <div class="downloadnow" onclick="jumpToJoin()">我要一对一咨询</div>
</div>

</body>
<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
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