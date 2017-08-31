<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>修改部门</title>
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

    <form class="mui-input-group" action="/mobile/groups/{{$group->FID}}" method="post" accept-charset="utf-8">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
        <div class="mui-input-row">
            <label for="IDa">部门</label>
            @if(! $group->canDelete() || (mb_strpos($group->FNAME,'导演') !== false) )
                <input id="IDa" type="text" maxlength="8" placeholder="统筹和制片不能修改名字哦" name="group_name" value="{{$group->FNAME}}" readonly>
            @else
                <input id="IDa" type="text" maxlength="8" placeholder="统筹和制片不能修改名字哦" name="group_name" value="{{$group->FNAME}}" >
            @endif
        </div>
        <div class="mui-input-row">
            <label for="group_leader">部门长</label>
            @if(count($users) > 0)
                <select name="group_leader" id="group_leader">
                    @foreach($users as $user)
                        <option value="{{$user->FID}}"
                        @if($group->FLEADERID == $user->FID) selected @endif>{{$user->FNAME}}</option>
                    @endforeach
                </select>
            @else
                <input type="text" disabled placeholder="没有部门成员" value="没有部门成员">
            @endif
        </div>
        <div class="btn-wrap">
            <button type="submit" class="mui-btn mui-btn-block mui-btn-success">保存</button>
        </div>
    </form><!--/end-->

</div>
<script>

    function history_back(){
        window.history.back();
        return true;
    }
</script>
</body>
</html>
