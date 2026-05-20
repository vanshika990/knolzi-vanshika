@extends('frontend.layouts.app')

@if (!empty($seometa))
    @section('meta_title', $seometa['title'])
    @section('meta_description', $seometa['description'])
    @section('meta_keywords', $seometa['keyword'])
    @section('meta_image', asset('assets/front/images/logo.png'))
@endif

@section('content')
<!-- Email Verification Section -->
<section class="relative z-10 max-w-3xl mx-auto px-6 py-16">
    <div class="glass-effect rounded-3xl p-8 animate-scale-in">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                {{ __('Verify Your Email Address') }}
            </h1>
        </div>
        <div class="mb-6">
            @if (session('resent'))
                <div class="alert alert-success bg-green-600/20 text-green-400 rounded-lg px-4 py-3 mb-4 text-center" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <p class="text-gray-500 text-center mb-2">
                {{ __('Before proceeding, please check your email for a verification link.') }}
            </p>
            <p class="text-gray-500 text-center mb-4">
                {{ __('If you did not receive the email') }},
                <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="text-blue-400 hover:underline font-medium focus:outline-none">
                        {{ __('click here to request another') }}
                    </button>.
                </form>
            </p>
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-blue-400 hover:underline font-medium">
                {{ __('Back to Login') }}
            </a>
        </div>
    </div>
</section>
@endsection
