@extends('frontend.layouts.app')
@section('meta_title', $course['meta_title'])
@section('meta_description', $course['meta_description'])
@section('meta_image', $course['course_image'])
@section('meta_keywords', $course['meta_keyword'])

@section('content')
<!-- Hero Banner / Course Overview Section -->
<section class="mb-10 relative z-10 mx-auto px-6 py-16">
    <!-- Enhanced Background with Theme Colors -->
    <div class="absolute inset-0 bg-gradient-to-br from-bg-primary/90 via-bg-secondary/70 to-bg-primary/95 rounded-3xl"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-bg-light/10 to-transparent rounded-3xl"></div>

    <div class="relative z-10 flex justify-center">
        <div class="bg-bg-light/20 backdrop-blur-sm rounded-3xl p-8 md:p-12 animate-fade-in w-full md:w-2/3 mx-auto text-center border border-border/30 shadow-xl">
            <!-- Breadcrumb in Course Info -->
            <nav class="mb-6 text-sm text-text-white/80 flex flex-row items-center gap-2 justify-center" aria-label="breadcrumb">
                <a href="/" class="hover:underline">Home</a>
                <span>/</span>
                @if(!empty($categories))
                    @foreach($categories as $category)
                        <a href="/category/{{ $category['slug'] }}" class="hover:underline">{{ $category['name'] }}</a>
                        <span>/</span>
                    @endforeach
                @endif
                <span class="text-text-white/60">{{ $course['course_name'] }}</span>
            </nav>
            <!-- Course Info -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-text-primary mb-4 leading-tight">{{ $course['course_name'] }}</h1>
            @if(!empty($course['course_tag']))
                <span class="inline-block bg-primary text-text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wide mb-2">{{ $course['course_tag'] }}</span>
            @endif
            <p class="text-lg text-text-secondary mb-6">{{ $course['course_sub_description'] }}</p>
            <div class="flex flex-wrap items-center gap-6 mb-4 justify-center">
                @if(!empty($authors))
                    @foreach($authors as $key => $author)
                    <div class="flex items-center gap-3">
                        <div class="text-left">
                            <div class="font-semibold text-text-primary">By {{ $author['name'] }}</div>
                            @php
                                $rating = 0.0;
                                if($rate != 0){
                                    $rating = $rate;
                                }
                                $fullStars = floor($rating);
                                $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) <= 0.75;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp
                            <div class="course-rating flex items-center gap-1 text-yellow-400 text-sm" aria-label="Course rating">
                                <span class="font-bold">{{ number_format((float)$rating, 1, '.', '') }}</span>
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
                    </div>
                    @endforeach
                @endif
                @if(!empty($languages))
                    <div class="flex items-center gap-1 text-text-secondary text-sm">
                        <i class="fas fa-language"></i>
                        @foreach($languages as $language)
                            {{ $language['name'] }}
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-3 text-text-secondary text-sm justify-center">
                <i class="fas fa-users"></i>
                {{ $course['courseView'] ?? 0 }} People are watching
            </div>
        </div>
    </div>
