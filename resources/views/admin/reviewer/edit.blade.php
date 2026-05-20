<div class="modal fade" id="kt_modal_reviewer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Reviewer</h2>
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
                        <form class="kt-form" action="{{route('admin.reviewer.update',$userDetail->id)}}" id="updatereviewer" name="updatereviewer" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="py-3 col-xxl-6">
                                    <label for="name" class="required form-label">Reviewer Name</label>
                                    <input type="text" name="name" class="form-control form-control-solid" placeholder="Reviewer Name" value="{{ $userDetail->name }}" id="name"  required="required">
                                </div>
                                <div class="py-3 col-xxl-6">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control form-control-solid" id="email" value="{{ $userDetail->email }}" placeholder="Email Address"  readonly>
                                </div>
                            </div>
                            <div class="py-3">
                                <label for="mobile_no" class="required form-label">Mobile No</label>
                                <input type="number" class="form-control form-control-solid" name="mobile_no" id="mobile_no" value="{{ $userDetail->mobile_no }}" placeholder="Mobile No">
                            </div>
                            <div class="row">
                                <div class="py-3 col-xxl-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control form-control-solid" name="password" id="password" placeholder="..........">
                                </div>
                                <div class="py-3 col-xxl-6">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" class="form-control form-control-solid" name="confirm_password" id="confirm_password" placeholder="..........">
                                </div>
                            </div>
                            <div class="py-3 col-xxl-12">
                                <div class="form-group">
                                    <label for="select_course" class="required form-label">Select Course</label>
                                    <select class="form-select form-select-solid" name="select_course[]" id="select_course" multiple>
                                        @foreach($sel_course_id as $course)
                                            <option value="{{ $course['course_id'] }}" selected >{{ $course['course_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="py-3">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $("#select_course").select2({ 
                placeholder: "Search course",
                ajax: {
                    url: "{{ route('searchcourse') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
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
        }); 
    </script>

    <script type="text/javascript">

        $('#kt_modal_reviewer').modal('show');
        $('#kt_modal_reviewer').on('hidden.bs.modal', function () {
            $(".modal").remove();
        });
        $(document).ready(function () {
            $("#updatereviewer").validate({
                rules: {
                    name: "required",
                    mobile_no: "required",
                }, submitHandler: function (form) {
                    var _url = '{{route("admin.reviewer.update",$userDetail->id)}}';
                    var data = $("#updatereviewer").serialize();
                    PostPutAjaxCall(_url, 'PUT', data);
                }
            });
        });

    </script>
</div>
