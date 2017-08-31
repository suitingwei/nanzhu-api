<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>部门管理</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html, body {
	background-color: #fff;
}
input[type='button']:disabled, input[type='button'].mui-disabled,
input[type='submit']:disabled,
input[type='submit'].mui-disabled,
input[type='reset']:disabled,
input[type='reset'].mui-disabled,
button:disabled,
button.mui-disabled,
.mui-btn:disabled,
.mui-btn.mui-disabled {
	opacity: .6;
	border-color: #ccc;
	background-color: #fff;
	color: #999;
}
.list-join {
	background-color: #f6f6f6;
}
.list-join .mui-table-view {
	background-color: transparent;
}
.list-join .mui-table-view-cell:after{
	right: 15px;
	background-color: #e8e8e8;
}
.list-join .mui-table-view:after {
	background-color: transparent;
}
.list-join .mui-col-xs-7 {
	padding: 10px 0 10px 15px;
}
.list-join .mui-col-xs-5 {
	padding-right: 15px;
}
.list-join .mui-col-xs-5 .mui-btn {
	margin-top: 5px;
	margin-left: 7px;
	color: #999;
	border-color: #999;
}
.list-join .mui-col-xs-5 .mui-btn-success {
	color: #3bb8a3;
	border-color: #3bb8a3;
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

@media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2){
	.list-join .mui-col-xs-5 .mui-btn {
		margin-left: 2px;
	}
}
</style>
</head>
<body>
<div id="listJoin" class="list-join">
	<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
		@foreach($allGroupsApplies as $apply)
			<li class="mui-table-view-cell">
				<div class="mui-table">
					<div class="mui-table-cell mui-col-xs-7">
						<div class="f16 mui-ellipsis">{{ $apply->user->FNAME }}</div>
						<p class="g6 mui-ellipsis">申请加入{{ $apply->group->FNAME }}</p>
					</div>
					<div class="mui-table-cell mui-col-xs-5 mui-text-right">
						<button type="button" class="mui-btn mui-btn-outlined mui-btn-success" onclick="approveJoinGroup(this,'{{ $user->FID }}','{{ $apply->id }}')">同意</button>
						<button type="button" class="mui-btn mui-btn-outlined" onclick="declineJoinGroup(this,'{{ $user->FID }}','{{ $apply->id }}')">拒绝</button>
					</div>
				</div>
			</li>
		@endforeach
	</ul>
</div><!--/end-->

<div class="manage-groups">
	<ul class="mui-table-view">
		@foreach($groups as $group)
			<li class="mui-table-view-cell">
				<a href="/mobile/groups/manage?movie_id={{ $movieId }}&user_id={{ $user->FID }}&group_id={{ $group->FID }}&percent={{ $group->members()->count()}}" class="mui-navigate-right">
                    {{ $group->FNAME }}
				</a>
			</li>
		@endforeach
	</ul>
</div>
</body>

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>

	/**
	 * 同意入组申请
	 */
	function approveJoinGroup(approveBtn,userId,joinGroupId){
		var url = '/mobile/users/'+userId+'/approve_join_group/'+joinGroupId;
		$.post(url,function(response){
			if(response.success){
				window.location.reload();
			}
		});
	}

	/**
	 * 拒绝入组申请
	 */
	function declineJoinGroup(declineBtn,userId,joinGroupId){
		var url = '/mobile/users/'+userId+'/decline_join_group/'+joinGroupId;
		$.post(url,function(response){
			if (response.success){
			    window.location.reload();
			}
		});
	}

	function history_back(){
		window.nanzhu.backHome();
	}
</script>
</html>