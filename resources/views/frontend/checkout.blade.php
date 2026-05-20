@extends('frontend.layouts.app')
@section('meta_title', 'Checkout')
@section('meta_description', 'Checkout')
@section('meta_image',asset('assets/front/images/logo.png'))

@push('styles')
<style>
    .gradient-text {
        background: linear-gradient(to right, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .glass-effect {
        backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .glass-effect-subtle {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .pattern-bg {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23433cca' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .btn-primary {
        background: linear-gradient(to right, #2563eb, #7c3aed);
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(to right, #1d4ed8, #6d28d9);
        transform: scale(1.05);
    }
    .btn-orange {
        background: linear-gradient(to right, #f97316, #ea580c);
        transition: all 0.2s ease;
    }
    .btn-orange:hover {
        background: linear-gradient(to right, #ea580c, #dc2626);
        transform: scale(1.05);
    }
    .checkout-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #312e81 100%);
        position: relative;
    }
    .checkout-header .pattern-bg {
        position: absolute;
        inset: 0;
        opacity: 0.20;
        z-index: 0;
    }
    .checkout-header .header-content {
        position: relative;
        z-index: 1;
    }
    .complete-payment-btn {
        width: 100%;
        padding: 1rem 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(90deg, #6366f1 0%, #2563eb 100%);
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 24px 0 rgba(99,102,241,0.15);
        transition: background 0.2s, transform 0.2s;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        letter-spacing: 0.02em;
        outline: none;
    }
    .complete-payment-btn:hover, .complete-payment-btn:focus {
        background: linear-gradient(90deg, #2563eb 0%, #6366f1 100%);
        transform: scale(1.03);
        outline: none;
    }
</style>
@endpush

@section('content')
<!-- Checkout Header Section -->
<section class="relative z-10 checkout-header py-16 overflow-hidden">
    <div class="pattern-bg"></div>
    <div class="max-w-7xl mx-auto px-6 text-center header-content">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800">
            Checkout
        </h1>
    </div>
</section>

<!-- Checkout Content -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2">
            <div class="glass-effect-subtle rounded-3xl p-8 animate-fade-in space-y-6">
                <h2 class="text-2xl font-bold mb-6">Order Details</h2>
                @foreach($checkout_data as $row)
                <div class="glass-effect rounded-xl p-6 hover:bg-white/10 transition-all duration-300 mb-4">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-32 h-32 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <div class="text-center w-full h-full flex items-center justify-center">
                                @if(!empty($row->course_image))
                                    <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="object-cover w-full h-full rounded-xl">
                                @else
                                    <svg class="w-8 h-8 text-blue-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold break-words mb-1">{{ $row->course_name }}</h3>
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                <div class="text-2xl font-bold">
                                    @if($row->is_discount == '1')
                                        <span class="line-through text-gray-400 mr-2">{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                                        <span>{{ getCurrencySymbol() }}{{ $row->dicount_course_price }}</span>
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
        <!-- Checkout Summary & Payment -->
        <div>
            <div class="glass-effect-subtle rounded-3xl p-8 animate-scale-in">
                <h3 class="text-2xl font-bold mb-6">Summary</h3>
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-500">Original Price:</span>
                        <span>{{ getCurrencySymbol() }}{{ $original_total }}/-</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-500">Coupon Discounts:</span>
                        <span>{{ getCurrencySymbol() }}{{ $total_discount_price }}/-</span>
                    </div>
                    <hr class="border-gray-600 my-4">
                    <div class="flex justify-between text-2xl font-bold">
                        <span>Total:</span>
                        <span>{{ getCurrencySymbol() }}{{ $total_price }}/-</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-2">Knolzi is required by law to collect applicable transaction taxes for purchases made in certain tax jurisdictions.</p>
                <p class="text-xs text-gray-400 mb-4">By completing your purchase you agree to these <a href="{{ route('terms') }}" class="underline text-blue-400">Terms of Service.</a></p>
                <h3 class="mt-3 mb-3 text-lg font-semibold">Payment Option</h3>
                @if(session()->get('country', '') == 'India')
                <div class="mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" checked>
                    <label class="form-check-label pymt-label ml-2" for="razorpay">
                        Payment with Razorpay <img src="{{ asset('assets/images/edupme-razorpay.svg') }}" class="inline-block ms-3" width="100" />
                    </label>
                </div>
                <div class="mb-4">
                    <input class="form-check-input" type="radio" name="payment_method" id="paytm" value="paytm">
                    <label class="form-check-label pymt-label ml-2" for="paytm">
                        Payment with PayTm <img src="{{ asset('assets/images/edupme-paytm.svg') }}" class="inline-block ms-3" width="50" />
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
                                data-name="Knolzi"
                                data-description="Knolzi Course payment"
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
                        <input type="hidden" name="order_id" value="{{ $orderId }}" class="form-control"/>
                        <input type="hidden" name="amount" value="{{ $total_price }}" class="form-control"/>
                        <button type="submit" class="complete-payment-btn">Complete payment</button>
                    </form>
                </div>
                @else
                <div class="mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" checked>
                    <label class="form-check-label pymt-label ml-2" for="razorpay">
                        Payment with Razorpay <img src="{{ asset('assets/images/edupme-razorpay.svg') }}" class="inline-block ms-3" width="100" />
                    </label>
                </div>
                <div class="mb-4">
                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                    <label class="form-check-label paypal-label ml-2" for="paypal">
                        Payment with Paypal <img src="{{ asset('assets/images/pp-logo.png') }}" class="inline-block ms-3" width="50" />
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
                                data-name="Knolzi"
                                data-description="Knolzi Course payment"
                                data-image="{{ asset('assets/front/images/logo.png') }}"
                                data-prefill.name="{{ auth()->user()->name }}"
                                data-prefill.email="{{ auth()->user()->email }}"
                                data-theme.color="#169cd8">
                        </script>
                    </form>
                </div>
                <div class="text-center" id="paytm_btn">
                    <form action="{{ route('paypal.processTransaction') }}" method="POST" >
                        @csrf
                        <input type="hidden" name="amount" value="{{ $total_price }}" class="form-control"/>
                        <button type="submit" class="complete-payment-btn">Complete payment</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=payment_method]').click(function() {
            var type = $(this).val();
            if (type == 'razorpay') {
                $('#paytm_btn').hide();
                $('#razorpay_btn').show();
            }
            if (type == 'paytm' || type == 'paypal') {
                $('#razorpay_btn').hide();
                $('#paytm_btn').show();
            }
        });
    });
</script>
@endpush
