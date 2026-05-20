<div class="modal fade" id="kt_modal_view_course_User" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Assign Course to User</h5>
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
                <div class="row" id="LicenceCountDiv">
                    
                    <div class="col-lg-4">
                        <div class="bg-light-danger px-3 py-3 rounded-2 mb-3">
                            <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2" id="total">
                                {{ $data['total_licence'] }}
                            </span>
                            <a href="#" class="text-danger fw-bold fs-6">Total Licence</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="bg-light-success  px-3 py-3 rounded-2 mb-3">
                            <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2" id="used">
                                {{ $data['used_licence'] }}
                            </span>
                            <a href="#" class="text-success fw-bold fs-6">Used Licence</a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="bg-light-warning px-3 py-3 rounded-2 mb-3">
                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2" id="remain">
                                {{ $data['remaining_licence'] }}
                            </span>
                            <a href="#" class="text-warning fw-bold fs-6">Remaining Licence</a>
                        </div>
                    </div>
                                                        
                </div>
                <div class="card mb-xl-8">
                    <div class="card-header border-0">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1"></span>
                        </h3>
                        @can('add-licence-org')

                            <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="" data-bs-original-title="Click to add a User">
                                <a href="javascript:void(0)" onclick="AddNewLicence()" class="btn btn-sm btn-primary">
                                    <span class="fas fa-user-plus"></span> Add New Licence
                                </a>
                            </div>

                        @endcan
                    </div>
                    <div class="card-body p-3">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ $dataTable->scripts() }}
    <script type="text/javascript">
        $('#kt_modal_view_course_User').modal('show');
        $('#kt_modal_view_course_User').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });

        function AddNewLicence(){
            
            var course_id = {!! json_encode($data['course_id'], JSON_HEX_TAG) !!};
            $('#kt_modal_view_course_User').hide();
            var _url = '{{ route("org-course-add-licence", ":id") }}';
            _url = _url.replace(':id', course_id);

            var remaining_licence = $('span#remain').text().trim();
            
            if(remaining_licence != '0'){
                GetPopupCallAjax(_url);
            }
            else{
                swal({
                    title: "Error!",
                    text: "You don't have Licence to add user",
                    type: "error"
                });
                $("#kt_modal_view_course_User").show();
            }
            
        }

        function ViewUserDetail(identifier) {
            $(".loading").show();
            var id = $(identifier).data('id');
            $("#kt_modal_view_course_User").hide();
            var _url = '{{ route("view-user-detail", ":id") }}';
            _url = _url.replace(':id', id);
            GetPopupCallAjax(_url);
        }

        function DeleteCourseUser(identifier){
            swal({
                title: 'Are you sure you want to Delete this User?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, do it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {

                    var id = $(identifier).data('id');
                    var _url = '{{ route("org-course-remove-licence", ":id") }}';
                    _url = _url.replace(':id', id);
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: _url,
                        data:{'id':id},
                        type: 'POST',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response) {
                                
                                $(".loading").hide();
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
                    

                } else {
                    swal("Cancelled", "cancelled.", "error");
                }
            });
        }

    </script>
</div>