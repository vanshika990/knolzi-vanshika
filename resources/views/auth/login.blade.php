<x-layout-front-base>
    @if(!empty($seometa))
    @section('meta_title', $seometa['title'])
    @section('meta_description', $seometa['description'])
    @section('meta_keywords', $seometa['keyword'])
    @section('meta_image',asset('assets/front/images/logo.png'))
    @endif
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Login In to Your Knolzi Account </h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <section class="login-page mt-5 mb-5">
        <div class="container">
            <x-message/>
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5">
                    <div class="edupme-forms">
                        <form method="POST" autocomplete="off" action="{{ route('login') }}" name="loginform" id="loginform">
                            @csrf
                            <div class="mb-3">
                                <div class="form-group">
                                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" required placeholder="Enter Email Address">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="paswword" required placeholder="Enter Your Password" autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="pwdeye">
                                        <span class="text-dark"><i id="eyeChange" class="bi bi-eye-fill" onclick="passwordEnableOrDisable()" ></i></span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-orange form-submit">Login</button>
                            <div class="text-center mt-2 mb-2">
                                or <a href="{{ route('password.request') }}" class="font-bd text-primary"> Forgot Password</a>
                            </div>
                            <div class="text-center mt-2 mb-2">
                                Don't have an account? <a href="{{ route('register') }}" class="font-bd text-primary"> Sign Up</a>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <a class="btn btn-primary m-2" href="{{ url('auth/facebook') }}" style="" id="btn-fblogin">
                                <i class="bi bi-facebook pe-2"></i> Login with Facebook
                            </a><a class="btn btn-primary m-2" href="{{ url('auth/google') }}" style="" id="btn-fblogin">
                                <i class="bi bi-google pe-2"></i> Login with Google
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @section('script')
    <script>
        $("#loginform").validate({
            rules: {
                email: "required",
                password: "required"
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
        function passwordEnableOrDisable() {
            var x = $('#paswword').prop("type");
            if (x === "password") {
                $('#paswword').prop("type", "text");
                $('#eyeChange').removeClass('bi-eye-fill');
                $('#eyeChange').addClass('bi-eye-slash-fill');
            }
            else
            {
                $('#paswword').prop("type", "password");
                $('#eyeChange').removeClass('bi-eye-slash-fill');
                $('#eyeChange').addClass('bi-eye-fill');
            }
        }
    </script>
    @endsection
    @stop
</x-layout-front-base>
