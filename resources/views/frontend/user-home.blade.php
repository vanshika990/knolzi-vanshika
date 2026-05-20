@extends('frontend.layouts.app')

@section('content')
<!-- Top Hero Section with Hindi Text -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-16">
    <div class="text-center">
        <div class="glass-effect rounded-3xl p-12 mb-8 animate-fade-in">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                <span class="gradient-text-warm">Millimeter Nahi</span>
                <br>
                <span class="gradient-text-cool">Centimeter Bano.</span>
            </h1>

            <p class="text-2xl md:text-3xl text-gray-500 mb-4 font-semibold">
                Growth Dekho, Sirf Personal hi Nahi Professional Bhi.
            </p>

            <p class="text-xl md:text-2xl text-gray-500">
                Ubhro <span class="gradient-text font-bold">knolzi</span> ke saath
            </p>
        </div>
    </div>
</section>

<!-- Selected Categories -->
@if(!empty($page_all_data['parent_cat']))
<section class="relative z-10 max-w-7xl mx-auto px-6 py-8">
    <div class="flex flex-wrap justify-center gap-4 mb-4">
        @foreach($page_all_data['parent_cat'] as $row)
            <a href="{{ route('categorycourses', $row['slug']) }}" class="btn-primary px-6 py-3 rounded-full font-semibold cursor-pointer">
                {{ $row['name'] }}
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- iLearn Section -->
@if(!empty($page_all_data['subscribed_course']))
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        <h2 class="text-4xl md:text-5xl font-bold mb-2 gradient-text">Let's Start, {{ $page_all_data['username'] ?? '' }}</h2>
        <div class="border-b-2 border-orange-400 w-24 mx-auto mb-4"></div>
        <h4 class="text-2xl font-semibold text-orange-400 mb-6">i Learn</h4>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($page_all_data['subscribed_course'] as $row)
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale shadow-lg">
            <a href="{{ route('coursedetails', $row['slug']) }}">
                <div class="w-full h-[120px] bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                    <img alt="course" src="{{ $row['course_image'] }}" class="object-cover h-full w-full lazyload">
                </div>
                <h4 class="text-lg font-bold mb-2">{{ $row['course_name'] }}</h4>
                <p class="text-gray-500 text-sm mb-4">{{ $row['course_sub_description'] }}</p>
                <div class="flex items-center justify-between">
                    <a href="{{ route('courselearn', encrypt($row['course_id'])) }}" class="btn-primary px-4 py-2 rounded-full text-sm font-semibold">Go to course</a>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Recommended for You Section -->
@if(!empty($page_all_data['recommended_course']))
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        <h2 class="text-4xl md:text-5xl font-bold mb-2 gradient-text">Recommended for you</h2>
        <div class="border-b-2 border-orange-400 w-24 mx-auto mb-4"></div>
        <div class="flex flex-wrap justify-center gap-4 mb-6">
            @foreach($page_all_data['child_cat'] as $row)
                <a href="{{ route('categorycourses', $row['slug']) }}" class="btn-secondary px-6 py-3 rounded-full font-semibold cursor-pointer">
                    {{ $row['name'] }}
                </a>
            @endforeach
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($page_all_data['recommended_course'] as $row)
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale shadow-lg">
            <a href="/course/{{ $row['slug'] }}">
                <div class="w-full h-[120px] bg-gradient-to-br from-green-500/20 to-blue-500/20 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                    <img alt="course" src="{{ $row['course_image'] }}" class="object-cover h-full w-full lazyload">
                </div>
                <h4 class="text-lg font-bold mb-2">{{ $row['course_name'] }}</h4>
                <p class="text-gray-500 text-sm mb-4">{{ $row['course_sub_description'] }}</p>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">By: {{ $row['author_name'] }}</span>
                    <div class="flex items-center space-x-1 text-sm">
                        @php
                            $rating = 0.0;
                            if($row['rate'] != 0){
                                $rating = $row['rate'] / $row['total_record'];
                            }
                            $fullStars = floor($rating);
                            $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) <= 0.75;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp
                        <span class="text-yellow-400 font-semibold">{{ number_format($rating, 1) }}</span>
                        <div class="flex space-x-0.5">
                            {{-- Full Stars --}}
                            @for ($i = 0; $i < $fullStars; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            {{-- Half Star --}}
                            @if ($halfStar)
                                <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="none">
                                    <defs>
                                        <linearGradient id="halfGrad">
                                            <stop offset="50%" stop-color="currentColor"/>
                                            <stop offset="50%" stop-color="#9CA3AF"/>
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#halfGrad)" d="M9.049 2.927c.3-.921 1.603-.921
                                        1.902 0l1.07 3.292a1 1 0
                                        00.95.69h3.462c.969 0 1.371
                                        1.24.588 1.81l-2.8 2.034a1
                                        1 0 00-.364 1.118l1.07
                                        3.292c.3.921-.755 1.688-1.54
                                        1.118l-2.8-2.034a1 1 0
                                        00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                                        1 0 00-.364-1.118L2.98
                                        8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                                        1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endif
                            {{-- Empty Stars --}}
                            @for ($i = 0; $i < $emptyStars; $i++)
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ getCurrencySymbol() . currencyConvert($row['course_price']) }}</span>
                    @if(!empty($row['course_tag']))
                    <span class="theme-blue px-3 py-1 rounded-full text-xs font-semibold text-white">{{ $row['course_tag'] }}</span>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Next In Line Section -->
