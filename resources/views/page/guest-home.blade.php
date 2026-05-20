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
            <img src="{{ $page_all_data['hero_image_image'] }}" class="img-fluid" alt="knolzi" />
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
    <section class="board-course">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="board-course-heading">
                        <h2>A broad selection of courses</h2>
                        <p>{{ $page_all_data['hero_broad_selection_description'] }}</p>
                    </div>
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        @if(!empty($page_all_data['categories']))
                            @php
                                $i = 1;
                            @endphp
                            @foreach($page_all_data['categories'] as $cat_name => $category)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($i == 1) active @endif" id="{{ $category['category']['slug'] }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $category['category']['slug'] }}" type="button" role="tab" aria-controls="pills-{{ $category['category']['slug'] }}" aria-selected="true">{{ $cat_name }}</button>
                                </li>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        @endif
                    </ul>

                    @if(!empty($page_all_data['categories']))
                        @php
                            $i = 1;
                        @endphp
                    <div class="tab-content" id="pills-tabContent">
                        @foreach($page_all_data['categories'] as $cat_name => $category)
                        <div class="tab-pane fade @if($i == 1) show active @endif" id="pills-{{ $category['category']['slug'] }}" role="tabpanel" aria-labelledby="{{ $category['category']['slug'] }}-tab">
                            <div class="course-tab-content">
                                <div class="course-tab-content-heading">
                                    <h2>{{ $category['category']['category_sub_description'] }}</h2>
                                    {!! $category['category']['category_description'] !!}
                                    <a href="{{ route('categorycourses', $category['category']['slug']) }}" target="_blank" class="btn btn-warning">Explore Related Courses</a>
                                </div>
                                <div class="owl-carousel owl-theme board-course-carousel mt-5">
                                    @foreach($category['courses'] as $row)
                                    <div class="item">
                                        <div class="course-block course-{{ $row['course_id'] }}" data-bs-html="true" data-bs-toggle="popover" data-bs-content='<div class="course-block-hover">
                                             <div class="course-title"><a href="javascript:void(0)">{{ $row['course_name'] }}</a>
                                             </div>
                                             <div class="course-update-date">
                                             <span>Updated {{ date('F, Y', strtotime($row['created_at'])) }}</span>
                                             </div>
                                             <div class="course-description">
                                             <p>{{ $row['course_sub_description'] }}</p>
                                             </div>
                                             <div class="course-desc-list">
                                             {{ $row['course_applications'] }}
                                             </div>
                                             <div class="course-addtocart cart-wish-{{ $row['course_id'] }}">
                                             <div class="f-flex align-items-center justify-content-between">
                                             @if(array_key_exists($row['course_id'],session()->get('cart',[])))
                                             <a href="{{ url('/cart') }}" class="btn btn-primary">Go to cart</a>
                                            @else
                                            <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>
                                            @endif
                                            <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-warning add-to-wishlist"><i class="icon-favourite"></i></a>
                                        </div>
                                    </div>
                                </div>'>
                                <div class="course-image">
                                    <a href="{{ route('coursedetails', $row['course_slug']) }}">
                                        <img data-src="{{ $row['course_image'] }}" alt="course" src="{{ asset('assets/front/images/logo.png') }}" class="img-fluid lazyload">
                                    </a>
                                </div>
                                <div class="course-title"><a href="{{ route('coursedetails', $row['course_slug']) }}">{{ $row['course_name'] }}</a></div>
                                <small>By : {{ $row['author_name'] }}</small>
                                <p>{{ $row['course_sub_description'] }}</p>
                                @php
                                $rate =0;
                                if($row['rates'] != 0){
                                $rate = $row['rates'] / $row['total_record'];
                                }
                                @endphp
                                <div class="course-rating">
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
                                @php
                                if(isset($row['course_tag'])){

                                @endphp
                                <div class="bestlller-btn">
                                    <a href="javascript:void(0)" class="btn btn-primary">{{ $row['course_tag'] }}</a>
                                </div>
                                @php
                                }
                                @endphp
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @php
            $i++;
            @endphp
            @endforeach
        </div>
        @endif
        </div>
        </div>
        </div>
    </section>
    <!-- board selection course end -->
    <!-- orange bar start -->
    <section class="edupme-orange-bar align-items-center justify-content-center d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="bar-list mb-xl-0 mb-sm-0 mb-3">
                        <div class="bar-list-icons">
                            <img alt="Knolzi" width="16" height="16" src="{{ $page_all_data['slogan_first_image'] }}">
                        </div>
                        <div class="bar-list-title">
                            {{ $page_all_data['slogan_first'] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bar-list mb-xl-0 mb-sm-0 mb-3">
                        <div class="bar-list-icons">
                            <img alt="Knolzi" width="16" height="16" src="{{ $page_all_data['slogan_second_image'] }}">
                        </div>
                        <div class="bar-list-title">
                            {{ $page_all_data['slogan_second'] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bar-list">
                        <div class="bar-list-icons">
                            <img alt="Knolzi" width="16" height="16" src="{{ $page_all_data['slogan_third_image'] }}">
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
    <section class="stud-view-course">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="stud-view-course-content">
                        <h2>Students are viewing</h2>
                        <div class="owl-carousel owl-theme stud-alsiview-course">
                            @foreach($page_all_data['view_course'] as $row)
                            <div class="item">

                                <div class="course-block course-{{ $row->course_id }}" data-bs-html="true" data-bs-toggle="popover" data-bs-content='<div class="course-block-hover">
                                     <div class="course-title"><a href="javascript:void(0)">{{ $row->course_name }}</a>
                                     </div>
                                     <div class="course-update-date">
                                     <span>Updated {{ date('F, Y', strtotime($row->created_at)) }}</span>
                                     </div>
                                     <div class="course-description">
                                     <p>{{ $row->course_sub_description }}</p>
                                     </div>
                                     <div class="course-desc-list">
                                     {{ $row->course_applications }}
                                     </div>
                                     <div class="course-addtocart">
                                     <div class="f-flex align-items-center justify-content-between">
                                     @if(array_key_exists($row->course_id,session()->get('cart',[])))
                                     <a href="{{ url('/cart') }}" class="btn btn-primary">Go to cart</a>
                                    @else
                                    <a href="javascript:void(0)" id="{{ $row->course_id }}" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>
                                    @endif
                                    <a href="javascript:void(0)" class="btn btn-warning"><i class="icon-favourite"></i></a>
                                </div>
                            </div>
                        </div>'>

                        <div class="course-image">
                            <a href="{{ route('coursedetails', $row->slug) }}">
                                <img data-src="{{ $row->course_image }}" alt="course" src="{{ asset('assets/front/images/logo.png') }}" class="img-fluid lazyload">
                            </a>
                        </div>
                        <div class="course-title"> <a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a></div>
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
                        @php
                        if(isset($row->course_tag)) {
                        @endphp
                        <div class="bestlller-btn">
                            <a href="javascript:void(0)" class="btn btn-primary">{{ $row->course_tag }}</a>
                        </div>
                        @php
                        }
                        @endphp
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
    <!-- top categories section start -->
    <section class="top-categories">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="top-categories-area">
                        <div class="categorie-list-title">
                            <h2>TOP Categories</h2>
                        </div>
                        <div class="categories-list">
                            <ul>
                                @foreach($page_all_data['cat_arr'] as $key => $value)
                                <li class=""><a href="{{ route('categorycourses', $value) }}" class="btn btn-warning">{{ $key }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- top categories section end -->
    <!-- became instructor section start -->
    <section class="become-instructor-section">
        <div class="container">
            <div class="row align-items-center justify-content-center mxw-become-instru">
                <div class="col-lg-5">
                    <div class="become-instru-txt">
                        <h2>{{ $page_all_data['teaching_sec_title'] }}</h2>

                        <p>{{ $page_all_data['teaching_sec_description'] }}</p>
                        <a href="{{ $page_all_data['teaching_sec_btn_url'] }}" class="btn btn-primary mt-3">{{ $page_all_data['teaching_sec_btn_name'] }}</a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="become-instru-img">
                        <img src="{{ $page_all_data['teaching_sec_image'] }}" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- became instructor section end -->
    <!-- knolzi business section start -->
    <section class="edupme-business-section">
        <div class="container">
            <div class="row align-items-center justify-content-center mxw-edupme-buss">
                <div class="col-lg-5 order-1 order-xl-12 order-lg-12">
                    <div class="edupme-buss-img">
                        <img src="{{ $page_all_data['digital_sec_image'] }}" class="img-fluid" />
                    </div>
                </div>
                <div class="col-lg-7 order-12 order-xl-1 order-lg-1">
                    <div class="edupme-buss-txt">
                        <div class="d-flex align-items-center edupme-brand">
                            <img src="{{ asset('assets/front/images/logo.svg') }}" class="img-fluid" />
                            <div class="buss-digi-class">
                                {!! $page_all_data['digital_title'] !!}
                            </div>
                        </div>
                        <p>{{ $page_all_data['digital_sec_description'] }}</p>
                        <a href="{{ $page_all_data['digital_sec_btn_url'] }}" class="btn btn-primary mt-3">{{ $page_all_data['digital_sec_btn_name'] }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- knolzi business section end -->
    <!-- education upgrade section start -->
    <section class="education-upgrade-section">
        <div class="container">
            <div class="row align-items-center justify-content-center mxw-edu-upgrd">
                <div class="col-lg-6">
                    <div class="edu-upgrd-txt">
                        <h2>{{ $page_all_data['blog_sec_title'] }}</h2>
                        <p>{{ $page_all_data['blog_sec_description'] }}</p>
                        <a href="{{ $page_all_data['blog_sec_btn_url'] }}" class="btn btn-primary">{{ $page_all_data['blog_sec_btn_name'] }}</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="edu-upgrd-img">
                        <img src="{{ $page_all_data['blog_sec_image'] }}" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- education upgrade section end -->


    @stop
</x-layout-front-base>
