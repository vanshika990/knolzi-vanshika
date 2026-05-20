<div class="modal fade" id="kt_modal_view_course_QA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">Course QA</h5>
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

                        <div class="col-md-2 col-sm-4 col-xs-12" style="float:right;">
                            <div class="form-group">
                                <select class="form-select form-select-solid" name="filter" id="filter">
                                    <option value="0">All</option>
                                    <option value="1">Pending</option>
                                    <option value="2">Approve</option>
                                    <option value="3">Reject</option>
                                </select>
                            </div>
                        </div>

                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ $dataTable->scripts() }}

    <script type="text/javascript">

        $('#kt_modal_view_course_QA').modal('show');
        $('#kt_modal_view_course_QA').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });

        document.getElementById("filter").onchange = function(){
            var value = document.getElementById("filter").value;

            const table = $('#get-course-qa-table');

            table.on('preXhr.dt', function (e, settings, data) {
                    data.filter = value;
            });

            table.DataTable().ajax.reload();
            return false;
        }

        function editCourseQA(identifier) {
            $(".loading").show();
            var id = $(identifier).data('id');
            $("#kt_modal_view_course_QA").hide();
            var _url = '{{ route("editcourseQA", ":id") }}';
            _url = _url.replace(':id', id);
            GetPopupCallAjax(_url);
        }

    </script>
</div>
