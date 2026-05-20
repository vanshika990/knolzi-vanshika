<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('meta_title','Knolzi') | {{ config('app.name', 'Knolzi') }}</title>

        @if (env('APP_ENV') == 'production')
        <meta name="google-site-verification" content="2Y4QpmsXXvt1FsY8HbzMZMZZgLZd6H1fn3GQT-acosY" />
        @endif
        <meta name="description" content="@yield('meta_description','Knolzi is The one-stop destination for learning and teaching online. Start learning today and grow. Make your Future brighter with us.')">

        <!-- Google / Search Engine Tags -->
        <meta name="title" content="@yield('meta_title','default description')">
        <meta name="description" content="@yield('meta_description','Knolzi is The one-stop destination for learning and teaching online. Start learning today and grow. Make your Future brighter with us.')">
        <meta name="keywords" content="@yield('meta_keywords','')">

        <!-- Facebook Meta Tags -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('meta_title','Knolzi Learning Platform')">
        <meta property="og:description" content="@yield('meta_description','Knolzi is The one-stop destination for learning and teaching online. Start learning today and grow. Make your Future brighter with us.')">
        <meta property="og:image" content="@yield('meta_image',asset('assets/front/images/logo.png'))">

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="@yield('meta_title','Knolzi')">
        <meta name="twitter:description" content="@yield('meta_description','Knolzi is The one-stop destination for learning and teaching online. Start learning today and grow. Make your Future brighter with us.')">
        <meta name="twitter:image" content="@yield('meta_image',asset('assets/front/images/logo.png'))">

        <link rel="canonical" href="{{ url()->current() }}"/>
        <!-- Favicon Icon -->
        <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}" />

        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Exo+2:wght@500;600;700&display=swap" />
        <!--end::Fonts-->

        <!--begin::Global Stylesheets Bundle(used by all pages)-->
        <link rel="stylesheet" href="{{ asset('assets/front/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link href="{{ asset('assets/css/sweetalert.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/fontawesome.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
        <!--end::Global Stylesheets Bundle-->

        <!-- Scripts -->
        {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        @if (env('APP_ENV') == 'production')
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '542799193555065');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=542799193555065&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-3RNJDJNZ34"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-3RNJDJNZ34');
        </script>
        @endif
    </head>
    <body>
        <div id="app"></div>
    <x-layout-front-header/>
    @yield('content')
    <x-layout-front-footer/>
    <script src="{{ asset('assets/front/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/common.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <script>
    lazyload();
    </script>
    <!--end::Javascript-->
    @yield('script')
</body>
</html>
