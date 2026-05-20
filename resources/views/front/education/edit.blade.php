<div class="modal fade" id="kt_modal_edit_education" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Education</h2>
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
                <div class="card">
                    <form class="kt-form" action="{{route('update-edu-qua')}}" id="updateeducation" name="updateeducation" method="POST">
                    @csrf
                        <div class="card-body p-3">
                            <div class="row">
                                @php $id = encrypt($education->id) @endphp
                                <input type="hidden" name="id" value="{{$id}}">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="degree" class="form-label">Education Background</label>
                                    <input type="text" class="form-control" id="degree" name="degree" value="{{$education->degree}}" placeholder="Your Education Background" required>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="university" class="form-label">University</label>
                                        <input type="text" class="form-control" id="university" name="university" value="{{$education->university}}" placeholder="Your University" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="institute" class="form-label">Institute</label>
                                        <input type="text" class="form-control" id="institute" name="institute" value="{{$education->institute}}" placeholder="Institute" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="stream" class="form-label">Stream</label>
                                        <input type="text" class="form-control" id="stream" name="stream" value="{{$education->stream}}" placeholder="Your stream" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="year" class="form-label">Year</label>
                                        <input type="number" class="form-control" id="year" name="year" maxlength="4" value="{{$education->year}}" placeholder="Your Education Year" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="grade" class="form-label">Grade</label>
                                        <input type="number" class="form-control" id="grade" name="grade" value="{{$education->grade}}" placeholder="Your Grade" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="py-3">
                                        <div class="mb-10 text-end">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $('#kt_modal_edit_education').modal('show');
        $('#kt_modal_edit_education').on('hidden.bs.modal', function() {
            $(".modal").remove();
        });

        $(document).ready(function() {
            $("#updateeducation").validate({
                rules: {
                    'degree': {
                        required: true,
                    }
                    , 'university': {
                        required: true,
                    }
                    , 'institute': {
                        required: true,
                    }
                    , 'stream': {
                        required: true,
                    }
                    , 'year': {
                        required: true,
                    }
                    , 'grade': {
                        required: true,
                    }
                }, submitHandler: function(form) {
                    var _url = '{{route("update-edu-qua")}}';
                    var data = new FormData(form);
                    
                    $.ajax({
                        url: _url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {
            
                            $(".modal").remove();

                            if (response) {
                                swal({title: "Status!", text: response.message, type: "success"},
                                function(){
                                    location.reload();
                                });
                            }
                        },
                    }).fail(function(xhr, textStatus, errorThrown) {
                        $('.text-danger').empty();
                        var errors = "";
                        if (xhr.status == 422) {
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(i, val) {
                                    errors += "<b><p>" + val[0] + "</p></b>";
                                });
                                if (errors !== "") {
                                    var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="toast-error" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + errors + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                                    $('body').append(toast);
                                    new bootstrap.Toast(document.querySelector('#toast-error')).show();
                                    $('#fileInput').val('');
                                    return false;
                                }
                            }
                        } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                            var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="toast-error" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body"> Server error </div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                            $('body').append(toast);
                            new bootstrap.Toast(document.querySelector('#toast-error')).show();
                            return false;
                        } else {
                            var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="toast-error" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body"> No internet Connection. please check your internet connection. </div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                            $('body').append(toast);
                            new bootstrap.Toast(document.querySelector('#toast-error')).show();
                            return false;
                        }
                    });
                    return false;
                }
            });
        });

    </script>
</div>
