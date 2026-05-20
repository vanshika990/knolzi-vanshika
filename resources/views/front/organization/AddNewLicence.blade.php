<div class="modal fade" id="kt_modal_org_course_user_create" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add User</h2>
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
                        <form class="kt-form" action="{{ route('org-course-add-licence-post') }}" name="addNewLicense" id="addNewLicense" method="POST">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="course_sub_id" id="course_sub_id" value="{{ $course_sub_id }}">
                                    <input type="hidden" name="course_id" id="course_id" value="{{ $course_id }}">

                                        <div class="py-3 col-xxl-6">
                                            <label for="user_id" class="required form-label">User Name</label>
                                            <select class="form-control" name="user_id" id="user_id" >
                                                @foreach($user_data as $data)
                                                <option value="{{ $data['user']->id }}">{{ $data['user']->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        </div>
                                        <div class="py-3">
                                            <div class="mb-10 float-end">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                        </form>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>

                                        <script type="text/javascript">

                                            $('#kt_modal_org_course_user_create').modal('show');
                                            $('#kt_modal_org_course_user_create').on('hidden.bs.modal', function() {
                                                $("#kt_modal_org_course_user_create").remove();
                                                $("#kt_modal_view_course_User").show();
                                            });

                                            $(document).ready(function() {
                                                $("#select_course").chosen({width: "100%"});
                                                $("#addNewLicense").validate({
                                                    rules: {
                                                        user_id: "required",
                                                    }, submitHandler: function(form) {
                                                        $(".loading").show();

                                                        var data = new FormData(form);
                                                        var _url = '{{ route("org-course-add-licence-post") }}';

                                                        $.ajax({
                                                            url: _url,
                                                            type: 'POST',
                                                            contentType: false,
                                                            data: data,
                                                            processData: false,
                                                            cache: false,
                                                            success: function(response) {
                                                                $('#kt_modal_org_course_user_create').remove();
                                                                $("#kt_modal_view_course_User").show();
                                                                $('#org-subscribe-course-user-table').DataTable().ajax.reload();
                                                                $('span#total').html(response.total);
                                                                $('span#used').html(response.used);
                                                                $('span#remain').html(response.remain);

                                                                swal({
                                                                    title: "Status!",
                                                                    text: response.message,
                                                                    type: "success"
                                                                });

                                                                $('.loading').hide();

                                                            }
                                                        }).fail(function(xhr, textStatus, errorThrown) {
                                                            $('#kt_modal_org_course_user_create').remove();
                                                            $("#kt_modal_view_course_User").show();
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
