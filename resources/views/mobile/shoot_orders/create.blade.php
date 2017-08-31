<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>录制专业版</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html,body {
    background-color: #fff;
}
.box-a {
    padding: 0 15px;
}
.mui-input-group {
    border-radius: 5px;
}
.mui-input-group .ipt-sdata {
    text-align: right;
    display: inline-block;
}
.ri {
    float: right;
    width: 78%;
    text-align: right;
}
.ri span {
    display: inline-block;
    font-size: 14px;
    color: #3bb8a3;
    line-height: 1.2;
    vertical-align: middle;
    margin-top: 3px;
}
.ri input {
    width: 100%;
    padding: 10px 0px;
    text-align: right;
    font-size: 16px;
}
.crew-list .mui-input-group .mui-input-row:after {
    left: 0;
    right: 0;
}
.mui-input-row, .mui-input-row:last-child:after {
    height: 0;
}
.mui-input-row label {
    width: 22%;
    padding-right: 0;
    padding-left: 0;
    font-size: 16px;
}
.mui-input-row label.red {
    color: #f15252;
    font-size: 15px;
    line-height: 1.6;
    padding: 5px 0 0;
    text-align: justify;
}
.mui-input-row .last {
    width: 100%;
}
.btn-wrap .mui-btn-success {
    background-color: #50c747;
    border-color: #50c747;
}
/* .btn-wrap .btn-alipay {
    background-color: #29a1f7;
    border-color: #29a1f7;
}
.btn-wrap .btn-alipay:enabled:active {
    background-color: #3cabfb;
    border-color: #3cabfb;
} */

@media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2){
    .mui-input-row label, .ri input, .ri span {
        font-size: 14px;
    }
    .crew-list .mui-input-group .mui-input-row {
        padding: 5px 0;
    }
}
</style>
</head>
<body>
<div class="data">

    <div class="box-a">
        <div class="box-bd">

            <div class="crew-list">
                <form id="form" class="mui-input-group" method="POST" action="/api/shoot_orders">
					<input type="hidden" id="wx_openid"  name="wx_openid" value="{{$wx_openid}}"/>
                    <div class="mui-input-row">
                        <label>拍摄时间</label>
                        <div class="ri">
						<input id="myDatetime" name="start_date" class="ipt-sdata" type="datetime-local" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S',time()) ?>">
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label>拍摄地点</label>
                        <div class="ri">
							<input type="hidden" name="address" value="北京市朝阳区西坝河东里2号茂华UHN国际村9号楼5单元1002室">
                            <span id="openLocation">北京市朝阳区西坝河东里2号茂华UHN国际村9号楼5单元1002室</span>
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label>手机号码</label>
                        <div class="ri">
                            <input name="phone" type="text" placeholder="请输入手机号">
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label>联系人</label>
                        <div class="ri">
                            <input type="text" name="contact" placeholder="请输入联系人">
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label>备注</label>
                        <div class="ri">
                            <input name="note" type="text" placeholder="您还有无其它拍摄时间范围(必填)">
                        </div>
                    </div>
                    <div class="mui-input-row last-row">
                        <label class="last red">注意事项：拍摄时间大约30分钟。请带妆前往，如需道具请自备。</label>
                    </div>
                </form>
            </div><!--/end-->

        </div>
    </div><!--/end-->

    <div class="btn-wrap">
        <!-- <a href="#" class="mui-btn mui-btn-block mui-btn-success btn-alipay">支付宝支付</a> -->
        <a href="#" onclick="order_submit();" class="mui-btn mui-btn-block mui-btn-success">微信支付</a>
        <p class="tc g9">注：暂无发票</p>
    </div><!--/end-->

</div><!--/end-->

<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/assets/javascripts/jquery.min.js"></script> 
<script src="/assets/javascripts/jquery.form.js"></script> 
<script src="/assets/javascripts/pingxx/pingpp.js"></script>
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
                    'openLocation'
                ]
            });
        }
    });
    wx.ready(function () {
        document.querySelector('#openLocation').onclick = function () {
            wx.openLocation({
                latitude: 39.9677632654, // 纬度，浮点数，范围为90 ~ -90
                longitude: 116.4490793180, // 经度，浮点数，范围为180 ~ -180。
                name: 'UHN国际村', // 位置名
                address: '北京市朝阳区西坝河东里2号茂华UHN国际村9号楼5单元1002室', // 地址详情说明
                scale: 14, // 地图缩放级别,整形值,范围从1~28。默认为最大
                infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
            });
        };
    });
</script>
<script>
	function order_submit(){
		$('#form').ajaxSubmit(function(data) { 
			//alert(data.shoot_order.id); 
			wap_pay("wx_pub",data.shoot_order.id);
		 }); 
	}
    function wap_pay(channel,order_no) {
        var xhr = new XMLHttpRequest();
		var wx_openid = document.getElementById("wx_openid").value;
        xhr.open("POST", "http://www.nanzhuxinyu.com/api/pays/charge", true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.send(JSON.stringify({
            channel: channel,
            order_no : order_no,
            wx_openid: wx_openid 
        }));
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
				var data = xhr.responseText;
				var jsonResponse = JSON.parse(data);
                pingpp.createPayment(jsonResponse.charge, function(result, err) {
					if(result=="success"){
						$.post( "/api/shoot_orders/"+order_no, { _method: "PATCH", is_payed: 1 })
							.done(function( data ) {
							window.location.href = "/mobile/shoot_orders/success";
						});
					}
					if(result=="error"){
						alert("支付失败");
					}
                    console.log(err.msg);
                    console.log(err.extra);
                });
            }
        }
    }
</script>
</body>
</html>
