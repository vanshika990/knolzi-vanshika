<x-layout-front-base>
@section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Reset Password </h1>
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
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

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
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="confirm-password" class="form-label">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                                
                                </div>
                            </div>

                            <button type="submit" class="btn btn-orange form-submit">Reset Password</button>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
</x-layout-front-base>