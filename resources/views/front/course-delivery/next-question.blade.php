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
                    <div class="question-mcq">{!! preg_replace('#(<[a-z ]*)(style=("|\')(.*?)("|\'))([a-z ]*>)#', '\\1\\6',$question->question_name) !!}</div>
                    @if($question->question_media_type == 'single' && $question->question_media != "")
                    <a class="btn btn-primary" data-bs-toggle="modal" href="#QuestionMediaModal" role="button">See this Media</a>
                    @elseif($question->question_media_type == 'multi' && !empty($question->question_media_multi))
                    <a class="btn btn-primary" data-bs-toggle="modal" href="#QuestionMediaModal" role="button">See this Media</a>
                    @elseif ($question->question_media_type == "scorm" && $question->question_media != "")
                    <iframe id="scorm_iframe" width="80%" height="100%" src="{{$question->question_media}}" frameborder="0" allowfullscreen></iframe>
                    @else
                    <div class="question-media">
                        {!!  preg_replace("/<p[^>]*>(?:\s|&nbsp;)*<\/p>/", '', $question->question_media) !!}
                    </div>
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
                </div>
                <div class="submit-next-button">
                    <button type="submit" class="btn btn-warning submit-que">SUBMIT</button>
                    <button type="button" disabled="disabled" class="btn btn-warning next-que">NEXT</button>
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
                                <li><a @if(!empty($hint) && !empty($hint['link'])) class="active" href="{{ $hint['link'] }}" target="_blank" @else href="javascript:void(0)" @endif><span class="icon-h-link"></span></a></li>
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
                                <li><a @if(!empty($help) && !empty($help['link'])) class="active" href="{{ $help['link'] }}" target="_blank" @else href="javascript:void(0)" @endif><span class="icon-h-link"></span></a></li>
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
        <div class="qaandcomment-sec">
            <nav>
                <div class="nav nav-tabs" id="QueTabs" role="tablist">
                    <button class="nav-link active" id="qatab-tab" data-bs-toggle="tab" data-bs-target="#qatab" type="button" role="tab" aria-controls="qatab" aria-selected="true">Q & A</button>
                    <button class="nav-link" id="commenttab-tab" data-bs-toggle="tab" data-bs-target="#commenttab" type="button" role="tab" aria-controls="commenttab" aria-selected="false">Comments</button>
                </div>
            </nav>
            <div class="tab-content" id="QueTabsContent">
                <div class="tab-pane fade show active" id="qatab" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="accordion" id="accordionQA">
                        @if(!empty($course_qa))
                        @foreach($course_qa as $key => $qa)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ md5($key) }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ md5($key) }}" aria-expanded="false" aria-controls="collapse{{ md5($key) }}">
                                    {{ $qa['question_name'] }}
                                </button>
                            </h2>
                            <div id="collapse{{ md5($key) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ md5($key) }}" data-bs-parent="#accordionQA">
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
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" href="#accordionQAModal" role="button">Add New Q & A</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="commenttab" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="comments" id="CourseComment">
                        @if(!empty($course_comment))
                        <ul>
                            @foreach($course_comment as $key => $comment)
                            <li>{{ $comment['comment'] }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p>You have not any comments for this course</p>
                        @endif
                        <div class="add-new-q-a">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" href="#CourseAddNewCommentModal" role="button">Add New Comment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--------------------------------All Modal ----------------------------------->
<div class="modal fade" id="RateThisCourseModal" aria-hidden="true" aria-labelledby="RateThisCourseModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="rate-review">
            @if(!empty($review))
            <div class="modal-header">
                <h5 class="modal-title" id="RateThisCourseModalToggleLabel">Your Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rate-review">
                <div class="course-rating">
                    <div class="star-rating">
                        <span class="star-rating__fill" style="width: {{ $review['rate'] * 20 }}%">
                        </span>
                    </div>
                </div>
                <p>{{ $review['review'] }}</p>
            </div>

            @else
            <div class="modal-header">
                <h5 class="modal-title" id="RateThisCourseModalToggleLabel">How would you rate this course?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="#" name="submitrate" id="submitrate" method="POST">
                    <label for="Select Rating" class="col-form-label">Select Rating</label>
                    <div class="my-rating"></div>
                    <div class="mb-3 review" style="display:none;">
                        <textarea class="form-control"  name="review" placeholder="Tell us about your own personal experience taking this course. Was it a good match for you?" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="course_id" value="{{ encrypt($question['course_id']) }}"/>
                        <input type="hidden" name="rate" class="user_rate" value=""/>
                        <button type="submit" class="btn btn-primary mb-3">Submit</button>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </div>
</div>
<div class="modal fade" id="accordionQAModal" aria-hidden="true" aria-labelledby="accordionQAModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accordionQAModalToggleLabel">Add Q & A</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="#" name="createqanda" id="createqanda" method="POST">
                    <div class="mb-3">
                        <label for="Question" class="col-sm-2 col-form-label">Question</label>
                        <textarea class="form-control coursecomment"  name="question" placeholder="Enter Your Question" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="course_id" value="{{ encrypt($question['course_id']) }}"/>
                        <button type="submit" class="btn btn-primary mb-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="CourseAddNewCommentModal" aria-hidden="true" aria-labelledby="CourseAddNewCommentModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CourseAddNewCommentModalToggleLabel">Add Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="#" name="createcomment" id="createcomment" method="POST">
                    <div class="mb-3">
                        <label for="Comment" class="col-sm-2 col-form-label">Comment</label>
                        <textarea class="form-control coursecomment"  name="course_comment" placeholder="Enter Your Comment" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="course_id" value="{{ encrypt($question['course_id']) }}"/>
                        <button type="submit" class="btn btn-primary mb-3">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if($question->question_media_type == 'single' || $question->question_media_type == 'multi')
<div class="modal fade" id="QuestionMediaModal" aria-hidden="true" aria-labelledby="QuestionMediaModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
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
                    <audio style="width: 100%" controls controlsList="nodownload">
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
@include('front.course-delivery.css-js-question')
