{{--可用于所有通讯录界面,右侧的A-Z搜索栏--}}
{{--参考:剧组通讯录,公开电话等--}}
<div class="mui-indexed-list-bar">
    @foreach(\App\Utils\StringUtil::charsWithSharp() as $char)
        <a> {{ $char }}</a>
    @endforeach
</div>