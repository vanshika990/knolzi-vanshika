@extends('frontend.layouts.app')

@if(!empty($page_all_data))
    @section('meta_title', $page_all_data['meta_title'])
    @section('meta_description', $page_all_data['meta_description'])
    @section('meta_keywords', $page_all_data['meta_keyword'])
    @section('meta_image', asset('assets/front/images/logo.png'))
@endif

@push('styles')
<style>
    .gradient-text-warm {
        background: #ffffff;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .explore-btn {
        background: var(--color-text-white);
        transition: all var(--transition-fast);
        color: var(--gradient-primary);
    }
</style>
@endpush

@section('content')

    <!-- Hero Section -->
    <section class="relative max-w-7xl mx-auto px-6 py-10 flex items-center justify-center min-h-[294px] mt-10">
        <!-- Background Image with Blend Effect and Gradient Overlay -->
        <div class="absolute inset-0 rounded-3xl overflow-hidden">

            <img src="{{ $page_all_data['hero_image_image'] ?? '' }}" alt="{{ strip_tags($page_all_data['hero_image_title'] ?? '') }}" class="w-full h-full object-cover object-center mix-blend-multiply opacity-90" />
        </div>
        <div class="relative z-10 w-full flex flex-col items-center justify-center text-center px-4 py-12">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                <span class="gradient-text-warm">{!! $page_all_data['hero_image_title'] ?? '' !!}</span>
            </h1>
            <div class="text-xl md:text-2xl text-text-white/80 mb-8 max-w-xl mx-auto gradient-text-warm">
                {!! $page_all_data['hero_image_description'] ?? '' !!}
            </div>
            <a href="{{ $page_all_data['hero_image_btn_url'] ?? '#' }}" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold shadow-lg inline-block explore-btn">
                {{ $page_all_data['hero_image_btn_name'] ?? 'Explore' }}
            </a>
        </div>
    </section>

    <!-- Most Popular Courses Section -->
    <section class="max-w-7xl mx-auto px-6 py-14 bg-bg-primary">
        <div class="mb-10 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-2 text-text-primary">{{ $page_all_data['cat_name'] ?? '' }} Courses</h2>
            <div class="w-24 h-1 bg-gradient-primary mx-auto mb-4"></div>
            <h4 class="text-xl font-semibold text-primary">Most Popular</h4>
        </div>
        @if(count($page_all_data['most_popular']) == 0)
            <div class="flex flex-col items-center justify-center py-16 relative z-10">
                <div class="bg-bg-light rounded-2xl p-8 text-center shadow-lg max-w-xl mx-auto border border-border">
                    <h2 class="text-2xl font-semibold mb-4 text-text-primary">No courses found in this category!</h2>
                    <p class="text-text-secondary mb-4">Try browsing all available courses or check back later.</p>
                    <a href="{{ url('/') }}" class="btn-primary px-8 py-3 rounded-full font-semibold">Browse Courses</a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($page_all_data['most_popular'] as $row)
                <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-bg-light/10 transition-all duration-300 group hover-scale">
                    <a href="{{ route('coursedetails', $row->slug) }}">
                        <div class="w-full h-[120px] bg-gradient-to-br from-bg-light/20 to-bg-secondary/20 rounded-xl mb-4 flex items-center justify-center">
                        <img alt="course" src="{{ $row['course_image'] }}" class="w-full h-[120px] object-cover rounded-xl ">
                        </div>
                        <h4 class="text-lg font-bold mb-2 text-text-primary">{{ $row->course_name }}</h4>
                        <p class="text-sm text-text-light mb-3">By: {{ $row->author_name }}</p>
                        <p class="text-secondary text-sm mb-4">{{ $row->course_sub_description }}</p>
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
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07
                                                3.292a1 1 0 00.95.69h3.462c.969 0
                                                1.371 1.24.588 1.81l-2.8 2.034a1
                                                1 0 00-.364 1.118l1.07 3.292c.3.921-.755
                                                1.688-1.54 1.118l-2.8-2.034a1 1
                                                0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
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
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07
                                                3.292a1 1 0 00.95.69h3.462c.969 0
                                                1.371 1.24.588 1.81l-2.8 2.034a1
                                                1 0 00-.364 1.118l1.07 3.292c.3.921-.755
                                                1.688-1.54 1.118l-2.8-2.034a1 1
                                                0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-text-light">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $row->views }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-text-primary">{{ getCurrencySymbol() . currencyConvert($row->course_price) }}</span>
                            @if(!empty($row->course_tag))
                            <span class="bg-primary px-3 py-1 rounded-full text-xs font-semibold text-text-white">{{ $row->course_tag }}</span>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- All Courses Section -->
    @if(count($page_all_data['all_course']) > 0)
    <section class="max-w-7xl mx-auto px-6 py-16 bg-bg-primary">
        <div class="mb-12 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-2 text-text-primary">All {{ $page_all_data['cat_name'] ?? '' }} Courses</h2>
            <div class="w-24 h-1 bg-gradient-primary mx-auto mb-4"></div>
        </div>
        @if(count($page_all_data['all_course']) == 0)
            <div class="flex flex-col items-center justify-center py-12 border-2 border-dashed border-primary rounded-2xl bg-bg-light max-w-xl mx-auto shadow-lg relative z-10">
                <p class="text-lg text-text-primary font-semibold mb-2">No courses found in this category!</p>
                <p class="text-text-secondary mb-4">Try browsing all available courses or check back later.</p>
                <a href="{{ url('/') }}" class="btn-primary px-6 py-3 rounded-full text-lg font-semibold">Browse Courses</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($page_all_data['all_course'] as $row)
                <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-bg-light/10 transition-all duration-300 group hover-scale">
                    <a href="{{ route('coursedetails', $row['slug']) }}">
                        <div class="w-full h-[120px] bg-gradient-to-br from-bg-light/20 to-bg-secondary/20 rounded-xl mb-4 flex items-center justify-center">
                            <img alt="course" src="{{ $row['course_image'] }}" class="w-full h-[120px] object-cover rounded-xl ">
                        </div>
                        <h4 class="text-lg font-bold mb-2 text-text-primary">{{ $row['course_name'] }}</h4>
                        <p class="text-sm text-text-light mb-3">By: {{ $row['author_name'] }}</p>
                        <p class="text-secondary text-sm mb-4">{{ $row['course_sub_description'] }}</p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-1">
                                @php
                                    $rating = 0.0;
                                    if($row['rate'] != 0){
                                        $rating = $row['rate'] / $row['total_record'];
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
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07
                                                3.292a1 1 0 00.95.69h3.462c.969 0
                                                1.371 1.24.588 1.81l-2.8 2.034a1
                                                1 0 00-.364 1.118l1.07 3.292c.3.921-.755
                                                1.688-1.54 1.118l-2.8-2.034a1 1
                                                0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
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
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07
                                                3.292a1 1 0 00.95.69h3.462c.969 0
                                                1.371 1.24.588 1.81l-2.8 2.034a1
                                                1 0 00-.364 1.118l1.07 3.292c.3.921-.755
                                                1.688-1.54 1.118l-2.8-2.034a1 1
                                                0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-text-light">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $row['views'] }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-text-primary">{{ getCurrencySymbol() . currencyConvert($row['course_price']) }}</span>
                            @if(!empty($row['course_tag']))
                            <span class="bg-primary px-3 py-1 rounded-full text-xs font-semibold text-text-white">{{ $row['course_tag'] }}</span>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </section>
    @endif
    <!-- Pagination -->
    <div class="max-w-7xl mx-auto px-6 flex justify-center mb-12">
        <nav aria-label="Page navigation example">
            {!! $page_all_data['all_course']->links('frontend.layouts.pagination') !!}
        </nav>
    </div>
@endsection
