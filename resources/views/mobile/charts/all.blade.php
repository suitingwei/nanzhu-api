<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>总数据</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
html, body {
    background-color: #fff;
    font-size: 16px;
}
.data {
    padding: 30px 0 58px;
}

.box-a {
    padding: 0 15px;
}

.crew-list {
    border: 1px solid rgba(232, 232, 232, .5);
    border-radius: 5px;
}
.mui-input-group {
    border-radius: 5px;
}

input[type='text'],input[type='number'] {
    padding: 10px 0;
    text-align: right;
    color: #3bb8a3;
    margin-right: 5px;
    width: 60%;
}

.mui-input-group .ipt-sdata {
    padding: 10px 15px 10px 0;
    text-align: right;
    color: #3bb8a3;
    width: 100%;
    display: inline-block;
}

.ri {
    float: right;
    width: 70%;
    text-align: right;
}

.ri span {
    margin-right: 15px;
}

.ri .lbfocus {
    width: auto;
    float: none;
    margin-right: 15px;
    padding: 0;
}

.crew-list .mui-input-group .mui-input-row:after {
    left: 0;
    right: 0;
}

.mui-input-row, .mui-input-row:last-child:after {
    height: 0;
}

.mui-input-row label {
    width: 30%;
    padding-right: 0;
}
.no_total_data::-webkit-input-placeholder{
    color: #fff;
}
.total-data::-webkit-input-placeholder {
  color: #f15252;
}
@media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
    .mui-input-row label {
        font-size: 14px;
        padding-left: 10px;
    }

    .ri input[type='text'],input[type='number'] {
        font-size: 14px;
    }

    .ri span, .ri .lbfocus {
        font-size: 14px;
        margin-right: 10px;
    }

    .mui-input-group .ipt-sdata {
        padding-right: 10px;
        font-size: 14px;
    }

}
</style>
</head>
<body>
<div class="data">
    <div class="box-a">
        <div class="box-bd">

            <div class="crew-list">
                <form id="form" class="mui-input-group" action="/mobile/charts/update_all?movie_id={{$movie_id}}" method="post"
                      accept-charset="utf-8">
                    <input type="hidden" name="FMOVIEID" value="{{$movie_id}}">
                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="mui-input-row">
                        <label for="IDa">总天数</label>
                        <div class="ri">
                            <input id="IDa" type="number" name="ftotalday" class="total-data" placeholder="必填、可改"
                                   @if(!$isTongChou) readonly @endif
                            @if($totaldata) value="{{$totaldata->FTOTALDAY}}" @endif>
                            <label class="lbfocus" for="IDa">天</label>
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label for="IDb">总页数</label>
                        <div class="ri">
                            <input id="IDb" type="number" name="ftotalpage" placeholder="未定稿可暂不填"
                                   @if(!$isTongChou) readonly @endif
                                  @if($totaldata)
                                    @if(intval($totaldata->FTOTALPAGE) == 0)
                                        class="no_total_data" value=""
                                    @else
                                        value="{{$totaldata->FTOTALPAGE}}"
                                    @endif
                                  @endif>
                            <label class="lbfocus" for="IDb">页</label>
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label for="IDc">总场／镜数</label>
                        <div class="ri">
                            <input id="IDc" type="number" name="ftotalscene" placeholder="未定稿可暂不填"
                                   @if(!$isTongChou) readonly @endif
                            @if($totaldata)
                                @if(intval($totaldata->FTOTALSCENE) == 0)
                                   class="no_total_data"
                                   value=" "
                                @else
                                   value="{{$totaldata->FTOTALSCENE}}"
                                @endif
                            @endif>
                            <label class="lbfocus" for="IDc">场／镜</label>
                        </div>
                    </div>
                    <div class="mui-input-row">
                        <label for="IDd">开拍时间</label>
                        <div class="ri">
                            <input id="IDd" class="ipt-sdata" name="fstartdate" value="@if($totaldata){{substr($totaldata->FSTARTDATE,0,10)}}@endif" type="date"   @if(!$isTongChou) readonly @endif>
                        </div>
                    </div><!--/end-->
					@if($isTongChou)
					<div class="fixed">
						<button type="button" class="btn-fixed mui-btn mui-btn-block mui-btn-success" onclick="form_submit()">保存</button>
                    </div><!--/end-->
					@endif
                </form>
            </div><!--/end-->

        </div><!--/end-->
    </div><!--/end-->

</div><!--/end-->

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
function form_submit() {
    if( $("input[name=ftotalday]").val() == ""){
        mui.alert('总天数必填、保存后可改!');
        return false;
    }
    if( $("input[name=fstartdate]").val() == ""){
        mui.alert('开拍时间必须选择!');
        return false;
    }

    var form = $("#form");
    $.post(form.attr('action'),form.serialize(),function(response){
        mui.toast(response.msg);
        window.location.href=reponse.redirect_url;
    });
}

function history_back(){
    window.nanzhu.backHome();
}
</script>
</body>
</html>
