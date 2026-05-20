<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('meta_title','Knolzi) | {{ config('app.name', 'Knolzi) }}</title>

        <link rel="canonical" href="{{ url()->current() }}"/>
        <!-- Favicon Icon -->
        <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}" />
        <link rel="icon" href="{{asset('assets/img/favicon.png')}}" type="image/x-icon">

        <link href="{{ asset('assets/css/sweetalert.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/front/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/front/css/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/css/fontawesome.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/front/css/star-rating-svg.css') }}" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <style>
            .delivery-main-content .left-side-del-panel .media-delivery-content {
            height: 80vh;
            /* 50% of the viewport height */

        }

        .left-side-del-panel .question-answer {
            padding-block: 30px;
            height: 75vh;
            margin-bottom: 60px;
        }
        </style>
    </head>
    <body>
        <!-- Start navbar -->
        <nav class="navbar navbar-expand-xl edupme-nav">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/front/images/logo.svg') }}" alt="knolzi" width="100">
                </a>
                <!--button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-layout-text-sidebar-reverse"></i>
                </button-->
                <div class="navbar-toggler pe-0">
                    <div class="mobile-collpase pe-0">
                        <button class="btn btn-white pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar">
                            <span class="bi bi-layout-text-sidebar " data-bs-toggle="offcanvas" data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar"></span>
                        </button>
                        <a class="nav-link search-form-tigger" href="#search" data-toggle="search-form"><i class="icon-search"></i></a>
                        <a class="nav-link me-2" href="javascript:void(0)"><i class="icon-cart"></i></a>
                    </div>
                </div>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav menu-center">

                    </ul>
                    <ul class="navbar-nav menu-right align-items-center delivery-right-menu">
                        <li class="nav-item your-progress-header dropdown">
                            <div class="circular">
                                <div class="inner"></div>
                                <div class="number">25%</div>
                                <div class="circle">
                                    <div class="bar left">
                                        <div class="progress"></div>
                                    </div>
                                    <div class="bar right">
                                        <div class="progress"></div>
                                    </div>
                                </div>
                            </div>
                            <a class="nav-link" href="javascript:void(0)">Your Progress</a>
                            <div class="dropdown-nav">
                                <div class="your-progress-dropdown">
                                    <h5>25 of 100 Complete</h5>
                                    <span>Finsh course to get your certificate</span>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <div class="user-icon">
                                <a href="javascript:void(0)">
                                    @if(!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" width="20" height="20">
                                    @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}"  width="20" height="20">
                                    @endif
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                            </div>
                            <div class="dropdown-nav">
                                <div class="profile-dropdown-area">
                                    @if(!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" width="50" height="50">
                                    @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}"  width="50" height="50">
                                    @endif
                                    <h6>{{ Auth::user()->name }}</h6>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                                <ul class="pb-3">
                                    <li class="border-bottom p-1 mb-1"></li>
                                    @hasanyrole('organization|institute|author')
                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    @endhasanyrole
                                    <li><a href="{{ route('getmycourse') }}">i Learn</a></li>
                                    <li><a href="{{ route('mycart') }}">Cart</a></li>
                                    <li><a href="{{ route('mywishlist') }}">Wishlist</a></li>
                                    <li class="border-bottom p-1 mb-1"></li>
                                    <li><a href="{{route('personal-profile')}}">Profile Settings</a></li>
                                    <li><a href="{{route('change-password')}}">Account Settings</a></li>
                                    <li><a href="javascript:void(0)">Purchase History</a></li>
                                    <li><a href="javascript:void(0)">Payment Settings</a></li>
                                    <li class="border-bottom p-1 mb-1"></li>
                                    <li><a href="javascript:void(0)">Need Help</a></li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">Log Out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                    <li class="border-bottom p-1 mb-1"></li>
                                    <li><a href="{{ route('start-teaching') }}" class="fw-bold text-center justify-content-center btn-light p-3">Teach with us</a></li>
                                    <li><a href="{{ route('digital-class') }}" class="fw-bold text-center justify-content-center btn-light p-3">Business Solutions</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item delivery-more-detail dropdown">
                            <a class="nav-link" href="javascript:void(0)"><i class="fas fa-ellipsis-v"></i></a>
                            <div class="dropdown-nav">
                                <div class="deliverymore-dropdown">
                                    <ul>
                                        <li><a href="javascript:void(0)"><span class="icon-bookmark"></span> Add to favorite</a></li>
                                        <li><a href="javascript:void(0)"><span class="fas fa-share-alt"></span> Share this course</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- end navbar -->

        <div class="del-breadcrumb">
            <div class="container">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        @if(!empty($category))
                        @foreach($category as $cat)
                        <li class="breadcrumb-item"><a href="{{ $cat->slug }}">{{ $cat->name }}</a></li>
                        @endforeach
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

        <section class="delivery-main-content">
            <div class="container">
                <div class="row" id="completed-question">

                    <div class="col-lg-12 pe-lg-0 pe-3 ps-3">
                        <div class="left-side-del-panel">
                            <div class="media-delivery-content">
                                <form class="row g-3" action="#" name="submit_completequestion" id="submit_completequestion" method="POST">
                                    <div class="question-answer">
                                        <div class="question-mcq">
                                            How would you rate this course?
                                        </div>
                                        <div class="rate-this-course"></div><br>
                                        <div class="question-mcq">
                                            How would you rate the author?
                                        </div>
                                        <div class="rate-this-author"></div><br>
                                        <div class="question-mcq">
                                            Rating for new skills learned.
                                        </div>
                                        <div class="rate-this-skill"></div><br>
                                        <div class="question-mcq">
                                            How was your overall experience?
                                        </div>
                                        <div class="rate-overall"></div><br>
                                        <div class="question-mcq">
                                            Will you return for accessing more courses on knolzi?
                                        </div>
                                        <div class="rate-this-accessing"></div><br>
                                        <div class="question-mcq">
                                            Will you recommend this course & knolzi to your friends and colleagues?
                                        </div>
                                        <div class="rate-this-recommend"></div><br>
                                        <div class="mb-3">
                                            <input type="hidden" name="course_id" value="{{ $course_id }}"/>
                                            <input type="hidden" name="attempt_id" value="{{ $attempt_id }}"/>
                                            <input type="hidden" name="course_rate" class="course_rate" value=""/>
                                            <input type="hidden" name="author_rate" class="author_rate" value=""/>
                                            <input type="hidden" name="new_skill_rate" class="new_skill_rate" value=""/>
                                            <input type="hidden" name="overall_rate" class="overall_rate" value=""/>
                                            <input type="hidden" name="accessing_rate" class="accessing_rate" value=""/>
                                            <input type="hidden" name="recommend_rate" class="recommend_rate" value=""/>
                                            <button type="submit" class="btn btn-primary mb-3">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <!--------------------------------All Modal ----------------------------------->

                </div>
            </div>
        </section>
        <div class="loading" style="display:none">Loading&#8230;</div>
    <x-layout-front-footer />
    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.star-rating-svg.js') }}"></script>
    <script>
