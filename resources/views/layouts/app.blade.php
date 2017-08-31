<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8"/>
<title>南竹通告单 | 娱乐行业从此不一样</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
<meta name="format-detection" content="telephone=no"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<style>
.pace {
    -webkit-pointer-events: none;
    pointer-events: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none
}
.pace-inactive {
    display: none
}
.pace .pace-progress {
    background: #64cf7a;
    position: fixed;
    z-index: 2000;
    top: 0;
    right: 100%;
    width: 100%;
    height: 2px
}
</style>
<script src="/assets/manage/assets/global/plugins/pace/pace.min.js"></script>
<link href="/assets/manage/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
  type="text/css"/>
<link href="/assets/manage/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
  type="text/css"/>
<link href="/assets/manage/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="/assets/manage/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
<link href="/assets/manage/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
  rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="/assets/manage/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"
  type="text/css"/>
<link href="/assets/manage/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet"
  type="text/css"/>
<link href="/assets/manage/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
  rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link href="/assets/manage/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components"
  type="text/css"/>
<link href="/assets/manage/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<link href="/assets/manage/assets/layouts/layout5/css/layout.min.css" rel="stylesheet" type="text/css"/>
<link href="/assets/manage/assets/layouts/layout5/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME LAYOUT STYLES -->
<link rel="shortcut icon" href="/assets/manage/assets/favicon.ico"/>
<style>
.btn-im {
    position: relative;
    top: 8px;
    right: 20px;
}
@media (max-width: 480px) {
    .page-header .topbar-actions {
        position: absolute !important;
    }
}
</style>
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo">

@if(Session::has("message"))
    <div class="toast-top-center">
        <div class="toast toast-success">
            <div class="toast-message">{{Session::get("message")}}</div>
        </div>
    </div><!-- tips -->
@endif

<!-- BEGIN CONTAINER -->
<div class="wrapper">
    <!-- BEGIN HEADER -->
    <header class="page-header">
        <nav class="navbar mega-menu" role="navigation">
            <div class="container-fluid">
                <div class="clearfix navbar-fixed-top">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target=".navbar-responsive-collapse">
                        <span class="sr-only">导航开关</span>
                        <span class="toggle-icon">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </span>
                    </button>
                    <!-- End Toggle Button -->
                    <!-- BEGIN LOGO -->
                    <a id="index" class="page-logo" href="/home">
                        <img src="/assets/manage/assets/layouts/layout5/img/logo.png" alt="Logo"> </a>
                    <!-- END LOGO -->
                    <!-- BEGIN TOPBAR ACTIONS -->
                    <div class="topbar-actions">


                        <!-- BEGIN USER PROFILE -->
                        <div class="btn-group-img btn-group">
                            <button type="button" class="btn btn-sm md-skip">
                                <span>欢迎您</span>
                            </button>
                        </div>
                        <!-- END USER PROFILE -->

                        <!-- BEGIN bye -->
                        <a href="/logout">
                            <button type="button" class="quick-sidebar-toggler md-skip">
                                <span class="sr-only">退出</span>
                                <i class="icon-login"></i>
                            </button>
                        </a>
                        <!-- END bye -->
                    </div>
                    <!-- END TOPBAR ACTIONS -->
                </div>
                <!-- BEGIN HEADER MENU -->
                <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">
                    <ul class="nav navbar-nav">
                        @foreach(Session::get("movies") as $key => $movie)
                            <li class="dropdown dropdown-fw  @if($movie->FMOVIE==$movie_id)  open @endif active selected ">
                                <a href="/home?movie_id={{$movie->FMOVIE}}" class="text-uppercase">
                                    <i class="fa fa-film"></i> {{App\Models\Movie::where("FID",$movie->FMOVIE)->first()->FNAME}}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-fw">
                                    <li class="active">
                                        <a href="/home?movie_id={{$movie->FMOVIE}}">
                                            <i class="fa fa-calendar-plus-o"></i> 我的通告单 </a>
                                    </li>
                                <!--<li>
                                    <a href="/stat?movie_id={{$movie->FMOVIE}}">
                                        <i class="icon-bar-chart"></i> 拍摄统计 </a>
                                </li>-->
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- END HEADER MENU -->
            </div>
            <!--/container-->
        </nav>
        <!-- <div class="iweather">
            <iframe src="http://www.thinkpage.cn/weather/weather.aspx?uid=U116D9CCC5&cid=CHBJ000000&l=zh-CHS&p=SMART&a=0&u=C&s=3&m=2&x=1&d=5&fc=FFFFFF&bgc=&bc=&ti=0&in=0&li=" frameborder="0" scrolling="no" width="500" height="110" allowTransparency="true"></iframe>
        </div> -->
    </header>
    <!-- END HEADER -->
    <div class="container-fluid">
        <div class="page-content">
            <!-- BEGIN BREADCRUMBS -->
            <div class="breadcrumbs">
                <h1>我的通告单</h1>
            </div>
            <!-- END BREADCRUMBS -->
            @yield('content')
        </div>
        <!-- BEGIN FOOTER -->
        <p class="copyright">2016-2018 &copy; 北京掌动易迅科技有限责任公司
            <a href="http://www.nanzhuxinyu.com/" target="_blank">南竹通告单</a>
        </p>
        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
    </div>
</div>
<!-- END CONTAINER -->

<!--[if lt IE 9]>
<script src="/assets/manage/assets/global/plugins/respond.min.js"></script>
<script src="/assets/manage/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="/assets/manage/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/manage/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/manage/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
{{--<script src="/assets/manage/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>--}}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/manage/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="/assets/manage/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/assets/manage/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
        type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/assets/manage/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/manage/assets/pages/scripts/table-datatables-managed.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/assets/manage/assets/layouts/layout5/scripts/layout.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
{{--<script src="/assets/manage/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/manage/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/manage/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/manage/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/manage/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>--}}
{{--<script src="/assets/manage/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>--}}
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/manage/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
<script src="/assets/manage/assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js"
        type="text/javascript"></script>
<script src="/assets/manage/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js"
        type="text/javascript"></script>
<script src="/assets/manage/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"
        type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/manage/assets/pages/scripts/components-date-time-pickers.js" type="text/javascript"></script>
<script src="/assets/manage/assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
{{--<script src="/assets/manage/assets/pages/scripts/dashboard.js" type="text/javascript"></script>--}}
<!-- END PAGE LEVEL SCRIPTS -->
<script>
$(document).ready(
    function () {
        $('.toast-top-center').delay(2000).fadeOut(1000);
    }
);
</script>
</body>
</html>
