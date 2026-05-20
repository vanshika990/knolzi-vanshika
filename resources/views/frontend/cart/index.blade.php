@extends('frontend.layouts.app')
    @section('meta_title', 'Cart')
    @section('meta_description', 'Cart')
    @section('meta_image', asset('assets/front/images/logo.png'))
    @section('content')
    <!-- Page Header -->
    <section class="bg-gray-100 py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-800">Shopping Cart</h1>
        </div>
    </section>
    <!-- Cart Section -->
    <section class="py-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                @if(empty($cart_data))
                <div class="w-full">
                    <div class="flex flex-col items-center justify-center bg-white rounded-lg shadow p-8">
                        <p class="text-lg text-gray-600 mb-4">Your cart is empty. Keep shopping to find a course!</p>
                        <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Browse Courses</a>
                    </div>
                </div>
                @else
                <!-- Cart Items -->
                <div class="w-full lg:w-2/3 space-y-6 cart-left">
                    <h4 class="text-xl font-semibold mb-4">{{$total_course_count}} Courses in Cart</h4>
                    @foreach($cart_data as $row)
                    <div class="flex bg-white rounded-lg shadow p-4 gap-4 items-center">
                        <div class="w-24 h-24 flex-shrink-0">
                            <a href="{{ route('coursedetails', $row->slug) }}">
                                <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="w-full h-full object-cover rounded">
                            </a>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-lg text-gray-800"><a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a></div>
                            <div class="text-sm text-gray-500 mb-2">By: Mr. {{ $row->author_name }}</div>
                            <div class="text-base font-semibold">
                                @if(is_numeric($row->discount_price))
                                    <span class="line-through text-gray-400 mr-2">{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                                    <span class="text-green-600">{{ getCurrencySymbol() }}{{ $row->discount_price }}</span>
                                @else
                                    <span class="text-gray-800">{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            @php $id= Crypt::encrypt($row->id); @endphp
                            <button onclick="removefromcart(this)" data-id="{{ $id }}" class="text-red-600 hover:underline text-sm">Remove</button>
                            @if(Auth::check())
                            <button onclick="MoveToWishlist(this)" data-id="{{ $id }}" class="text-blue-600 hover:underline text-sm">Move to wishlist</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Cart Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-semibold">Total:</span>
                            <span class="text-xl font-bold text-green-600 total-price">{{ getCurrencySymbol() }}{{ $total_price }}</span>
                        </div>
                        @if(Auth::check())
                        <a href="{{ route('getcheckout') }}" class="block w-full text-center py-2 mb-3 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold transition">Checkout</a>
                        @else
                        <a href="{{ route('login') }}" class="block w-full text-center py-2 mb-3 bg-yellow-500 text-white rounded hover:bg-yellow-600 font-semibold transition">Checkout</a>
                        @endif
                        @if(session()->get('coupon_code',''))
                        <div class="flex items-center justify-between bg-gray-100 rounded px-3 py-2 mb-3">
                            <span class="text-sm font-medium">{{ session()->get('coupon_code','') }}</span>
                            <a href="{{ route('remove-coupon-from-cart') }}" class="text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></a>
                        </div>
                        @endif
                        <div class="flex mb-2">
                            <input type="text" name="discounts" class="flex-1 border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200 coupon_code" placeholder="Coupon Code">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700 apply-coupon" type="button" id="button-addon2">Apply</button>
                        </div>
                        <label class="error text-red-500 text-sm mt-2 hidden"></label>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @section('script')
    <script type="text/javascript">
        // The same JS logic as before, you can copy from the old file or update as needed
    </script>
    @endsection
    @stop

