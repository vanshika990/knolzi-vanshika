@php
    $parentCategories = App\Models\Category::with('subcategory')->where('parent_id', 0)->get();
@endphp
<header class="bg-bg-primary sticky top-0 z-50 backdrop-blur-lg border-b border-border">
    <nav class="flex items-center justify-between p-4 lg:px-6 max-w-7xl mx-auto">
        <!-- Logo Section -->
        <a href="{{ route('homepage') }}" class="flex items-center space-x-3 group">
            {{-- <div class="w-10 h-10 lg:w-12 lg:h-12 bg-primary rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <span class="text-2xl lg:text-3xl font-bold text-primary">Knolzi</span> --}}
            <img src="{{ asset('assets/images/logo.png') }}" alt="Knolzi" class="h-10 object-contain">
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
            <div class="relative z-50">
                <!-- Toggle Button -->
                <button id="courseDropdownToggle"
                    class="text-text-primary hover:text-primary font-medium inline-flex items-center focus:outline-none">
                    Course Categories
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div id="courseDropdown"
                    class="absolute hidden left-0 w-64 bg-bg-primary rounded-lg shadow-lg border border-border" style="overflow: visible;">
                    <ul class="py-2">

                        @foreach($parentCategories as $index => $category)
                        <li class="relative group/sub">
                            <a href="{{ route('categorycourses', $category->slug) }}"
                                class="flex justify-between items-center px-4 py-2 text-sm text-text-primary hover:bg-bg-light hover:text-primary">
                                {{ $category->name }}
                                @if(count($category->subcategory))
                                    <svg class="w-4 h-4 ml-2 text-text-light transition-colors group-hover/sub:text-primary"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                @endif
                            </a>

                            @if(count($category->subcategory))
                                <!-- Submenu -->
                                <div id="submenu-{{ $index }}"
                                    class="absolute top-0 left-[100%] w-64 bg-bg-primary border border-border rounded-lg shadow-lg hidden group-hover/sub:block z-50"
                                    style="min-height: 100%;">
                                    <ul class="py-2">
                                        @foreach($category->subcategory as $sub)
                                            <li>
                                                <a href="{{ route('categorycourses', $sub->slug) }}"
                                                    class="block px-4 py-2 text-sm text-secondary hover:bg-bg-light hover:text-primary">
                                                    {{ $sub->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Search Trigger Button (Desktop) -->
            <button class="nav-link text-text-primary hover:text-primary font-medium flex items-center search-form-tigger" type="button" data-toggle="search-form">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                </svg>
                Search
            </button>
            <a href="{{ route("digital-class") }}" class="nav-link text-text-primary hover:text-primary font-medium">Digital Classroom</a>
            <a href="{{ route("start-teaching") }}" class="nav-link text-text-primary hover:text-primary font-medium">Start Teaching</a>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-4">
            <!-- Shopping Cart -->
            <div class="relative hidden md:block">
                <button onclick="window.location.href='{{ route('mycart') }}'" class="p-2 text-text-primary hover:text-primary transition-colors duration-200 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6" />
                    </svg>
                    <span class="cart-badge">{{ count(getCartData()) }}</span>
                </button>
            </div>

            <!-- Auth Buttons/User Dropdown -->
            <div class="hidden md:flex items-center space-x-3">
                @if(Auth::check())
                    <!-- User Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button type="button" class="flex items-center space-x-2 focus:outline-none" onclick="document.getElementById('userDropdown').classList.toggle('hidden')">
                            @if(!empty(Auth::user()->profile_image))
                                <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full border-2 border-white shadow object-cover">
                            @else
                                <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full border-2 border-white shadow object-cover">
                            @endif
                            <span class="font-semibold text-sm px-3 py-2 bg-primary text-text-white rounded-full">{{ Str::limit(Auth::user()->name, 16) }}</span>
                        </button>
                        <div id="userDropdown" class="absolute right-0 mt-2 w-72 bg-bg-primary rounded-lg shadow-lg border border-border py-4 px-0 z-50 hidden">
                            <div class="flex flex-col items-center pb-4 border-b">
                                @if(!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 rounded-full mb-2 object-cover">
                                @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 rounded-full mb-2 object-cover">
                                @endif
                                <div class="font-bold text-lg text-text-primary">{{ Auth::user()->name }}</div>
                                <div class="text-secondary text-sm">{{ Auth::user()->email }}</div>
                            </div>
                            <ul class="py-2">
                                @hasanyrole('organization|institute|author')
                                <li><a href="{{ route('dashboard') }}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">Dashboard</a></li>
                                @endhasanyrole
                                @can('get-my-course')
                                <li><a href="{{ route('getmycourse') }}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">i Learn</a></li>
                                @endcan
                                @can('view-reviewer-course')
                                <li><a href="{{ route('getreviewercourse') }}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">i Learn</a></li>
                                @endcan
                                <li><a href="{{ route('mycart') }}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">Cart</a></li>
                                <li><a href="{{ route('mywishlist') }}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">Wishlist</a></li>
                                <li><a href="{{route('personal-profile')}}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">Profile Settings</a></li>
                                <li><a href="{{route('change-password')}}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">Account Settings</a></li>
                                <li><a href="{{ route('purchase-history') }}" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">Purchase History</a></li>
                                <li>
                                    <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-6 py-2 text-text-primary hover:bg-bg-light hover:text-primary">Log Out</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                                </li>
                            </ul>
                            <div class="border-t pt-2 flex flex-col gap-2">
                                                <a href="{{ route('start-teaching') }}" class="block text-center font-bold py-2 text-text-primary hover:bg-bg-light hover:text-primary">Teach with us</a>
                <a href="{{ route('digital-class') }}" class="block text-center font-bold py-2 text-text-primary hover:bg-bg-light hover:text-primary">Business Solutions</a>
                            </div>
                        </div>
                    </div>
                    <script>
                        // Hide dropdown when clicking outside
                        document.addEventListener('click', function(event) {
                            var dropdown = document.getElementById('userDropdown');
                            var button = dropdown && dropdown.previousElementSibling;
                            if (dropdown && !dropdown.contains(event.target) && (!button || !button.contains(event.target))) {
                                dropdown.classList.add('hidden');
                            }
                        });
                    </script>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary px-4 py-2 rounded-full text-sm font-medium">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary px-6 py-2 rounded-full text-sm font-medium">
                        Sign up
                    </a>
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden p-2 text-text-primary hover:text-primary transition-colors duration-200" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Search Form Section (below header, not overlay) -->
    <div class="search-form-wrapper w-full flex justify-center bg-transparent z-40 mt-6 mb-4 hidden">
        <form class="search-form w-full max-w-3xl mx-auto relative" id="searchCourse" action="{{ route('search') }}">
            <div class="w-full shadow-lg rounded-xl bg-bg-primary flex items-center px-4 py-2 border border-border">
                <span class="input-group-addon flex items-center mr-3">
                    <svg class="w-6 h-6 text-text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </span>
                <input type="text" name="q" id="autoSearch" aria-label="Search" class="search form-control flex-1 border-none outline-none text-text-primary placeholder-text-light bg-bg-secondary px-4 py-3 text-lg rounded-xl focus:ring-2 focus:ring-primary transition-all duration-200" placeholder="Search by Category, Course Creators, Subjects, Streams etc." autocomplete="off">
                <button type="button" class="search-close input-group-addon flex items-center ml-3 text-primary hover:text-primary-dark text-2xl bg-transparent border-none transition-all duration-150 hover:bg-bg-light rounded-full p-1" data-toggle="search-form-close" aria-label="Close search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                    </svg>
                </button>
            </div>
            <div class="search-show-content" id="autoList"></div>
        </form>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="bg-bg-primary hidden lg:hidden absolute top-full left-0 right-0 border-t border-border shadow-lg">
        <div class="p-4 space-y-2">
            <a href="#" class="mobile-menu-item block py-3 px-4 text-text-primary hover:text-primary rounded-lg">Course Categories</a>
            <a href="#" class="mobile-menu-item block py-3 px-4 text-text-primary hover:text-primary rounded-lg">Search</a>
            <a href="{{ route("digital-class") }}" class="mobile-menu-item block py-3 px-4 text-text-primary hover:text-primary rounded-lg">Digital Classroom</a>
            <a href="{{ route("start-teaching") }}" class="mobile-menu-item block py-3 px-4 text-text-primary hover:text-primary rounded-lg">Start Teaching</a>

            <!-- Mobile Cart -->
            <div class="mobile-menu-item py-3 px-4 text-text-primary hover:text-primary rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9H19M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6" />
                </svg>
                Cart ({{ count(getCartData()) }})
            </div>

            <!-- Mobile Auth Buttons -->
            <div class="pt-4 space-y-2">
                <a href="{{ route('login') }}" class="btn-secondary block text-center w-full py-3 rounded-lg text-sm font-medium">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="btn-primary block w-full text-center py-3 rounded-lg text-sm font-medium">
                    Sign up
                </a>
            </div>
        </div>
    </div>
</header>
