<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>每日数据</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .mui-bar {
            background-color: #3bb8a3;
            box-shadow: 0 0 0 #fff;
            padding: 8px 15px;
            height: 60px;
            position: static;
        }

        .mui-bar .mui-btn-link {
            color: #fff;
        }

        .mui-title .ipt-data {
            display: block;
            width: 50%;
            padding-left: 0;
            padding-right: 0;
            font-weight: normal;
            text-align: center;
            margin: 0 auto;
            border-color: #3bb8a3;
            background-color: #3bb8a3;
            color: #fff;
        }

        .box-a .box-hd {
            height: 56px;
            line-height: 56px;
            background-color: #fff;
        }

        .crew-list {
            padding: 0 15px;
        }

        .mui-input-group {
            background-color: transparent;
        }

        input[type='text'], input[type='number'] {
            padding: 10px 0px;
            text-align: right;
            color: #3bb8a3;
            margin-right: 5px;
            width: 100px;
            font-size: 18px;
        }

        .ri {
            float: right;
            width: 54%;
            text-align: right;
        }

        .ri span {
            font-size: 16px;
            color: #666;
            margin-right: 15px;
        }

        .ri .lbfocus {
            width: auto;
            float: none;
            font-size: 16px;
            color: #666;
            margin-right: 15px;
            padding: 0;
        }

        .crew-list .mui-input-group .mui-input-row:after {
            left: 0;
            right: 0;
            background-color: #dadad8;
        }

        .mui-input-row, .mui-input-row:last-child:after {
            height: 0;
        }

        .mui-input-row label {
            width: 46%;
            padding-right: 0;
        }

        .tips {
            padding-top: 60%;
            line-height: 1.6;
        }

        .tips .i-tips {
            color: #bbbec4;
            font-size: 60px;
        }

        .disable input[type='text'], .disable input[type='number'], input.dis {
            color: #333;
        }

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .mui-input-row label {
                font-size: 15px;
                padding-left: 10px;
            }

            .ri input[type='text'] {
                font-size: 17px;
                width: 84px;
            }

            .ri span, .ri .lbfocus {
                font-size: 14px;
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>

<div class="tips tc">
    <p><i class="mui-icon if i-tips"></i></p>
    <p class="f16 g9">请先填加总数据哟~</p>
</div>
<script>
    function history_back() {
        window.nanzhu.backHome();
    }
</script>
</body>
</html>