</section>
<!-- Price Box / Purchase Card -->
<section class="relative max-w-7xl mx-auto px-4 py-0 z-20">
    <div class="relative flex flex-col lg:flex-row gap-8">
        <!-- Course Details Card (Sticky & Overlay) -->
        <div class="w-full lg:w-1/3">
            <div class="lg:absolute lg:top-[20%] lg:left-0 lg:w-1/3 lg:z-30">
                <div class="sticky top-8">
                    <div class="bg-bg-light rounded-2xl p-6 shadow-xl border border-border transition-all duration-300 hover:shadow-2xl">
                        <!-- Course Image with Hover Effect -->
                        <div class="overflow-hidden rounded-xl mb-5 border border-border">
                            <img src="{{ $course['course_image'] }}" alt="{{ $course['course_name'] }}"
                                 class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <!-- Price Section -->
                        <div class="mb-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if(isset($course['course_price']) && $course['course_price'] > 0)
                                    @if(isset($course['total_dis_price']))
                                        <span class="text-5xl font-extrabold text-text-primary">{{ getCurrencySymbol() }}{{ $course['total_dis_price'] }}</span>
                                        <span class="text-xl line-through text-text-light">{{ getCurrencySymbol() }}{{ currencyConvert($course['course_price']) }}</span>
                                    @else
                                        <span class="text-5xl font-extrabold text-text-primary">{{ getCurrencySymbol() }}{{ currencyConvert($course['course_price']) }}</span>
                                    @endif
                                @else
                                    <span class="text-3xl font-bold text-primary">Free</span>
                                @endif
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            @if(in_array($course['course_id'], $subscribe_course))
                                <div class="text-center">
                                    <p class="text-primary font-semibold mb-3">You already purchased this course</p>
                                    <a href="{{ route('courselearn', encrypt($course['course_id'])) }}"
                                       class="block w-full bg-primary hover:bg-bg-secondary text-text-white text-center py-3 px-4 rounded-full font-semibold transition-colors">
                                        Continue Learning
                                    </a>
                                </div>
                            @else
                                @if(array_key_exists($course['course_id'], $cart))
                                    <a href="{{ url('/cart') }}"
                                       class="block w-full bg-primary hover:bg-bg-secondary text-text-white text-center py-3 px-4 rounded-full font-semibold transition-colors">
                                        Go to Cart
                                    </a>
                                @else
                                    <button id="{{ $course['course_id'] }}"
                                            class="w-full bg-primary hover:bg-bg-secondary text-text-white py-3 px-4 rounded-full font-semibold transition-colors add-to-cart">
                                        Add to Cart
                                    </button>
                                @endif
                                @if(!in_array($course['course_id'], $subscribe_course))
                                    @if(Auth::check())
                                        <a href="{{ route('BuynowCheckout', encrypt($course['course_id'])) }}"
                                           class="block w-full bg-bg-light border-2 border-primary text-primary hover:bg-bg-secondary/10 text-center py-3 px-4 rounded-full font-semibold transition-colors">
                                            Enroll Now
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="block w-full bg-bg-light border-2 border-primary text-primary hover:bg-bg-secondary/10 text-center py-3 px-4 rounded-full font-semibold transition-colors">
                                            Enroll Now
                                        </a>
                                    @endif
                                @endif
                            @endif
                        </div>
                        <!-- Course Includes -->
                        @if(!empty($course['course_include']))
                        <div class="border-t border-border pt-5 mt-5">
                            <h3 class="text-lg font-semibold text-text-primary text-center mb-3">This Course Includes</h3>
                            <ul class="space-y-2">
                                {!! str_replace('<li>', '<li class="flex items-start"><svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>', $course['course_include']) !!}
                            </ul>
                        </div>
                        @endif
                        <!-- Coupon Section -->
                        @if(!in_array($course['course_id'], $subscribe_course))
                        <div class="border-t border-border pt-5 mt-5">
                            <h3 class="text-lg font-semibold text-text-primary text-center mb-3">Apply Coupon</h3>
                            <div class="flex gap-2">
                                <input type="text"
                                       name="discounts"
                                       class="flex-1 rounded-l-lg border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                       placeholder="Enter coupon code"
                                       aria-label="Coupon Code">
                                <button class="bg-primary hover:bg-bg-secondary text-text-white px-4 rounded-r-lg font-medium transition-colors"
                                        type="button"
                                        id="button-addon2">
                                    Apply
                                </button>
                            </div>
                            @if(session()->get('coupon_code',''))
                            <div class="flex items-center justify-between bg-primary/10 rounded-lg px-4 py-2 mt-3">
                                <span class="text-sm font-medium text-primary">{{ session()->get('coupon_code','') }}</span>
                                <a href="{{ route('remove-coupon-from-cart') }}" class="text-red-500 hover:text-red-700" title="Remove coupon">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="w-full lg:w-2/3 lg:ml-auto space-y-8">
            <div class="bg-bg-light/20 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-border/30">
                <h2 class="text-2xl font-bold mb-4 text-text-primary">Description</h2>
                <div class="prose max-w-none text-text-secondary">{!! $course['course_description'] !!}</div>
                <button class="text-primary showmore mt-2">Show More <i class="fas fa-angle-down"></i></button>
                <button class="text-primary showless mt-2 hidden">Show Less <i class="fas fa-angle-up"></i></button>
            </div>
            <div class="bg-bg-light/20 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-border/30">
                <h2 class="text-2xl font-bold mb-4 text-text-primary">Requirements</h2>
                <div class="prose max-w-none text-text-secondary">{!! $course['course_requirement'] !!}</div>
            </div>
            <div class="bg-bg-light/20 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-border/30">
                <h2 class="text-2xl font-bold mb-4 text-text-primary">You'll learn in this module</h2>
                <div class="prose max-w-none text-text-secondary">{!! $course['course_applications'] !!}</div>
            </div>
            @if(!empty($course_content))
            <div class="bg-bg-light/20 backdrop-blur-sm rounded-2xl p-0 overflow-hidden border border-border/30" x-data="{ openSection: 0 }">
                <div class="p-8 pb-6">
                    <h2 class="text-2xl font-bold mb-1 text-text-primary">Course Content</h2>
                    <div class="flex items-center text-sm text-text-light mb-6">
                        <span class="flex items-center mr-4">
                            <i class="fas fa-list-ul mr-1.5"></i>
                            {{ count($course_content) }} {{ Str::plural('Module', count($course_content)) }}
                        </span>
                        @php
                            $totalLessons = 0;
                            foreach($course_content as $content) {
                                $totalLessons += !empty($content['child']) ? count($content['child']) : 0;
                            }
                        @endphp
                        <span class="flex items-center">
                            <i class="fas fa-play-circle mr-1.5"></i>
                            {{ $totalLessons }} {{ Str::plural('Lesson', $totalLessons) }}
                        </span>
                    </div>
                </div>
                <div class="border-t border-border/30">
                    <div id="modulelist-accordion" class="divide-y divide-border/20">
                        <template x-for="(content, key) in {{ json_encode(array_values($course_content)) }}" :key="key">
                        <div class="group" :class="{'bg-bg-light/10': openSection === key}">
                            <button
                                @click="openSection === key ? openSection = null : openSection = key"
                                class="w-full px-8 py-4 text-left flex items-center justify-between focus:outline-none hover:bg-bg-light/5 transition-colors duration-200"
                                :aria-expanded="openSection === key"
                                :aria-controls="'module-' + key"
                            >
                                <div class="flex items-center">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/30 text-primary flex items-center justify-center mr-3">
                                        <i class="fas fa-folder text-sm"></i>
                                    </span>
                                    <span class="font-medium text-text-primary" x-text="content.que_toc_text"></span>
                                </div>
                                <div class="flex items-center">
                                    <template x-if="content.child && content.child.length">
                                        <span class="text-xs bg-bg-light/10 text-text-light rounded-full px-2.5 py-1 mr-3" x-text="content.child.length + ' ' + (content.child.length === 1 ? 'lesson' : 'lessons')"></span>
                                    </template>
                                    <svg
                                        class="w-5 h-5 text-text-light transform transition-transform duration-200"
                                        :class="{ 'rotate-180': openSection === key }"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>
                            <div
                                x-show="openSection === key"
                                x-collapse
                                :id="'module-' + key"
                                class="bg-bg-light/3"
                            >
                                <template x-if="content.child && content.child.length">
                                <ul class="py-2">
                                    <template x-for="(sub_content, subKey) in content.child" :key="subKey">
                                    <li class="border-t border-border/20 first:border-0">
                                        <a
                                            href="#"
                                            class="flex items-center px-8 py-3 hover:bg-bg-light/5 transition-colors duration-150 group/item"
                                        >
                                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-bg-light/5 text-text-light flex items-center justify-center mr-3 group-hover/item:bg-primary/20 group-hover/item:text-primary transition-colors">
                                                <i class="fas fa-play text-xs"></i>
                                            </span>
                                            <span class="text-text-secondary flex-1" x-text="sub_content.que_toc_text"></span>
                                            <span class="text-xs text-text-light group-hover/item:text-text-secondary">5:30</span>
                                        </a>
                                    </li>
                                    </template>
                                </ul>
                                </template>
                            </div>
                        </div>
                        </template>
                    </div>
                </div>
                <div class="p-4 bg-bg-light/5 text-center">
                    <button type="button" @click="openSection = null" class="text-sm font-medium text-primary hover:text-bg-secondary flex items-center justify-center w-full py-2">
                        <span>Collapse all sections</span>
                    </button>
                </div>
            </div>
            @endif
            @if(!empty($student_view_course))
            <div class="bg-bg-light/20 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-border/30">
                <h2 class="text-2xl font-bold mb-4 text-text-primary">Students are viewing</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($student_view_course as $key => $course_row)
                        @if($key < 2)
                        <div class="bg-bg-light/5 rounded-xl p-4 flex gap-4 items-center">
                            <img src="{{ $course_row->course_image }}" alt="course" class="w-24 h-24 object-cover rounded-xl">
                            <div class="flex-1">
                                <div class="font-bold text-lg text-text-primary mb-1"><a href="{{ route('coursedetails', $course_row->slug) }}">{{ $course_row->course_name }}</a></div>
                                @php
                                    $rate =0;
                                    if($course_row->rate != 0){ $rate = $course_row->rate / $course_row->total_record; }
                                    $fullStars = floor($rate);
                                    $halfStar = ($rate - $fullStars) >= 0.25 && ($rate - $fullStars) <= 0.75;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp
                                <div class="course-rating flex items-center gap-1 text-yellow-400 text-sm" aria-label="Course rating">
                                    <span class="font-bold">{{ number_format((float)$rate, 1, '.', '') }}</span>
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
                                <div class="text-text-light text-sm flex items-center gap-2">
                                    <i class="fas fa-user"></i> {{ $course_row->views }} (People are watching)
                                </div>
                                <div class="text-primary font-bold mt-1">
                                    {{ getCurrencySymbol() }}{{ currencyConvert($course_row->course_price) }}
                                    @if(in_array($course_row->course_id,$wishlist))
                                    <span class="icon-bookmark"></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                <div class="text-center mt-6">
                    <a href="javascript:void(0)" class="bg-primary hover:bg-bg-secondary text-text-white px-8 py-3 rounded-full text-lg font-semibold transition-colors">Show More</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Promotional Bar Section --}}
