<div class="modal fade" id="kt_modal_create_notification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Notification</h2>
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
                        <form class="kt-form" action="{{ route('admin.notification.store') }}" name="createnotification" id="createnotification" method="POST">
                            @csrf
                            <div class="py-3">
                                <label for="Name" class="required form-label">Title</label>
                                <input type="text" name="title" class="form-control form-control-solid" placeholder="Enter Title" value="{{ old('title') }}"  required="required" />
                            </div>

                            <div class="py-3">
                                <label for="Name" class="required form-label">Description</label>
                                <textarea type="text" name="description" class="form-control form-control-solid" placeholder="Enter Description" required="required" >{{ old('description') }}</textarea>
                            </div>

                            <div class="py-3">
                                <label for="Category" class="required form-label">Sent To</label>
                                <select name="send_to" id="send_to" class="form-select form-select-solid" >
                                    <option value="0">All</option>
                                    <option value="1">Organization</option>
                                    <option value="2">Individual User</option>
                                </select>
                            </div>

                            <div id="send_org" style="display: none;">
                                <div class="py-3 choosn-sle">
                                    <label for="Category" class="required form-label">Select Organization</label>
                                    <select name="organization[]" id="organization" class="form-select form-select-solid select-box" multiple >
                                    </select>
                                </div>

                                <div class="py-3">
                                    <label for="Category" class="form-label">With User</label>
                                    <input type="checkbox" name="with_user" id="with_user" value="1">
                                </div>
                            </div>

                            <div class="py-3 choosn-sle" id="send_user" style="display: none;">
                                <label for="Category" class="required form-label">Individual User</label>
                                <select name="user[]" id="user" class="form-select form-select-solid select-box" multiple >
                                </select>
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
    $('#kt_modal_create_notification').modal('show');
    $(document).ready(function() {

        $('#send_to').on('change', function() {
            var send_to = $(this).val();

            if (send_to == 0) {
                document.getElementById('send_org').style.display = 'none';
                document.getElementById('send_user').style.display = 'none';
            }
            if (send_to == 1) {
                document.getElementById('send_org').style.display = 'block';
                document.getElementById('send_user').style.display = 'none';
            }
            if (send_to == 2) {
                document.getElementById('send_org').style.display = 'none';
                document.getElementById('send_user').style.display = 'block';
            }

        });

        $("#organization").select2({
            placeholder: "Search Organization",
            ajax: {
                url: "{{ route('searchorganization') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        "_token": "{{ csrf_token() }}",
                        searchTerm: params.term // search term
                    };
                },
                templateSelection: function(data, container) {
                    // Add custom attributes to the <option> tag for the selected option
                    $(data.element).attr('data-custom-attribute', data.customValue);
                    return data.text;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#user").select2({
            placeholder: "Search User",
            ajax: {
                url: "{{ route('searchuser') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        "_token": "{{ csrf_token() }}",
                        searchTerm: params.term // search term
                    };
                },
                templateSelection: function(data, container) {
                    // Add custom attributes to the <option> tag for the selected option
                    $(data.element).attr('data-custom-attribute', data.customValue);
                    return data.text;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#createnotification").validate({
            rules: {
                name: "required"
            }, submitHandler: function(form) {
                $(".loading").show();
                var _url = '{{ route("admin.notification.store") }}';
                var data = $("#createnotification").serialize();
                PostPutAjaxCall(_url, 'POST', data);
            }
        });
    });
</script>
</div>
