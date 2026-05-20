<x-layout-front-base>
    @section('meta_title', $course['meta_title'])
    @section('meta_description', $course['meta_description'])
    @section('meta_image', $course['course_image'])
    @section('meta_keywords', $course['meta_keyword'])

    @section('content')
    <section class="course-detail-hero">
        <div class="hero-area">
            <img src="{{ asset('assets/front/images/course-hero-img.jpg') }}" class="img-fluid" alt="knolzi" />
            <div class="hero-img-content">
                <div class="row">
                    <div class="col-xl-4">

                    </div>
                    <div class="col-xl-8">
                        <nav aria-label="breadcrumb">
                            @if(!empty($categories))
                            <ul class="breadcrumb">
                                @foreach($categories as $category)
                                <li class="breadcrumb-item"><a href="/category/{{ $category['slug'] }}">{{ $category['name'] }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </nav>
                        <div class="hero-title">
                            <h1>{{ $course['course_name'] }}</h1>
                            <p>{{ $course['course_sub_description'] }}</p>
                        </div>
                        @if(!empty($course['course_tag']))
                        <div class="hero-btn d-flex align-items-center mb-3 mt-3">
                            <a href="javascript:void(0)" class="btn btn-primary">{{ $course['course_tag'] }}</a>
                        </div>
                        @endif
                        <div class="author-detail">
                            <small>By :
                                @if(!empty($authors))
                                @php
                                $i = 0;
                                $len = count($authors);
                                @endphp
                                @foreach($authors as $key => $author)
                                @if(++$i === $len)
                                <a href="#author-{{ $key }}"> {{ $author['name'] }} </a>
                                @else
                                <a href="#author-{{ $key }}"> {{ $author['name'] }} </a> ,
                                @endif

                                @endforeach
                                @endif
                            </small>
                            <div class="course-rating">
                                <strong>{{ number_format((float)$rate, 1, '.', '') }}</strong>
                                <div class="star-rating">
                                    <span class="star-rating__fill" style="width: {{ $rate * 20 }}%">
                                    </span>
                                </div>
                            </div>
                            <div class="people-watch">
                                <span class="icon-user me-2"></span>
                                <span class="me-2"> {{ $course['courseView'] }}  (People are watching)</span>

                                @if(!empty($languages))
                                <span class="icon-language me-2"></span>

                                @foreach($languages as $language)
                                <span class="me-2">
                                    {{ $language['name'] }}
                                </span>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- hero image end -->
    <!-- course detail section start -->
    <section class="course-detail-content-sec">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-xl-4 col-lg-10 col-md-10">
                    <div class="course-addtocart-area">
                        <div class="course-image">
                            <img src="{{ $course['course_image'] }}" alt="{{ $course['course_name'] }}" class="img-fluid">
                        </div>
                        <div class="course-price-box">
                            <div class="course-price">

                                @if(isset($course['total_dis_price']))
                                <span><del>{{ getCurrencySymbol() }}{{ currencyConvert($course['course_price']) }}</del></span><br/><span>{{ getCurrencySymbol() }}{{ $course['total_dis_price'] }}</span>
                                @else
                                <span>{{ getCurrencySymbol() }}{{ currencyConvert($course['course_price']) }}</span>
                                @endif
                            </div>
                            @if(in_array($course['course_id'],$subscribe_course))
                            <p><b>You already purchased this course</b></p>
                            <a href="{{ route('courselearn', encrypt($course['course_id'])) }}" class="btn btn-warning">Go to course</a>
                            @elseif(array_key_exists($course['course_id'],$cart))
                            <a href="{{ url('/cart') }}" class="btn btn-warning">Go to cart</a>
                            @else
                            <a href="javascript:void(0)" id="{{ $course['course_id'] }}" class="btn btn-warning add-to-cart"> Add to cart</a>
                            @endif
                            @if(!in_array($course['course_id'],$subscribe_course))
                            @if(Auth::check())
                            <a href="{{ route('BuynowCheckout', encrypt($course['course_id'])) }}" class="btn btn-outline-dark">Buy Now</a>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-outline-dark">Buy Now</a>
                            @endif
                            @endif
                            @if(!empty($course['course_include']))
                            <div class="course-include">
                                <strong>This course includes </strong>
                                {!! $course['course_include'] !!}
                            </div>
                            @endif
                            @if(!in_array($course['course_id'],$subscribe_course))
                            <a href="javascript:void(0)" class="applycoupon">Apply Coupon</a>
                            @if(session()->get('coupon_code',''))
                            <div class="coupon-code-rmv"><label>{{ session()->get('coupon_code','') }}</label> <a href="{{ route('remove-coupon-from-cart') }}"><i class="fas fa-trash-alt"></i></a></div>
                            @endif
                            <div class="input-group apply-coupon-section" style="display:none;">
                                <input type="hidden" name="course_id" class="course_id" value="{{ $course['course_id'] }}">
                                <input type="text" name="discounts" class="form-control coupon_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
                                <button class="btn btn-primary apply-coupon" type="button" id="button-addon2">Apply</button>
                            </div>
                            <!--<label class="error" style="color: red;"></label>-->
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-8 col-lg-10 col-md-10">
                    <!-- Description module section start -->
                    <div class="course-detail-description">
                        <div class="description-para">
                            <h2>Description</h2>
                            {!! $course['course_description'] !!}
                        </div>
                        <button class="text-primary showmore">Show More <i class="fas fa-angle-down"></i></button>
                        <button class="text-primary showless" style="display:none;">Show Less <i class="fas fa-angle-up"></i></button>
                    </div>
                    <!-- Description module section end -->
                    <!-- Requirements module section start -->
                    <div class="learnmodule">
                        <h2>Requirements</h2>
                        {!! $course['course_requirement'] !!}
                    </div>
                    <!-- Requirements module section end -->
                    <!-- learn module section start -->
                    <div class="learnmodule">
                        <h2>You'll learn in this module</h2>
                        {!! $course['course_applications'] !!}
                    </div>
                    <!-- learn module section end -->
                    @if(!empty($course_content))
                    <!-- module content sectuin start -->
                    <div class="module-content">
                        <h2>Module Content</h2>
                        <ul id="modulelist-accordian">
                            @foreach($course_content as $key => $content)
                            <li>
                                <div class="mdc-ablk">
                                    <div class="module-title" id="moduleheading{{$key}}">
                                        <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#headingcollapse{{$key}}" aria-expanded="true" aria-controls="headingcollaps{{$key}}e">
                                            <i class="far fa-file"></i> {{ $content['que_toc_text'] }}
                                            @if(!empty($content['child']))
                                            <i class="fas fa-chevron-circle-down ps-2"></i>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div id="headingcollapse{{$key}}" class="accordion-collapse collapse" aria-labelledby="moduleheading{{$key}}" data-bs-parent="#modulelist-accordian">
                                    @if(!empty($content['child']))
                                    <ol>
                                        @foreach($content['child'] as $sub_content)
                                        <li>{{ $sub_content['que_toc_text'] }}</li>
                                        @endforeach
                                    </ol>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- module content sectuin end -->
                    @endif
                    <!-- student are viewing section start -->
                    @if(!empty($student_view_course))
                    <div class="student-r-view two-column-course">
                        <h2>Students are viewing</h2>
                        <div class="row">
                            @foreach($student_view_course as $key => $course_row)
                            <div class="col-lg-6 @if($key > 1) more @else less  @endif" style="@if($key > 1) display: none;  @endif">
                                <div class="course-block">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5">
                                            <div class="course-image">
                                                <a href="{{ route('coursedetails', $course_row->slug) }}">
                                                    <img src="{{ $course_row->course_image }}" alt="course" class="img-fluid">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-7 col-md-7">
                                            <div class="course-title"><a href="{{ route('coursedetails', $course_row->slug) }}">{{ $course_row->course_name }}</a></div>
                                            @php
                                            $rate =0;
                                            if($course_row->rate != 0){
                                            $rate = $course_row->rate / $course_row->total_record;
                                            }
                                            @endphp
                                            <div class="course-rating">
                                                <strong>{{ number_format((float)$rate, 1, '.', '') }}</strong>
                                                <div class="star-rating">
                                                    <span class="star-rating__fill" style="width: {{ $rate * 20 }}%">
                                                    </span>
                                                </div>
                                                @if($course_row->course_featured == 1)
                                                <i class="icon-diamond"></i>
                                                @endif
                                            </div>
                                            <div class="people-watch">
                                                <span class="icon-user me-2"></span>
                                                <span> {{ $course_row->views }}  (People are watching)</span>
                                            </div>
                                            <div class="course-price">
                                                <span>{{ getCurrencySymbol() }}{{ currencyConvert($course_row->course_price) }}</span>
                                                @if(in_array($course_row->course_id,$wishlist))
                                                <span class="icon-bookmark"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="studentview-showmore st-showmore">
                                    <a href="javascript:void(0)" class="btn btn-warning">Show More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- student are viewing section end -->
                </div>
            </div>
        </div>
    </section>
    <!-- course detail section end -->
    <!-- orange bar start -->
    <section class="edupme-orange-bar align-items-center justify-content-center d-flex">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-md-4">
                    <div class="bar-list mb-xl-0 mb-sm-0 mb-3">
                        <div class="bar-list-icons">
                            <img alt="Knolzi" width="16" height="16" src="@if(isset($slogan_section['homepage_slogan_section']['slogan_first_image'] )) {{ $slogan_section['homepage_slogan_section']['slogan_first_image'] }} @endif">
                        </div>
                        <div class="bar-list-title">
                            @if(isset($slogan_section['homepage_slogan_section']['slogan_first'])) {{ $slogan_section['homepage_slogan_section']['slogan_first'] }} @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bar-list mb-xl-0 mb-sm-0 mb-3">
                        <div class="bar-list-icons">
                            <img alt="Knolzi" width="16" height="16" src="@if(isset($slogan_section['homepage_slogan_section']['slogan_second_image'] )) {{ $slogan_section['homepage_slogan_section']['slogan_second_image'] }} @endif">
                        </div>
                        <div class="bar-list-title">
                            @if(isset($slogan_section['homepage_slogan_section']['slogan_second'])) {{ $slogan_section['homepage_slogan_section']['slogan_second'] }} @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bar-list">
                        <div class="bar-list-icons">
                            <img alt="Knolzi" width="16" height="16" src="@if(isset($slogan_section['homepage_slogan_section']['slogan_third_image'] )) {{ $slogan_section['homepage_slogan_section']['slogan_third_image'] }} @endif">
                        </div>
                        <div class="bar-list-title">
                            @if(isset($slogan_section['homepage_slogan_section']['slogan_third'])) {{ $slogan_section['homepage_slogan_section']['slogan_third'] }} @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- orange bar end -->
    <section class="course-detail-content-sec">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-xl-4 col-lg-10 col-md-10">
                </div>
                <div class="col-xxl-8 col-xl-8 col-lg-10 col-md-10">
                    @if(!empty($related_category))
                    <!-- top categories section start -->
                    <div class="top-categories">
                        <div class="top-categories-area">
                            <div class="categorie-list-title">
                                <h2>Categories Depending on your Search History</h2>
                            </div>
                            <div class="categories-list">
                                <ul>
                                    @foreach($related_category as $category)
                                    <li><a href="{{ route('categorycourses', $category['slug']) }}" class="btn btn-warning">{{ $category['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- top categories section end -->
                    @endif

                    @if(!empty($bundle_course))
                    <!-- make a bundle section start -->
                    <div class="makebundle-section two-column-course">
                        <h2>Make a Bundle</h2>
                        <div class="row">
                            @php
                            $cart_added=0;
                            $bundle_course_id=[];
                            @endphp
                            @foreach($bundle_course as $bundle_row)
                            @if(!in_array($bundle_row->course_id,$subscribe_course))
                            <div class="col-lg-6">
                                <div class="course-block">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="course-image">
                                                <a href="{{ route('coursedetails', $bundle_row->slug) }}"><img src="{{ $bundle_row->course_image }}" alt="{{ $bundle_row->course_name }}" class="img-fluid"></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="course-title"><a href="{{ route('coursedetails', $bundle_row->slug) }}">{{ $bundle_row->course_name }}</a></div>
                                            @php
                                            $bundle_course_id[] =$bundle_row->course_id;
                                            $rate =0;
                                            if($bundle_row->rate != 0){
                                            $rate = $bundle_row->rate / $bundle_row->total_record;
                                            }
                                            if(array_key_exists($bundle_row->course_id,$cart)) {
                                            $cart_added++;
                                            }
                                            @endphp
                                            <div class="course-rating">
                                                <strong>{{ number_format((float)$rate, 1, '.', '') }}</strong>
                                                <div class="star-rating">
                                                    <span class="star-rating__fill" style="width: {{ $rate * 20 }}%">
                                                    </span>
                                                </div>
                                                @if($bundle_row->course_featured == 1)
                                                <i class="icon-diamond"></i>
                                                @endif
                                            </div>
                                            <div class="people-watch">
                                                <span class="icon-user me-2"></span>
                                                <span>{{ $bundle_row->views }} (People are watching)</span>
                                            </div>
                                            <div class="course-price">
                                                <span>{{ getCurrencySymbol() }}{{ currencyConvert($bundle_row->course_price) }}</span>
                                                @if(in_array($bundle_row->course_id,$wishlist))
                                                <span class="icon-bookmark"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            <div class="col-lg-12">
                                <div class="studentview-showmore">
                                    @if($cart_added == count($bundle_course))
                                    <a href="{{ url('/cart') }}" class="btn btn-warning">Go to cart</a>
                                    @else
                                    <a href="javascript:void(0)" id="{{ implode(",", $bundle_course_id) }}" class="btn btn-warning add-all-to-cart">Add all to cart</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- make a bundle section end -->
                    @endif
                    @if(!$course_review->isEmpty())
                    <!-- student review section start -->
                    <div class="student-review">
                        <h2>Student's Reviews</h2>
                        @foreach($course_review as $review)
                        <div class="review-block">
                            <div class="user-icon">
                                <a href="javascript:void(0)">
                                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $review->user->name }}" width="50" height="50" class="img-fluid">
                                    <span>{{ $review->user->name }}</span>
                                </a>
                            </div>
                            <p>{{ $review['review'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="show-more-review text-center" data-page="2">
                        <button class="btn btn-primary">Show more</button>
                    </div>
                    <!-- student review section end -->
                    @endif
                    <!-- instructor section start -->
                    @if(!empty($authors))
                    <div class="instructor-review">
                        <h2>Instructors</h2>
                        @foreach($authors as $key => $author)
                        <div class="instructor-block" id="author-{{ $key }}">
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-5">
                                    <div class="course-image">
                                        <a href="/author/{{ $author['author_slug'] }}">
                                            @if($author['profile_image'] == "")
                                            <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $author['name'] }}" class="img-fluid">
                                            @else
                                            <img src="{{ $author['profile_image'] }}" alt="{{ $author['name'] }}" class="img-fluid">
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-7">
                                    <div class="course-title"><a href="/author/{{ $author['author_slug'] }}">{{ $author['name'] }}</a></div>
                                    @php
                                    $rate =0;
                                    if($author['rate'] != 0){
                                    $rate = $author['rate'] / $author['total_record'];
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
                                        <span> {{ $author['views'] }}  (People are watching)</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <p class="mt-2 mb-2">{!! $author['about_me'] !!}</p>
                                    <a href="/author/{{ $author['author_slug'] }}" class="text-primary">READ MORE <i class="fas fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <!-- instructor section end -->
                </div>
            </div>
        </div>
    </section>
    @section('script')
    <script>
        $(".applycoupon").click(function(e) {
            $(".apply-coupon-section").slideToggle();
        });
        $(".apply-coupon").click(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                url: "{{ route('ApplyBuynowCoupon') }}",
                type: 'POST',
                data: {'coupon_code': $(".coupon_code").val(), 'course_id': $(".course_id").val()},
                success: function(response) {
                    $(".error").html("");
                    if (response.success === true) {
//                        $('.course-price').html(response.html);
                        location.reload();
                    } else {
//                        $('.course-price').html(response.html);
                        $(".coupon_code").val('');
//                        $(".error").html(response.message);
                        swal({
                            title: "Error!",
                            text: response.message,
                            html: true,
                            type: "error"
                        },
                        function() {
                            location.reload();
                        });
                    }
                },
            }).fail(function(data) {
                $('.text-danger').empty();
                $('.loading').hide();
                var response = data.responseJSON.errors;
                var html = '';
                $.each(response, function(i, val) {
                    html += '<p>' + val[0] + '</p>';
                });
                $(".loading").hide();
                if (html == '') {
                    html = 'Something went wrong!';
                }
                swal({
                    title: "Error!",
                    text: html,
                    html: true,
                    type: "error"
                },
                function() {
                    location.reload();
                });
                /*var id = makeid(10);
                 var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + html + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                 $('body').append(toast);
                 new bootstrap.Toast(document.querySelector('#' + id)).show();*/
            });
            return false;
        });
    </script>
    @endsection
    @stop
</x-layout-front-base>
