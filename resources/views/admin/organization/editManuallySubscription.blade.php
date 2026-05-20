<div class="modal fade" id="kt_modal_edit_subscription" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25 mw-lg-25 mw-md-25">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Subscription</h2>
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
                        <form class="kt-form" name="editsubscription" id="editsubscription" method="POST" >
                            @csrf
                            <div class="row">
                                
                                <input type="hidden" name="id" value="{{ encrypt($data->id) }}">

                                <div class="py-3 col-md-12">
                                    <label for="no licence" class="required form-label">No. of Licence</label>
                                    <input type="number"  name="licence" id="licence" class="form-control form-control-solid" value="{{$data->no_of_licence}}" placeholder="Enter no of licence" min="1" required="required" />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="no licence" class="required form-label">Expire date</label>
                                    <input type="date"  name="licence_expire" id="licence_expire" class="form-control form-control-solid" value="{{ date('Y-m-d', strtotime($data->sub_expire_date))}}" required="required" />
                                </div>

                                <div class="py-3">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $('#kt_modal_edit_subscription').modal('show');
        $('#kt_modal_edit_subscription').on('hidden.bs.modal', function() {
            $("#kt_modal_edit_subscription").remove();
            $("#kt_modal_view_subscribecourse").show();
        });

        $(document).ready(function () {
            $("#editsubscription").validate({
                rules: {
                    licence: "required",
                    licence_expire: "required",
                }, submitHandler: function (form) {
                    var _url = '{{route("orgupdatemanualsubscription")}}';
                    var data = new FormData(form);
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: _url,
                        type: 'POST',
                        contentType: false,
                        data: data,
                        processData: false,
                        cache: false,
                        success: function(response) {
                            $('#kt_modal_edit_subscription').modal('hide');
                            $('#kt_modal_edit_subscription').on('hidden.bs.modal', function() {
                                $("#kt_modal_edit_subscription").remove();
                                $("#kt_modal_view_subscribecourse").show();
                            });
                            $('.loading').hide();
                            swal({title: "Status!", text: response.message , type: "success"});
                        }
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