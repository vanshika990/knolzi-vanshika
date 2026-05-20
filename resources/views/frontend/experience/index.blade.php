@extends('frontend.layouts.app')
@section('meta_title', 'Work Experience')
@section('meta_description', 'Work Experience - Knolzi')
@section('meta_image',asset('assets/front/images/logo.png'))

@section('content')
<section class="relative z-10 max-w-5xl mx-auto px-6 py-16">
    <div class="bg-bg-primary rounded-3xl p-10 shadow-xl border border-border">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 text-text-primary">
                Work <span class="bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">Experience</span>
            </h1>
            <p class="text-text-secondary max-w-2xl mx-auto">
                View, add, and manage your work experience
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
                    </ul>
                </div>
            </div>
            <!-- Experience List & Add Button -->
            <div class="md:w-2/3">
                <div class="bg-bg-secondary border border-border rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-text-primary">Work Experience</h2>
                        <button type="button" class="btn-primary px-6 py-2 rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300" onclick="addexperience()">Add Experience</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(!empty($data) && count($data))
                            @foreach($data as $exp)
                                <div class="bg-bg-primary border border-border rounded-xl p-6 flex flex-col justify-between h-full shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <div>
                                        <div class="mb-3">
                                            <span class="font-bold text-text-primary">Company Name:</span>
                                            <span class="text-text-secondary ml-2">{{ $exp->company_name }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-bold text-text-primary">Experience:</span>
                                            <span class="text-text-secondary ml-2">{{ $exp->experience }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-bold text-text-primary">Year:</span>
                                            <span class="text-text-secondary ml-2">{{ $exp->year }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-bold text-text-primary">Role:</span>
                                            <span class="text-text-secondary ml-2">{{ $exp->role }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-bold text-text-primary">Designation:</span>
                                            <span class="text-text-secondary ml-2">{{ $exp->designation }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-row gap-2 mt-4 pt-4 border-t border-border-light">
                                        <button type="button" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-id="{{ encrypt($exp->id) }}" onclick="editexperience(this)">Edit</button>
                                        <button type="button" class="bg-error hover:bg-red-600 text-text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-id="{{ encrypt($exp->id) }}" onclick="deleteexperience(this)">Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-2 flex flex-col items-center justify-center py-12 text-center">
                                <div class="w-24 h-24 bg-bg-light rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-text-primary mb-2">No experience records found</h3>
                                <p class="text-text-secondary mb-4">Click "Add Experience" to create your first work experience entry.</p>
                                <button type="button" class="btn-primary px-6 py-3 rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300" onclick="addexperience()">Add Experience</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function showModalFromAjax(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $("#experienceModalAdd, #experienceModalEdit").remove();
                $('body').append(response);
                if ($('#experienceModalAdd').length) {
                    $('#experienceModalAdd').removeClass('hidden');
                    document.body.style.overflow = 'hidden';
                } else if ($('#experienceModalEdit').length) {
                    $('#experienceModalEdit').removeClass('hidden');
                    document.body.style.overflow = 'hidden';
                }
            },
            error: function() {
                alert('Failed to load modal.');
            }
        });
    }
    function addexperience() {
        var _url = '{{ route("add-work-experience") }}';
        showModalFromAjax(_url);
    }
    function editexperience(identifier) {
        var id = $(identifier).data('id');
        var _url = '{{ route("edit-work-experience", ":id") }}'.replace(':id', id);
        showModalFromAjax(_url);
    }
    function deleteexperience(identifier) {
        window.pendingDeleteId = $(identifier).data('id');
        $('#tailwind-confirm-modal').removeClass('hidden');
        $('body').addClass('overflow-hidden');
    }
    $(document).on('click', '#cancel-delete-btn', function() {
        $('#tailwind-confirm-modal').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        window.pendingDeleteId = null;
    });
    $(document).on('click', '#confirm-delete-btn', function() {
        if (!window.pendingDeleteId) return;
        var _url = '{{ route("work-experience-delete", ":id") }}'.replace(':id', window.pendingDeleteId);
        $.ajax({
            url: _url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#tailwind-confirm-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                if (response) {
                    location.reload();
                }
            },
            error: function() {
                $('#tailwind-confirm-modal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                alert('Error deleting experience.');
            }
        });
    });
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
    });
</script>
<!-- TailwindCSS Confirm Modal -->
<div id="tailwind-confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-bg-primary rounded-xl shadow-lg p-8 max-w-md w-full text-center flex flex-col items-center">
        <div class="flex flex-col items-center w-full">
            <div class="text-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold mb-2 text-text-primary">Are you sure you want to delete this experience?</h2>
            <p class="text-text-secondary mb-6">This action cannot be undone.</p>
            <div class="flex justify-center gap-4 w-full mt-2">
                <button id="cancel-delete-btn" class="px-6 py-2 rounded-lg bg-bg-light text-text-primary hover:bg-light font-semibold transition-colors duration-200">Cancel</button>
                <button id="confirm-delete-btn" class="px-6 py-2 rounded-lg bg-error text-text-white hover:bg-red-700 font-semibold transition-colors duration-200">Delete</button>
            </div>
        </div>
    </div>
</div>
@endpush
