<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"> <title>申请退出</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html, body {
	background-color: #fff;
}
.manage-groups .mui-table-view-cell > a:not(.mui-btn) {
	margin: 18px 17px;
}
.manage-groups .mui-table-view:before {
	height: 0;
}
.manage-groups .mui-table-view-cell:after {
	right: 15px;
	background-color: #e8e8e8;
}
.manage-groups .mui-table-view:after {
	right: 15px;
	left: 15px;
	background-color: #e8e8e8;
}
.mui-navigate-right:after, .mui-push-right:after {
	right: 0;
	font-size: 20px;
}
</style>
</head>
<body>
<div class="manage-groups">
	<ul class="mui-table-view">
        @foreach($groups as $group)
			<li class="mui-table-view-cell" onclick="exitGroup('{{ $userId }}','{{$group->FID}}','{{ $group->FNAME }}','{{ $movieId }}')">
				<a class="mui-navigate-right">
					退出{{ $group->FNAME }}部门
				</a>
			</li>
		@endforeach
	</ul>
</div><!--/end-->

<div class="fixed">
	<button type="button" class="btn-fixed mui-btn mui-btn-block mui-btn-danger" onclick="exitMovie('{{$userId}}','{{$movieId}}')">退出本剧组</button>
</div><!--/end-->

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
/**
 * 退出部门
 * @param groupId
 */
function exitGroup(userId,groupId,groupName,movieId){

	var btnArray = ['否', '是'];
	mui.confirm('是否退出'+groupName+'部门？', '提示', btnArray, function (e) {
		if (e.index == 1) {
			var url = '/mobile/users/'+userId+'/exit_group/'+groupId;
			$.post(url, {movie_id:movieId},function (responseData) {
				mui.alert(responseData.msg);
				if(responseData.success){
					window.location.reload();
				}
			})

		}
	});
}

/**
 * 退出剧组
 * @param userId
 * @param movieId
 */
function exitMovie(userId,movieId){
	var btnArray = ['否', '是'];
	var u = navigator.userAgent, app = navigator.appVersion;
	var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
	var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

	mui.confirm('是否退出本剧组？', '提示', btnArray, function (e) {
		if (e.index == 1) {
			var url = '/mobile/users/'+userId+'/exit_movie/'+movieId;
			$.post(url, function (responseData) {
				mui.alert(responseData.msg);
				if(responseData.success){
				    if(isAndroid){
				    	window.nanzhu.backHome(responseData.data.movie_id);
					}else if(isIOS){
					    popToMenu();
					}
				}
			})
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