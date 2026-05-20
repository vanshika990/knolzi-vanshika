<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Course Delivery | {{ config('app.name', 'Knolzi) }}</title>

        <link rel="canonical" href="{{ url()->current() }}"/>
        <!-- Favicon Icon -->
        <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}" />
        <link rel="icon" href="{{asset('assets/img/favicon.png')}}" type="image/x-icon">

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    </head>
    <body>
        <!-- Start navbar -->
        <nav class="navbar navbar-expand-xl edupme-nav">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/front/images/logo.svg') }}" alt="knolzi" width="100">
                </a>
                <div class="navbar-toggler pe-0">
                    <div class="mobile-collpase pe-0">
                        <button class="btn btn-white pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar">
                            <span class="bi bi-layout-text-sidebar " data-bs-toggle="offcanvas" data-bs-target="#mobilemenu-sidebar" aria-controls="mobilemenu-sidebar"></span>
                        </button>
                        <a class="nav-link search-form-tigger" href="#search" data-toggle="search-form"><i class="icon-search"></i></a>
                        <a class="nav-link me-2" href="javascript:void(0)"><i class="icon-cart"></i></a>
                    </div>
                </div>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav menu-center">

                    </ul>
                    <ul class="navbar-nav menu-right align-items-center delivery-right-menu">

                        <li class="nav-item your-progress-header dropdown">
                            <div class="circular">
                                <div class="pe-cir pie{{ $rand_progress }}" data-pie='{ "percent": {{ $progresscount }} }'>
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
                                    @if(!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" width="20" height="20">
                                    @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}"  width="20" height="20">
                                    @endif
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                            </div>
                            <div class="dropdown-nav">
                                <div class="profile-dropdown-area">
                                    @if(!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" width="50" height="50">
                                    @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}"  width="50" height="50">
                                    @endif
                                    <h6>{{ Auth::user()->name }}</h6>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                                <ul class="pb-3">
                                    <li class="border-bottom p-1 mb-1"></li>
                                    @hasanyrole('organization|institute|author')
                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    @endhasanyrole
                                    <li><a href="{{ route('getreviewercourse') }}">i Learn</a></li>
                                    <li><a href="{{ route('mycart') }}">Cart</a></li>
                                    <li><a href="{{ route('mywishlist') }}">Wishlist</a></li>
                                    <li class="border-bottom p-1 mb-1"></li>
                                    <li><a href="{{route('personal-profile')}}">Profile Settings</a></li>
                                    <li><a href="{{route('change-password')}}">Account Settings</a></li>
                                    <li><a href="javascript:void(0)">Purchase History</a></li>
                                    <li><a href="javascript:void(0)">Payment Settings</a></li>
                                    <li class="border-bottom p-1 mb-1"></li>
                                    <li><a href="javascript:void(0)">Need Help</a></li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">Log Out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                    <li class="border-bottom p-1 mb-1"></li>
                                    <li><a href="{{ route('start-teaching') }}" class="fw-bold text-center justify-content-center btn-light p-3">Teach with us</a></li>
                                    <li><a href="{{ route('digital-class') }}" class="fw-bold text-center justify-content-center btn-light p-3">Business Solutions</a></li>
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
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        @if(!empty($category))
                        @foreach($category as $cat)
                        <li class="breadcrumb-item"><a href="{{ route('categorycourses',$cat->slug) }}">{{ $cat->name }}</a></li>
                        @endforeach
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

        <section class="delivery-main-content">
            <div class="fluid-container pe-5 ps-5">
                <div class="row" id="completed-question">
                    @if(empty($question))
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
                                <form class="g-3 w-100" action="#" name="submitquestion" id="submitquestion" method="POST">
                                    <div class="question-answer">
                                        <div class="question-mcq">{!! preg_replace('#(<[a-z ]*)(style=("|\')(.*?)("|\'))([a-z ]*>)#', '\\1\\6',str_replace(['<p>', '</p>'], '',$question->question_name)) !!}</div>
                                        @if ($question->question_media_type == 'single' && $question->question_media != "")
                                            <a class="btn btn-primary" data-bs-toggle="modal" href="#QuestionMediaModal" role="button">See this Media</a>
                                        @elseif ($question->question_media_type == 'multi' && !empty($question->question_media_multi))
                                            <a class="btn btn-primary" data-bs-toggle="modal" href="#QuestionMediaModal" role="button">See this Media</a>
                                        @elseif ($question->question_media_type == "scorm" && $question->question_media != "")
                                            <iframe id="scorm_iframe"  width="80%" height="100%" src="{{$question->question_media}}" frameborder="0" allowfullscreen></iframe>
                                        @else
                                            {!!  preg_replace("/<p[^>]*>(?:\s|&nbsp;)*<\/p>/", '', $question->question_media) !!}
                                        @endif
                                            @if($question->question_type == 'single' || $question->question_type == 'multi')
                                                @if(!empty($question_ans))
                                                @foreach($question_ans as $key => $que_ans)
                                                <div class="form-check" id="ans">
                                                    <input class="form-check-input" type="radio" name="answer" id="Question{{ $key }}" data-type="radio" data-key="{{ $key }}" value="{{ encrypt($que_ans['id']) }}">
                                                    <label class="form-check-label" for="Question{{ $key }}">
                                                        @if($que_ans['choice_type'] == '0')
                                                        {!! strip_tags($que_ans['answer_name']) !!}
                                                        @else
                                                        {!! $que_ans['answer_name'] !!}
                                                        @endif
                                                    </label>
                                                </div>
                                                @endforeach
                                                <input type="hidden" name="type" value="radio"/>
                                                @endif
                                            @else
                                            <div class="mb-3">
                                                <textarea class="form-control" id="textanswer" name="answer" placeholder="write your answer" rows="5"></textarea>
                                            </div>
                                            <input type="hidden" name="type" value="user_input"/>
                                            @endif
                                        <input type="hidden" name="question_id" value="{{ encrypt($question['id']) }}"/>
                                        <input type="hidden" name="course_id" value="{{ encrypt($question['course_id']) }}"/>
                                        <input type="hidden" name="course_attempt_id" value="{{ encrypt($courseAttempt->course_attempt_id) }}"/>
                                        <input type="hidden" name="time_taken" id="count" value=""/>
                                        <input type="hidden" name="is_complete" value="{{ $is_complete }}"/>
                                    </div>
                                    <div class="submit-next-button">
                                        @if($is_complete == '1')
                                        <button type="submit" class="btn btn-warning submit-que">Complete</button>
                                        @else
                                        <button type="submit" class="btn btn-warning submit-que">SUBMIT</button>
                                        <button type="button" disabled="disabled" class="btn btn-warning next-que">NEXT</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="bottom-hint-panel">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="hint-area">
                                            <div class="hint-title">
                                                <span class="icon-hint-yellow"></span> <h3>Hint</h3> <i class="icon-right-arrow"></i>
                                            </div>
                                            <div class="hint-icons">
                                                <ul>
                                                    <li><a @if(!empty($hint) && !empty($hint['video'])) class="active" data-bs-toggle="modal" href="#VideoHintModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-video"></span></a></li>
                                                    <li><a @if(!empty($hint) && !empty($hint['audio'])) class="active" data-bs-toggle="modal" href="#AudioHintModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-audio"></span></a></li>
                                                    <li><a @if(!empty($hint) && !empty($hint['pdf'])) class="active" data-bs-toggle="modal" href="#DocumentHintModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-document"></span></a></li>
                                                    <li><a @if(!empty($hint) && !empty($hint['image'])) class="active" data-bs-toggle="modal" href="#ImageHintModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-image"></span></a></li>
                                                    <li><a @if(!empty($hint) && !empty($hint['link'])) class="active link_hint" href="{{ $hint['link'] }}" target="_blank" @else class="link_hint" href="javascript:void(0)" @endif><span class="icon-h-link"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="hint-area rgt-help">
                                            <div class="hint-title">
                                                <span class="icon-help-yellow"></span> <h3>Help</h3> <i class="icon-right-arrow"></i>
                                            </div>
                                            <div class="hint-icons">
                                                <ul>
                                                    <li><a @if(!empty($help) && !empty($help['video'])) class="active" data-bs-toggle="modal" href="#VideoHelpModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-video"></span></a></li>
                                                    <li><a @if(!empty($help) && !empty($help['audio'])) class="active" data-bs-toggle="modal" href="#AudioHelpModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-audio"></span></a></li>
                                                    <li><a @if(!empty($help) && !empty($help['pdf'])) class="active" data-bs-toggle="modal" href="#DocumentHelpModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-document"></span></a></li>
                                                    <li><a @if(!empty($help) && !empty($help['image'])) class="active" data-bs-toggle="modal" href="#ImageHelpModal" role="button" @else href="javascript:void(0)" @endif><span class="icon-h-image"></span></a></li>
                                                    <li><a @if(!empty($help) && !empty($help['link'])) class="active link_help" href="{{ $help['link'] }}" target="_blank" @else class="link_help" href="javascript:void(0)" @endif><span class="icon-h-link"></span></a></li>
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
                                    <div class="progress-bar" role="progressbar" style="width: {{ $progresscount }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="chapter-progress">
                                <span>Chapter Progress</span>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $chepter_progress }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-------------------------------- All Modal ----------------------------------->
                    @if($question->question_media_type == 'single' || $question->question_media_type == 'multi' || $question->question_media_type == 'scorm' )
                    <div class="modal fade" id="QuestionMediaModal" aria-hidden="true" aria-labelledby="QuestionMediaModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="width:1020px;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="QuestionMediaModalToggleLabel">Question Media</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if($question->question_media_type == 'multi')
                                    <div id="carousel" class="owl-carousel owl-theme">
                                        @foreach($question->question_media_multi as $image)
                                        <div class="item">
                                            <img src="{{ $image }}" alt="Question Media">
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                        @if (strpos($question->question_media, '.mp4') !== false)
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <video width="100%" class="embed-responsive-item" controls controlsList="nodownload" src="{{ $question->question_media }}"></video>
                                        </div>
                                        @elseif($question->question_media_type == 'scorm')
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe id="scorm_iframe" src="{{URL::to('/')}}/uploads/question_{{$question->id}}/story.html" style="height: 78vh; width: 100%;"></iframe>
                                            </div>
                                        @else
                                        <img src="{{ $question->question_media }}" class="img-fluid" alt="Question Media">
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($help) && !empty($help['video']))
                    <div class="modal fade" id="VideoHelpModal" aria-hidden="true" aria-labelledby="VideoHelpModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="VideoHelpModalToggleLabel">Video Help</h5>
                                    <input type="hidden" name="helpVideo" id="helpVideo" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <video width="100%" class="embed-responsive-item" controls controlsList="nodownload" src="{{ $help['video'] }}"></video>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($help) && !empty($help['audio']))
                    <div class="modal fade" id="AudioHelpModal" aria-hidden="true" aria-labelledby="AudioHelpModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="AudioHelpModalToggleLabel">Audio Help</h5>
                                    <input type="hidden" name="helpAudio" id="helpAudio" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    @if(!empty($help) && !empty($help['pdf']))
                    <div class="modal fade" id="DocumentHelpModal" aria-hidden="true" aria-labelledby="DocumentHelpModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="DocumentHelpModalToggleLabel">PDF Help</h5>
                                    <input type="hidden" name="helpPdf" id="helpPdf" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe width="100%" height="600px" class="embed-responsive-item" src="{{ $help['pdf'] }}#toolbar=0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @php
                    @endphp
                    @if(!empty($help) && !empty($help['image']))
                    <div class="modal fade" id="ImageHelpModal" aria-hidden="true" aria-labelledby="ImageHelpModalModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ImageHelpModalToggleLabel">Image Help</h5>
                                    <input type="hidden" name="helpImage" id="helpImage" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="carousel" class="owl-carousel owl-theme">
                                        @foreach($help['image'] as $image)
                                        <div class="item">
                                            <img src="{{ $image['image'] }}" class="img-fluid" alt="Image Help"/>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($hint) && !empty($hint['video']))
                    <div class="modal fade" id="VideoHintModal" aria-hidden="true" aria-labelledby="VideoHintModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="VideoHintModalToggleLabel">Video Hint</h5>
                                    <input type="hidden" name="cntVideo" id="cntVideo" value=""/>
                                    <input type="hidden" name="videoTime" id="videoTime" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <video width="100%" class="embed-responsive-item" controls controlsList="nodownload" src="{{ $hint['video'] }}"></video>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($hint) && !empty($hint['audio']))
                    <div class="modal fade" id="AudioHintModal" aria-hidden="true" aria-labelledby="AudioHintModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="AudioHintModalToggleLabel">Audio Hint</h5>
                                    <input type="hidden" name="cntAudio" id="cntAudio" value=""/>
                                    <input type="hidden" name="audioTime" id="audioTime" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    @if(!empty($hint) && !empty($hint['pdf']))
                    <div class="modal fade" id="DocumentHintModal" aria-hidden="true" aria-labelledby="DocumentHintModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="DocumentHintModalToggleLabel">PDF Hint</h5>
                                    <input type="hidden" name="cntPdf" id="cntPdf" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe width="100%" height="600px" class="embed-responsive-item" src="{{ $hint['pdf'] }}#toolbar=0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!empty($hint) && !empty($hint['image']))
                    <div class="modal fade" id="ImageHintModal" aria-hidden="true" aria-labelledby="ImageHintModalModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ImageHintModalToggleLabel">Image Hint</h5>
                                    <input type="hidden" name="cntImage" id="cntImage" value=""/>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="carousel" class="owl-carousel owl-theme">
                                        @foreach($hint['image'] as $image)
                                        <div class="item">
                                            <img src="{{ $image['image'] }}" class="img-fluid" alt="Image Hint"/>
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
    @include('front.reviewer.css-js-question')
    <script>
