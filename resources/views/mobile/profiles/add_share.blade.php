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

<div class="search">
    <div class="user-search">
    	<form action="/mobile/users/{{$user_id}}/add_share" method="get" accept-charset="utf-8">
	        <input class="search-ipt" type="text" name="phone" value="" placeholder="输入手机号搜索">
	        <button type="submit" class="btn btn-link btn-search"><i class="if i-search"></i></button>
	    </form>
    </div>
</div><!-- search end -->

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
					@if(in_array($user->FID,$user_ids))
					<button type="submit" disabled class="btn btn-sm btn-default btn-add disabled">已添加</button>

					@else
					<form action="/mobile/users/{{$user_id}}/post_share" method="post" style="display:inline-block" accept-charset="utf-8">
						<input type="hidden" name="user_id" value="{{$user->FID}}">
	                    <button type="submit" class="btn btn-sm btn-default btn-add">授权</button>
					</form>
					@endif
                </div>
            </li>
            @endforeach
        </ul>
    </div><!-- users end -->
	@elseif($users)
		<div class="msg-empty ac">
			<div class="msg-empty-desc f16">对不起，没有搜索到该协助编辑哟<br>赶快让TA加入南竹通告单吧~</div>
		</div><!-- msg end -->
	@endif

</div><!-- container end -->

<script>


    function history_back(){
        window.history.back();
		return true;
	}


</script>
</body>
</html>