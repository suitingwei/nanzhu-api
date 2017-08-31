<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0" />
<link rel="shortcut icon" href="/assets/manage/assets/favicon.ico" />
<title>南竹通告单+</title>
<style>
.webim-logo img {
    max-width: 100%;
    height: auto;
}
.webim-sign h2 {
    font-weight: 100;
}
.webim-checkbox, .webim-sign p, #chatrooms, #strangers {
    display: none;
}
.webim-sign .bg-color {
    border-color: #54b99f;
    background-color: #54b99f;
}
.webim-dialog .webim-button, .webim-send-wrapper .webim-button {
    margin: 10px auto;
}
.webim-dialog .webim-button {
    font-size: 16px;
}
.webim-send-wrapper .webim-button {
    font-size: 12px;
}
.webim-msg-container .left .webim-msg-value > pre {
	color: #000;
}
</style>
<!--sdk-->
<script src='/chat/sdk/dist/strophe.js'></script>
<script src='/chat/sdk/dist/websdk-1.1.2.js'></script>

<!--config-->
<script src="/chat/demo/javascript/dist/webim.config.js"></script>

<!--[if lte IE 9]>
<script src="/chat/demo/javascript/dist/low/html5shiv.js"></script>
<script src="/chat/demo/javascript/dist/low/respond.min.js"></script>
<script src="/chat/demo/javascript/dist/swfupload/swfupload.min.js"></script>
<![endif]-->
<script src="/chat/demo/javascript/dist/low/modernizr.custom.min.js"></script>
</head>
<body>
<style>
.webim { top: 5%; }
.error, .webim-sign {
    display: none;
}
.webim-logo {
    width: 100%;
    text-align: center;
}
.webim-logo .webim-logo-img {
    width: 150px;
    margin: 50px auto 90px;
}
.webim-chat {
    max-width: 900px;
    min-width: 900px;
    max-height: 600px;
}
</style>

<section id='main' class='w100'>
    <article id='demo'></article>
    <article id='components'></article>
</section><!--end-->

<!--demo javascript-->
<script src="/chat/demo/javascript/dist/demo.js"></script>
<script src="/chat/demo/javascript/dist/low/jquery-1.11.3.min.js"></script>
<script src="/chat/demo/javascript/dist/low/jquery.cookie.js"></script>
<script src="/chat/demo/javascript/dist/low/jquery.placeholder.min.js"></script>
<script>
jQuery(document).ready(function ($) {

    //兼容ie placeholder
    $('input, textarea').placeholder();

    /*防刷新：检测是否存在cookie*/
    if($.cookie("captcha")){
        var count = $.cookie("captcha");
        var btn = $('.mob-code input');
        btn.val("重新获取(" + count + ")").attr('disabled',true).css('cursor','not-allowed');
        var resend = setInterval(function(){
            count--;
            if (count > 0){
                btn.val("重新获取(" + count + ")").attr('disabled',true).css('cursor','not-allowed');
                $.cookie("captcha", count, {path: '/', expires: (1/86400)*count});
            }else {
                clearInterval(resend);
                btn.val("获取验证码").removeClass('disabled').removeAttr('disabled style');
            }
        }, 1000);
    }

    $('input.user-name').val('{{ $username }}');
    $('input.user-nick-name').val('{{ $nickname }}');
    $('input.user-cover-url').val('{{ $coverUrl }}');

    $('.form-code').children('input').val('{{ $pwd }}');
    $('.login-btn').click();
});
</script>
</body>
</html>