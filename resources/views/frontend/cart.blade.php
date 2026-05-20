@extends('frontend.layouts.app')
@section('meta_title', 'Cart')
@section('meta_description', 'Cart')
@section('meta_image',asset('assets/front/images/logo.png'))

@push('styles')
<style>
    .cart-header {
        background: var(--gradient-primary);
        position: relative;
    }
    .cart-header .pattern-bg {
        position: absolute;
        inset: 0;
        opacity: 0.20;
        z-index: 0;
    }
    .cart-header .header-content {
        position: relative;
        z-index: 1;
    }
    /* Fix Apply button UI */
    .apply-coupon {
        border-radius: var(--radius-md);
        height: 48px;
        min-width: 90px;
        font-size: var(--text-base);
        font-weight: var(--font-semibold);
        margin-left: 0.5rem;
        box-shadow: none;
        outline: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .apply-coupon:active, .apply-coupon:focus {
        outline: 2px solid var(--color-primary);
        outline-offset: 2px;
    }
    .coupon_code {
        height: 48px;
        font-size: var(--text-base);
        width: 222px;
    }
</style>
@endpush

@section('content')
<!-- Cart Header Section -->
<section class="relative z-10 cart-header py-16 overflow-hidden">
    <div class="pattern-bg"></div>
    <div class="max-w-7xl mx-auto px-6 text-center header-content">
        <h1 class="text-4xl md:text-5xl font-bold text-white">
            Shopping Cart
        </h1>
    </div>
</section>

<!-- Cart Content -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2">
        <div class="bg-bg-primary border border-border rounded-3xl p-8 shadow-lg space-y-6">
          @if(empty($cart_data) || count($cart_data) == 0)
            <div class="text-center py-16">
                <p class="text-xl text-secondary mb-4">Your cart is empty. Keep shopping to find a course!</p>
                <a href="{{ url('/') }}" class="bg-gradient-primary hover:bg-gradient-primary px-6 py-3 rounded-lg text-text-white font-bold shadow-lg hover:shadow-xl transition-all duration-300">Browse Courses?</a>
            </div>
          @else
            <h2 class="text-2xl font-bold text-text-primary">{{ $total_course_count }} {{ Str::plural('Course', $total_course_count) }} in Cart</h2>
            @foreach($cart_data as $row)
            <div class="bg-bg-primary border border-border rounded-xl p-6 hover:shadow-lg transition-all duration-300">
              <div class="flex flex-col md:flex-row gap-6">
                <!-- Image -->
                <div class="w-full md:w-32 h-32 bg-gradient-to-br from-bg-light to-bg-secondary rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                  <div class="text-center w-full h-full flex items-center justify-center">
                    @if(!empty($row->course_image))
                      <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="object-cover w-full h-full rounded-xl">
                    @else
                      <svg class="w-8 h-8 text-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                      </svg>
                    @endif
                  </div>
                </div>
                <!-- Content -->
                <div class="flex-1 min-w-0">
                  <h3 class="text-lg font-bold break-words mb-1 text-text-primary">{{ $row->course_name }}</h3>
                  <p class="text-primary text-sm mb-2">By : Mr. {{ $row->author_name }}</p>
                                      <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                      <div class="flex gap-4">
                        @php $id= Crypt::encrypt($row->id); @endphp
                        <button class="text-error hover:text-error text-sm" data-id="{{ $id }}" onclick="removefromcart(this)">Remove</button>
                        @if(Auth::check())
                          <button class="text-primary hover:text-primary-dark text-sm" data-id="{{ $id }}" onclick="MoveToWishlist(this)">Move to wishlist</button>
                        @endif
                      </div>
                      <div class="text-2xl font-bold text-text-primary">
                        @if(is_numeric($row->discount_price) && $row->discount_price > 0)
                          <span class="line-through text-text-light mr-2">{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                          <span class="text-success">{{ getCurrencySymbol() }}{{ $row->discount_price }}</span>
                        @else
                          <span class="text-success">{{ getCurrencySymbol() }}{{ currencyConvert($row->course_price) }}</span>
                        @endif
                      </div>
                    </div>
                </div>
              </div>
            </div>
            @endforeach
          @endif
        </div>
      </div>
      <!-- Cart Summary -->
      @if(!empty($cart_data) && count($cart_data) > 0)
      <div>
        <div class="bg-bg-primary border border-border rounded-3xl p-8 shadow-lg">
          <h3 class="text-2xl font-bold mb-6 text-text-primary">Order Summary</h3>
          <div class="space-y-4 mb-6">
            <div class="flex justify-between text-lg">
              <span class="text-secondary">Subtotal:</span>
              <span class="text-text-primary">{{ getCurrencySymbol() }}{{ $total_price }}</span>
            </div>
            <div class="flex justify-between text-lg">
              <span class="text-secondary">Tax:</span>
              <span class="text-text-primary">₹0</span>
            </div>
            <hr class="border-border my-4">
            <div class="flex justify-between text-2xl font-bold">
              <span class="text-text-primary">Total:</span>
              <span class="text-success">{{ getCurrencySymbol() }}{{ $total_price }}</span>
            </div>
          </div>
          <!-- Coupon Input -->
          <div class="mb-6">
            <div class="flex gap-2">
              <input type="text" placeholder="Coupon Code" class="flex-1 px-4 py-3 bg-bg-primary border border-border rounded-lg text-text-primary placeholder-text-light focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary coupon_code" value="{{ session()->get('coupon_code','') }}" />
              <button class="bg-primary hover:bg-primary-dark text-text-white font-semibold px-5 py-3 transition-colors whitespace-nowrap apply-coupon" type="button">Apply</button>
            </div>
            <label class="error text-error mt-2" style="display: none;"></label>
            @if(session()->get('coupon_code',''))
              <div class="mt-2 flex items-center gap-2">
                <span class="bg-primary-light text-primary px-3 py-1 rounded text-sm">{{ session()->get('coupon_code','') }}</span>
                <a href="{{ route('remove-coupon-from-cart') }}" class="text-error hover:text-error text-sm"><i class="fas fa-trash-alt"></i> Remove</a>
              </div>
            @endif
          </div>
          <!-- Checkout Button -->
          @if(Auth::check())
            <a href="{{ route('getcheckout') }}" class="bg-gradient-primary hover:bg-gradient-primary w-full px-8 py-4 rounded-xl text-lg font-bold text-text-white block text-center shadow-lg hover:shadow-xl transition-all duration-300">Checkout</a>
          @else
            <a href="{{ route('login') }}" class="bg-gradient-primary hover:bg-gradient-primary w-full px-8 py-4 rounded-xl text-lg font-bold text-text-white block text-center shadow-lg hover:shadow-xl transition-all duration-300">Checkout</a>
          @endif
          <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="text-primary hover:text-primary-dark text-sm transition-colors">
              ← Continue Shopping
            </a>
          </div>
        </div>
      </div>
      @endif
    </div>
  </section>
@endsection

@push('scripts')
<script type="text/javascript">
    $(".apply-coupon").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name=\'csrf-token\']').attr('content')
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
                    location.reload();
                } else {
                    $(".coupon_code").val('');
                    $(".error").html(response.message).show();
                }
            },
        }).fail(function(data) {
            var response = data.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += val[0];
            });
            if (html == '') {
                html = 'Something went wrong!';
            }
            $(".error").html(html).show();
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
                    },
                }).fail(function(data) {
                    var response = data.responseJSON.errors;
                    var html = '';
                    $.each(response, function(i, val) {
                        html += val[0];
                    });
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
            },
        }).fail(function(xhr, textStatus, errorThrown) {
            var response = xhr.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += val[0];
            });
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
@endpush
