<x-layout-front-base>
    @section('meta_title', 'Contact Us')
    @section('meta_description', 'We welcome feedback from the teaching community.')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- hero image start -->
    <section class="hero-dc hero-st">
        <div class="hero-area">
            <img src="{{ (empty($data)) ? '' : $data['contactuspage_hero_section']['hero_sec_image'] }}" class="img-fluid" alt="knolzi" />
            <div class="hero-img-content">
                <div class="hero-title">
                    <h1>{{ (empty($data)) ? '' : $data['contactuspage_hero_section']['hero_sec_title'] }}</h1>
                    <p>{{ (empty($data)) ? '' : strip_tags($data['contactuspage_hero_section']['hero_sec_description']) }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="contactus" tabindex="-1" aria-labelledby="contactusLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactusLabel">Contact-Us</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" id="contactuserror" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal -->
    <!-- hero image end -->
    <section class="login-page mt-5 mb-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8">
                    <div class="edupme-forms">
                        <form action="{{ route('contactusform') }}" name="contactusform" id="contactusform" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject Name</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Enter Your Subject" value="{{ old('subject') }}" autofocus>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="name" class="form-label">Contact Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter Contact Name" value="{{ old('name') }}">
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter Email Address" value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="mobile" class="form-label">Mobile Number</label>
                                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="Mobile Number" value="{{ old('mobile') }}">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <div id="requesthear_about_us">
                                    <label for="hear_about_us" class="form-label d-block">How did you hear about us? </label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hear_about_us" id="socialmedia" value="Social Media">
                                        <label class="form-check-label" for="socialmedia">Social Media</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hear_about_us" id="linkedin" value="LinkedIn">
                                        <label class="form-check-label" for="linkedin">LinkedIn</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hear_about_us" id="searchengine" value="Search Engine (Google/Yahoo/Bing)">
                                        <label class="form-check-label" for="searchengine">Search Engine (Google/Yahoo/Bing)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hear_about_us" id="wordofmouth" value="Word of Mouth">
                                        <label class="form-check-label" for="wordofmouth">Word of Mouth</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hear_about_us" id="recommended" value="Recommended by Friend/Colleague">
                                        <label class="form-check-label" for="recommended">Recommended by Friend/Colleague</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="hear_about_us" id="blogs" value="Blogs">
                                        <label class="form-check-label" for="blogs">Blogs</label>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" placeholder="Enter message" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-lg-4 mb-2">
                                    <button type="submit" class="btn btn-orange form-submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.001477026633!2d72.47003971496788!3d23.02371798495252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b50f3876ce5%3A0xde9e0f34a0afd7de!2sedupme!5e0!3m2!1sen!2sin!4v1630915182726!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
    <div class="loading" style="display:none">Loading&#8230;</div>
    @section('script')

    <script type="text/javascript">

        $(document).ready(function() {

            $("#contactusform").validate({
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "hear_about_us") {
                        error.insertAfter("#requesthear_about_us");
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    'subject': {
                        required: true,
                    },
                    'name': {
                        required: true,
                    },
                    'email': {
                        required: true,
                    },
                    'mobile': {
                        required: true,
                    },
                    'hear_about_us': {
                        required: true,
                    },
                    'message': {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    $(".loading").show();

                    var data = new FormData(form);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('contactusform') }}",
                        type: 'POST',
                        contentType: false,
                        data: data,
                        processData: false,
                        cache: false,
                        success: function(response) {
                            $(".loading").hide();

                            $('#contactus').modal('show');
                            $(".modal .modal-body").html(response.message);
                            $("#contactus").on("hidden.bs.modal", function() {
                                location.reload();
                            });

                        }
                    }).fail(function(xhr, textStatus, errorThrown) {
                        $(".loading").hide();
                        $('.text-danger').empty();
                        var errors = "";
                        if (xhr.status == 422) {
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(i, val) {
                                    errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                                });
                                if (errors !== "") {
                                    $('#contactus').modal('show');
                                    $("#contactuserror").html(errors).show();
                                }
                            }
                        } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                            $('#contactus').modal('show');
                            $("#contactuserror").html("Server error").show();
                            return false;
                        } else {
                            $('#contactus').modal('show');
                            $("#contactuserror").html("No internet Connection. please check your internet connection.").show();
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
