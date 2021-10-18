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
<!-- [ chat user list ] end -->

<!-- [ chat message ] start -->

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

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function () {
        $('.double-click').click(function() {
            return false;
        }).dblclick(function() {
            window.location = this.href;
            return false;
        });
    });
</script>

<script type="text/javascript" src="{{asset('public/js/custom.js?='.time()) }}"></script>

@yield('javascript')

<script>
    $(function (){
        @if(session('default'))
        trigger_pnofify('default', '{{ session('default')['title'] }}', '{{ session('default')['message'] }}');
        @endif
        @if(session('primary'))
        trigger_pnofify('primary', '{{ session('primary')['title'] }}', '{{ session('primary')['message'] }}');
        @endif
        @if(session('success'))
        trigger_pnofify('success', '{{ session('success')['title'] }}', '{{ session('success')['message'] }}');
        @endif
        @if(session('info'))
        trigger_pnofify('info', '{{ session('info')['title'] }}', '{{ session('info')['message'] }}');
        @endif
        @if(session('error'))
        trigger_pnofify('error', '{{ session('error')['title'] }}', '{{ session('error')['message'] }}');
        @endif
        @if(session('warning'))
        trigger_pnofify('warning', '{{ session('warning')['title'] }}', '{{ session('warning')['message'] }}');
        @endif
    });
</script>

<script>
    // Set timeout variables.
    var timoutWarning = 840000; // Display warning in 14 Mins.
    var timoutNow = 900000; // Timeout in 15 mins.
    var logoutUrl = '{{ route('logout') }}'; // URL to logout page.

    var warningTimer;
    var timeoutTimer;

    // Start timers.
    function StartTimers() {
        warningTimer = setTimeout("IdleWarning()", timoutWarning);
        timeoutTimer = setTimeout("IdleTimeout()", timoutNow);
    }

    // Reset timers.
    function ResetTimers() {
        clearTimeout(warningTimer);
        clearTimeout(timeoutTimer);
        StartTimers();
        //$("#timeout").dialog('close');
    }

    // Show idle timeout warning dialog.
    function IdleWarning() {
        alert('Warning: No activity detected, session will be end soon.');
    }

    // Logout the user.
    function IdleTimeout() {
        window.location = logoutUrl;
    }

    $(function () {
        //StartTimers();

        $('body').mousemove(function() {
            ResetTimers();
        });

        $('body').keypress(function() {
            ResetTimers();
        });
    });
</script>


</body>
</html>
