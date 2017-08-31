<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>最高权限移交</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html,body {
    background-color: #fff;
}
input[type='text'] {
    padding: 10px 55px 10px 15px;
}
.mui-input-row .link-edit {
    display: inline-block;
    z-index: 100;
    width: auto;
    position: absolute;
    padding-left: 30px;
    padding-right: 30px;
    right: 15px;
    top: 12px;
    height: 37px;
    font-size: 16px;
    color: #fff;
}
.mui-input-row label {
    width: 22%;
}
.mui-input-row label ~ input, .mui-input-row label ~ select, .mui-input-row label ~ textarea {
    width: 78%;
}
.mui-input-group {
    background-color: transparent;
}
.mui-input-row, .mui-input-row:last-child {
    background-color: #fff;
}
.input-info {
    width: 78%;
    display: inline-block;
    line-height: 1.1;
    padding: 11px 120px 11px 0;
}
</style>
</head>
<body>
<div class="crew-list crew-create">
    <form class="mui-input-group">
        <div class="mui-input-row">
            <label class="g9">当前</label>
            <span class="input-info">{{ $admin->FNAME }}</span>
            <a class="link-edit mui-btn mui-btn-success" href="{{ url()->current() }}/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}">更改</a>
        </div>
    </form>
</div>

<script>
function history_back(){
    window.history.back();
    return true;
}
</script>
</body>
</html>