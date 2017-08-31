@foreach($messages  as $key => $message)
    <div class="msg-date tc g9">
        {{$message['date']}}
    </div><!--/end-->
    @foreach($message['data'] as $m)
        <div class="card card-img @if($m->r_is_read==1) ryes @endif @if($m->is_undo==1) msg-cancel @endif">
            @if($m->is_undo==1)
                <div class="msg-cancel"></div>
            @endif
            <div class="card-header">
                <div class="card-header-t">
                    <span class="f18">
                        <a
                                @if($m->is_undo ==1)
                                href="javascript:;"
                                @else
                                href="/mobile/messages/{{$m->id}}?user_id={{$user_id}}&type={{ request('type') }}&androidVer={{ $androidVersion }}&title={{ request('title') }}">{{($m->title)}}
                            @endif
                        </a>
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
                        <div
                                @if($m->is_undo==1 ||
                                App\Models\GroupUser::is_tongchou($movie_id,$user_id) || App\Models\GroupUser::is_zhipian($movie_id,$user_id) || $user_id == 21906
                                )
                                class="mui-col-xs-4"
                                @else
                                class="mui-col-xs-4"
                                @endif
                        >
                            <a
                                    @if($m->is_undo ==1)
                                    href="javascript:;"
                                    @else
                                    href="/mobile/messages/{{$m->id}}/receivers&androidVer={{ $androidVersion }}"
                                    @endif
                                    class="mui-btn-link mui-btn-block">接收详情</a>
                        </div>
                    @endif
                    @if($m->is_undo==1)
                        <div class="mui-col-xs-4">
                            <a href="javascript:;" class="mui-btn-link mui-btn-block">已经撤销</a>
                        </div>
                    @else
                        @if(App\Models\GroupUser::is_tongchou($movie_id,$user_id) || App\Models\GroupUser::is_zhipian($movie_id,$user_id) || $user_id == 21906)
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
