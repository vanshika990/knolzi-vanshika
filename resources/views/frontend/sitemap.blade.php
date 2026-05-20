@extends('frontend.layouts.app')
@section('meta_title', 'Sitemap')
@section('meta_description', 'We welcome feedback from the teaching community.')
@section('meta_image',asset('assets/front/images/logo.png'))

@section('content')
<!-- Sitemap Hero Section -->
<section class="relative z-10 max-w-4xl mx-auto px-4 md:px-8 py-16">
    <div class="text-center mb-10">
        <div class="glass-effect rounded-3xl p-8 md:p-12 mb-6 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight gradient-text">Sitemap</h1>
            <span class="text-lg text-gray-400">Explore all pages, categories, and courses</span>
        </div>
    </div>
    <div class="glass-effect-subtle rounded-3xl p-6 md:p-10 shadow-xl animate-fade-in">
        <div class="static-content prose prose-invert max-w-none text-gray-500">
            <div class="mb-10">
                <h2 class="text-2xl font-semibold mb-4">Pages</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <ul class="space-y-2">
                        <li><a href="javascript:void(0)" class="hover:underline">Get the app</a></li>
                        <li><a href="javascript:void(0)" class="hover:underline">About us</a></li>
                        <li><a href="{{ route('contactus') }}" class="hover:underline">Contact us</a></li>
                    </ul>
                    <ul class="space-y-2">
                        <li><a href="https://blog.edupme.com/" class="hover:underline">Blog</a></li>
                        <li><a href="{{ route('contactus') }}" class="hover:underline">Help & Support</a></li>
                        <li><a href="{{ route('digital-class') }}" class="hover:underline">Digital Classroom</a></li>
                        <li><a href="{{ route('start-teaching') }}" class="hover:underline">Start Teaching</a></li>
                    </ul>
                    <ul class="space-y-2">
                        <li><a href="{{ route('terms') }}" class="hover:underline">Terms</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:underline">Privacy Policy</a></li>
                        <li><a href="{{ route('disclaimer') }}" class="hover:underline">Disclaimer</a></li>
                    </ul>
                </div>
            </div>

            @if(!$categorys->isEmpty())
            <div class="mb-10">
                <h2 class="text-2xl font-semibold mb-4">Categories</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($categorys as $category)
                    <ul class="space-y-2">
                        <li><a href="{{ route('categorycourses',$category->slug) }}" class="hover:underline">{{$category['name']}}</a></li>
                    </ul>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!$courses->isEmpty())
            <div class="mb-10">
                <h2 class="text-2xl font-semibold mb-4">Courses</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                    <ul class="space-y-2">
                        <li><a href="{{ route('coursedetails',$course->slug) }}" class="hover:underline">{{$course['course_name']}}</a></li>
                    </ul>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
