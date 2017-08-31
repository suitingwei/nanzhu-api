<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>部门管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <link rel="stylesheet" href="/assets/mobile/css/ui.css">
    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }

        .mui-btn.btn-yes {
            opacity: .6;
            border-color: #ccc;
            background-color: #fff;
            color: #999;
        }

        .contact .mui-table-view p {
            width: 60%;
        }

        .list-join .mui-table-view-cell:after {
            right: 15px;
            background-color: #e8e8e8;
        }

        .list-join .mui-table-view:after {
            background-color: transparent;
        }

        .list-join .mui-col-xs-7 {
            padding: 10px 0 10px 15px;
        }

        .list-join .mui-col-xs-5 {
            padding-right: 15px;
        }

        .list-join .mui-col-xs-5 .mui-btn {
            margin-top: 5px;
            margin-left: 7px;
            color: #999;
            border-color: #999;
        }

        .list-join .mui-col-xs-5 .mui-btn-success {
            color: #3bb8a3;
            border-color: #3bb8a3;
        }

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) {
            .bar-footer2 {
                height: 42px;
            }

            .bar-footer2 .mui-btn {
                font-size: 16px;
                line-height: 30px;
            }

            .list-join .mui-col-xs-5 .mui-btn {
                margin-left: 2px;
            }
        }
    </style>
</head>
<body>
<footer class="mui-bar mui-bar-footer bar-footer2">
    <div class="mui-row">
        <div class="mui-col-xs-6">
            <a href="/mobile/groups/{{ $group->FID }}/delete?user_id={{ $user->FID }}"
               class="mui-btn mui-btn-block mui-btn-danger">删除</a>
        </div>
        <div class="mui-col-xs-6">
            <button onclick="wechatShare(this)"
                    url="/mobile/groups/{{ $group->FID }}/wechat_share?movie_id={{ $movieId }}&user_id={{ $user->FID }}"
                    class="mui-btn mui-btn-block mui-btn-success">微信邀请
            </button>
        </div>
    </div>
</footer><!--/end-->

@if($user->isLeaderOfGroup($group->FID))
    <div id="listJoin" class="list-join">
        <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
            @foreach($group->unAuditedApplies() as $apply)
                <li class="mui-table-view-cell">
                    <div class="mui-table">
                        <div class="mui-table-cell mui-col-xs-7">
                            <div class="f16 mui-ellipsis">{{ $apply->user->FNAME }}</div>
                            <p class="g6 mui-ellipsis">申请加入{{ $apply->group->FNAME }}部门</p>
                        </div>
                        <div class="mui-table-cell mui-col-xs-5 mui-text-right">
                            <button type="button" class="mui-btn mui-btn-outlined mui-btn-success"
                                    onclick="approveJoinGroup(this,'{{ $user->FID }}','{{ $apply->id }}')">同意
                            </button>
                            <button type="button" class="mui-btn mui-btn-outlined"
                                    onclick="declineJoinGroup(this,'{{ $user->FID }}','{{ $apply->id }}')">拒绝
                            </button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div><!--/end-->
@endif
<div class="contact ui-content">
    <div id='list' class="mui-indexed-list">

        <div class="mui-indexed-list-search mui-input-row mui-search">
            <input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="搜索">
        </div>

        @include('mobile.template.chars_lists')

        <div class="mui-indexed-list-alert"></div>

        <div class="mui-indexed-list-inner">
            <div class="mui-indexed-list-empty-alert">没有搜索到~</div>
            <ul class="mui-table-view">
                @foreach($groupedUsers as $groupKey => $groupUsers)

                    <li data-group="{{ $groupKey }}"
                        class="mui-table-view-divider mui-indexed-list-group">{{ $groupKey }}</li>

                    @foreach($groupUsers as $groupUser)
                        <li data-value="ALM" data-tags="{{ $groupUser->name }}"
                            class="mui-table-view-cell mui-media mui-checkbox mui-indexed-list-item">
                            <a class="link-tel"
                               @if($groupUser->is_open == 1)
                               spare-phones="{{ json_encode($groupUser->sparePhones()->checked()->get()->all()) }}"
                               onclick="showTelAlert(this)"
                                    @endif>
                                <span class="pic">
                                    <img class="mui-media-object mui-pull-left" src="{{ $groupUser->user_pic_url }}">
                                </span>
                                <div class="mui-media-body">
                                    <span class="tel-name">{{ $groupUser->name }}</span>
                                    <p class="mui-ellipsis">职位:{{ $groupUser->position }}</p>
                                </div>
                            </a>
                            <div class="btn-op">
                                <a class="mui-btn mui-btn-outlined mui-btn-success btn-yes"
                                   @if( ! $groupUser->hadJoinedContacts())
                                   style="display:none;"
                                   @endif
                                   url="/mobile/groups/{{$group->FID}}/removeContact/{{ $groupUser->FID }}"
                                   onclick="removePhoneContacts(this)">已加入通讯录</a>

                                <a class="mui-btn mui-btn-outlined mui-btn-success"
                                   @if($groupUser->hadJoinedContacts())
                                   style="display:none;"
                                   @endif
                                   url="/mobile/groups/{{$group->FID}}/addContact/{{ $groupUser->FID }}"
                                   onclick="addPhoneContacts(this)">未加入通讯录</a>
                            </div>
                        </li>
                    @endforeach
                @endforeach

            </ul>
        </div>

    </div><!--/end-->
