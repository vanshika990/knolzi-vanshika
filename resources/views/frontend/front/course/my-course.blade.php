@extends('frontend.layouts.app')
@section('meta_title', 'My Courses')
@section('meta_description', 'My Courses - Knolzi')
@section('meta_image', asset('assets/front/images/logo.png'))

@section('content')
<!-- Hero Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-16">
    <div class="text-center">
        <div class="glass-effect rounded-3xl p-8 md:p-12 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight gradient-text">My Courses</h1>
            <p class="text-lg text-gray-500 mb-2">All your enrolled courses in one place.</p>
        </div>
    </div>
</section>

<section class="relative z-10 max-w-7xl mx-auto px-6 pb-12">
    @if(empty($subscribe_courses))
    <div class="flex flex-col items-center justify-center min-h-[200px] w-full">
        <div class="glass-effect w-full mx-auto rounded-2xl p-8 mb-10 animate-fade-in text-center flex flex-col items-center">
            <p class="text-2xl text-gray-400 font-semibold mb-2">You don't have any course</p>
            <a href="/" class="btn-primary px-6 py-3 rounded-full font-semibold mt-4">Browse Courses</a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($subscribe_courses as $row)
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale shadow-lg">
            <a href="{{ route('coursedetails', $row->slug) }}">
                <div class="w-full h-[120px] bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                    <img alt="course" src="{{ $row->course_image }}" class="object-cover h-full w-full lazyload">
                </div>
                <h4 class="text-lg font-bold mb-2">{{ $row->course_name }}</h4>
                <p class="text-gray-500 text-sm mb-2">By: {{ $row->author_name }}</p>
                <div class="flex items-center justify-between mb-2">
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
                <div class="flex items-center justify-between">
                    @hasanyrole('organization')
                    @else
                        @if($row['state'] == "todo")
                        <a class="btn-primary px-4 py-2 rounded-full text-sm font-semibold" href="{{ route('courselearn', encrypt($row->course_id)) }}">Start</a>
                        @else
                        <a class="btn-primary px-4 py-2 rounded-full text-sm font-semibold" href="{{ route('courselearn', encrypt($row->course_id)) }}">Resume</a>
                        @endif
                    @endhasanyrole
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</section>
@stop