@if(isset($slogan_section['homepage_slogan_section']))
<section class="w-full mt-10 bg-gradient-to-r from-primary via-bg-secondary to-primary py-3">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-center items-center gap-4 md:gap-0">
        <div class="flex items-center flex-1 justify-center md:justify-start px-2">
            @if(isset($slogan_section['homepage_slogan_section']['slogan_first_image']))
                <span class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-lg mr-3">
                    <img src="{{ $slogan_section['homepage_slogan_section']['slogan_first_image'] }}" alt="Knolzi" class="w-6 h-6">
                </span>
            @endif
            <span class="font-semibold text-white text-base text-left">{{ $slogan_section['homepage_slogan_section']['slogan_first'] ?? '' }}</span>
        </div>
        <div class="flex items-center flex-1 justify-center px-2">
            @if(isset($slogan_section['homepage_slogan_section']['slogan_second_image']))
                <span class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-lg mr-3">
                    <img src="{{ $slogan_section['homepage_slogan_section']['slogan_second_image'] }}" alt="Knolzi" class="w-6 h-6">
                </span>
            @endif
            <span class="font-semibold text-white text-base text-left">{{ $slogan_section['homepage_slogan_section']['slogan_second'] ?? '' }}</span>
        </div>
        <div class="flex items-center flex-1 justify-center md:justify-end px-2">
            @if(isset($slogan_section['homepage_slogan_section']['slogan_third_image']))
                <span class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-lg mr-3">
                    <img src="{{ $slogan_section['homepage_slogan_section']['slogan_third_image'] }}" alt="Knolzi" class="w-6 h-6">
                </span>
            @endif
            <span class="font-semibold text-white text-base text-left">{{ $slogan_section['homepage_slogan_section']['slogan_third'] ?? '' }}</span>
        </div>
    </div>
