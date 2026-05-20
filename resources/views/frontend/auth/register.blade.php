@extends('frontend.layouts.app')

@if (!empty($seometa))
    @section('meta_title', $seometa['title'])
    @section('meta_description', $seometa['description'])
    @section('meta_keywords', $seometa['keyword'])
    @section('meta_image', asset('assets/front/images/logo.png'))
@endif

@section('content')
    <!-- Registration Form Section -->
    <section class="relative z-10 max-w-5xl mx-auto px-6 py-16">
        <div class="glass-effect rounded-3xl p-10 animate-scale-in">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">
                    Create Your <span class="gradient-text">Account</span>
                </h1>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Join thousands of professionals transforming their knowledge management
                </p>
            </div>

            <!-- Form Grid -->
            <form class="" autocomplete="off" method="POST"
                action="{{ route('register') }}" name="register-individual" id="register-individual">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" for="type">Select Type <span style="color: #f1416c;">*</span></label>
                        <select name="type" id="type" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 bg-white outline-none">
                            <option value="individual" {{ old('type') == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="organization" {{ old('type') == 'organization' ? 'selected' : '' }}>Organization</option>
                        </select>
                        @error('type')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" for="name">Your Name <span style="color: #f1416c;">*</span></label>
                        <input type="text"
                            class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" placeholder="Enter Your Name" required autocomplete="name" autofocus>
                        @error('name')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" for="email">Email Address <span style="color: #f1416c;">*</span></label>
                        <input type="email"
                            class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('email') is-invalid @enderror"
                            placeholder="Enter Email Address" name="email" id="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" for="mobile_number">Mobile Number <span style="color: #f1416c;">*</span></label>
                        <input type="number"
                            class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('mobile_number') is-invalid @enderror"
                            placeholder="Mobile Number" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" required>
                        @error('mobile_number')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" for="password">Password <span style="color: #f1416c;">*</span></label>
                        <input type="password"
                            class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('password') is-invalid @enderror"
                            placeholder="Your Password" name="password" id="indvpassword" required autocomplete="new-password">
                        @error('password')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" for="password_confirmation">Confirm Password <span style="color: #f1416c;">*</span></label>
                        <input type="password"
                            class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('password_confirmation') is-invalid @enderror"
                            placeholder="Confirm Password" name="password_confirmation" id="password-confirm" required>
                        @error('password_confirmation')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" for="birth_date">Date of Birth <span style="color: #f1416c;">*</span></label>
                        <input type="date"
                            class="w-full input-field rounded-xl py-3 px-4 text-gray-700 bg-white outline-none @error('birth_date') is-invalid @enderror"
                            name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required>
                        @error('birth_date')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div id="cmp_code">
                        <label class="block text-sm font-medium mb-2" for="company_code">Company Code</label>
                        <input type="text"
                            class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none @error('company_code') is-invalid @enderror"
                            placeholder="Company Code" name="company_code" id="company_code" value="{{ old('company_code') }}">
                        @error('company_code')
                        <span class="error text-red-500 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <!-- Terms & Submit Button (Full Width) -->
                <div class="w-full md:col-span-2 space-y-4 mt-6">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox"
                            class="mt-1 w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                        <label class="text-sm text-gray-500">
                            I agree to the <a href="{{ route('terms') }}" class="text-blue-400 hover:underline">Terms of Use</a> and <a
                                href="{{ route('privacy') }}" class="text-blue-400 hover:underline">Privacy Policy</a>
                        </label>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="w-full md:w-1/2 mx-auto mt-4">
                    <button type="submit"
                        class="w-full btn-primary py-3 rounded-xl font-semibold text-white flex items-center justify-center space-x-2">
                        <span>Sign Up</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-gray-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-400 hover:underline font-medium">Log In</a>
                </p>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#type').bind('change', function(e) {
            if ($('#type').val() == 'individual') {
                $("#cmp_code").show();
            } else if ($('#type').val() == 'organization') {
                $("#cmp_code").hide();
            } else {
                $("#cmp_code").show();
            }
        }).trigger('change');

        $("#register-individual").validate({
            rules: {
                type: "required",
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                mobile_number: "required",
                password: "required",
                password_confirmation: {
                    required: true,
                    equalTo: "#indvpassword"
                },
                birth_date: "required"
            },
            messages: {
                type: "Please select a type.",
                name: "Please enter your name.",
                email: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                mobile_number: "Please enter your mobile number.",
                password: "Please enter your password.",
                password_confirmation: {
                    required: "Please confirm your password.",
                    equalTo: "Passwords do not match."
                },
                birth_date: "Please enter your date of birth."
            },
            errorElement: 'span',
            errorClass: 'error text-red-500 text-xs',
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection
