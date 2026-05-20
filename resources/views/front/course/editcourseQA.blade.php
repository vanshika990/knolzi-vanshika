<div class="modal fade" id="kt_modal_edit_course_QA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Edit Course QA</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                                <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
                                <rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="card mb-xl-8">
                    <div class="card-body p-3">
                        <form class="kt-form" action="{{route('course.qa.update')}}" id="updateCourseQA" name="updateCourseQA" method="POST">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{ encrypt($QA_data->id) }}">
                                <div class="py-3 col-xxl-12">
                                    <label for="question" class="form-label">Question</label>
                                    <input type="text" name="question" class="form-control form-control-solid" placeholder="Question" value="{{ $QA_data->question_name }}" id="question" readonly>
                                </div>
                                <div class="py-3 col-xxl-12">
                                    <label for="answer" class="required form-label">Answer</label>
                                    <textarea class="form-control form-control-solid" id="answer" name="answer" rows="5" cols="4">{{ $QA_data->answer }}</textarea>
                                </div>
                            </div>
                            <div class="py-3">
                                <label for="status" class="required form-label d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="1" {{ $QA_data->status == '1' ? 'checked' : ''}} />
                                    <label class="form-check-label" for="inlineRadio1">Approve</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="2" {{ $QA_data->status == '2' ? 'checked' : ''}} />
                                    <label class="form-check-label" for="inlineRadio1">Reject</label>
                                </div>
                            </div>
                            <div class="mb-10 float-end">
                                <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">

        $('#kt_modal_edit_course_QA').modal('show');
        $('#kt_modal_edit_course_QA').on('hidden.bs.modal', function () {
            $("#kt_modal_edit_course_QA").remove();
            $("#kt_modal_view_course_QA").show();
        });

        $(document).ready(function () {
            $("#updateCourseQA").validate({
                rules: {
                    answer: {
                        required:true,
                    },
                    status: {
                        required: true,
                    }
                }, submitHandler: function (form) {
                    var _url = '{{route("course.qa.update")}}';
                    var data = new FormData(form);

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
                                $("#kt_modal_edit_course_QA").remove();
                                $("#kt_modal_view_course_QA").show();
                                $('#get-course-qa-table').DataTable().ajax.reload();
                                swal({
                                    title: "Status!",
                                    text: response.message,
                                    type: "success"
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
</div>
