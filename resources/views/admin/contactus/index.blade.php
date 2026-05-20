<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Contact us</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-2 pt-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Contact us</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                        <div class="row input-daterange">
                            <div class="col-md-4">
                                <input type="date" name="from_date" id="from_date" class="form-control" placeholder="From Date"/>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="to_date" id="to_date" class="form-control" placeholder="To Date"/>
                            </div>
                            <div class="col-md-4">
                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-warning">Refresh</button>
                            </div>
                        </div>
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>

    @section('script')
        {{ $dataTable->scripts() }}

        <script type="text/javascript">

            function ViewDetail(identifier) {
                var id = $(identifier).data('id');
                $(".loading").show();
                var _url = '{{ route("contact-us-detail", ":id") }}';
                _url = _url.replace(':id', id);
                GetCallAjax(_url);
            }

            $(document).ready(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                $('#filter').click(function(){
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if(from_date != '' &&  to_date != '') {
                        $("#contactus-table").on('preXhr.dt', function(e, settings, data) {
                            data.from_date = from_date;
                            data.to_date = to_date;
                        });
                        $('#contactus-table').DataTable().ajax.reload();
                    }
                    else {
                        alert('Both Date is required');
                    }
                });
                $('#refresh').click(function(){
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $("#contactus-table").on('preXhr.dt', function(e, settings, data) {
                        data.from_date = '';
                        data.to_date = '';
                    });
                    $('#contactus-table').DataTable().ajax.reload();
                });
            });

        </script>
    @endsection
    @stop
</x-layout-admin-base>