</section>
@endif

{{-- Top Categories Depending on Search History --}}
@if(!empty($related_category))
<section class="max-w-7xl mx-auto px-4 py-10">
    <div class="bg-bg-light/20 backdrop-blur-sm rounded-xl p-6 border border-border/30">
        <h2 class="text-xl font-bold mb-4 text-text-primary">Categories Depending on your Search History</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($related_category as $category)
                <a href="{{ route('categorycourses', $category['slug']) }}" class="bg-primary text-text-white px-4 py-2 rounded-full font-semibold hover:bg-bg-secondary transition-colors">{{ $category['name'] }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Make a Bundle Section --}}
@if(!empty($bundle_course))
<section class="max-w-7xl mx-auto px-4 py-10">
    <div class="bg-bg-light/20 backdrop-blur-sm rounded-xl p-6 border border-border/30">
        <h2 class="text-xl font-bold mb-4 text-text-primary">Make a Bundle</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php $cart_added=0; $bundle_course_id=[]; @endphp
            @foreach($bundle_course as $bundle_row)
                @if(!in_array($bundle_row->course_id,$subscribe_course))
                <div class="bg-bg-light/5 rounded-lg p-4 flex gap-4 items-center">
                    <a href="{{ route('coursedetails', $bundle_row->slug) }}">
                        <img src="{{ $bundle_row->course_image }}" alt="{{ $bundle_row->course_name }}" class="w-24 h-24 object-cover rounded-lg">
                    </a>
                    <div class="flex-1">
                        <div class="font-bold text-lg text-text-primary mb-1"><a href="{{ route('coursedetails', $bundle_row->slug) }}">{{ $bundle_row->course_name }}</a></div>
                        @php
                        $bundle_course_id[] =$bundle_row->course_id;
                        $rate =0;
                        if($bundle_row->rate != 0){
                        $rate = $bundle_row->rate / $bundle_row->total_record;
                        }
                        $fullStars = floor($rate);
                        $halfStar = ($rate - $fullStars) >= 0.25 && ($rate - $fullStars) <= 0.75;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        if(array_key_exists($bundle_row->course_id,$cart)) {
                        $cart_added++;
                        }
                        @endphp
                        <div class="course-rating flex items-center gap-1 text-yellow-400 text-sm" aria-label="Course rating">
                            <span class="font-bold">{{ number_format((float)$rate, 1, '.', '') }}</span>
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
                        <div class="text-text-light text-sm flex items-center gap-2">
                            <i class="fas fa-user"></i> {{ $bundle_row->views }} (People are watching)
                        </div>
                        <div class="text-primary font-bold mt-1">
                            {{ getCurrencySymbol() }}{{ currencyConvert($bundle_row->course_price) }}
                            @if(in_array($bundle_row->course_id,$wishlist))
                            <span class="icon-bookmark"></span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        <div class="text-center mt-6">
            @if($cart_added == count($bundle_course))
                <a href="{{ url('/cart') }}" class="bg-primary hover:bg-bg-secondary text-text-white px-8 py-3 rounded-full text-lg font-semibold transition-colors">Go to cart</a>
            @else
                <a href="javascript:void(0)" id="{{ implode(",", $bundle_course_id) }}" class="bg-primary hover:bg-bg-secondary text-text-white px-8 py-3 rounded-full text-lg font-semibold transition-colors add-all-to-cart">Add all to cart</a>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Student Reviews Section --}}
