<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>举报</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
.mui-input-group {
    background-color: transparent;
}
.mui-input-group .f-ipt {
    background-color: #fff;
    padding: 15px;
}
.mui-input-group .f-ipt input, .mui-input-group .f-ipt textarea {
    height: auto;
    line-height: 1;
    padding: 0;
}
.c-xb {
    color: #888;
    padding: 15px 15px 5px;
    font-size: 16px;
}
</style>
<body>
<div class="crew-list">
    <form id="reports_form" class="mui-input-group" action="/mobile/reports" method="post" accept-charset="utf-8">
        <div class="form-w">
            <input type="hidden" name="recruit_id" value="@if($recruit){{$recruit->id}}@endif">
            <div class="c-xb">举报内容</div>
            <div class="f-ipt">
                <input name="title" type="text" value="@if($recruit){{$recruit->title}}@endif">
            </div>
            <div class="c-xb">举报理由</div>
            <div class="f-ipt">
                <textarea name="content" rows="7" placeholder="请输入您的举报理由"></textarea>
            </div>
            <div class="c-xb">联系方式</div>
            <div class="f-ipt">
                <input type="text" name="contact" value="" placeholder="请输入您的手机号码/邮箱">
            </div>
            <div class="btn-wrap">
                <button id="form_btn" class="mui-btn mui-btn mui-btn-block mui-btn-success" type="button">提交</button>
            </div>
        </div>
    </form>
</div><!-- container end -->

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
mui.init();
mui.ready(function() {
    $(document).ready(function(){
        var form_validation = function form_validation(){
            var flag = true;
            if ($("input[name='title']").val() =="") {
                mui.alert("请填写举报内容");
                flag = false;
                return;
            }
            if ($("textarea[name='content']").val() =="") {
                mui.alert("请填写举报理由");
                flag = false;
                return;
            }
            if ($("input[name='contact']").val() =="") {
                mui.alert("请填写联系方式");
                flag = false;
                return;
            }
            if (flag) {
                $("#reports_form").submit();
            }
        };
        $("#form_btn").on("click",form_validation);
    });
});

function history_back(){
    window.history.back();
}
</script>
</body>
</html>