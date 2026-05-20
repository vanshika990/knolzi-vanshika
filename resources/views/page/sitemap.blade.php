<x-layout-front-base>
    @section('meta_title', 'Sitemap')
    @section('meta_description', 'We welcome feedback from the teaching community.')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')

    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Sitemap </h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <section class="sitemap-page mt-5 mb-5">
        <div class="container">
            <div class="row">
                <h3>Pages</h3>
            </div>
            <div class="row mb-3">
                <div class="col-lg-4 col-md-4">
                    <ul>
                        <li><a href="javascript:void(0)">Get the app</a></li>
                        <li><a href="javascript:void(0)">About us</a></li>
                        <li><a href="{{ route('contactus') }}">Contact us</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <ul>
                        <li><a href="https://blog.edupme.com/">Blog</a></li>
                        <li><a href="{{ route('contactus') }}">Help & Support</a></li>
                        <li><a href="{{ route('digital-class') }}">Digital Classroom</a></li>
                        <li><a href="{{ route('start-teaching') }}">Start Teaching</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <ul>
                        <li><a href="{{ route('terms') }}">Terms</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('disclaimer') }}">Disclaimer</a></li>
                    </ul>
                </div>
            </div>

            @if(!$categorys->isEmpty())
            <div class="row">
                <h3>Category</h3>
            </div>
            <div class="row mb-3">
                @foreach($categorys as $category)
                <div class="col-lg-4 col-md-4">
                    <ul>
                        <li><a href="{{ route('categorycourses',$category->slug) }}">{{$category['name']}}</a></li>
                    </ul>
                </div>
                @endforeach
            </div>
            @endif

            @if(!$courses->isEmpty())
            <div class="row">
                <h3>Courses</h3>
            </div>
            <div class="row mb-3">
                @foreach($courses as $course)
                <div class="col-lg-4 col-md-4">
                    <ul>
                        <li><a href="{{ route('coursedetails',$course->slug) }}">{{$course['course_name']}}</a></li>
                    </ul>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </section>
    @stop
</x-layout-front-base>
