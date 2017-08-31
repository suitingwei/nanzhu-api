<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>剧组通知</title>
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/assets/mobile/css/ui.css">
<style>
.msg {
    padding-bottom: 60px;
}
.card-header, .card-footer {
    min-height: 20px;
}
.card-header .card-header-t {
    margin: 8px 0;
}
.card-header .card-header-t a {
    display: block;
}

.msg .card-img:before {
    font-family: "if" !important;
    font-size: 50px;
    font-weight: normal;
    font-style: normal;
    line-height: 1;
    display: inline-block;
    -webkit-font-smoothing: antialiased;
    -webkit-text-stroke-width: 0.2px;
    -moz-osx-font-smoothing: grayscale;
    content: "\e629";
    position: absolute;
    right: -2px;
    top: -1px;
    color: #f15252;
}

.msg .ryes:before {
    content: "\e62a";
    color: #ccc;
}

.card-content a {
    display: block;
}

.card-txt {
    padding-bottom: 0;
}

.card-txt p {
    white-space: pre-wrap;
    word-wrap: break-word;
    margin-bottom: 14px;
    font-size: 16px;
    line-height: 1.8;
}

.pic {
    height: 200px;
    overflow: hidden;
    margin-bottom: 12px;
}

.title-receive {
    color: #666;
    font-size: 14px;
    padding: 6px 0 0;
}

.msg-cancel .card-header-t a, .msg-cancel .title-receive, .msg-cancel .card-txt p {
    color: #ccc;
}

.msg-cancel .card-footer, .msg-cancel .card-content-inner img {
    opacity: 0.3;
}

.card-footer .mui-row .mui-col-xs-4:after,
.card-footer .mui-row .mui-col-xs-4:first-child:after {
    font-family: if;
    font-size: 60px;
    line-height: 1;
    position: absolute;
    top: 52%;
    display: inline-block;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    text-decoration: none;
    color: #efefef;
    -webkit-font-smoothing: antialiased;
    right: -30px;
    content: '\e603';
}

.card-footer button:disabled {
    background-color: transparent;
}

.card-footer .mui-row .mui-col-xs-4:last-child:after {
    display: none;
}

.msg .msg-cancel-icon {
    background: url('/assets/mobile/img/icon-cancel.png') no-repeat center center;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    background-size: 30% auto;
    position: absolute;
    z-index: 9;
    text-align: center;
}
</style>
</head>
<script language=javascript>
    function scrollToFooter() {
        document.getElementById("footer").scrollIntoView();
    }
</script>
<body onload="scrollToFooter()">

<div id="wrapper" class="msg">
    <div class="lists">
        <div class="datalist">

            @foreach($messages  as $key => $message)
                <div class="msg-date tc g9">
                    {{$message['date']}}
                </div><!--/end-->

                @foreach($message['data'] as $m)
                    <div class="card card-img @if($m->r_is_read==1) ryes @endif @if($m->is_undo==1) msg-cancel @endif" >
                        @if($m->is_undo==1)
                            <div class="msg-cancel-icon"></div>
                        @endif
                        <div class="card-header">
                            <div class="card-header-t">
                                <span class="f18">
                                    @if($m->is_undo == 1)
                                        <a href="javascript:;">{{ $m->title }}</a>
                                    @else
                                        <a href="/mobile/messages/{{$m->id}}?user_id={{$user_id}}&type={{ request('type') }}&androidVer={{ $androidVersion }}&title={{ request('title') }}">{{($m->title)}}</a>
                                    @endif
                                </span>
                                @if($is_show_receivers > 0)
                                    @if($m->is_undo == 1)
                                        <a href="javascript:;">
                                            <div class="title-receive">
                                                接收详情：{{ $m->readRate() }}
                                            </div>
                                        </a>
                                    @else
                                        <a href="/mobile/messages/{{$m->id}}?user_id={{$user_id}}&type={{ request('type') }}&androidVer={{ $androidVersion }}&title={{ request('title') }}">
                                            <div class="title-receive">
                                                接收详情：{{ $m->readRate() }}
                                            </div>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-content-inner">
                                <a
                                        @if($m->is_undo ==1)
                                        href="javascript:;"
                                        @else
                                        href="/mobile/messages/{{$m->id}}?user_id={{$user_id}}&type={{ request('type') }}&androidVer={{ $androidVersion }}&title={{ request('title') }}"
                                        @endif
                                >
                                    @if(isset($m->pictures()[0]))
                                        <div class="pic"><img src="{{$m->pictures()[0]}}"/></div>
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="mui-row tc">

                                @if( version_compare($iosVersion,'3.3.6','>=') ||
                                     version_compare($androidVersion,'3.3.6','>=')
                                )
                                    <div class="mui-col-xs-4">
                                        <button onclick="transToChatGroup('{{ request('type') }}','{{ $m->title }}','{{ request()->root() }}/mobile/messages/{{$m->id}}?user_id={{$user_id}}&type={{ request('type') }}&title={{ request('title') }}')"
                                                @if($m->is_undo ==1) disabled @endif
                                                class="mui-btn-link mui-btn-block">转至消息
                                        </button>
                                    </div>
                                @endif
                                @if($is_show_receivers > 0)
                                    <div @if($m->is_undo==1 ||
                                             App\Models\GroupUser::is_tongchou($movie_id,$user_id) ||
                                             App\Models\GroupUser::is_zhipian($movie_id,$user_id) ||
                                             App\Models\GroupUser::is_director($movie_id,$user_id) ||
                                             $user_id == 21906
                                          )
                                         class="mui-col-xs-4"
                                         @else
                                         class="mui-col-xs-4"
                                            @endif>
                                        <a
                                                @if($m->is_undo ==1)
                                                href="javascript:;"
                                                @else
                                                href="/mobile/messages/{{$m->id}}/receivers?movie_id={{ $movie_id }}&androidVer={{ $androidVersion }}"
                                                @endif
                                                class="mui-btn-link mui-btn-block">接收详情</a>
                                    </div>
                                @endif
                                @if($m->is_undo==1)
                                    <div class="mui-col-xs-4">
                                        <a href="javascript:;" class="mui-btn-link mui-btn-block">已经撤销</a>
                                    </div>
                                @else
                                    @if(App\Models\GroupUser::is_tongchou($movie_id,$user_id) ||
                                        App\Models\GroupUser::is_zhipian($movie_id,$user_id) ||
                                        App\Models\GroupUser::is_director($movie_id,$user_id) ||
                                        $user_id == 21906
                                    )
                                        <div class="mui-col-xs-4">
                                            <button url="/mobile/messages/{{$m->id}}/redo?type={{$type}}&user_id={{$user_id}}&movie_id={{$movie_id}}&title={{request('title')}}"
                                                    class="mui-btn-link mui-btn-block"
                                                    onclick="confirmCancelSend(this,'{{ request('title') }}')">撤销发送
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div><!--/end-->
                @endforeach
            @endforeach
        </div><!--/end-->
    </div><!--/end-->

    <div id="footer"></div>

    @if(App\Models\GroupUser::is_tongchou($movie_id,$user_id) ||
        App\Models\GroupUser::is_zhipian($movie_id,$user_id) ||
        App\Models\GroupUser::is_director($movie_id,$user_id) ||
        $user_id == 21906)
        <div class="fixed">
            <a href="/mobile/users/{{$user_id}}/messages/create?movie_id={{$movie_id}}&type={{$type}}&title={{ request('title') }}"
               class="btn-fixed mui-btn mui-btn-block mui-btn-success">新建</a>
        </div>
    @endif
