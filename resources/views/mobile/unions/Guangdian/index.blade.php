<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>广电影视联盟</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
.mui-table-view-cell > a:not(.mui-btn) {
    margin: 18px;
}
.mui-col-xs-6{
    box-sizing:border-box;
    -moz-box-sizing:border-box; /* Firefox */
    -webkit-box-sizing:border-box; /* Safari */
    border: 1px solid #e8e8e8;
    height:150px;
}
.mui-col-xs-6 a{
    overflow: hidden;
    width: 100%;
    height:100%;
    display: block;
    position: relative;
}
.message{
    position: absolute;
    right:20%;
    top:20%;
    width:20px;
    height:20px;
    text-align: center;
    background: #ff3c5f;
    line-height: 20px;
    border-radius:10px;
    color:white;
    font-size:9px;
}
.mui-col-xs-6 a img{
    display: block;
    width: 38px;
    height:38px;
    margin:40px auto 26px;
}
.mui-table-view .mui-media-body {
    padding-top: 12px;
}
.mui-col-xs-6 a p{
    color:#222222;
    text-align: center;
    font-size: 14px;
}
.mui-table-view .mui-media-object {
    line-height: 70px;
    max-width: 70px;
    height: 70px;
}

.mui-media img {
    border-radius: 3px;
}

.mui-table-view .mui-media-object.mui-pull-left {
    margin-right: 15px;
}

.mui-media p {
    color: #666;
    margin-top: 5px;
}

.mui-table-view:after {
    right: 15px;
    left: 15px;
    height: 0;
}
.mui-table-view-cell:after {
    right: 15px;
    background-color: #e8e8e7;
}
.mui-table-view-cell:last-child:before, .mui-table-view-cell:last-child:after {
    height: 1px;
}

</style>
</head>
<body class="bgwh">
@if($hasAccessKey)
<ul class="mui-row mui-table-view">
    <input type="hidden" value="{{$userId}}" name="userId" />
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/intro/{{$unionId}}?title=联盟简介">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E8%81%94%E7%9B%9F%E7%AE%80%E4%BB%8B@2x.png" />
            <p>联盟简介</p>
        </a>
    </li>
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/bignews/{{$unionId}}?user_id={{$userId}}&title=联盟大事记">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E8%81%94%E7%9B%9F%E5%A4%A7%E4%BA%8B%E8%AE%B0@2x.png" alt="大事记">
            <?php
            $howManybignewNotRead=0;
            ?>
            @foreach($messagesForBignews as $message)
                <?php
                $thisMesIsNotRead=DB::table('message_receivers')->where('receiver_id',$userId)->where('message_id',$message->id)->value('is_read');

                if($thisMesIsNotRead==0){
                    $howManybignewNotRead++;
                }
                ?>
            @endforeach
            @if($howManybignewNotRead!=0)
                <span class="message">{{$howManybignewNotRead}}</span>
            @endif
            <p>联盟大事记</p>
        </a>
    </li>
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/notice/{{$unionId}}?user_id={{$userId}}&title=联盟通知">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E8%81%94%E7%9B%9F%E9%80%9A%E7%9F%A5@2x.png" alt="通知">
            <?php
                $howManyMesIsNotRead=0;
            ?>
            @foreach($messages as $message)
            <?php
                $thisMesIsNotRead=DB::table('message_receivers')->where('receiver_id',$userId)->where('message_id',$message->id)->value('is_read');

                if($thisMesIsNotRead==0){
                    $howManyMesIsNotRead++;
                }
            ?>
            @endforeach
            @if($howManyMesIsNotRead!=0)
            <span class="message">{{$howManyMesIsNotRead}}</span>
            @endif
            <p>联盟通知</p>
        </a>
    </li>
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/coopration/{{$unionId}}?user_id={{$userId}}&title=联盟项目合作">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E6%8A%B1%E5%9B%A2%E5%8F%96%E6%9A%96@2x.png" >
            <?php
            $howManycooprationRead=0;
            ?>
            @foreach($messagesForCoopration as $message)
                <?php
                $thisMesIsNotRead=DB::table('message_receivers')->where('receiver_id',$userId)->where('message_id',$message->id)->value('is_read');

                if($thisMesIsNotRead==0){
                    $howManycooprationRead++;
                }
                ?>
            @endforeach
            @if($howManycooprationRead!=0)
                <span class="message">{{$howManycooprationRead}}</span>
            @endif
            <p>联盟项目合作</p>
        </a>
    </li>
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/recommend/{{$unionId}}?title=艺人推荐">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E5%86%85%E9%83%A8%E8%89%BA%E4%BA%BA%E6%8E%A8%E8%8D%90@2x.png" >
            <p>联盟艺人推荐</p>
        </a>
    </li>
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/vipcompanies/{{$unionId}}?title=联盟单位会员">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E5%85%AC%E5%8F%B8%E4%BC%9A%E5%91%98@2x.png" >
            <p>联盟单位会员</p>
        </a>
    </li>
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/vip/{{$unionId}}?title=联盟个人会员">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E4%B8%AA%E4%BA%BA%E4%BC%9A%E5%91%98@2x.png" >
            <p>联盟个人会员</p>
        </a>
    </li>
    <li class="mui-col-xs-6">
        <a href="/mobile/unions/feedback/{{$unionId}}?user_id={{$userId}}&title=联盟会员反馈">
            <img class="mui" src="http://nanzhu.oss-cn-shanghai.aliyuncs.com/unions/%E4%BC%9A%E5%91%98%E5%8F%8D%E9%A6%88@2x.png" alt="公司">
            <p>联盟会员反馈</p>
        </a>
    </li>
</ul><!--/end-->
@else
    <p style="margin-top: 260px;text-align: center"> 您并非此联盟会员</p>
@endif
</body>
<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/screenSize.js"></script>
<script>
    window.onload=function(){
        getRem(750,100);

    };
    window.onResize=function () {
        getRem(750,100);
    };
    $(document).ready(function () {
        window.nanzhu.showTitle('广电影视联盟',false,{});
    })

    function history_back(){
        window.nanzhu.showTitle('公会组织',false,{});
        window.history.back();
    }
</script>
</html>