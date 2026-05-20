<x-layout-front-base>
@section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Forgot Password </h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <section class="login-page mt-5 mb-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-4">
                    <div class="edupme-forms">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" aria-describedby="emailHelp" placeholder="Enter Email Address">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-orange form-submit">Reset Password</button>
                            <div class="text-center mt-2 mb-2">
                                Or <a href="{{ route('login') }}" class="font-bd text-primary"> Log In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
</x-layout-front-base>