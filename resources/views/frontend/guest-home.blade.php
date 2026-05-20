@extends('frontend.layouts.app')

@section('content')
<!-- Top Hero Section with Hindi Text -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-16">
    <div class="text-center">
        <div class="glass-effect rounded-3xl p-12 mb-8 animate-fade-in">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                <span class="gradient-text-warm">Millimeter Nahi</span>
                <br>
		<h2 style="color:red; text-align:center;">
    Hello Vanshika Jain 🚀
</h2>
<h1>lio the tiger</h1>
                <span class="gradient-text-cool">Centimeter Bano.</span>
            </h1>

            <p class="text-2xl md:text-3xl text-secondary mb-4 font-semibold">
                Growth Dekho, Sirf Personal hi Nahi Professional Bhi.
            </p>

            <p class="text-xl md:text-2xl text-secondary">
                Ubhro <span class="gradient-text font-bold">knolzi</span> ke saath
            </p>
	<h2 style="color:red; text-align:center;">
    Hello Vanshika Jain 🚀
</h2>
        </div>
    </div>
</section>

<!-- Main Hero Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <div class="inline-flex items-center glass-effect-subtle rounded-full px-4 py-2 mb-8">
            <svg class="w-4 h-4 text-primary mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <span class="text-sm text-secondary">Transform your knowledge management</span>
        </div>

        <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
            Turn Your Knowledge Into
            <br>
            <span class="text-primary">Powerful Insights</span>
        </h1>

        <p class="text-xl text-secondary mb-8 max-w-3xl mx-auto leading-relaxed">
            Knolzi is an AI-powered knowledge management platform that helps individuals and teams
            capture, organize, and discover insights from their information ecosystem.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('register') }}" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold flex items-center group">
                Start Free Trial
                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
            {{-- <button class="btn-secondary px-8 py-4 rounded-full text-lg font-semibold">
                Watch Demo
            </button> --}}
        </div>
    </div>

    <!-- Dashboard Preview -->
    {{-- <div class="relative max-w-5xl mx-auto animate-scale-in">
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <div class="bg-slate-800/50 rounded-xl p-6 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="bg-slate-700 rounded-lg px-4 py-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="text-gray-400">Search across all your knowledge...</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-lg p-4 border border-white/10">
                        <h3 class="font-semibold mb-2">Research Notes</h3>
                        <p class="text-sm text-gray-500">42 documents indexed</p>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-lg p-4 border border-white/10">
                        <h3 class="font-semibold mb-2">Meeting Transcripts</h3>
                        <p class="text-sm text-gray-500">28 conversations</p>
                    </div>
                    <div class="bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-lg p-4 border border-white/10">
                        <h3 class="font-semibold mb-2">Web Articles</h3>
                        <p class="text-sm text-gray-500">156 saved articles</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</section>

<!-- Courses Section -->
<section id="courses" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold mb-4">
            A Broad Selection of
            <span class="text-primary"> Courses</span>
        </h2>
        <p class="text-xl text-secondary max-w-2xl mx-auto mb-8">
            {{ $page_all_data['hero_broad_selection_description'] }}
        </p>

        <!-- Course Categories -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            @if(!empty($page_all_data['categories']))
                @foreach($page_all_data['categories'] as $cat_name => $category)
                <button class="tab-btn {{ $loop->first ? 'btn-primary' : 'btn-secondary' }} px-6 py-3 rounded-full font-semibold cursor-pointer"
                    data-category="{{ $category['category']['slug'] }}-tab">
                    {{ $cat_name }}
                </button>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Course Cards -->
    @if(!empty($page_all_data['categories']))
    <div class="glass-effect-subtle rounded-3xl p-8">
        <!-- Course Card-->
        @foreach($page_all_data['categories'] as $cat_name => $category)
        <div class="tab-content" data-tab="{{ $category['category']['slug'] }}-tab">
            <div class="mb-8">
                <h3 class="text-3xl font-bold mb-4">{{ $category['category']['category_sub_description'] }}</h3>
                <div class="text-secondary mb-6">
                    {!! $category['category']['category_description'] !!}
                </div>
                <a href="{{ route('categorycourses', $category['category']['slug']) }}" class="tab-btn btn-primary px-6 py-3 rounded-full font-semibold cursor-pointer">
                    Explore Related Courses
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($category['courses'] as $row)
                <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale">
                    <a href="{{ route('coursedetails', $row['course_slug']) }}">
                        <div class="w-full h-[120px] bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-xl mb-4 flex items-center justify-center">
                            {{-- <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg> --}}
                            <img alt="course" src="{{ $row['course_image'] }}" class="img-fluid lazyload">
                        </div>
                        <h4 class="text-lg font-bold mb-2">{{ $row['course_name'] }}</h4>
                        <p class="text-sm text-secondary mb-3">By: {{ $row['author_name'] }}</p>
                        <p class="text-secondary text-sm mb-4">{{ $row['course_sub_description'] }}</p>

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-1">
                                @php
                                    $rating = 0.0;
                                    if($row['rates'] != 0){
                                        $rating = $row['rates'] / $row['total_record'];
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
                                                0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                                                1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                                                1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor

                                        {{-- Half Star --}}
                                        @if ($halfStar)
                                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="none">
                                                <defs>
                                                    <linearGradient id="halfGrad">
                                                        <stop offset="50%" stop-color="currentColor"/>
                                                        <stop offset="50%" stop-color="#9CA3AF"/> {{-- Tailwind gray-400 --}}
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
                                                0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                                                1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                {{-- <div class="flex space-x-1">
                                    <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div> --}}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $row['courseView'] }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold">{{ getCurrencySymbol() . currencyConvert($row['course_price']) }}</span>
                            @isset($row['course_tag'])
                            <span class="theme-blue px-3 py-1 rounded-full text-xs font-semibold text-white">{{ $row['course_tag'] }}</span>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>

<!-- Features Section -->
<section id="features" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold mb-4">
            Intelligent Knowledge
            <span class="text-primary"> Management</span>
        </h2>
        <p class="text-xl text-secondary max-w-2xl mx-auto">
            Capture, connect, and discover insights from your information with AI-powered tools
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="group glass-effect-subtle rounded-2xl p-8 hover:bg-white/10 transition-all duration-300 hover-scale">
            <div class="feature-icon mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-text-primary">Universal Capture</h3>
            <p class="text-secondary leading-relaxed">
                Seamlessly capture knowledge from web pages, documents, emails, and conversations with our browser extension and integrations.
            </p>
        </div>

        <div class="group glass-effect-subtle rounded-2xl p-8 hover:bg-white/10 transition-all duration-300 hover-scale">
            <div class="feature-icon mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-text-primary">AI-Powered Analysis</h3>
            <p class="text-secondary leading-relaxed">
                Advanced AI automatically categorizes, tags, and connects your information to reveal hidden patterns and insights.
            </p>
        </div>

        <div class="group glass-effect-subtle rounded-2xl p-8 hover:bg-white/10 transition-all duration-300 hover-scale">
            <div class="feature-icon mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-text-primary">Instant Search</h3>
            <p class="text-secondary leading-relaxed">
                Find any piece of information instantly with semantic search that understands context and meaning, not just keywords.
            </p>
        </div>

        <div class="group glass-effect-subtle rounded-2xl p-8 hover:bg-white/10 transition-all duration-300 hover-scale">
            <div class="feature-icon mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-text-primary">Team Collaboration</h3>
            <p class="text-secondary leading-relaxed">
                Share knowledge bases with your team while maintaining fine-grained access controls and collaborative editing features.
            </p>
        </div>

        <div class="group glass-effect-subtle rounded-2xl p-8 hover:bg-white/10 transition-all duration-300 hover-scale">
            <div class="feature-icon mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-text-primary">Enterprise Security</h3>
            <p class="text-secondary leading-relaxed">
                Your data is protected with enterprise-grade security, end-to-end encryption, and compliance with industry standards.
            </p>
        </div>

        <div class="group glass-effect-subtle rounded-2xl p-8 hover:bg-white/10 transition-all duration-300 hover-scale">
            <div class="feature-icon mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-text-primary">Knowledge Analytics</h3>
            <p class="text-secondary leading-relaxed">
                Track knowledge growth, usage patterns, and team insights with comprehensive analytics and reporting dashboards.
            </p>
        </div>
    </div>
</section>

<!-- Use Cases Section -->
<section id="use-cases" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold mb-4">
            Perfect For Every
            <span class="text-primary"> Use Case</span>
        </h2>
        <p class="text-xl text-secondary max-w-2xl mx-auto">
            From research to customer support, Knolzi adapts to your workflow
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 glass-effect-subtle rounded-2xl p-8">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">Research & Development</h3>
            <p class="text-gray-600 mb-4">
                Accelerate research by connecting insights across papers, patents, and internal documents. Never lose track of important findings again.
            </p>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Literature review automation
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Cross-reference discovery
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Trend analysis
                </li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 glass-effect-subtle rounded-2xl p-8">
            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">Customer Support</h3>
            <p class="text-gray-600 mb-4">
                Build a comprehensive knowledge base from support tickets, documentation, and customer conversations to resolve issues faster.
            </p>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Instant answer retrieval
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Case history analysis
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    FAQ automation
                </li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-cyan-500/10 to-blue-500/10 glass-effect-subtle rounded-2xl p-8">
            <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">Sales & Marketing</h3>
            <p class="text-gray-600 mb-4">
                Transform customer insights, market research, and competitive intelligence into actionable strategies and personalized outreach.
            </p>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Customer intelligence
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Competitive analysis
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Campaign insights
                </li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-violet-500/10 to-purple-500/10 glass-effect-subtle rounded-2xl p-8">
            <div class="w-12 h-12 bg-gradient-to-r from-violet-500 to-violet-600 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">Personal Knowledge</h3>
            <p class="text-gray-600 mb-4">
                Build your personal second brain by capturing and connecting ideas from books, articles, podcasts, and daily experiences.
            </p>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Learning acceleration
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Idea connections
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Memory enhancement
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="bg-gradient-to-r from-blue-600/20 to-blue-700/20 glass-effect rounded-3xl p-12 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">
            Ready to Unlock Your
            <span class="gradient-text"> Knowledge Potential?</span>
        </h2>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Join thousands of professionals and teams who have transformed their information management with Knolzi's AI-powered platform.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold">
                Start Your Free Trial
            </a>
            <a href="{{ route('digital-class') }}" class="btn-secondary px-8 py-4 rounded-full text-lg font-semibold">
                Schedule Demo
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Set the first tab as active
        $('.tab-btn:first').addClass('btn-primary').removeClass('btn-secondary');
        $('.tab-content').hide();
        $('.tab-content:first').show();

        $('.tab-btn').on('click', function () {
            var target = $(this).data('category');

            // Toggle button classes
            $('.tab-btn').removeClass('btn-primary').addClass('btn-secondary');
            $(this).addClass('btn-primary').removeClass('btn-secondary');

            // Show/Hide content
            $('.tab-content').hide();
            $('.tab-content[data-tab="' + target + '"]').fadeIn(200);
        });
    });
</script>
@endpush
