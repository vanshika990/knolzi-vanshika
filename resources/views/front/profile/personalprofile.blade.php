<x-layout-front-base>
    @section('meta_title', 'My Personal Profile')
    @section('meta_description', 'My Personal Profile - Knolzi)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>My Profile</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <!-- personal profile page content start -->
    <section class="login-page mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-4" style="position: relative;">
                                <div class="profile-img">
                                    @if(!empty($user->profile_image))
                                    <img src="{{$user->profile_image}}" alt="{{ Auth::user()->name }}" class="img-fluid">
                                    @else
                                    <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ Auth::user()->name }}" class="img-fluid">
                                    @endif
                                    <a href="javascript:void(0)" ><img src="{{ asset('assets/front/images/edit.png') }}" alt="edit profile" class="edit-profile"></a>
                                    <form id="profile_image_form" name="profile_image_form">
                                        <input type="file" name="picture" id="fileInput"  style="display:none"/>
                                    </form>
                                </div>
                                <div class="profile-name">
                                    <div class="unm-mpv">
                                        {{$user->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="border mt-4 mb-3"></div>
                            <div class="left-profile-menu">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{route('personal-profile')}}">My Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('education-qualification')}}">Education & Qualifications</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('work-experience')}}">Work Experience</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header cursor-pointer">
                            <div class="card-title m-0">
                                <h4 class="fw-bolder m-0">Profile Details</h4>
                            </div>
                            <a href="{{route('edit-personal-profile')}}" class="btn btn-primary align-self-center">Edit Profile</a>
                        </div>
                        <div class="card-body">
                            <div class="p-9">
                                <div class="row mb-3">
                                    <label class="col-lg-4 fw-bold text-muted">Full Name</label>
                                    <div class="col-lg-8">
                                        <span class="fw-bolder fs-6 text-dark">{{$user->name}}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-4 fw-bold text-muted">Email Address</label>
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-bold fs-6">{{$user->email}}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-lg-4 fw-bold text-muted">Age</label>
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <span class="fw-bolder fs-6 me-2">
                                            @if($user->age_group == '51 and above')
                                                51 & above
                                            @else
                                                {{$user->age_group}}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- personal profile page content end -->

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".edit-profile").on('click', function(e) {
                e.preventDefault();
                $("#fileInput:hidden").trigger('click');
            });

            $("#fileInput").on('change', function() {

                var data = new FormData($('#profile_image_form')[0]);
                var _url = '{{ route("update-profile-image") }}';

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
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response) {
                            location.reload();
                        }
                    },
                }).fail(function(xhr, textStatus, errorThrown) {
                    $('.text-danger').empty();
                    var errors = "";
                    if (xhr.status == 422) {
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(i, val) {
                                errors += "<b><p>" + val[0] + "</p></b>";
                            });
                            if (errors !== "") {
                                var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="toast-error" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + errors + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                                $('body').append(toast);
                                new bootstrap.Toast(document.querySelector('#toast-error')).show();
                                $('#fileInput').val('');
                                return false;
                            }
                        }
                    } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                        var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="toast-error" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body"> Server error </div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                        $('body').append(toast);
                        new bootstrap.Toast(document.querySelector('#toast-error')).show();
                        return false;
                    } else {
                        var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="toast-error" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body"> No internet Connection. please check your internet connection. </div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                        $('body').append(toast);
                        new bootstrap.Toast(document.querySelector('#toast-error')).show();
                        return false;
                    }
                });
                return false;
            });
        });
    </script>
    @endsection
    @stop
</x-layout-front-base>
