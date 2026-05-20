<x-layout-front-base>
    @section('meta_title', 'Cart')
    @section('meta_description', 'Cart')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Shopping Cart</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <!-- cart page secttion start -->
    <section class="shop-cart-sec mb-5 mt-5">
        <div class="container">
            <div class="row">
                @if(empty($cart_data))
                <div class="col-md-12">
                    <!-- no course - no details found section start -->
                    <div class="no-found">
                        <div class="container">
                            <div class="no-found-text">
                                <p>Your cart is empty. Keep shopping to find a courses!</p>
                                <a href="{{ url('/')}}" class="mt-2 btn btn-primary">Browse Courses?</a>
                            </div>
                        </div>
                    </div>
                    <!-- no course - no details found section end -->
                </div>
                @else
                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-7 order-lg-1 order-md-1 order-1 cart-left">
                    <h4 class="mb-2 d-block font-bd">{{$total_course_count}} Courses in Cart</h4>
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
                                        @if(is_numeric($row->discount_price))
                                        <span><del>{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</del></span><br/><span>{{ getCurrencySymbol() }}{{ $row->discount_price }}</span>
                                        @else 
                                        <span>{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                                        @endif
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
                </div>    
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-5 order-lg-1 order-md-1 order-12 mb-md-4">
                    <div class="cart-total-box">
                        <span>Total:</span>
                        <div class="course-price total-price">
                            <span>{{ getCurrencySymbol() }}{{ $total_price }}</span>
                        </div>
                        @if(Auth::check())
                        <a href="{{ route('getcheckout') }}" class="btn btn-warning mb-3">Checkout</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-warning mb-3">Checkout</a>                        
                        @endif
                        @if(session()->get('coupon_code',''))
                        <div class="coupon-code-rmv"><label>{{ session()->get('coupon_code','') }}</label> <a href="{{ route('remove-coupon-from-cart') }}"><i class="fas fa-trash-alt"></i></a></div>
                        @endif
                        <div class="input-group apply-coupon-section">
                            <input type="text" name="discounts" class="form-control coupon_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
                            <button class="btn btn-primary apply-coupon" type="button" id="button-addon2">Apply</button>
                        </div>
                        <label class="error" style="display: none;"></label>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    <!-- cart page secttion end -->
    @section('script')
    <script type="text/javascript">
        $(".apply-coupon").click(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                url: "{{ route('applyCoupon') }}",
                type: 'POST',
                data: {'coupon_code': $(".coupon_code").val()},
                success: function(response) {
                    $(".error").html("").hide();
                    if (response.success === true) {
                        $('.cart-left').html(response.html);
                        $(".total-price > span").html(response.total_price);
                        location.reload();
                    } else {
                        $(".coupon_code").val('');
                        $('.cart-left').html(response.html);
                        $(".total-price > span").html(response.total_price);
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
                    html += val[0];
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
            });
            return false;
        });
        function removefromcart(identifier) {
            swal({
                title: 'Are you sure you want to remove this course?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, do it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    $(".loading").show();
                    var _url = '{{ route("remove-from-cart", ":id") }}';
                    var id = $(identifier).data('id');
                    _url = _url.replace(':id', id);
                    $.ajax({
                        url: _url,
                        type: 'GET',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success == true) {
                                swal({
                                    title: "Success!",
                                    text: response.message,
                                    html: true,
                                    type: "success"
                                },
                                function() {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: "Somethign went wrong please try again",
                                    html: true,
                                    type: "error"
                                },
                                function() {
                                    location.reload();
                                });
                            }
                            $(".loading").hide();
                        },
                    }).fail(function(data) {
                        $('.text-danger').empty();
                        $('.loading').hide();
                        var response = data.responseJSON.errors;
                        var html = '';
                        $.each(response, function(i, val) {
                            html += val[0];
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
                    });
                    return false;
                }
            });
        }

        function MoveToWishlist(identifier) {
            $(".loading").show();
            var _url = '{{ route("move-to-wishlist", ":id") }}';
            var id = $(identifier).data('id');
            _url = _url.replace(':id', id);

            $.ajax({
                url: _url,
                type: 'GET',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success == true) {
                        swal({
                            title: "Success!",
                            text: response.message,
                            html: true,
                            type: "success"
                        },
                        function() {
                            location.reload();
                        });
                    } else {
                        swal({
                            title: "Error!",
                            text: "Somethign went wrong please try again",
                            html: true,
                            type: "error"
                        },
                        function() {
                            location.reload();
                        });
                    }
                    $(".loading").hide();
                },
            }).fail(function(xhr, textStatus, errorThrown) {
                $('.text-danger').empty();
                $('.loading').hide();
                var response = data.responseJSON.errors;
                var html = '';
                $.each(response, function(i, val) {
                    html += val[0];
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
            });
            return false;
        }
    </script>
    @endsection
    @stop
</x-layout-front-base>
