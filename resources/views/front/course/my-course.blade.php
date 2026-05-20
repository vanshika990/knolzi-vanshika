<x-layout-front-base>
    @section('meta_title', 'My Courses')
    @section('meta_description', 'My Courses - Knolzi)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>My Course</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <section class="shop-cart-sec mb-5 mt-5">
        <div class="container">
            <div class="row">
                @if(empty($subscribe_courses))
                <!-- no course - no details found section start -->
                <div class="no-found">
                    <div class="container">
                        <div class="no-found-text">
                            <p>You don't have any course</p>
                        </div>
                    </div>
                </div>
                <!-- no course - no details found section end -->
                @else
                @foreach($subscribe_courses as $row)
                <div class="col-lg-3 mb-3 col-md-4">
                    <div class="course-block">
                        <div class="course-image">
                            <a href="{{ route('coursedetails', $row->slug) }}"><img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="img-fluid"></a>
                        </div>
                        <div class="course-title"><a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a></div>
                        <small>By : Mr. {{ $row->author_name }}</small>
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
                        <div class="course-price">
                            <!--<span>{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>-->
                            @hasanyrole('organization')
                            @else
                            @if($row['state'] == "todo")
                            <a class="btn btn-primary float-end" href="{{ route('courselearn', encrypt($row->course_id)) }}">Start</a>
                            @else
                            <a class="btn btn-primary float-end" href="{{ route('courselearn', encrypt($row->course_id)) }}">Resume</a>
                            @endif
                            @endhasanyrole
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </section>

    @stop
</x-layout-front-base>
