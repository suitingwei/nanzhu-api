<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>我在本组</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
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

.mui-input-row .mui-checkbox {
    width: 16%;
}

.mui-input-row .mui-checkbox ~ input, .mui-input-row .mui-checkbox ~ select, .mui-input-row .mui-checkbox ~ textarea {
    width: 84%;
}

.mui-input-row .mui-checkbox .active-op {
    top: 6px;
    left: 25px;
}

.input-info {
    width: 78%;
    display: inline-block;
    line-height: 1.1;
    padding: 11px 40px 11px 0;
}

.info2 {
    width: 70%;
}
.info2 .mui-navigate-right {
    color: #333;
}
.info2 .mui-navigate-right:after {
    right: 15px;
}


.tips {
    padding: 15px;
    text-align: justify;
}

.manage-groups {
    margin-top: 10px;
}
.manage-groups .mui-table-view-cell > a:not(.mui-btn) {
    margin: 18px 17px;
}
.manage-groups .mui-table-view:before {
    height: 0;
}
.manage-groups .mui-table-view-cell:after {
    right: 15px;
    background-color: #e8e8e8;
}
.manage-groups .mui-table-view:after {
    right: 15px;
    left: 15px;
    background-color: #e8e8e8;
}
.mui-navigate-right:after, .mui-push-right:after {
    right: 0;
    font-size: 20px;
}

@media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
    .mui-input-row .mui-checkbox {
        width: 18%;
    }

    .mui-input-row .mui-checkbox ~ input, .mui-input-row .mui-checkbox ~ select, .mui-input-row .mui-checkbox ~ textarea {
        width: 82%;
    }
}
</style>
</head>
<body>
<div class="crew-list crew-join">
    <form id="updateGroupInfoForm" class="mui-input-group" action="{{ url()->current() }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="movie_id" value="{{ request('movie_id') }}">
        <div class="mui-input-row">
            <label for="job_is_open" class="last">我使用的手机号</label>
            <input class="switch" type="checkbox" id="job_is_open" onclick="togglePhoneCheckboxes(this)" @if($firstGroupUser->isPhoneOpened()) checked @endif>
            <input type="hidden" name="job_is_open">
        </div>

        @foreach($firstGroupUser->sharePhonesInGroup() as $phone)
            <div class="mui-input-row">
                <input type="hidden" name="phoneJson[]">
                <label class="mui-checkbox">
                    <input class="active-op" name="phoneCheckbox" type="checkbox" phone-is-open="{{ $phone->is_open }}" spare-phone-id="{{ $phone->spare_phone_id }}" order="{{ $phone->order }}" phone-is-register="{{ $phone->is_register_phone }}">
                </label>
                <input type="number" maxlength="11"
                       @if($phone->phone_number != 0)
                       value="{{ $phone->phone_number }}"
                       @endif>
            </div>
        @endforeach

        <div class="tips g9 f16">
            打开上边的开关，也可添加您在本组中使用的临时号码，<i class="gr mui-icon mui-icon-checkbox-filled"></i>即视为被使用。关闭此开关，号码视为被隐藏。
        </div>
        <div class="mui-input-row">
            <label class="g9">我的部门</label>
           {{-- <div class="input-info info2"><a class="mui-navigate-right" href="/mobile/users/{{ request('user_id') }}/join_other_group?movie_id={{ request('movie_id') }}">{{ $user->groupNamesInMovie($movieId) }}、服装组、道具组</a></div>--}}
            <div class="input-info info2"><a class="mui-navigate-right" href="/mobile/users/{{ request('user_id') }}/all-groups?movie_id={{request('movie_id')}}&user_id={{ request('user_id') }}">{{ $user->groupNamesInMovie($movieId) }}</a></div>
        </div>
        <div class="mui-input-row">
            <label class="g9" for="IDe">我的姓名</label>
            <input id="IDe" type="text" placeholder="姓名或艺名" name="user_name" value="{{ $firstGroupUser->user->FNAME }}">
        </div>
        <div class="mui-input-row">
            <label class="g9" for="IDf">我的职位</label>
            <input id="IDf" maxlength="15" type="text" placeholder="请输入职位" name="position" value="{{ $firstGroupUser->FREMARK }}">
        </div>
        <div class="mui-input-row">
            <label class="g9" for="IDg">我的房号</label>
            <textarea id="IDg" rows="1" placeholder="选填" name="room" >{{ $firstGroupUser->room}}</textarea>
        </div>
    </form><!--/end-->

    <div class="manage-groups">
        <ul class="mui-table-view">
            <li class="mui-table-view-cell">
                <a onclick="exitMovie('{{ request('user_id') }}','{{ request('movie_id') }}')"  class="mui-navigate-right"
                style="color: #f15252;">
                   退出剧组
                </a>
            </li>
        </ul>
    </div>

    <div class="btn-wrap">
        <button type="button" class="mui-btn mui-btn-block mui-btn-success" onclick="submitForm()">保存</button>
    </div><!--/end-->

</div>

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
mui.init();
mui.ready(function () {
    $(document).ready(function () {
        /**
         * 初始化开关数据,因为三个电话号码的是否可以勾选可以在本地进行设置,
         * 所以必须使用js变量保存状态,只不过需要使用api传递的参数进行初始化
         */
        $("input[name=phoneCheckbox]").each(function () {
            initCheckboxAndInput(this);
        });
    });
});

/**
 * 设置注册手机号被勾选
 * 根据input:checkbox的phone-is-register是否为1判断
 */
