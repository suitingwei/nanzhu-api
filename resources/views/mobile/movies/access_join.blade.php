<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>申请进组</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        .mui-input-group {
            background-color: transparent;
        }

        .mui-input-row, .mui-input-row:last-child {
            background-color: #fff;
        }

        .mui-input-row .last {
            width: 100%;
        }

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .mui-input-row .last {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="crew-list crew-join">

    <form id="form" class="mui-input-group" action="/mobile/movies/post_join" method="post" accept-charset="utf-8">
        <input type="hidden" name="movie_id" value="{{$movie_id}}">
        <input type="hidden" name="user_id" value="{{$user_id}}">
        <div class="mui-input-row">
            <label for="IDa">进组密码</label>
            <input id="IDa" required class="join-psw" type="text" name="password" value="" maxlength="12"
                   placeholder="6-12位数字或字母，区分大小写">
        </div>
        <div class="mui-input-row">
            <label for="group_name">部门</label>
            <select id="group_name" required name="group_name">
                <option value="">请选择部门</option>
                @foreach ($groups as $group)
                    <option value="{{$group->FNAME}}">{{$group->FNAME}}</option>
                @endforeach
            </select>
        </div>
        <div class="mui-input-row mb10">
            <label for="IDc">职位</label>
            <input id="IDc" type="text" required name="job" value="" placeholder="请输入职位">
        </div>
        <div class="mui-input-row">
            <label for="is_public" class="last">同意将我的电话添加至剧组通讯录</label>
            <input class="switch" type="checkbox" id="is_public" name="is_public" value="10" checked>
        </div>
        <div class="mui-input-row last-row">
            <label for="is_use_phone" class="last">同意在本剧组中使用我的注册手机号</label>
            <input class="switch" type="checkbox" id="is_use_phone" name="is_use_phone" value="1" checked>
        </div>
        <div class="mui-input-row last-row">
            <label for="IDg" class="last">同意将我添加到剧组群聊天</label>
            <input class="switch" type="checkbox" checked name="is_join_chat_group" id="is_join_chat_group">
        </div>
        <div class="btn-wrap">
            <button type="button" id="form_btn" class="mui-btn mui-btn-block mui-btn-success">进组</button>
        </div>
    </form><!--/end-->

    <div class="tc">
        <a class="report" href="/mobile/reports/create?movie_id=">举报</a>
    </div><!--/end-->

</div>

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    mui.init();
    mui.ready(function () {
    });
    $(document).ready(function () {
        var form_validation = function form_validation() {
            var flag = true;
            if ($("input[name='password']").val() == "") {
                mui.alert("请填写进组密码");
                flag = false;
                return;
            }
            var reg = new RegExp('^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$');
            if (reg.exec($("input[name='password']").val()) == null) {
                mui.alert("进组密码必须为长度为6~12位的字母+数字组合");
                flag = false;
                return;
            }
            if ($("#group_name").val() == "") {
                mui.alert("请选择部门");
                flag = false;
                return;
            }

            var job = $("input[name=job]").val();
            if (job) {
                var reg = new RegExp('^[\u4e00-\u9fa5a-zA-Z0-9]+$');
                if (!reg.exec(job)) {
                    mui.alert('我的职位只能填写大小写字母,英文,中文');
                    flag = false;
                    return;
                }
            }

            if (flag) {
                if (!document.getElementById('is_use_phone').checked) {
                    document.getElementById('is_use_phone').value = 0;
                }
                if (!document.getElementById('is_public').checked) {
                    document.getElementById('is_public').value = 20;
                }
                var u = navigator.userAgent, app = navigator.appVersion;
                var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                if (isIOS) {
                    $('#form').submit();
                } else {
                    var form = $('#form');
                    $.ajax({
                        url: form.attr('action'),
                        data: form.serialize(),
                        method: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            mui.toast(response.msg);
                        }
                    })
                }
            }
        };

        $("#form_btn").on("click", form_validation);
    });
</script>
</body>
</html>
