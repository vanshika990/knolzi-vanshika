<div class="modal fade" id="kt_modal_update_profile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-75 mw-lg-75 mw-md-75">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update profile</h2>
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
                        <form class="kt-form" action="{{ route('updateuserprofilepost') }}" name="updateuserprofile" id="updateuserprofile" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <input type="hidden" name="id" value="{{ encrypt($user->id) }}">

                                <div class="py-1 col-md-6">
                                    <label for="name" class="required form-label">Name</label>
                                    <input type="text" class="form-control form-control-solid" name="name" value="{{ $user->name }}" id="name" placeholder="Enter name">
                                </div>
                                <div class="py-1 col-md-6">
                                    <label for="email" class="required form-label" >Email</label>
                                    <input type="text" class="form-control form-control-solid" name="email" value="{{ $user->email }}" id="email" placeholder="Enter email" readonly>
                                </div>
                                
                                <div class="py-1 col-md-6">
                                    <label for="profile_title" class="required form-label">Profile title</label>
                                    <input type="text" class="form-control form-control-solid" name="profile_title" value="{{ $user->profile_title }}" id="profile_title" placeholder="Enter profile title">
                                </div>
                                
                                <div class="py-3 col-md-6 course-img-prevw">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" id="image" class="form-control" />
                                    @if(!empty($user->profile_image))
                                        <a href="{{ $user->profile_image }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                </div>

                                <div class="py-3 col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control form-control-solid" name="password" id="password" placeholder="..........">
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" class="form-control form-control-solid" name="confirm_password" id="confirm_password" placeholder="..........">
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="about_me" class="required form-label">About me</label>
                                    <textarea name="about_me" id="about_me" cols="30" rows="10"> {{ $user->about_me}} </textarea>
                                </div>

                            </div>

                            <div class="py-3">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>

        $('#kt_modal_update_profile').modal('show');
        $('#kt_modal_update_profile').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });

        CKEDITOR.replace('about_me');
        
        $(document).ready(function () {
        
            /**********************Form Validation **************************/

            $("#updateuserprofile").validate({
                
                rules: {
                    'name': {
                        required: true,
                    }, 
                    'profile_title': {
                        required: true,
                    },
                    'about_me': {
                        required: true,
                    }
                },
                submitHandler: function (form) {
                    $(".loading").show();
                    var data = new FormData(form);
                     
                    var about_me = CKEDITOR.instances['about_me'].getData()
                    data.append('about_me', about_me);

                    var _url = '{{ route("updateuserprofilepost") }}';
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: _url,
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response) {
                                $(".modal,.fade.show").remove();
                                $(".loading").hide();
                                $('#institute-table').DataTable().ajax.reload();
                                $('#author_user-table').DataTable().ajax.reload();
                                swal({
                                    title: "Status!",
                                    text: response.message,
                                    type: "success"
                                });
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
            });
        });

    </script>
</div>
