<x-layout-front-base>
    @section('meta_title', 'Change password')
    @section('meta_description', 'Change password - Knolzi)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Change Password </h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <section class="login-page mt-5 mb-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-4">
                    <div class="edupme-forms">
                        <form action="{{route('change-password-post')}}" id="changepassword" name="changepassword" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="old_password" class="form-label">Old Password</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password" value="{{ old('old_password') }}" placeholder="Enter Old Password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" value="{{ old('new_password') }}" placeholder="Enter New Password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="{{ old('confirm_password') }}" placeholder="Enter Confirm Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-orange form-submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @section('script')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $("#changepassword").validate({
                rules: {
                    'old_password': {
                        required: true,
                    },
                    'new_password': {
                        required: true,
                    },
                    'confirm_password': {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    var _url = '{{route("change-password-post")}}';
                    var data = new FormData(form);

                    $.ajax({
                        url: _url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            if (response) {
                                swal({title: "Status!", text: response.message, type: "success"},
                                function(){
                                    location.reload();
                                });
                            }
                        },
                    }).fail(function(xhr, textStatus, errorThrown) {
                        $('.text-danger').empty();
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
            });

        });
    </script>
    @endsection
    @stop
</x-layout-front-base>
