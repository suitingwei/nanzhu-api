<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>组员反馈</title>
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

<form  class="mui-input-group"  method="POST" action="/mobile/permissions/groupuser-feedback/toggle" id="toggleGroupUserFeedbackForm">
    {{ csrf_field() }}
    <div class="mui-input-row" id="openornot">
        <label for="job_is_open" class="last" style="width:70%">是否允许组员反馈</label>
        <input type="hidden" name="movie_id" value="{{ $movieId }}">
        <input type="hidden" name="user_id" value="{{ request()->input('user_id')}}">
        <input style="margin: 3px 11px 0px 0px;" class="switch" type="checkbox" id="job_is_open" onclick="toogleMovieGroupUserFeedback()"
               @if($movie->is_groupuser_feedback_open)
               checked
                @endif
        >
    </div>
</form>
@if(!$movie->is_groupuser_feedback_open)
    <div style="padding:5px 15px;">
        全组每一个人直接向核心管理层提出自己的不良感受和改善意见.
    </div>
@else
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
                <form id="addReceivePowerForm" action="/mobile/permissions/groupuser-feedback/" method="post">
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
                                           @if($user->hadAssignedPowerInMovie($movieId,\App\Models\GroupUserFeedBackPower::class) )
                                           checked
                                            @endif>
                                    <input type="hidden" name="groupuser_feedback_powers[]">
                                    <a class="active-info">
                                    <span class="pic">
                                        <img class="mui-media-object mui-pull-left" src="{{ $user->cover_url }}">
                                    </span>
                                        <div class="mui-media-body">
                                            {{ $user->FNAME }}
                                            <p class="mui-ellipsis">
                                                {{ $user->groupNamesInMovie($movieId)}}
                                                {{ $user->positionInMovie($movieId)}}
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
@endif
<script src="/assets/mobile/js/ui.min.js"></script>
<script src="/assets/mobile/js/ui.indexedlist.js"></script>
<script src="/assets/javascripts/jquery.min.js"></script>
<script>
    mui.init();
    mui.ready(function () {

        @if($movie->is_groupuser_feedback_open)
        var list = document.getElementById('list');
        var footer = document.querySelector('footer.mui-bar');
        var openornot = document.getElementById('openornot')
        //calc hieght
        list.style.height = (document.body.offsetHeight - footer.offsetHeight -openornot.offsetHeight) + 'px';

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

        @endif
    });

    $("#done").click(function () {
        $('input[name=subBox]').each(function () {

            var checked = $(this).prop('checked');
            var userId = $(this).attr('user-id');

            $(this).next().val(
                JSON.stringify({
                    checked: checked,
                    userId: userId
                })
            );
        });

        $('#addReceivePowerForm').submit();
    });

    function history_back() {
        window.history.back();
        return true;

    }

    function toogleMovieGroupUserFeedback(){
        var form =$("#toggleGroupUserFeedbackForm");

        var action = form.attr('action');
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                mui.toast(response.msg);
                setTimeout(function(){
                    window.location.reload();
                },1000);
            }
        })
    }
</script>
</body>
</html>
