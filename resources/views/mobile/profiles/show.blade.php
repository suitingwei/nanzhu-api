<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>南竹通告单</title>
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <script src="/assets/mobile/js/flexible_css.js"></script>
    <link rel="stylesheet" href="/assets/mobile/css/style.css">
    <style>
        .user-intro .r i, .user-intro .r span {
            display: inline-block;
            vertical-align: middle;
        }

        .user-intro .user-more li {
            padding-bottom: 0.266667rem;
        }

        .user-more span {
            float: left;
            width: 75%;
        }

        .user-more span.c-lg {
            width: 24%;
        }

        .btn-wrap {
            padding: 0.4rem 0.333333rem 0.6rem;
        }

        .video-bd {
            min-height: 3rem;
        }

        .video-bd video {
            width: 100%;
            height: 5.200555rem;
            background: #000;
        }
        .bg-w {
            min-height : 3rem;
        }

    </style>
</head>
<body>

@if($from!="app")
    <div class="header">
        <div class="app">
            <div class="app-icon"></div>
            <div class="app-text">
                <h2 class="app-text-title f18">南竹通告单</h2>
                <p class="app-text-summary f16">真懂娱乐圈的平台</p>
            </div>
            <a id="appBtn" class="app-btn f18" href="javascript:;">立即下载</a>
            <i class="app-label"></i>
        </div>
    </div><!-- intro end -->
@endif