@if(isset($course_review) && !$course_review->isEmpty())
<section class="max-w-7xl mx-auto px-4 py-10">
    <div class="bg-bg-light/20 backdrop-blur-sm rounded-xl p-6 border border-border/30">
        <h2 class="text-xl font-bold mb-4 text-text-primary">Student's Reviews</h2>
        <div class="space-y-4">
            @foreach($course_review as $review)
            <div class="flex items-start gap-4 bg-bg-light/5 rounded-lg p-4">
                <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $review->user->name }}" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <div class="font-semibold text-text-primary">{{ $review->user->name }}</div>
                    <p class="text-text-secondary mt-1">{{ $review['review'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6" data-page="2">
            <button class="bg-primary hover:bg-bg-secondary text-text-white px-8 py-3 rounded-full text-lg font-semibold transition-colors">Show more</button>
        </div>
    </div>
</section>
@endif

{{-- Instructor Section --}}
@if(!empty($authors))
<section class="max-w-7xl mx-auto px-4 py-10">
    <div class="bg-bg-light/20 backdrop-blur-sm rounded-xl p-6 border border-border/30">
        <h2 class="text-xl font-bold mb-4 text-text-primary">Instructors</h2>
        <div class="space-y-8">
            @foreach($authors as $key => $author)
            <div class="flex flex-col md:flex-row gap-6 bg-bg-light/5 rounded-lg p-6" id="author-{{ $key }}">
                <div class="flex-shrink-0">
                    <a href="/author/{{ $author['author_slug'] }}">
                        @if($author['profile_image'] == "")
                        <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $author['name'] }}" class="w-24 h-24 rounded-full object-cover">
                        @else
                        <img src="{{ $author['profile_image'] }}" alt="{{ $author['name'] }}" class="w-24 h-24 rounded-full object-cover">
                        @endif
                    </a>
                </div>
                <div class="flex-1">
                    <div class="font-bold text-lg text-text-primary mb-1"><a href="/author/{{ $author['author_slug'] }}">{{ $author['name'] }}</a></div>
                    @php
                    $rate =0;
                    if($author['rate'] != 0){
                    $rate = $author['rate'] / $author['total_record'];
                    }
                    $fullStars = floor($rate);
                    $halfStar = ($rate - $fullStars) >= 0.25 && ($rate - $fullStars) <= 0.75;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp
                    <div class="course-rating flex items-center gap-1 text-yellow-400 text-sm" aria-label="Instructor rating">
                        <span class="font-bold">{{ number_format((float)$rate, 1, '.', '') }}</span>
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
                    <div class="text-text-light text-sm flex items-center gap-2 mt-1">
                        <i class="fas fa-user"></i> {{ $author['views'] }} (People are watching)
                    </div>
                    <div class="mt-2 text-text-secondary">{!! $author['about_me'] !!}</div>
                    <a href="/author/{{ $author['author_slug'] }}" class="text-primary font-semibold mt-2 inline-block">READ MORE <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Fix for Add to Cart -> Go to Cart button
    $(document).on('click', '.add-to-cart', function() {
        var btn = $(this);
        btn.prop('disabled', true).text('Loading...');
        var course_id = btn.attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/shopping-carts/me/cart',
            data: { 'course_id': course_id },
            type: 'POST',
            success: function(response) {
                // Replace button with Go to Cart link
                var goToCart = $('<a>', {
                    href: '/cart',
                    class: btn.attr('class').replace('add-to-cart', ''),
                    text: 'Go to Cart',
                    style: btn.attr('style') || ''
                });
                btn.replaceWith(goToCart);
                // Optionally update cart count if you have a cart_count element
                if (response.cart_count !== undefined) {
                    $('.cart_count').html(response.cart_count + '<span class="visually-hidden">unread </span>').show();
                }
            },
            error: function(data) {
                btn.prop('disabled', false).text('Add to Cart');
                var response = data.responseJSON && data.responseJSON.errors ? data.responseJSON.errors : null;
                var html = '';
                if (response) {
                    $.each(response, function(i, val) {
                        html += '<p>' + val[0] + '</p>';
                    });
                }
                if (html === '') {
                    html = 'Something went wrong!';
                }
                swal({
                    title: 'Error!',
                    text: html,
                    html: true,
                    type: 'error'
                }, function() {
                    location.reload();
                });
            }
        });
    });
});
</script>
@endpush
