<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>部门列表</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html, body {
    background: #fff;
}

.list-txt {
    color: #555;
}

form .mui-btn-link {
    padding: 0;
    margin: -2px 5px 0;
}
</style>
</head>
<body>
<div class="list">

    <div class="mod-list pb80">
        @foreach($groups as $group)
            <div class="list-row">
                <label>{{$group->FNAME}}{{$group->members()->count()}}人</label>
                <div class="list-txt">{{$group->leader()}}</div>
                <div class="list-option">
                    <a href="/mobile/groups/{{$group->FID}}/edit?user_id={{ request('user_id') }}"/><i class="mui-icon mui-icon-compose"></i></a>
                    @if(!$group->isEssential())
                        <form name="delform" action="/mobile/groups/{{$group->FID}}" method="post" accept-charset="utf-8" class="dib" id="deleteGroupForm{{$group->FID}}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button id="btnDel" class="mui-btn-link" type="button" onclick="deleteGroup('{{$group->FID}}','{{$group->FNAME}}')">
                                <i class="mui-icon mui-icon-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div><!--/end-->

    <div class="fixed">
        <a href="/mobile/groups/create?movie_id={{$movie_id}}&user_id={{ request()->input('user_id')}}" class="btn-fixed mui-btn mui-btn mui-btn-block mui-btn-success">创建部门</a>
    </div><!--/end-->

</div><!--/end-->

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
function deleteGroup(formId, groupName) {
    var btnArray = ['否', '是'];
    mui.confirm('是否要删除 ' + groupName + "部门 ？", '提示', btnArray, function (e) {
        if (e.index == 1) {
            var form = $("#deleteGroupForm" + formId);
            $.ajax({
                url: form.prop('action'),
                data: form.serialize(),
                method : 'DELETE',
                success : function(response){
                    mui.alert(response.msg);
                    if(response.success){
                        window.location.reload();
                    }
                }
            })
        }
    })
}

/**
 * 如果是部门列表index界面跳转到工作台
 */
function history_back() {
        window.nanzhu.backHome();
}
</script>
</body>
</html>