<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>创建新剧</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            background-color: #fff;
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
            position: relative;
            top: 18px;
        }

        .mui-input-group {
            background-color: transparent;
        }

        .mui-input-row, .mui-input-row:last-child {
            background-color: #fff;
        }

        .selectboxit .selectboxit-arrow-container .selectboxit-arrow {
            display: none;
        }

        .selectboxit-container {
            top: -1px;
            width: 66%;
        }

        .selectboxit-container .selectboxit {
            top: 6px;
        }
    </style>
</head>
<body>
<div class="crew-list crew-create">

    <form id="movie_form" class="mui-input-group" action="/mobile/movies" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="user_id" value="{{$user_id}}">
        <div class="mui-input-row">
            <label for="IDa">剧名</label>
            <input id="IDa" type="text" required name="FNAME" maxlength="30" placeholder="1-30字">
        </div>
        <div class="mui-input-row">
            <label for="IDb">出品方</label>
            <input id="IDb" type="text" name="chupinfang[]" maxlength="30" placeholder="选填">
            <a class="link-add" href="javascript:;" id="chupinfangbtn"><i class="mui-icon mui-icon-plus"></i></a>
        </div>

        <div id="chupinfang_div">
        </div>

        <div class="mui-input-row">
            <label for="IDc">制作方</label>
            <input id="IDc" type="text" name="zhizuofang[]" maxlength="30" placeholder="选填">
            <a class="link-add" href="javascript:;" id="zhizuofangbtn"><i class="mui-icon mui-icon-plus"></i></a>
        </div>

        <div id="zhizuofang_div">
        </div>
        <div class="mui-input-row">
            <label for="FTYPE">项目类型</label>
            <select id="FTYPE" name="FTYPE" required class="iselect">
                <option value="">请选择</option>
                @foreach(App\Models\Movie::old_types() as $key=> $type)
                    <option value="{{$key}}" data-icon="mui-icon if i-{{$key}}">{{$type}}</option>
                @endforeach
            </select>
        </div>
        <div class="mui-input-row">
            <label for="startDate">拍摄开始</label>
            <input id="startDate" name="FSTARTDATE" required type="date" required placeholder="开始日期">
        </div>
        <div class="mui-input-row">
            <label for="endDate">拍摄结束</label>
            <input id="endDate" name="FENDDATE" required type="date" required placeholder="结束日期">
        </div>
        <div class="mui-input-row">
            <label for="IDe">进组密码</label>
            <input id="IDe" class="join-psw" required type="text" name="FPASSWORD" maxlength="12"
                   placeholder="6-12位数字或字母，区分大小写" onblur=" this.style.imeMode='disabled'">
        </div>
        <div class="btn-wrap">
            <button type="button" id="form_btn" class="mui-btn mui-btn-block mui-btn-success">新建</button>
        </div>
        <input name="FISOROPEN" type="hidden" value="1"/>
    </form><!--/end-->

</div>

<script src="/assets/mobile/js/jquery.dropdown.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    mui.init();
    mui.ready(function () {
        $(document).ready(function () {
            var submitted = false;
            var form_validation = function form_validation() {
                var flag = true;
                var input = document.getElementById("IDe");
                input.blur();

                var regEmoji = new RegExp('^[\\u4E00-\\u9FA5A-Za-z0-9()（）<>《》\.]+$');
                var regchupin_zhizuo = new RegExp('^[\\u4E00-\\u9FA5-\\(-)-（-）-.A-Za-z0-9]+$');
                if ($("input[name='FNAME']").val() == "") {
                    $(this).blur();
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
                    $(this).blur();
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
                    $.post('/mobile/movies/validate', {
                        name: $("input[name='FNAME']").val(),
                        type: $("#FTYPE option:selected").val(),
                    }, function (response) {
                        if (!response.success) {
                            mui.alert(response.msg);
                            return false;
                        }
                        if (!submitted) {
                            submitted = true;
                            $("#movie_form").submit();
                        } else {
                            mui.alert('程序正在处理,请稍后');
                        }
                    });
                }
            };
            $("#form_btn").on("click", form_validation);
        });
    });

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

        $("#zhizuofangbtn").on("click", function () {
            var div = "<div class=\"mui-input-row\"><label>制作方</label><input type=\"text\" maxlength=\"30\" name=\"zhizuofang[]\"><a class=\"link-add\" href=\"#\" name='zhizuofang' ><i class=\"mui-icon mui-icon-minus\"></i></a></div>";
            $("#zhizuofang_div").append(div);
            $("a[name='zhizuofang']").on("click", function () {
                $(this).parent().remove();
            });
        });

        $("#FTYPE").css("width", "100%");

    });
</script>
</body>
</html>