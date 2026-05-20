<x-layout-font-dashboard-base>
    @section('content')


        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend"
                    data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ url('/') }}">Course
                            Statistics</a>
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-dark fs-7 fw-bold my-1 ms-1">{{ $course->course_name }}</small>
                    </h1>
                </div>
            </div>
        </div>
        {{-- {{ dd($finished_course_students) }} --}}
        @if (count($statistics) == 0)
            <div class="post d-flex flex-column-fluid" id="kt_post">
                <div id="kt_content_container" class="container">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-2 pt-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">No student has completed this course</span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="post d-flex flex-column-fluid" id="kt_post">
                <div id="kt_content_container" class="container">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-2 pt-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Student count for completed courses</span>
                            </h3>
                        </div>
                        <div class="card-header border-2 pt-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">

                                    {{ count($statistics) }}

                                </span>
                            </h3>
                        </div>
                        {{-- <div class="card-body py-3">
                            <h1 class="mt-5">The first attempt ID of students who finished the course.</h1> --}}
                        {{-- <table class="table table-bordered mt-3">
                            <thead>
                                <tr>

                                    <th>User ID</th>
                                    <th>First Attempt ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course->first_attempts as $attempt)

                                    <tr>

                                        <td>{{ $attempt['user_id'] }}</td>
                                        <td>{{ $attempt['min_id'] }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table> --}}
                        {{-- </div> --}}
                        <div class="card-body py-3">
                            <h1 class="mt-5">Percentage and Time taken to complete the course</h1>
                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-5 fs-5" id="studentTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Percentage</th>
                                        <th>Time Taken in Minutes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($statistics as $i => $user_data)
                                        {{-- {{ dd($user_data->name) }} --}}
                                        <tr>
                                            <td>{{ $user_data->name }}</td>
                                            <td>{{ $user_data->email }}</td>
                                            <td>{{ $percentages[$i] }}</td>
                                            @if ($data['timeTaken'][$i])
                                                <td>{{ $data['timeTaken'][$i] }}</td>
                                            @else
                                                <td>--</td>
                                            @endif
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div class="card-body py-3 fs-6">
                            <h1 class="mt-5">Students' Grasp on Topic</h1>
                            <table class="table table-bordered mt-3">
                                <tbody>
                                    {{-- {{ dd($user_data->name) }} --}}
                                    <tr>
                                        <td>Lack of Attention</td>
                                        <td>{{ $data['fk'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Needs Serious Help</td>
                                        <td>{{ $data['pr'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Needs Some Help</td>
                                        <td>{{ $data['sw'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bright</td>
                                        <td>{{ $data['br'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if (($data['fk'] || $data['pr'] || $data['sw'] || $data['br']) > 0)
                            <div class="card-body py-3 fs-7">
                                <div style="width: 800px; height: 500px;">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        @endif
                        <div class="card-body py-3">

                            <h1 class="mt-5">Cognition Metric</h1>
                            <table class="table table-bordered mt-3 fs-6">
                                <tbody>
                                    {{-- {{ dd($user_data->name) }} --}}
                                    <tr>
                                        <td>Need Help in Analytical problems</td>
                                        <td>{{ $data['anaWeak'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Need to work on Basics</td>
                                        <td>{{ $data['applWeak'] }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="card-body py-3">

                            <h1 class="mt-5">Detailed Results</h1>
                            <table class="table table-bordered mt-3 fs-6">
                                <tbody>
                                    <tr>

                                        <td>Number of students who have subscribed the course</td>
                                        <td>
                                            @if ($totalStudents > 0)
                                                {{ $totalStudents }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Number of students who have completed the course </td>
                                        <td>{{ $totalSubscription }}</td>
                                    </tr>
                                    <tr>
                                        <td>Completion %</td>
                                        <td>{{ round(($totalSubscription / $totalStudents) * 100, 1) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Number of students with average Performance</td>
                                        <td>{{ $data['av'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Number of students who need some attention</td>
                                        <td>{{ $data['sw'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Number of students who need serious attention</td>
                                        <td>{{ $data['pr'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Number of students who are bright and fast</td>
                                        <td>{{ $data['br'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Number of students who did not pay attention</td>
                                        <td>{{ $data['fk'] }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
        @endif
    @section('script')
        <script type="text/javascript">
            $(document).ready(function() {
                $('#studentTable').DataTable();
                $('#studentTable_length select').removeClass('form-select');

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

        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Lack of Attention', 'Needs Serious Help', 'Needs Some Help', 'Bright',
                        'Need Help in Analytical problems', 'Need to work on Basics'
                    ],
                    datasets: [{
                        label: '# of Students',
                        data: [
                            {{ $data['fk'] }},
                            {{ $data['pr'] }},
                            {{ $data['sw'] }},
                            {{ $data['br'] }},
                            {{ $data['anaWeak'] }},
                            {{ $data['applWeak'] }}
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endsection
@stop
</x-layout-font-dashboard-base>
