<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>拍摄进度查看</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html, body {
    height: 100%;
    overflow: hidden;
}
</style>
</head>
<body>
<footer class="mui-bar mui-bar-footer bar-footer2">
    <span class="bin-checkbox gr">
        <label class="mui-checkbox"><input class="checkall" id="checkAll" type="checkbox"></label>全选/全不选
    </span>
    <button id="done" type="button" class="mui-btn mui-btn-success mui-pull-right">保存</button>
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
            <form id="addProgressPowerForm" action="/mobile/permissions/progress/" method="post">
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
                                class="active mui-table-view-cell mui-media mui-checkbox mui-indexed-list-item">
                                <input class="active-op" name="subBox" type="checkbox"
                                       user-id="{{ $user->FID }}"
                                       @if($user->hadAssignedPowerInMovie($movieId,\App\Models\ProgressPower::class) )
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

    //done event
    done.addEventListener('tap', function () {
        var checkboxArray = [].slice.call(list.querySelectorAll('input[type="checkbox"]'));
        var checkedValues = [];
        checkboxArray.forEach(function (box) {
            if (box.checked) {
                checkedValues.push(box.parentNode.innerText);
            }
        });
    }, false);

    //checkAll
    document.getElementById("checkAll").addEventListener('tap', function () {
        var all = document.getElementById("checkAll");
        var sub = document.getElementsByName("subBox"), l = sub.length;
        all.onclick = function () {
            for (var i = l; i--;) {
                sub[i].checked = all.checked;
            }
        };
        for (var i = l; i--;) {
            sub[i].onclick = function () {
                var k = 0;
                for (var i = l; i--;)sub[i].checked && k++;
                all.checked = l == k;
            };
        }
    });

});

$("#done").click(function () {
    $('input[name=subBox]').each(function () {

        var checked = $(this).prop('checked');
        var userId  = $(this).attr('user-id');

        $(this).next().val(
            JSON.stringify({
                checked: checked,
                userId :userId
            })
        );
    });

    $('#addProgressPowerForm').submit();
});

function history_back(){
	window.history.back();
	return true;
}

</script>
</body>
</html>
