<div class="modal fade" id="kt_modal_view_getcoursereview" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0" >Course Review</h5>
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
                <div class="mb-xl-8 py-3" style="width:15%">
                    <label for="Send To" class="required form-label">Filter Status</label>
                    <select name="selected_status" class="form-select form-control-solid" onchange="Get_status(this.value);">
                        <option value="4">All</option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>
                    </select>
                </div>
                <div class="card mb-xl-8">
                    <div class="card-body p-3">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ $dataTable->scripts() }}

    <script type="text/javascript">
        $('#kt_modal_view_getcoursereview').modal('show');
        $('#kt_modal_view_getcoursereview').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });

        function ViewReviewDetail(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            $("#kt_modal_view_getcoursereview").hide();
            var _url = '{{ route("getcoursereviewdetail", ":id") }}';
            _url = _url.replace(':id', id);
            GetPopupCallAjax(_url);
        }

        function EditStatus(review_id) {
            var id = $(review_id).data('id');
            $(".loading").show();
            var _url = '{{ route("editcoursereviewstatus", ":id") }}';
            _url = _url.replace(':id', id);
            GetPopupCallAjax(_url);
        }

        function Get_status(status) {
            $("#get-course-review-table").on('preXhr.dt', function(e, settings, data) {
                data.status = status;
            });
            window.LaravelDataTables["get-course-review-table"].ajax.reload();
        }


    </script>
</div>