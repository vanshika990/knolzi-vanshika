<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Author</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-2 pt-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Authors</span>
                    </h3>

                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="" data-bs-original-title="Click to add a Author">
                        <a href="javascript:void(0)" onclick="CreateAuthor()" class="btn btn-sm btn-primary">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                    <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                </svg>
                            </span> Author
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
    {{ $dataTable->scripts() }}

    <script type="text/javascript">
        $(document).on("click", '#status_changed', function() {
            var user_id = $(this).attr("uid");
            var status = $(this).text();
            var titleText = 'Are you sure you want to active this User ?';
            if (status == 'Active') {
                var titleText = 'Are you sure you want to deactive this User ?';
            }
            swal({
                title: titleText,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, do it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    $(".loading").show();
                    var data = {'id': user_id};
                    PostPutAjaxCall('{{ route("userchangestatus") }}', 'POST', data);
                } else {
                    swal("Cancelled", "cancelled.", "error");
                }
            });
        });

        function Viewdetails(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("getuserdetail", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

        function getAuthorCourse(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("getauthorcourse", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

        function deleteAuthor(delete_id) {
            var id = $(delete_id).data('id');
            swal({
                title: 'Are you sure you want to Delete this Author?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, do it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    var _url = '{{ route("deleteuserprofile", ":id") }}';
                    _url = _url.replace(':id', id);
                    DeleteAjaxCall(_url);
                } else {
                    swal("Cancelled", "cancelled.", "error");
                }
            });
        }

        
        function updateprofile(identifier){
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("updateuserprofile", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

        function CreateAuthor() {
            $(".loading").show();
            var role = 'author';
            var _url = '{{ route("adduserprofile", ":role") }}';
            _url = _url.replace(':role', role);
            GetCallAjax(_url);
        }

        function emailverify(identifier) {
            var email_id = $(identifier).data('email_verify');
            $(".loading").show();
            var _url = '{{ route("verifyuser", ":email") }}';
            _url = _url.replace(':email', email_id);
            
            $.ajax({
                url: _url,
                type: 'GET',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success === 'true') {
                        $(".loading").hide();
                        swal({title: "Status!", text: response.message, type: "success"});
                    }
                    else{
                        $(".loading").hide();
                        swal({title: "Status!", text: response.message, type: "error"});
                    }
                    $(".loading").hide();
                },
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

    </script>
    @endsection
    @stop
</x-layout-admin-base>
