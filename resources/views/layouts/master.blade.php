<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Valasys Media is a top lead generation company in Dubai & USA providing 360° custom-made & personalized, B2B lead generation services." />
    <meta name="keywords" content="valasys, marketing, lead, generation, b2b">
    <meta name="author" content="Valasys Media" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="base-path" content="{{ url('/') }}" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('public/template') }}/assets/images/favicon.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/animation/css/animate.min.css">
    <!-- pnotify css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/pnotify/css/pnotify.custom.min.css">
    <!-- pnotify-custom css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/pages/pnotify.css">

    <!-- vendor css -->
    @yield('stylesheet')

    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/style.css">

    @yield('style')
</head>

<body>
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            @php
            $module = \App\Models\Module::whereRoleId(Auth::user()->role_id)->first();
            @endphp
            <a href="{{route($module->route_name)}}" class="b-brand">
                <span class="b-title">Valasys Media - CRM</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:void(0);"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            @include('layouts.sidebars.'.$module->slug)
        </div>
    </div>
</nav>

<!-- [ navigation menu ] end -->

<!-- [ Header ] start -->
@include('layouts.header', ['module' => $module])
<!-- [ Header ] end -->

<!-- [ chat user list ] start -->
<section class="header-user-list">
    <div class="h-list-header">
        <div class="input-group">
            <input type="text" id="search-friends" class="form-control" placeholder="Search Friend . . .">
        </div>
    </div>
    <div class="h-list-body">
        <a href="#!" class="h-close-text"><i class="feather icon-chevrons-right"></i></a>
        <div class="main-friend-cont scroll-div">
            <div class="main-friend-list">
                <div class="media userlist-box" data-id="1" data-status="online" data-username="Josephin Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image ">
                        <div class="live-status">3</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Josephin Doe<small class="d-block text-c-green">Typing . . </small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="2" data-status="online" data-username="Lary Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Lary Doe<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="3" data-status="online" data-username="Alice">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-3.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Alice<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="4" data-status="offline" data-username="Alia">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Alia<small class="d-block text-muted">10 min ago</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="5" data-status="offline" data-username="Suzen">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-4.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Suzen<small class="d-block text-muted">15 min ago</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="1" data-status="online" data-username="Josephin Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image ">
                        <div class="live-status">3</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Josephin Doe<small class="d-block text-c-green">Typing . . </small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="2" data-status="online" data-username="Lary Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Lary Doe<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="3" data-status="online" data-username="Alice">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-3.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Alice<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="4" data-status="offline" data-username="Alia">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Alia<small class="d-block text-muted">10 min ago</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="5" data-status="offline" data-username="Suzen">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-4.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Suzen<small class="d-block text-muted">15 min ago</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="1" data-status="online" data-username="Josephin Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image ">
                        <div class="live-status">3</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Josephin Doe<small class="d-block text-c-green">Typing . . </small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="2" data-status="online" data-username="Lary Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Lary Doe<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="3" data-status="online" data-username="Alice">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-3.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Alice<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="4" data-status="offline" data-username="Alia">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Alia<small class="d-block text-muted">10 min ago</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="5" data-status="offline" data-username="Suzen">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-4.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Suzen<small class="d-block text-muted">15 min ago</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="1" data-status="online" data-username="Josephin Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image ">
                        <div class="live-status">3</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Josephin Doe<small class="d-block text-c-green">Typing . . </small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="2" data-status="online" data-username="Lary Doe">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Lary Doe<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="3" data-status="online" data-username="Alice">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-3.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Alice<small class="d-block text-c-green">online</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="4" data-status="offline" data-username="Alia">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-1.jpg" alt="Generic placeholder image">
                        <div class="live-status">1</div>
                    </a>
                    <div class="media-body">
                        <h6 class="chat-header">Alia<small class="d-block text-muted">10 min ago</small></h6>
                    </div>
                </div>
                <div class="media userlist-box" data-id="5" data-status="offline" data-username="Suzen">
                    <a class="media-left" href="#!"><img class="media-object img-radius" src="{{ asset('public/template') }}/assets/images/user/avatar-4.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body">
                        <h6 class="chat-header">Suzen<small class="d-block text-muted">15 min ago</small></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- [ chat user list ] end -->

