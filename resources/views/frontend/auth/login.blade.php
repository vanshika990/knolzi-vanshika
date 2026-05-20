@extends('frontend.layouts.app')

@if (!empty($seometa))
    @section('meta_title', $seometa['title'])
    @section('meta_description', $seometa['description'])
    @section('meta_keywords', $seometa['keyword'])
    @section('meta_image', asset('assets/front/images/logo.png'))
@endif

@section('content')
<!-- Login Form Section -->
<section class="relative z-10 max-w-3xl mx-auto px-6 py-16">
    <div class="bg-bg-light/20 backdrop-blur-sm rounded-3xl p-8 animate-scale-in border border-border/30 shadow-xl">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                Login In to Your <span class="gradient-text">Knolzi Account</span>
            </h1>
        </div>
        <!-- Social Login Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 mb-6">
            <a href="{{ url('auth/google') }}" class="flex-1 bg-bg-light border border-border hover:bg-bg-secondary hover:border-primary transition-all duration-300 rounded-xl py-3 px-6 flex items-center justify-center space-x-3 text-text-primary hover:text-primary shadow-sm hover:shadow-md transform hover:-translate-y-1">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="font-medium">Continue with Google</span>
            </a>

            <a href="{{ url('auth/facebook') }}" class="flex-1 bg-bg-light border border-border hover:bg-bg-secondary hover:border-primary transition-all duration-300 rounded-xl py-3 px-6 flex items-center justify-center space-x-3 text-text-primary hover:text-primary shadow-sm hover:shadow-md transform hover:-translate-y-1">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/>
                </svg>
                <span class="font-medium">Continue with Facebook</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="flex items-center my-6">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-border to-transparent"></div>
            <span class="px-4 text-text-light text-sm">or</span>
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-border to-transparent"></div>
        </div>

        <!-- Login Form -->
        <form class="space-y-4" autocomplete="off" method="POST" action="{{ route('login') }}" name="login-form">
            @csrf
            <!-- Email -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2 text-text-primary">Email Address</label>
                <input type="email"
                    class="w-full bg-bg-light border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                    placeholder="Enter Email Address" name="email" value="{{ old('email') }}" required>
            </div>

            <!-- Password -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2 text-text-primary">Password</label>
                <input type="password"
                    class="w-full bg-bg-light border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-300"
                    placeholder="Enter Your Password" name="password" required>
            </div>

            <!-- Forgot Password -->
            <div class="text-right">
                <a href="{{ route('password.request') }}" class="text-blue-400 hover:underline font-medium">
                    Forgot Password?
                </a>
            </div>

            <!-- Submit Button -->
            <div class="w-full md:w-1/2 mx-auto mt-4">
                <button type="submit"
                    class="w-full btn-primary py-3 rounded-xl font-semibold text-white flex items-center justify-center space-x-2">
                    <span>Login</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </form>

        <!-- Signup Link -->
        <div class="text-center mt-6">
            <p class="text-gray-500">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-blue-400 hover:underline font-medium">Sign Up</a>
            </p>
        </div>
    </div>
</section>

@endsection
