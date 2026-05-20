<x-layout-front-base>
    @if(!empty($page_all_data['seo_meta']))
    @section('meta_title', $page_all_data['seo_meta']->title)
    @section('meta_description', $page_all_data['seo_meta']->description)
    @section('meta_keywords', $page_all_data['seo_meta']->keyword)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @endif
    @section('content')
    <!-- hero image start -->
    <section class="hero">

        <div class="hero-area">
            <img src="{{ $page_all_data['hero_image_image'] }}" class="img-fluid" alt="{{ strip_tags($page_all_data['hero_image_title']) }}" />
            <div class="hero-img-content">
                <div class="hero-title">
                    <h1>{!! $page_all_data['hero_image_title'] !!}</h1>
                    {!! $page_all_data['hero_image_description'] !!}
                </div>
                <div class="hero-btn d-flex align-items-center">
                    <a href="{{ $page_all_data['hero_image_btn_url'] }}" class="btn btn-warning text-uppercase">{{ $page_all_data['hero_image_btn_name'] }}</a>
                </div>
            </div>
        </div>
    </section>
    <!-- hero image end -->
    <!-- selected categories start -->
    <section class="selected-categories">
        <div class="container mt-4 mb-5">
            <div class="categories-list">
                <ul>
                    @if(!empty($page_all_data['parent_cat']))
                        @php
                            $i = 1;
                        @endphp
                        @foreach($page_all_data['parent_cat'] as $row)
                            <li class=""><a href="{{ route('categorycourses', $row['slug']) }}" class="btn btn-primary">{{ $row['name'] }}</a></li>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </section>
    <!-- selected categories end -->
    <!-- i Learn section start -->
    <section class="ilearn-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="course-tab-content-heading">
                        <h2>Let's Start, {{ $page_all_data['username'] }}</h2>
                        <div class="border mt-3 mb-3"></div>
                        <h4 class="font-bd">i Learn</h4>
                    </div>

                    <div class="owl-carousel owl-theme course-carousel">
                        @foreach($page_all_data['subscribed_course'] as $row)
                        <div class="item">
                            <div class="course-block course-{{ $row['course_id'] }}" data-bs-html="true" data-bs-toggle="popover" data-bs-content='<div class="course-block-hover">
                                 <div class="course-title"><a href="javascript:void(0)">{{ $row["course_name"] }}</a>
                                 </div>
                                 <div class="course-update-date">
                                 <span>Updated {{ date('F, Y', strtotime($row['created_at'])) }}</span>
                                 </div>
                                 <div class="course-description">
                                 <p>{{ $row["course_sub_description"] }}</p>
                                 </div>
                                 <div class="course-desc-list">
                                 {{ $row["course_applications"] }}
                                 </div>
                                 <div class="course-addtocart">
                                 <div class="f-flex align-items-center justify-content-between">
                                 <a href="{{ route('courselearn', encrypt($row['course_id'])) }}" class="btn btn-warning">Go to course</a>
                                 </div>
                                 </div>
                                 </div>'>
                                <div class="course-image">
                                    <a href="{{ route('coursedetails', $row['slug']) }}">
                                        <img src="{{ $row['course_image'] }}" alt="{{ $row['course_name'] }}" class="img-fluid">
                                    </a>
                                </div>
                                <div class="course-title">
                                    <a href="{{ route('coursedetails', $row['slug']) }}">{{ $row['course_name'] }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- i Learn section end -->
    <!-- recommended for you section start-->
    <section class="recommended-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="course-tab-content-heading">
                        <div class="border mt-3 mb-3"></div>
                        <h2>Recommended for you</h2>
                        <div class="categories-list mt-3 mb-3">
                            <ul>
                                @foreach($page_all_data['child_cat'] as $row)
                                    <li class=""><a href="{{ route('categorycourses', $row['slug']) }}" class="btn btn-warning">{{ $row['name'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!--- ==================================================== -->
                    <div class="owl-carousel owl-theme course-carousel">
                        @foreach($page_all_data['recommended_course'] as $row)
                        <div class="item">
                            <div class="course-block course-{{ $row['course_id'] }}" data-bs-html="true" data-bs-toggle="popover" data-bs-content='<div class="course-block-hover">
                                 <div class="course-title"><a href="javascript:void(0)">{{ $row["course_name"] }}</a>
                                 </div>
                                 <div class="course-update-date">
                                 <span>Updated {{ date('F, Y', strtotime($row["created_at"])) }} </span>
                                 </div>
                                 <div class="course-description">
                                 <p>{{ $row['course_sub_description'] }}</p>
                                 </div>
                                 <div class="course-desc-list">
                                 <ul>
                                 {{ $row['course_applications'] }}
                                 </ul>
                                 </div>
                                 <div class="course-addtocart cart-wish-{{ $row['course_id'] }}">
                                 <div class="f-flex align-items-center justify-content-between">
                                 @if(array_key_exists($row['course_id'],$page_all_data['cart']))
                                 <a href="{{ url('/cart') }}" class="btn btn-warning goto-cart-{{ $row['course_id'] }}	">Go to cart</a>
                                @else
                                <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>
                                @endif
                                <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-warning @if(in_array($row['course_id'],$wishlist)) remove-to-wishlist add @else add-to-wishlist @endif"><i class="icon-favourite"></i></a>
                            </div>
                        </div>
                    </div>'>
                    <div class="course-image">
                        <a href="/course/{{ $row['slug'] }}">
                            <img src="{{ $row['course_image'] }}" alt="{{ $row['course_name'] }}" class="img-fluid">
                        </a>
                    </div>
                    <div class="course-title"><a href="/course/{{ $row['slug'] }}">{{ $row['course_name'] }}</a></div>
                    <small>By : {{ $row['author_name'] }}</small>
                    <p>{{ $row['course_sub_description'] }}</p>
                    <div class="course-rating">
                        @php
                            $rate =0;
                            if($row['rate'] != 0){
                                $rate = $row['rate'] / $row['total_record'];
                            }
                        @endphp
                        <strong>{{ number_format((float)$rate, 1, '.', '') }}</strong>
                        <div class="star-rating">
                            <span class="star-rating__fill" style="width: {{ $rate * 20 }}%"></span>
                        </div>
                    </div>
                    <div class="people-watch">
                        <span class="icon-user me-2"></span>
                        <span> {{ $row['courseView'] }}  (People are watching)</span>
                    </div>
                    <div class="course-price">
                        <span>{{ getCurrencySymbol() }}{{ currencyConvert($row['course_price']) }}</span>
                    </div>
                    @if(!empty($row['course_tag']))
                        <div class="bestlller-btn">
                            <a href="javascript:void(0)" class="btn btn-primary">{{ $row['course_tag'] }}</a>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</div>
</section>
    <!-- recommended for you section end-->
    <!-- next in line section start-->
    <section class="nextinline-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="course-tab-content-heading">
                        <div class="border mt-3 mb-3"></div>
                        <h2>Next In Line</h2>
                    </div>
                    <!--- ==================================================== -->
                    <div class="owl-carousel owl-theme course-carousel mt-4 mb-4">
                        @foreach($page_all_data['next_line'] as $row)
                        <div class="item">
                            <div class="course-block course-{{ $row['course_id'] }}" data-bs-html="true" data-bs-toggle="popover" data-bs-content='<div class="course-block-hover">
                                 <div class="course-title"><a href="javascript:void(0)">{{ $row["course_name"] }}</a>
                                 </div>
                                 <div class="course-update-date">
                                 <span>Updated {{ date('F, Y', strtotime($row["created_at"])) }} </span>
                                 </div>
                                 <div class="course-description">
                                 <p>{{ $row['course_sub_description'] }}</p>
                                 </div>
                                 <div class="course-desc-list">
                                 <ul>
                                 {{ $row['course_applications'] }}
                                 </ul>
                                 </div>
                                 <div class="course-addtocart cart-wish-{{ $row['course_id'] }}">
                                 <div class="f-flex align-items-center justify-content-between">
                                 @if(array_key_exists($row['course_id'],$page_all_data['cart']))
                                 <a href="{{ url('/cart') }}" class="btn btn-warning goto-cart-{{ $row['course_id'] }}	">Go to cart</a>
                                @else
                                <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>
                                @endif
                                <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-warning @if(in_array($row['course_id'],$wishlist)) remove-to-wishlist add @else add-to-wishlist @endif"><i class="icon-favourite"></i></a>
                            </div>
                        </div>
                    </div>'>
                    <div class="course-image">
                        <a href="/course/{{ $row['slug'] }}">
                            <img src="{{ $row['course_image'] }}" alt="{{ $row['course_name'] }}" class="img-fluid">
                        </a>
                    </div>
                    <div class="course-title"><a href="/course/{{ $row['slug'] }}">{{ $row['course_name'] }}</a></div>
                    <small>By : {{ $row['author_name'] }}</small>
                    <p>{{ $row['course_sub_description'] }}</p>
                    <div class="course-rating">
                        @php
                        $rate =0;
                        if($row['rate'] != 0){
                        $rate = $row['rate'] / $row['total_record'];
                        }
                        @endphp
                        <strong>{{ number_format((float)$rate, 1, '.', '') }}</strong>
                        <div class="star-rating">
                            <span class="star-rating__fill" style="width: {{ $rate * 20 }}%">
                            </span>
                        </div>
                    </div>
                    <div class="people-watch">
                        <span class="icon-user me-2"></span>
                        <span> {{ $row['courseView'] }}  (People are watching)</span>
                    </div>
                    <div class="course-price">
                        <span>{{ getCurrencySymbol() }}{{ currencyConvert($row['course_price']) }}</span>
                    </div>
                    @if(!empty($row['course_tag']))
                    <div class="bestlller-btn">
                        <a href="javascript:void(0)" class="btn btn-primary">{{ $row['course_tag'] }}</a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        </div>
        </div>
        </div>
    </section>
    <!-- next in line section end-->
    <!-- orange bar start -->
    <section class="edupme-orange-bar align-items-center justify-content-center d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="bar-list mb-xl-0 mb-sm-0 mb-3">
                        <div class="bar-list-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-award" viewBox="0 0 16 16">
                            <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
                            <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
                            </svg>
                        </div>
                        <div class="bar-list-title">
                            {{ $page_all_data['slogan_first'] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bar-list mb-xl-0 mb-sm-0 mb-3">
                        <div class="bar-list-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                        </div>
                        <div class="bar-list-title">
                            {{ $page_all_data['slogan_second'] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bar-list">
                        <div class="bar-list-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trophy" viewBox="0 0 16 16">
                            <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5c0 .538-.012 1.05-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33.076 33.076 0 0 1 2.5.5zm.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935zm10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935zM3.504 1c.007.517.026 1.006.056 1.469.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.501.501 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667.03-.463.049-.952.056-1.469H3.504z"/>
                            </svg>
                        </div>
                        <div class="bar-list-title">
                            {{ $page_all_data['slogan_third'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- orange bar end -->
    <!-- student are also views section start -->
    <section class="topcourcesfrom-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="course-tab-content-heading mt-4">
                        <h2>Students are viewing</h2>
                        <div class="owl-carousel owl-theme course-carousel mt-4 mb-4">
                            @foreach($page_all_data['view_course'] as $row)
                            <div class="item">
                                <div class="course-block course-{{ $row->course_id }}" data-bs-html="true" data-bs-toggle="popover" data-bs-content='<div class="course-block-hover">
                                     <div class="course-title"><a href="javascript:void(0)">{{ $row->course_name }}</a>
                                     </div>
                                     <div class="course-update-date">
                                     <span>Updated {{ date('F, Y', strtotime($row->created_at)) }}</span>
                                     </div>
                                     <div class="course-duration">
                                     <ol class="breadcrumb"><li class="breadcrumb-item">1 total hours</li>
                                     </ol></div>
                                     <div class="course-description">
                                     <p>{{ $row->course_sub_description }}</p>
                                     </div>
                                     <div class="course-desc-list">
                                     {{ $row->course_applications }}
                                     </div>
                                     <div class="course-addtocart cart-wish-{{ $row->course_id }}">
                                     <div class="f-flex align-items-center justify-content-between">
                                     @if(in_array($row->course_id,$page_all_data['subscribed_course_id']))
                                     <p><b>You already purchased this course</b></p>
                                    <a href="{{ route('getmycourse') }}" class="btn btn-warning">Go to course</a>
                                    @elseif(array_key_exists($row->course_id,$page_all_data['cart']))
                                    <a href="{{ url('/cart') }}" class="btn btn-warning goto-cart-{{ $row->course_id }}	">Go to cart</a>
                                    @else
                                    <a href="javascript:void(0)" id="{{ $row->course_id }}" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>
                                    @endif
                                    @if(!in_array($row->course_id,$page_all_data['subscribed_course_id']))
                                    <a href="javascript:void(0)" id="{{ $row->course_id }}" class="btn btn-warning @if(in_array($row->course_id,$wishlist)) remove-to-wishlist add @else add-to-wishlist @endif"><i class="icon-favourite"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>'>

                        <div class="course-image">
                            <a href="{{ route('coursedetails', $row->slug) }}">
                                <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="img-fluid">
                            </a>
                        </div>
                        <div class="course-title"><a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a></div>
                        <small>By : {{ $row->author_name }}</small>
                        <p>{{ $row->course_sub_description }}</p>
                        <div class="course-rating">
                            @php
                            $rate =0;
                            if($row->rate != 0){
                            $rate = $row->rate / $row->total_record;
                            }
                            @endphp
                            <strong>{{ number_format((float)$rate, 1, '.', '') }}</strong>
                            <div class="star-rating">
                                <span class="star-rating__fill" style="width: {{ $rate * 20 }}%">
                                </span>
                            </div>
                        </div>
                        <div class="people-watch">
                            <span class="icon-user me-2"></span>
                            <span> {{ $row->views }}  (People are watching)</span>
                        </div>
                        <div class="course-price">
                            <span>{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                        </div>
                        @if(isset($row->course_tag))
                        <div class="bestlller-btn">
                            <a href="javascript:void(0)" class="btn btn-primary">{{ $row->course_tag }}</a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        </div>
        </div>
        </div>
    </section>
    <!-- student are also views section end -->
    @stop
</x-layout-front-base>
