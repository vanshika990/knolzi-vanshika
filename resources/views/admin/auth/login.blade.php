<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name', 'Knolzi') }} | Admin Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--begin::Global Stylesheets Bundle(used by all pages)-->
        <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
        <!--end::Global Stylesheets Bundle-->
        {{-- <script src="{{ mix('js/app.js') }}" defer></script>  --}}
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" class="">
        <!--begin::Main-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Authentication - Sign-in -->
            <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-size1: 100% 50%; background-image: url({{ asset('assets/media/illustrations/development-hd-dark.png') }}">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                    <!--begin::Logo-->
                    <a href="{{ url('/') }}" class="mb-12">
                        {{-- <img alt="Knolzi" src="{{asset('assets/images/logo.png')}}" class="h-75px" /> --}}
                        <img alt="Knolzi" src="{{asset('assets/images/logo.png')}}" class="h-75px" />
                    </a>
                    <!--end::Logo-->
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                        @if(\Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ \Session::get('success') }}
                        </div>
                        @endif
                        {{ \Session::forget('success') }}
                        @if(\Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ \Session::get('error') }}
                        </div>
                        @endif
                        <!--begin::Form-->
                        <form class="form w-100"  id="kt_sign_in_form"  action="{{ route('adminLoginPost') }}" method="post">
                            {!! csrf_field() !!}
                            <!--begin::Heading-->
                            <div class="text-center mb-10">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3">Sign In to Knolzi</h1>
                                <!--end::Title-->
                            </div>
                            <!--begin::Heading-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-lg form-control-solid" type="email" name="email" autocomplete="off" value="{{ old('email') }}" placeholder="Enter Email" required autofocus/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack mb-2">
                                    <!--begin::Label-->
                                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Input-->
                                <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" placeholder="Enter Password" required/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="text-center">
                                <!--begin::Submit button-->
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    <span class="indicator-label">Continue</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Submit button-->

                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Authentication - Sign-in-->
        </div>
        <!--end::Main-->
        <!--begin::Javascript-->
        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script src="{{asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{asset('assets/js/scripts.bundle.js') }}"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Page Custom Javascript(used by this page)-->
        <script src="{{asset('assets/js/custom/authentication/sign-in/admin-auth.js') }}"></script>
        <!--end::Page Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->
</html>
