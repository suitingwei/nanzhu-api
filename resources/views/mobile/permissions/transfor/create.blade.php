<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>最高权限转移</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html, body {
    height: 100%;
    overflow: hidden;
}
    .btn-full-width{
        min-width:100%;
    }
.mui-radio input[type='radio']:checked:before, .mui-checkbox input[type='checkbox']:checked:before
{
    content: '\e442';
}
</style>
</head>
<body>
<footer class="mui-bar mui-bar-footer bar-footer2">
    <button id="done" type="button" class="mui-btn mui-btn-success btn-full-width">完成</button>
</footer><!--/end-->


<div class="contact ui-content">
    <div id='list' class="mui-indexed-list">

        <div class="mui-indexed-list-search mui-input-row mui-search">
            <input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="搜索">
        </div>

        @include('mobile.template.chars_lists')

        <div class="mui-indexed-list-alert"></div>

        <div class="mui-indexed-list-inner">
            <div class="mui-indexed-list-empty-alert">没有搜索到~</div>
            <form id="addTransforPowerForm" action="/mobile/permissions/transfor/" method="post">
                <input type="hidden" name="movie_id" value="{{ request('movie_id') }}">
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                <input type="hidden" name="androidVer" value="{{ request('androidVer') }}">
                {{ csrf_field() }}
                <ul class="mui-table-view">
                    @foreach($groupedUsers as $groupKey => $users)
                        <li data-group="{{ $groupKey }}"
                            class="mui-table-view-divider mui-indexed-list-group">{{ $groupKey }}</li>
                        @foreach($users as $user)
                            <li data-value="ALM" data-tags="{{ $user->FNAME }}"
                                class="active mui-table-view-cell mui-media mui-radio mui-indexed-list-item">
                                <input class="active-op" name="subBox" type="radio" value="{{ $user->FID }}"
                                       @if($user->isAdminOfMovie($movieId) )
                                       checked
                                        @endif>
                                <input type="hidden" name="contactPower[]">
                                <a class="active-info">
                                    <span class="pic">
                                        <img class="mui-media-object mui-pull-left" src="{{ $user->cover_url}}">
                                    </span>
                                    <div class="mui-media-body">
                                        {{ $user->FNAME }}
                                        <p class="mui-ellipsis">
                                            {{ $user->groupNamesInMovie($movieId)}}/{{ $user->positionInMovie($movieId)}}
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </form>
        </div>

    </div><!--/end-->
</div><!--/end-->

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script src="/assets/mobile/js/ui.indexedlist.js"></script>
<script>
mui.init();
mui.ready(function () {

    var list = document.getElementById('list');
    var footer = document.querySelector('footer.mui-bar');

    //calc hieght
    list.style.height = (document.body.offsetHeight - footer.offsetHeight) + 'px';

    //create
    window.indexedList = new mui.IndexedList(list);
});

$("#done").click(function () {
    var checkboxes = $('input[name=subBox]:checked');
    if (checkboxes.length != 1) {
        mui.alert('最高权限必须设置一个人');
        return false;
    }

    var form = $('#addTransforPowerForm');

    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g

    $.ajax({
        url: form.prop('action'),
        data: form.serialize(),
        method : 'POST',
        dataType:'json',
        success : function(response){
            mui.alert(response.msg);
            if(response.success){
                if(isAndroid){
                    window.nanzhu.backHome();
                }else{
                    if(typeof popToMenu == 'function'){
                        popToMenu();
                    }else{
                        window.location.href= response.data.redirect_url;
                    }
                }
            }
        }
    })
});

function history_back(){
	window.history.back();
	return true;
}
</script>
</body>
</html>
