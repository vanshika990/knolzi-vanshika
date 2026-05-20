<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Question</small>
                </h1>
            </div>
        </div>
    </div>
    <form class="kt-form" action="{{ route('admin.question.store') }}" name="createquestion" id="createquestion" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Add Question</span>
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="py-3 col-md-12">
                                <label for="question_name" class="required form-label">Question name</label>
                                <textarea name="question_name" id="question_name" cols="30" rows="10"></textarea>
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="question_level" class="required form-label" >Question Level</label>
                                <input type="text" class="form-control form-control-solid" name="question_level" value="{{ old('question_level') }}" id="question_level" placeholder="Question Level">
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="toc_no" class="required form-label"> TOC No</label>
                                <input type="text" class="form-control form-control-solid" name="toc_no" value="{{ old('toc_no') }}" id="toc_no" placeholder="TOC No">
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="toc_text" class="form-label"> TOC Text</label>
                                <textarea name="toc_text" id="toc_text" class="form-control form-control-solid" cols="75"  placeholder="TOC Text" rows="1"></textarea>
                            </div>

                            <div class="py-3 col-md-6">
                                <fieldset class="form-group">
                                    <label for="question_media_type" class="required form-label d-block">Question Media</label>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_media_type" id="single_media" value="single" checked="checked"> Single
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_media_type" id="html" value="html" > Html
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_media_type" id="scorm" value="scorm" > Upload Scrom Zip
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="py-3 col-md-6" id="question_type">
                                <fieldset class="form-group">
                                    <label for="que_type" class="required form-label d-block">Question type</label>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_type" id="radio" value="single"> Single
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_type" id="multi" value="multi"> Multi
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="question_type" id="user_input" value="user_input" > User input
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="py-3 col-md-6" id="question_media_scorm" style="display:none">
                                        <div class="form-group">
                                            <label for="image_help" class="required form-label d-block">Scorm zip upload</label>  <small id="scorm_zip" class="text-muted question_media_note">(Note : upload only scorm zip.)</small>
                                            <input type="file" class="form-control  form-control-solid" name="scorm_zip" id="scorm_zip">
                                        </div>
                            </div>
                            <div class="py-3 col-md-12" id="question_media_html" style="display:none">
                                <label class="form-label" for="">Question Media</label>
                                <textarea name="question_media" id="question_media_data" cols="30" rows="10"></textarea>
                            </div>
                            <div class="py-3 col-md-12" id="question_media_single">
                                <div class="form-group">
                                    <label for="question_media" class="form-label">Question Media</label>
                                    <small id="fileHelp" class="text-muted question_media_note"> (Note : upload only mp4,pdf,jpeg,jpg,png,gif)</small>
                                    <input type="file" class="form-control" name="question_media" id="question_media">
                                </div>
                            </div>
                            <div class="py-3 col-md-12 col-sm-6">
                                <div class="form-group">
                                    <label for="course_id" class="required form-label">Select Course</label>
                                    <select class="form-select form-select-solid select-box" name="course_id" id="course_id">
                                        @foreach($courseData as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="py-3 pb-0">
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
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <textarea class="form-control form-control-solid" id="choice1" placeholder="Choice" name="choice1" rows="1" cols="50"></textarea>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number" class="form-control form-control-solid" placeholder="Order" name="order[]" value="{{ old('order1') ? old('order1') : '1' }}"  min="1" max="4">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select class="form-select form-select-solid" name="ctype[]" id="ctype">
                                                                <option value="0">Normal</option>
                                                                <option value="1">Math</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="javacript:void(0);" class="add_new_choice btn btn-primary m-b-15"><i class="las la-plus"></i> Add</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="py-3 choosn-sle col-md-6">
                                    <label for="question intent" class="required form-label">Question Intent</label>
                                    <select multiple class="form-select form-select-solid select-box" name="question_intent[]" id="question_intent" >
                                        @foreach($intentDetail as $intent)
                                        <option value="{{ $intent->id }}">{{ $intent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="py-3 col-md-6" id="div_correct_question_ans">
                                    <div class="form-group">
                                        <label for="correct_question_ans" class="required form-label">Correct answer</label>
                                        <input type="number" class="form-control form-control-solid" name="correct_question_ans" id="correct_question_ans" placeholder="Correct answer"  value="{{ old('correct_question_ans') }}" min="1" max="4">
                                    </div>
                                </div>
                                <div class="py-1 col-md-12 col-sm-12 col-sm-12">
                                    <label for="status" class="form-label d-block">Status</label>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="status" value="1" checked> Active
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="status" value="0"> Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="post d-flex flex-column-fluid" id="kt_post1">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Question help</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="py-3 col-md-6 col-sm-12">
                                <label for="video_help" class="form-label">Video help</label> <small id="fileHelp" class="text-muted">(Note : upload only videos.supported mimes type : mp4)</small>
                                <fieldset>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="upload_video_help" id="vh_video" value="video" checked> Upload Video
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="url_help" id="vh_url" value="url"> URL
                                        </label>
                                    </div>
                                    <div class="video box mt-3" style='{{ old('url_help')=="url" ? 'display:'.''.'none'.'' : 'display:'.''.'block'.'' }}'>
                                        <input type="file" class="form-control" name="video_help" placeholder="Video Help" id="video_help_file">
                                    </div>
                                    <div class="url box mt-3" style='{{ old('url_help')=="url" ? 'display:'.''.'block'.'' : 'display:'.''.'none'.'' }}'>
                                        <input type="text" class="form-control  form-control-solid" placeholder="Video Help" name="video_help" value="{{ old('video_help') }}">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group mt-5 pt-5">
                                    <label for="audio_help" class="form-label">Audio help</label>  <small id="audiohelp" class="text-muted">(Note : upload only audio. supported mimes type : mp3)</small>
                                    <input type="file" class="form-control" name="audio_help" id="audio_help">
                                </div>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="pdf_help" class="form-label">Pdf help</label>  <small id="pdfhelp" class="text-muted">(Note : upload only pdf. supported mimes type : pdf)</small>
                                    <input type="file" class="form-control" name="pdf_help" id="pdf_help">
                                </div>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="pdf_help" class="form-label">link help</label>
                                    <input type="text" class="form-control  form-control-solid" name="link_help" placeholder="Link Help" value="{{ old('link_help') }}">
                                </div>
                            </div>

                            <div class="py-3 col-sm-12">
                                <label for="image_gallary" class="form-label">Image help</label> <small id="fileHelp" class="text-muted"> (Note : upload only images)</small>
                                <div id="uploader" style="display:hidden">
                                    <input type="file" id="image_help" name="image_help[]" multiple="multiple" accept="image/*" style="display:none"/>
                                    <label class="imgchoose" for="image_help"><i class="fa fa-plus-square-o" aria-hidden="true"></i>  Choose file</label>
                                </div>
                                <div id="displayimage"></div>
                                <span class="text-danger imageGallay-feedback"></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="post d-flex flex-column-fluid" id="kt_post2">
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
                                <fieldset>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="upload_video_hint" id="vh_video_hint" value="video_hint" checked> Upload Video
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="url_hint" id="vh_url_hint" value="url_hint"> URL
                                        </label>
                                    </div>
                                    <div class="video_hint box_hint mt-3" style='{{ old('url_hint')=="url_hint" ? 'display:'.''.'none'.'' : 'display:'.''.'block'.'' }}'>
                                        <input type="file" class="form-control" name="video_hint" id="video_hint_file">
                                    </div>
                                    <div class="url_hint box_hint mt-3" style='{{ old('url_hint')=="url_hint" ? 'display:'.''.'block'.'' : 'display:'.''.'none'.'' }}'>
                                        <input type="text" class="form-control form-control-solid" placeholder="Video Hint" name="video_hint" value="{{ old('video_hint') }}">
                                    </div>
                                </fieldset>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group mt-5 pt-5">
                                    <label for="audio_hint" class="form-label">Audio hint</label>  <small id="audiohint" class="text-muted">(Note : upload only audio. supported mimes type : mp3)</small>
                                    <input type="file" class="form-control" name="audio_hint" id="audio_hint">
                                </div>
                            </div>
                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="pdf_hint" class="form-label">Pdf hint</label>  <small id="pdfhint" class="text-muted">(Note : upload only pdf. supported mimes type : pdf)</small>
                                    <input type="file" class="form-control" name="pdf_hint" id="pdf_hint">
                                </div>
                            </div>

                            <div class="py-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="link_hint" class="form-label">link hint</label>
                                    <input type="text" class="form-control  form-control-solid" name="link_hint" placeholder="Link Help" value="{{ old('link_hint') }}">
                                </div>
                            </div>
                            <div class="py-3 col-sm-12">
                                <label for="image_gallary" class="form-label">Image hint</label> <small id="fileHelp" class="text-muted"> (Note : upload only images)</small>
                                <div id="uploader" style="display:hidden">
                                    <input type="file" id="image_hint" name="image_hint[]" multiple="multiple" accept="image/*" style="display:none"/>
                                    <label class="imgchoose" for="image_hint"><i class="fa fa-plus-square-o" aria-hidden="true"></i>  Choose file</label>
                                </div>
                                <div class="row" id="displayimagehint">

                                </div>
                                <span class="text-danger imageGallay-feedback"></span>
                            </div>

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
        var counter = 2;
        var storedimage_help = [];
        var storedimage_hint = [];


        $('#displayimage').on(
                'dragover',
                function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
        )
        $('#displayimage').on(
                'dragenter',
                function(e) {
                    //alert();
                    e.preventDefault();
                    e.stopPropagation();
                }
        )
        $('#displayimage').on(
                'drop',
                function(e) {
                    if (e.originalEvent.dataTransfer) {
                        if (e.originalEvent.dataTransfer.files.length) {
                            e.preventDefault();
                            e.stopPropagation();
                            upload(e.originalEvent.dataTransfer.files);
                        }
                    }
                }
        );

        $('#displayimagehint').on(
                'dragover',
                function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
        )
        $('#displayimagehint').on(
                'dragenter',
                function(e) {
                    //alert();
                    e.preventDefault();
                    e.stopPropagation();
                }
        )
        $('#displayimagehint').on(
                'drop',
                function(e) {
                    if (e.originalEvent.dataTransfer) {
                        if (e.originalEvent.dataTransfer.files.length) {
                            e.preventDefault();
                            e.stopPropagation();
                            upload_hint(e.originalEvent.dataTransfer.files);
                        }
                    }
                }
        );

        $(document).ready(function() {
            CKEDITOR.replace('question_media_data', {
                extraPlugins: 'mathjax',
                mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
                height: 320,
                filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form',
            });
            if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                document.getElementById('ie8-warning').className = 'tip alert';
            }

            CKEDITOR.replace('choice1', {
                extraPlugins: 'mathjax',
                mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
            });
            if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                document.getElementById('ie8-warning').className = 'tip alert';
            }

            CKEDITOR.replace('question_name', {
                extraPlugins: 'mathjax',
                mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
            });
            if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                document.getElementById('ie8-warning').className = 'tip alert';
            }

            $("#question_intent").select2({
                placeholder: "Select Question Intent",
            });

            $('input[name=question_media_type]').click(function() {

                var type = $(this).val();
                if (type == 'single') {
                    $('#question_media_html').hide();
                    $('#question_media_single').show();
                    $('#kt_post1').show();
                    $('#kt_post2').show();
                    //$('#question_type').show();
                    $('#question_media_single').show();
                    //$('#div_correct_question_ans').show();
                    $('#question_media_scorm').hide();
                }
                if (type == 'html') {
                    $('#question_media_single').hide();
                    $('#question_media_html').show();
                    $('#kt_post1').show();
                    $('#kt_post2').show();
                    //$('#question_type').show();
                    $('#question_media_single').show();
                    //$('#div_correct_question_ans').show();
                    $('#question_media_scorm').hide();
                }
                if (type == 'scorm') {
                    $('#question_media_single').hide();
                    $('#question_media_html').hide();
                    $('#question_media_scorm').show();
                    $('#kt_post1').removeClass('d-flex');
                    $('#kt_post2').removeClass('d-flex');
                    $('#kt_post1').hide();
                    $('#kt_post2').hide();
                    //$('#question_type').hide();
                    $('#question_media_single').hide();
                    //$('#div_correct_question_ans').hide();
                }
            });

            $('#vh_video').click(function() {
                $("#video_help_file").rules("add", {
                    extension: "mp4|MP4"
                });
                $("#vh_url").prop("checked", false);
                $(this).prop("checked", true);
                $(".box").hide();
                $(".video").show();
            });

            $('#vh_url').click(function() {
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

            $(".add_new_choice").click(function(e) {
                if (counter > 4) {
                    alert("Only 4 Choice allow");
                    return false;
                }
                var newInput = '<tr id="remove_' + counter + '"><td><div class="form-group" ><textarea class="form-control form-control-solid" placeholder="Choice" id="choice' + counter + '"  name="choice' + counter + '" rows="1" cols="50"></textarea></div></td><td><div class="form-group" ><input type="number" class="form-control form-control-solid" id="order' + counter + '" placeholder="Order" name="order[]" min="1" max="4" value="' + counter + '"></div></td><td><div class="form-group"><select class="form-select form-select-solid " name="ctype[]" id="choice_type"><option value="0">Normal</option><option value="1">Math</option></select></div></td><td><a href="javacript:void(0);" onclick="remove_choice(' + counter + '); return false;" class="btn btn-danger m-b-15"><i class="las la-trash"></i> Delete</a></td></tr>'
                $("#answer_table").append(newInput);
                e.preventDefault();

                CKEDITOR.replace('choice' + counter, {
                    extraPlugins: 'mathjax',
                    mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
                });
                if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
                    document.getElementById('ie8-warning').className = 'tip alert';
                }

                $("#choice" + counter).rules("add", {
                    required: true
                });
                $("#order" + counter).rules("add", {
                    required: true
                });

                counter++;
            });

        });

        // remove choice
        function remove_choice(id) {
            $("#choice" + id).rules("remove");
            $("#order" + id).rules("remove");
            $("#remove_" + id).remove();
            counter--;
        }

        // help_image add remove start
        function upload(e, target_id) {
            var filess = []
            if (typeof e.target === 'undefined') {
                for (var i = 0; i < e.length; i++) {
                    filess.push(e[i]);
                }
                var filesArr = filess;
            }
            else {
                var files = e.target.files;
                var filesArr = Array.prototype.slice.call(files);
                var d = storedimage_help.length;
                filesArr.forEach(function(f, index) {
                    storedimage_help.push(f);
                    var reader = new FileReader();
                    var i = storedimage_help.length - 1;
                    if (f.type.match('image')) {
                        reader.onload = function(e) {
                            var html = "<div class='col-md-2 col-sm-4 col-xs-12'><div class='img-fix'><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='thumb selFile' title='Click to remove'><div class='imgdelete' id='helpimgdelete'><span><i class='bi bi-trash fs-2'></i></span></div></div></div>";
                            $("#" + target_id).append(html);
                        }
                        reader.readAsDataURL(f);
                    }
                });
            }
        }

        $(document).on("click","#helpimgdelete span",function() {
            var file = $(this).prev('img').data("file");
            for (var i = 0; i < storedimage_help.length; i++) {
                if (storedimage_help[i].name === file) {
                    storedimage_help.splice(i, 1);
                    break;
                }
            }
            $(this).parent().parent().remove();
        });

        function handleFileSelect(evt) {
            var files = evt.target.files;
            upload(evt, 'displayimage');
        }
        document.getElementById('image_help').addEventListener('change', handleFileSelect, false);

        // help_image add remove end

        // hint_image add remove start
        function upload_hint(e, target_id) {
            var filess = []
            if (typeof e.target === 'undefined') {
                for (var i = 0; i < e.length; i++) {
                    filess.push(e[i]);
                }
                var filesArr = filess;
            }
            else {
                var files = e.target.files;
                var filesArr = Array.prototype.slice.call(files);
                var d = storedimage_hint.length;
                filesArr.forEach(function(f, index) {
                    storedimage_hint.push(f);
                    var reader = new FileReader();
                    var i = storedimage_hint.length - 1;
                    if (f.type.match('image')) {
                        reader.onload = function(e) {
                            var html = "<div class='col-md-2 col-sm-4 col-xs-12'><div class='img-fix'><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='thumb selFile' title='Click to remove'><div class='imgdelete' id='hintimgdelete'><span><i class='bi bi-trash fs-2'></i></span></div></div></div>";
                            $("#" + target_id).append(html);
                        }
                        reader.readAsDataURL(f);
                    }
                });
            }
        }

        $(document).on("click","#hintimgdelete span",function() {
            var file = $(this).prev('img').data("file");
            for (var i = 0; i < storedimage_hint.length; i++) {
                if (storedimage_hint[i].name === file) {
                    storedimage_hint.splice(i, 1);
                    break;
                }
            }
            $(this).parent().parent().remove();
        });

        function handleFileSelecthint(evt) {
            var files = evt.target.files;
            upload_hint(evt, 'displayimagehint');
        }
        document.getElementById('image_hint').addEventListener('change', handleFileSelecthint, false);

        // help_image add remove end
        $(document).ready(function() {
            $("#createquestion").validate({
                rules: {
                    question_name: {
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
                    'scorm_zip': {
                        extension: "zip"
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
                    'question_type': {
                        required: true,
                    }
                },
                messages: {
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
                submitHandler: function(form) {
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
                    var question_media_data = CKEDITOR.instances['question_media_data'].getData();

                    data.append('question_media_data', question_media_data);

                    var question_name = CKEDITOR.instances['question_name'].getData();
                    data.append('question_name', question_name);

                    var rowCount = $("#answer_table tr").length;
                    for (let i = 1; i < rowCount; i++) {
                        choices = CKEDITOR.instances['choice' + i].getData();
                        data.append('choice[]', choices);
                    }

                    var _url = '{{ route("admin.question.store") }}';
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
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response) {
                                $(".loading").hide();
                                swal({title: "Status!", text: response.message, type: "success"},
                                function() {
                                    window.location.href = "{{ route('admin.question.index') }}";
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
        });
    </script>
    @endsection
    @stop
</x-layout-admin-base>
