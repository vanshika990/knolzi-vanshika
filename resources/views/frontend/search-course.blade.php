@extends('frontend.layouts.app')

@if(!empty($page_all_data['seo_meta']))
    @section('meta_title', $page_all_data['seo_meta']->title)
    @section('meta_description', $page_all_data['seo_meta']->description)
    @section('meta_keywords', $page_all_data['seo_meta']->keyword)
    @section('meta_image', asset('assets/front/images/logo.png'))
@endif

@section('content')
<!-- Search Results Header -->
<section class="w-full bg-gradient-primary py-12 mb-8">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-4xl md:text-5xl font-bold text-center text-text-white">All Courses</h1>
    </div>
</section>

<!-- Search Results Content -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-8">
    @if($page_all_data['all_course']->isEmpty())
        <div class="flex flex-col items-center justify-center py-24">
            <div class="glass-effect rounded-2xl p-8 text-center">
                <h2 class="text-2xl font-semibold mb-4 text-secondary">No Course Found</h2>
                <a href="{{ url('/') }}" class="btn-primary px-8 py-3 rounded-full font-semibold">Browse Courses</a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($page_all_data['all_course'] as $row)
            <div class="glass-effect-subtle rounded-2xl p-4 flex flex-col group hover-scale relative">
                <div class="relative">
                    <a href="{{ route('coursedetails', $row['slug']) }}">
                        <img src="{{ $row['course_image'] }}" alt="{{ $row['course_name'] }}" class="w-full h-40 object-cover rounded-xl mb-4 lazyload">
                    </a>
                    @if(!in_array($row['course_id'],$page_all_data['sub_course']))
                        @php
                            $inWishlist = isset($wishlist) && in_array($row['course_id'], $wishlist);
                        @endphp
                        <button type="button" class="absolute top-3 right-3 bg-bg-primary hover:bg-warning/10 text-warning rounded-full p-2 shadow add-to-wishlist-btn transition-colors flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-warning {{ $inWishlist ? 'wishlist-added' : '' }}" data-course-id="{{ $row['course_id'] }}" title="{{ $inWishlist ? 'In wishlist' : 'Add to wishlist' }}" aria-label="{{ $inWishlist ? 'In wishlist' : 'Add to wishlist' }}">
                            <span class="wishlist-icon">
                            @if($inWishlist)
                                <!-- Filled Heart -->
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                </svg>
                            @else
                                <!-- Outline Heart -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                </svg>
                            @endif
                            </span>
                        </button>
                    @endif
                </div>
                <div class="flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold mb-1"><a href="{{ route('coursedetails', $row['slug']) }}">{{ $row['course_name'] }}</a></h3>
                        <p class="text-sm text-text-light mb-2">By: {{ $row['author_name'] }}</p>
                        <p class="text-secondary text-sm mb-4">{{ $row['course_sub_description'] }}</p>
                        <div class="flex items-center mb-2">
                            @php
                                $rate = 0;
                                if($row['rate'] != 0){
                                    $rate = $row['rate'] / $row['total_record'];
                                }
                                $fullStars = floor($rate);
                                $halfStar = ($rate - $fullStars) >= 0.5 ? 1 : 0;
                                $emptyStars = 5 - $fullStars - $halfStar;
                            @endphp
                            <span class="text-yellow-400 font-bold mr-2">{{ number_format((float)$rate, 1, '.', '') }}</span>
                            <div class="flex items-center">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <!-- Full Star -->
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endfor
                                @if ($halfStar)
                                    <!-- Half Star -->
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <!-- Empty Star -->
                                    <svg class="w-4 h-4 text-text-light" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xl font-bold">{{ getCurrencySymbol() . currencyConvert($row['course_price']) }}</span>
                                                        @if(!empty($row['course_tag']))
                            <span class="bg-primary px-3 py-1 rounded-full text-xs font-semibold text-text-white ml-2">{{ $row['course_tag'] }}</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="flex items-center text-sm text-text-light">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $row['views'] ?? 0 }} (People are watching)
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        @if(in_array($row['course_id'],$page_all_data['sub_course']))
                            <p class="text-success font-semibold mb-2">You already purchased this course</p>
                            <a href="{{ route('getmycourse') }}" class="bg-warning hover:bg-warning/80 px-4 py-2 rounded-full text-center font-semibold text-text-white">Go to course</a>
                        @elseif(array_key_exists($row['course_id'],$page_all_data['cart']))
                            <a href="{{ url('/cart') }}" class="btn-primary px-4 py-2 rounded-full text-center font-semibold">Go to cart</a>
                        @else
                            <button type="button" id="{{ $row['course_id'] }}" class="btn-primary px-4 py-2 rounded-full text-center font-semibold add-to-cart flex items-center justify-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6" /></svg> Add to cart</button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-center mt-10">
            {!! $page_all_data['all_course']->links('frontend.layouts.pagination') !!}
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script type="text/javascript">
    // Add to Cart
    $(document).on("click", ".add-to-cart", function() {
        var course_id = $(this).attr("id");
        var btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/user/shopping-carts/me/add',
            data: {'course_id': course_id},
            type: 'POST',
            success: function(response) {
                btn.prop('disabled', false).html('<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6" /></svg> Add to cart');
                if (response.login == false) {
                    window.location.href = response.url;
                    return false;
                } else {
                    window.location.reload();
                }
            },
            error: function(data) {
                btn.prop('disabled', false).html('<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6" /></svg> Add to cart');
                var response = data.responseJSON.errors;
                var html = '';
                $.each(response, function(i, val) {
                    html += '<p>' + val[0] + '</p>';
                });
                if (html == '') {
                    html = 'Something went wrong!';
                }
                alert(html);
            }
        });
    });
    // Add/Remove to Wishlist
    $(document).on("click", ".add-to-wishlist-btn", function() {
        var btn = $(this);
        if (btn.prop('disabled')) return;
        var course_id = btn.data("course-id");
        var inWishlist = btn.hasClass('wishlist-added');
        var originalHtml = btn.find('.wishlist-icon').html();
        btn.prop('disabled', true);
        btn.find('.wishlist-icon').html('<svg class="animate-spin w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var url = inWishlist ? '/user/shopping-carts/me/remove-wishlist' : '/shopping-carts/me/wishlist';
        $.ajax({
            url: url,
            data: {'course_id': course_id},
            type: 'POST',
            success: function(response) {
                if (response.login == false) {
                    window.location.href = response.url;
                    return false;
                } else {
                    if (inWishlist) {
                        // Now removed from wishlist
                        btn.removeClass('wishlist-added');
                        btn.attr('title', 'Add to wishlist').attr('aria-label', 'Add to wishlist');
                        btn.find('.wishlist-icon').html('<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z" /></svg>');
                        showToast('Removed from wishlist!');
                    } else {
                        // Now added to wishlist
                        btn.addClass('wishlist-added');
                        btn.attr('title', 'In wishlist').attr('aria-label', 'In wishlist');
                        btn.find('.wishlist-icon').html('<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" /></svg>');
                        showToast('Added to wishlist!');
                    }
                    btn.prop('disabled', false);
                }
            },
            error: function(data) {
                btn.find('.wishlist-icon').html(originalHtml);
                btn.prop('disabled', false);
                var response = data.responseJSON && data.responseJSON.errors ? data.responseJSON.errors : null;
                var html = '';
                if (response) {
                    $.each(response, function(i, val) {
                        html += '<p>' + val[0] + '</p>';
                    });
                }
                if (html == '') {
                    html = 'Something went wrong!';
                }
                alert(html);
            }
        });
    });
    // Toast function
    function showToast(message) {
        if ($('#custom-toast').length) {
            $('#custom-toast').remove();
        }
        var toast = $('<div id="custom-toast" class="fixed bottom-6 right-6 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300 opacity-0">' + message + '</div>');
        $('body').append(toast);
        setTimeout(function() {
            toast.addClass('opacity-100');
        }, 10);
        setTimeout(function() {
            toast.removeClass('opacity-100');
            setTimeout(function() { toast.remove(); }, 300);
        }, 2000);
    }
</script>
@endpush