@if(!empty($page_all_data['next_line']))
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        <h2 class="text-4xl md:text-5xl font-bold mb-2 gradient-text">Next In Line</h2>
        <div class="border-b-2 border-orange-400 w-24 mx-auto mb-4"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($page_all_data['next_line'] as $row)
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale shadow-lg">
            <a href="/course/{{ $row['slug'] }}">
                <div class="w-full h-[120px] bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                    <img alt="course" src="{{ $row['course_image'] }}" class="object-cover h-full w-full lazyload">
                </div>
                <h4 class="text-lg font-bold mb-2">{{ $row['course_name'] }}</h4>
                <p class="text-gray-500 text-sm mb-4 line-clamp-4 overflow-hidden">{{ $row['course_sub_description'] }}</p>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-400">By: {{ $row['author_name'] }}</span>
                    <div class="flex items-center space-x-1 text-sm">
                        @php
                            $rating = 0.0;
                            if($row['rate'] != 0){
                                $rating = $row['rate'] / $row['total_record'];
                            }
                            $fullStars = floor($rating);
                            $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) <= 0.75;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp
                        <span class="text-yellow-400 font-semibold">{{ number_format($rating, 1) }}</span>
                        <div class="flex space-x-0.5">
                            {{-- Full Stars --}}
                            @for ($i = 0; $i < $fullStars; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            {{-- Half Star --}}
                            @if ($halfStar)
                                <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="none">
                                    <defs>
                                        <linearGradient id="halfGrad">
                                            <stop offset="50%" stop-color="currentColor"/>
                                            <stop offset="50%" stop-color="#9CA3AF"/>
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#halfGrad)" d="M9.049 2.927c.3-.921 1.603-.921
                                        1.902 0l1.07 3.292a1 1 0
                                        00.95.69h3.462c.969 0 1.371
                                        1.24.588 1.81l-2.8 2.034a1
                                        1 0 00-.364 1.118l1.07
                                        3.292c.3.921-.755 1.688-1.54
                                        1.118l-2.8-2.034a1 1 0
                                        00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                                        1 0 00-.364-1.118L2.98
                                        8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                                        1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endif
                            {{-- Empty Stars --}}
                            @for ($i = 0; $i < $emptyStars; $i++)
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ getCurrencySymbol() . currencyConvert($row['course_price']) }}</span>
                    @if(!empty($row['course_tag']))
                    <span class="theme-blue px-3 py-1 rounded-full text-xs font-semibold text-white">{{ $row['course_tag'] }}</span>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Orange Bar Section -->
