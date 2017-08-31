<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>剧组通讯录查看</title>
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
    <a href="/mobile/permissions/contact/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}" class="mui-btn mui-btn-block mui-btn-success">修改</a>
</footer><!--/end-->


<div class="contact ui-content">
    <div id='list' class="mui-indexed-list">

        <div class="mui-indexed-list-search mui-input-row mui-search">
            <input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="搜索">
        </div><!--/end-->

        @include('mobile.template.chars_lists')

        <div class="mui-indexed-list-alert"></div>

        <div class="mui-indexed-list-inner">
            <div class="mui-indexed-list-empty-alert">没有搜索到~</div>
            <ul class="mui-table-view">
                @foreach($groupedUsers as $groupKey => $users)
                    <li data-group="{{ $groupKey }}" class="mui-table-view-divider mui-indexed-list-group">{{ $groupKey }}</li>
                    @foreach($users as $user)
                        <li data-value="ALM" data-tags="{{ $user->FNAME }}" class="mui-table-view-cell mui-media mui-checkbox mui-indexed-list-item">
                            <a class="link-tel">
                                <span class="pic">
                                    <img class="mui-media-object mui-pull-left" src="{{ $user->FPICURL }}">
                                </span>
                                <div class="mui-media-body">
                                    <span class="tel-name">{{ $user->FNAME }}</span>
                                    <p class="mui-ellipsis">
                                        {{ $user->groupNamesInMovie($movieId)}}/{{ $user->positionInMovie($movieId)}}
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>

    </div><!--/end-->
</div><!--/end-->

<script src="/assets/mobile/js/ui.min.js"></script>
<script src="/assets/mobile/js/ui.indexedlist.js"></script>
<script>
mui.init();
mui.ready(function() {

  var list = document.getElementById('list');
  var footer = document.querySelector('footer.mui-bar');

  //calc hieght
  list.style.height = (document.body.offsetHeight - footer.offsetHeight) + 'px';

  //create
  window.indexedList = new mui.IndexedList(list);

});

function history_back(){
    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g

    if(isAndroid){
        window.nanzhu.dissmiss();
    }

    window.history.back();

    return true;
}

</script>
</body>
</html>
