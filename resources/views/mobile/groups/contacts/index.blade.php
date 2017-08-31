<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>剧组通讯录</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html, body {
    height: 100%;
    overflow: hidden;
}
.link-tel .tel-name {
    color: #333;
}
.link-tel .tel-name {
    color: #3bb8a3;
}
.phoneno {
    margin-top: 10px;
    margin-bottom: 0;
}
.phoneno a {
    color: #333;
    font-size: 17px;
}
</style>
</head>
<body>
<div class="contact ui-content">
    <div id='list' class="mui-indexed-list">

        <!-- 顶部搜索栏 -->
        <div class="mui-indexed-list-search mui-input-row mui-search">
            <input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="搜索">
        </div>

        <!-- 右侧边栏 A-Z -->
        @include('mobile.template.chars_lists')

        <div class="mui-indexed-list-alert"></div>

        <div class="mui-indexed-list-inner">
            <div class="mui-indexed-list-empty-alert">没有搜索到~</div>
            <ul class="mui-table-view">
                @foreach($groupedUsers as $groupKey => $users)
                    <li data-group="{{ $groupKey }}" class="mui-table-view-divider mui-indexed-list-group">{{ $groupKey }}</li>

                    @foreach($users as $user)
                        <li data-value="ALM" data-tags="{{ $user->FNAME }}"
                            class="mui-table-view-cell mui-media mui-checkbox mui-indexed-list-item">

                            <a class="link-tel"
                               @if($user->isSharePhonesInMovieOpened($movieId))
                               spare-phones="{{json_encode($user->sharePhonesInMovie($movieId)->all() ) }}"
                               onclick="showTelAlert(this)"
                                    @endif>
                                <span class="pic">
                                    <img class="mui-media-object mui-pull-left" src="{{ $user->cover_url}}">
                                </span>
                                <div class="mui-media-body">
                                    @if( $user->sharePhonesInMovie($movieId)->count() > 0)
                                        <span class="tel-name">{{ $user->FNAME }}</span>
                                    @else
                                        <span>{{ $user->FNAME }}</span>
                                    @endif
                                    <p class="mui-ellipsis">
                                        {{ $user->groupNamesInMovie($movieId,true)}}/{{ $user->positionInMovie($movieId)}}
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

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script src="/assets/mobile/js/ui.indexedlist.js"></script>
<script>
mui.init();
mui.ready(function() {

  var list = document.getElementById('list');

  //calc hieght
  list.style.height = (document.body.offsetHeight) + 'px';

  //create
  window.indexedList = new mui.IndexedList(list);

});

function showTelAlert(obj) {

    var sparePhones = JSON.parse($(obj).attr('spare-phones'));

    var toolTip = '';
    for (var i = 0; i < sparePhones.length; i++) {
        toolTip += '<p class="phoneno"><a href="tel:' + sparePhones[i].FPHONE + '">' + sparePhones[i].FPHONE + '</a></p>';
    }

    if (toolTip == '') {
        toolTip = '没有可用的公开电话';
    }

    mui.alert(toolTip, '手机号码', '取消');
}

/**
 * 剧组通讯录返回工作台
 */
function history_back(){
    window.nanzhu.backHome();
}


</script>
</body>
</html>
