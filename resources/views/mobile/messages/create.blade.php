<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>新增通知</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            background-color: #fff;
        }

        .wrapper {
            margin: 15px;
        }

        .crew-list .mui-input-group:before {
            height: 1px;
        }

        .mui-row {
            margin-left: -4%;
        }

        .mui-col-xs-4 {
            margin-left: 4%;
            width: 29.333333%;
        }

        .msg {
            padding-bottom: 60px;
        }

        .pic {
            height: 110px;
            text-align: center;
            background: #f9f9f9 url("/assets/mobile//img/logo-d.png") no-repeat center center;
            background-size: contain;
            border: 1px solid #fff;
        }

        .pic img {
            max-width: 100%;
            float: none;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
        }

        .btn-file {
            position: relative;
            height: 110px;
            text-align: center;
            border: 1px solid #e8e8e8;
        }

        .btn-file .i-bplus {
            width: 100%;
            height: 100%;
            color: #e8e8e8;
            font-size: 60px;
        }

        .btn-file .i-bplus:before {
            position: relative;
            top: 25%;
        }

        .file {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 3;
            width: 100%;
            height: 100%;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
            -moz-opacity: 0;
        }

        .btn-del {
            display: block;
            position: absolute;
            right: 5px;
            bottom: 5px;
            color: #ff584e;
            background-color: rgba(255, 255, 255, .7);
            border-radius: 50%;
            padding: 3px;
            width: 30px;
            height: 30px;
            line-height: 25px;
            overflow: hidden;
            text-align: center;
            z-index: 20;
        }

        .btn-del .if {
            font-size: 20px;
        }

        .fixed {
            z-index: 30;
        }
    </style>
</head>
<body>
<div class="msg crew-list">

    <form id="form" class="mui-input-group" action="/mobile/users/{{$user_id}}/messages" method="post"
          enctype='multipart/form-data' accept-charset="utf-8">
        <input type="hidden" name="movie_id" value="{{$movie_id}}">
        <input type="hidden" name="user_id" value="{{$user_id}}">
        <input type="hidden" name="url_title" value="{{request('title')}}">
        <input type="hidden" name="type" value="{{$type}}">

        <div class="mui-input-row">
            <input type="text" name="title" maxlength="62" placeholder="标题">
        </div>

        <div class="mui-input-row last-row">
            <textarea maxlength="25000" rows="7" name="content" placeholder="在这里填写内容哟..."></textarea>
            <div class="wrapper">
                <div class="mui-row">
                    <div class="mui-col-xs-4" id="uploadedImageAre1a">
                        <div class="pic">
                            <img id="image" src="">
                            <input type="hidden" name="img_url[]">
                            <input type="hidden" name="multiple_upload" id="multiple" value="false">
                        </div>
                        <a onclick="deleteImg(this);" class="btn btn-link btn-del"><i class="if i-del"></i></a>
                    </div>
                    <div class="mui-col-xs-4" id='uploadedImageArea'>
                        <div class="btn-file">
                            <i class="mui-icon if i-bplus"></i>
                            <!--兼容性,如果是上个版本的ios,因为使用的是file上传,会默认弹出ios的相册,跟js调用的相册不是一个相册,所以要屏蔽掉-->
                            <!--onclick事件,也就是说旧版本的ios,只是一个input file,当然判断旧版本是不行的,因为旧版本没有传递header,所以只能说不是当前版本的就是老版本
                                所以如果以后ios更新了,也需要把!=的范围加大
                            -->
                            @if($iosAppVersion && version_compare($iosAppVersion,'3.2.0','<=') )
                                <input type="file" id="files" class="file" name="pic_url[]">
                            @else
                                <button id="files" class="file" type="button"
                                        name="pic_url[]" value=""
                                        onclick="callAndroid()">
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div><!--/end-->
        </div><!--/end-->

        <div class="fixed">
            <button id="form_btn" type="button" class="btn-fixed mui-btn mui-btn-block mui-btn-success">保存</button>
        </div><!--/end-->
    </form><!--/end-->

</div><!--/end-->
<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>

    function history_back() {
        window.history.back();
        return true;
    }

    mui.init();
    mui.ready(function () {
        $(document).ready(function () {
            var submitted = false;
            var form_validation = function form_validation() {
                var flag = true;
                if ($("input[name='title']").val() == "") {
                    mui.alert("请填写标题");
                    flag = false;
                    return;
                }
                if ($("textarea[name='content']").val() == "") {
                    mui.alert("请填写内容");
                    flag = false;
                    return;
                }
                if (flag) {
                    if (!submitted) {
                        submitted = true;
                        $("#form").submit();
                    } else {
                        mui.alert('程序正在运行,请耐心等待...');
                    }

                }
            }
            $("#form_btn").on("click", form_validation);
        });
    });

    document.getElementById("files").onchange = function () {

        var reader = new FileReader();
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            document.getElementById("image").src = e.target.result;
        };

        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);

    };

    /**
     * 安卓上传图片时候调用
     * 通知开始上传图片
     */
    function callAndroid() {
        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if (isAndroid) {
            window.nanzhu.callCalculator();
        } else if (isIOS) {
            juzuAddIOSPic(9);
        }
    }

    /**
     *
     */
    function removeOldImageUrl() {
        $("div.append-img").remove();
        $("#image").attr('src', '');
    }
    /**
     * 获取上传图片的url
     * @param imgUrl
     */
    function showImageUrl(imgUrlString) {

        removeOldImageUrl();

        var urlArray = imgUrlString.split(',');

        $("#image").attr('src', urlArray[0]);
        $('input[name="img_url[]"]').val(urlArray[0]);
        $("#multiple").val(true);

        for (var index = 1; index < urlArray.length; index++) {
            $("#uploadedImageArea").before(
                    '<div class="mui-col-xs-4 append-img" > ' +
                    '<div class="pic">' +
                    '<img id="image" src="' + urlArray[index] + '">' +
                    '<input type="hidden" name="img_url[]" value="' + urlArray[index] + '">' +
                    '<a onclick="deleteImg(this);" class="btn btn-link btn-del"><i class="if i-del"></i></a>' +
                    '</div> ')

        }
        return imgUrlString.length;
    }

    /**
     * 删除选中的图片
     * @param deleteBtn
     */
    function deleteImg(deleteBtn) {
        var appendImgParent = $(deleteBtn).parents('div.append-img').first();
        if (appendImgParent.length != 0) {
            appendImgParent.remove();
        } else {
            $("#image").attr('src', '').next('input[name="img_url[]"]').val('');
        }
    }

</script>
</body>
</html>
