@extends('frontend.layouts.app')
@if(!empty($seometa))
@section('meta_title', $seometa['title'])
@section('meta_description', $seometa['description'])
@section('meta_keywords', $seometa['keyword'])
@section('meta_image',asset('assets/front/images/logo.png'))
@endif

@section('content')
<!-- Disclaimer Hero Section -->
<section class="relative z-10 max-w-4xl mx-auto px-4 md:px-8 py-16">
    <div class="text-center mb-10">
        <div class="glass-effect rounded-3xl p-8 md:p-12 mb-6 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight gradient-text">Disclaimer</h1>
        </div>
    </div>
    <div class="glass-effect-subtle rounded-3xl p-6 md:p-10 shadow-xl animate-fade-in">
        <div class="static-content prose prose-invert max-w-none text-gray-500">
            <p>All the information and content available in this website and application/platform is for the exclusive purpose of general information. The said information and content is provided by APTRaise Technologies Private Limited and while the endeavour of the company is to keep the said information and content up to date and correct, the company makes no representations or warranties of any kind, whether express or implied, about the completeness, accurateness, trustworthiness, appropriateness, or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own peril.</p>
            <p>In no event will the company be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>
            <p>Through this website you are able to link to other websites which are not under the control of APTRaise Technologies Private Limited. By the virtue of having no control over the nature, content and availability of those websites, the company does not make any representations or warranties of any kind, whether express or implied, about the completeness, accurateness, trustworthiness, appropriateness, or availability with respect to the website or the information, products, services, or related graphics contained on those websites for any purpose. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>
            <p>Every endeavour of APTRaise Technologies Private Limited is made to keep the website up and running smoothly. however, the company takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond the control of the company.</p>
            <p>If any product/Services or any IT Platform is developed by the attendee/student/corporate by gaining knowledge by using the said platform then, For that product it shall be the sole and exclusive responsibility and liability of the particular user/attendee/corporate/students and in no way APTRaise Technologies Private Limited will be liable or responsible.</p>
            <div class="mt-8">
                <strong>Technologies Private Limited</strong> is located at – <br>
                <strong>Address:</strong> 75, PRATHAM VATIKA, OPP.AMANTRAN BUNGLOWS, <br>
                BOPAL AHMEDABAD, Ahmedabad, Gujarat, 380058 <br>
                <strong>Email:</strong> info@aptraise.com <br>
                <strong>Website:</strong> www.aptraise.com <br>
            </div>
        </div>
    </div>
</section>
@endsection
