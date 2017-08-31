<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>权限与设置</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        body {
            background-color: #fff;
        }

        .if {
            font-size: 30px;
            top: -2px;
        }

        .crew-no {
            padding: 80px 0;
            line-height: 1.6;
        }

        .crew-no i {
            color: #bbbec4;
            font-size: 50px;
            margin-bottom: 10px;
        }

        .crew-menu {
            border-top: 1px solid #f0f0f0;
        }

        .crew .mui-media:nth-child(3n+3) .crew-menu-wrap {
            border-right: 1px solid #f0f0f0;
        }

        .crew .mui-media:nth-child(2n) .crew-menu-wrap {
            border-right: 0;
        }

        .crew-menu li:last-child {
            height: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .i-screens:before {
            content: "\e679"
        }
    </style>
</head>
<body>
<div class="crew bgwh">
    <p style="padding-left: 1em;line-height: 2em;padding-top: 0.8em;color: #333;">权限与设置</p>
    <ul class="crew-menu mui-table-view mui-grid-view mui-grid-9">
        <li class="mui-table-view-cell mui-media mui-col-xs-6">
            <div class="crew-menu-wrap">
                <a href="/mobile/permissions/contact/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=剧组通讯录查看权限&percent={{ $contactPercent }}">
                    <i class="mui-icon if i-book"></i>
                    <div class="mui-media-body">剧组通讯录查看</div>
                </a>
            </div>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-6">
            <div class="crew-menu-wrap">
                <a href="/mobile/permissions/receive/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=接受详情查看权限&percent={{ $receivePercent }}">
                    <i class="mui-icon if i-blist"></i>
                    <div class="mui-media-body">接收详情查看</div>
                </a>
            </div>
        </li>
        @if( version_compare($iosVersion,'3.6.3','>=') ||
             version_compare($androidVersion,'3.6.2','>=')
        )
            <li class="mui-table-view-cell mui-media mui-col-xs-6">
                <div class="crew-menu-wrap">
                    <a href="/mobile/permissions/previous-prospect/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=前期勘景查看&percent={{ $previousProspectPercent}}">
                        <i class="mui-icon if i-screens"></i>
                        <div class="mui-media-body">勘景与资料查看</div>
                    </a>
                </div>
            </li>
        @endif


        @if( version_compare($iosVersion,'3.3.6','>') ||
             version_compare($androidVersion,'3.3.6','>')
        )
            <li class="mui-table-view-cell mui-media mui-col-xs-6">
                <div class="crew-menu-wrap">
                    <a href="/mobile/permissions/reference-plan/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=参考大计划查看&percent={{ $planPercent}}">
                        <i class="mui-icon if i-bigplan"></i>
                        <div class="mui-media-body">参考大计划查看</div>
                    </a>
                </div>
            </li>
        @endif
        @if( version_compare($iosVersion,'3.4.1','>=') ||
             version_compare($androidVersion,'3.4.1','>=')
        )
            <li class="mui-table-view-cell mui-media mui-col-xs-6">
                <div class="crew-menu-wrap">
                    <a href="/mobile/permissions/daily-report/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=场记日报表查看&percent={{ $dailyReportPercent }}">
                        <i class="mui-icon if i-bigplan"></i>
                        <div class="mui-media-body">场记日报表查看</div>
                    </a>
                </div>
            </li>
        @endif
        <li class="mui-table-view-cell mui-media mui-col-xs-6">
            <div class="crew-menu-wrap">
                <a href="/mobile/permissions/progress/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=拍摄进度查看权限&percent={{ $progressPercent }}">
                    <i class="mui-icon if i-rec"></i>
                    <div class="mui-media-body">拍摄进度查看</div>
                </a>
            </div>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-6">
            <div class="crew-menu-wrap">
                <a href="/mobile/permissions/transfor?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=最高权限移交">
                    <i class="mui-icon if i-king"></i>
                    <div class="mui-media-body">最高权限移交</div>
                </a>
            </div>
        </li>
        <li></li>
    </ul><!--/end-->

</div><!--/end-->

<div class="crew bgwh">
    <p style="padding-left: 1em;line-height: 2em;padding-top: 0.8em;color:#333">个性化设置</p>
    <ul class="crew-menu mui-table-view mui-grid-view mui-grid-9">
        @if( version_compare($iosVersion,'3.6.4','>=') ||
             version_compare($androidVersion,'3.6.3','>=')
        )
            <li class="mui-table-view-cell mui-media mui-col-xs-6">
                <div class="crew-menu-wrap">
                    <a href="/mobile/permissions/groupuser-feedback/create?movie_id={{ request('movie_id') }}&user_id={{ request('user_id') }}&androidVer={{ $androidVersion }}&title=组员反馈查看&percent={{ $previousProspectPercent}}">
                        <i class="mui-icon if i-screens"></i>
                        <div class="mui-media-body">组员反馈查看</div>
                    </a>
                </div>
            </li>
            {{--<li class="mui-table-view-cell mui-media mui-col-xs-6">--}}
            {{--<div class="crew-menu-wrap">--}}
            {{--<a href="/mobile/daodao-location?title=定位查看">--}}
            {{--<i class="mui-icon if i-screens"></i>--}}
            {{--<div class="mui-media-body">定位查看</div>--}}
            {{--</a>--}}
            {{--</div>--}}
            {{--</li>--}}
        @endif
        <li></li>
    </ul><!--/end-->

</div><!--/end-->
<script src="/assets/javascripts/jquery.min.js"></script>
<script>

    $(document).ready(function () {
        window.nanzhu.dissmiss();
    });

    /**
     * 如果是权限与设置index界面跳转到工作台
     */
    function history_back() {
        window.nanzhu.backHome();
    }

</script>
</body>
</html>
