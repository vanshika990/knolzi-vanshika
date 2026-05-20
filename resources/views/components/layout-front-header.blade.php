
<!-- Start desktop menu navbar -->
<nav class="navbar navbar-expand-xl edupme-nav">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('assets/front/images/logo.svg') }}" alt="Knolzi" width="100">
        </a>
        <div class="navbar-toggler pe-0">
            <div class="mobile-collpase pe-0">
                <button class="btn btn-white pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar">
                    <span class="bi bi-layout-text-sidebar " data-bs-toggle="offcanvas" data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar"></span>
                </button>
                <a class="nav-link search-form-tigger" href="javascript:void(0)" data-toggle="search-form" id="autoBtn"><i class="icon-search"></i></a>
                <a class="nav-link me-2" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#mycart-sidebar" aria-controls="mycart-sidebar">
                    <i class="icon-cart" data-bs-toggle="offcanvas" data-bs-target="#mycart-sidebar" aria-controls="mycart-sidebar"></i>
                    @if(count(getCartData()) != 0)
                    <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger cart_count">{{ count(getCartData()) }}<span class="visually-hidden">unread </span></span>
                    @else
                    <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger cart_count" style="display: none"><span class="visually-hidden">unread </span></span>
                    @endif
                </a>
            </div>
        </div>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav menu-center">
                <li class="nav-item dropdown">
                    <a class="nav-link active" aria-current="page" href="javascript:void(0)">Course Categories</a>
                    <div class="dropdown-nav">
                        <ul>
                            @if(!empty($parentCategories))
                            @foreach($parentCategories as $category)
                            <li>
                                <a href="{{ route('categorycourses',$category->slug) }}">{{ $category->name }}<i class="@if(count($category->subcategory) > 0) bi bi-chevron-right @endif"></i></a>
                                @if(count($category->subcategory))
                                @include('page.menu.subCategoryList',['subcategories' => $category->subcategory])
                                @endif
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link search-form-tigger" href="#search" data-toggle="search-form"><i class="icon-search"></i> Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route("digital-class") }}">Digital Classroom</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route("start-teaching") }}">Start Teaching</a>
                </li>
            </ul>
            @if(Auth::check())
            <ul class="navbar-nav menu-right align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link me-2" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#mycart-sidebar" aria-controls="mycart-sidebar">
                        <i class="icon-cart" data-bs-toggle="offcanvas" data-bs-target="#mycart-sidebar" aria-controls="mycart-sidebar"></i>
                        @if(count(getCartData()) != 0)
                        <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger cart_count">{{ count(getCartData()) }}<span class="visually-hidden">unread </span></span>
                        @else
                        <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger cart_count" style="display: none"><span class="visually-hidden">unread </span></span>
                        @endif
                    </a>
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
                            @can('get-my-course')
                            <li><a href="{{ route('getmycourse') }}">i Learn</a></li>
                            @endcan
                            @can('view-reviewer-course')
                            <li><a href="{{ route('getreviewercourse') }}">i Learn</a></li>
                            @endcan
                            <li><a href="{{ route('mycart') }}">Cart</a></li>
                            <li><a href="{{ route('mywishlist') }}">Wishlist</a></li>
                            <li class="border-bottom p-1 mb-1"></li>
                            <li><a href="{{route('personal-profile')}}">Profile Settings</a></li>
                            <li><a href="{{route('change-password')}}">Account Settings</a></li>
                            <li><a href="{{ route('purchase-history') }}">Purchase History</a></li>
                            <li class="border-bottom p-1 mb-1"></li>
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
            </ul>
            @else
            <ul class="navbar-nav menu-right align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link me-2" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#mycart-sidebar" aria-controls="mycart-sidebar">
                        <i class="icon-cart" data-bs-toggle="offcanvas" data-bs-target="#mycart-sidebar" aria-controls="mycart-sidebar"></i>
                        @if(count(getCartData()) != 0)
                        <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger cart_count">{{ count(getCartData()) }}<span class="visually-hidden">unread </span></span>
                        @else
                        <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger cart_count" style="display: none"><span class="visually-hidden">unread </span></span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-dark me-2" href="{{ route("login") }}">Log In</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning me-2" href="{{ route("register") }}">Sign up</a>
                </li>
            </ul>
            @endif
        </div>
        <div class="offcanvas offcanvas-end cartdetails" tabindex="-1" id="mycart-sidebar">
            {!! getCartHtml() !!}
        </div>
        <div class="search-form-wrapper">
            <form class="search-form" id="searchCourse" action="{{ route('search') }}">
                <div class="input-group justify-content-between align-items-center flex-nowrap">
                    <span class="input-group-addon" id="basic-addon2">
                        <i class="icon-search"></i>
                    </span>
                    <div class="form-group w-100">
                        <label class="form-label">Search  knolzi</label>
                        <input type="text" name="q" id="autoSearch" class="search form-control" placeholder="Search by Category, Course Creators, Subjects, Streams etc.">
                    </div>
                    <a href="javascript:void(0)">
                        <span class="input-group-addon search-close" id="basic-addon2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/></svg>
                        </span>
                    </a>
                </div>
                <div class="search-show-content" id="autoList">

                </div>
            </form>
        </div>
    </div>
