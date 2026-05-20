</div>
</div>
</section>
<!-- footer start -->
<section class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-lg-12">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-12 text-center">
                        <div class="footer-brand">
                            <img src="{{ asset('assets/front/images/logo-w.svg') }}" class="img-fluid text-white" alt="Knolzi" />
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8 col-md-12">
                        <div class="footer-links">
                            <ul>
                                <li><a href="{{ route('digital-class') }}">Digital Classroom</a></li>
                                <li><a href="{{ route('start-teaching') }}">Start Teaching</a></li>
                                <li><a href="{{ route('aboutus') }}">About us</a></li>
                                <li><a href="{{ route('contactus') }}">Contact us</a></li>
                            </ul>
                            <ul>
                                <li><a href="{{ route('terms') }}">Terms</a></li>
                                <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('disclaimer') }}">Disclamier</a></li>
                                <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                            </ul>
                            <ul>
                                <li><a href="https://blog.edupme.com">Insights</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-5 col-md-6 mx-auto">
                <div class="errors"></div>
                <div class="success"></div>
                <form class="form-group txt-fltr search-blk" role="search" method="post" id="subscribeform" name="subscribeform" action="{{ route('subscriber') }}">
                    <h5 for="" class="text-white font-bd">Subscribe</h5>
                    <input type="email" name="email" class="form-control subscribe" placeholder="Enter your email id" id="email" autocomplete="off">
                    <label for="search-submit" id="submit-btn">
                        <i class="fa fa-location-arrow" aria-hidden="true"></i>
                    </label>
                </form>
                @php
                $social_media = getSocialMediaLink();
                @endphp
                <div class="footer-social">
                    <ul>
                        <li><a href="{{ $social_media['twitter_url'] }}" class="ftr-tw"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="{{ $social_media['facebook_url'] }}" class="ftr-fb"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="{{ $social_media['instagram_url'] }}" class="ftr-ins"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="{{ $social_media['linkedin_url'] }}" class="ftr-linked"><i class="fab fa-linkedin-in"></i></a></li>
                        <li><a href="{{ $social_media['youtube_url'] }}" class="ftr-ytb"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
                <div class="download-app text-center">
                    <h5 class="text-white font-bd mb-2">Available Now</h5>
                    <ul>
                        <li><a href="javascript:void(0)" class="dwn-ply-str"><img src="{{ asset('assets/front/images/dwn-google-str.png') }}" class="img-fluid" alt="play-store"></a></li>
                        <li><a href="javascript:void(0)" class="app-str"><img src="{{ asset('assets/front/images/dwn-app-str.png') }}" class="img-fluid" alt="app-store"></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12 text-center">
                <div class="border"></div>
                <div class="copyright">
                    <p class="text-white mt-3">Copyright 2021 All Rights Reserved</p>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('keyup keypress', 'input.subscribe', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                return false;
            }
        });
        $("#submit-btn").click(function(e) {
            e.preventDefault();
            var _url = '{{ route("subscriber") }}';
            var myform = document.getElementById("subscribeform");
            var data = new FormData(myform);
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
                        $(".loading").hide();
                        $(".errors").hide();
                        $(".success").html("<b><p class='alert alert-success' style='color:green'>" + response.message + "</p></b>").show();
                        $('#email').val('');

                    }
                    $(".loading").hide();
                },
            }).fail(function(xhr, textStatus, errorThrown) {
                $(".success").hide();
                $('.loading').hide();
                var errors = "";
                if (xhr.status == 422) {
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(i, val) {
                            errors += "<b><p class='alert alert-danger' style='color:red'>" + val[0] + "</p></b>";
                        });
                        if (errors !== "") {
                            $(".errors").html(errors);
                        }
                    }
                } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                    $(".errors").html("Server error");
                    return false;
                } else {
                    $(".errors").html("No internet Connection. please check your internet connection.");
                    return false;
                }
            });
            return false;
        });
    });
</script>

<!-- footer end -->


