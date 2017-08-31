<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>申请加入</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            background: #fff;
        }

        .mui-row {
            padding: 10px 10px 10px;
        }

        .wrap {
            padding: 10px;
        }

        .wrap .mui-btn {
            margin-bottom: 0;
            padding: 12px 0;
            height: 52px;
            overflow: hidden;
        }

        .group-active {
            border-color: #3bb8a3;
            color: #3bb8a3;
        }

        .group-active:before {
            font-family: uii;
            font-size: 28px;
            font-weight: normal;
            line-height: 1;
            text-decoration: none;
            border-radius: 0;
            background: none;
            -webkit-font-smoothing: antialiased;
            content: '\e442';
            position: absolute;
            right: 3px;
            bottom: 3px;
        }

        .group-join {
            border-color: #e34728;
            color: #e34728;
        }

        .mui-popup-title {
            display: none;
        }

        .thistips {
            padding: 10px 20px;
            background: #f0f0f0;
        }

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .wrap .mui-btn {
                padding: 10px 0;
                font-size: 16px;
            }
        }
    </style>
<body>

<div class="thistips">
    <span style="color:#3bb8a3">绿色表示已加入部门,</span>
    <span style="color:#e34728">红色代表待审核状态</span>
</div>

<div class="">
    <div class="group-list group-create">
        <ul class="list-unstyled mui-row">
            @foreach($movieAllGroups as $group)
                <li class="mui-col-xs-6">
                    <div class="wrap">
                        @if($user->isInGroup($group->FID))
                            <div class="mui-btn mui-btn-outlined mui-btn-block group-active"
                                 onclick="exitGroup('{{ $user->FID }}','{{ $group->FID }}','{{ $group->FNAME }}','{{ $movie->FID }}')">
                                <span class="gfname">{{$group->FNAME}}</span>
                            </div>
                        @elseif($user->hadTryJoinedGroup($group->FID) && $user->isJoinGroupAuditting($group->FID))
                            <div class="mui-btn mui-btn-outlined mui-btn-block group-join"
                                 onclick="showAudittingAlert('{{ $group->FNAME }}')">
                                <span class="gfname">{{$group->FNAME}}</span>
                            </div>
                        @else
                            <div class="mui-btn mui-btn-outlined mui-btn-block notjoin"
                                 onclick="joinGroup('{{$user->FID}}','{{ $group->FID }}','{{ $group->FNAME }}','{{ $movie->FID }}')">
                                <span class="gfname">{{$group->FNAME}}</span>
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

</body>
<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script>
    /**
     * 退出部门
     * @param  groupId
     */
    function exitGroup(userId, groupId, groupName, movieId) {

        var btnArray = ['否', '是'];
        mui.confirm('是否退出' + groupName + '部门？', '提示', btnArray, function (e) {
            if (e.index == 1) {
                var url = '/mobile/users/' + userId + '/exit_group/' + groupId;
                $.post(url, {movie_id: movieId}, function (responseData) {
                    mui.alert(responseData.msg)
                    if (responseData.success) {
                        window.location.reload();
                    }
                })

            }
        });
    }

    /**
     * Join the group.
     */
    function joinGroup(userId, groupId, groupName, movieId) {
        var btnArray = ['否', '是'];
        mui.confirm('是否申请加入' + groupName + '部门？', '提示', btnArray, function (e) {
            if (e.index == 1) {
                var url = '/mobile/users/' + userId + '/join_other_group';
                $.ajax({
                    url: url,
                    data: {
                        movie_id: movieId,
                        user_id: userId,
                        join_groups: groupId,
                    },
                    method: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        var msg = '<p class="g3 f18">申请成功</p><div class="g6 f16">等待部门长审核</div>';
                        mui.alert(msg, '提示', function () {
                            if (response.success) {
                                setTimeout('window.location.reload()', 1000);
                            }
                        });
                    }
                })
            }
        });
    }

    /**
     * Show the group join auditting alert.
     * @param groupName
     */
    function showAudittingAlert(groupName) {
        mui.alert('您已经申请加入' + groupName + '部门,请耐心等待');
    }

    function history_back() {
        window.history.back();
        return true;
    }
</script>
</html>