<!-- [ chat message ] start -->
<section class="header-chat">
    <div class="h-list-header">
        <h6>Josephin Doe</h6>
        <a href="#!" class="h-back-user-list"><i class="feather icon-chevron-left"></i></a>
    </div>
    <div class="h-list-body">
        <div class="main-chat-cont scroll-div">
            <div class="main-friend-chat">
                <div class="media chat-messages">
                    <a class="media-left photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body chat-menu-content">
                        <div class="">
                            <p class="chat-cont">hello Datta! Will you tell me something</p>
                            <p class="chat-cont">about yourself?</p>
                        </div>
                        <p class="chat-time">8:20 a.m.</p>
                    </div>
                </div>
                <div class="media chat-messages">
                    <div class="media-body chat-menu-reply">
                        <div class="">
                            <p class="chat-cont">Ohh! very nice</p>
                        </div>
                        <p class="chat-time">8:22 a.m.</p>
                    </div>
                </div>
                <div class="media chat-messages">
                    <a class="media-left photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="Generic placeholder image"></a>
                    <div class="media-body chat-menu-content">
                        <div class="">
                            <p class="chat-cont">can you help me?</p>
                        </div>
                        <p class="chat-time">8:20 a.m.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-list-footer">
        <div class="input-group">
            <input type="file" class="chat-attach" style="display:none">
            <a href="#!" class="input-group-prepend btn btn-success btn-attach">
                <i class="feather icon-paperclip"></i>
            </a>
            <input type="text" name="h-chat-text" class="form-control h-send-chat" placeholder="Write hear . . ">
            <button type="submit" class="input-group-append btn-send btn btn-primary">
                <i class="feather icon-message-circle"></i>
            </button>
        </div>
    </div>
</section>
<!-- [ chat message ] end -->

<!-- [ Main Content ] start -->
@yield('content')
<!-- [ Main Content ] end -->

<div id="div-modal"></div>

<script>
    var BASE_PATH = "{{ url('/') }}";
</script>
<!-- Required Js -->
<script src="{{ asset('public/template') }}/assets/js/vendor-all.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="{{ asset('public/template') }}/assets/js/pcoded.min.js"></script>


<!-- amchart js -->
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/amcharts.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/gauge.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/serial.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/light.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/pie.min.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/ammap.min.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/usaLow.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/radar.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/amchart/js/worldLow.js"></script>

<!-- Float Chart js -->
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/jquery.flot.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/jquery.flot.categories.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/curvedLines.js"></script>
<script src="{{ asset('public/template') }}/assets/plugins/flot/js/jquery.flot.tooltip.min.js"></script>

<!-- pnotify Js -->
<script src="{{ asset('public/template') }}/assets/plugins/pnotify/js/pnotify.custom.min.js"></script>

<!-- Moment Js -->
<script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>

<!-- dashboard-custom js -->
<script src="{{ asset('public/template') }}/assets/js/pages/dashboard-crypto.js"></script>

<script>
    {{-- AJAX ERROR HANDLER CODE=> error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); } --}}
    function checkSession(e){401==e.status&&location.reload()}
    $(".alert-auto-dismiss").fadeTo(5000,500).slideUp(500,function(){$(".alert-auto-dismiss").slideUp(500)});
</script>

<script>
    (function ($) {
        $.fn.doubleClickToGo = function () {
            var secondForDoubleClick = .5; //Add more seconds to increase the interval when two click are considered double click
            var firstClickTime = null;
            var secondClickTime = null;
            this.filter("a").each(function () {
                $(this).click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var currentTime = new Date().getTime() / 1000;
                    if ((currentTime - firstClickTime > secondForDoubleClick)){
                        firstClickTime = null;
                    }
                    if (firstClickTime == null) {
                        firstClickTime = currentTime
                        secondClickTime = null;
                    } else {
                        secondClickTime = currentTime
                        console.log((secondClickTime - firstClickTime))
                        if ((secondClickTime - firstClickTime) <= secondForDoubleClick) {
                            firstClickTime = null;
                            secondClickTime = null;
                            var link = $(this);
                            var url = link.attr("href");
                            window.location.href = url;
                        }
                        firstClickTime = null;
                        secondClickTime = null;
                    }
                })
            });
            return this;
        };
    }(jQuery));
</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function () {
        $('.double-click').doubleClickToGo();
    });
</script>

<script type="text/javascript" src="{{asset('public/js/custom.js?='.time()) }}"></script>

@yield('javascript')



</body>
</html>
