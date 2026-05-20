<x-layout-front-base>
    @section('meta_title', '404 Not Found - Knolzi')
    @section('meta_description', '404 Not Found - Knolzi')
    @section('meta_keywords', '404 Not Found - Knolzi')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <div class="container">
        <div class="row align-items-center justify-content-center text-center" style="height:100vh;">
            <div class="col-lg-8">
                <div class="pagenotfound">
                    <img src="{{ asset("assets/front/images/404.png") }}" class="img-fluid" />
                    <div class="pagenotfound-content">
                        <h1>404</h1>
                        <h2>Page Not Found</h2>
                        <a href="{{ url("/") }}" class="btn btn-warning">GO TO HOME</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stop
</x-layout-front-base>
