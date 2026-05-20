<x-layout-front-base>
    @section('meta_title', 'Thank you')
    @section('meta_description', 'Thank you for Payment Complete - Knolzi')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <div class="container">
        <div class="row align-items-center justify-content-center text-center" style="height:70vh;">
            <div class="col-lg-8">
                <div class="thankyou-sec">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 20 20"><defs><style>.a{fill:#0e840a;}</style></defs><path class="a" d="M7.707.293a1,1,0,0,1,0,1.414l-4,4a1,1,0,0,1-1.414,0l-2-2A1,1,0,1,1,1.707,2.293L3,3.586,6.293.293a1,1,0,0,1,1.414,0Z" transform="translate(6 7)"/><path class="a" d="M10,20A10,10,0,1,1,20,10,10,10,0,0,1,10,20ZM10,2a8,8,0,1,0,8,8,8,8,0,0,0-8-8Z"/></svg>
                    <h1>Error!</h1>
                    <p>{{ $message }} </p>
                    <a href="{{ route('getmycourse') }}" class="btn btn-warning"><i class="bi bi-arrow-left-short"></i> Go to i Learn</a>
                </div>

            </div>

        </div>
        <div class="row align-items-center justify-content-center text-center pb-5">
            <div class="col-lg-8">
                If you have any issues <a href="{{ route('getmycourse') }}">contact us</a>
            </div>
        </div>
    </div>
    @stop
</x-layout-front-base>
