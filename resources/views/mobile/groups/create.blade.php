<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加部门</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            background: #fff;
        }
    </style>
</head>
<body>
<div class="crew-list">
    <form class="mui-input-group" action="/mobile/groups" method="post" accept-charset="utf-8" id="createNewGroup">
        <input type="hidden" name="movie_id" value="{{$movie_id}}">
        <input type="hidden" name="user_id" value="{{$user_id}}">
        <div class="mui-input-row">
            <label for="IDa">部门</label>
            <input id="IDa" type="text" maxlength="8" name="group_name" value="" placeholder="部门名词最多8个字">
        </div>
        <div class="btn-wrap">
            <button type="button" class="mui-btn mui-btn-block mui-btn-success" onclick="createNewGroup()">保存</button>
        </div>
    </form>
</div>
<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>

    function history_back() {
        window.history.back();
        return true;
    }

    /**
     * 创建新的部门
     */
    function createNewGroup() {
        var form = $("#createNewGroup");

        var url = form.prop('action');

        $.post(url, form.serialize(), function (responseData) {
            if (!responseData.success) {
                mui.alert(responseData.msg);
            } else {
                window.location.href = responseData.data.redirect_url;
            }
        });
    }
</script>
</body>
</html>