</nav>
<!-- end navbar -->
<!-- Mobile menu start-->
<div class="offcanvas offcanvas-start mobile-sidebar" tabindex="-1" id="mobilemenu-sidebar" aria-labelledby="offcanvasExampleLabel">
    <button type="button" class="text-reset btn btn-white close-offcanvas" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x-circle-fill"></i></button>
    <div class="offcanvas-body">
        <div class="sidebar mobile-categoires-ul">
            <nav class="sidebar py-2 mb-4">
                @if(Auth::check())
                <div class="profile-dropdown-area">
                    @if(!empty(Auth::user()->profile_image))
                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" width="50" height="50">
                    @else
                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}"  width="50" height="50">
                    @endif
                    <h6>{{ Auth::user()->name }}</h6>
                    <span>{{ Auth::user()->email }}</span>
                </div>
                <ul class="nav flex-column mb-5">
                    @hasanyrole('organization|institute|author')
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endhasanyrole
                    <li><a href="{{ route('getmycourse') }}">i Learn</a></li>
                    <li><a href="{{ route('mycart') }}">Cart</a></li>
                    <li><a href="{{ route('mywishlist') }}">Wishlist</a></li>
                    <li><a href="{{route('personal-profile')}}">Profile Settings</a></li>
                    <li><a href="{{route('change-password')}}">Account Settings</a></li>
                    <li>
                        <a href="javascript:void(0)" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
                @else
                <strong>Login / Signup</strong>
                <ul class="nav flex-column mb-5">
                    <li class="nav-item"><a href="{{ route("login") }}" class="nav-link">Log In</a></li>
                    <li class="nav-item"><a href="{{ route("register") }}" class="nav-link">Sign Up</a></li>
                </ul>
                @endif
                <strong>Categories</strong>
                <ul class="nav flex-column mb-5" id="nav_accordion">
                    @if(!empty($parentCategories))
                    @foreach($parentCategories as $category)
                    <li>
                        <a href="{{ route('categorycourses',$category->slug) }}" class="nav-link">{{ $category->name }}<i class="@if(count($category->subcategory) > 0) bi bi-chevron-right @endif"></i></a>
                        @if(count($category->subcategory))
                        @include('page.menu.subCategoryListMobile',['subcategories' => $category->subcategory])
                        @endif
                    </li>
                    @endforeach
                    @endif
                </ul>
                <strong>More From Knolzi</strong>
                <ul class="nav flex-column mb-5">
                    <li class="nav-item"><a href="{{ route("digital-class") }}" class="nav-link">Digital Classroom</a></li>
                    <li class="nav-item"><a href="{{ route("start-teaching") }}" class="nav-link">Start Teaching</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- Mobile menu end-->
<script type="text/javascript">
    $(document).ready(function() {
        $('.search-form-tigger').on('click', function(event) {
            $('#autoList').fadeOut();
            $('#autoList').html();
            $('#autoSearch').val("");
        });

        $("#autoSearch").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{route('autocomplete.fetch')}}",
                    data: {
                        term: request.term
                    },
                    //dataType: "json",
                    success: function(data) {
                        if (data != '') {
                            $('#autoList').fadeIn();
                            $('#autoList').html(data);
                        }
                    }
                });
            },
            minLength: 1
        });
    });
</script>

