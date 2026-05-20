<div class="modal fade" id="kt_modal_view_inviteduser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-md-75">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Invited user</h2>
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
        $('#kt_modal_view_inviteduser').modal('show');
        $('#kt_modal_view_inviteduser').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });
    </script>
</div>