@extends('frontend.layouts.app')

@section('content')
    <section class="relative z-10 max-w-xl mx-auto px-6 py-16">
        <div class="glass-effect rounded-3xl p-10 animate-scale-in">
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">
                    Forgot <span class="gradient-text">Password</span>
                </h1>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Enter your email address to receive a password reset link.
                </p>
            </div>
            @if (session('status'))
                <div class="alert alert-success mb-4 text-green-500 text-center" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" autocomplete="off" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email Address <span style="color: #f1416c;">*</span></label>
                    <input type="email" name="email" id="email" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter Email Address" required autofocus>
                    @error('email')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full btn-primary py-3 rounded-xl font-semibold text-white flex items-center justify-center space-x-2">
                        <span>Send Password Reset Link</span>
                    </button>
                </div>
            </form>
            <div class="text-center mt-6">
                <p class="text-gray-500">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-blue-400 hover:underline font-medium">Log In</a>
                </p>
            </div>
        </div>
    </section>
@endsection