function setDefaultPhoneChecked() {
    $('input[name=phoneCheckbox][phone-is-register="1"]').prop('checked', true);
}

/**
 * 切换电话号码选择框的激活状态
 */
function togglePhoneCheckboxes(rootSwitch) {

    var switchOn = $(rootSwitch).is(':checked');

    $("input[name=phoneCheckbox]").each(function () {
        switchOn ? enableCheckboxAndInput(this) : disableCheckboxAndInput(this);
    });

    //如果是讲总开关从关闭到打开,默认勾选注册手机号
    if (switchOn) {
        setDefaultPhoneChecked();
    }
}

/**
 * 禁止电话号码的勾选框和输入框
 * @param checkbox
 */
function disableCheckboxAndInput(checkbox) {
    $(checkbox).addClass('disabled').prop('disabled', true).prop('checked', false);
    $(checkbox).parent().next().prop('readonly', true).prop('placeholder', '不可编辑');
}

/**
 * 允许电话号码的勾选框和输入框
 */
function enableCheckboxAndInput(checkbox) {
    $(checkbox).removeClass('disabled').prop('disabled', false);

    var isRegisterPhone = $(checkbox).attr('phone-is-register') == 1;
    $(checkbox).parent().next().prop('readonly', isRegisterPhone).prop('placeholder', '请输入备用号码');
}

/**
 * 初始化电话号码的勾选框和输入框
 * 1.初始化勾选框的勾选状态,是否允许勾选
 */
function initCheckboxAndInput(checkbox) {
    //1.初始化该勾选框的勾选状态
    var isPhoneOpen = $(checkbox).attr('phone-is-open') == 1;         //是否公开手机号,影响勾选框是否被选中
    var isRegisterPhone = $(checkbox).attr('phone-is-register') == 1;  //是否注册手机,影响input是否readonly

    $(checkbox).prop('checked', isPhoneOpen).prop('readonly', isRegisterPhone);

    //2. 是否允许勾选,如果不公开电话,不允许任何勾选,编辑操作
    var isRootSwitchOpen = "{{ $firstGroupUser->isPhoneOpened() }}";

    isRootSwitchOpen ? enableCheckboxAndInput(checkbox) : disableCheckboxAndInput(checkbox);
}

/**
 * 验证我的职位
 */
function validatePosition() {

    var position = $("input[name=position]").val();
    var reg = new RegExp('^[\u4e00-\u9fa5a-zA-Z0-9]+$');

    if (position && !reg.exec(position)) {
        mui.alert('我的职位只能填写大小写字母,英文,中文');
        return false;
    }
    return position;
}
/**
 * 提交表单的时候处理各个电话是否选中的值,
 * 因为如果没有选中checkbox,提交表单就没有这个值,
 * 处理起来比较麻烦,保证所有地方都是完整的提交
 */
function submitForm() {
    var canSubmit = true;
    var position = validatePosition();
    var phoneJsonArr = [];

    if (position === false) {
        return false;
    }

    $('input[name="phoneJson[]"]').each(function () {
        var phoneCheckbox = $(this).next().find('input[name=phoneCheckbox]');
        var validatePhones = validateCheckboxAndInput(phoneCheckbox);
        if (!validatePhones) {
            canSubmit = false;
            //如果验证失败,出现错误信息,直接退出循环
            return false;
        }
        else {
            phoneJsonArr.push(validatePhones);
        }
    });

    if (canSubmit) {
        var form = $("#updateGroupInfoForm");
        $.post(
            form.action,
            {
                job_is_open: $("#job_is_open").is(':checked') ? 1 : 0,
                phoneJson: phoneJsonArr,
                position: position,
                user_name: $("input[name=user_name]").val(),
                room :$("textarea[name=room]") .val()
            },
            function (responseData) {
                if(responseData.success){
                    mui.toast(responseData.msg);
                    //window.location.href=responseData.redirect;
                    setTimeout('window.location.reload()',1000);
                }
            })
    }
}

/**
 * 验证提交的数据
 * @return 验证失败返回false; 验证成功返回json数据
 */
function validateCheckboxAndInput(checkbox) {

    var checked = checkbox.is(':checked');
    var order = checkbox.attr('order');
    var sparePhoneId = checkbox.attr('spare-phone-id');
    var phoneNumber = checkbox.parent().next().val();

    //打钩了,但是没有填电话号码
    if (checked && phoneNumber == '') {
        mui.alert('请填写已勾选开关的手机号');
        return false;
    }

    //电话号码是否数字
    if (checked && isNaN(phoneNumber)) {
        mui.alert('手机号必须是数字');
        return false;
    }

    return JSON.stringify(
        {
            is_open: checked,
            phone_number: phoneNumber,
            order: order,
            spare_phone_id: sparePhoneId,
        }
    );
}

/**
 * 退出剧组
 * @param  userId
 * @param  movieId
 */
function exitMovie(userId,movieId){
    var btnArray = ['否', '是'];
    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
    var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

    mui.confirm('是否退出本剧组？', '提示', btnArray, function (e) {
        if (e.index == 1) {
            var url = '/mobile/users/'+userId+'/exit_movie/'+movieId;
            $.post(url, function (responseData) {
                mui.alert(responseData.msg)
                if(responseData.success){
                    if(isAndroid){
                        window.nanzhu.backHome();
                    }else if(isIOS){
                        popToMenu();
                    }
                }
            })
        }
    })
}

function history_back(){
    window.nanzhu.backHome();
}
</script>
</body>
</html>