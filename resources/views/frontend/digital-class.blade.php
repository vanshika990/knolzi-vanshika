@extends('frontend.layouts.app')

@section('meta_title', $page_all_data['seo_meta']->title ?? 'Digital Class')
@section('meta_description', $page_all_data['seo_meta']->description ?? '')
@section('meta_keywords', $page_all_data['seo_meta']->keyword ?? '')
@section('meta_image', asset('assets/front/images/logo.png'))

@section('content')
<!-- Hero Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-16">
    <div class="text-center">
        <div class="glass-effect rounded-3xl p-12 mb-8 animate-fade-in">
            <img src="{{ $page_all_data['hero_sec_image'] }}" class="mx-auto mb-6 rounded-2xl shadow-lg max-h-72 object-cover" alt="Knolzi" />
            <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight text-primary">{{ $page_all_data['hero_sec_title'] }}</h1>
            <p class="text-xl md:text-2xl text-gray-500 mb-6">{{ $page_all_data['hero_sec_description'] }}</p>
            <a href="javascript:void(0)" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold" data-bs-toggle="modal" data-bs-target="#bookyourdemo">Book your free demo</a>
        </div>
    </div>
</section>

<!-- How it Works Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-12">
    <div class="glass-effect-subtle rounded-2xl p-8 text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $page_all_data['how_it_work_sec_title'] }}</h1>
        <span class="text-lg text-gray-500">{{ $page_all_data['how_it_work_sec_sub_title'] }}</span>
    </div>
    <div class="flex justify-center mb-8">
        <img src="{{ $page_all_data['how_it_work_sec_image'] }}" class="rounded-xl shadow-lg max-h-80 object-contain" />
    </div>
    <div class="text-center">
        <a href="javascript:void(0)" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold" data-bs-toggle="modal" data-bs-target="#bookyourdemo">Book your free demo</a>
    </div>
</section>

<!-- Learning & Teaching Cycle Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="glass-effect-subtle rounded-2xl p-8 flex flex-col items-center">
            <img src="{{ $page_all_data['teaching_cycle_sec_image'] ?: asset('assets/front/images/teaching-cycle.png') }}" class="rounded-xl shadow-lg max-h-56 object-contain mb-4" />
            <h1 class="text-2xl font-bold mb-2">{{ $page_all_data['teaching_cycle_sec_title'] }}</h1>
            <p class="text-gray-500">{{ $page_all_data['teaching_cycle_sec_sub_title'] }}</p>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-8 flex flex-col items-center">
            <img src="{{ $page_all_data['learning_cycle_sec_image'] ?: asset('assets/front/images/learning-cycle.png') }}" class="rounded-xl shadow-lg max-h-56 object-contain mb-4" />
            <h1 class="text-2xl font-bold mb-2">{{ $page_all_data['learning_cycle_sec_title'] }}</h1>
            <p class="text-gray-500">{{ $page_all_data['learning_cycle_sec_sub_title'] }}</p>
        </div>
    </div>
</section>

<!-- Top Features Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-primary">TOP Features</h1>
        <p class="text-lg text-gray-500">Ready to use functionalities for course management, create and sell online courses with your own branded App</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        @foreach($page_all_data['features_features'] as $row)
        <div class="glass-effect-subtle rounded-2xl p-6 flex flex-col items-center">
            <img src="{{ $row['image'] }}" class="rounded-xl shadow-lg max-h-32 object-contain mb-4" />
            <h3 class="text-xl font-bold mb-2">{{ $row['title'] }}</h3>
            <p class="text-gray-500">{{ $row['sub_title'] }}</p>
        </div>
        @endforeach
    </div>
    <div class="text-center">
        <a href="javascript:void(0)" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold" data-bs-toggle="modal" data-bs-target="#bookyourdemo">Book your free demo</a>
    </div>
</section>

<!-- Knolzi Will Help In Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-primary">Knolzi will help in</h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        @foreach($page_all_data['help_help'] as $row)
        <div class="glass-effect-subtle rounded-2xl p-6 flex flex-col items-center">
            <img src="{{ $row['image'] }}" class="rounded-xl shadow-lg max-h-32 object-contain mb-4" />
            <h3 class="text-xl font-bold mb-2">{{ $row['title'] }}</h3>
        </div>
        @endforeach
    </div>
    <div class="text-center">
        <a href="javascript:void(0)" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold" onclick="openDemoModal()">Book your free demo</a>
    </div>
</section>

