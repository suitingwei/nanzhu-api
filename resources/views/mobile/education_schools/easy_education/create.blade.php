<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我要报名</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            background-color: #fff;
        }

        input[type='text'] {
            padding: 10px 55px 10px 15px;
        }

        input.join-psw {
            padding-right: 15px;
        }

        .link-add {
            display: inline-block;
            z-index: 100;
            height: 100%;
            padding-left: 20px;
            padding-right: 15px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .link-add i {
            position: relative;
            top: 18px;
        }

        .mui-input-group {
            background-color: transparent;
        }

        .mui-input-row, .mui-input-row:last-child {
            background-color: #fff;
        }

        .selectboxit .selectboxit-arrow-container .selectboxit-arrow {
            display: none;
        }

        .selectboxit-container {
            top: -1px;
            width: 66%;
        }

        .selectboxit-container .selectboxit {
            top: 6px;
        }
    </style>
</head>
<body>
<div class="crew-list crew-create">

    <form id="movie_form" class="mui-input-group" action="/mobile/education-schools/easy-education/join" method="POST">
        {!! csrf_field() !!}
        <div class="mui-input-row">
            <label for="user-name-input">姓名</label>
            <input id="user-name-input" type="text" required name="name" maxlength="30">
        </div>
        <div class="mui-input-row">
            <label for="user-gender-select">性别</label>
            <select name="gender" id="user-gender-select">
                <option value=""></option>
                <option value="男">男</option>
                <option value="女">女</option>
            </select>
        </div>
        <div class="mui-input-row">
            <label for="user-phone-input">电话</label>
            <input id="user-phone-input" type="tel" required name="phone" maxlength="30">
        </div>
        <div class="mui-input-row">
            <label for="user-course-select">课程选择</label>
            <select name="course" id="user-course-select">
                <option value=""></option>
                <option value="全能艺人">全能艺人</option>
                <option value="新媒体娱乐营销">新媒体娱乐营销</option>
                <option value="明星经纪人">明星经纪人</option>
                <option value="制片人">制片人</option>
                <option value="节目编导">节目编导</option>
            </select>
        </div>
        <div class="btn-wrap">
            <button type="button" id="form_btn" class="mui-btn mui-btn-block mui-btn-success">确定</button>
        </div>
    </form>

</div>

<script src="/assets/mobile/js/jquery.dropdown.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    mui.init();
    mui.ready(function () {
        var form_validation = function form_validation() {
            if ($("#user-name-input").val() === "") {
                mui.alert("请填写姓名");
                return false;
            }

            if ($("#user-gender-select").val() === "") {
                mui.alert("请选择性别");
                return false;
            }

            if ($("#user-course-select").val() === "") {
                mui.alert("请选择课程");
                return false;
            }

            const phoneReg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if (!phoneReg.test($("#user-phone-input").val())) {
                mui.alert('请填写正确手机号');
                return false;
            }
            $.post('/mobile/education-schools/easy-education/join', $('#movie_form').serialize(), function (response) {
                if (response.success) {
                    mui.alert('报名成功,请保持手机畅通，马上会有专员联系您!');
                } else {
                    mui.alert(response.msg);
                }
            })
        };
        $("#form_btn").on("click", form_validation);
    });

</script>
</body>
</html>
