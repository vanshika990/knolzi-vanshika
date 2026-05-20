<x-layout-front-base>
    @if(!empty($page_all_data['seo_meta']))
    @section('meta_title', $page_all_data['seo_meta']->title)
    @section('meta_description', $page_all_data['seo_meta']->description)
    @section('meta_keywords', $page_all_data['seo_meta']->keyword)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @endif
    @section('content')

    <!-- hero image start -->
    <section class="hero-dc hero-st">
        <div class="hero-area">
            <img src="{{ $page_all_data['hero_sec_image'] }}" class="img-fluid" alt="knolzi" />
            <div class="hero-img-content">
                <div class="hero-title">
                    <h1>{{ $page_all_data['hero_sec_title'] }}</h1>
                    <p>{{ strip_tags($page_all_data['hero_sec_description']) }}</p>
                </div>
                <div class="hero-btn d-flex align-items-center">
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#startteaching">Start Teaching Now</a>
                </div>
            </div>
        </div>
    </section>
    <!-- hero image end -->
    <!-- boost your income section start -->
    <section class="boost-income d-flex align-items-center justify-content-between">
        <div class="container">
            <div class="d-lg-flex d-md-flex d-block align-items-center justify-content-center boost-bg">
                <img src="{{ $page_all_data['teachingpage_boost_income_sec_image'] }}" class="img-fluid"/>
                <div class="boost-income-content text-center">
                    <h1>{{ $page_all_data['teachingpage_boost_income_sec_title'] }}</h1>
                    <p>{{ strip_tags($page_all_data['teachingpage_boost_income_sec_description']) }}</p>
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#startteaching">Start Teaching Now</a>
                </div>
            </div>
        </div>
    </section>
    <!-- boost your income section end -->
    <!-- top features section start -->
    <section class="top-features-section">
        <div class="container">
            <div class="border-top m-5 m-lg-5"></div>
            <div class="row text-center justify-content-center">
                <div class="col-lg-12">
                    <div class="top-feature-title mb-5">
                        <h1>TOP Features</h1>
                        <p>Ready to use functionalities to manage your course</p>
                    </div>
                </div>
                @foreach($page_all_data['features_features'] as $row)
                <div class="col-lg-4 col-md-6">
                    <div class="tfeature-block">
                        <img src="{{ $row['image'] }}" class="img-fluid"/>
                        <h3>{{ $row['title'] }}</h3>
                        <p>{{ $row['sub_title'] }}</p>
                    </div>
                </div>
                @endforeach
                <div class="col-lg-12 text-center mt-0 mt-lg-4 mt-md-4">
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#startteaching">Start Teaching Now</a>
                </div>
            </div>
            <div class="border-top m-5 m-lg-5"></div>
        </div>
    </section>
    <!-- top features section end -->
    <!-- knolzi will help in start -->
    <section class="edupme-helpin">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12 mb-4">
                    <div class="top-feature-title">
                        <h1>knolzi will help in</h1>
                    </div>
                </div>
                @foreach($page_all_data['help_help'] as $row)
                <div class="col-lg-4 col-md-4">
                    <div class="tfeature-block">
                        <img src="{{ $row['image'] }}" class="img-fluid"/>
                        <h3>{{ $row['title'] }}</h3>
                    </div>
                </div>
                @endforeach
                <div class="col-lg-12 text-center mt-0 mt-lg-4 mt-md-4">
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#startteaching">Start Teaching Now</a>
                </div>
            </div>
        </div>
    </section>
    <!-- knolzi will help in end -->
    <!-- Modal -->
    <div class="modal fade" id="startteaching" tabindex="-1" aria-labelledby="startteachingLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="startteachingLabel">Start Teaching Now</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" id="startteachingerror" style="display: none;">
                    </div>
                    <form action="#" name="startteachingform" id="startteachingform" method="POST">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="Contact Name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" name="contact_name" placeholder="Enter Contact Name" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="Email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label for="Phone Number" class="form-label required">Phone Number </label>
                                <input type="tel" class="form-control" name="phone_number" placeholder="Enter Phone Number" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="Experience in online teaching" class="form-label">How experienced are you in Online Teaching?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="online_teaching_experience" value="I'm Beginner / I'm not having knowledge of Online Teaching ever before" id="online_teaching_experience1">
                                    <label class="form-check-label" for="online_teaching_experience">
                                        I'm Beginner / I'm not having knowledge of Online Teaching ever before
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="online_teaching_experience" value="I'm Experienced / I have some knowledge of Online Teaching" id="online_teaching_experience2">
                                    <label class="form-check-label" for="online_teaching_experience2">
                                        I'm Experienced / I have some knowledge of Online Teaching
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="online_teaching_experience" value="I'm Professional / I know all about Online Teaching" id="online_teaching_experience3">
                                    <label class="form-check-label" for="online_teaching_experience3">
                                        I'm Professional / I know all about Online Teaching
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="Own audience" class="form-label">Do you have your own audience to share your course?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="own_audience" value="Not at the moment" id="own_audience1">
                                    <label class="form-check-label" for="own_audience">
                                        Not at the moment
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="own_audience" value="I have a small following" id="own_audience2">
                                    <label class="form-check-label" for="own_audience2">
                                        I have a small following
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="own_audience" value="I have a sizeable following" id="own_audience3">
                                    <label class="form-check-label" for="own_audience3">
                                        I have a sizeable following
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="Teaching provide" class="form-label">What kind of teaching do you provide?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="teaching_provide" value="Classroom / In-person / Informal Teaching" id="teaching_provide1">
                                    <label class="form-check-label" for="teaching_provide1">
                                        Classroom / In-person / Informal Teaching
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="teaching_provide" value="Institute level / Professional Teaching" id="teaching_provide2">
                                    <label class="form-check-label" for="teaching_provide2">
                                        Institute level / Professional Teaching
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="teaching_provide" value="Online Teaching" id="teaching_provide3">
                                    <label class="form-check-label" for="teaching_provide3">
                                        Online Teaching
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="teaching_provide" value="Other" id="teaching_provide4">
                                    <label class="form-check-label" for="teaching_provide4">
                                        Other
                                    </label>
                                    <textarea name="other_teaching" id="other_teaching" class="form-control" cols="30" rows="2" placeholder="Enter Teaching" style="display:none"></textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="How did you hear about us?" class="form-label">How did you hear about us? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hear_about_us" value="Social Media" id="hear_about_us1">
                                    <label class="form-check-label" for="hear_about_us">
                                        Social Media
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hear_about_us" value="Linkedin" id="hear_about_us2">
                                    <label class="form-check-label" for="hear_about_us2">
                                        Linkedin
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hear_about_us" value="Search Engine (Google/Yahoo/Bing)" id="hear_about_us3">
                                    <label class="form-check-label" for="hear_about_us3">
                                        Search Engine (Google/Yahoo/Bing)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hear_about_us" value="Word of Mouth" id="hear_about_us4">
                                    <label class="form-check-label" for="hear_about_us4">
                                        Word of Mouth
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hear_about_us" value="Recommended by Friend/Colleague" id="hear_about_us5">
                                    <label class="form-check-label" for="hear_about_us5">
                                        Recommended by Friend/Colleague
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hear_about_us" value="Blogs" id="hear_about_us6">
                                    <label class="form-check-label" for="hear_about_us6">
                                        Blogs
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="loading" style="display:none">Loading&#8230;</div>
    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name=teaching_provide]').click(function() {
                var type = $(this).val();
                if (type == 'Other') {
                    $('#other_teaching').show();
                }
                else {
                    $('#other_teaching').hide();
                }
            });

            $("#startteachingform").validate({
                rules: {
                    contact_name: "required",
                    email: "required",
                    phone_number: "required",
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
                        url: "{{ route('startteaching') }}",
                        type: 'POST',
                        contentType: false,
                        data: data,
                        processData: false,
                        cache: false,
                        success: function(response) {
                            $("#startteachingerror").hide();
                            $(".modal .modal-body").html(response.message);
                            $("#startteaching").on("hidden.bs.modal", function() {
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
                                    $("#startteachingerror").html(errors).show();
                                }
                            }
                        } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                            $("#startteachingerror").html("Server error").show();
                            return false;
                        } else {
                            $("#startteachingerror").html("No internet Connection. please check your internet connection.").show();
                            return false;
                        }
                    });
                    return false;
                }
            });
        });
    </script>
    @endsection
    @stop
</x-layout-front-base>
