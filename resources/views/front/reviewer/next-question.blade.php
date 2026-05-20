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
                    <p>{!!  preg_replace("/<p[^>]*>(?:\s|&nbsp;)*<\/p>/", '', $question->question_media) !!}</p>
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
    </div>
</div>
<!--------------------------------All Modal ----------------------------------->
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
@include('front.reviewer.css-js-question')
