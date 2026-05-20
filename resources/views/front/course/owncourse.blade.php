<x-layout-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('dashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Own Course</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Own Course</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>

    @section('script')
    {{ $dataTable->scripts() }}
    <script type="text/javascript">
        
        function Viewdetails(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("coursequestiondetail", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

    </script>
    @endsection
    @stop
</x-layout-base>