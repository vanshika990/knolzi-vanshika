<footer class="relative bg-bg-secondary text-text-primary overflow-hidden border-t border-border">
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Logo and Brand Section -->
            <div class="lg:col-span-1 footer-section">
                <div class="flex items-center space-x-3 mb-6">
                    {{-- <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <span class="text-2xl lg:text-3xl font-bold text-primary">Knolzi</span> --}}
                    <a href="{{ route('homepage') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Knolzi" class="h-10 object-contain">
                    </a>
                </div>
                <p class="text-secondary text-sm leading-relaxed mb-6">
                    Empowering education through innovative digital classroom solutions and interactive learning experiences.
                </p>
                <div class="flex space-x-4">
                    <div class="w-3 h-3 bg-primary-light rounded-full animate-pulse"></div>
                    <div class="w-3 h-3 bg-primary rounded-full animate-pulse" style="animation-delay: 0.5s"></div>
                    <div class="w-3 h-3 bg-primary-dark rounded-full animate-pulse" style="animation-delay: 1s"></div>
                </div>
            </div>

            <!-- Navigation Links Column 1 -->
            <div class="lg:col-span-1 footer-section" style="animation-delay: 0.2s">
                <h3 class="text-lg font-semibold section-heading text-text-primary">Platform</h3>
                <div class="space-y-4">
                    <a href="{{ route('digital-class') }}" class="footer-link block text-secondary">Digital Classroom</a>
                    <a href="{{ route('start-teaching') }}" class="footer-link block text-secondary">Start Teaching</a>
                    <a href="{{ route('aboutus') }}" class="footer-link block text-secondary">About us</a>
                    <a href="{{ route('contactus') }}" class="footer-link block text-secondary">Contact us</a>
                </div>
            </div>

            <!-- Navigation Links Column 2 -->
            <div class="lg:col-span-1 footer-section" style="animation-delay: 0.4s">
                <h3 class="text-lg font-semibold section-heading text-text-primary">Legal</h3>
                <div class="space-y-4">
                    <a href="{{ route('terms') }}" class="footer-link block text-secondary">Terms</a>
                    <a href="{{ route('privacy') }}" class="footer-link block text-secondary">Privacy Policy</a>
                    <a href="{{ route('disclaimer') }}" class="footer-link block text-secondary">Disclaimer</a>
                    <a href="{{ route('sitemap') }}" class="footer-link block text-secondary">Sitemap</a>
                </div>
            </div>

            <!-- Subscribe Section -->
            <div class="lg:col-span-1 footer-section" style="animation-delay: 0.6s">
                <h3 class="text-lg font-semibold section-heading text-text-primary">Stay Connected</h3>

                <!-- Newsletter Signup -->
                <div class="mb-6">
                    <form class="form-group txt-fltr search-blk" role="search" method="post" id="subscribeform" name="subscribeform" action="{{ route('subscriber') }}">
                        <p class="text-secondary text-sm mb-4">Get the latest updates and educational resources</p>
                        <div class="flex">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Enter your email id"
                                class="email-input flex-1 px-4 py-3 rounded-l-lg text-text-primary placeholder-text-light focus:outline-none focus:ring-2 focus:ring-primary form-control subscribe"
                                autocomplete="off"
                            >
                            <button type="button" id="submit-btn" class="subscribe-button px-6 py-3 rounded-r-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </div>
                        <div class="errors mb-1 text-left" style="color: #ff2d20; font-weight: 600;"></div>
                    </form>
                </div>

                <!-- Social Media Icons -->
                @php
                $social_media = getSocialMediaLink();
                @endphp
                <div class="mb-8">
                    <h4 class="text-sm font-semibold text-secondary mb-4">Follow Us</h4>
                    <div class="flex space-x-3">
                        <a href="{{ $social_media['twitter_url'] }}" class="social-icon w-11 h-11 glass-effect rounded-full flex items-center justify-center" target="_blank">
                            <!-- Twitter SVG -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.954 4.569c-0.885 0.389-1.83 0.654-2.825 0.775 1.014-0.611 1.794-1.574 2.163-2.724-0.951 0.564-2.005 0.974-3.127 1.195-0.897-0.957-2.178-1.555-3.594-1.555-2.719 0-4.924 2.206-4.924 4.924 0 0.386 0.044 0.762 0.128 1.124-4.09-0.205-7.719-2.165-10.148-5.144-0.424 0.729-0.666 1.577-0.666 2.476 0 1.708 0.87 3.216 2.188 4.099-0.807-0.026-1.566-0.247-2.228-0.616v0.062c0 2.385 1.693 4.374 3.946 4.827-0.413 0.112-0.849 0.172-1.298 0.172-0.317 0-0.626-0.03-0.928-0.086 0.627 1.956 2.444 3.377 4.6 3.417-1.68 1.318-3.809 2.105-6.102 2.105-0.396 0-0.787-0.023-1.175-0.069 2.179 1.397 4.768 2.213 7.557 2.213 9.054 0 14.002-7.496 14.002-13.986 0-0.213-0.005-0.425-0.014-0.636 0.962-0.693 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="{{ $social_media['facebook_url'] }}" class="social-icon w-11 h-11 glass-effect rounded-full flex items-center justify-center" target="_blank">
                            <!-- Facebook SVG -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-0.733 0-1.325 0.592-1.325 1.326v21.348c0 0.733 0.592 1.326 1.325 1.326h11.495v-9.294h-3.128v-3.622h3.128v-2.672c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463 0.099 2.797 0.143v3.24l-1.918 0.001c-1.504 0-1.797 0.715-1.797 1.763v2.313h3.587l-0.467 3.622h-3.12v9.293h6.116c0.733 0 1.325-0.593 1.325-1.326v-21.349c0-0.734-0.592-1.326-1.325-1.326z"/>
                            </svg>
                        </a>
                        <a href="{{ $social_media['instagram_url'] }}" class="social-icon w-11 h-11 glass-effect rounded-full flex items-center justify-center" target="_blank">
                            <!-- Instagram SVG -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584 0.012 4.85 0.07 1.366 0.062 2.633 0.334 3.608 1.308 0.974 0.974 1.246 2.241 1.308 3.608 0.058 1.266 0.069 1.646 0.069 4.85s-0.012 3.584-0.069 4.85c-0.062 1.366-0.334 2.633-1.308 3.608-0.974 0.974-2.241 1.246-3.608 1.308-1.266 0.058-1.646 0.069-4.85 0.069s-3.584-0.012-4.85-0.069c-1.366-0.062-2.633-0.334-3.608-1.308-0.974-0.974-1.246-2.241-1.308-3.608-0.058-1.266-0.069-1.646-0.069-4.85s0.012-3.584 0.069-4.85c0.062-1.366 0.334-2.633 1.308-3.608 0.974-0.974 2.241-1.246 3.608-1.308 1.266-0.058 1.646-0.069 4.85-0.069zm0-2.163c-3.259 0-3.667 0.012-4.947 0.071-1.276 0.059-2.556 0.337-3.637 1.418-1.081 1.081-1.359 2.361-1.418 3.637-0.059 1.28-0.071 1.688-0.071 4.947s0.012 3.667 0.071 4.947c0.059 1.276 0.337 2.556 1.418 3.637 1.081 1.081 2.361 1.359 3.637 1.418 1.28 0.059 1.688 0.071 4.947 0.071s3.667-0.012 4.947-0.071c1.276-0.059 2.556-0.337 3.637-1.418 1.081-1.081 1.359-2.361 1.418-3.637 0.059-1.28 0.071-1.688 0.071-4.947s-0.012-3.667-0.071-4.947c-0.059-1.276-0.337-2.556-1.418-3.637-1.081-1.081-2.361-1.359-3.637-1.418-1.28-0.059-1.688-0.071-4.947-0.071zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a3.999 3.999 0 1 1 0-7.998 3.999 3.999 0 0 1 0 7.998zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                            </svg>
                        </a>
                        <a href="{{ $social_media['linkedin_url'] }}" class="social-icon w-11 h-11 glass-effect rounded-full flex items-center justify-center" target="_blank">
                            <!-- LinkedIn SVG -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="{{ $social_media['youtube_url'] }}" class="social-icon w-11 h-11 glass-effect rounded-full flex items-center justify-center" target="_blank">
                            <!-- YouTube SVG -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- App Download Section -->
                {{-- <div>
                    <h4 class="text-sm font-semibold text-slate-300 mb-4">Available Now</h4>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="#" class="app-badge inline-block">
                            <div class="glass-effect rounded-lg px-4 py-2 flex items-center space-x-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3.609 1.814L13.792 12 3.61 22.186c-.38.38-.38 1.006 0 1.392.38.38 1.006.38 1.392 0l10.548-10.548c.38-.38.38-1.006 0-1.392L5.002.422c-.38-.38-1.006-.38-1.392 0-.38.38-.38 1.006 0 1.392z"/>
                                </svg>
                                <span class="text-sm font-medium">Google Play</span>
                            </div>
                        </a>
                        <a href="#" class="app-badge inline-block">
                            <div class="glass-effect rounded-lg px-4 py-2 flex items-center space-x-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                                </svg>
                                <span class="text-sm font-medium">App Store</span>
                            </div>
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- Enhanced Copyright Section -->
        <div class="border-t border-border mt-12 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-center md:text-left">
                    <p class="text-secondary text-sm">
                        &copy; {{ date('Y') }} Knolzi. All Rights Reserved
                    </p>
                    {{-- <p class="text-slate-400 text-xs mt-1">
                        Designed with ❤️ for better education
                    </p> --}}
                </div>
                <div class="flex items-center space-x-6 text-text-light text-xs">
                    <span>🌟 Trusted by 10,000+ educators</span>
                    <span>📚 100+ courses available</span>
                    <span>🎓 Making learning fun</span>
                </div>
            </div>
        </div>
    </div>
</footer>

