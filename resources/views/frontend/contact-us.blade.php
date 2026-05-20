@extends('frontend.layouts.app')
@section('meta_title', 'Contact Us')
@section('meta_description', 'We welcome feedback from the teaching community.')

@section('content')
<!-- Hero Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center">
        <div class="glass-effect rounded-3xl p-12 mb-8 animate-fade-in inline-block">
            <img src="{{ $data['contactuspage_hero_section']['hero_sec_image'] ?? '' }}" alt="Contact Us" class="mx-auto mb-6 rounded-2xl max-h-64 object-cover shadow-lg">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                {{ $data['contactuspage_hero_section']['hero_sec_title'] ?? 'Contact Us' }}
            </h1>
            <p class="text-xl md:text-2xl text-secondary mb-4">
                {{ isset($data['contactuspage_hero_section']['hero_sec_description']) ? strip_tags($data['contactuspage_hero_section']['hero_sec_description']) : '' }}
            </p>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="relative z-10 max-w-5xl mx-auto px-6 py-16">
    <div class="knolzi-form rounded-3xl p-10 animate-scale-in">
        <!-- Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-2 text-primary">Get in Touch</h2>
            <p class="text-secondary max-w-2xl mx-auto">
                We'd love to hear from you! Please fill out the form below and our team will get back to you as soon as possible.
            </p>
        </div>
        <form id="contactusform" method="POST" action="{{ route('contactusform') }}" autocomplete="off">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="subject" class="block text-sm font-medium mb-2 text-text-primary">Subject Name</label>
                    <input type="text" id="subject" name="subject" class="w-full knolzi-form rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none @error('subject') border-error @enderror" placeholder="Enter Your Subject" value="{{ old('subject') }}">
                    <span class="text-error text-xs mt-1 error-subject"></span>
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium mb-2 text-text-primary">Contact Name</label>
                    <input type="text" id="name" name="name" class="w-full knolzi-form rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none @error('name') border-error @enderror" placeholder="Enter Contact Name" value="{{ old('name') }}">
                    <span class="text-error text-xs mt-1 error-name"></span>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium mb-2 text-text-primary">Email Address</label>
                    <input type="email" id="email" name="email" class="w-full knolzi-form rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none @error('email') border-error @enderror" placeholder="Enter Email Address" value="{{ old('email') }}">
                    <span class="text-error text-xs mt-1 error-email"></span>
                </div>
                <div>
                    <label for="mobile" class="block text-sm font-medium mb-2 text-text-primary">Mobile Number</label>
                    <input type="text" id="mobile" name="mobile" class="w-full knolzi-form rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none @error('mobile') border-error @enderror" placeholder="Mobile Number" value="{{ old('mobile') }}">
                    <span class="text-error text-xs mt-1 error-mobile"></span>
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-sm font-medium mb-2 text-text-primary">How did you hear about us?</label>
                <div class="flex flex-wrap gap-4" id="requesthear_about_us">
                    @php
                        $hearOptions = [
                            'Social Media', 'LinkedIn', 'Search Engine (Google/Yahoo/Bing)',
                            'Word of Mouth', 'Recommended by Friend/Colleague', 'Blogs'
                        ];
                    @endphp
                    @foreach($hearOptions as $option)
                        <label class="inline-flex items-center">
                            <input type="radio" name="hear_about_us" value="{{ $option }}" class="form-radio text-primary focus:ring-primary">
                            <span class="ml-2 text-secondary">{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
                <span class="text-error text-xs mt-1 error-hear_about_us"></span>
            </div>
            <div class="mt-6">
                <label for="message" class="block text-sm font-medium mb-2 text-text-primary">Message</label>
                <textarea id="message" name="message" rows="4" class="w-full knolzi-form rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none @error('message') border-error @enderror" placeholder="Enter your message here..."></textarea>
                <span class="text-error text-xs mt-1 error-message"></span>
            </div>
            <div class="w-full md:w-1/2 mx-auto mt-8">
                <button type="submit" class="w-full submit-btn py-3 rounded-xl font-semibold text-text-white flex items-center justify-center space-x-2">
                    <span>Send Message</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Google Map Section -->
<section class="relative z-10 max-w-5xl mx-auto px-6 py-12">
    <div class="glass-effect-subtle rounded-3xl overflow-hidden shadow-lg animate-fade-in">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.001477026633!2d72.47003971496788!3d23.02371798495252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b50f3876ce5%3A0xde9e0f34a0afd7de!2sedupme!5e0!3m2!1sen!2sin!4v1630915182726!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

<!-- Success Modal -->
<div id="contactSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
    <div class="bg-bg-primary text-text-primary rounded-2xl shadow-xl p-8 max-w-md w-full text-center">
        <div class="mb-4">
            <svg class="w-16 h-16 mx-auto text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-2xl font-bold mb-2">Thank you for getting in touch!</h3>
        <p class="mb-4" id="contactSuccessMessage"></p>
        <button onclick="document.getElementById('contactSuccessModal').classList.add('hidden')" class="btn-primary px-6 py-2 rounded-full text-lg font-semibold">Close</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#contactusform').on('submit', function(e) {
        e.preventDefault();
        $('.text-red-500').text('');
        var form = $(this);
        var formData = form.serialize();
        $(form).find('button[type=submit]').prop('disabled', true).text('Submitting...');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $(form).find('button[type=submit]').prop('disabled', false).text('Send Message');
                if(response.success) {
                    $('#contactSuccessMessage').html(response.message);
                    $('#contactSuccessModal').removeClass('hidden');
                    form[0].reset();
                }
            },
            error: function(xhr) {
                $(form).find('button[type=submit]').prop('disabled', false).text('Send Message');
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (const key in errors) {
                        $('.error-' + key).text(errors[key][0]);
                    }
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });
});
</script>
@endpush
