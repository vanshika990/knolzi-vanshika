
<span class="total_course_count">{{$total_course_count}} Courses in Cart</span>
@foreach($cart_data as $row)
<div class="cart-course">
    <div class="course-block">
        <div class="row">
            <div class="col-lg-3 col-sm-4 col-4">
                <div class="course-image">
                    <a href="{{ route('coursedetails', $row->slug) }}">
                        <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="img-fluid">
                    </a>
                </div>
            </div>
            <div class="col-lg-5 col-sm-7 col-7">
                <div class="course-title"><a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a></div>
                <small>By : Mr. {{ $row->author_name }}</small>
                <div class="course-price">
                    <span>{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                </div>
            </div>
            <div class="col-xxl-2 col-xl-3 col-lg-3 col-sm-12">
                <div class="cart-rm-vw">
                    <ul>
                        @php $id= Crypt::encrypt($row->id); @endphp
                        <li><a href="javascript:void(0)" data-id = "{{ $id }}" onclick="removefromcart(this)">Remove</a></li>
                        @if(Auth::check())
                        <li><a href="javascript:void(0)" data-id = "{{ $id }}" onclick="MoveToWishlist(this)">Move to wishlist</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>                        
</div>
@endforeach
