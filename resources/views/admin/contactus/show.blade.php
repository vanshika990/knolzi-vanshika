<div class="modal fade" id="kt_modalcontactus_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Details</h2>
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
                                <div class="mt-4 mb-2">
                                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                        @if (isset($data->subject))
                                        <tr>
                                            <th><b>Subject</b></th>
                                            <td>{{ $data->subject }}</td>
                                        </tr>
                                        @endif
                                        @if (isset($data->name))
                                        <tr>
                                            <th><b>Contact name</b></th>
                                            <td>{{ $data->name }}</td>
                                        </tr>
                                        @endif
                                        @if ($data->email != "")
                                        <tr>
                                            <th><b>Email address</b></th>
                                            <td><a href="mailto:{{ $data->email }}">{{ $data->email }}</a></td>
                                        </tr>
                                        @endif
                                        @if ($data->mobile_no != "")
                                        <tr>
                                            <th><b>Mobile Number</b></th>
                                            <td><a href="tel:{{ $data->mobile_no }}">{{ $data->mobile_no }}</a></td>
                                        </tr>
                                        @endif
                                        @if ($data->who_are_you != "")
                                        <tr>
                                            <th><b>Who are you</b></th>
                                            <td>{{ $data->who_are_you }}</td>
                                        </tr>
                                        @endif
                                        @if ($data->gender != "")
                                        <tr>
                                            <th><b>Gender</b></th>
                                            <td>{{ $data->gender }}</td>
                                        </tr>
                                        @endif
                                        @if ($data->hear_about_us != "")
                                        <tr>
                                            <th><b>Hear about us</b></th>
                                            <td>{{ $data->hear_about_us }}</td>
                                        </tr>
                                        @endif
                                        @if ($data->message != "")
                                        <tr>
                                            <th><b>Message</b></th>
                                            <td>{{ $data->message }}</td>
                                        </tr>
                                        @endif
                                        @if ($data->created_at !== "")
                                        <tr>
                                            <th><b>Create Date</b></th>
                                            <td>{{ $data->created_at }}</td>
                                        </tr>
                                        @endif
                                        @if ($data->updated_at !== "")
                                        <tr>
                                            <th><b>Update Date</b></th>
                                            <td>{{ $data->updated_at }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $('#kt_modalcontactus_details').modal('show');
    $('#kt_modalcontactus_details').on('hidden.bs.modal', function() {
        $(".modal").remove();
        $('.modal-backdrop').remove();
    });
</script>
</div>