</div><!--/end-->

<script src="/assets/javascripts/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script src="/assets/mobile/js/ui.indexedlist.js"></script>
<script>
    mui.init();
    mui.ready(function () {

        var list = document.getElementById('list');
        var footer = document.querySelector('footer.mui-bar');
        var listJoin = document.getElementById('listJoin');

        //calc hieght
        list.style.height = (document.body.offsetHeight - footer.offsetHeight - listJoin.offsetHeight) + 'px';

        //create
        window.indexedList = new mui.IndexedList(list);

    });

    /**
     * 加入通讯录
     * @param aTag
     */
    function addPhoneContacts(aTag) {
        var url = $(aTag).attr('url');
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function (responseData) {
                if (responseData.success) {
                    $(aTag).css('display', 'none').prev('a').css('display', 'block');
                    //setTimeout(window.location.href = '/mobile/groups/manage?movie_id=' + "{{ request('movie_id') }}" + '&user_id={{ $user->FID }}', 1000);
                }
            }
        });
    }

    /**
     * 移除通讯录
     * @param aTag
     */
    function removePhoneContacts(aTag) {
        var url = $(aTag).attr('url');
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function (responseData) {
                if (responseData.success) {
                    $(aTag).css('display', 'none').next('a').css('display', 'block');
                    // setTimeout(window.location.href = '/mobile/groups/manage?movie_id=' + "{{ request('movie_id') }}" + '&user_id={{ $user->FID }}', 1000);
                }
            }
        });
    }

    /**
     * 如果是部门管理index界面跳转到工作台
     */
    function history_back() {
        //如果是从部门列表界面进来的,返回部门列表
        if ('{{ request()->has('group_id') }}' == '1') {
            window.history.back();
            return true;
        } else {
            //如果是直接进来,返回工作台
            window.nanzhu.backHome();
            return true;
        }
    }

    /**
     * 微信分享部门管理添加
     */
    function wechatShare(shareButton) {
        var json = {
            title: '好友邀请',
            cover: 'https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1473824619&di=10eff76e4504ebc52fa03fe68b770ab7&src=http://d.hiphotos.baidu.com/image/pic/item/0ff41bd5ad6eddc492d491153ddbb6fd52663328.jpg',
            content: '我是{{ $user->FNAME }},现邀您一起体验全新的剧组神器,《南竹通告单+》',
            url: '{{ env('APP_URL') }}' + $(shareButton).attr('url')
        };

        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if (isIOS) {
            bumenShare(JSON.stringify(json));
        }
        if (isAndroid) {
            window.nanzhu.wechatShareAndroid(JSON.stringify(json));
        }

    }

    function jumpBack() {
        window.location.href = '/mobile/groups/manage?movie_id=' + "{{ request('movie_id') }}" + '&user_id={{ $user->FID }}';
    }

    /**
     * 同意入组申请
     */
    function approveJoinGroup(approveBtn, userId, joinGroupId) {
        var url = '/mobile/users/' + userId + '/approve_join_group/' + joinGroupId;
        $.post(url, function (response) {
            if (response.success) {
                window.location.reload();
            }
        });
    }

    /**
     * 拒绝入组申请
     */
    function declineJoinGroup(declineBtn, userId, joinGroupId) {
        var url = '/mobile/users/' + userId + '/decline_join_group/' + joinGroupId;
        $.post(url, function (response) {
            if (response.success) {
                window.location.reload();
            }
        });
    }
</script>
</body>
</html>