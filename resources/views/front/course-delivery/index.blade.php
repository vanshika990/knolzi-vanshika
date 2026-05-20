<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Course Delivery | {{ config('app.name', 'Knolzi') }}</title>

    <link rel="canonical" href="{{ url()->current() }}" />
    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" />

    <link href="{{ asset('assets/css/sweetalert.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/front/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/owl.theme.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/fontawesome.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front/css/star-rating-svg.css') }}" />

    <style>
        body {
            background: #FFFFFF;
            color: #1F1F1F;
        }
        .delivery-main-content .left-side-del-panel .media-delivery-content {
            height: 80vh;
            background: #FFFFFF;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(12px);
            color: #1F1F1F;
            border: 1px solid #E0E0E0;
        }
        .left-side-del-panel .question-answer {
            padding-block: 30px;
            height: 75vh;
            margin-bottom: 60px;
            color: #666666;
        }
        .delivery-main-content .left-side-del-panel .question-answer .question-media img {
            vertical-align: middle;
            text-align: center;
            margin: auto;
            display: block;
            margin-bottom: 10px;
            border-radius: 1rem;
            box-shadow: 0 2px 16px 0 var(--color-shadow);
        }
        .delivery-main-content .left-side-del-panel .question-answer .question-media p,
        .delivery-main-content .left-side-del-panel .question-answer .question-media li,
        .delivery-main-content .left-side-del-panel .question-answer .question-media span {
            font-size: 20px !important;
            color: #1F1F1F !important;
            line-height: 1.5;
        }
        .left-side-del-panel .question-answer .form-check label.form-check-label {
            font-size: 18px;
            margin: 0 !important;
            padding: 0 !important;
            font-weight: bold;
            color: #2196F3;
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            line-height: 1.4;
        }
        .delivery-main-content .left-side-del-panel .question-mcq,
        .delivery-main-content .left-side-del-panel .question-mcq p,
        .delivery-main-content .left-side-del-panel .question-mcq span {
            font-size: 30px !important;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .right-side-del-pnael {
            background: #F8F9FA;
            color: #1F1F1F;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid #E0E0E0;
        }
        .total-progress, .chapter-progress {
            margin: 15px 0px;
        }
        .total-progress .progress, .chapter-progress .progress {
            border-radius: 50px;
            background: #F5F5F5;
            border: 2px solid #E0E0E0;
        }
        .total-progress .progress-bar, .chapter-progress .progress-bar {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-radius: 50px;
        }
        .qaandcomment-sec .tab-content {
            background: #FFFFFF;
            color: #666666;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }
        .qaandcomment-sec .nav-link {
            font-size: 24px;
            font-weight: 800;
            font-family: 'Exo 2';
            color: #2196F3;
            border: none;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            background: transparent;
        }
        .qaandcomment-sec .nav-link.active {
            color: #2196F3;
            background: #F5F5F5;
        }
        .add-new-q-a .btn-success {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #FFFFFF;
            border: none;
        }
        .add-new-q-a .btn-success:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            color: #FFFFFF;
        }
        .bottom-hint-panel {
            background: #2196F3;
            padding: 10px;
            border-radius: 1rem;
            color: #FFFFFF;
        }
        .hint-area .hint-title h3 {
            color: #FFFFFF;
            border-bottom: 2px solid #FFFFFF;
        }
        .hint-area .hint-title {
            color: #FFFFFF;
        }
        .hint-area .hint-icons ul li span {
            color: #FFFFFF;
        }
        .hint-area .hint-icons ul li .active span:before {
            color: #FFFFFF;
        }
        /* Fix icon visibility in bottom-hint-panel */
        .bottom-hint-panel .hint-icons ul li span.icon-h-video:before,
        .bottom-hint-panel .hint-icons ul li span.icon-h-audio:before,
        .bottom-hint-panel .hint-icons ul li span.icon-h-document:before,
        .bottom-hint-panel .hint-icons ul li span.icon-h-image:before,
        .bottom-hint-panel .hint-icons ul li span.icon-h-link:before {
            color: #FFFFFF !important;
            opacity: 0.8;
        }
        .bottom-hint-panel .hint-icons ul li a.active span.icon-h-video:before,
        .bottom-hint-panel .hint-icons ul li a.active span.icon-h-audio:before,
        .bottom-hint-panel .hint-icons ul li a.active span.icon-h-document:before,
        .bottom-hint-panel .hint-icons ul li a.active span.icon-h-image:before,
        .bottom-hint-panel .hint-icons ul li a.active span.icon-h-link:before {
            color: #FFD700 !important;
            opacity: 1;
        }
        .bottom-hint-panel .hint-icons ul li a:hover span.icon-h-video:before,
        .bottom-hint-panel .hint-icons ul li a:hover span.icon-h-audio:before,
        .bottom-hint-panel .hint-icons ul li a:hover span.icon-h-document:before,
        .bottom-hint-panel .hint-icons ul li a:hover span.icon-h-image:before,
        .bottom-hint-panel .hint-icons ul li a:hover span.icon-h-link:before {
            color: #FFD700 !important;
            opacity: 1;
        }
        /* Ensure hint and help title icons are visible */
        .bottom-hint-panel .hint-title span.icon-hint-yellow:before,
        .bottom-hint-panel .hint-title span.icon-help-yellow:before {
            color: #FFD700 !important;
        }
        .bottom-hint-panel .hint-title i.icon-right-arrow:before {
            color: #FFFFFF !important;
        }
        .modal-content {
            background: #FFFFFF;
            color: #1F1F1F;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(16px);
            border: 1px solid #E0E0E0;
        }
        .modal-header, .modal-body {
            border: none;
        }
        .form-control, textarea.form-control {
            background: #FFFFFF;
            color: #1F1F1F;
            border: 1px solid #E0E0E0;
            border-radius: 0.75rem;
        }
        .form-control:focus, textarea.form-control:focus {
            background: #FFFFFF;
            color: #1F1F1F;
            border-color: #2196F3;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.2);
        }
        .submit-next-button .btn-warning {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: #FFFFFF;
            border: none;
        }
        .submit-next-button .btn-warning:disabled {
            background: #E3F2FD;
            color: #90CAF9;
            border: 1px solid #BBDEFB;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .submit-next-button .btn-warning:hover:not(:disabled) {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            color: #FFFFFF;
        }
        .submit-next-button .btn-warning:hover:disabled {
            background: #E3F2FD;
            color: #90CAF9;
            border: 1px solid #BBDEFB;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .nav-link.search-form-tigger, .nav-link.me-2 {
            color: #2196F3;
        }
        .del-breadcrumb {
            background: #F8F9FA;
            color: #1F1F1F;
            border-radius: 0.75rem;
            border: 1px solid #E0E0E0;
        }
        .del-breadcrumb .breadcrumb .breadcrumb-item, .del-breadcrumb .breadcrumb .breadcrumb-item a {
            color: #2196F3;
        }
        .accordion-item, .accordion-button {
            background: transparent;
            color: #1F1F1F;
        }
        .accordion-button.collapsed {
            color: #2196F3;
        }
        .accordion-body {
            color: #1F1F1F;
        }
        .comments ul li {
            color: #666666;
        }
        .your-progress-dropdown {
            color: #666666;
        }
        .comments p {
            color: #FFFFFF;
        }
        /* Custom scrollbar for dark background */
        ::-webkit-scrollbar {
            width: 8px;
            background: #FFFFFF;
        }
        ::-webkit-scrollbar-thumb {
            background: #E0E0E0;
            border-radius: 4px;
        }
        ::selection {
            background: #2196F3;
            color: #FFFFFF;
        }

        /* Additional fixes for better visibility */
        .delivery-main-content {
            background: #FFFFFF;
            min-height: 100vh;
        }

        .left-side-del-panel {
            background: #FFFFFF;
        }

        .question-answer {
            padding: 20px;
        }

        .question-mcq {
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-check {
            margin-bottom: 15px;
            padding-left: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check-input {
            margin-top: 0;
            margin-right: 0;
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        .submit-next-button {
            padding: 20px;
            text-align: center;
        }

        .submit-next-button .btn {
            margin: 0 10px;
            padding: 10px 30px;
            font-weight: 600;
        }

        .right-side-del-pnael {
            padding: 20px;
        }

        .total-progress, .chapter-progress {
            margin-bottom: 20px;
        }

        .total-progress span, .chapter-progress span {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1F1F1F;
        }

        .qaandcomment-sec {
            margin-top: 20px;
        }

        .tab-content {
            padding: 20px;
            min-height: 200px;
        }

        /* Ensure content visibility */
        .question-answer {
            overflow-y: auto;
            max-height: 60vh;
        }

        .question-media {
            margin: 20px 0;
        }

        .question-media img {
            max-width: 100%;
            height: auto;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border: none;
            color: #FFFFFF;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            color: #FFFFFF;
        }

        /* Fix for radio buttons */
        .form-check-input[type="radio"] {
            margin-right: 0;
            accent-color: #2196F3;
        }

        .form-check-label {
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            flex: 1;
        }

        /* Progress display */
        .progress {
            background-color: #F5F5F5;
            border-radius: 50px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            transition: width 0.3s ease;
        }
        /* --- HEADER/NAVBAR IMPROVEMENTS --- */
        .edupme-nav {
            background: #F8F9FA;
            box-shadow: 0 2px 16px 0 rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #E0E0E0;
            min-height: 80px;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .edupme-nav .navbar-brand img {
            filter: drop-shadow(0 2px 8px rgba(96,165,250,0.10));
        }
        .edupme-nav .navbar-nav .nav-link,
        .edupme-nav .navbar-nav .nav-link i {
            color: #1F1F1F !important;
            font-weight: 600;
            font-size: 18px;
            transition: color 0.2s;
        }
        .edupme-nav .navbar-nav .nav-link:hover,
        .edupme-nav .navbar-nav .nav-link:focus {
            color: #2196F3 !important;
        }
        .edupme-nav .navbar-nav.menu-right .nav-item {
            margin-left: 1.5rem;
        }
        .edupme-nav .user-icon {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-radius: 2rem;
            padding: 2px 16px 2px 2px;
            box-shadow: 0 2px 8px 0 rgba(96,165,250,0.10);
        }
        .edupme-nav .user-icon img {
            border-radius: 50%;
            border: 2px solid #FFFFFF;
            margin-right: 8px;
            width: 36px !important;
            height: 36px !important;
            object-fit: cover;
            background: #FFFFFF;
        }
        .edupme-nav .user-icon span {
            color: #FFFFFF;
            font-weight: 700;
            font-size: 18px;
            padding-left: 2px;
        }
        .edupme-nav .nav-link.search-form-tigger, .edupme-nav .nav-link.me-2 {
            background: #FFFFFF;
            color: #2196F3 !important;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            font-size: 20px;
            transition: background 0.2s;
        }
        .edupme-nav .nav-link.search-form-tigger:hover, .edupme-nav .nav-link.me-2:hover {
            background: #F5F5F5;
        }
        .edupme-nav .icon-cart {
            position: relative;
            font-size: 22px;
        }
        .edupme-nav .icon-cart[data-count]:after {
            content: attr(data-count);
            position: absolute;
            top: -8px;
            right: -10px;
            background: #F44336;
            color: #FFFFFF;
            font-size: 12px;
            border-radius: 50%;
            padding: 2px 6px;
            font-weight: bold;
            border: 2px solid #FFFFFF;
        }
        .edupme-nav .navbar-toggler .btn {
            background: #FFFFFF;
            color: #1F1F1F;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .edupme-nav .navbar-toggler .btn:hover {
            background: #F5F5F5;
        }
        .edupme-nav .dropdown-nav {
            background: var(--color-text-white);
            border-radius: 1rem;
            box-shadow: 0 2px 16px 0 var(--color-shadow);
            margin-top: 10px;
            min-width: 260px;
            padding: 0;
        }
        .edupme-nav .dropdown-nav ul {
            padding: 0;
            margin: 0;
        }
        .edupme-nav .dropdown-nav ul li {
            list-style: none;
            margin: 0;
            padding: 0;
            /* Remove extra margin and padding between li elements */
            line-height: 1.2;
        }
        .edupme-nav .dropdown-nav ul li a {
            color: var(--color-text-primary);
            font-size: 16px;
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 4px 20px;
            display: block;
            background: none;
            transition: background 0.2s, color 0.2s;
            text-align: left;
        }
        .edupme-nav .dropdown-nav ul li a:hover {
            background: var(--color-bg-light);
            color: var(--color-primary);
        }
        .edupme-nav .dropdown-nav .profile-dropdown-area {
            text-align: center;
            padding: 20px 10px 10px 10px;
            border-bottom: 1px solid var(--color-border);
        }
        .edupme-nav .dropdown-nav .profile-dropdown-area h6 {
            color: #1F1F1F;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 2px;
        }
        .edupme-nav .dropdown-nav .profile-dropdown-area span {
            color: #666666;
            font-size: 14px;
            font-weight: 400;
        }
        .edupme-nav .dropdown-nav .profile-dropdown-area img {
            border-radius: 50%;
            border: 2px solid #FFFFFF;
            margin-bottom: 8px;
            width: 56px;
            height: 56px;
            object-fit: cover;
            background: #FFFFFF;
        }
        .edupme-nav .dropdown-nav ul li.border-bottom {
            border-bottom: 1px solid #E0E0E0;
            margin: 0;
            height: 0;
            padding: 0;
        }
        .edupme-nav .dropdown-nav ul li .btn-light {
            background: #F5F5F5;
            color: #1F1F1F;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            width: 90%;
            margin: 8px auto 0 auto;
            display: block;
            text-align: center;
            padding: 7px 0;
            font-size: 16px;
            transition: background 0.2s, color 0.2s;
        }
        .edupme-nav .dropdown-nav ul li .btn-light:hover {
            background: #1F1F1F;
            color: #2196F3;
        }
        /* Remove disabled/greyed-out look from all dropdown items */
        .edupme-nav .dropdown-nav ul li,
        .edupme-nav .dropdown-nav ul li a,
        .edupme-nav .dropdown-nav ul li .btn-light {
            opacity: 1 !important;
            pointer-events: auto !important;
        }


        .logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-radius: 0.75rem;
            transition: transform 0.3s ease;
        }

        @media (min-width: 992px) {
            .logo-icon {
                width: 3rem;
                height: 3rem;
            }
        }

        .logo-container:hover .logo-icon {
            transform: scale(1.05);
        }

        .logo-svg {
            width: 1.5rem;
            height: 1.5rem;
            color: #FFFFFF;
        }

        @media (min-width: 992px) {
            .logo-svg {
                width: 1.75rem;
                height: 1.75rem;
            }
        }

        .logo-text {
            font-size: 1.875rem;
            font-weight: 900;
            font-family: 'Exo 2', 'Segoe UI', 'Arial', sans-serif;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: #2196F3; /* Fallback color */
            line-height: 1.05;
            margin-left: 0;
            letter-spacing: 0;
            display: inline-block;
            vertical-align: middle;
        }

        /* Ensure logo container is properly styled */
        .logo-container {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-container:hover {
            text-decoration: none;
            color: inherit;
        }

        /* Logo icon improvements */
        .logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px 0 rgba(33, 150, 243, 0.2);
        }

        /* Ensure SVG is visible */
        .logo-svg {
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
        }

        /* Header improvements */
        .edupme-nav .navbar-brand {
            display: flex;
            align-items: center;
        }

        .edupme-nav .navbar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .edupme-nav .navbar-nav .nav-link i {
            font-size: 1.1em;
        }

        /* Progress circle styling */
        .circular {
            position: relative;
            display: inline-block;
        }

        .pe-cir {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #F5F5F5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #1F1F1F;
            font-size: 0.875rem;
        }

        /* User icon improvements */
        .user-icon {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            border-radius: 2rem;
            padding: 2px 16px 2px 2px;
            box-shadow: 0 2px 8px 0 rgba(33, 150, 243, 0.2);
        }

        .user-icon a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        .user-icon img {
            border-radius: 50%;
            border: 2px solid #FFFFFF;
            margin-right: 8px;
            width: 36px !important;
            height: 36px !important;
            object-fit: cover;
            background: #FFFFFF;
        }

        .user-icon span {
            color: #FFFFFF;
            font-weight: 700;
            font-size: 18px;
            padding-left: 2px;
        }
        .logo {
            height: 45px;
        }
        /* @media (min-width: 992px) {
            .logo-text {
                font-size: 2.2rem;
            }
        } */
        /* --- END HEADER/NAVBAR IMPROVEMENTS --- */
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <!-- Start navbar -->
    <nav class="navbar navbar-expand-xl edupme-nav">
        <div class="container">
            <!-- Logo Section -->
            <a href="{{ route('homepage') }}" class="logo-container d-flex align-items-center">
                {{-- <div class="logo-icon d-flex align-items-center justify-content-center me-3">
                    <svg class="logo-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <span class="logo-text">Knolzi</span> --}}
                <img src="{{ asset('assets/images/logo.png') }}" alt="Knolzi" class="logo">
            </a>
            <div class="navbar-toggler pe-0">
                <div class="mobile-collpase pe-0">
                    <button class="btn btn-white pe-0" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar">
                        <span class="bi bi-layout-text-sidebar " data-bs-toggle="offcanvas"
                            data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar"></span>
                    </button>
                    <a class="nav-link search-form-tigger" href="#search" data-toggle="search-form"><i
                            class="icon-search"></i></a>
                    <a class="nav-link me-2" href="javascript:void(0)"><i class="icon-cart"></i></a>
                </div>
            </div>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav menu-center">

                </ul>
                <ul class="navbar-nav menu-right align-items-center delivery-right-menu">
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="modal" href="#RateThisCourseModal" role="button"><i
                                class="fas fa-star-half-alt"></i> Rate this course</a>
                    </li>
                    <li class="nav-item your-progress-header dropdown">
                        <div class="circular">
                            <div class="pe-cir pie{{ $rand_progress }}"
                                data-pie='{ "percent": {{ $progresscount }} }'>
                            </div>
                        </div>
                        <a class="nav-link" href="javascript:void(0)">Your Progress</a>
                        <div class="dropdown-nav">
                            <div class="your-progress-dropdown">
                                <h5>{{ $progresscount }} of 100 Complete</h5>
                                <span>Finsh course to get your certificate</span>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <div class="user-icon">
                            <a href="javascript:void(0)">
                                @if (!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}"
                                        width="20" height="20">
                                @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}"
                                        alt="{{ Auth::user()->name }}" width="20" height="20">
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                        </div>
                        <div class="dropdown-nav">
                            <div class="profile-dropdown-area">
                                @if (!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}"
                                        width="50" height="50">
                                @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}"
                                        alt="{{ Auth::user()->name }}" width="50" height="50">
                                @endif
                                <h6>{{ Auth::user()->name }}</h6>
                                <span>{{ Auth::user()->email }}</span>
                            </div>
                            <ul class="pb-3">
                                <li class="border-bottom p-1 mb-1"></li>
                                @hasanyrole('organization|institute|author')
                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                @endhasanyrole
                                @can('get-my-course')
                                <li><a href="{{ route('getmycourse') }}">i Learn</a></li>
                                @endcan
                                @can('view-reviewer-course')
                                <li><a href="{{ route('getreviewercourse') }}">i Learn</a></li>
                                @endcan
                                <li><a href="{{ route('mycart') }}">Cart</a></li>
                                <li><a href="{{ route('mywishlist') }}">Wishlist</a></li>
                                <li class="border-bottom p-1 mb-1"></li>
                                <li><a href="{{ route('personal-profile') }}">Profile Settings</a></li>
                                <li><a href="{{ route('change-password') }}">Account Settings</a></li>
                                <li><a href="javascript:void(0)">Purchase History</a></li>
                                <li><a href="javascript:void(0)">Payment Settings</a></li>
                                <li class="border-bottom p-1 mb-1"></li>
                                <li><a href="javascript:void(0)">Need Help</a></li>
                                <li>
                                    <a href="javascript:void(0)"
                                        onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">Log
                                        Out</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                                <li class="border-bottom p-1 mb-1"></li>
                                <li><a href="{{ route('start-teaching') }}"
                                        class="fw-bold text-center justify-content-center btn-light p-3">Teach with
                                        us</a></li>
                                <li><a href="{{ route('digital-class') }}"
                                        class="fw-bold text-center justify-content-center btn-light p-3">Business
                                        Solutions</a></li>
                            </ul>
                        </div>
                    </li>
                    <!--                        <li class="nav-item delivery-more-detail dropdown">
                                                    <a class="nav-link" href="javascript:void(0)"><i class="fas fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-nav">
                                                        <div class="deliverymore-dropdown">
                                                            <ul>
                                                                <li><a href="javascript:void(0)"><span class="icon-bookmark"></span> Add to favorite</a></li>
                                                                <li><a href="javascript:void(0)"><span class="fas fa-share-alt"></span> Share this course</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>-->
                </ul>
            </div>
        </div>
    </nav>
    <!-- end navbar -->

    <div class="del-breadcrumb">
        <div class="container">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ul class="breadcrumb">
                    @if (!empty($category))
                        @foreach ($category as $cat)
                            <li class="breadcrumb-item"><a
                                    href="{{ route('categorycourses', $cat->slug) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </nav>
        </div>
    </div>

    <section class="delivery-main-content">
        <div id="content_delivery_div" class="fluid-container pe-5 ps-5">
            <div class="row" id="completed-question">
                @if (empty($question))
                    <div class="col-lg-12 pe-lg-0 pe-3 ps-3">
                        <div class="left-side-del-panel">
                            <div class="media-delivery-content">
                                <div class="question-answer">
                                    Question not found! please try later.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-9 pe-lg-0 pe-3 ps-3">
                        <div class="left-side-del-panel">
                            <div class="media-delivery-content">
                                <form class="g-3 w-100" action="#" name="submitquestion" id="submitquestion"
                                    method="POST">
                                    <div class="question-answer">
                                        <div class="question-mcq">{!! preg_replace(
                                            '#(<[a-z ]*)(style=("|\')(.*?)("|\'))([a-z ]*>)#',
                                            '\\1\\6',
                                            str_replace(['<p>', '</p>'], '', $question->question_name),
                                        ) !!}</div>
                                        @if ($question->question_media_type == 'single' && $question->question_media != '')
                                            <a class="btn btn-primary" data-bs-toggle="modal"
                                                href="#QuestionMediaModal" role="button">See this Media</a>
                                        @elseif($question->question_media_type == 'multi' && !empty($question->question_media_multi))
                                            <a class="btn btn-primary" data-bs-toggle="modal"
                                                href="#QuestionMediaModal" role="button">See this Media</a>
                                        @elseif ($question->question_media_type == 'scorm' && $question->question_media != '')
                                            <iframe id="scorm_iframe" width="80%" height="100%"
                                                src="{{ $question->question_media }}" frameborder="0"
                                                allowfullscreen></iframe>
                                        @else
                                            <div class="question-media">
                                                {!! preg_replace('/<p[^>]*>(?:\s|&nbsp;)*<\/p>/', '', $question->question_media) !!}
                                            </div>
                                        @endif
                                        @if ($question->question_type == 'single' || $question->question_type == 'multi')
                                            @if (!empty($question_ans))
                                                @foreach ($question_ans as $key => $que_ans)
                                                    <div class="form-check" id="ans">
                                                        <input class="form-check-input" type="radio" name="answer"
                                                            id="Question{{ $key }}" data-type="radio"
                                                            data-key="{{ $key }}"
                                                            value="{{ encrypt($que_ans['id']) }}">
                                                        <label class="form-check-label"
                                                            for="Question{{ $key }}">
                                                            @if ($que_ans['choice_type'] == '0')
                                                                {!! strip_tags($que_ans['answer_name']) !!}
                                                            @else
                                                                {!! $que_ans['answer_name'] !!}
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <input type="hidden" name="type" value="radio" />
                                            @endif
                                        @else
                                            <div class="mb-3 col-12">
                                                <textarea class="form-control" id="textanswer" name="answer" placeholder="write your answer" required></textarea>
                                            </div>
                                            <input type="hidden" name="type" value="user_input" />
                                        @endif
                                        <input type="hidden" name="question_id"
                                            value="{{ encrypt($question['id']) }}" />
                                        <input type="hidden" name="course_id"
                                            value="{{ encrypt($question['course_id']) }}" />
                                        <input type="hidden" name="course_attempt_id"
                                            value="{{ encrypt($courseAttempt->course_attempt_id) }}" />
                                        <input type="hidden" name="time_taken" id="count" value="" />
                                    </div>
                                    <div class="submit-next-button">
                                        <button type="submit" class="btn btn-warning submit-que">SUBMIT</button>
                                        <button type="button" disabled="disabled"
                                            class="btn btn-warning next-que">NEXT</button>
                                    </div>
                                </form>
                            </div>
                            <div class="bottom-hint-panel">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="hint-area">
                                            <div class="hint-title">
                                                <span class="icon-hint-yellow"></span>
                                                <h3>Hint</h3> <i class="icon-right-arrow"></i>
                                            </div>
                                            <div class="hint-icons">
                                                <ul>
                                                    <li><a
                                                            @if (!empty($hint) && !empty($hint['video'])) class="active" data-bs-toggle="modal" href="#VideoHintModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-video"></span></a></li>
                                                    <li><a
                                                            @if (!empty($hint) && !empty($hint['audio'])) class="active" data-bs-toggle="modal" href="#AudioHintModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-audio"></span></a></li>
                                                    <li><a
                                                            @if (!empty($hint) && !empty($hint['pdf'])) class="active" data-bs-toggle="modal" href="#DocumentHintModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-document"></span></a></li>
                                                    <li><a
                                                            @if (!empty($hint) && !empty($hint['image'])) class="active" data-bs-toggle="modal" href="#ImageHintModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-image"></span></a></li>
                                                    <li><a
                                                            @if (!empty($hint) && !empty($hint['link'])) class="active link_hint" href="{{ $hint['link'] }}" target="_blank" @else class="link_hint" href="javascript:void(0)" @endif><span
                                                                class="icon-h-link"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="hint-area rgt-help">
                                            <div class="hint-title">
                                                <span class="icon-help-yellow"></span>
                                                <h3>Help</h3> <i class="icon-right-arrow"></i>
                                            </div>
                                            <div class="hint-icons">
                                                <ul>
                                                    <li><a
                                                            @if (!empty($help) && !empty($help['video'])) class="active" data-bs-toggle="modal" href="#VideoHelpModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-video"></span></a></li>
                                                    <li><a
                                                            @if (!empty($help) && !empty($help['audio'])) class="active" data-bs-toggle="modal" href="#AudioHelpModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-audio"></span></a></li>
                                                    <li><a
                                                            @if (!empty($help) && !empty($help['pdf'])) class="active" data-bs-toggle="modal" href="#DocumentHelpModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-document"></span></a></li>
                                                    <li><a
                                                            @if (!empty($help) && !empty($help['image'])) class="active" data-bs-toggle="modal" href="#ImageHelpModal" role="button" @else href="javascript:void(0)" @endif><span
                                                                class="icon-h-image"></span></a></li>
                                                    <li><a
                                                            @if (!empty($help) && !empty($help['link'])) class="active link_help" href="{{ $help['link'] }}" target="_blank" @else class="link_help" href="javascript:void(0)" @endif><span
                                                                class="icon-h-link"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 ps-lg-0 pe-3 ps-3">
                        <div class="right-side-del-pnael">
                            <div class="total-progress">
                                <span>Total Progress</span>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $progresscount }}%;" aria-valuenow="25" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="chapter-progress">
                                <span>Chapter Progress</span>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $chepter_progress }}%;" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="qaandcomment-sec">
                                <nav>
                                    <div class="nav nav-tabs" id="QueTabs" role="tablist">
                                        <button class="nav-link active" id="qatab-tab" data-bs-toggle="tab"
                                            data-bs-target="#qatab" type="button" role="tab"
                                            aria-controls="qatab" aria-selected="true">Q & A</button>
                                        <button class="nav-link" id="commenttab-tab" data-bs-toggle="tab"
                                            data-bs-target="#commenttab" type="button" role="tab"
                                            aria-controls="commenttab" aria-selected="false">Comments</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="QueTabsContent">
                                    <div class="tab-pane fade show active" id="qatab" role="tabpanel"
                                        aria-labelledby="nav-home-tab">
                                        <div class="accordion" id="accordionQA">
                                            @if (!empty($course_qa))
                                                @foreach ($course_qa as $key => $qa)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading{{ md5($key) }}">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse{{ md5($key) }}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse{{ md5($key) }}">
                                                                {{ $qa['question_name'] }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapse{{ md5($key) }}"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="heading{{ md5($key) }}"
                                                            data-bs-parent="#accordionQA">
                                                            <div class="accordion-body">
                                                                {{ $qa['answer'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p>No any Q & A for this course</p>
                                            @endif
                                            <div class="add-new-q-a">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    href="#accordionQAModal" role="button">Add New Q & A</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="commenttab" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="comments" id="CourseComment">
                                            @if (!empty($course_comment))
                                                <ul>
                                                    @foreach ($course_comment as $key => $comment)
                                                        <li>{{ $comment['comment'] }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p>You have not any comments for this course</p>
                                            @endif
                                            <div class="add-new-q-a">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    href="#CourseAddNewCommentModal" role="button">Add New
                                                    Comment</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--------------------------------All Modal ----------------------------------->
                    <div class="modal fade" id="RateThisCourseModal" aria-hidden="true"
                        aria-labelledby="RateThisCourseModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="rate-review">
                                @if (!empty($review))
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="RateThisCourseModalToggleLabel">Your Review</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="rate-review">
                                        <div class="course-rating">
                                            <div class="star-rating">
                                                <span class="star-rating__fill"
                                                    style="width: {{ $review['rate'] * 20 }}%">
                                                </span>
                                            </div>
                                        </div>
                                        <p>{{ $review['review'] }}</p>
                                    </div>
                                @else
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="RateThisCourseModalToggleLabel">How would you rate
                                            this course?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3" action="#" name="submitrate" id="submitrate"
                                            method="POST">
                                            <label for="Select Rating" class="col-form-label">Select Rating</label>
                                            <div class="my-rating"></div>
                                            <div class="mb-3 review" style="display:none;">
                                                <textarea class="form-control" name="review"
                                                    placeholder="Tell us about your own personal experience taking this course. Was it a good match for you?"
                                                    rows="3"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <input type="hidden" name="course_id"
                                                    value="{{ encrypt($question['course_id']) }}" />
                                                <input type="hidden" name="rate" class="user_rate"
                                                    value="" />
                                                <button type="submit" class="btn btn-primary mb-3">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="accordionQAModal" aria-hidden="true"
                        aria-labelledby="accordionQAModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="accordionQAModalToggleLabel">Add Q & A</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" action="#" name="createqanda" id="createqanda"
                                        method="POST">
                                        <div class="mb-3">
                                            <label for="Question" class="col-sm-2 col-form-label">Question</label>
                                            <textarea class="form-control coursecomment" name="question" placeholder="Enter Your Question" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <input type="hidden" name="course_id"
                                                value="{{ encrypt($question['course_id']) }}" />
                                            <button type="submit" class="btn btn-primary mb-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="CourseAddNewCommentModal" aria-hidden="true"
                        aria-labelledby="CourseAddNewCommentModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="CourseAddNewCommentModalToggleLabel">Add Comment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" action="#" name="createcomment" id="createcomment"
                                        method="POST">
                                        <div class="mb-3">
                                            <label for="Comment" class="col-sm-2 col-form-label">Comment</label>
                                            <textarea class="form-control coursecomment" name="course_comment" placeholder="Enter Your Comment" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <input type="hidden" name="course_id"
                                                value="{{ encrypt($question['course_id']) }}" />
                                            <button type="submit" class="btn btn-primary mb-3">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($question->question_media_type == 'single' || $question->question_media_type == 'multi')
                        <div class="modal fade" id="QuestionMediaModal" aria-hidden="true"
                            aria-labelledby="QuestionMediaModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="QuestionMediaModalToggleLabel">Question Media</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($question->question_media_type == 'multi')
                                            <div id="carousel" class="owl-carousel owl-theme">
                                                @foreach ($question->question_media_multi as $image)
                                                    <div class="item">
                                                        <img src="{{ $image }}" alt="Question Media">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            @if (strpos($question->question_media, '.mp4') !== false)
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <video width="100%" class="embed-responsive-item" controls
                                                        controlsList="nodownload"
                                                        src="{{ $question->question_media }}"></video>
                                                </div>
                                            @else
                                                <img src="{{ $question->question_media }}" class="img-fluid"
                                                    alt="Question Media">
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($help) && !empty($help['video']))
                        <div class="modal fade" id="VideoHelpModal" aria-hidden="true"
                            aria-labelledby="VideoHelpModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="VideoHelpModalToggleLabel">Video Help</h5>
                                        <input type="hidden" name="helpVideo" id="helpVideo" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <video width="100%" class="embed-responsive-item" controls
                                                controlsList="nodownload" src="{{ $help['video'] }}"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($help) && !empty($help['audio']))
                        <div class="modal fade" id="AudioHelpModal" aria-hidden="true"
                            aria-labelledby="AudioHelpModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="AudioHelpModalToggleLabel">Audio Help</h5>
                                        <input type="hidden" name="helpAudio" id="helpAudio" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <audio style="width:100%" controls controlsList="nodownload">
                                                <source src="{{ $help['audio'] }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($help) && !empty($help['pdf']))
                        <div class="modal fade" id="DocumentHelpModal" aria-hidden="true"
                            aria-labelledby="DocumentHelpModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="DocumentHelpModalToggleLabel">PDF Help</h5>
                                        <input type="hidden" name="helpPdf" id="helpPdf" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe width="100%" height="600px" class="embed-responsive-item"
                                                src="{{ $help['pdf'] }}#toolbar=0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @php
                    @endphp
                    @if (!empty($help) && !empty($help['image']))
                        <div class="modal fade" id="ImageHelpModal" aria-hidden="true"
                            aria-labelledby="ImageHelpModalModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ImageHelpModalToggleLabel">Image Help</h5>
                                        <input type="hidden" name="helpImage" id="helpImage" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="carousel" class="owl-carousel owl-theme">
                                            @foreach ($help['image'] as $image)
                                                <div class="item">
                                                    <img src="{{ $image['image'] }}" class="img-fluid"
                                                        alt="Image Help" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($hint) && !empty($hint['video']))
                        <div class="modal fade" id="VideoHintModal" aria-hidden="true"
                            aria-labelledby="VideoHintModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="VideoHintModalToggleLabel">Video Hint</h5>
                                        <input type="hidden" name="cntVideo" id="cntVideo" value="" />
                                        <input type="hidden" name="videoTime" id="videoTime" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <video width="100%" class="embed-responsive-item" controls
                                                controlsList="nodownload" src="{{ $hint['video'] }}"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($hint) && !empty($hint['audio']))
                        <div class="modal fade" id="AudioHintModal" aria-hidden="true"
                            aria-labelledby="AudioHintModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="AudioHintModalToggleLabel">Audio Hint</h5>
                                        <input type="hidden" name="cntAudio" id="cntAudio" value="" />
                                        <input type="hidden" name="audioTime" id="audioTime" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <audio style="width:100%" controls controlsList="nodownload">
                                                <source src="{{ $hint['audio'] }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($hint) && !empty($hint['pdf']))
                        <div class="modal fade" id="DocumentHintModal" aria-hidden="true"
                            aria-labelledby="DocumentHintModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="DocumentHintModalToggleLabel">PDF Hint</h5>
                                        <input type="hidden" name="cntPdf" id="cntPdf" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe width="100%" height="600px" class="embed-responsive-item"
                                                src="{{ $hint['pdf'] }}#toolbar=0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($hint) && !empty($hint['image']))
                        <div class="modal fade" id="ImageHintModal" aria-hidden="true"
                            aria-labelledby="ImageHintModalModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ImageHintModalToggleLabel">Image Hint</h5>
                                        <input type="hidden" name="cntImage" id="cntImage" value="" />
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="carousel" class="owl-carousel owl-theme">
                                            @foreach ($hint['image'] as $image)
                                                <div class="item">
                                                    <img src="{{ $image['image'] }}" class="img-fluid"
                                                        alt="Image Hint" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @endif
            </div>
        </div>
    </section>
    <div class="loading" style="display:none">Loading&#8230;</div>
    {{-- <x-layout-front-footer /> --}}
    <script src="{{ asset('assets/front/js/delivery-custom.js') }}"></script>
    <script src="{{ asset('assets/front/js/circularProgressBar.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    @include('front.course-delivery.css-js-question')
    <script>
        countDownTime();
        $(document).ajaxComplete(function() {
            MathJax.typeset();
        });
    </script>
</body>

</html>
