<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>申请加入</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        .list-unstyled{
            list-style: none;
        }
        .group-active{
            border:1px solid #3bb8a3;
            margin-bottom: 0px;
            color: #3bb8a3;
        }
        .group-join{
            margin-bottom: 0px;
            border:1px solid #e34728;
            color: #e34728;

        }
        .notjoin{
            margin-bottom: 0px;
        }
        .group-active:before{
            position: absolute;
            right: 1px;
            bottom: -3px;
            font-family:uii;
            content:"\e442";
            font-size:20px;
        }
        .mui-content{
            background: white;
            overflow: hidden;
        }
        .thistips{
            padding:10px;
            background:#f0f0f0;

        }
        .colhalf{
            width: 50%;
            float: left;
        }
        .wrap{
            padding: 10px;
        }
        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2){
            .wrap .mui-btn {
                padding: 10px 0;
                font-size: 16px;
            }
        }
    </style>
<body>

<div class="thistips">
    <span style="color:#3bb8a3">绿色表示已加入部门,</span>
    <span style="color:#e34728">红色代表待审核状态</span>
</div>

<div class="mui-content">
    <div class="group-list group-create">
        <ul class="list-unstyled" >
            <li class="colhalf">
                <div class="wrap">
                    <button class="mui-btn mui-btn-outlined mui-btn-block group-active">
                        <span>道具组</span>
                    </button>
                </div>
            </li>
            <li class="colhalf">
                <div class="wrap">
                    <button class="mui-btn mui-btn-outlined mui-btn-block group-join">
                        <span>道具组</span>
                    </button>
                </div>
            </li>
            <li class="colhalf">
                <div class="wrap">
                    <button class="mui-btn mui-btn-outlined mui-btn-block notjoin">
                        <span>道具组</span>
                    </button>
                </div>
            </li>

        </ul>
    </div>
</div>

</body>
<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    $(".mui-btn").click(function(){
         var that=$(this);
         if($(this).hasClass("group-active")){
            mui.confirm("确定退出？",function(e){
                if(e.index==1){
                    that.removeClass("group-active");
                    that.addClass("notjoin")
                }
             });
            return "";
         }
         if($(this).hasClass("notjoin")){
             mui.confirm("确定加入？",function(e){
                 if(e.index==1) {
                     that.addClass("group-join");
                     that.removeClass("notjoin");
                 }
             });
             return "";
         }
     })

</script>
</html>




