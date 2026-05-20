@extends('frontend.layouts.app')
@section('meta_title', 'My Personal Profile')
@section('meta_description', 'My Personal Profile - Knolzi')
@section('meta_image',asset('assets/front/images/logo.png'))

@section('content')
<section class="relative z-10 max-w-5xl mx-auto px-6 py-16">
    <div class="bg-bg-primary border border-border rounded-3xl p-10 shadow-lg">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 text-text-primary">
                My <span class="bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">Profile</span>
            </h1>
            <p class="text-text-secondary max-w-2xl mx-auto">
                View and update your personal information
            </p>
        </div>
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar/Profile Image -->
            <div class="md:w-1/3 flex flex-col items-center">
                <div class="relative mb-6">
                    @if(!empty($user->profile_image))
                        <img src="{{$user->profile_image}}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-primary">
                    @else
                        <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-primary">
                    @endif
                    <form id="profile_image_form" name="profile_image_form" class="absolute bottom-0 right-0 flex items-center justify-center">
                        <input type="file" name="picture" id="fileInput" class="hidden" />
                        <label for="fileInput" class="cursor-pointer bg-primary hover:bg-primary-dark text-text-white rounded-full p-2 shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-primary-light" tabindex="0" aria-label="Upload profile image">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 10-4-4l-8 8v3zm0 0v3a1 1 0 001 1h3" />
                            </svg>
                        </label>
                    </form>
                </div>
                <div class="text-lg font-semibold text-text-primary mb-2">{{$user->name}}</div>
                <div class="w-full mt-4">
                    <ul class="space-y-2">
                        <li><a href="{{route('personal-profile')}}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('personal-profile') ? 'bg-primary text-text-white' : 'bg-bg-light text-text-secondary hover:bg-primary hover:text-text-white' }}">My Profile</a></li>
                        <li><a href="{{route('education-qualification')}}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('education-qualification') ? 'bg-primary text-text-white' : 'bg-bg-light text-text-secondary hover:bg-primary hover:text-text-white' }}">Education & Qualifications</a></li>
                        <li><a href="{{route('work-experience')}}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('work-experience') ? 'bg-primary text-text-white' : 'bg-bg-light text-text-secondary hover:bg-primary hover:text-text-white' }}">Work Experience</a></li>
                    </ul>
                </div>
            </div>
            <!-- Profile Details/Form -->
            <div class="md:w-2/3">
                <div class="bg-bg-secondary border border-border rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-text-primary">Profile Details</h2>
                        <a href="{{route('edit-personal-profile')}}" class="btn-primary px-6 py-2 rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300">Edit Profile</a>
                    </div>
                    <form class="space-y-6" autocomplete="off" method="POST" action="{{ route('update-personal-profile') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-text-secondary">Full Name</label>
                                <input type="text" name="name" value="{{$user->name}}" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-text-secondary">Email Address</label>
                                <input type="email" name="email" value="{{$user->email}}" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-text-secondary">Age</label>
                                <input type="text" name="age_group" value="{{ $user->age_group == '51 and above' ? '51 & above' : $user->age_group }}" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light" readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("#fileInput").on('change', function() {
            var data = new FormData($('#profile_image_form')[0]);
            var _url = '{{ route("update-profile-image") }}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: _url,
                data: data,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response) {
                        location.reload();
                    }
                },
            }).fail(function(xhr, textStatus, errorThrown) {
                // Error handling (can be improved as needed)
                alert('Error uploading image.');
                $('#fileInput').val('');
            });
            return false;
        });
    });
</script>
@endpush
