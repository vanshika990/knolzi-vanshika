@extends('frontend.layouts.app')

@section('content')
    <section class="relative z-10 max-w-xl mx-auto px-6 py-16">
        <div class="glass-effect rounded-3xl p-10 animate-scale-in">
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">
                    Confirm <span class="gradient-text">Password</span>
                </h1>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Please confirm your password before continuing.
                </p>
            </div>
            <form method="POST" action="{{ route('password.confirm') }}" autocomplete="off" class="space-y-6">
                @csrf
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password <span style="color: #f1416c;">*</span></label>
                    <input id="password" type="password" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full btn-primary py-3 rounded-xl font-semibold text-white flex items-center justify-center space-x-2">
                        <span>Confirm Password</span>
                    </button>
                </div>
                <div class="text-center mt-6">
                    @if (Route::has('password.request'))
                        <a class="text-blue-400 hover:underline font-medium" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>
@endsection