<!-- Book Your Free Demo Modal (TailwindCSS) -->
<div id="bookyourdemoModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden overflow-y-auto">
    <div class="h-auto flex items-center justify-center p-4 min-h-screen">
        <div class="glass-effect rounded-3xl p-6 md:p-8 shadow-2xl max-w-2xl w-full relative animate-scale-in bg-white border border-gray-200">
            <!-- Close Button -->
            <button onclick="closeDemoModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">
                &times;
            </button>
            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-3xl md:text-4xl font-bold mb-2 text-gray-800">
                    Book your free demo
                </h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Fill out the form below and our team will contact you for a free demo.</p>
            </div>
            <!-- Error Alert -->
            <div class="alert alert-danger text-red-400 mb-4 hidden" id="bookyourdemoerror"></div>
            <!-- Form -->
            <form action="#" name="bookyourdemoform" id="bookyourdemoform" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Contact Name</label>
                        <input type="text" name="contact_name" placeholder="Enter Contact Name" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none bg-white border border-gray-200 focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Email</label>
                        <input type="email" name="email" placeholder="Enter Email" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none bg-white border border-gray-200 focus:border-blue-400" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Phone Number</label>
                        <input type="tel" name="phone_number" placeholder="Enter Phone Number" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none bg-white border border-gray-200 focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Institute Name</label>
                        <input type="text" name="institute_name" placeholder="Enter Institute Name" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none bg-white border border-gray-200 focus:border-blue-400" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Number of Students</label>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="no_of_students" value="0-100">
                                <span>0-100</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="no_of_students" value="100-500">
                                <span>100-500</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="no_of_students" value="500-1000">
                                <span>500-1000</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="no_of_students" value="1000-2000">
                                <span>1000-2000</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="no_of_students" value="2000+">
                                <span>2000+</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">How did you hear about us?</label>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="hear_about_us" value="Social Media">
                                <span>Social Media</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="hear_about_us" value="Linkedin">
                                <span>Linkedin</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="hear_about_us" value="Search Engine (Google/Yahoo/Bing)">
                                <span>Search Engine (Google/Yahoo/Bing)</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="hear_about_us" value="Word of Mouth">
                                <span>Word of Mouth</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="hear_about_us" value="Recommended by Friend/Colleague">
                                <span>Recommended by Friend/Colleague</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                                <input class="form-check-input" type="radio" name="hear_about_us" value="Blogs">
                                <span>Blogs</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">State</label>
                        <input type="text" name="state" placeholder="Enter State" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none bg-white border border-gray-200 focus:border-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Message (If any)</label>
                        <textarea name="message" class="w-full input-field rounded-xl py-3 px-4 text-gray-700 placeholder-gray-400 outline-none bg-white border border-gray-200 focus:border-blue-400" placeholder="Enter Message (If any)"></textarea>
                    </div>
                </div>
                <div class="w-full pt-6">
                    <button type="submit" class="btn-primary py-3 px-8 rounded-xl font-semibold text-white flex items-center justify-center space-x-2 text-lg shadow-lg mt-2 mx-auto block">
                        <span>Submit</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </form>
            <div class="loading text-center text-gray-600 mt-4 hidden">Loading&#8230;</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $("#bookyourdemoform").validate({
            rules: {
                contact_name: "required",
                email: "required",
                phone_number: "required",
                institute_name: "required",
                state: "required",
            },
            submitHandler: function(form) {
                $(".loading").show();
                $('.float-end').text("Please Wait...");
                var data = new FormData(form);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('bookyourfreedemo') }}",
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function(response) {
                        $("#bookyourdemoerror").hide();
                        $(".modal .modal-body").html(response.message);
                        $("#bookyourdemo").on("hidden.bs.modal", function() {
                            location.reload();
                        });
                        $(".loading").hide();

                    }
                }).fail(function(xhr, textStatus, errorThrown) {
                    $(".loading").hide();
                    $('.float-end').text("submit");
                    $('.text-danger').empty();
                    var errors = "";
                    if (xhr.status == 422) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(i, val) {
                                errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                            });
                            if (errors !== "") {
                                $("#bookyourdemoerror").html(errors).show();
                            }
                        }
                    } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                        $("#bookyourdemoerror").html("Server error").show();
                        return false;
                    } else {
                        $("#bookyourdemoerror").html("No internet Connection. please check your internet connection.").show();
                        return false;
                    }
                });
                return false;
            }
        });
    });

// Modal open/close logic (robust)
function openDemoModal() {
    var modal = document.getElementById('bookyourdemoModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}
function closeDemoModal() {
    var modal = document.getElementById('bookyourdemoModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}
// Close modal by clicking outside content
window.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('mousedown', function(e) {
        var modal = document.getElementById('bookyourdemoModal');
        if (modal && !modal.classList.contains('hidden')) {
            var content = modal.querySelector('.glass-effect');
            if (content && !content.contains(e.target)) {
                closeDemoModal();
            }
        }
    });
    // Update all demo buttons to use the correct handler
    document.querySelectorAll('[data-demo-modal], .btn-primary[data-bs-target="#bookyourdemo"], a.btn-primary').forEach(function(btn) {
        if (btn.textContent && btn.textContent.toLowerCase().includes('book your free demo')) {
            btn.onclick = openDemoModal;
        }
    });
});
</script>
@endpush
