<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('dashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Course completed list</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-2 pt-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Course completed list</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_send_mail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Send completion certificate</h2>
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
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body p-3">
                        <form class="kt-form" action="{{ route('sendcertificatemail') }}" name="completecourse" id="completecourse" method="POST" >
                            @csrf
                            <div class="row">
                                <input type="hidden" name="user_id" id="user_id">
                                <input type="hidden" name="course_id" id="course_id">
                                <div class="py-3 col-xxl-12">
                                    <label for="email" class="required form-label">Email</label>
                                    <input type="email" name="email" class="form-control form-control-solid" id="email" placeholder="Email Address"  required="required">
                                </div>
                                <div class="py-3 col-xxl-12">
                                    <label for="certificate" class="required form-label">Upload file</label>
                                    <input type="file" class="form-control form-control-solid" name="certificate" id="certificate" required>
                                </div>
                            </div>
                            
                            <div class="py-3">
                                <div class="mb-10 float-end">
                                    <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @section('script')
    {{ $dataTable->scripts() }}

    <script type="text/javascript">
        
        function uploaddata(identifier) {
            var email = $(identifier).data('email_id');
            var uid = $(identifier).data('uid');
            var cid= $(identifier).data('cid');

            if (email !== null && email !== "") {
                $('#email').val(email);
            }
            $('#user_id').val(uid);
            $('#course_id').val(cid);
            $('#kt_modal_send_mail').modal('show');
        }

        $(document).ready(function () {
            $("#completecourse").validate({
                rules: {
                    email: "required",
                    certificate: "required",
                }, submitHandler: function (form) {

                    $('.loading').show();

                    var data = new FormData(form);
                    var _url = '{{ route("sendcertificatemail") }}';

                    $.ajax({
                        url: _url,
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $(".loading").hide();
                            $(".modal,.fade.show").remove();
                            $('.data-table').DataTable().ajax.reload();
                            swal({ title: "Status!", text: response.message, type: "success" });
                        },
                    }).fail(function(xhr, textStatus, errorThrown) {
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
