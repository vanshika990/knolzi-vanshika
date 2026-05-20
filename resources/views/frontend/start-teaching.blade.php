@extends('frontend.layouts.app')

@section('meta_title', $page_all_data['seo_meta']->title ?? 'Digital Class')
@section('meta_description', $page_all_data['seo_meta']->description ?? '')
@section('meta_keywords', $page_all_data['seo_meta']->keyword ?? '')
@section('meta_image', asset('assets/front/images/logo.png'))

@section('content')

    <!-- Hero Section -->
    <section class="relative max-w-7xl mx-auto px-6 py-20 flex items-center mt-10">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-3xl shadow-2xl overflow-hidden"></div>
        <div class="absolute inset-0 bg-cover bg-center hero-fade-mask rounded-3xl" style="background-image: url('{{ $page_all_data['hero_sec_image'] }}');"></div>
        <div class="absolute inset-0 bg-white/80 rounded-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center w-full h-full px-8 md:px-16">
            <div class="flex flex-col justify-center h-full text-left max-w-2xl w-full space-y-8 md:pr-12">
                <h1 class="text-5xl md:text-6xl font-bold leading-tight drop-shadow-lg text-primary">
                    {{ $page_all_data['hero_sec_title'] }}
                </h1>
                <p class="text-xl text-secondary">
                    {!! strip_tags($page_all_data['hero_sec_description']) !!}
                </p>
                <button class="btn-primary px-8 py-4 rounded-full text-lg font-semibold shadow-lg mt-8" onclick="openModal()">
                    START TEACHING NOW
                </button>
            </div>
        </div>
    </section>

    <!-- Boost Income Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="bg-gradient-to-r from-primary via-primary to-primary-dark rounded-3xl flex flex-col md:flex-row items-center shadow-2xl px-6 md:px-16 py-12 gap-8 md:gap-0 overflow-hidden relative">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-light/20 to-primary-dark/20"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-light/10 rounded-full -translate-y-32 translate-x-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-primary/10 rounded-full translate-y-24 -translate-x-24"></div>

            <!-- Image Left -->
            <div class="flex items-center justify-center md:w-1/2 mb-8 md:mb-0 relative z-10">
                <div class="w-64 h-64 bg-white/95 rounded-2xl flex items-center justify-center shadow-2xl ring-4 ring-white/30 backdrop-blur-sm">
                    <img src="{{ $page_all_data['teachingpage_boost_income_sec_image'] }}"
                         alt="Boost Income"
                         class="object-contain w-48 h-48 md:w-56 md:h-56" />
                </div>
            </div>
            <!-- Content Right -->
            <div class="flex flex-col justify-center items-center text-center md:w-1/2 px-0 md:px-8 relative z-10">
                <h2 class="text-4xl font-bold mb-4 text-white drop-shadow-lg">
                    {{ $page_all_data['teachingpage_boost_income_sec_title'] }}
                </h2>
                <p class="text-lg text-white/95 mb-8 leading-relaxed">
                    {!! strip_tags($page_all_data['teachingpage_boost_income_sec_description']) !!}
                </p>
                <button class="bg-white text-primary hover:bg-gray-50 px-8 py-4 rounded-full text-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 mt-8 border-2 border-white/20" onclick="openModal()">
                    START TEACHING NOW
                </button>
            </div>
        </div>
    </section>

    <!-- Top Features Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4 text-text-primary">TOP Features</h2>
            <p class="text-xl text-secondary">Ready to use functionalities to manage your course</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($page_all_data['features_features'] as $row)
            <div class="bg-bg-primary border border-border rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                <img src="{{ $row['image'] }}" class="rounded-xl shadow-lg max-h-32 object-contain mb-4 transition-transform duration-700 hover:scale-110 mx-auto" />
                <h3 class="text-xl font-semibold mb-4 text-text-primary">{{ $row['title'] }}</h3>
                <p class="text-secondary">{{ $row['sub_title'] }}</p>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <button class="btn-primary px-8 py-4 rounded-full text-lg font-semibold mt-8" onclick="openModal()">
                START TEACHING NOW
            </button>
        </div>
    </section>

    <!-- Help Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4 text-text-primary">Knolzi will help in</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($page_all_data['help_help'] as $row)
            <div class="bg-bg-primary border border-border rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                <img src="{{ $row['image'] }}" class="rounded-xl shadow-lg max-h-32 object-contain mb-4 transition-transform duration-700 hover:scale-110 mx-auto" />
                <h3 class="text-xl font-semibold mb-4 text-text-primary">{{ $row['title'] }}</h3>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <button class="btn-primary px-8 py-4 rounded-full text-lg font-semibold mt-8" onclick="openModal()">
                START TEACHING NOW
            </button>
        </div>
    </section>

    <!-- Start Teaching Modal -->
    <div id="teachingModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden overflow-y-auto">
        <div class="h-auto flex items-center justify-center p-4">
            <div class="bg-bg-primary rounded-3xl p-6 md:p-8 shadow-2xl max-w-4xl w-full relative animate-scale-in border border-border">
                <!-- Close Button -->
                <button onclick="closeModal()" class="absolute top-4 right-4 text-secondary hover:text-text-primary text-2xl">
                    ✕
                </button>
                <!-- Header -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl md:text-4xl font-bold mb-2 text-text-primary">
                        Start Teaching Now
                    </h2>
                    <p class="text-secondary max-w-2xl mx-auto">Join our platform and start sharing your knowledge with the world</p>
                </div>
                <!-- Form -->
                <form action="#" name="startteachingform" id="startteachingform" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-text-primary">Contact Name</label>
                            <input type="text" name="contact_name" placeholder="Enter Contact Name" class="w-full bg-bg-secondary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-text-primary">Email</label>
                            <input type="email" name="email" placeholder="Enter Email" class="w-full bg-bg-secondary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-text-primary">Phone Number</label>
                        <input type="tel" name="phone_number" placeholder="Enter Phone Number" class="w-full bg-bg-secondary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-text-primary">How experienced are you in Online Teaching?</label>
                            <div class="space-y-1">
                            <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                <input type="radio" name="online_teaching_experience" value="I'm Beginner / I'm not having knowledge of Online Teaching ever before" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                <span class="text-text-primary leading-snug">I'm Beginner / I'm not having knowledge of Online Teaching ever before</span>
                            </label>
                            <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                <input type="radio" name="online_teaching_experience" value="I'm Experienced / I have some knowledge of Online Teaching" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                <span class="text-text-primary leading-snug">I'm Experienced / I have some knowledge of Online Teaching</span>
                            </label>
                            <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                <input type="radio" name="online_teaching_experience" value="I'm Professional / I know all about Online Teaching" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                <span class="text-text-primary leading-snug">I'm Professional / I know all about Online Teaching</span>
                            </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-text-primary">Do you have your own audience to share your course?</label>
                            <div class="space-y-1">
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="own_audience" value="Not at the moment" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Not at the moment</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="own_audience" value="I have a small following" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">I have a small following</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="own_audience" value="I have a sizeable following" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">I have a sizeable following</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-text-primary">What kind of teaching do you provide?</label>
                            <div class="space-y-1">
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="teaching_provide" value="Classroom / In-person / Informal Teaching" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Classroom / In-person / Informal Teaching</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="teaching_provide" value="Institute level / Professional Teaching" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Institute level / Professional Teaching</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="teaching_provide" value="Online Teaching" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Online Teaching</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="teaching_provide" value="Other" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0" id="teaching_provide_other">
                                    <span class="text-text-primary leading-snug">Other</span>
                                </label>
                                <textarea name="other_teaching" id="other_teaching" class="w-full bg-bg-secondary border border-border px-4 py-3 rounded-xl text-text-primary placeholder-text-light outline-none mt-2 hidden focus:border-primary focus:ring-2 focus:ring-primary/20" cols="30" rows="2" placeholder="Enter Teaching"></textarea>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-text-primary">How did you hear about us?</label>
                            <div class="space-y-1">
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="hear_about_us" value="Social Media" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Social Media</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="hear_about_us" value="Linkedin" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Linkedin</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="hear_about_us" value="Search Engine (Google/Yahoo/Bing)" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Search Engine (Google/Yahoo/Bing)</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="hear_about_us" value="Word of Mouth" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Word of Mouth</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="hear_about_us" value="Recommended by Friend/Colleague" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Recommended by Friend/Colleague</span>
                                </label>
                                <label class="flex items-start space-x-3 cursor-pointer p-2 rounded-lg">
                                    <input type="radio" name="hear_about_us" value="Blogs" class="w-5 h-5 text-primary border-border focus:ring-primary/20 mt-0.5 flex-shrink-0">
                                    <span class="text-text-primary leading-snug">Blogs</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full pt-6">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 py-3 px-8 rounded-xl font-semibold text-white flex items-center justify-center space-x-2 text-lg shadow-lg mt-2 mx-auto block transition-all duration-300">
                            <span>Submit</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">
