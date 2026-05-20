<!--begin::Aside-->
<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ url('/') }}">
            {{-- <img alt="Knolzi" src="{{asset('assets/img/edupme-logo.png')}}" class="h-45px logo" /> --}}
            <img alt="Knolzi" src="{{asset('assets/images/logo.png')}}" class="h-45px logo" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <!--begin::Svg Icon | path: icons/duotone/Navigation/Angle-double-left.svg-->
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                        <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.5" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                    </g>
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admindashboard') ? 'active' : '' }}" href="{{ route('admindashboard') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotone/Design/PenAndRuller.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                    <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
<!--                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.permission.*') ? 'active' : '' }}" href="{{ route('admin.permission.index') }}">
                        <span class="menu-icon">
                            <i class="las la-key fs-1"></i>
                        </span>
                        <span class="menu-title">Permissions</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                        <span class="menu-icon">
                            <i class="las la-infinity fs-1"></i>
                        </span>
                        <span class="menu-title">Roles</span>
                    </a>
                </div>-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (  (request()->routeIs('admin.organization.*')) || (request()->routeIs('admin.individual.*')) || (request()->routeIs('admin.reviewer.*')) || (request()->routeIs('admin.institute.*')) || (request()->routeIs('author')) ) ? 'hover show' : '' }}">
                    <span class="menu-link {{ (  (request()->routeIs('admin.organization.*')) || (request()->routeIs('admin.individual.*')) || (request()->routeIs('admin.reviewer.*')) || (request()->routeIs('admin.institute.*')) || (request()->routeIs('author')) ) ? 'active' : '' }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">Users</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.organization.*') ? 'active' : '' }}" href="{{ route('admin.organization.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Organization</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.individual.*') ? 'active' : '' }}" href="{{ route('admin.individual.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Individual</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.reviewer.*') ? 'active' : '' }}" href="{{ route('admin.reviewer.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Reviewer</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.institute.*') ? 'active' : '' }}" href="{{ route('admin.institute.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Institute</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('author') ? 'active' : '' }}" href="{{ route('author') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Author</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.language.*') ? 'active' : '' }}" href="{{ route('admin.language.index') }}">
                        <span class="menu-icon">
                            <i class="las la-language fs-1"></i>
                        </span>
                        <span class="menu-title">Language</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.category.*') ? 'active' : '' }}" href="{{ route('admin.category.index') }}">
                        <span class="menu-icon">
                            <i class="las la-list fs-1"></i>
                        </span>
                        <span class="menu-title">Category</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.course.*') ? 'active' : '' }}" href="{{ route('admin.course.index') }}">
                        <span class="menu-icon">
                            <i class="las la-file-alt fs-1"></i>
                        </span>
                        <span class="menu-title">Course</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.coupon.*') ? 'active' : '' }}" href="{{ route('admin.coupon.index') }}">
                        <span class="menu-icon">
                            <i class="las la-tags fs-1"></i>
                        </span>
                        <span class="menu-title">Coupon</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.currency.*') ? 'active' : '' }}" href="{{ route('admin.currency.index') }}">
                        <span class="menu-icon">
                            <i class="las la-dollar-sign fs-1"></i>
                        </span>
                        <span class="menu-title">Currency</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.question-intent.*') ? 'active' : '' }}" href="{{ route('admin.question-intent.index') }}">
                        <span class="menu-icon">
                            <i class="las la-list-alt fs-1"></i>
                        </span>
                        <span class="menu-title">Question Intent</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.question.*') ? 'active' : '' }}" href="{{ route('admin.question.index') }}">
                        <span class="menu-icon">
                            <i class="las la-question-circle fs-1"></i>
                        </span>
                        <span class="menu-title">Question</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.notification.*') ? 'active' : '' }}" href="{{ route('admin.notification.index') }}">
                        <span class="menu-icon">
                            <i class="las la-bell fs-1"></i>
                        </span>
                        <span class="menu-title">Notification</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}" href="{{ route('admin.feedback.index') }}">
                        <span class="menu-icon">
                            <i class="las la-clipboard-list fs-1"></i>
                        </span>
                        <span class="menu-title">Feedback</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('contact-us') ? 'active' : '' }}" href="{{ route('contact-us') }}">
                        <span class="menu-icon">
                            <i class="las la-headset fs-1"></i>
                        </span>
                        <span class="menu-title">Contact Us</span>
                    </a>
                </div>
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (  (request()->routeIs('home-page-setting')) || (request()->routeIs('teaching-page-setting')) || (request()->routeIs('digital-classroom-page-setting')) || (request()->routeIs('admin.top-features.*')) || (request()->routeIs('admin.help.*')) || (request()->routeIs('contact-us-page-setting')) ) ? 'hover show' : '' }}">
                    <span class="menu-link {{ (  (request()->routeIs('home-page-setting')) || (request()->routeIs('teaching-page-setting')) || (request()->routeIs('digital-classroom-page-setting')) || (request()->routeIs('admin.top-features.*')) || (request()->routeIs('admin.help.*')) || (request()->routeIs('contact-us-page-setting')) ) ? 'active' : '' }}">
                        <span class="menu-icon">
                            <span class="las la-file fs-1"></span>
                        </span>
                        <span class="menu-title">Pages</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('home-page-setting') ? 'active' : '' }}" href="{{ route('home-page-setting') }}">
                                <span class="menu-icon">
                                    <i class="bullet bullet-dot"></i>
                                </span>
                                <span class="menu-title">Homepage Settings</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('teaching-page-setting') ? 'active' : '' }}" href="{{ route('teaching-page-setting') }}">
                                <span class="menu-icon">
                                    <i class="bullet bullet-dot"></i>
                                </span>
                                <span class="menu-title">Start Teaching page Settings</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('digital-classroom-page-setting') ? 'active' : '' }}" href="{{ route('digital-classroom-page-setting') }}">
                                <span class="menu-icon">
                                    <i class="bullet bullet-dot"></i>
                                </span>
                                <span class="menu-title">Digital classroom page Settings</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.top-features.*') ? 'active' : '' }}" href="{{ route('admin.top-features.index') }}">
                                <span class="menu-icon">
                                    <i class="bullet bullet-dot"></i>
                                </span>
                                <span class="menu-title">Top features</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.help.*') ? 'active' : '' }}" href="{{ route('admin.help.index') }}">
                                <span class="menu-icon">
                                    <i class="bullet bullet-dot"></i>
                                </span>
                                <span class="menu-title">Help</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('contact-us-page-setting') ? 'active' : '' }}" href="{{ route('contact-us-page-setting') }}">
                                <span class="menu-icon">
                                    <i class="bullet bullet-dot"></i>
                                </span>
                                <span class="menu-title">Contact-Us page settings</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (  request()->routeIs('request-demo')  ) ? 'hover show' : '' }}">
                    <span class="menu-link {{ (  (request()->routeIs('request-demo')) ) ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="las la-laptop fs-1"></i>
                        </span>
                        <span class="menu-title">Demo Request</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('request-demo') ? 'active' : '' }}" href="{{ route('request-demo') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">View</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('request-demo-export') ? 'active' : '' }}" href="{{ route('request-demo-export') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Export</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (  request()->routeIs('teaching')  ) ? 'hover show' : '' }}">
                    <span class="menu-link {{ (  (request()->routeIs('teaching')) ) ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="las la-chalkboard-teacher fs-1"></i>
                        </span>
                        <span class="menu-title">Teaching</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('teaching') ? 'active' : '' }}" href="{{ route('teaching') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">View</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('teaching-export') ? 'active' : '' }}" href="{{ route('teaching-export') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Export</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.seometa.*') ? 'active' : '' }}" href="{{ route('admin.seometa.index') }}">
                        <span class="menu-icon">
                            <i class="las la-file-code fs-1"></i>
                        </span>
                        <span class="menu-title">SEO meta</span>
                    </a>
                </div>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
</div>
<!--end::Aside-->
