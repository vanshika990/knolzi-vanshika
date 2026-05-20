<x-layout-font-dashboard-base>
    @section('meta_title', 'My Users')
    @section('meta_description', 'My Users - Knolzi)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">My Invitation</small>
                </h1>
            </div>
        </div>
    </div>
    <!-- static page header end -->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-2 pt-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">My Invitation</span>
                    </h3>
                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="" data-bs-original-title="Click to send an Invitation">
                        <a href="javascript:void(0)" onclick="CreateInvitation()" class="btn btn-sm btn-primary">
                            <span class="fas fa-user-plus"></span>
                            Add Invitation
                        </a>
                    </div>
                </div>
                <div class="card-body py-3">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
    @section('script')
    <x-additional-js-css/>
    {{ $dataTable->scripts() }}
        <script type="text/javascript">
            function CreateInvitation() {
                GetCallAjax("{{ route('createInvitation') }}");
            }
            function resendInvitation(identifier) {
                swal({
                    title: 'Are you sure you want to resend invitation?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, do it!",
                    cancelButtonText: "No, cancel it!",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        var id = $(identifier).data('id');
                        var _url = '{{ route("resendInvitation", ":id") }}';
                        _url = _url.replace(':id', id);
                        PostPutAjaxCall(_url, "POST", {'id': id});
                    } else {
                        swal("Cancelled", "cancelled.", "error");
                    }
                });
            }

        </script>
    @endsection
    @stop
</x-layout-font-dashboard-base>
