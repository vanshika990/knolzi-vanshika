@extends('frontend.layouts.app')

@section('content')
    <section class="relative z-10 max-w-xl mx-auto px-6 py-16">
        <div class="glass-effect rounded-3xl p-10 animate-scale-in">
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">
                    Reset <span class="gradient-text">Password</span>
                </h1>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Enter your email and new password to reset your account password.
                </p>
            </div>
            @if (session('status'))
                <div class="alert alert-success mb-4 text-green-500 text-center" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.update') }}" autocomplete="off" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email Address <span style="color: #f1416c;">*</span></label>
                    <input id="email" type="email" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password <span style="color: #f1416c;">*</span></label>
                    <input id="password" type="password" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <label for="password-confirm" class="block text-sm font-medium mb-2">Confirm Password <span style="color: #f1416c;">*</span></label>
                    <input id="password-confirm" type="password" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none" name="password_confirmation" required autocomplete="new-password">
                </div>
                <div>
                    <button type="submit" class="w-full btn-primary py-3 rounded-xl font-semibold text-white flex items-center justify-center space-x-2">
                        <span>Reset Password</span>
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
