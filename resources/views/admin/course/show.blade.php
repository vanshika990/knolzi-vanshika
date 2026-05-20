<div class="modal fade" id="kt_modal_view_course" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50">
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

                                        <tr>
                                            <th><b>Course Name</b></th>
                                            <td>{{ $course->course_name }}</td>
                                        </tr>

                                        <tr>
                                            <th><b>Course Code</b></th>
                                            <td>{{ $course->course_code }}</td>
                                        </tr>

                                        <tr>
                                            <th><b>Author</b></th>
                                            <td>{{ implode(',', $user) }}</td>
                                        </tr>

                                        <tr>
                                            <th><b>Category</b></th>
                                            <td>
                                                @foreach($categories as $row)
                                                    @if(in_array($row->id, $course_category)) {{ $row['name'] }} , @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><b>Language</b></th>
                                            @foreach($course_language as $row)
                                            <td>{{ $row->course_language['name'] }}</td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <th><b>Course Description</b></th>
                                            <td>{{ strip_tags($course->course_description) }}</td>
                                        </tr>

                                        @if ($course->course_sub_description != "")
                                        <tr>
                                            <th><b>Course Sub Description</b></th>
                                            <td>{{ $course->course_sub_description }}</td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th><b>Course Requirement</b></th>
                                            <td>{{ strip_tags($course->course_requirement) }}</td>
                                        </tr>

                                        <tr>
                                            <th><b>Course Application</b></th>
                                            <td>{{ strip_tags($course->course_applications) }}</td>
                                        </tr>

                                        <tr>
                                            <th><b>Course Price</b></th>
                                            <td>{{ $course->course_price }}</td>
                                        </tr>
                                        <tr>
                                            <th><b>SEO meta title</b></th>
                                            @if ($course->meta_title != "")
                                                <td>{{ strip_tags($course->meta_title) }}</td>
                                            @else
                                                <td>-</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th><b>SEO meta keyword</b></th>
                                            @if ($course->meta_keyword != "")
                                                <td>{{ strip_tags($course->meta_keyword) }}</td>
                                            @else
                                                <td>-</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th><b>SEO meta description</b></th>
                                            @if ($course->meta_description != "")
                                                <td>{{ strip_tags($course->meta_description) }}</td>
                                            @else
                                                <td>-</td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <th><b>Status</b></th>
                                            <td>
                                                @if ($course->status == "1")
                                                <button name="status" class="btn btn-sm btn-success">Publish</button>
                                                @elseif($course->status == "0")
                                                <button name="status" class="btn btn-sm btn-danger">Unpublish</button>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th><b>Create Date</b></th>
                                            <td>{{ $course->created_at }}</td>
                                        </tr>

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
        $('#kt_modal_view_course').modal('show');
        $('#kt_modal_view_course').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });
    </script>

</div>
