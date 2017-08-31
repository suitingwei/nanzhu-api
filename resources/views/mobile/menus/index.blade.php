<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>剧组菜单</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
.if {
    font-size: 30px;
    top: -2px;
}
.crew-no {
    padding: 80px 0;
    line-height: 1.6;
}
.crew-no i {
    color: #bbbec4;
    font-size: 50px;
    margin-bottom: 10px;
}
.refurbish {
    position: absolute;
    left: 15px;
    top: 4px;
    color: #999;
}
.refurbish:hover {
    -webkit-animation: rotate .8s infinite linear;
    -moz-animation: rotate .8s infinite linear;
    animation: rotate .8s infinite linear;
}
.refurbish i {
    font-size: 22px;
}
@-webkit-keyframes rotate {
0%{
  -webkit-transform:rotate(0deg);
 }
 100%{
   -webkit-transform:rotate(360deg);
 }
}
@-moz-keyframes rotate {
0%{
  -moz-transform:rotate(0deg);
 }
 100%{
   -moz-transform:rotate(360deg);
 }
}
@keyframes rotate {
0%{
  transform:rotate(0deg);
 }
 100%{
   transform:rotate(360deg);
 }
}
</style>
</head>
<body>

@if(count($movies) > 0 )
<?php $progress = ""; ?>
<div class="crew bgwh">
    <div class="crew-name tc">
        <div class="f20 rel">
            <span class="refurbish" onclick="location.reload()">
                <i class="mui-icon if i-refurbish"></i>
            </span>
            <form id="movie_id_form" action="/mobile/menus" method="get" accept-charset="utf-8">
                <input type="hidden" name="user_id" value="{{$user_id}}">
                <select class="iselect" name="movie_id" id="choose_movie_id" >
                    @foreach($movies as $m)
					<?php if($movie_id == $m['movie_id']){  $progress = $m['progress'] ; }?>
                    <option data-icon="mui-icon if i-{{$m['movie_type']}}" value="{{$m['movie_id']}}" @if($m['movie_id'] == $movie_id) selected @endif>{{($m['movie_name'])}}</option>
                    @endforeach
                </select>
            </form>
        </div>
        @if($progress != 0)
        <div class="mt10 f17 g9">
            拍摄进度：第{{$progress}}天
        </div>
        @endif
    </div><!--/end-->
    <ul class="crew-menu mui-table-view mui-grid-view mui-grid-9">
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
        		<a href="/mobile/notices?type=10&movie_id={{$movie_id}}&user_id={{$user_id}}&title=每日通告单">
        	        <i class="mui-icon if i-filecopy">@if($notice_num > 0)<span class="mui-badge">{{$notice_num}}</span>@endif</i>
        	        <div class="mui-media-body">每日通告单</div>
        	    </a>
            </div>
    	</li>
		@if($is_tongchou || $hasPrepread)
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/notices?type=20&movie_id={{$movie_id}}&user_id={{$user_id}}&title=预备通告单">
                	<i class="mui-icon if i-file">@if($notice_backup_num > 0)<span class="mui-badge">{{$notice_backup_num}}</span>@endif</i>
                	<div class="mui-media-body">预备通告单</div>
                </a>
            </div>
        </li>
		@endif
        @if($bigPlanButton)
            <li class="mui-table-view-cell mui-media mui-col-xs-4">
                <div class="crew-menu-wrap">
                    <a href="">
                        <i class="mui-icon if i-file">@if($notice_backup_num > 0)<span class="mui-badge">{{$notice_backup_num}}</span>@endif</i>
                        <div class="mui-media-body">参考大计划</div>
                    </a>
                </div>
            </li>
        @endif
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/users/{{$user_id}}/messages?type=juzu&movie_id={{$movie_id}}&user_id={{ $user_id }}&title=剧组通知">
                	<i class="mui-icon if i-horn">@if($juzu_num > 0)<span class="mui-badge">{{$juzu_num}}</span>@endif</i>
                	<div class="mui-media-body">剧组通知</div>
                </a>
            </div>
        </li>
    	<li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/users/{{$user_id}}/messages?type=blog&movie_id={{$movie_id}}&user_id={{ $user_id }}&title=剧本扉页">
                	<i class="mui-icon if i-bookmk">@if($blog_num > 0)<span class="mui-badge">{{$blog_num}}</span>@endif</i>
                	<div class="mui-media-body">剧本扉页</div>
                </a>
            </div>
        </li>

    	<li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/users/public_contact?movie_id={{$movie_id}}&user_id={{ $user_id }}">
                	<i class="mui-icon if i-tel"></i>
                	<div class="mui-media-body">公开电话</div>
                </a>
            </div>
        </li>
		@if($contactButton > 0)
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/users/contact?movie_id={{$movie_id}}&user_id={{ $user_id }}">
                	<i class="mui-icon if i-book"></i>
                	<div class="mui-media-body">剧组通讯录</div>
                </a>
            </div>
        </li>
		@endif
        @if($groupButton > 0)
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/groups/manage?movie_id={{$movie_id}}&user_id={{ $user_id }}">
                	<i class="mui-icon if i-group"></i>
                	<div class="mui-media-body">部门管理</div>
                </a>
            </div>
        </li>
        @endif

		@if($progressButton)
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/charts/daily?movie_id={{$movie_id}}&user_id={{ $user_id }}&title=每日数据">
                	<i class="mui-icon if i-bag"></i>
                	<div class="mui-media-body">每日数据</div>
                </a>
            </div>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="http://chart.nanzhuxinyu.com/chart/charts/progresschart.jsp?chartId=2&juzuId={{$movie_id}}&apiToken=&userToken=&chartEnv=&movie={juzuId:{{ $movie_id }}}&title=数据图形">
                	<i class="mui-icon if i-pie"></i>
                	<div class="mui-media-body">数据图形</div>
                </a>
            </div>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/charts/all?movie_id={{$movie_id}}&user_id={{ $user_id }}&title=总数据">
                	<i class="mui-icon if i-data"></i>
                	<div class="mui-media-body">总数据</div>
                </a>
            </div>
        </li>
		@endif

		@if($juzuButton)
    	<li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/movies/{{$movie_id}}?user_id={{ $user_id }}&movie_id={{$movie_id}}&title=剧组信息">
                	<i class="mui-icon if i-crew"></i>
                	<div class="mui-media-body">剧组信息</div>
                </a>
            </div>
        </li>

    	<li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/groups?movie_id={{$movie_id}}&user_id={{ $user_id }}&title=部门列表">
                	<i class="mui-icon if i-list"></i>
                	<div class="mui-media-body">部门列表</div>
                </a>
            </div>
        </li>
    	<li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/permissions?user_id={{$user_id}}&movie_id={{$movie_id}}">
                	<i class="mui-icon if i-locked"></i>
                	<div class="mui-media-body">权限与设置</div>
                </a>
            </div>
        </li>

		@endif
    	<li class="mui-table-view-cell mui-media mui-col-xs-4">
            <div class="crew-menu-wrap">
            	<a href="/mobile/users/{{$user_id}}/group?movie_id={{$movie_id}}&user_id={{ $user_id }}">
                	<i class="mui-icon if i-user"></i>
                	<div class="mui-media-body">我在本组</div>
                </a>
            </div>
        </li>
        <li></li>
    </ul><!--/end-->
</div><!--/end-->

@else
<div class="crew-no tc">
    <p><i class="mui-icon if i-box"></i></p>
    <p class="g9 f16">工作台无任务，点击“搜索”加入剧组<br>或点击右上角“＋”创建新剧</p>
</div><!--/end-->
@endif

<script src="/assets/mobile/js/jquery.dropdown.js"></script>
<script>
$(document).ready(function(){

    var selectBox = $(".iselect").selectBoxIt({
        downArrowIcon: "mui-icon mui-icon-arrowdown"
    });

	selectBox = $(".iselect").on("change",function (){
        $("#movie_id_form").submit();
	})

});
</script>
</body>
</html>
