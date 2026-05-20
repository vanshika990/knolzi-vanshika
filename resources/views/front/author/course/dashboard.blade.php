<x-layout-font-dashboard-base>
    @section('content')


        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                    data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ url('/') }}">Author
                            Dashboard</a>
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Course</small>
                    </h1>
                </div>
            </div>
        </div>
        {{-- {{ dd($finished_course_students) }} --}}
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Student count for completed courses</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <table id="coursesTable"
                            class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-5 fs-5">
                            <thead>
                                <tr>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Student subscribed count</th>
                                    <th>Student completed count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statistics as $course)
                                    <tr>
                                        <td>{{ $course->course_id }}</td>
                                        <td>{{ $course->course_name }}</td>
                                        <td>{{ $course->subscribed_students_count }}</td>
                                        <td>{{ $course->completed_students_count }}</td>
                                        <td><a href="{{ route('courses.statistics', $course->course_id) }}">View
                                                Statistics</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @section('script')
        <script type="text/javascript">
            $(document).ready(function() {

                $('#coursesTable').DataTable({
                    order: [
                        [3, 'desc'] // Then, sort by the first column in ascending order
                    ],
                });
                $('#coursesTable_length select').removeClass('form-select');

                CKEDITOR.replace('course_description');
                CKEDITOR.replace('course_applications');
                CKEDITOR.replace('course_requirement');
                CKEDITOR.replace('course_include');

                $("#course_language_id").select2({
                    placeholder: "Select language"
                });

                $("#user_id").select2({
                        placeholder: "Search Author",
                        ajax: {
                            url: "{{ route('frontsearchcourseauthor') }}",
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

                                var $option = $('.select2 option[value="' + tag.id + '"]');
                                if ($option.attr('locked')) {
                                    $(container).addClass('locked-tag');
                                    tag.locked = true;
                                }

                                return data.text;
                            },
                            processResults: function(response) {
                                return {
                                    results: response
                                };
                            },
                            cache: true
                        }
                    })
                    .on('select2:unselecting', function(e) {
                        if ($(e.params.args.data.element).attr('locked')) {
                            e.preventDefault();
                        }
                    });

                $("#course_category_id").select2({

                    placeholder: "Search Category",
                    dropdownCss: {
                        "display": "none"
                    },
                    ajax: {
                        url: "{{ route('frontsearchcategory') }}",
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
                        url: "{{ route('frontsearchrelatedcourses') }}",
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
                        subscription_day: "required"
                    },
                    submitHandler: function(form) {
                        $(".loading").show();

                        var data = new FormData(form);

                        data.append('course_description', CKEDITOR.instances['course_description']
                            .getData());
                        data.append('course_applications', CKEDITOR.instances['course_applications']
                            .getData());
                        data.append('course_requirement', CKEDITOR.instances['course_requirement']
                            .getData());
                        data.append('course_include', CKEDITOR.instances['course_include'].getData());

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ route('user.view-my-course.store') }}",
                            type: 'POST',
                            contentType: false,
                            data: data,
                            processData: false,
                            cache: false,
                            success: function(response) {

                                $('.loading').hide();

                                swal({
                                        title: "Status!",
                                        text: "Course added successfully.",
                                        type: "success"
                                    },
                                    function() {
                                        window.location.href =
                                            "{{ route('user.view-my-course.index') }}";
                                    });

                            }
                        }).fail(function(xhr, textStatus, errorThrown) {
                            $('.text-danger').empty();
                            $('.loading').hide();
                            var errors = "";
                            if (xhr.status == 422) {
                                if (xhr.responseJSON.errors) {
                                    $.each(xhr.responseJSON.errors, function(i, val) {
                                        errors += "<b><p style='color:red'>" + val[0] +
                                            "</p></b><br/>";
                                    });
                                    if (errors !== "") {
                                        swal({
                                            title: "Error!",
                                            text: errors,
                                            type: "error",
                                            html: true
                                        });
                                    }
                                }
                            } else if (xhr.status == 500 || xhr.status == 404 || xhr.status ==
                                400) {
                                swal({
                                    title: "Error!",
                                    text: "Server error",
                                    type: "error",
                                    html: true
                                });
                                return false;
                            } else {
                                swal({
                                    title: "Error!",
                                    text: "No internet Connection. please check your internet connection.",
                                    type: "error",
                                    html: true
                                });
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
</x-layout-font-dashboard-base>
