<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>{{ config('app.name', 'Laravel') }} | Thank you</title>

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="{{ asset('uploads/logo/favicon.png') }}" type="image/x-icon">
        <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('uploads/logo/favicon.png') }}">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ asset('uploads/logo/favicon.png') }}">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ asset('uploads/logo/favicon.png') }}">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ asset('uploads/logo/favicon.png') }}">

        <!-- Bootstrap -->
        <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap rtl -->
        <!--<link href="{{ asset('assets/bootstrap-rtl/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css"/>-->
        <!-- pe-icon-7-stroke -->
        <link href="{{ asset('assets/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" rel="stylesheet" type="text/css"/>
        <!-- Theme style -->
        <link href="{{ asset('assets/dist/css/styleBD.css') }}" rel="stylesheet" type="text/css"/>
        <!-- Theme style rtl -->
        <!--<link href="{{ asset('assets/dist/css/styleBD-rtl.css') }}" rel="stylesheet" type="text/css"/>-->
        <style>
            .container-center-thankyou {
                max-width: 800px;
                margin: 10% auto 0;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <!-- Content Wrapper -->
        <div class="login-wrapper">
            <div class="container-center-thankyou">
                <div class="panel panel-bd">
                    <div class="middle-box">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <div class="thank-you-pop">
                                    <img src="{{ asset('assets/dist/img/Green-Round-Tick.png') }}" alt="">
                                    <h1>Thank You!</h1>
                                    <p>Your password successfully reset.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jQuery/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
</body>
</html>