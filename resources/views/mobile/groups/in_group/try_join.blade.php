
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>申请加入</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html,body{
    background: #fff;
}
.mui-row {
    padding: 10px 10px 68px;
}
.wrap {
    padding: 10px;
}
.wrap .mui-btn {
    margin-bottom: 0;
    border-color: #ddd;
    padding: 12px 0;
    height: 52px;
    overflow: hidden;
}
.mui-radio, .mui-checkbox {
    position: static;
}
.mui-radio input[type='radio'], .mui-checkbox input[type='checkbox'] {
    width: 100%;
    height: 100%;
    top: auto;
    bottom: 0;
    right: 0;
}
.mui-radio input[type='radio']:before, .mui-checkbox input[type='checkbox']:before,
.mui-checkbox input[type='checkbox']:checked:before {
    position: absolute;
    right: 3px;
    bottom: 3px;
}
.mui-radio input[type='radio']:before, .mui-checkbox input[type='checkbox']:before {
    color: #fff;
}
.wrap .active {
    border-color: #3bb8a3;
    color: #3bb8a3;
}
.mui-popup-title {
    display: none;
}
.mui-radio input[type='radio'][disabled], .mui-checkbox input[type='checkbox'][disabled], 
.mui-radio input[type='radio'][disabled]:before, .mui-checkbox input[type='checkbox'][disabled]:before {
    /*-webkit-text-fill-color: rgba(59, 184, 163, 1);*/
    opacity: 1;
}

.join_only .mui-radio input[type='radio']:before, .join_only .mui-checkbox input[type='checkbox']:before {
    color: #fff;
    /*content: '\e409';*/
}
.join_onlyicon {
    color: #ccc;
    font-size: 10px;
    display: inline-block;
    position: absolute;
    right: -24px;
    top: -5px;
    z-index: 1;
    overflow: hidden;
    transform:rotate(45deg);
    -ms-transform:rotate(45deg); 	/* IE 9 */
    -moz-transform:rotate(45deg); 	/* Firefox */
    -webkit-transform:rotate(45deg); /* Safari 和 Chrome */
    -o-transform:rotate(45deg); 	/* Opera */
    background: #ddd;
    color: #fff;
    padding: 10px 15px 0 15px;
}

@media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2){
    .wrap .mui-btn {
        padding: 10px 0;
        font-size: 16px;
    }
}
</style>
</head>
<body>
<div class="group-list group-create">
    <form action="/mobile/users/{{$userId}}/join_other_group" method="post" accept-charset="utf-8" id="joinGroup">
        <input type="hidden" name="movie_id" value="{{$movieId}}">

        <input type="hidden" name="user_id" value="{{$userId}}">
        <ul class="list-unstyled mui-row">
            @foreach($groups as $group)
                <li class="mui-col-xs-6">
                    <div class="wrap">
                        <div class="mui-btn mui-btn-outlined mui-btn-block
                             @if($user->isInGroup($group->FID))
                                active
                             @elseif($user->hadTryJoinedGroup($group->FID) && $user->isJoinGroupAuditting($group->FID))
                                join_only
                             @endif">
                            <span class="gfname">{{$group->FNAME}}</span>

                            @if( $user->hadTryJoinedGroup($group->FID) &&
                                 $user->isJoinGroupAuditting($group->FID) &&
                                !$user->isInGroup($group->FID))
                                <span class="join_onlyicon">待审核</span>
                            @endif

                            <div class="mui-checkbox">
                                <input type="checkbox"
                                       value="{{$group->FID}}"
                                       @if($user->isInGroup($group->FID))
                                            checked
                                            disabled
                                       @elseif($user->hadTryJoinedGroup($group->FID) && $user->isJoinGroupAuditting($group->FID))
                                               disabled
                                       @else
                                           name="join_groups[]"
                                       @endif
                                        >
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul><!--/end-->

        @if(!$isAllGroupsJoined)
        <div class="fixed">
            <button type="button" class="btn-fixed mui-btn mui-btn-block mui-btn-success" onclick="joinGroup()">申请加入</button>
        </div><!--/end-->
            @endif
    </form>
</div>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
$(".mui-btn").click(function(){
    $(this).toggleClass("active");
});

function joinGroup(){

    var form = $("#joinGroup");
    var selectedGroup= $('input[type=checkbox][name="join_groups[]"]:checked').length;
    if(selectedGroup == 0){
        mui.alert('加入的部门不能为空');
        return false;
    }
    $.ajax({
        url: form.prop('action'),
        data: form.serialize(),
        method : 'POST',
        dataType:'json',
        success : function(response){
            var msg= '<p class="g3 f18">申请成功</p><div class="g6 f16">等待部门长审核</div>';
            mui.alert(msg, '提示', function() {
                if(response.success){
                    setTimeout('window.history.back()', 1000);
                }
            });
        }
    })
}

function history_back(){
    window.history.back();
    return true;
}
</script>
</body>
</html>