<div class="modal fade" id="kt_modal_add_invitation" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Send Invitation</h5>
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
                        <form class="kt-form" action="{{ route('org-invitation-create') }}" name="sendinvitation" id="sendinvitation" method="POST">
                            @csrf
                            <div class="py-3">
                                <label for="Name" class="required form-label">Email Address</label>
                                <input type="email" name="email_id" class="form-control form-control-solid" placeholder="Enter Email Address" value="{{ old('email_id') }}"  required="required" />
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
</div>
<script type="text/javascript">
    $('#kt_modal_add_invitation').modal('show');
    $(document).ready(function() {
        $("#sendinvitation").validate({
            rules: {
                email_id: "required"
            }, submitHandler: function(form) {
                $(".loading").show();
                var _url = '{{ route("org-invitation-create") }}';
                var data = $("#sendinvitation").serialize();
                PostPutAjaxCall(_url, 'POST', data);
            }
        });
    });
</script>
</div>
