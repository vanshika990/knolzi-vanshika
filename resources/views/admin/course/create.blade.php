<x-layout-admin-base>
    @section('content')
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Course</small>
                    </h1>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Add Course</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <form class="kt-form" action="{{ route('admin.course.store') }}" name="createcourse" id="createcourse" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="py-3 col-md-6">
                                    <label for="Name" class="required form-label">Course Name</label>
                                    <input type="text" name="course_name" id="course_name" class="form-control form-control-solid" placeholder="Enter Course Name" value="{{ old('course_name') }}" />
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="Name" class="required form-label">Course Price</label>
                                    <input type="number" name="course_price" id="course_price" class="form-control form-control-solid" placeholder="Enter Course Price" value="{{ old('course_price') }}" required />
                                </div>

                                <div class="py-3 col-md-12">
                                    <label for="description" class="required form-label">Course Sub Description</label>
                                    <textarea name="course_sub_description" id="course_sub_description" class="form-control form-control-solid" rows="3" cols="119" placeholder="Enter course sub description..." required></textarea>
                                </div>

                                <div class="py-3 col-md-6">
                                    <label for="Name" class="required form-label">Course Code</label>
                                    <input type="text" name="course_code" id="course_code" class="form-control form-control-solid" placeholder="Enter Course code" value="{{ old('course_code') }}"  required/>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="course_image" class="required form-label">Course Image</label><small id="fileHelp" class="text-muted"> Note : upload only images ( type : jpeg,jpg,png,gif )</small>
                                    <input type="file" id="course_image" name="course_image" class="form-control" accept="image/*" aria-describedby="fileHelp" required/>
                                </div>

                                <div class="py-3 choosn-sle col-md-6">
                                    <label for="Course Author" class="required form-label">Course Author</label>
                                    <select multiple class="form-select form-select-solid select-box" name="user_id[]" id="user_id" required></select>
                                </div>
                                <div class="py-3 choosn-sle col-md-6">
                                    <label for="Display Name" class="required form-label">Category</label>
                                    <select name="course_category_id[]" id="course_category_id" class="form-select form-select-solid" multiple required></select>
                                </div>

                                <div class="py-3 choosn-sle col-md-6">
                                    <label for="Course type" class="required form-label">Course Type</label>
                                    <select name="course_type" id="course_type" class="form-select form-select-solid">
                                        <option value="">Select option</option>
                                        @foreach($course_types as $type)
                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="py-3 col-md-6">
                                    <label class="required form-label d-block">Course Featured</label>
                                    <div class="form-check-inline form-check">
                                        <label class="form-check-label" for="Yes">
                                            <input class="form-check-input" type="radio" name="course_featured" value="1" id="yes"  />
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check-inline form-check">
                                        <label class="form-check-label" for="No">
                                            <input class="form-check-input" type="radio" name="course_featured" value="0" id="no" checked="checked" />
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="py-3 choosn-sle col-md-6">
                                    <label for="Course tag" class="form-label">Course Tag</label>
                                    <select name="course_tag" id="course_tag" class="form-select form-select-solid">
                                        <option value="">Select option</option>
                                        <option value="Bestseller">Bestseller</option>
                                    </select>
                                </div>
                                <div class="py-3 col-md-6"></div>
                                <div class="py-3 col-md-6">
                                    <label for="description" class="required form-label">Course Description</label>
                                    <textarea name="course_description" id="course_description" rows="10" cols="80" >
                                    </textarea>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="description" class="required form-label">Course Requirement</label>
                                    <textarea name="course_requirement" id="course_requirement" class="form-control form-control-solid" rows="10" cols="80" ></textarea>
                                </div>

                                <div class="py-3 col-md-6">
                                    <label for="course_application" class="required form-label">Course Application (What you'll learn)</label>
                                    <textarea name="course_applications" id="course_applications" rows="10" cols="80" ></textarea>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="course_include" class="required form-label">Course Include</label>
                                    <textarea name="course_include" id="course_include" rows="10" cols="80" ></textarea>
                                </div>

                                <div class="py-3 choosn-sle col-md-6">
                                    <label for="Language" class="required form-label">Language</label>
                                    <select name="course_language_id[]" id="course_language_id" class="form-select form-select-solid" multiple required>
                                        @if(!empty($languages))
                                            @foreach($languages as $row)
                                                <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label class="required form-label d-block">Status</label>
                                    <div class="form-check-inline form-check">
                                        <label class="form-check-label" for="Publish">
                                            <input class="form-check-input" type="radio" name="status" value="1" id="publish" checked="checked" />
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check-inline form-check">
                                        <label class="form-check-label" for="unpublish">
                                            <input class="form-check-input" type="radio" name="status" value="0" id="unpublish" />
                                            Unpublish
                                        </label>
                                    </div>

                                </div>
                                <div class="py-3 choosn-sle col-md-6">
                                    <label for="Display Name" class="required form-label">Related Courses</label>
                                    <select name="related_course_id[]" id="related_course_id" class="form-select form-select-solid" multiple required></select>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="Subscription days" class="required form-label">Subscription days</label>
                                    <input type="number" name="subscription_day" id="subscription_day" min="1" class="form-control form-control-solid" placeholder="Enter days" value="{{ old('subscription_day') }}" required="required"/>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="meta title" class="required form-label">SEO meta title</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control form-control-solid" placeholder="Enter SEO meta title" value="{{ old('meta_title') }}" />
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="meta keyword" class="required form-label">SEO meta keyword</label>
                                    <input type="text" name="meta_keyword" id="meta_keyword" class="form-control form-control-solid" placeholder="Enter SEO meta keyword" value="{{ old('meta_keyword') }}" />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="meta description" class="required form-label">SEO meta description</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control form-control-solid" rows="3" cols="119" placeholder="Enter SEO meta description..." ></textarea>
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
    @section('script')
        <script type="text/javascript">

            $(document).ready(function() {

                CKEDITOR.replace('course_description');
                CKEDITOR.replace('course_applications');
                CKEDITOR.replace('course_requirement');
                CKEDITOR.replace('course_include');

                $("#course_language_id").select2();

                $("#course_category_id").select2({

                    placeholder: "Search Category",
                    dropdownCss:{"display": "none"},
                    ajax: {
                        url: "{{ route('searchcategory') }}",
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

                $("#related_course_id").select2({
                    placeholder: "Search Related Courses",
                    ajax: {
                        url: "{{ route('searchrelatedcourses') }}",
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

                $("#user_id").select2({
                    placeholder: "Search Author",
                    ajax: {
                        url: "{{ route('searchcourseauthor') }}",
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

                $("#createcourse").validate({
                    rules: {
                        course_name: "required",
                        course_type: "required",
                        subscription_day: "required"
                    },
                    submitHandler: function(form) {
                        $(".loading").show();

                        var data = new FormData(form);
                        data.append('course_description', CKEDITOR.instances['course_description'].getData());
                        data.append('course_applications', CKEDITOR.instances['course_applications'].getData());
                        data.append('course_requirement', CKEDITOR.instances['course_requirement'].getData());
                        data.append('course_include', CKEDITOR.instances['course_include'].getData());

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ route('admin.course.store') }}",
                            type: 'POST',
                            contentType: false,
                            data: data,
                            processData: false,
                            cache: false,
                            success: function(response) {

                                $('.loading').hide();

                                swal({title: "Status!", text: "Course added successfully.", type: "success"},
                                function(){
                                    window.location.href = "{{ route('admin.course.index') }}";
                                });

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
    @endsection
    @stop
</x-layout-admin-base>
