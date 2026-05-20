@extends('frontend.layouts.app')
@section('meta_title', '419 Page Expired - Knolzi')
@section('meta_description', '419 Page Expired - Knolzi')
@section('meta_image', asset('assets/front/images/logo.png'))
@section('content')
<section class="relative z-10 max-w-2xl mx-auto px-6 py-16">
    <div class="text-center">
        <div class="glass-effect rounded-3xl p-12 mb-8 animate-fade-in">
            <img src="{{ asset('assets/front/images/404.png') }}" class="mx-auto mb-6 rounded-2xl shadow-lg max-h-72 object-cover" alt="419 Page Expired" />
            <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight text-orange-500">419</h1>
            <h2 class="text-2xl md:text-3xl font-semibold text-white mb-8">Page Expired</h2>
            <a href="{{ url('/') }}" class="btn-primary inline-block text-white font-bold rounded-full px-8 py-4 text-lg shadow hover:scale-105 transition">GO TO HOME</a>
        </div>
    </div>
</section>
@endsection
