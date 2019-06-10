<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo env('APP_NAME'); ?> </title>

    <link rel="shortcut icon" type="image/png" href=""/>

    <!-- Bootstrap -->
    <link href="{{asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- NProgress -->
    <link href="{{asset('assets/css/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <!-- PNotify -->
    <link href="{{asset('assets/css/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/pnotify.buttons.css')}}" rel="stylesheet">

    <link href="{{asset('assets/css/login.css')}}" rel="stylesheet" />

    <!-- Custom Theme Style -->
    <link href="{{asset('assets/css/admin.min.css')}}" rel="stylesheet">
    <style>
    .error{
            color: red;
        }
        .alert-danger, .alert-error, .invalid-feedback {
        color: #E9EDEF;
        background-color: rgba(231,76,60,.88);
        border-color: rgba(231,76,60,.88);
}
    </style>
    
</head>

<body class="nav-md">
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                 <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <h1><span style="font-size: 25px">Fair Food Forager</span></h1>
                    @if(session()->has('message'))
					    <div class="alert alert-danger" style="margin-bottom: 0px;">
					        {{ session()->get('message') }}
					    </div>
					@endif
                    <br />
                    <div class="col-xs-12 has-feedback" style="margin-bottom: 20px;">
                        <input type="email" name="email" class="form-control has-feedback-left{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" style="margin-bottom: 0px;" required>
                        <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-xs-12 has-feedback" style="margin-bottom: 20px;">
                        <input type="password" name="password" class="form-control has-feedback-left{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" required style="margin-bottom: 0px;">
                        <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="checkbox" style="margin-top: 5px">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-sign-in"></i> LOGIN</button>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
                <br />
                <div class="separator">
                    <a href="" class="btn btn-link" style="font-style: italic; color: #ffffff; margin-top: 0;">Forgot password?</a>
                </div>
            </section>
        </div>
    </div>

</body>
</html>