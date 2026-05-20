<x-layout-front-base>
    @if(!empty($page_all_data['seo_meta']))
    @section('meta_title', $page_all_data['seo_meta']->title)
    @section('meta_description', $page_all_data['seo_meta']->description)
    @section('meta_keywords', $page_all_data['seo_meta']->keyword)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @endif
    @section('content')

    <!-- hero image start -->
    <section class="hero-dc">
        <div class="hero-area">
            <img src="{{ $page_all_data['hero_sec_image'] }}" class="img-fluid" alt="knolzi" />
            <div class="hero-img-content">
                <div class="hero-title">
                    <h1>{{ $page_all_data['hero_sec_title'] }} </h1>
                    <p>{{ $page_all_data['hero_sec_description'] }}</p>
                </div>
                <div class="hero-btn d-flex align-items-center">
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#bookyourdemo">Book your free demo</a>
                </div>
            </div>
        </div>
    </section>
    <!-- hero image end -->
    <!-- how it works section start -->
    <section class="howitworks">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="howitworks-block d-flex align-items-center justify-content-between">
                        <h1>{{ $page_all_data['how_it_work_sec_title'] }}</h1>
                        <span>{{ $page_all_data['how_it_work_sec_sub_title'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- how it works section end -->
    <!-- infographic section start -->
    <section class="howitworks-infograph">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-4 mt-4">
                    <img src="{{ $page_all_data['how_it_work_sec_image'] }}" class="img-fluid" />
                </div>
                <div class="col-lg-8 text-center">
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#bookyourdemo">Book your free demo</a>
                </div>
            </div>
        </div>
    </section>
    <!-- infographic section end -->
    <!-- learning cycle section start -->
    <section class="learn-teach-cycle">
        <div class="container">
            <div class="border-top m-5 m-lg-4 mt-lg-5"></div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="learn-teach-cycle-blk">
                        @if($page_all_data['teaching_cycle_sec_image']!='')
                        <img src="{{ $page_all_data['teaching_cycle_sec_image'] }}" class="img-fluid" />
                        @else
                        <img src="{{ asset('assets/front/images/teaching-cycle.png') }}" class="img-fluid" />
                        @endif
                        <div class="learn-teach-cycle-text">
                            <h1>{{ $page_all_data['teaching_cycle_sec_title'] }}</h1>
                            <p>{{ $page_all_data['teaching_cycle_sec_sub_title'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="learn-teach-cycle-blk">
                        @if($page_all_data['learning_cycle_sec_image']!='')
                        <img src="{{ $page_all_data['learning_cycle_sec_image'] }}" class="img-fluid" />
                        @else
                        <img src="{{ asset('assets/front/images/learning-cycle.png') }}" class="img-fluid" />
                        @endif
                        <div class="learn-teach-cycle-text">
                            <h1>{{ $page_all_data['learning_cycle_sec_title'] }}</h1>
                            <p>{{ $page_all_data['learning_cycle_sec_sub_title'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-top m-5 m-lg-4 mt-lg-5"></div>
        </div>
    </section>
    <!-- learning cycle section end -->
    <!-- top features section start -->
    <section class="top-features-section">
        <div class="container">
            <div class="row text-center justify-content-center">
                <div class="col-lg-12">
                    <div class="top-feature-title mb-5">
                        <h1>TOP Features</h1>
                        <p>Ready to use functionalities for course management, create and sell online courses with your own branded App</p>
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
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#bookyourdemo">Book your free demo</a>
                </div>
            </div>
            <div class="border-top m-5 m-lg-4 mt-lg-5"></div>
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
                    <a href="javascript:void(0)" class="btn btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#bookyourdemo">Book your free demo</a>
                </div>
            </div>
        </div>
    </section>
    <!-- knolzi will help in end -->
    <!-- Modal -->
    <div class="modal fade" id="bookyourdemo" tabindex="-1" aria-labelledby="bookyourdemoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookyourdemoLabel">Book your free demo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" id="bookyourdemoerror" style="display: none;">
                    </div>
                    <form action="#" name="bookyourdemoform" id="bookyourdemoform" method="POST">
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
                            <div class="mb-3 col-md-6">
                                <label for="Phone Number" class="form-label">Phone Number </label>
                                <input type="tel" class="form-control" name="phone_number" placeholder="Enter Phone Number" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="Institute Name" class="form-label">Institute Name</label>
                                <input type="text" class="form-control" name="institute_name" placeholder="Enter Institute Name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="Number of Students" class="form-label">Number of Students</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="no_of_students" value="0-100" id="no_of_students1">
                                    <label class="form-check-label" for="no_of_students1">
                                        0-100
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="no_of_students" value="100-500" id="no_of_students2">
                                    <label class="form-check-label" for="no_of_students2">
                                        100-500
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="no_of_students" value="500-1000" id="no_of_students3">
                                    <label class="form-check-label" for="no_of_students3">
                                        500-1000
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="no_of_students" value="1000-2000" id="no_of_students4">
                                    <label class="form-check-label" for="no_of_students4">
                                        1000-2000
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="no_of_students" value="2000+" id="no_of_students4">
                                    <label class="form-check-label" for="no_of_students4">
                                        2000+
                                    </label>
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
                        <div class="mb-3 col-md-12">
                            <label for="State" class="form-label">State</label>
                            <input type="text" class="form-control" name="state" placeholder="Enter State" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="Message" class="form-label">Message (If any)</label>
                            <textarea name="message" class="form-control" placeholder="Enter Message (If any)"></textarea>
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
    </script>
    @endsection
    @stop
</x-layout-front-base>
