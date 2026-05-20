<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Organization Users</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-2 pt-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Organization Users</span>
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

        function getIndividualUser(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("orgIndividual", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

        function getSubscribeCourse(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("getsubscribercourse", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

        function getinviteduser(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("getinviteduser", ":id") }}';
            _url = _url.replace(':id', id);
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

        function addMenuallySubscription(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("orgaddmanualsubscription", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

    </script>
    @endsection
    @stop
</x-layout-admin-base>
