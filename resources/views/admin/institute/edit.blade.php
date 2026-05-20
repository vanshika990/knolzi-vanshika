<div class="modal fade" id="kt_modal_edit_institute" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25 mw-lg-25 mw-md-25">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Institute</h2>
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
                        <form class="kt-form" action="{{route('admin.institute.update',$id)}}" id="updateinstitute" name="updateinstitute" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="py-3 choosn-sle col-md-12">
                                    <label for="Author Name" class="required form-label">Institute Author</label>
                                    <select class="form-select form-select-solid select-box" name="author_id[]" id="author_id" multiple required>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
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

        $('#kt_modal_edit_institute').modal('show');
        $('#kt_modal_edit_institute').on('hidden.bs.modal', function() {
            $(".modal").remove();
        });

        $(document).ready(function() {
            $("#author_id").select2({
                placeholder: "Search Author",
                ajax: {
                    url: "{{ route('searchauthor') }}",
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
            $("#updateinstitute").validate({
                rules: {
                    'author_id[]': "required",
                }, submitHandler: function(form) {
                    var _url = '{{route("admin.institute.update",$id)}}';
                    var data = $("#updateinstitute").serialize();
                    PostPutAjaxCall(_url, 'PUT', data);
                }
            });
        });

    </script>
</div>
