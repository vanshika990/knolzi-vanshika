@extends('frontend.layouts.app')
@section('meta_title', 'About Us')
@section('content')

<!-- Hero Section -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="text-left">
            <div class="glass-effect rounded-3xl p-8 mb-8 animate-fade-in">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Unique Platform For <span class="text-primary">Education</span>,
                    <span class="text-primary">Training</span> &
                    <span class="text-primary">Onboarding</span>
                </h1>

                <p class="text-xl text-secondary mb-8 leading-relaxed">
                    A combined effective knowledge delivery solved through artificial intelligence based methodology.
                </p>

                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-text-primary">Available Now</h4>
                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        <!-- Google Play Badge -->
                        <a href="https://play.google.com/store" target="_blank" rel="noopener noreferrer">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                alt="Get it on Google Play" class="h-12">
                        </a>

                        <!-- App Store Badge -->
                        <a href="https://www.apple.com/app-store/" target="_blank" rel="noopener noreferrer">
                            <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                                alt="Download on the App Store" class="h-12">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative animate-scale-in">
            <div class="glass-effect-subtle rounded-2xl p-8">
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-lg p-6 border border-white/10">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-400 rounded-lg flex items-center justify-center mb-4">
                            📱
                        </div>
                        <h3 class="font-semibold mb-2">Mobile Learning</h3>
                        <p class="text-sm text-secondary">Learn on the go with our mobile app</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500/20 to-blue-500/20 rounded-lg p-6 border border-white/10">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-400 rounded-lg flex items-center justify-center mb-4">
                            🤖
                        </div>
                        <h3 class="font-semibold mb-2">AI-Powered</h3>
                        <p class="text-sm text-secondary">Intelligent learning pathways</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-lg p-6 border border-white/10">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-lg flex items-center justify-center mb-4">
                            🎯
                        </div>
                        <h3 class="font-semibold mb-2">Personalized</h3>
                        <p class="text-sm text-secondary">Tailored content for every learner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Framework Section -->
<section id="about" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="animate-fade-in">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                First Ever <span class="text-primary">AI-Powered</span>
                <br>
                Interactive Digital Learning Framework!
            </h2>
            <p class="text-xl text-secondary mb-6 leading-relaxed">
                With the power of AI, knolzi aligns knowledge delivery to each user with the proper needs of organization & Institute which creates 100% better results.
            </p>
            <p class="text-lg text-text-light mb-8">
                knolzi expedites On-Boarding trainings and Hands-on learning while aligning the needs of the organization & institute.
            </p>
            <button class="btn-primary px-8 py-4 rounded-full text-lg font-semibold">
                Learn More
            </button>
        </div>

        <div class="relative animate-scale-in">
            <div class="glass-effect rounded-2xl p-8">
                <div class="bg-gradient-to-br from-blue-500/30 to-purple-500/30 rounded-xl h-64 flex items-center justify-center">
                    <svg class="w-24 h-24 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision Section -->
<section id="vision" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="glass-effect-subtle rounded-3xl p-12 text-center animate-fade-in">
        <h2 class="text-4xl md:text-6xl font-bold mb-8">
            <span class="gradient-text">VISION</span>
        </h2>
        <blockquote class="text-2xl md:text-3xl font-semibold mb-8 italic text-secondary">
            "Knowledge Delivery at the Right Time to the Right Person at the Right Level."
        </blockquote>
        <p class="text-xl text-secondary mb-6 max-w-4xl mx-auto leading-relaxed">
            We believe to connect individual user learning with the best in class knowledge delivery and prepare them for a better tomorrow.
        </p>
        <p class="text-lg font-semibold gradient-text-warm">
            knolzi is a one-stop solution for knowledge Gainers and Givers...
        </p>
    </div>
</section>

<!-- Courses Section -->
<section id="courses" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold mb-4">
            Our <span class="gradient-text">Courses</span>
        </h2>
        <p class="text-xl text-secondary max-w-2xl mx-auto">
            Comprehensive learning paths designed for various industries and skill levels
        </p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                ⚙️
            </div>
            <h3 class="font-semibold text-lg mb-2">Engineering</h3>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                🤖
            </div>
            <h3 class="font-semibold text-lg mb-2">Robotics</h3>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                🚚
            </div>
            <h3 class="font-semibold text-lg mb-2">Logistics</h3>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                💰
            </div>
            <h3 class="font-semibold text-lg mb-2">Taxation</h3>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                📊
            </div>
            <h3 class="font-semibold text-lg mb-2">Mathematics</h3>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                🌍
            </div>
            <h3 class="font-semibold text-lg mb-2">International Business</h3>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                🛡️
            </div>
            <h3 class="font-semibold text-lg mb-2">Insurance</h3>
        </div>
        <div class="glass-effect-subtle rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 group hover-scale text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">
                📈
            </div>
            <h3 class="font-semibold text-lg mb-2">Sales</h3>
        </div>
    </div>
</section>

<!-- Company Info Section -->
<section id="company" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="glass-effect rounded-3xl p-12 text-center animate-fade-in">
        <h3 class="text-3xl md:text-4xl font-bold mb-8">
            <span class="gradient-text-warm">knolzi</span> is the product of
            <span class="gradient-text">APTRaise Technologies Pvt Ltd.</span>
        </h3>
        <p class="text-xl text-secondary max-w-4xl mx-auto leading-relaxed">
            APTRaise Technologies is a team of Industry Veterans & "Subject Matter Experts" who have more than 15+ years of experience in the field of Learning & Development, Training, Organizational Development & Human Resource practices. We deliver Effective & Efficient Solutions in the field of Learning Technologies, Content Curation, Training & Assessment Delivery.
        </p>
    </div>
</section>
@endsection
