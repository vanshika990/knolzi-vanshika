<x-layout-font-dashboard-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ url('/') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Question</small>
                </h1>
            </div>
        </div>
    </div>
    <form class="kt-form" action="{{ route('user.my-course-question.update',$questionDetail->id) }}" name="updatequestion" id="updatequestion" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Edit Question</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="py-3 col-md-12">
                                <label for="question_name" class="required form-label">Question name</label>
                                <textarea name="question_name" id="question_name" cols="30" rows="10">{{ $questionDetail->question_name }}</textarea>
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="question_level" class="required form-label" >Question Level</label>
                                <input type="text" class="form-control form-control-solid" name="question_level" value="{{ $questionDetail->que_level }}" id="question_level" placeholder="Question Level">
                            </div>
                            <div class="py-3 col-md-12">
                                <fieldset class="form-group">
                                    <label for="question_media_type" class="required form-label d-block">Question Media</label>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_media_type" id="single_media" value="single" checked="checked" {{ $questionDetail->question_media_type == 'single' ? 'checked' : ''}}> Single
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_media_type" id="html" value="html" {{ $questionDetail->question_media_type == 'html' ? 'checked' : ''}} > Html
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_media_type" id="scorm" value="scorm" {{ $questionDetail->question_media_type == 'scorm' ? 'checked' : ''}} > Upload Scrom Zip
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="py-3 col-md-12" id="question_media_single" style="display:{{ $questionDetail->question_media_type == 'single' ? 'block' : 'none'}}">
                                <div class="form-group">
                                    <label for="question_media" class="required form-label">Question Media</label> <small id="fileHelp" class="text-muted question_media_note"> (Note : upload only mp4,pdf,jpeg,jpg,png,gif)</small>
                                    <input type="file" class="form-control" name="question_media" id="question_media">
                                    @if($questionDetail->question_media_type == 'single' && $questionDetail->question_media != '')
                                    <a href="{{ $questionDetail->question_media }}" class="btn btn-primary m-b-15 mt-3" target="_blank">Preview</a>
                                    <input type="hidden" name="media_status" id="mediahelp" value="1">
                                    <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="mediahelp" title="Click here to delete media"> Delete </a>
                                    @endif
                                </div>
                            </div>
                            <div class="py-3 col-md-6" id="question_media_scorm" style="display:{{ $questionDetail->question_media_type == 'scorm' ? 'block !important' : 'none !important'}}">
                                <div class="form-group">
                                    <label for="image_help" class="required form-label d-block">Scorm zip upload</label>  <small id="scorm_zip" class="text-muted question_media_note">(Note : upload only scorm zip.)</small>
                                    <input type="file" class="form-control  form-control-solid" name="scorm_zip" id="scorm_zip">
                                    @if($questionDetail->question_media_type == 'scorm' )
                                        <a href="{{ $questionDetail->question_media }}" class="btn btn-primary m-b-15 mt-3" target="_blank">Preview</a>
                                        <input type="hidden" name="scorm_status" id="scormhelp" value="1">
                                        <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="scormhelp" title="Click here to delete media"> Delete </a>
                                    @endif
                                </div>
                            </div>
                            <div class="py-3 col-md-12" id="question_media_html" style="display:{{ $questionDetail->question_media_type == 'html' ? 'block' : 'none'}}">
                                <label for="" class="required form-label">Question Media</label>
                                <textarea name="question_media" id="question_media_data" cols="30" rows="10">{{ $questionDetail->question_media }}</textarea>
                            </div>


                            <div class="py-3 col-md-6">
                                <label for="toc_no" class="required form-label"> TOC No</label>
                                <input type="text" class="form-control form-control-solid" name="toc_no" value="{{ $questionDetail->que_toc_no }}" id="toc_no" placeholder="TOC No">
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="toc_text" class="required form-label"> TOC Text</label>
                                <textarea name="toc_text" id="toc_text" class="form-control form-control-solid" cols="75" rows="1">{{ $questionDetail->que_toc_text }}</textarea>
                            </div>

                            <div class="py-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="course_id" class="required form-label">Select Course</label>
                                    <select class="form-control form-control-solid " name="course_id" id="course_id">
                                        @foreach($courseData as $course)
                                        <option value="{{ $course['course']['course_id'] }}" {{ $course['course']['course_id'] == $questionDetail->course_id ? 'selected' : ''}} >{{ $course['course']['course_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="py-3 col-md-6" id="question_type">
                                <fieldset class="form-group">
                                    <label for="que_type" class="required form-label d-block">Question type</label>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_type" id="radio" value="single" {{ $questionDetail->question_type == 'single' ? 'checked' : ''}} > Single
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_type" id="multi" value="multi" {{ $questionDetail->question_type == 'multi' ? 'checked' : ''}} > Multi
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_type" id="user_input" value="user_input" {{ $questionDetail->question_type == 'user_input' ? 'checked' : ''}}> User input
                                        </label>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="py-3 pb-0 row">
                                <div class="py-3">
                                    <label class="required form-label">Answer</label>
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="answer_table">
                                            <thead>
                                                <tr class="fw-bolder text-muted">
                                                    <th class="min-w-150px">Answer name</th>
                                                    <th class="min-w-140px">Answer order</th>
                                                    <th class="min-w-120px">choice type</th>
                                                    <th class="min-w-100px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @for ($i = 0; $i < count($questionDetail->questionanswer); $i++)
                                                @if($i==0)
                                                <tr>
                                                    <td>
                                                        <div class="form-group" >
                                                            <textarea class="form-control form-control-solid" id="choice1" placeholder="Choice" name="choice{{ $i+1 }}" rows="1" cols="50">{{ $questionDetail->questionanswer[$i]->answer_name }}</textarea>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group" >
                                                            <input type="number" class="form-control form-control-solid" placeholder="Order" name="order[]" value="{{ $questionDetail->questionanswer[$i]->answer_order }}"  min="1" max="4">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select class="form-control form-control-solid" name="ctype[]" id="ctype">
                                                                <option @if($questionDetail->questionanswer[$i]->choice_type == '0') selected  @endif value="0">Normal</option>
                                                                <option @if($questionDetail->questionanswer[$i]->choice_type == '1') selected  @endif value="1">Math</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="javacript:void(0);" class="add_new_choice btn btn-primary m-b-15"><i class="las la-plus"></i> Add</a>
                                                    </td>
                                                </tr>
                                                @endif
                                                @if($i != 0)
                                                <tr class="remove" id="remove_{{ $i+1 }}">
                                                    <td>
                                                        <div class="form-group" >
                                                            <textarea class="form-control form-control-solid" id="choice{{ $i+1 }}" placeholder="Choice" name="choice{{ $i+1 }}" rows="1" cols="50">{{ $questionDetail->questionanswer[$i]->answer_name }}</textarea>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group" >
                                                            <input type="number" class="form-control form-control-solid" id="order{{ $i+1 }}" placeholder="Order" name="order[]" value="{{ $questionDetail->questionanswer[$i]->answer_order }}"  min="1" max="4">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select class="form-control form-control-solid" name="ctype[]" id="ctype">
                                                                <option @if($questionDetail->questionanswer[$i]->choice_type == 0) selected  @endif value="0">Normal</option>
                                                                <option @if($questionDetail->questionanswer[$i]->choice_type == 1) selected  @endif value="1">Math</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="javacript:void(0);" onclick="remove_choice({{ $i+1 }}); return false;" class="btn btn-danger m-b-15"> <i class="las la-trash"></i> Delete</a>
                                                    </td>
                                                    </div>
                                                    @endif
                                                    @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="py-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group choosn-sle">
                                        <label for="question_intent" class="required form-label">Question Intent</label>
                                        @php $question_intent_id = explode(',',$questionDetail->question_intent_id); @endphp
                                        <select class="form-select form-select-solid select-box" name="question_intent[]" id="question_intent" multiple>
                                            @foreach($intentDetail as $intent)
                                            <option value="{{ $intent->id }}" {{ (in_array($intent->id, $question_intent_id)) ? 'selected' : '' }}>{{ $intent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="py-3 col-md-6 col-sm-12 col-xs-12" id="div_correct_question_ans">
                                    <div class="form-group">
                                        <label for="correct_question_ans" class="required form-label">Correct answer</label>
                                        <input type="number" class="form-control form-control-solid" name="correct_question_ans" id="correct_question_ans" placeholder="Correct answer"  value="{{ $questionDetail->correct_question_ans }}" min="1" max="4">
                                    </div>
                                </div>

                                <div class="py-3 col-md-12 col-sm-12 col-sm-12">
                                    <label for="status" class="form-label d-block">Status</label>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="status" value="1" {{ $questionDetail->status == '1' ? 'checked' : ''}} > Active
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="status" value="0" {{ $questionDetail->status == '0' ? 'checked' : ''}} > Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post1" style="display:{{ $questionDetail->question_media_type == 'scorm' ? 'none !important' : 'block' }}">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Question help</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="py-3 col-md-6 col-sm-12">
                                <label for="video_help" class="form-label">Video help</label> <small id="fileHelp" class="text-muted">(Note : upload only videos.supported mimes type : mp4)</small>
                                @php

                                if(!empty($questionDetail['questionhashelp'])){
                                if($questionDetail['questionhashelp']['video_type'] == '1'){
                                $video_type = '1';
                                }
                                else{
                                $video_type = '0';
                                }
                                }
                                else{
                                $video_type = '0';
                                }

                                @endphp

                                <fieldset>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="upload_video_help" id="vh_video" value="video" @if(isset($questionDetail['questionhashelp']) && $questionDetail['questionhashelp']['video_type'] == '0') checked @endif> Upload Video
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="url_help" id="vh_url" value="url" @if(isset($questionDetail['questionhashelp']) && $questionDetail['questionhashelp']['video_type'] == '1') checked @endif > URL
                                        </label>
                                    </div>
                                    <div class="video box mt-3" style='@if($video_type != '0') display:none @endif' >
                                         <input type="file" class="form-control" placeholder="Video Help" name="video_help" id="video_help_file">
                                    </div>
                                    <div class="url box mt-3" style='@if($video_type != '1') display:none @endif' >
                                         <input type="text" class="form-control form-control-solid" placeholder="Video Help" name="video_help" value="{{ (empty($questionDetail['questionhashelp'])) ? '' : ( $questionDetail['questionhashelp']['video_type'] == '1' ? $questionDetail['questionhashelp']['video'] : '') }}">
                                    </div>
                                    @if(!empty($questionDetail['questionhashelp']))
                                    @if(isset($questionDetail['questionhashelp']['video']) && $questionDetail['questionhashelp']['video'] !== '')
                                    <a href="{{ $questionDetail['questionhashelp']['video'] }}" target="_blank" class="btn btn-primary m-b-15 mt-3">Preview</a>
                                    <input type="hidden" name="video_help_status" id="videohelp" value="1">
                                    <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="videohelp" title="Click here to delete video help"> Delete </a>
                                    @endif
                                    @endif
                                </fieldset>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group mt-5 pt-5">
                                    <label for="audio_help" class="form-label">Audio help</label>  <small class="text-muted">(Note : upload only audio. supported mimes type : mp3)</small>
                                    <input type="file" class="form-control" name="audio_help" id="audio_help">
                                    @if(!empty($questionDetail['questionhashelp']) && !empty($questionDetail['questionhashelp']['audio']) )
                                    <input type="hidden" name="audio_help_status" id="audiohelp" value="1">
                                    <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="audiohelp" title="Click here to delete audio help"> Delete </a>
                                    @endif
                                </div>
                            </div>

                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="pdf_help" class="form-label">Pdf help</label>  <small class="text-muted">(Note : upload only pdf. supported mimes type : pdf)</small>
                                    <input type="file" class="form-control" name="pdf_help" id="pdf_help">
                                    @if(!empty($questionDetail['questionhashelp']) && !empty($questionDetail['questionhashelp']['pdf']) )
                                    <a href="{{ $questionDetail['questionhashelp']['pdf'] }}" target="_blank" class="btn btn-primary m-b-15 mt-3">Preview</a>
                                    <input type="hidden" name="pdf_help_status" id="pdfhelp" value="1">
                                    <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="pdfhelp" title="Click here to pdf help"> Delete </a>
                                    @endif
                                </div>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="pdf_help" class="form-label">link help</label>
                                    <input type="text" class="form-control  form-control-solid" placeholder="Link Help" name="link_help" value="{{ (empty($questionDetail['questionhashelp'])) ? '' : (  (empty($questionDetail['questionhashelp']['link'])) ? '' : $questionDetail['questionhashelp']['link'] ) }}">
                                </div>
                            </div>

                            <div class="py-3 col-sm-12">
                                <label for="image_gallary" class="form-label">Image help</label> <small id="fileHelp" class="text-muted"> (Note : upload only images)</small>
                                <div id="uploader" style="display:hidden">
                                    <input type="file" id="image_help" name="image_help[]" multiple="multiple" accept="image/*" style="display:none"/>
                                    <label class="imgchoose" for="image_help"><i class="fa fa-plus-square-o" aria-hidden="true"></i>  Choose file</label>
                                </div>
                                <div id="displayimage">
                                    @if(isset($questionDetail['quehasimghelp']) && !empty($questionDetail['quehasimghelp']) )
                                    @foreach($questionDetail['quehasimghelp'] as $img)
                                    <div class="col-md-2 col-sm-4 col-xs-12">
                                        <div class="img-fix">
                                            <img src="{{ $img->image }}" imgID = "{{ $img->id }}" class="thumb selFile">
                                            <div class='imgdelete' id="helpimgdelete"><span><i class='bi bi-trash fs-2' aria-hidden='true'></i></span></div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <input id="imghelpRemove" type="hidden" name="imghelpRemove" value="">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post2" style="display:{{ $questionDetail->question_media_type == 'scorm' ? 'none !important' : 'block' }}">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Question Hint</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="py-3 col-md-6 col-sm-12">
                                <label for="video_hint" class="form-label">Video hint</label> <small id="fileHelp" class="text-muted">(Note : upload only videos.supported mimes type : mp4)</small>
                                @php

                                if(!empty($questionDetail['questionhashint'])){
                                if($questionDetail['questionhashint']['video_type'] == '1'){
                                $video_type = '1';
                                }
                                else{
                                $video_type = '0';
                                }
                                }
                                else{
                                $video_type = '0';
                                }

                                @endphp
                                <fieldset>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="upload_video_hint" id="vh_video_hint" value="video_hint" @if(isset($questionDetail['questionhashint']) && $questionDetail['questionhashint']['video_type'] == '0') checked @endif> Upload Video
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="url_hint" id="vh_url_hint" value="url_hint" @if(isset($questionDetail['questionhashint']) && $questionDetail['questionhashint']['video_type'] == '1') checked @endif > URL
                                        </label>
                                    </div>
                                    <div class="video_hint box_hint mt-3" style='@if($video_type != '0') display:none @endif' >
                                         <input type="file" class="form-control" placeholder="Video Hint" name="video_hint" id="video_hint_file">
                                    </div>
                                    <div class="url_hint box_hint mt-3" style='@if($video_type != '1') display:none @endif' >
                                         <input type="text" class="form-control  form-control-solid" placeholder="Video Hint" name="video_hint" value="{{ (isset($questionDetail['questionhashint']) && $questionDetail['questionhashint']['video_type'] == '1') ? $questionDetail['questionhashint']['video'] : '' }}">
                                    </div>
                                    @if(!empty($questionDetail['questionhashint']))
                                    @if(isset($questionDetail['questionhashint']['video']) && $questionDetail['questionhashint']['video'] !== '')
                                    <a href="{{ $questionDetail['questionhashint']['video'] }}" target="_blank" class="btn btn-primary m-b-15 mt-3">Preview</a>
                                    <input type="hidden" name="video_hint_status" id="videohint" value="1">
                                    <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="videohint" title="Click here to video hint"> Delete </a>
                                    @endif
                                    @endif
                                </fieldset>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group  pt-5 mt-5">
                                    <label for="audio_hint" class="form-label">Audio hint</label>  <small class="text-muted">(Note : upload only audio. supported mimes type : mp3)</small>
                                    <input type="file" class="form-control" placeholder="Audio Hint" name="audio_hint" id="audio_hint">
                                    @if(!empty($questionDetail['questionhashint']) && !empty($questionDetail['questionhashint']['audio']) )
                                    <input type="hidden" name="audio_hint_status" id="audiohint" value="1">
                                    <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="audiohint" title="Click here to delete audio hint"> Delete </a>
                                    @endif
                                </div>
                            </div>

                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="pdf_hint" class="form-label">Pdf hint</label>  <small class="text-muted">(Note : upload only pdf. supported mimes type : pdf)</small>
                                    <input type="file" class="form-control" placeholder="Pdf Hint" name="pdf_hint" id="pdf_hint">
                                    @if(!empty($questionDetail['questionhashint']) && !empty($questionDetail['questionhashint']['pdf']) )
                                    <a href="{{ $questionDetail['questionhashint']['pdf'] }}" target="_blank" class="btn btn-primary m-b-15 mt-3">Preview</a>
                                    <input type="hidden" name="pdf_hint_status" id="pdfhint" value="1">
                                    <a href="javascript:void(0);" class="deleteFile btn btn-danger m-b-15 mt-3" help="pdfhint" title="Click here to delete pdf hint"> Delete </a>
                                    @endif
                                </div>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="link_hint" class="form-label">link hint</label>
                                    <input type="text" class="form-control  form-control-solid" placeholder="Link Hint" name="link_hint" value="{{ (empty($questionDetail['questionhashint'])) ? '' : (  (empty($questionDetail['questionhashint']['link'])) ? '' : $questionDetail['questionhashint']['link'] ) }}">
                                </div>
                            </div>

                            <div class="py-3 col-sm-12">
                                <label for="image_gallary" class="form-label">Image hint</label> <small id="fileHelp" class="text-muted"> (Note : upload only images)</small>
                                <div id="uploader" style="display:hidden">
                                    <input type="file" id="image_hint" name="image_hint[]" multiple="multiple" accept="image/*" style="display:none"/>
                                    <label class="imgchoose" for="image_hint"><i class="fa fa-plus-square-o" aria-hidden="true"></i>  Choose file</label>
                                </div>
                                <div class="row" id="displayimagehint">
                                    @if(isset($questionDetail['quehasimghint']) && !empty($questionDetail['quehasimghint']) )
                                    @foreach($questionDetail['quehasimghint'] as $img)

                                    <div class="col-md-2 col-sm-4 col-xs-12">
                                        <div class="img-fix">
                                            <img src="{{ $img->image }}" imgID = "{{ $img->id }}" class="thumb selFile">
                                            <div class='imgdelete' id="hintimgdelete"><span><i class='bi bi-trash fs-2' aria-hidden='true'></i></span></div>
                                        </div>
                                    </div>

                                    @endforeach
                                    @endif
                                </div>
                                <input id="imghintRemove" type="hidden" name="imghintRemove" value="">
                            </div>
                            {{-- <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post3">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="text-end">
                        <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @section('script')
    <script>

            CKEDITOR.replace('question_media_data', {
                extraPlugins: 'mathjax',
                mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML',
                height: 320,
            });
            if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                document.getElementById('ie8-warning').className = 'tip alert';
            }

            var rowCount = $("#answer_table tr").length;

            for (let i = 1; i < rowCount; i++) {
                CKEDITOR.replace('choice'+i, {
                    extraPlugins: 'mathjax',
                    mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-MML-AM_CHTML',
                    height: 320,
                });
                if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                    document.getElementById('ie8-warning').className = 'tip alert';
                }
            }

            CKEDITOR.replace('question_name',{
                extraPlugins: 'mathjax',
                mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
            });
            if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                document.getElementById('ie8-warning').className = 'tip alert';
            }

            var counter = 2;
            var storedimage_help = [];
            var storedimage_hint = [];

            $(document).ready(function () {

                $('.deleteFile').click(function () {
                    var helptype = $(this).attr('help');
                    $('#' + helptype).val('0');
                    $(this).remove();
                });

                $('input[name=question_media_type]').click(function () {
                    var type = $(this).val();
                    if (type == 'single') {
                        $('#question_media_html').hide();
                        $('#question_media_single').show();
                        $('#question_media_scorm').hide();
                        $('#kt_post1').show();
                        $('#kt_post2').show();
                        $('#question_type').show();
                        $('#question_media_single').show();
                        $('#div_correct_question_ans').show();
                    }

                    if (type == 'html') {
                        $('#question_media_single').hide();
                        $('#question_media_html').show();
                        $('#question_media_scorm').hide();
                        $('#kt_post1').show();
                        $('#kt_post2').show();
                        $('#question_type').show();
                        $('#question_media_single').show();
                        $('#div_correct_question_ans').show();
                    }
                    if (type=='scorm') {
                        $('#question_media_single').hide();
                        $('#question_media_html').hide();
                        $('#question_media_scorm').show();
                        $('#kt_post1').removeClass('d-flex');
                        $('#kt_post2').removeClass('d-flex');
                        $('#kt_post1').hide();
                        $('#kt_post2').hide();
                        $('#question_type').hide();
                        $('#question_media_single').hide();
                        $('#div_correct_question_ans').hide();
                    }
                });

                $("#question_intent").select2();

                $.validator.setDefaults({
                    ignore: ":hidden:not(select)"
                });

                /**********************Form Validation **************************/
                $("#updatequestion").validate({
                    rules: {
                        'question_name': {
                            required: true,
                        },
                        'choice1': {
                            required: true,
                        },
                        'order1': {
                            required: true,
                        },
                        'correct_question_ans': {
                            required: true,
                        },
                        'course_id': {
                            required: true,
                        },
                        'question_intent[]': {
                            required: true,
                        },
                        'status': {
                            required: true,
                        },
                        'data_help': {
                            extension: "pdf"
                        },
                        'image_help': {
                            extension: "jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF"
                        },
                        'question_media': {
                            extension: "mp4|MP4|pdf|jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF"
                        },
                        'question_level': {
                            required: true,
                        },
                        'toc_no': {
                            required: true,
                        },
                    },
                    messages: {
                        'data_help': {
                            extension: "Please upload only pdf."
                        },
                        'image_help': {
                            extension: "Please upload only image."
                        },
                        'video_help': {
                            extension: "Please upload only mp4 video."
                        },
                        'question_media': {
                            extension: "Please upload valid question media."
                        }
                    },
                    submitHandler: function (form) {
                        $(".loading").show();
                        var data = new FormData(form);

                        data.delete('image_help[]');
                        document.getElementById('image_help').value = null;
                        for (var i = 0, len = storedimage_help.length; i < len; i++) {
                            data.append('image_help[]', storedimage_help[i]);
                        }

                        data.delete('image_hint[]');
                        document.getElementById('image_hint').value = null;
                        for (var i = 0, len = storedimage_hint.length; i < len; i++) {
                            data.append('image_hint[]', storedimage_hint[i]);
                        }
                        data.delete('question_media[]');

                        var question_media_data = CKEDITOR.instances['question_media_data'].getData()
                        data.append('question_media_data', question_media_data);

                        var question_name = CKEDITOR.instances['question_name'].getData();
                        data.append('question_name', question_name);

                        var rowCount = $("#answer_table tr").length;

                        for (let i = 1; i < rowCount; i++) {
                            var choice = 'choice'+i
                            choice = CKEDITOR.instances['choice'+i].getData();
                            data.append( 'choice[]', choice);
                        }

                        var _url = '{{ route("user.my-course-question.update",$questionDetail->id) }}';
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: _url,
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response) {
                                    $(".loading").hide();

                                    swal({title: "Status!", text: "Question updated successfully.", type: "success"},
                                    function(){
                                        window.location.href = "{{ route('user.my-course-question.index') }}";
                                    });
                                }
                                $(".loading").hide();
                            },
                        }).fail(function(xhr, textStatus, errorThrown) {
                            $('.text-danger').empty();
                            $('.loading').hide();
                            var errors = "";
                            if (xhr.status == 422) {
                                if (xhr.responseJSON.errors) {
                                    $.each(xhr.responseJSON.errors, function(i, val) {
                                        errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                                    });
                                    if (errors !== "") {
                                        swal({title: "Error!", text: errors, type: "error", html: true});
                                    }
                                }
                            } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                                swal({title: "Error!", text: "Server error", type: "error", html: true});
                                return false;
                            } else {
                                swal({title: "Error!", text: "No internet Connection. please check your internet connection.", type: "error", html: true});
                                return false;
                            }
                        });
                        return false;
                    }
                });

                /*********************************For Upload Video Radio Button ************************/
                $('#vh_video').click(function () {
                    $("#video_help_file").rules("add", {
                        extension: "mp4|MP4"
                    });
                    $("#vh_url").prop("checked", false);
                    $(this).prop("checked", true);
                    $(".box").hide();
                    $(".video").show();
                });
                /*********************************For URL Radio Button ************************/
                $('#vh_url').click(function () {
                    $("#video_help_file").rules('remove');
                    $(this).prop("checked", true);
                    $("#vh_video").prop("checked", false);
                    $(".box").hide();
                    $(".url").show();
                });

                $('#vh_video_hint').click(function() {
                    $("#video_hint_file").rules("add", {
                        extension: "mp4|MP4"
                    });
                    $("#vh_url_hint").prop("checked", false);
                    $(this).prop("checked", true);
                    $(".box_hint").hide();
                    $(".video_hint").show();
                });

                $('#vh_url_hint').click(function() {
                    $("#video_hint_file").rules('remove');
                    $(this).prop("checked", true);
                    $("#vh_video_hint").prop("checked", false);
                    $(".box_hint").hide();
                    $(".url_hint").show();
                });

                /*********************************For Add Answer code ************************/

                $(".add_new_choice").click(function (e) {
                    var coundiv = $("#answer_table tr").length;
                    if (coundiv > 4) {
                        alert("Only 4 Choice allow");
                        return false;
                    }
                    var newInput = '<tr class="remove" id="remove_' + coundiv + '"><td><div class="form-group" ><textarea class="form-control form-control-solid" placeholder="Choice" id="choice' + coundiv + '"  name="choice'+coundiv+'" rows="1" cols="50"></textarea></div></td><td><div class="form-group" ><input type="number" class="form-control form-control-solid" id="order' + coundiv + '" placeholder="Order" name="order[]" min="1" max="4" value="' + coundiv + '"></div></td><td><div class="form-group"><select class="form-control form-control-solid" name="ctype[]" id="ctype' + coundiv + '"><option value="0">Normal</option><option value="1">Math</option></select></div></td><td><a href="javacript:void(0);" onclick="remove_choice(' + coundiv + '); return false; " class="btn btn-danger m-b-15"><i class="las la-trash"></i> Delete</a></td></tr>'
                    $("#answer_table").append(newInput);
                    e.preventDefault();

                    CKEDITOR.replace('choice'+coundiv,{
                        extraPlugins: 'mathjax',
                        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
                    });
                    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                        document.getElementById('ie8-warning').className = 'tip alert';
                    }

                    $("#choice" + coundiv).rules("add", {
                        required: true
                    });
                    $("#order" + coundiv).rules("add", {
                        required: true
                    });
                    coundiv++;
                });

            });

            function remove_choice(id) {
                $("#choice" + id).rules("remove");
                $("#order" + id).rules("remove");
                $("#remove_" + id).remove();
                counter--;
            }

            // image_help related data
            function upload(e) {
                if (typeof e.target === 'undefined') {
                    var filess = []
                    for (var i = 0; i < e.length; i++) {
                        filess.push(e[i]);
                    }
                    var filesArr = filess;
                }
                else {
                    var files = e.target.files;
                    var filesArr = Array.prototype.slice.call(files);
                }
                var d = storedimage_help.length;
                filesArr.forEach(function(f, index) {
                    storedimage_help.push(f);
                    var reader = new FileReader();
                    var i = storedimage_help.length - 1;
                    if (f.type.match('image')) {
                        reader.onload = function(e) {
                            var html = "<div class='col-md-2 col-sm-4 col-xs-12'><div class='img-fix'><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='thumb selFile' title='Click to remove'><div class='imgdelete' id='helpimgdelete'><span><i class='bi bi-trash fs-2'></i></span></div></div></div>";
                            $("#displayimage").append(html);
                        }
                        reader.readAsDataURL(f);
                    }
                });
            }

            $(document).on("click","#helpimgdelete span",function() {

                if ($(this).parent().prev('img').attr("imgID")) {
                    appendimghelpdelet($(this).parent().prev('img').attr("imgID"));
                }

                var file = $(this).prev('img').data("file");
                for (var i = 0; i < storedimage_help.length; i++) {
                    if (storedimage_help[i].name === file) {
                        storedimage_help.splice(i, 1);
                        break;
                    }
                }
                $(this).parent().parent().remove();
            });

            function appendimghelpdelet(ids){
                var resultObj = $("#imghelpRemove");
                var outputObj = ids;
                var stringToAppend = resultObj.val().length > 0 ? resultObj.val() + "," : "";
                resultObj.val(stringToAppend + outputObj);
            }
            function handleFileSelect(evt) {
                var files = evt.target.files;
                upload(evt, 'displayimage');
            }
            document.getElementById('image_help').addEventListener('change', handleFileSelect, false);

        // EOF image_help related data
        // image_hint related data
            function upload_hint(e) {
                if (typeof e.target === 'undefined') {
                    var filess = []
                    for (var i = 0; i < e.length; i++) {
                        filess.push(e[i]);
                    }
                    var filesArr = filess;
                }
                else {
                    var files = e.target.files;
                    var filesArr = Array.prototype.slice.call(files);
                }
                var d = storedimage_hint.length;
                filesArr.forEach(function(f, index) {
                    storedimage_hint.push(f);
                    var reader = new FileReader();
                    var i = storedimage_hint.length - 1;
                    if (f.type.match('image')) {
                        reader.onload = function(e) {
                            var html = "<div class='col-md-2 col-sm-4 col-xs-12'><div class='img-fix'><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='thumb selFile' title='Click to remove'><div class='imgdelete' id='hintimgdelete'><span><i class='bi bi-trash fs-2'></i></span></div></div></div>";
                            $("#displayimagehint").append(html);
                        }
                        reader.readAsDataURL(f);
                    }
                });
            }

            $(document).on("click","#hintimgdelete span",function() {
                if ($(this).parent().prev('img').attr("imgID")) {
                    appendimghintdelet($(this).parent().prev('img').attr("imgID"));
                }
                var file = $(this).prev('img').data("file");
                for (var i = 0; i < storedimage_hint.length; i++) {
                    if (storedimage_hint[i].name === file) {
                        storedimage_hint.splice(i, 1);
                        break;
                    }
                }
                $(this).parent().parent().remove();
            });

            function appendimghintdelet(ids){
                var resultObj = $("#imghintRemove");
                var outputObj = ids;
                var stringToAppend = resultObj.val().length > 0 ? resultObj.val() + "," : "";
                resultObj.val(stringToAppend + outputObj);
            }

            function handleFileSelecthint(evt) {
                var files = evt.target.files;
                upload_hint(evt, 'displayimagehint');
            }

            document.getElementById('image_hint').addEventListener('change', handleFileSelecthint, false);
            // EOF image_hint related data

        </script>
    @endsection
    @stop
</x-layout-font-dashboard-base>
