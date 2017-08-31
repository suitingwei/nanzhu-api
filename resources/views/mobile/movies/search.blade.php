<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>搜索剧组</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
.if {
    font-size: 30px;
    position: relative;
    top: 4px;
}
.i-40, .i-50, .i-80, .i-100, .i-35 {
    top: 5px;
}
.dl-horizontal dt {
	width: 28%;
}
.dl-horizontal dd {
	margin-left: 0;
	width: 66%;
}

.tips {
    padding-top: 60%;
    line-height: 1.6;
}
.tips .i-tips {
    color: #bbbec4;
    font-size: 60px;
}

@media screen and (-webkit-device-pixel-ratio: 2) and (device-height: 568px) and (device-width: 320px) {
	.card .dl-horizontal dd {
		margin-left: 0;
	}
}
/* i6 */
@media only screen and (min-device-width: 375px) and (max-device-width: 667px) and (orientation : portrait) {
	.dl-horizontal dt {
		width: 28%;
	}
}

/* i6p */
@media only screen and (min-device-width: 414px) and (max-device-width: 736px) and (orientation : portrait) {
	.dl-horizontal dt {
		width: 24%;
	}
}
</style>
</head>
<body>
<div class="crew-search">

    @if(count($movies) == 0)
        <div class="tips tc">
            <p><i class="mui-icon if i-tips"></i></p>
            <p class="f16 g9">暂无相关数据哦~ </p>
        </div><!--/end-->
    @else
        @foreach($movies as $movie)
        <div class="card">
            <div class="card-header">
                 <div class="card-header-t">
                 <i class="mui-icon if i-{{$movie->FTYPE}}"></i><span class="f18">{{$movie->movie_name}}</span>
                 </div>
                 <div class="card-op">
                 @if(array_key_exists($movie->movie_id,$movie_ids))
                 <button type="button" disabled="" class="mui-btn mui-btn btn-l disabled mui-btn-success">已经加入</button>
                 @else
                 @if($movie->FISOROPEN == 1)
                 <a href="/mobile/movies/access_join?movie_id={{$movie->movie_id}}&user_id={{$user_id}}" class="mui-btn mui-btn btn-l mui-btn-success">申请进组</a>
                 @else
                 <button type="button" disabled="" class="mui-btn btn-l disabled mui-btn-success">已经关闭</button>
                 @endif
                 @endif
                 </div>
             </div>
             <div class="card-content">
                 <div class="card-content-inner">
                 <ul class="list-unstyled">
                 <li class="fix">
                 <dl class="dl-horizontal">
                 <dt>出品方：</dt>
                 <dd>{{$movie->chupinfang}}</dd>
                 </dl>
                 </li>
                 <li class="fix">
                 <dl class="dl-horizontal">
                 <dt>制作方：</dt>
                 <dd>{{$movie->zhizuofang}}</dd>
                 </dl>
                 </li>
                 <li class="fix">
                 <dl class="dl-horizontal">
                 <dt>建剧人：</dt>
                 <dd>{{App\User::where("FID",$movie->FNEWUSER)->first()->FNAME}}</dd>
                 </dl>
                 </li>
                 <li class="fix">
                 <dl class="dl-horizontal">
                 <dt>拍摄时间：</dt>
                 <dd>{{substr($movie->FSTARTDATE,0,10)}} 至 {{substr($movie->FENDDATE,0,10)}}</dd>
                 </dl>
                 </li>
                 </ul>
                 </div>
             </div>
         </div><!--/end-->
        @endforeach
    @endif

</div><!--/end-->

</body>
</html>