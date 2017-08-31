<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>合作邀约</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">

    <style>
        .send{
            width: 150px;
            text-align: center;
            height: 35px;
            line-height: 35px;
            color: white;
            background-color: #00bba1;
            border-radius: 17px;
            padding: 0;
            margin-left: 28%;

        }
        .forbtn{
            position: fixed;
            bottom: 0;
            background-color: #fff;
            padding: 10px 0;
            width: 100%;
            margin: 0;
        }
        .form-group{
            margin-bottom: 0;
        }

    </style>
</head>
<body style="background-color: #f0f0f0">
<div class="row" >
    <div class="col-lg-12">
        <div class="panel panel-default" style="border:none">



            <div class="panel-body" style="background-color: #f0f0f0;padding: 15px 0 0 0 ">
                    <div class="form-group">
                        <div class="col-lg-8">
                            <input id="title" required="required" type="text" name="title" class="form-control" placeholder="标题" style="border: none">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 10px">
                        <input type="hidden" name="content" id="forContent" >
                        <div class="col-lg-8">
                            <textarea id="editor" required="required" name="content" style="width:100%;height:200px;border:none" placeholder="描述" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-8">
                            <input id="phone" type="text" name="phone" class="form-control" placeholder="手机号" style="border: none" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-8">
                            <input id="wx" type="text" name="wx" class="form-control" placeholder="微信号" style="border: none">
                        </div>
                    </div>
                    <div class="form-group forbtn">
                        <button  class="send" onclick="sendCV('{{$userId}}','{{$basementId}}')">发送</button>
                    </div>


            </div><!-- /.panel-body -->

        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

</body>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/m.js"></script>
<script>

    function history_back() {
        window.nanzhu.backHome();
    }

    function sendCV(userId,scriptId){
        var phone = $('#phone').val();
        var title = $('#title').val();
        var wx    = $('#wx').val();
        var content = $('#editor').val();
        $.ajax({
            type:'post',
            url :'/mobile/trade-resources/sendCV',
            data: {'userId':userId,'scriptId':scriptId},
            success:function(res){
                if(res.msg==0){
                    mui.alert('请完善个人资料后重试');
                }
                if(res.msg==1){
                    var btnArray = ['否', '是'];
                    mui.confirm('','是否将您的合作邀约发给对方?', btnArray, function (e) {
                        if (e.index == 1) {
                            $.ajax({
                                type:'post',
                                url: '/mobile/trade-resources/sendProfile/{{$type}}',
                                data:{'profileId':res.data.ProfileId,'basementId':scriptId,'userId':userId,'title':title,'wx':wx,'content':content,'phone':phone},
                                success:function(respon){
                                    mui.alert('已发送,请等待对方联系');
                                }
                            })
                        }
                    });
                }
            }
        })
    }
</script>
</html>