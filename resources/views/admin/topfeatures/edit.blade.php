<div class="modal fade" id="kt_modal_edit_feature" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Feature</h2>
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
                        <form class="kt-form" action="{{route('admin.top-features.update',encrypt($feature->id))}}" id="updatefeature" name="updatefeature" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="py-3 col-md-12">
                                    <label for="Title" class="required form-label">Title</label>
                                    <input type="text" name="title" class="form-control form-control-solid" placeholder="Enter Title" value="{{ $feature->title }}"  required="required" />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="Sub Title" class="required form-label">Sub title</label>
                                    <textarea name="sub_title" class="form-control form-control-solid" placeholder="Enter sub title " rows="3" cols="3" >{{ $feature->sub_title }}</textarea>
                                </div>
                                <div class="py-3 col-md-12 course-img-prevw">
                                    <label for="Image" class="required form-label">Image</label>
                                    <input type="file" name="image" id="image" class="form-control" />
                                    <input type="hidden" name="old_image" value="{{ $feature->image }}">
                                        @if(!empty($feature->image))
                                        <a href="{{ $feature->image }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                        @endif
                                </div>
                                <div class="py-3">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Submit
                                        </button>
                                    </div>
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
    $('#kt_modal_edit_feature').modal('show');
    $('#kt_modal_edit_feature').on('hidden.bs.modal', function() {
        $(".modal").remove();
    });
    $(document).ready(function() {
        $("#updatefeature").validate({
            rules: {
                title: "required",
                sub_title: "required",
            }, submitHandler: function(form) {
                $(".loading").show();
                var data = new FormData(form);
                var _url = '{{route("admin.top-features.update",encrypt($feature->id))}}';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: _url,
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response) {
                            $(".loading").hide();
                            $(".modal,.fade.show").remove();
                            $('#Topfeatures-table').DataTable().ajax.reload();
                            swal({title: "Status!", text: response.message, type: "success"});
                        }
                        $(".loading").hide();
                    },
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
