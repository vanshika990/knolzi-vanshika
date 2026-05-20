<x-layout-front-base>
    @if(!empty($page_all_data['seo_meta']))
    @section('meta_title', $page_all_data['seo_meta']->title)
    @section('meta_description', $page_all_data['seo_meta']->description)
    @section('meta_keywords', $page_all_data['seo_meta']->keyword)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @endif
    @section('content')
    <!-- all courses section start -->
    <section class="mt-5 mb-3 cat-all-course">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="course-tab-content-heading">
                        <h2>All Courses</h2>
                        <div class="border mt-3 mb-3"></div>
                    </div>  
                </div>
                @if(count($page_all_data['all_course']) == '0')
                <div class="alert alert-primary" role="alert">
                    No Course Found
                </div> 
                @else
                @foreach($page_all_data['all_course'] as $row)
                <div class="col-lg-3 col-md-4">
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
                         @if(in_array($row['course_id'],$page_all_data['sub_course']))
                         <p><b>You already purchased this course</b></p>
                        <a href="{{ route('getmycourse') }}" class="btn btn-warning">Go to course</a> 
                        @elseif(array_key_exists($row['course_id'],$page_all_data['cart']))
                        <a href="{{ url('/cart') }}" class="btn btn-primary goto-cart-{{ $row['course_id'] }} ">Go to cart</a>                          
                        @else
                        <a href="javascript:void(0)" id="{{ $row['course_id'] }}" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>
                        @endif
                        @if(!in_array($row['course_id'],$page_all_data['sub_course']))
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
            <small>{{ $row['author_name'] }}</small>
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
                <span> {{ $row['views'] }}  (People are watching)</span>
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
        </div>
        </div>
    </section>
    <!-- all courses section end -->
    <div class="container d-flex justify-content-center">
        <nav aria-label="Page navigation example" class="mb-5">
            {!! $page_all_data['all_course']->links('vendor.pagination.custom') !!}
        </nav>
    </div>
    @stop
</x-layout-front-base>
