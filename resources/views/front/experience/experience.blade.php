<x-layout-front-base>
    @section('meta_title', 'Work Experiance')
    @section('meta_description', 'Work Experiance - Knolzi)
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Work Experience</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <!-- personal profile page content start -->
    <section class="workexp-page mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-4" style="position: relative;">
                                <div class="profile-img">
                                    @if(!empty(Auth::user()->profile_image))
                                    <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" class="img-fluid">
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
                                        {{ Auth::user()->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="border mt-4 mb-3"></div>
                            <div class="left-profile-menu">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" aria-current="page" href="{{route('personal-profile')}}">My Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{route('education-qualification')}}">Education & Qualifications</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{route('work-experience')}}">Work Experience</a>
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
                                <h4 class="fw-bolder m-0">Work Experience</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row gx-9 gy-6">
                                <div class="col-xl-6 mb-3">
                                    <div class="d-flex justify-content-around align-items-center p-3 border-primary content card-dashed bg-light-primary text-center">
                                        <div class="">
                                            <div class="fs-6 text-gray-600 mb-3">Do you need to Add Experience? </div>
                                            <a href="javascript:void(0)" onclick="addexperience()" class="btn btn-sm btn-primary border-radius-5px" >Add Experience</a>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($data))
                                @foreach($data as $data)
                                <div class="col-xl-6 mb-3">
                                    <div class="d-block d-lg-flex justify-content-between align-items-center p-3 content card-dashed">
                                        <div class="">
                                            <div class="mb-2">
                                                <div class="fs-6 fw-bolder mb-0">Company Name</div>
                                                <div class="fs-6 text-gray-600">{{ $data->company_name }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="fs-6 fw-bolder mb-0">Experience</div>
                                                <div class="fs-6 text-gray-600">{{ $data->experience }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="fs-6 fw-bolder mb-0">Year</div>
                                                <div class="fs-6 text-gray-600">{{ $data->year }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="fs-6 fw-bolder mb-0">Role</div>
                                                <div class="fs-6 text-gray-600">{{ $data->role }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="fs-6 fw-bolder mb-0">Designation</div>
                                                <div class="fs-6 text-gray-600">{{ $data->designation }}</div>
                                            </div>
                                        </div>
                                        <div class="py-2">
                                            @php $id = encrypt($data->id) @endphp
                                            <a href="javascript:void(0)" data-id = "{{$id}}"  onclick="deleteexperience(this)" class="btn btn-sm btn-outline-secondary me-3" >Delete</a>
                                            <a href="javascript:void(0)" data-id = "{{$id}}"  onclick="editexperience(this)" class="btn btn-sm btn-light me-3" >Edit</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
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

        function editexperience(identifier) {
            var id = $(identifier).data('id');
            $(".loading").show();
            var _url = '{{ route("edit-work-experience", ":id") }}';
            _url = _url.replace(':id', id);
            GetCallAjax(_url);
        }

        function addexperience() {
            var _url = '{{ route("add-work-experience") }}';
            GetCallAjax(_url);
        }

        function deleteexperience(identifier) {

            var id = $(identifier).data('id');

            swal({
                title: 'Are you sure you want to Delete this Experience?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, do it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    var _url = '{{ route("work-experience-delete", ":id") }}';
                    _url = _url.replace(':id', id);
                    $.ajax({
                        url: _url,
                        type: 'GET',
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
                } else {
                    swal("Cancelled", "cancelled.", "error");
                }
            });
        }

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
