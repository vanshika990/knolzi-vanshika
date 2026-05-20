<x-layout-front-base>
    @section('meta_title', 'A State of Art Online Interactive Delivery Framework')
    @section('meta_description', 'We enables knowledge delivery at the right time to right person at the right level, with the power of Artificial Intelligence and Information Technology')
    @section('meta_keyword', 'about us knolzi')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- hero image start -->
    <section class="hero-dc hero-st">
        <div class="hero-area">
            <img src="{{ asset('assets/images/about-hero-image.jpg') }}" class="img-fluid" alt="knolzi" />
            <div class="hero-img-content">
                <div class="hero-title">
                    <h1>Unique Platform For Education, Training & Onboarding</h1>
                    <p>A combined effective knowledge delivery solved through artificial intelligence based methodology.</p>
                </div>
                <div class="download-app text-left">
                    <h5 class="text-white">Available Now</h5>
                    <ul>
                        <li><a href="https://play.google.com/store/apps/details?id=com.edupme" class="dwn-ply-str"><img src="{{ asset('assets/front/images/dwn-google-str.png') }}" class="img-fluid" alt="play-store"></a></li>
                        <li><a href="javascript:void(0)" class="app-str"><img src="{{ asset('assets/front/images/dwn-app-str.png') }}" class="img-fluid" alt="app-store"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="about-ai-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <p>
                        <img src="{{ asset('assets/images/online-education@72x-8.png') }}" class="img-fluid" style="margin:0;margin-bottom:5px;margin-left:40px;float:right;" alt="Knolzi online education">
                    <h1>First Ever AI-Powered Interactive Digital Learning Framework!</h1>
                    <span>With the power of AI, knolzi aligns knowledge delivery to each user with the proper needs of organization & Institute which creates 100% better results.</span>
                    <span>knolzi expedites On-Boarding trainings and Hands-on learning while aligning the needs of the organization & institute.</span>
                    </p>
                </div>
                <div class="col-lg-12 mb-5">
                    <p><img src="{{ asset('assets/images/edupme-vision@72x-8.png') }}" class="img-fluid" style="margin:0;margin-bottom:5px;margin-right:40px;float:left;" alt="online-education">
                    <h1>VISION</h1>
                    <h2>"Knowledge Delivery at the Right Time to the Right Person at the Right Level."</h2>
                    <span>We believe to connect individual user learning with the best in class knowledge delivery and prepare them for a better tomorrow.</span>
                    <h3>knolzi is a one-stop solution for knowledge Gainers and Givers...</h3>
                    </p>
                </div>
                <div class="col-lg-12 mb-5">
                    <h1 class="mb-4">Our Courses</h1>
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-engineering@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">Engineering</div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-robotics@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">Robotics</div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-logistics@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">Logistics</div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-taxation@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">Taxation</div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-maths@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">Maths</div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-iner-busi@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">International Business</div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-insurance@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">Insurance</div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6 text-center p-3">
                            <img src="{{ asset('assets/images/ed-sales@72x-8.png') }}" class="img-fluid">
                            <div class="ourcourse-title">Sales</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <h4><strong>knolzi is the product of APTRaise Technologies Pvt Ltd.</strong></h4>
                    <span>APTRaise Technologies is a team of Industry Veterans & "Subject Matter" experts who have in their past assignments trained a huge number of engineers, developed new-age technologies, delivered highly automated solutions, led cutting edge research and led teams to transform organizations. The motto of APTRaise derives from the name itself: Raise yourself, Aptly...</span>
                    <span>For further information, please visit <a href="https://www.aptraise.com" target="_blank">www.aptraise.com</a></span>
                </div>
            </div>
        </div>
    </section>
    @stop
</x-layout-front-base>
