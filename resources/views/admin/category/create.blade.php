<div class="modal fade" id="kt_modal_create_category" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50 mw-lg-50 mw-md-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Category</h2>
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
                        <form class="kt-form" action="{{ route('admin.category.store') }}" name="createcategory" id="createcategory" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="py-3 col-md-6">
                                    <label for="Name" class="required form-label">Name</label>
                                    <input type="text" name="name" class="form-control form-control-solid" placeholder="Enter Name" value="{{ old('name') }}"  required="required" />
                                </div>
                                @if(count($parents) > 0)
                                    <div class="py-3 col-md-6">
                                        <label for="Display Name" class="form-label">Parent Category</label>
                                        <select name="parent_id" class="form-select form-select-solid">
                                            <option value="0">Select Parent Category</option>
                                            @foreach($parents as $data)
                                            <option value="{{$data['id']}}">{{$data['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="parent_id" value="0">
                                @endif
                                <div class="py-3 col-md-12">
                                    <label for="category_sub_description" class="required form-label">Category Sub Description</label>
                                    <textarea name="category_sub_description" id="category_sub_description" class="form-control form-control-solid" rows="3" cols="119" placeholder="Enter category sub description..." required></textarea>
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="category_description" class="required form-label">Category Description</label>
                                    <textarea name="category_description" id="category_description" class="form-control form-control-solid" rows="10" cols="80" required></textarea>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="meta title" class="required form-label">SEO meta title</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control form-control-solid" placeholder="Enter SEO meta title" value="{{ old('meta_title') }}" required />
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="meta keyword" class="required form-label">SEO meta keyword</label>
                                    <input type="text" name="meta_keyword" id="meta_keyword" class="form-control form-control-solid" placeholder="Enter SEO meta keyword" value="{{ old('meta_keyword') }}" required />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="meta description" class="required form-label">SEO meta description</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control form-control-solid" rows="3" cols="119" placeholder="Enter SEO meta description..." required ></textarea>
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
</div>
<script type="text/javascript">
    $('#kt_modal_create_category').modal('show');
    $(document).ready(function() {

        CKEDITOR.replace('category_description');

        $("#createcategory").validate({
            rules: {
                name: "required",
                category_sub_description: "required",
                category_description: "required",
            },
            submitHandler: function(form) {
                $(".loading").show();
                
                var data = new FormData(form);
                data.append('category_description', CKEDITOR.instances['category_description'].getData());

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                $.ajax({
                    url: "{{ route('admin.category.store') }}",
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function(response) {
                        $(".modal,.fade.show").remove();
                        $('#category-table').DataTable().ajax.reload();
                        $('.loading').hide();
                        swal({title: "Status!", text: response.message, type: "success"});
                    }
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
