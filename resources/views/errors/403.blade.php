<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>403 Access Forbidden | {{ config('app.name', 'Knolzi') }}</title>

        <meta name="description" content="403 Access Forbidden - Knolzi">

        <!-- Google / Search Engine Tags -->
        <meta itemprop="name" content="403 Access Forbidden - Knolzi">
        <meta itemprop="description" content="403 Access Forbidden - Knolzi">
        <meta itemprop="image" content="{{ asset('assets/front/images/logo.png') }}">

        <!-- Facebook Meta Tags -->
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="403 Access Forbidden - Knolzi">
        <meta property="og:description" content="403 Access Forbidden - Knolzi">
        <meta property="og:image" content="{{ asset('assets/front/images/logo.png') }}">

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="403 Access Forbidden - Knolzi">
        <meta name="twitter:description" content="403 Access Forbidden - Knolzi">
        <meta name="twitter:image" content="{{ asset('assets/front/images/logo.png') }}">
        <link rel="canonical" href="{{ url()->current() }}"/>
        <!-- Favicon Icon -->
        <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}" />

        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Exo+2:wght@500;600;700&display=swap" />
        <!--end::Fonts-->

        <!--begin::Global Stylesheets Bundle(used by all pages)-->
        <link rel="stylesheet" href="{{ asset('assets/front/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/front/css/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

        <link href="{{ asset('assets/css/sweetalert.min.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/fontawesome.css') }}" rel="stylesheet">

    </head>
    <body>
        <div id="app"></div>
        <div class="container">
            <div class="row align-items-center justify-content-center text-center" style="height:100vh;">
                <div class="col-lg-8">
                    <div class="pagenotfound">
                        <img src="{{ asset("assets/front/images/404.png") }}" class="img-fluid" />
                        <div class="pagenotfound-content">
                            <h1>403</h1>
                            <h2>Access Forbidden</h2>
                            <a href="{{ url("/") }}" class="btn btn-warning">GO TO HOME</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