// Define globally so they are accessible from HTML onclick
function openModal() {
    document.getElementById('teachingModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('teachingModal').classList.add('hidden');
}

$(document).ready(function() {
    // Show/hide 'other_teaching' textarea
    $('input[name=teaching_provide]').click(function() {
        var type = $(this).val();
        if (type == 'Other') {
            $('#other_teaching').removeClass('hidden').show();
        } else {
            $('#other_teaching').addClass('hidden').hide();
        }
    });
    // Form validation and AJAX submit
    $("#startteachingform").validate({
        rules: {
            contact_name: "required",
            email: "required",
            phone_number: "required",
        },
        submitHandler: function(form) {
            // Optionally show a loading indicator here
            var data = new FormData(form);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('startteaching') }}",
                type: 'POST',
                contentType: false,
                data: data,
                processData: false,
                cache: false,
                success: function(response) {
                    // Optionally show a success message
                    $("#teachingModal .min-h-screen").html('<div class="text-center text-white py-16">' + response.message + '</div>');
                    // Reload on modal close
                    $("#teachingModal").on("click", function(e) {
                        if (e.target === this) location.reload();
                    });
                }
            }).fail(function(xhr, textStatus, errorThrown) {
                var errors = "";
                if (xhr.status == 422) {
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(i, val) {
                            errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                        });
                        if (errors !== "") {
                            // Show error above the form
                            if (!$('#startteachingerror').length) {
                                $("#startteachingform").prepend('<div id="startteachingerror" class="text-red-500 mb-4">'+errors+'</div>');
                            } else {
                                $('#startteachingerror').html(errors).show();
                            }
                        }
                    }
                } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                    $('#startteachingerror').html("Server error").show();
                    return false;
                } else {
                    $('#startteachingerror').html("No internet Connection. please check your internet connection.").show();
                    return false;
                }
            });
            return false;
        }
    });
});
</script>
@endpush