$(".would-you-rate-this-course").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".user_rate").val(currentRating);
        $(".review").show();
    }
});
$(".my-rating").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".user_rate").val(currentRating);
        $(".review").show();
    }
});
/****************Rate / Review **********/
$("#submitrate").validate({
    rules: {
        review: "required",
        rate: "required"
    }, submitHandler: function(form) {
        $('.loading').show();
        var data = new FormData(form);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('submit-rate-review') }}",
            type: 'POST',
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            success: function(response) {
                $('.loading').hide();
                swal({title: "Status!", text: response.message, type: "success"});
                $("#rate-review").html(response.html);
                $('#RateThisCourseModal').modal('hide');
                $(".modal-backdrop").remove();
                form.reset();
            }
        }).fail(function(xhr, textStatus, errorThrown) {
            $('.loading').hide();
            var errors = "";
            if (xhr.status == 422) {
                if (xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(i, val) {
                        errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                    });
                    if (errors !== "") {
                        swal({title: "Error!", text: errors, type: "error", html: true});
                    }
                }
            } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                swal({title: "Error!", text: "Server error", type: "error", html: true});
                return false;
            } else {
                swal({title: "Error!", text: "No internet Connection. please check your internet connection.", type: "error", html: true});
                return false;
            }
        });
        return false;
    }
});

$(".rate-this-course").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".course_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-author").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".author_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-skill").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".new_skill_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-overall").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".overall_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-accessing").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".accessing_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-recommend").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".recommend_rate").val(currentRating);
        $(".review").show();
    }
});


$("#submit_completequestion").validate({
    rules: {
        review: "required",
        rate: "required"
    }, submitHandler: function(form) {
        $('.loading').show();
        var data = new FormData(form);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('submit-complete-question') }}",
            type: 'POST',
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            success: function(response) {
                $('.loading').hide();
                swal({
                    title: "Status!",
                    text: response.message,
                    html: true,
                    type: "success"
                },
                function() {
                    window.location = "{{ route('homepage') }}";
                });
            }
        }).fail(function(xhr, textStatus, errorThrown) {
            $('.loading').hide();
            var errors = "";
            if (xhr.status == 422) {
                if (xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(i, val) {
                        errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                    });
                    if (errors !== "") {
                        swal({title: "Error!", text: errors, type: "error", html: true});
                    }
                }
            } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                swal({title: "Error!", text: "Server error", type: "error", html: true});
                return false;
            } else {
                swal({title: "Error!", text: "No internet Connection. please check your internet connection.", type: "error", html: true});
                return false;
            }
        });
        return false;
    }
});
    </script>
</body>
</html>
