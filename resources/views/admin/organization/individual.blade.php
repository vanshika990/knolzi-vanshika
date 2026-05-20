<div class="modal fade" id="kt_modal_view_companyindividualuser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-md-75">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Individual Users</h2>
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
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ $dataTable->scripts() }}

    <script type="text/javascript">
        $('#kt_modal_view_companyindividualuser').modal('show');
        $('#kt_modal_view_companyindividualuser').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });

        $(document).on("click", '#status_changed', function() {
            var user_id = $(this).attr("uid");
            var status = $(this).text();
            var titleText = 'Are you sure you want to active this User ?';
            if (status == 'Active') {
                var titleText = 'Are you sure you want to deactive this User ?';
            }
            swal({
                title: titleText,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, do it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    $(".loading").show();
                    var data = {'id': user_id};
                    PostPutPopupAjaxCall('{{ route("userchangestatus") }}', 'POST', data);
                } else {
                    swal("Cancelled", "cancelled.", "error");
                }
            });
        });

        function Viewdetails(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            $("#kt_modal_view_companyindividualuser").hide();
            var _url = '{{ route("getuserdetail", ":id") }}';
            _url = _url.replace(':id', id);
            GetPopupCallAjax(_url);
        }

    </script>
</div>