<div class="container">
    <div class="user-info bg-w">
        <div class="user-hd ac">
            <div class="pic">
                <img src="{{$profile->avatar}}">
            </div>
            <h1>
                {{$profile->name}}
                @if($profile->gender=="男")
                    <span><i class="if i-man c-blue"></i></span>
                @endif
                @if($profile->gender=="女")
                    <span><i class="if i-wman c-pink"></i></span>
                @endif
            </h1>
            <p class="c-lg f14">生日：{{$profile->birthday}}</p>
            @if($from=="app")
                <div class="like-btn c-lg">
                    <a href="javascript:;" id="like_form_submit" @if($is_liked) class="active" @endif>
                        <i class="if i-heart"></i>
                        <span>{{$like_count}}</span>
                    </a>
                </div>
                <form id="like_form" action="/mobile/likes" method="post" accept-charset="utf-8">
                    <input type="hidden" name="type" value="user">
                    <input type="hidden" name="user_id" value="{{$current_user_id}}">
                    <input type="hidden" name="like_id" value="{{$profile->id}}">
                </form>
                <form id="unlike_form" action="/mobile/likes/1" method="post" accept-charset="utf-8">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="type" value="user">
                    <input type="hidden" name="user_id" value="{{$current_user_id}}">
                    <input type="hidden" name="like_id" value="{{$profile->id}}">
                </form>
            @endif
        </div><!-- avatar end -->

        <div class="user-intro">
            <div class="r bdt-g bdb-g c-lg ac">
                <div class="g-xs-3">
                    <i class="if i-ruler"></i>
                    <span>{{$profile->height}}cm</span>
                </div>
                <div class="g-xs-3">
                    <i class="if i-weight"></i>
                    <span>{{$profile->weight}}kg</span>
                </div>
                <div class="g-xs-3">
                    <i class="if i-stars"></i>
                    <span>{{$profile->constellation}}</span>
                </div>
                <div class="g-xs-3">
                    <i class="if i-blood"></i>
                    <span>{{$profile->blood_type}}型</span>
                </div>
            </div>
            <ul class="user-more pdlr40">
                @if($profile->behind_position)
                    <li class="cf"><span class="c-lg">幕后职位</span><span>{{$profile->behind_position}}</span></li>
                @endif
                @if($profile->before_position)
                    <li class="cf"><span class="c-lg">台前身份</span><span>{{$profile->before_position}}</span></li>
                @endif
                <li class="cf"><span class="c-lg">语言</span><span>{{$profile->language}}</span></li>
                <li class="cf"><span class="c-lg">特长</span><span>{{$profile->speciality}}</span></li>
                <li class="cf"><span class="c-lg">毕业院校</span><span>{{$profile->college}}</span></li>
            </ul>
        </div><!-- intro end -->
    </div>

    @if(!empty($profile->introduction))
        <div class="mod-a bg-w mgt20">
            <div class="mod-a-hd ac">
                <div class="i-w"><i class="if i-ruser"></i></div>
                <h2>个人介绍</h2>
            </div>
            <div class="mod-a-bd c-b pdlr40 aj lh16">
                {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($profile->introduction) !!}
            </div>
        </div><!-- mod end -->
    @endif

    @if(!empty($profile->work_ex))
        <div class="mod-a bg-w mgt20">
            <div class="mod-a-hd ac">
                <div class="i-w"><i class="if i-resume"></i></div>
                <h2>作品简历</h2>
            </div>
            <div class="mod-a-bd c-b pdlr40 aj lh16">
                {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($profile->work_ex) !!}
            </div>
        </div><!-- mod end -->
    @endif

    @if(!empty($profile->prize_ex))
        <div class="mod-a bg-w mgt20">
            <div class="mod-a-hd ac">
                <div class="i-w"><i class="if i-rcup"></i></div>
                <h2>获奖经历</h2>
            </div>
            <div class="mod-a-bd c-b pdlr40 aj lh16">
                {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($profile->prize_ex) !!}
            </div>
        </div><!-- mod end -->
    @endif

    @if(!empty($profile->schedule))
        <div class="mod-a bg-w mgt20">
            <div class="mod-a-hd ac">
                <div class="i-w"><i class="if i-ocalendar"></i></div>
                <h2>个人档期</h2>
            </div>
            <div class="mod-a-bd c-b pdlr40 aj lh16">
                <p>{{ $profile->schedule }}</p>
            </div>
        </div><!-- mod end -->
    @endif

    @if(!empty($profile->email))
        <div class="mod-a bg-w mgt20">
            <div class="mod-a-hd ac">
                <div class="i-w"><i class="if i-rphone"></i></div>
                <h2>联系方式</h2>
            </div>
            <div class="mod-a-bd c-b l-i pdlr40 aj lh16">
                {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($profile->email) !!}
            </div>
        </div><!-- mod end -->
    @endif

    @if (count($profile->pic_urls())>0)
        @if(isset($profile->pic_urls()[0]))
            <div class="mod-img bg-w mgt20">
                <div class="mod-img-hd img-hd1">
                    <span class="c-img1">形象照</span><i class="if i-mark"></i>
                </div>
                <div class="slider-img mod-img-bd">
                    <div class="my-gallery">
                        @foreach ($profile->pic_urls()[0]->pictures() as $pic)
                            <div class="swiper-slide">
                                    <img src="{{$pic}}" data-preview-src="" data-preview-group="1">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div><!-- mod end -->
        @endif
    @endif

    @if (count($profile->pic_urls()) > 0)
        @if(isset($profile->pic_urls()[1]))
            <div class="mod-img bg-w mgt20">
                <div class="mod-img-hd img-hd2">
                    <span class="c-img2">剧照</span><i class="if i-mark"></i>
                </div>
                <div class="slider-img mod-img-bd">
                    <div class="my-gallery">
                        @foreach ($profile->pic_urls()[1]->pictures() as $pic)
                            <div class="swiper-slide">
                                @if(App\Models\Picture::is_from_ios($profile->avatar))
                                    <img src="{{$pic}}" data-preview-src="" data-preview-group="2">
                                @else
                                    <img src="{{App\Models\Picture::convert_pic($pic)}}" data-preview-src=""
                                         data-preview-group="2">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div><!-- mod end -->
        @endif
    @endif

    @if($profile->self_video_url)
        <div class="mod-img bg-w mgt20">
            <div class="mod-img-hd img-hd3">
                <span class="c-img3">自我介绍</span><i class="if i-mark"></i>
            </div>
            <div class="mod-img-bd video-bd">
                <video id="videoUser" src="{{ $profile->self_video_obj[0]->video_url }}"
                       poster="{{ $profile->self_video_obj[0]->cover_url}}" controls="controls">
                    您的浏览器不支持 video 标签。
                </video>
            </div>
        </div><!-- mod end -->
    @endif

    @if((isset($profile->collection_video_obj[0]) && $profile->collection_video_obj[0]->video_url != '') ||
        (isset($profile->collection_video_obj[1]) && $profile->collection_video_obj[1]->video_url != '')
    )
        <div class="mod-img bg-w mgt20">
            <div class="mod-img-hd img-hd4 c-lorange">
                <span class="c-img4">作品集锦</span><i class="if i-mark"></i>
            </div>
            <div class="mod-img-bd video-bd video-bd2">
                @if(isset($profile->collection_video_obj[0]) && $profile->collection_video_obj[0]->video_url != '')
                    <div class="video">
                        <video id="videoPro1" src="{{ $profile->collection_video_obj[0]->video_url  }}"
                               poster="{{ $profile->collection_video_obj[0]->cover_url }}" controls="controls">
                            您的浏览器不支持 video 标签。
                        </video>
                    </div>
                @endif
                @if(isset($profile->collection_video_obj[1]) && $profile->collection_video_obj[1]->video_url != '')
                    <div class="video">
                        <video id="videoPro2" src="{{ $profile->collection_video_obj[1]->video_url }}"
                               poster="{{ $profile->collection_video_obj[1]->cover_url }}" controls="controls">
                            您的浏览器不支持 video 标签。
                        </video>
                    </div>
                @endif
            </div>
        </div><!-- mod end -->
    @endif
    {{--<div class="btn-wrap">
        <a class="btn btn-primary btn-block btn-lg" href="/video_intro.php">“高大上”版介绍视频</a>
    </div>--}}<!-- btn end -->