</div><!-- end -->

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/ui.min.js"></script>
<script src="/assets/mobile/js/dropload.min.js"></script>
<script>
    function history_back() {
        //如果是在剧组通知index界面,返回工作台
        var reg = new RegExp('\/mobile\/users\/[0-9]+\/messages');
        if (reg.exec(window.location.href)) {
            window.nanzhu.backHome();
        }
    }

    $(function () {

        $('.msg').dropload({
            autoLoad: false,
            scrollArea: window,
            domUp: {
                domClass: 'dropload-up',
                domRefresh: '<div class="dropload-refresh g8">↓下拉加载更多</div>',
                domUpdate: '<div class="dropload-update g8">↓下拉加载更多</div>',
                domLoad: '<div class="dropload-load g8"><span class="loading"></span>加载中...</div>'
            },

            loadUpFn: function (me) {
                if (!navigator.onLine) {
                    mui.alert('网络出现问题,请重新检查网络连接');
                } else {
                    var u = navigator.userAgent, app = navigator.appVersion;
                    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
                    if (isAndroid) {
                        $.ajaxSetup({
                            headers: {
                                'app-version':'{{ $androidVersion }}'
                            }
                        });
                    }

                    $.ajax({
                        type: 'GET',
                        url: window.location.href + '&page=' + loadMore(),
                        success: function (data) {
                            var wrapperUl = $("#wrapper").find(".datalist");

                            if (data) {
                                wrapperUl.prepend(data);
                            } else {
                                me.noData();
                            }

                            me.resetload();
                        },
                        error: function (xhr, type) {
                            me.resetload();
                        }
                    });
                }
            },
            threshold: 50
        });
    });

    function loadMore() {
        if (typeof loadMore.page == 'undefined') {
            return loadMore.page = 2;
        }
        else {
            loadMore.page += 1;
            return loadMore.page;
        }
    }


    /**
     * 确认撤销发送
     *
     * @param cancelBtn
     * @param title
     */
    function confirmCancelSend(cancelBtn, title) {

        var url = $(cancelBtn).attr('url');

        var btnArray = ['否', '是'];
        mui.confirm('你是否要撤销该' + title + '?', '提示', btnArray, function (e) {
            if (e.index == 1) {
                $.get(url, function (responseData) {
                    window.location.href = responseData.data.redirect_url;
                });
            }
        });

    }

    /**
     * 转发剧组通知,剧本扉页到聊天消息
     * @param type
     * @param title
     */
    function transToChatGroup(type, title, url) {
        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

        url = encodeURI(url);
        var typeDesc = type == 'juzu' ? '剧组通知' : '剧本扉页';

        if (isIOS) {
            juzuToMsgJS(url, typeDesc + title, type);
        } else {
            window.nanzhu.juzuToMsgJSAndroid(url, typeDesc + title, type);
        }

    }

</script>
</body>
</html>