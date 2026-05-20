<div class="modal fade" id="kt_modal_view_notification_history" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Notification History</h5>
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

                        <div class="mb-10">
                            <div class="symbol symbol-200px w-100">
                                <div class="mt-2 mb-2">
                                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-10 gy-3">
                                        <tr>
                                            <th><b>Title</b></th>
                                            <td>{{ $notification->title }}</td>
                                        </tr>
                                        <tr>
                                            <th><b>Description</b></th>
                                            <td>{{ $notification->body }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ $dataTable->scripts() }}

    <script type="text/javascript">

        $('#kt_modal_view_notification_history').modal('show');
        $('#kt_modal_view_notification_history').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });

    </script>
</div>