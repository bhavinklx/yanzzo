<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <!--<link rel="icon" type="<?php /*echo SITE_URL; */ ?>siteadmin/img/favicon.ico" sizes="16x16" href="<?php /*echo SITE_URL; */ ?>siteadmin/img/favicon.ico">-->
    <title>Admin Panel | YAARIOKE - The Karaoke Lounge</title>

    <!-- page css -->
    <link href="{{ asset('assets/dist/css/pages/login-register-lock.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dist/css/style.min.css') }}" rel="stylesheet">

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
</head>

<body class="skin-blue card-no-border">
<!-- Preloader - style you can find in spinners.css -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label"></p>
    </div>
</div>
<!-- Main wrapper - style you can find in pages.scss -->
<section id="wrapper">
    <div class="login-register" style="background-image:url({{ asset('assets/images/background/login-register.jpg') }});">
        <div class="login-box card">
            @if(Session::has("failedMsg"))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ Session::get('failedMsg') }}
                </div>
            @endif
            <div class="card-body">
                <form class="form-horizontal form-material" id="loginFrm" role="form" method="post" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <h3 class="box-title m-b-20">Sign In</h3>
                    <div class="form-group" id="login_user">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="email" id="email" placeholder="Username">
                        </div>
                        @error("email")
                        <span class="help-block"><small id="msg_email" class="text-danger">{{ $message }}</small></span>
                        @enderror
                    </div>
                    <div class="form-group" id="login_pass">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="password" id="password" autocomplete="new-password" placeholder="Password">
                        </div>
                        @error("password")
                        <span class="help-block"><small id="msg_password" class="text-danger">{{ $message }}</small></span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Remember me</label>
                                {{--<a href="javascript:void(0)" id="to-recover" data-toggle="tooltip" data-placement="top" data-original-title="Forgot Password" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>--}}
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-lg btn-info btn-rounded" name="submit" type="submit">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- End Wrapper -->

<!-- All Jquery -->
<script src="{{ asset('assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/jquery-validation-1.19.1/dist/jquery.validate.js') }}"></script>

<!--Custom JavaScript -->
<script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
    });
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });

    if ($("#loginFrm").length > 0) {
        $("#loginFrm").validate({
            rules: {
                email: {
                    required: true,
                    maxlength: 50,
                    email: true,
                },

                password: {
                    required: true,
                },
            },
            messages: {
                email: {
                    required: "Please enter your email",
                    email: "Please enter your valid email",
                    maxlength: "The email name should less than or equal to 50 characters",
                },

                password: {
                    required: "Please enter your password",
                },
            },
        })
    }
</script>

</body>
<!-- END BODY -->
</html>