</div><!-- container end -->

<script src="/assets/mobile/js/jquery.min.js"></script>
<script src="/assets/mobile/js/m.js"></script>
<script>
    //like btn
    (function ($) {
        $.extend({
            tipsBox: function (options) {
                options = $.extend({
                    obj: null,
                    str: "+1",
                    startSize: "30px",
                    endSize: "50px",
                    interval: 600,
                    color: "#66c68c"
                }, options);
                $("body").append("<span class='num'>" + options.str + "</span>");
                var box = $(".num");
                var left = options.obj.offset().left + options.obj.width() / 2;
                var top = options.obj.offset().top - options.obj.height();
                box.css({
                    "position": "absolute",
                    "left": left + "px",
                    "top": top + "px",
                    "z-index": 9999,
                    "font-size": options.startSize,
                    "line-height": options.endSize,
                    "color": options.color
                });
                box.animate({
                    "font-size": options.endSize,
                    "opacity": "0",
                    "top": top - parseInt(options.endSize) + "px"
                }, options.interval, function () {
                    box.remove();
                    options.callback();
                });
            }
        });
    })(jQuery);

    $(function () {
        $("#like_form_submit").on("click", function () {
            if ($("#like_form_submit").hasClass("active")) {
                $("#unlike_form").submit();
                $.tipsBox({
                    obj: $(this),
                    str: "-1"
                });
            } else {
                $("#like_form").submit();
                $.tipsBox({
                    obj: $(this),
                    str: "+1"
                });
            }
        });

        var video1 = document.getElementById("videoUser");
        $("#videoUser").click(function () {
            if (video1.paused) {
                video1.play();
            } else {
                video1.pause();
            }
        });
        var video2 = document.getElementById("videoPro1");
        $("#videoPro1").click(function () {
            if (video2.paused) {
                video2.play();
            } else {
                video2.pause();
            }
        });
        var video3 = document.getElementById("videoPro2");
        $("#videoPro2").click(function () {
            if (video3.paused) {
                video3.play();
            } else {
                video3.pause();
            }
        });
    });

    // mui
    mui.init({});
    mui.previewImage();
    document.getElementById('appBtn').addEventListener('tap', function () {
        mui.openWindow({
            url: "http://a.app.qq.com/o/simple.jsp?pkgname=com.zdyx.nanzhu",
            id: "appBtn",
            show: {
                aniShow: 'fade-in',
                duration: 300
            }
        });
    });
    mui('.btn-wrap').on('tap', 'a', function () {
        location.href = this.getAttribute('href');
    });
</script>

<!-- share -->
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/assets/mobile/js/jquery.min.js"></script>
<script>
    var currentUrl = encodeURIComponent(location.href.split('#')[0]);

    $.get('/api/wechat/get-config?current_url=' + currentUrl, function (responseData) {
        if (responseData.success) {
            wx.config({
                debug: false, // true false
                appId: responseData.data.config.appId,
                timestamp: responseData.data.config.timestamp,
                nonceStr: responseData.data.config.nonceStr,
                signature: responseData.data.config.signature,
                jsApiList: [
                    'onMenuShareTimeline', 'onMenuShareAppMessage'
                ]
            });
        }
    });
    wx.ready(function () {
        <?php $desc ="";
              if($profile->before_position){
                $desc .= "台前身份：".$profile->before_position;
              }

              if($profile->behind_position){
                $desc .= "幕后身份：".$profile->behind_position;
              }
        ?>
        wx.onMenuShareTimeline({
            title: "{{$profile->name}}\n{{ $desc }} 语言：{{$profile->language}} 特长：{{$profile->speciality}} 毕业院校：{{$profile->college}}",
            link: "{{Request::root()."/mobile/users/".$profile->id}}",
            imgUrl: "{{$profile->avatar}}"
        });
        wx.onMenuShareAppMessage({
            title: "{{$profile->name}}",
            desc: "{{ $desc }}\n语言：{{$profile->language}}\n特长：{{$profile->speciality}}\n毕业院校：{{$profile->college}}",
            link: "{{Request::root()."/mobile/users/".$profile->id}}",
            imgUrl: "{{$profile->avatar}}",
            type: 'link'
        });
    });
    function history_back() {
        window.nanzhu.backHome();
    }
</script>
</body>
</html>
