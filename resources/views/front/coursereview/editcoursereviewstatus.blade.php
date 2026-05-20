<div class="modal fade" id="kt_modal_edit_review_status" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Edit Status</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                fill="#000000">
                                <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
                                <rect fill="#000000" opacity="0.5"
                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                    x="0" y="7" width="16" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="card mb-xl-8">
                    <div class="card-body p-3">
                        <form class="kt-form" id="updatereviewstatus" name="updatereviewstatus">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ encrypt($review_data->id) }}">
                            <div class="py-3">
                                <div class="form-check form-check-inline ">
                                    <input class="form-check-input" type="radio" name="status" value="0"
                                        id="flexRadioChecked-0" {{ $review_data->status == '0' ? 'checked' : '' }} />
                                    <label class="form-check-label" for="flexRadioChecked-0"> Pending </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="1"
                                        id="flexRadioChecked-1" {{ $review_data->status == '1' ? 'checked' : '' }} />
                                    <label class="form-check-label" for="flexRadioChecked-1"> Approve </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="2"
                                        id="flexRadioChecked-2" {{ $review_data->status == '2' ? 'checked' : '' }} />
                                    <label class="form-check-label" for="flexRadioChecked-2"> Reject </label>
                                </div>
                            </div>
                            <div class="mb-10 float-end">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#kt_modal_view_getcoursereview").hide();
    $('#kt_modal_edit_review_status').modal('show');
    $('#kt_modal_edit_review_status').on('hidden.bs.modal', function() {
        $("#kt_modal_edit_review_status").remove();
        $("#kt_modal_view_getcoursereview").show();
    });

    $(document).ready(function() {
        $("#updatereviewstatus").validate({
            rules: {
                name: "required"
            },
            submitHandler: function(form) {
                var data = $("#updatereviewstatus").serialize();

                $(".loading").show();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route('reviewupdatestatus') }}',
                    data: data,
                    type: 'POST',
                    success: function(response) {
                        if (response) {
                            $("#kt_modal_edit_review_status").remove();
                            $('#kt_modal_view_getcoursereview').show();
                            window.LaravelDataTables["get-course-review-table"].ajax
                                .reload();
                            swal({
                                title: "Status!",
                                text: response.message,
                                type: "success"
                            });
                            $('body').removeAttr('style').css({
                                "--kt-toolbar-height": "55px",
                                "--kt-toolbar-height-tablet-and-mobile": "55px"
                            });
                        }
                        $(".loading").hide();
                    },
                    error: function(data) {
                        var response = data.responseJSON.errors;
                        var html = '';
                        $.each(response, function(i, val) {
                            html += '<p>' + val[0] + '</p>';
                        });
                        $(".loading").hide();
                        if (html == '') {
                            html = 'Something went wrong!';
                        }
                        swal({
                            html: true,
                            title: "Error!",
                            text: html,
                            icon: "error",
                            type: "error"
                        });
                    }
                });
            }
        });
    });
</script>
</div>
