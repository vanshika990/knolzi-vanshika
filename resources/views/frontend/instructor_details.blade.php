@extends('frontend.layouts.app')

@section('meta_title', $user['profile_title'])
@section('meta_description', strip_tags($user['about_me']))
@section('meta_keywords', $user['name']." Knolzi")
@section('meta_image', asset('assets/front/images/logo.png'))

@section('content')
<!-- Instructor Header (No Logo/Avatar) -->
<section class="instructor-header relative z-40 w-full theme-blue-gradient py-12 mb-8 mt-0">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 flex flex-col items-center text-center gap-6 relative z-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-1">{{ $user['name'] }}</h1>
        <div class="text-lg text-white/80 mb-2">{{ $user['profile_title'] }}</div>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-4">
            <div class="bg-white/10 rounded-lg px-6 sm:px-8 py-4 text-white text-sm font-semibold flex flex-col items-center min-w-[120px] sm:min-w-[140px]">
                <span class="text-xs text-white/60">Total Students</span>
                <span class="text-2xl font-bold">{{ COUNT($student) }}</span>
            </div>
            <div class="bg-white/10 rounded-lg px-6 sm:px-8 py-4 text-white text-sm font-semibold flex flex-col items-center min-w-[120px] sm:min-w-[140px]">
                <span class="text-xs text-white/60">Reviews</span>
                <span class="text-2xl font-bold">{{ $review['total_review'] }}</span>
            </div>
        </div>
        @if(!empty($user['about_me']))
            <div class="text-white/90 text-base max-w-2xl mx-auto md:mx-0 bg-white/5 rounded-lg p-6 shadow-lg">
                {!! strip_tags($user['about_me']) !!}
            </div>
        @endif
    </div>
</section>

<!-- Instructor Courses -->
<section class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 py-8 bg-gray-50 min-h-screen">
    <div class="mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">My Courses <span class="text-blue-600">({{ $course_count }})</span></h2>
        <div class="h-1 w-20 bg-blue-500 rounded mb-4"></div>
    </div>
    @if(!empty($my_course) && count($my_course))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($my_course as $row)
            <div class="bg-white rounded-2xl p-4 flex flex-col group hover:shadow-lg transition-shadow duration-300 relative border border-gray-200">
                <div class="relative">
                    <a href="{{ route('coursedetails', $row['slug']) }}">
                        <img src="{{ $row['course_image'] }}" alt="{{ $row['course_name'] }}" class="w-full h-40 object-cover rounded-xl mb-4 lazyload">
                    </a>
                    @if(!in_array($row['course_id'],$subscribe_course))
                        @php
                            $inWishlist = isset($wishlist) && in_array($row['course_id'], $wishlist);
                        @endphp
                        <button type="button" class="absolute top-3 right-3 bg-white hover:bg-yellow-100 text-yellow-500 rounded-full p-2 shadow add-to-wishlist-btn transition-colors flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-yellow-400 {{ $inWishlist ? 'wishlist-added' : '' }}" data-course-id="{{ $row['course_id'] }}" title="{{ $inWishlist ? 'In wishlist' : 'Add to wishlist' }}" aria-label="{{ $inWishlist ? 'In wishlist' : 'Add to wishlist' }}">
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
                        <h3 class="text-lg font-bold mb-1 text-gray-800"><a href="{{ route('coursedetails', $row['slug']) }}" class="hover:text-blue-600 transition-colors">{{ $row['course_name'] }}</a></h3>
                        <p class="text-sm text-gray-600 mb-2">By: {{ $row['author_name'] }}</p>
                        <p class="text-gray-600 text-sm mb-4">{{ $row['course_sub_description'] }}</p>
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
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xl font-bold text-gray-800">{{ getCurrencySymbol() . currencyConvert($row['course_price']) }}</span>
                                                        @if(!empty($row['course_tag']))
                            <span class="theme-blue px-3 py-1 rounded-full text-xs font-semibold text-white ml-2">{{ $row['course_tag'] }}</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $row['courseView'] ?? 0 }} (People are watching)
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        @if(in_array($row['course_id'],$subscribe_course))
                            <p class="text-green-400 font-semibold mb-2">You already purchased this course</p>
                            <a href="{{ route('getmycourse') }}" class="btn-orange px-4 py-2 rounded-full text-center font-semibold">Go to course</a>
                        @elseif(array_key_exists($row['course_id'],$cart))
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
            {!! $my_course->links('frontend.layouts.pagination') !!}
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-24">
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-200 shadow-sm">
                <h2 class="text-2xl font-semibold mb-4 text-gray-600">No Courses Found</h2>
                <a href="{{ url('/') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-semibold transition-colors">Browse Courses</a>
            </div>
        </div>
    @endif
</section>
@endsection

@push('styles')
<style>
/* Ensure instructor header is always visible but below navigation */
.instructor-header {
    position: relative !important;
    z-index: 40 !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Ensure content sections are above background but below navigation */
section {
    position: relative;
    z-index: 10;
}

/* Ensure header and navigation stay on top */
header {
    z-index: 50 !important;
}

/* Ensure navigation dropdowns are above all content */
header nav .relative {
    z-index: 60 !important;
}

/* Ensure dropdown menus are at the top */
#courseDropdown,
[id^="submenu-"] {
    z-index: 70 !important;
    position: absolute !important;
}
</style>
@endpush

@push('scripts')
<script type="text/javascript">
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
