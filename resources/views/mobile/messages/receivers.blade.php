<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>接收详情</title>
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
    padding: 18px 15px;
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

/*.user-wrap .user-phone {*/
    /*color: #3bb8a3;*/
    /*text-decoration: underline;*/
/*}*/

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

.tooltips-time[tooltip-position="top"] span {
    margin-left: -78px;
    width: 130px;
    color: #fff;
}

.tooltips-time[tooltip-position="top"] span:after {
    margin-left: 22px;
}
.txt-gray {
    color: #d6d5d5;
}
.table-fixed {
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 10;
}
.table-fixed-body {
    margin-top: 50px;
}
</style>
</head>
<body>
<div class="table-list">
    <div class="table-fixed">
        <table class="mui-table">
            <thead>
                <tr>
                    <th width="25%">组别</th>
                    <th width="25%">职位</th>
                    <th width="25%">姓名</th>
                    <th width="25%">接收</th>
                </tr>
            </thead>
        </table>
    </div>
    <table class="table-fixed-body mui-table">
        <tbody>
        <?php
        function cmp($a, $b)
        {
            return strcmp($a['groupid'], $b['groupid']);
        }
        usort($un_receivers, "cmp");
        ?>
        @foreach($un_receivers as $receiver)
            <tr>
                <td>
                    <?php $unReceiveUser = App\User::find($receiver['uid']) ?>
                    <div class="tooltips" tooltip-position="right">
                        <?php $firstJoinGroup = $unReceiveUser->groupsInMovie($movieId)->first(); ?>
                        <div class="mui-ellipsis">@if($firstJoinGroup){{ $firstJoinGroup->FNAME}} @endif</div>
                        <span>{{ $unReceiveUser->groupNamesInMovie($movieId) }}</span>
                    </div>
                </td>
                <td>
                    <div class="tooltips" tooltip-position="top">
                        <div class="mui-ellipsis">{{$receiver['job']}}</div>
                        <span>{{$receiver['job']}}</span>
                    </div>
                </td>
                <td>
                    <div class="user-wrap">
                        @if($receiver['leader']==$receiver['uid'])<i class="mui-icon if i-leader"></i>@endif
                        <?php $phones = \DB::table("t_biz_sparephone")->where("FGROUPUSERID",
                                $receiver['group_user_id'])->get();?>

                        @if($unReceiveUser->isSharePhonesInMovieOpened($movieId))
                            <div class="tooltips user-phone" tooltip-position="top">
                                <div class="mui-ellipsis">{{$receiver['username']}}</div>
                                <span>
                                    {{$receiver['username']}}
                                </span>
                                {{--<span>--}}
                                    {{--@foreach($unReceiveUser->sharePhonesInMovie($movieId)as $phone)--}}
                                        {{--@if($phone->FChecked)--}}
                                            {{--<a href='tel:{{$phone->FPHONE}}'>{{$phone->FPHONE}}</a>--}}
                                        {{--@endif--}}
                                    {{--@endforeach--}}
                                {{--</span>--}}
                            </div>
                        @else
                            <div class="mui-ellipsis">{{$receiver['username']}}</div>
                        @endif
                    </div>
                </td>
                <td>
                    @if($receiver['created_at'] != $receiver['updated_at'])
                        <div class="tooltips tooltips-time" tooltip-position="top">
                            <div class="mui-ellipsis f14">{{date("H:i",strtotime($receiver['updated_at']))}}</div>
                            <span>{{date("Y-m-d H:i:s",strtotime($receiver['updated_at']))}}</span>
                        </div>
                        @else
                        <span class="txt-gray">未读</span>
                    @endif
                </td>
            </tr>
        @endforeach

        @foreach($receivers as $receiver)
            <tr>
                <td>
                    <?php $user = App\User::find($receiver['uid']) ?>
                    <div class="tooltips" tooltip-position="right">
                        <?php $firstJoinGroup = $user->groupsInMovie($movieId)->first(); ?>
                        <div class="mui-ellipsis">@if($firstJoinGroup){{ $firstJoinGroup->FNAME}}  @endif</div>
                        <span>{{ $user->groupNamesInMovie($movieId) }}</span>
                    </div>
                </td>
                <td>
                    <div class="tooltips" tooltip-position="top">
                        <div class="mui-ellipsis">{{$receiver['job']}}</div>
                        <span>{{$receiver['job']}}</span>
                    </div>
                </td>
                <td>
                    <div class="user-wrap">
                        @if($receiver['leader']==$receiver['uid'])<i class="mui-icon if i-leader"></i>@endif
                        <?php $phones = \DB::table("t_biz_sparephone")->where("FGROUPUSERID",
                                $receiver['group_user_id'])->get();?>
                        @if($user->isSharePhonesInMovieOpened($movieId))
                            <div class="tooltips user-phone" tooltip-position="top">
                                <div class="mui-ellipsis">{{$receiver['username']}}</div>
                                <span>
                                    {{$receiver['username']}}
                                </span>
                                {{--<span>--}}
                                    {{--@foreach($user->sharePhonesInMovie($movieId)as $phone)--}}
                                        {{--@if($phone->FChecked)--}}
                                            {{--<a href='tel:{{$phone->FPHONE}}'>{{$phone->FPHONE}}</a>--}}
                                        {{--@endif--}}
                                    {{--@endforeach--}}
                                {{--</span>--}}
                            </div>

                        @else
                            <div class="mui-ellipsis">{{$receiver['username']}}</div>
                        @endif
                    </div>
                </td>
                <td>
                    @if($receiver['created_at'] != $receiver['updated_at'])
                        <div class="tooltips tooltips-time" tooltip-position="top">
                            <div class="mui-ellipsis f14">{{date("H:i",strtotime($receiver['updated_at']))}}</div>
                            <span>{{date("Y-m-d H:i:s",strtotime($receiver['updated_at']))}}</span>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table><!--/end-->

</div>

<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    $('.tooltips span').hide();
    $('.tooltips').on('click', function (e) {
        $('.tooltips span').fadeOut('fast');
        $(e.target).next().fadeIn('fast');
    });

    function history_back() {
        //如果是从原生入口进来,没有浏览记录,所以直接调用功名的安卓js返回
        if ('{{ $from }}' == 'native') {
            window.nanzhu.backHome();
        } else {
            window.history.back();
            return true;
        }
    }
</script>
</body>
</html>