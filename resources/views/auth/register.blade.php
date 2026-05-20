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
                    <h1>Sign Up and Start Learning! </h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <section class="register-page mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <x-message/>
                    <div class="tab-content" id="registertab-content">
                        <div class="tab-pane fade show active" id="indv" role="tabpanel" aria-labelledby="indv-tab">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-lg-8">
                                    <div class="edupme-forms">
                                        <form autocomplete="off" method="POST" action="{{ route('register') }}" name="register-individual" id="register-individual">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="type" class="form-label">Select type <span style="color: #f1416c;">*</span> </label>
                                                        <select name="type" id="type" class="form-select ">
                                                            <option value="individual" {{ (old('type') == 'individual') ? 'selected' : '' }} >Individual</option>
                                                            <option value="organization" {{ (old('type') == 'organization') ? 'selected' : '' }} >Organization</option>
                                                        </select>
                                                        @error('type')
                                                        <span class="error" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <label for="name" class="form-label">Your Name <span style="color: #f1416c;">*</span> </label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Your Name" required autocomplete="name" autofocus>
                                                    @error('name')
                                                    <span class="error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Email address <span style="color: #f1416c;">*</span> </label>
                                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email Address" >
                                                        @error('email')
                                                        <span class="error" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="mobilenum" class="form-label">Mobile Number <span style="color: #f1416c;">*</span> </label>
                                                        <input type="number" class="form-control @error('mobile') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" required placeholder="Mobile Number">
                                                        @error('mobile_number')
                                                        <span class="error" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="password" class="form-label">Password <span style="color: #f1416c;">*</span> </label>
                                                        <input id="indvpassword" type="password" class="form-control" name="password" required placeholder="Your Password" autocomplete="new-password">
                                                        <div class="pwdeye">
                                                            <span class="text-dark"><i id="eyeChangeInd" class="bi bi-eye-fill" onclick="indvpasswordEnableOrDisable()"></i></span>
                                                        </div>
                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="cfpassword" class="form-label">Confirm Password <span style="color: #f1416c;">*</span> </label>
                                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">
                                                        <div class="pwdeye">
                                                            <span class="text-dark"><i id="eyeChangeInd2" class="bi bi-eye-fill" onclick="indvconfpasswordEnableOrDisable()"></i></span>
                                                        </div>
                                                        @error('password_confirmation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="dob" class="form-label">Date of Birth <span style="color: #f1416c;">*</span> </label>
                                                        <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="Date Of Birth" value="{{ old('birth_date') }}">
                                                        @error('birth_date')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3" id="cmp_code">
                                                    <div class="form-group">
                                                        <label for="cmpcode" class="form-label">Company Code</label>
                                                        <input type="text" class="form-control" name="company_code" id="company_code" value="{{old('company_code')}}" placeholder="Company Code">
                                                        @error('company_code')
                                                        <span class="error" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row justify-content-center mt-3">
                                                <div class="col-lg-4 mb-2">
                                                    <button type="submit" class="btn btn-orange form-submit">Sign Up</button>
                                                </div>
                                                <div class="text-center mt-2 mb-2">
                                                    By signing up, you agree to our <a href="{{ route('terms') }}" class="text-primary font-bd">Terms of Use</a> and <a href="{{ route('privacy') }}" class="text-primary font-bd">Privacy Policy</a>.
                                                </div>
                                                <div class="text-center mt-2 mb-2">
                                                    Already have an account? <a href="{{ route('login') }}" class="font-bd text-primary"> Log In</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @section('script')
    <script>
        $("#register-individual").validate({
            rules: {
                email: "required",
                password: "required",
                birth_date: "required"
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        function indvpasswordEnableOrDisable() {
            var x = $('#indvpassword').prop("type");
            if (x === "password") {
                $('#indvpassword').prop("type", "text");
                $('#eyeChangeInd').removeClass('bi-eye-fill');
                $('#eyeChangeInd').addClass('bi-eye-slash-fill');
            }
            else
            {
                $('#indvpassword').prop("type", "password");
                $('#eyeChangeInd').removeClass('bi-eye-slash-fill');
                $('#eyeChangeInd').addClass('bi-eye-fill');
            }
        }

        function indvconfpasswordEnableOrDisable() {
            var x = $('#password-confirm').prop("type");
            if (x === "password") {
                $('#password-confirm').prop("type", "text");
                $('#eyeChangeInd2').removeClass('bi-eye-fill');
                $('#eyeChangeInd2').addClass('bi-eye-slash-fill');
            }
            else
            {
                $('#password-confirm').prop("type", "password");
                $('#eyeChangeInd2').removeClass('bi-eye-slash-fill');
                $('#eyeChangeInd2').addClass('bi-eye-fill');
            }
        }

        $(document).ready(function() {
            $('#type').bind('change', function(e) {
                if ($('#type').val() == 'individual') {
                    $("#cmp_code").show();
                } else if ($('#type').val() == 'organization') {
                    $("#cmp_code").hide();
                } else {
                    $("#cmp_code").show();
                }
            }).trigger('change');

        });

    </script>
    @endsection
    @stop
</x-layout-front-base>