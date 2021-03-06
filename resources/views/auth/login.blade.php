<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | Valasys Media - CRM</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="author" content="CodedThemes" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('public/template') }}/assets/images/favicon.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/style.css">

</head>

<body>
<div class="auth-wrapper aut-bg-img-side cotainer-fiuid align-items-stretch">
    <div class="row align-items-center w-100 align-items-stretch bg-white">
        <div class="d-none d-lg-flex col-lg-8 aut-bg-img align-items-center d-flex justify-content-center">
            <div class="col-md-8">
                <small>Version 2.0</small>
                <h1 class="text-white mb-5">Valasys Media <br> Program Management</h1>
            </div>
        </div>
        <div class="col-lg-4 align-items-stret h-100 align-items-center d-flex justify-content-center">
            <div class=" auth-content text-center">
                <div class="mb-4">
                    <i class="feather icon-unlock auth-icon"></i>
                </div>
                <h3 class="mb-4">Login</h3>
                <form method="post" action="{{ route('login') }}">
                    @csrf
                    @if(session('error'))
                        <div class="input-group mb-3">
                            <span class="text-danger">{{session('error')}}</span>
                        </div>
                    @endif
                    <div class="input-group mb-3">
                        <input @if(Cookie::has('login_email')) value="{{ Cookie::get('login_email') }}" @endif type="email" class="form-control" placeholder="Email" name="email" required>
                    </div>
                    <div class="input-group mb-4">
                        <input @if(Cookie::has('login_password')) value="{{ Cookie::get('login_password') }}" @endif type="password" class="form-control" placeholder="password" name="password" required>
                    </div>
                    <div class="form-group text-left">
                        <div class="checkbox checkbox-fill d-inline">
                            <input type="checkbox" name="remember_me" id="checkbox-fill-a1" checked>
                            <label for="checkbox-fill-a1" class="cr"> Save credentials</label>
                        </div>
                    </div>
                    <button class="btn btn-primary shadow-2 mb-4" type="submit">Login</button>
                    <!--<p class="mb-2 text-muted">Forgot password? <a href="auth-reset-password-v2.html">Reset</a></p>-->
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Required Js -->
<script src="{{ asset('public/template') }}/assets/js/vendor-all.min.js"></script><script src="{{ asset('public/template') }}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="{{ asset('public/template') }}/assets/js/pcoded.min.js"></script>

</body>
</html>
