<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>南竹通告单</title>
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<script src="/assets/mobile/js/flexible_css.js"></script>
<link rel="stylesheet" href="/assets/mobile/css/style.css">
</head>
<body>

<div class="container">

	@if(count($users) > 0)
    <div class="pdlr30 users-list bg-w">
        <ul class="list">
        	@foreach($users as $user)
            <li>
                <div class="item-pic"><img src="{{$user->toArray()['FPIC']}}"></div>
                <div class="item-content">
                    <p class="item-title">{{$user->FNAME}}</p>
                    <div class="item-desc">{{$user->FPHONE}}</div>
                </div>
                <div class="btn-op">
                	<form action="/mobile/users/{{$user_id}}/delete_share" method="post" style="display:inline-block" accept-charset="utf-8">
						<input type="hidden" name="_method" value="DELETE">
						<input type="hidden" name="user_id" value="{{$user->FID}}">
						<button type="submit" class="btn btn-link btn-del"><i class="if i-del"></i></button>
					</form>
                </div>
            </li>
            @endforeach
        </ul>
    </div><!-- users end -->

	@else
	<div class="msg-empty ac">
        <div class="msg-empty-desc f16">暂无授权，最多可授权三个人编辑您的资料</div>
    </div><!-- msg end -->
	@endif

	@if(count($records) > 0)
    <div class="t1 pdlr30">编辑记录</div>
    <div class="pdlr30 users-list bg-w">
        <ul class="list">
			@foreach($records as $record)
            <li>
				<?php $u = App\User::where("FID",$record->user_id)->first()?>
                <div class="item-pic"><img src="{{$u->toArray()['FPIC']}}"></div>
                <div class="item-content">
                    <p class="item-title">{{$u->FNAME}}<span class="c-g">{{$u->FPHONE}}</span></p>
                    <div class="item-desc f14">{{$record->created_at}} 编辑过您的资料</div>
                </div>
            </li>
			@endforeach
        </ul>
    </div><!-- users end -->
	@endif

</div><!-- container end -->

@if(count($users) < 3)
<div class="btn-fixwrap">
	<a id="addUser" href="/mobile/users/{{$user_id}}/add_share" class="btn btn-primary btn-lg btn-block">授权</a>
</div>
@endif

<script>
    function history_back(){
        window.nanzhu.backHome();
        return true;
    }
</script>
</body>
</html>