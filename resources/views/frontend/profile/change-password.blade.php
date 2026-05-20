@extends('frontend.layouts.app')

@section('meta_title', 'Change Password')
@section('meta_description', 'Change Password - Knolzi')
@section('meta_image',asset('assets/front/images/logo.png'))

@section('content')
<section class="relative z-10 max-w-5xl mx-auto px-6 py-16">
    <div class="bg-bg-primary rounded-3xl p-10 shadow-xl border border-border">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 text-text-primary">
                Change <span class="bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">Password</span>
            </h1>
            <p class="text-text-secondary max-w-2xl mx-auto">
                Update your account password securely
            </p>
        </div>
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar/Profile Image -->
            <div class="md:w-1/3 flex flex-col items-center">
                <div class="relative mb-6">
                    @if(!empty($user->profile_image))
                        <img src="{{$user->profile_image}}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-primary shadow-lg">
                    @else
                        <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-primary shadow-lg">
                    @endif
                    <form id="profile_image_form" name="profile_image_form" class="absolute bottom-0 right-0 flex items-center justify-center">
                        <input type="file" name="picture" id="fileInput" class="hidden" />
                        <label for="fileInput" class="cursor-pointer bg-primary hover:bg-primary-dark text-text-white rounded-full p-2 shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-primary-light transition-colors duration-200" tabindex="0" aria-label="Upload profile image">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 10-4-4l-8 8v3zm0 0v3a1 1 0 001 1h3" />
                            </svg>
                        </label>
                    </form>
                </div>
                <div class="text-lg font-semibold text-text-primary mb-2">{{$user->name}}</div>
                <div class="w-full mt-4">
                    <ul class="space-y-2">
                        <li><a href="{{route('personal-profile')}}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('personal-profile') ? 'bg-primary text-text-white shadow-md' : 'bg-bg-light text-text-secondary hover:bg-primary hover:text-text-white hover:shadow-md' }} transition-all duration-200 font-medium">My Profile</a></li>
                        <li><a href="{{route('education-qualification')}}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('education-qualification') ? 'bg-primary text-text-white shadow-md' : 'bg-bg-light text-text-secondary hover:bg-primary hover:text-text-white hover:shadow-md' }} transition-all duration-200 font-medium">Education & Qualifications</a></li>
                        <li><a href="{{route('work-experience')}}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('work-experience') ? 'bg-primary text-text-white shadow-md' : 'bg-bg-light text-text-secondary hover:bg-primary hover:text-text-white hover:shadow-md' }} transition-all duration-200 font-medium">Work Experience</a></li>
                        <li><a href="{{route('change-password')}}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('change-password') ? 'bg-primary text-text-white shadow-md' : 'bg-bg-light text-text-secondary hover:bg-primary hover:text-text-white hover:shadow-md' }} transition-all duration-200 font-medium">Change Password</a></li>
                    </ul>
                </div>
            </div>
            <!-- Change Password Form -->
            <div class="md:w-2/3">
                <div class="bg-bg-secondary border border-border rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-text-primary">Change Password</h2>
                        <div class="w-8 h-8 bg-primary-light rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <form class="space-y-6" autocomplete="off" method="POST" action="{{ route('change-password-post') }}" name="changepassword" id="changepassword">
                        @csrf
                        <div>
                            <label for="old_password" class="block text-sm font-medium mb-2 text-text-secondary">Current Password</label>
                            <input type="password" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="old_password" name="old_password" placeholder="Enter your current password">
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium mb-2 text-text-secondary">New Password</label>
                            <input type="password" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="new_password" name="new_password" placeholder="Enter your new password">
                        </div>
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium mb-2 text-text-secondary">Confirm New Password</label>
                            <input type="password" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="confirm_password" name="confirm_password" placeholder="Confirm your new password">
                        </div>
                        <div class="flex justify-end mt-8">
                            <button type="submit" class="btn-primary px-8 py-3 rounded-xl font-semibold flex items-center space-x-2 shadow-lg hover:shadow-xl transition-all duration-300">
                                <span>Update Password</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
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
                alert('Error uploading image.');
                $('#fileInput').val('');
            });
            return false;
        });

        $("#changepassword").validate({
            rules: {
                'old_password': { required: true },
                'new_password': { required: true, minlength: 6 },
                'confirm_password': { required: true, equalTo: "#new_password" },
            },
            messages: {
                'old_password': { required: "Please enter your current password" },
                'new_password': {
                    required: "Please enter a new password",
                    minlength: "Password must be at least 6 characters long"
                },
                'confirm_password': {
                    required: "Please confirm your new password",
                    equalTo: "Passwords do not match"
                },
            },
            submitHandler: function(form) {
                var _url = '{{route("change-password-post")}}';
                var data = new FormData(form);
                $.ajax({
                    url: _url,
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response) {
                            swal({
                                title: "Success!",
                                text: response.message,
                                type: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK"
                            },
                            function(){
                                location.reload();
                            });
                        }
                    },
                }).fail(function(xhr, textStatus, errorThrown) {
                    $('.text-danger').empty();
                    var errors = "";
                    if (xhr.status == 422) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(i, val) {
                                errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                            });
                            if (errors !== "") {
                                swal({
                                    title: "Error!",
                                    text: errors,
                                    type: "error",
                                    html: true,
                                    confirmButtonColor: "#d33"
                                });
                            }
                        }
                    } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                        swal({
                            title: "Error!",
                            text: "Server error",
                            type: "error",
                            html: true,
                            confirmButtonColor: "#d33"
                        });
                        return false;
                    } else {
                        swal({
                            title: "Error!",
                            text: "No internet Connection. Please check your internet connection.",
                            type: "error",
                            html: true,
                            confirmButtonColor: "#d33"
                        });
                        return false;
                    }
                });
                return false;
            }
        });
    });
</script>
@endpush
