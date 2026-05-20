<x-layout-front-base>
    @section('meta_title', 'Checkout')
    @section('meta_description', 'Checkout')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->

    <section class="checkout-page mt-5 mb-5">
        <div class="container">
            <div class="row">
                @if(\Session::has('error'))
                <div class="alert alert-danger">{{ \Session::get('error') }}</div>
                {{ \Session::forget('error') }}
                @endif
                @if(\Session::has('success'))
                <div class="alert alert-success">{{ \Session::get('success') }}</div>
                {{ \Session::forget('success') }}
                @endif
                <x-message/>
                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-7 order-lg-1 order-md-1 order-1">
                    <div class="checkout-form">
                        <div class="order-details">
                            <h1>Order Details</h1>
                            @foreach($checkout_data as $row)
                            <div class="cart-course">
                                <div class="course-block">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-4 col-5">
                                            <div class="course-image">
                                                <a href="">
                                                    <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="img-fluid">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-sm-7 col-7">
                                            <div class="course-title">
                                                <a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a>
                                            </div>
                                            <div class="course-price">
                                                @if($row->is_discount == '1')
                                                <span><del>{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</del></span><br/><span>{{ getCurrencySymbol() }}{{ $row->dicount_course_price }}</span>
                                                @else
                                                <span>{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-5 order-lg-1 order-md-1 order-12 mb-md-4">
                    <div class="checkout-summary">
                        <h3>Summary</h3>
                        <ul>
                            <li><p>Original Price</p><span>{{ getCurrencySymbol() }}{{ $original_total }}/-</span></li>
                            <li><p>Coupon Discounts</p><span>{{ getCurrencySymbol() }}{{ $total_discount_price }}/-</span></li>
                        </ul>
                        <div class="border"></div>
                        <ul class="chk-summ-total">
                            <li><p>Total:</p><span>{{ getCurrencySymbol() }}{{ $total_price }}/-</span></li>
                        </ul>
                        <p>Knolzi is required by law to collect applicable transaction taxes for purchases made in certain tax jurisdictions. </p>
                        <p>By completing your purchase you agree to these <a href="{{ route('terms') }}">Terms of Service.</a></p>
                        <h3 class="mt-3 mb-3">Payment Option</h3>

                        @if(session()->get('country', '') == 'India')
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" checked>
                            <label class="form-check-label pymt-label" for="razorpay">
                                Payment with Razorpay <img src="{{ asset('assets/images/edupme-razorpay.svg') }}" class="img-fluid ms-3" width="100" />
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="paytm" value="paytm">
                            <label class="form-check-label pymt-label" for="paytm">
                                Payment with PayTm <img src="{{ asset('assets/images/edupme-paytm.svg') }}" class="img-fluid ms-3" width="50" />
                            </label>
                        </div>
                        <div class="card-body text-center pt-2 p-0" id="razorpay_btn">
                            <form action="{{ route('razorpay.payment.store') }}" method="POST" >
                                @csrf
                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="{{ env('RAZORPAY_KEY','rzp_live_xEvpWXVcg1Knbn') }}"
                                        data-amount="{{ $total_price * 100 }}"
                                        data-order_id="{{ $orderId }}"
                                        data-buttontext="Complete payment"
                                        data-name="Knolzi
                                        data-description="knolzi Course payment"
                                        data-image="{{ asset('assets/front/images/logo.png') }}"
                                        data-prefill.name="{{ auth()->user()->name }}"
                                        data-prefill.email="{{ auth()->user()->email }}"
                                        data-theme.color="#169cd8">
                                </script>
                            </form>
                        </div>
                        <div class="text-center" id="paytm_btn" style="display:none;">
                            <form method="post" action="{{route('paytm.payment')}}">
                                @csrf
                                <input type="hidden" name="order_id" placeholder="Order id" value="{{ $orderId }}" class="form-control"/>
                                <input type="hidden" name="amount" placeholder="Amount" value="{{ $total_price }}" class="form-control"/>
                                <button type="submit" class="btn btn-warning" >Complete payment</button>
                            </form>
                        </div>
                        @else
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" checked>
                            <label class="form-check-label pymt-label" for="razorpay">
                                Payment with Razorpay <img src="{{ asset('assets/images/edupme-razorpay.svg') }}" class="img-fluid ms-3" width="100" />
                            </label>
                        </div>
                         <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" checked>
                            <input type="hidden" name="amount" placeholder="Amount" value="{{ $total_price }}" class="form-control"/>
                            <label class="form-check-label paypal-label" for="paytm">
                                Payment with Paypal <img src="{{ asset('assets/images/pp-logo.png') }}" class="img-fluid ms-3" width="50" />
                            </label>
                        </div>
                        <div class="card-body text-center pt-2 p-0" id="razorpay_btn" style="display:none;">
                            <form action="{{ route('razorpay.payment.store') }}" method="POST" >
                                @csrf
                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="{{ env('RAZORPAY_KEY','rzp_live_xEvpWXVcg1Knbn') }}"
                                        data-amount="{{ $total_price * 100 }}"
                                        data-order_id="{{ $orderId }}"
                                        data-buttontext="Complete payment"
                                        data-name="Knolzi
                                        data-description="knolzi Course payment"
                                        data-image="{{ asset('assets/front/images/logo.png') }}"
                                        data-prefill.name="{{ auth()->user()->name }}"
                                        data-prefill.email="{{ auth()->user()->email }}"
                                        data-theme.color="#169cd8">
                                </script>
                            </form>
                        </div>
                        <div class="text-center" id="paytm_btn" >
                            <form action="{{ route('paypal.processTransaction') }}" method="POST" >
                            @csrf
                            <input type="hidden" name="amount" value="{{ $total_price }}" class="form-control"/>
                            <button type="submit" name="submit" class="btn btn-warning" >Complete payment</button>
                        </form>
                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </section>
    @section('script')
    <script>
        $(document).ready(function() {
            $('input[name=payment_method]').click(function() {
                var type = $(this).val();
                if (type == 'razorpay') {
                    $('#paytm_btn').hide();
                    $('#razorpay_btn').show();
                }
                if (type == 'paytm') {
                    $('#razorpay_btn').hide();
                    $('#paytm_btn').show();
                }
                if (type == 'paypal') {
                    $('#razorpay_btn').hide();
                    $('#paytm_btn').show();
                }
            });
        });
    </script>
    @endsection
    @stop
</x-layout-front-base>
