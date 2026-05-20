<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon Icon -->
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title','Knolzi') | {{ config('app.name', 'Knolzi') }}</title>

    @if (app()->environment('production'))
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

    @if (app()->environment('production'))
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('tailwindcss/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/sweetalert.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tailwindcss/custom.css') }}" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="min-h-screen bg-bg-primary text-text-primary overflow-x-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-bg-secondary to-bg-primary opacity-100"></div>

    <!-- Header -->
    @include('frontend.layouts.header')

    @yield('content')

    <!-- Footer -->
    @include('frontend.layouts.footer')

    <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <script>
        lazyload();
    </script>
    @stack('scripts')
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuButton = event.target.closest('button');

            if (!mobileMenu.contains(event.target) && !menuButton) {
                mobileMenu.classList.add('hidden');
            }
        });

        $(document).ready(function () {
            let dropdown = $('#courseDropdown');
            let toggle = $('#courseDropdownToggle');

            toggle.on('mouseenter', function () {
                dropdown.stop(true, true).fadeIn(200);
            });

            $('.relative.z-50').on('mouseleave', function () {
                dropdown.stop(true, true).fadeOut(200);
            });

            // Optional: Keep submenu open on hover
            $(document).on('mouseenter', '.group\\/sub', function () {
                $(this).find('[id^="submenu-"]').show();
            }).on('mouseleave', '.group\\/sub', function () {
                $(this).find('[id^="submenu-"]').hide();
            });
        });

    </script>

    <script>
        $(document).ready(function() {
            // Toggle search section below header
            $('[data-toggle=search-form]').click(function() {
                $('.search-form-wrapper').removeClass('hidden').addClass('flex');
                $('.search-form-wrapper .search').focus();
                $('html').addClass('search-form-open');
            });
            $('[data-toggle=search-form-close], .search-close').click(function() {
                $('.search-form-wrapper').addClass('hidden').removeClass('flex');
                $('html').removeClass('search-form-open');
            });
            // Hide on ESC
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('.search-form-wrapper').addClass('hidden').removeClass('flex');
                    $('html').removeClass('search-form-open');
                }
            });
            // Autocomplete
            $('#autoSearch').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete.fetch') }}",
                        data: { term: request.term },
                        success: function(data) {
                            if (data != '') {
                                $('#autoList').fadeIn();
                                $('#autoList').html(data);
                            } else {
                                $('#autoList').fadeOut();
                            }
                        }
                    });
                },
                minLength: 1
            });
            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-form').length) {
                    $('#autoList').fadeOut();
                }
            });
            // Clear input and suggestions on open
            $('.search-form-tigger').on('click', function(event) {
                $('#autoList').fadeOut();
                $('#autoList').html('');
                $('#autoSearch').val("");
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('keyup keypress', 'input.subscribe', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $("#submit-btn").click(function(e) {
                e.preventDefault();
                var _url = '{{ route("subscriber") }}';
                var myform = document.getElementById("subscribeform");
                var data = new FormData(myform);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: _url,
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response) {
                            $(".loading").hide();
                            $(".errors").hide();
                            $(".success").html("<b><p class='alert alert-success' style='color:green'>" + response.message + "</p></b>").show();
                            $('#email').val('');
                        }
                        $(".loading").hide();
                    },
                }).fail(function(xhr, textStatus, errorThrown) {
                    $(".success").hide();
                    $('.loading').hide();
                    var errors = "";
                    if (xhr.status == 422) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(i, val) {
                                errors += "<b><p class='alert alert-danger' style='color:red'>" + val[0] + "</p></b>";
                            });
                            if (errors !== "") {
                                $(".errors").html(errors);
                            }
                        }
                    } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                        $(".errors").html("Server error");
                        return false;
                    } else {
                        $(".errors").html("No internet Connection. please check your internet connection.");
                        return false;
                    }
                });
                return false;
            });
        });
    </script>

</body>
</html>