countDownTime();
$(document).ajaxComplete(function() {
    MathJax.typeset();
});
    </script>
    <style>
        .delivery-main-content .left-side-del-panel .media-delivery-content {
            height: 80vh;
            /* 50% of the viewport height */

        }

        .left-side-del-panel .question-answer {
            padding-block: 30px;
            height: 75vh;
            margin-bottom: 60px;
        }

        .delivery-main-content .left-side-del-panel .question-answer .question-media img {
            vertical-align: middle;
            text-align: center;
            margin: auto;
            display: block;
            margin-bottom: 10px;
        }

        .delivery-main-content .left-side-del-panel .question-answer .question-media p,
        .delivery-main-content .left-side-del-panel .question-answer .question-media li,
        .delivery-main-content .left-side-del-panel .question-answer .question-media span {
            font-size: 20px !important;
            color: #000 !important;
            line-height: 1.5;
        }

        .left-side-del-panel .question-answer .form-check label.form-check-label {
            font-size: 18px;
            margin: 0 !important;
            padding: 0 !important;
            font-weight: bold;
        }

        .delivery-main-content .left-side-del-panel .question-mcq,
        .delivery-main-content .left-side-del-panel .question-mcq p,
        .delivery-main-content .left-side-del-panel .question-mcq span {
            font-size: 30px !important;
        }
    </style>
</body>
</html>
