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
    </style>
</head>
<body>

<footer class="mui-bar"></footer>
<div class="contact ui-content">
    <div id='list' class="mui-indexed-list">

        <div class="mui-indexed-list-search mui-input-row mui-search">
            <input type="search" class="mui-input-clear mui-indexed-list-search-input" placeholder="搜索">
        </div><!--/end-->

        @include('mobile.template.chars_lists')

        <div class="mui-indexed-list-alert"></div>

        <div class="mui-indexed-list-inner">
            <div class="mui-indexed-list-empty-alert">没有搜索到~</div>
            <ul class="mui-table-view">

                @foreach($groupUsers as $groupKey => $group)

                    <li data-group="{{ $groupKey }}"
                        class="mui-table-view-divider mui-indexed-list-group">{{ $groupKey }}</li>

                    @foreach($group as $contact)
                        <li data-value="ALM" data-tags="{{ $contact->name }}"
                            class="mui-table-view-cell mui-media mui-checkbox mui-indexed-list-item">
                            <a class="link-tel"
                               @if($contact->is_open == 1)
                               spare-phones="{{ json_encode($contact->sparePhones()->checked()->get()->all()) }}"
                               onclick="showTelAlert(this)"
                                    @endif>
                                <span class="pic">
                                    <img class="mui-media-object mui-pull-left" src="{{ $contact->user_pic_url }}">
                                </span>
                                <div class="mui-media-body">
                                    <span class="tel-name">{{ $contact->name }}</span>
                                    <p class="mui-ellipsis">职位:{{ $contact->position }}</p>
                                </div>
                            </a>
                            <div class="btn-op">
                                <a type="button" class="mui-btn mui-btn-link btn-del">
                                    <a class="if i-del"
                                            url="/mobile/groups/{{ $groupId }}/member/{{ $contact->FID }}/delete?user_id={{ $userId }}"
                                            onclick="deleteGroupUser(this)">

                                    </a>
                                </a>
                            </div>
                        </li>
                    @endforeach
                @endforeach

            </ul>
        </div><!--/end-->

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

        //calc hieght
        list.style.height = (document.body.offsetHeight - footer.offsetHeight) + 'px';

        //create
        window.indexedList = new mui.IndexedList(list);


    });

    /**
     * 部门管理删除成员
     *
     * @param deleteBtn
     *
     */
    function deleteGroupUser(deleteBtn) {

        var url = $(deleteBtn).attr('url');
        var btnArray = ['否', '是'];
        mui.confirm('您是否要删除此人？', '提示', btnArray, function (e) {
            if (e.index == 1) {
                $.get(url, function (responseData) {
                    if(responseData.success){
                        window.location.href=responseData.data.redirect_url;
                    }else{
                        mui.alert(responseData.msg);
                    }
                })

            }
        })
    }

    function history_back(){
        window.history.back();
        return true;
    }
</script>
</body>
</html>