<x-layout-front-base>
    @section('meta_title', $user['profile_title'])
    @section('meta_description', strip_tags($user['about_me']))
    @section('meta_keywords', $user['name']." Knolzi")
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <section class="instructor-column mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 order-lg-1 order-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="instructor-profile mb-3">
                                <small>INSTRUCTOR</small>
                                <h1>{{ $user['name'] }}</h1>
                                <span>{{ $user['profile_title'] }}</span>
                            </div>
                            <div class="instructor-reviews d-flex align-items-center justify-content-start mb-lg-5 mb-2">
                                <div class="instru-total me-3">
                                    <small>Total Students</small>
                                    <p>{{ COUNT($student) }}</p>
                                </div>
                                <div class="instru-total">
                                    <small>Reviews</small>
                                    <p>{{ $review['total_review'] }}</p>
                                </div>
                            </div>
                        </div>
                        @if(!empty($user['about_me']))
                        <div class="col-lg-12">
                            <div class="instru-about mb-5">
                                <h4>About me</h4>
                                <p>{!! strip_tags($user['about_me']) !!}</p>
                            </div>
                        </div>
                        @endif

                        @if(!empty($my_course))
                        <div class="col-md-12">
                            <div class="course-tab-content-heading">
                                <h4>My Courses ({{ $course_count }})</h4>
                            </div>
                        </div>

                        @foreach($my_course as $key => $row)
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="course-block course-{{ $row['course_id'] }}" data-bs-html="true" data-bs-toggle="popover" data-bs-content='<div class="course-block-hover">
                                 <div class="course-title"><a href="javascript:void(0)">{{ $row["course_name"] }}</a>
                                 </div>
                                 <div class="course-update-date">
                                 <span>Updated {{ date('F, Y', strtotime($row["created_at"])) }}</span>
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
                                 @if(in_array($row['course_id'],$subscribe_course))
                                 <p><b>You already purchased this course</b></p>
                                <a href="{{ route('getmycourse') }}" class="btn btn-warning">Go to course</a>
                                @elseif(array_key_exists($row['course_id'],$cart))
                                <a href="{{ url('/cart') }}" class="btn btn-primary goto-cart-{{ $row['course_id'] }} ">Go to cart</a>
                                @else
                                <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>
                                @endif
                                @if(!in_array($row['course_id'],$subscribe_course))
                                <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-warning @if(in_array($row['course_id'],$wishlist)) remove-to-wishlist add @else add-to-wishlist @endif"><i class="icon-favourite"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>'>
                    <div class="course-image">
                        <a href="{{ route('coursedetails', $row['slug']) }}">
                            <img src="{{ $row['course_image'] }}" alt="{{ $row['course_name'] }}" class="img-fluid">
                        </a>
                    </div>
                    <div class="course-title"><a href="{{ route('coursedetails', $row['slug']) }}">{{ $row['course_name'] }}</a></div>
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
            @endif
            <div class="col-lg-12">
                <nav aria-label="Page navigation example" class="mb-5">
                    {!! $my_course->links('vendor.pagination.custom') !!}
                </nav>
            </div>
        </div>
        </div>
        <div class="col-lg-4 order-lg-1 order-12 mb-md-4 mb-4">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="instru-prof">
                        <div class="avatar mb-4">
                            @if(!empty($user['profile_image']))
                            <img src="{{ $user['profile_image'] }}" alt="{{ $user['name'] }}" class="img-raised rounded-circle img-fluid" width="230px" style="height: 230px">
                            @else
                            <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $user['name'] }}" class="img-raised rounded-circle img-fluid" width="230px" style="height: 230px">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
    @stop
</x-layout-front-base>
