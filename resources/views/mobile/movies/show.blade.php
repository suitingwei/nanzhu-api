<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>剧组信息</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        .mui-input-row label {
            color: #888;
        }

        .mui-input-row label.red {
            color: #f15252;
        }

        input[type='text'] {
            padding: 10px 55px 10px 15px;
        }

        input.join-psw {
            padding-right: 15px;
        }

        .link-add {
            display: inline-block;
            z-index: 100;
            height: 100%;
            padding-left: 20px;
            padding-right: 15px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .link-add i {
            font-style: normal;
            position: relative;
            top: 18px;
        }

        .mui-input-group {
            background-color: transparent;
        }

        .mui-input-row, .mui-input-row:last-child {
            background-color: #fff;
        }

        .mui-input-row .last {
            width: 100%;
        }

        .selectboxit .selectboxit-arrow-container .selectboxit-arrow {
            display: none;
        }

        .selectboxit-container {
            top: -1px;
        }

        .selectboxit-container .selectboxit {
            top: 6px;
        }

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .mui-input-row .last {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="crew-list crew-create">

    <form id="form" class="mui-input-group" action="/mobile/movies/{{$movie->FID}}" method="POST">
        <input type="hidden" name="_method" value="PATCH">
        {!! csrf_field() !!}
        <div class="mui-input-row">
            <label for="IDa">剧名</label>
            <input id="IDa" type="text" name="FNAME" maxlength="30" value="{{$movie->FNAME}}">
        </div>
        <?php $chupinfangArr = explode(',', $movie->chupinfang);?>

        @foreach($chupinfangArr as $index => $chupinfang)
            <div class="mui-input-row">
                <label for="IDb">出品方</label>
                <input id="IDb" type="text" name="chupinfang[]" maxlength="30" placeholder="选填" value="{{$chupinfang}}">
                @if($index ==0)
                    <a class="link-add" id="chupinfangbtn"><i class="mui-icon mui-icon-plus"></i></a>
                @elseif($index>0)
                    <a class="link-add" name="chupinfang"><i class="mui-icon mui-icon-minus"></i></a>
                @endif
            </div>
        @endforeach

        <div id="chupinfang_div">
        </div>

        <?php $zhizuofangArr = explode(',', $movie->zhizuofang);?>

        @foreach($zhizuofangArr as $index => $zhizuofang)
            <div class="mui-input-row">
                <label for="IDc">制作方</label>
                <input id="IDc" type="text" name="zhizuofang[]" maxlength="30" placeholder="选填" value="{{$zhizuofang}}">
                @if($index ==0 )
                    <a class="link-add" id="zhizuofangbtn"><i class="mui-icon mui-icon-plus"></i></a>
                @else
                    <a class="link-add" name="chupinfang"><i class="mui-icon mui-icon-minus"></i></a>
                @endif
            </div>
        @endforeach

        <div id="zhizuofang_div">
        </div>

        <div class="mui-input-row">
            <label for="IDd">项目类型</label>
            <select id="IDd" name="FTYPE" class="iselect">
                @foreach(App\Models\Movie::old_types() as $key=> $type)
                    <option value="{{$key}}" @if($movie->FTYPE==$key) selected
                            @endif data-icon="mui-icon if i-{{$key}}">{{$type}}</option>
                @endforeach
            </select>
        </div>
        <div class="mui-input-row">
            <label for="startDate">拍摄开始</label>
            <input id="startDate" name="FSTARTDATE" type="date" value="{{substr($movie->FSTARTDATE,0,10)}}">
        </div>
        <div class="mui-input-row">
            <label for="endDate">拍摄结束</label>
            <input id="endDate" name="FENDDATE" type="date" value="{{substr($movie->FENDDATE,0,10)}}">
        </div>
        <div class="mui-input-row mb10">
            <label for="IDe">进组密码</label>
            <input id="IDe" class="join-psw" type="text" name="FPASSWORD" maxlength="12" value="{{$movie->FPASSWORD}}"
                   placeholder="6-12位数字或字母，区分大小写">
        </div>
        <div class="mui-input-row">
            <a href="/mobile/movies/{{ $movie->FID }}/members?user_id={{ $user_id}}&title=剧组人员">
                <label class="last">当前剧组人员</label>
                <span class="link-add c-g"><i>{{ $movie->allUsersInMovie()->count() }}人</i></span>
            </a>
        </div>
        <div class="mui-input-row">
            <label for="fisoropen" class="last">允许人员进组</label>
            <input class="switch" type="checkbox" id="fisoropen" name="FISOROPEN" value=1
                   @if($movie->FISOROPEN==1) checked @endif>
        </div>
        <div class="mui-input-row last-row">
            <label class="last f16 red">如关闭此功能，任何人将无法加入本剧组</label>
        </div>
        <div class="btn-wrap">
            <button id="save" type="button" class="mui-btn mui-btn-block mui-btn-success">保存</button>
        </div>
    </form><!--/end-->

</div>
</body>

<script src="/assets/mobile/js/jquery.dropdown.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    $(document).ready(function () {

        var selectBox = $(".iselect").selectBoxIt({
            downArrowIcon: "mui-icon mui-icon-arrowdown"
        });

        $("#chupinfangbtn").on("click", function () {
            var div = "<div class=\"mui-input-row\"><label>出品方</label><input type=\"text\" maxlength=\"30\" name=\"chupinfang[]\"><a class=\"link-add\" href=\"#\" name=\"chupinfang\" ><i class=\"mui-icon mui-icon-minus\"></i></a></div>";
            $("#chupinfang_div").append(div);
            $("a[name='chupinfang']").on("click", function () {
                $(this).parent().remove();
            });

        });

        $("a[name='chupinfang']").on("click", function () {
            $(this).parent().remove();
        });

        $("#zhizuofangbtn").on("click", function () {
            var div = "<div class=\"mui-input-row\"><label>制作方</label><input type=\"text\" maxlength=\"30\" name=\"zhizuofang[]\"><a class=\"link-add\" href=\"#\" name='zhizuofang' ><i class=\"mui-icon mui-icon-minus\"></i></a></div>";
            $("#zhizuofang_div").append(div);
            $("a[name='zhizuofang']").on("click", function () {
                $(this).parent().remove();
            });
        });

        $("a[name='zhizuofang']").on("click", function () {
            $(this).parent().remove();
        });
    });

    mui.init();
    mui.ready(function () {

        $(document).ready(function () {
            var form_validation = function form_validation() {
                var flag = true;
                var regEmoji = new RegExp('^[\\u4E00-\\u9FA5A-Za-z0-9()（）<>《》\.]+$');
                var regchupin_zhizuo = new RegExp('^[\\u4E00-\\u9FA5-\\(-)-（-）-.A-Za-z0-9]+$');
                if ($("input[name='FNAME']").val() == "") {
                    mui.alert("请填写剧名");
                    flag = false;
                    return;
                }
                if (regEmoji.exec($("input[name='FNAME']").val()) == null) {
                    mui.alert("剧名只能输入中文,字母,数字");
                    flag = false;
                    return;
                }
                if ($("input[name='chupinfang[]']").val() != "") {
                    var arr = $("input[name='chupinfang[]']");
                    for (var i = 0; arr.length > i; i++) {
                        var item = $(arr[i]);
                        console.log(item.val());
                        if (item.val() != "") {
                            if (regchupin_zhizuo.exec(item.val()) == null) {
                                mui.alert("出品方只能输入中文,字母,数字,()（）.");
                                flag = false;
                                return;
                            }
                        }
                    }
                }
                if ($("input[name='zhizuofang[]']").val() != "") {
                    var arr = $("input[name='zhizuofang[]']");
                    for (var i = 0; arr.length > i; i++) {
                        var item = $(arr[i]);
                        console.log(item.val());
                        if (item.val() != "") {
                            if (regchupin_zhizuo.exec(item.val()) == null) {
                                mui.alert("制作方只能输入中文,字母,数字,()（）.");
                                flag = false;
                                return;
                            }
                        }
                    }
                }
                if ($("#FTYPE option:selected").val() == "") {
                    mui.alert("请选择类型");
                    flag = false;
                    return;
                }
                var beginDate = $("input[name='FSTARTDATE']").val();
                var endDate = $("input[name='FENDDATE']").val();
                if (beginDate == "") {
                    mui.alert("请选择开始时间");
                    flag = false;
                    return;
                }
                if (endDate == "") {
                    mui.alert("请选择结束时间");
                    flag = false;
                    return;
                }
                if (new Date(beginDate).getTime() + 1 > new Date(endDate).getTime()) {
                    mui.alert("开始时间不能比结束时间大");
                    flag = false;
                    return;
                }

                if ($("input[name='FPASSWORD']").val() == "") {
                    mui.alert("请填写进组密码");
                    flag = false;
                    return;
                }
                var reg = new RegExp('^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$');
                if (reg.exec($("input[name='FPASSWORD']").val()) == null) {
                    mui.alert("进组密码必须为长度为6~12位的字母+数字组合");
                    flag = false;
                    return;
                }
                if (flag) {
                    if (!document.getElementById('fisoropen').checked) {
                        document.getElementById('fisoropen').value = 0;
                    }
                    document.getElementById('form').submit();
                    mui.toast('保存成功');
                }
            }
            $("#save").on("click", form_validation);
        });

    });

    /*
     //save
     function formsubmit() {
     if (!document.getElementById('fisoropen').checked) {
     document.getElementById('fisoropen').value = 0;
     }
     document.getElementById('form').submit();
     mui.toast('保存成功');
     }*/

    function history_back() {
        window.nanzhu.backHome();
    }
</script>
</html>
