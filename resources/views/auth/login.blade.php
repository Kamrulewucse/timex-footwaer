<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | {{ config('app.name') }}</title>
{{--    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">--}}
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('themes/backend/dist/css/adminlte.min.css') }}">
    <style>
        .login-box, .register-box {
            width: 420px;
        }
        .card-primary.card-outline {
            border-top: 3px solid #da4a7c;
        }
        .btn-primary {
            background-color: #da4a7c;
            border-color: #da4a7c;
        }
        .btn-primary:hover{
            background-color: #da4a7c;
            border-color: #da4a7c;
        }
        .icheck-primary>input:first-child:checked+input[type=hidden]+label::before, .icheck-primary>input:first-child:checked+label::before {
            background-color: #da4a7c;
            border-color: #da4a7c;
        }
        .icheck-primary>input:first-child:not(:checked):not(:disabled):hover+input[type=hidden]+label::before, .icheck-primary>input:first-child:not(:checked):not(:disabled):hover+label::before {
            border-color: #da4a7c;
        }
        .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
            background-color: #da4a7c;
            border-color: #da4a7c;
        }
        .btn-primary {
            background-color: #378903;
            border-color: #378903;
        }
        .card-primary.card-outline {
            border-top: 3px solid #378903;
        }
        .btn-primary:hover {
            background-color: #378903;
            border-color: #378903;
        }
        .icheck-primary>input:first-child:checked+input[type=hidden]+label::before, .icheck-primary>input:first-child:checked+label::before {
            background-color: #378903;
            border-color: #378903;
        }
        .icheck-primary>input:first-child:checked+input[type=hidden]+label::before, .icheck-primary>input:first-child:checked+label::before {
            background-color: #378903;
            border-color: #378903;
        }
        .btn-primary {
            background-color: #378903;
            border-color: #378903;
        }
        .btn-primary {
            color: #fff;
            background-color: #378903;
            border-color: #378903;
            box-shadow: none;
        }
        element.style {
        }
        .btn-primary:not(:disabled):not(.disabled).active:focus, .btn-primary:not(:disabled):not(.disabled):active:focus, .show>.btn-primary.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgb(38 143 255 / 50%);
        }
        .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
            background-color: #378903;
            border-color: #378903;
        }

    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{ route('login') }}">
                <img height="100px" src="{{ asset('img/company.png') }}" alt="">
            </a>
{{--            <h2>{{config('app.name')}}</h2>--}}
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            @if($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-danger">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="email" class="form-control" placeholder="Email/Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" autocomplete="current-password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input name="remember" type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-flat btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <hr>
            <a target="_blank" href="https://techandbyte.com">
                <img style="display: block; margin-left: auto; margin-right: auto; width: 200px; height: 80px;" src="{{ asset('img/techandbyte.jpg') }}">
            </a>
            <p style="text-align:center; font-size:14px; color:Blue;">
                <a target="_blank" href="https://techandbyte.com">
                    <strong style="color: #0b4444">Design & Developed</strong>
                </a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('themes/backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('themes/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('themes/backend/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
