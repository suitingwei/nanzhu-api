<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>剧组人员</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            background-color: #fff;
            font-size: 16px;
        }

        .table-list {
            padding-bottom: 100px;
        }

        .table-list thead th {
            font-weight: normal;
            background-color: #f0f0f0;
            text-align: left;
            padding: 15px;
        }

        .table-list tbody tr td {
            text-align: left;
            padding: 12px 15px;
            position: relative;
        }

        .table-list tbody tr td:after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 1px;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: #e8e8e8;
        }

        .table-list tbody tr td:first-child:after {
            left: 15px;
        }

        .table-list tbody tr td:last-child:after {
            right: 15px;
        }

        .user-wrap {
            position: relative;
        }

        .user-wrap .user-phone {
            color: #3bb8a3;
            text-decoration: underline;
        }

        .user-wrap .i-leader {
            color: #f05f50;
            position: absolute;
            top: -18px;
            right: -26px;
            font-size: 22px;
        }

        .tooltips[tooltip-position="right"] span {
            margin-top: -25px;
            margin-left: 50px;
        }

        .tooltips[tooltip-position="top"] span {
            bottom: 35px;
            margin-left: -30px;
        }

        .tooltips[tooltip-position="bottom"] span {
            top: 12px;
            margin-left: -42px;
            width: 130px;
            margin-bottom: 30px;
        }

        .tooltips[tooltip-position="bottom"] span a {
            color: #fff;
            font-size: 16px;
            display: block;
            margin: 5px 0;
            position: relative;
            z-index: 100;
        }

        .ajax-running {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
        }

        .ajax-running p {
            color: #fff;
            text-align: center;
            margin-top: 50%;
            font-size: 16px;
            background: rgba(0,0,0,0.8);
            width: 100px;
            margin: 50% auto;
            border-radius: 50px;
            padding: 5px 0;
        }

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .mui-btn-danger {
                padding: 3px 8px;
            }
        }
    </style>
</head>
<body>
<div class="table-list">

    <table class="mui-table">
        <thead>
        <tr>
            <th width="25%">组别</th>
            <th width="25%">职位</th>
            <th width="25%">姓名</th>
            <th width="25%">操作</th>
        </tr>
        </thead>
        <tbody>

        @foreach($members as $user)
            <tr>
                <td>
                    <div class="tooltips" tooltip-position="right">
                        <div class="mui-ellipsis">{{ $user->groupNamesInMovie($movie->FID) }}</div>
                        <span>
                            {{ $user->groupNamesInMovie($movie->FID) }}
                        </span>
                    </div>
                </td>
                <td>
                    <div class="tooltips" tooltip-position="top">
                        <div class="mui-ellipsis">{{ $user->positionInMovie($movie->FID) }}</div>
                        <span>
                            {{ $user->positionInMovie($movie->FID) }}
                        </span>
                    </div>
                </td>
                <td>
                    <div class="user-wrap">
			<?php $sharePhonesInMovie = $user->sharePhonesInMovie($movie->FID) ?>
                        @if(count($sharePhonesInMovie)>0)
				<div class="tooltips user-phone" tooltip-position="bottom">
				    <div class="mui-ellipsis">{{ $user->FNAME }}</div>
				    <span>
					@foreach($user->sharePhonesInMovie($movie->FID) as $phone)
					    <a href='tel:{{$phone->FPHONE  }}'>{{ $phone->FPHONE }}</a>
					@endforeach
				    </span>
				</div>
			@else
                            <div class="mui-ellipsis">{{$user->FNAME}}</div>
                        @endif
                    </div>
                </td>
                <td>
                    @if($user->isNotAdminOfMovie($movie->FID))
                        <button class="mui-btn mui-btn-outlined mui-btn-danger"
                                onclick="deleteUserFromMovie(
                                        '{{ $user->FID }}',
                                        '{{ $movie->FID }}',
                                        '{{ $user->groupNamesInMovie($movie->FID) }}',
                                        '{{ $user->positionInMovie($movie->FID) }}',
                                        '{{ $user->FNAME }}')">删除
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

<div id="ajax_processing" style="display: none" class="ajax-running">
    <p>删除中...</p>
</div>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    $('.tooltips span').hide();
    $('.tooltips').on('click', function (e) {
        $('.tooltips span').fadeOut('fast');
        $(e.target).next().fadeIn('fast');
    });


    $( document ).ajaxStart(function() {
        $("#ajax_processing").show();
    });

    $( document ).ajaxComplete(function() {
        $("#ajax_processing").hide();
    });

    /**
     * 从剧组删除一个用户
     * @param userId
     * @param movieId
     */
    function deleteUserFromMovie(userId, movieId, groupNames, position, userName) {

        var btnArray = ['否', '是'];
        mui.confirm('确认将' + groupNames + '部门的' + position + ':' + userName + '删除么?', '提示', btnArray, function (e) {
            if (e.index == 1) {
                var url = '/mobile/users/' + userId + '/exit_movie/' + movieId;
                $.post(url, function (responseData) {
                    if (responseData.success) {
                        window.location.reload();
                    }
                })
            }
        })
    }

    function history_back() {
        window.history.back();

        return true;
    }
</script>
</body>
</html>