@if(!empty($page_all_data['slogan_first']) || !empty($page_all_data['slogan_second']) || !empty($page_all_data['slogan_third']))
<section class="relative z-10 max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if(!empty($page_all_data['slogan_first']))
        <div class="glass-effect-subtle rounded-2xl p-6 flex items-center space-x-4">
            <span class="text-orange-400 text-3xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-award" viewBox="0 0 16 16">
                    <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
                    <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
                </svg>
            </span>
            <span class="font-bold text-lg">{{ $page_all_data['slogan_first'] }}</span>
        </div>
        @endif
        @if(!empty($page_all_data['slogan_second']))
        <div class="glass-effect-subtle rounded-2xl p-6 flex items-center space-x-4">
            <span class="text-yellow-400 text-3xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                </svg>
            </span>
            <span class="font-bold text-lg">{{ $page_all_data['slogan_second'] }}</span>
        </div>
        @endif
        @if(!empty($page_all_data['slogan_third']))
        <div class="glass-effect-subtle rounded-2xl p-6 flex items-center space-x-4">
            <span class="text-purple-400 text-3xl">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-trophy" viewBox="0 0 16 16">
                    <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5c0 .538-.012 1.05-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33.076 33.076 0 0 1 2.5.5zm.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935zm10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935zM3.504 1c.007.517.026 1.006.056 1.469.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.501.501 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667.03-.463.049-.952.056-1.469H3.504z"/>
                </svg>
            </span>
            <span class="font-bold text-lg">{{ $page_all_data['slogan_third'] }}</span>
        </div>
        @endif
    </div>
</section>
@endif

<!-- Students are Viewing Section -->
@if(!empty($page_all_data['view_course']))
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        <h2 class="text-4xl md:text-5xl font-bold mb-2 gradient-text">Students are viewing</h2>
        <div class="border-b-2 border-orange-400 w-24 mx-auto mb-4"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($page_all_data['view_course'] as $row)
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale">
            <a href="{{ route('coursedetails', $row->slug) }}">
                <div class="w-full h-[120px] bg-gradient-to-br from-orange-500/20 to-yellow-500/20 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                    <img alt="course" src="{{ $row->course_image }}" class="object-cover h-full w-full lazyload">
                </div>
                <h4 class="text-lg font-bold mb-2">{{ $row->course_name }}</h4>
                <p class="text-sm text-gray-400 mb-3">By: {{ $row->author_name }}</p>
                <p class="text-gray-500 text-sm mb-4">{{ $row->course_sub_description }}</p>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-1">
                        @php
                            $rating = 0.0;
                            if($row->rate != 0){
                                $rating = $row->rate / $row->total_record;
                            }
                            $fullStars = floor($rating);
                            $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) <= 0.75;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp
                        <div class="flex items-center space-x-1 text-sm">
                            <span class="text-yellow-400 font-semibold">{{ number_format($rating, 1) }}</span>
                            <div class="flex space-x-0.5">
                                {{-- Full Stars --}}
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                                {{-- Half Star --}}
                                @if ($halfStar)
                                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="none">
                                        <defs>
                                            <linearGradient id="halfGrad">
                                                <stop offset="50%" stop-color="currentColor"/>
                                                <stop offset="50%" stop-color="#9CA3AF"/>
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#halfGrad)" d="M9.049 2.927c.3-.921 1.603-.921
                                            1.902 0l1.07 3.292a1 1 0
                                            00.95.69h3.462c.969 0 1.371
                                            1.24.588 1.81l-2.8 2.034a1
                                            1 0 00-.364 1.118l1.07
                                            3.292c.3.921-.755 1.688-1.54
                                            1.118l-2.8-2.034a1 1 0
                                            00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                                            1 0 00-.364-1.118L2.98
                                            8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                                            1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                                {{-- Empty Stars --}}
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ $row->views }}
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold">{{ getCurrencySymbol() . currencyConvert($row->course_price) }}</span>
                    @if(isset($row->course_tag))
                    <span class="theme-blue px-3 py-1 rounded-full text-xs font-semibold text-white ml-2">{{ $row->course_tag }}</span>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif
@endsection
