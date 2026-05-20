<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Digital classroom setting</small>
                </h1>
            </div>
        </div>
    </div>
    <form class="kt-form" action="{{ route('home-page-setting-post') }}" name="digitalclassroompagesetting" id="digitalclassroompagesetting" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Hero section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="hero_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_hero'])) ? '' : 'digital_classroom_hero' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="hero_sec_title" class="required form-label">Hero section title</label>
                                <input type="text" name="hero_sec_title" id="hero_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_hero'])) ? '' : $data['digital_classroom_hero']['hero_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="hero_sec_image" class="required form-label">Hero image</label>
                                <input type="file" name="hero_sec_image" id="hero_sec_image" class="form-control" />
                                <input id="hero_sec_oldimage" type="hidden" name="hero_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_hero'])) ? '' :  $data['digital_classroom_hero']['hero_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['digital_classroom_hero']))
                                        <a href="{{ $data['digital_classroom_hero']['hero_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="hero_sec_description" class="required form-label">Description</label>
                                <textarea name="hero_sec_description" id="hero_sec_description" class="form-control form-control-solid" placeholder="Enter description" rows="3" cols="3" >@if(!(empty($data))) @if(!empty($data['digital_classroom_hero'])) {{ $data['digital_classroom_hero']['hero_sec_description'] }} @endif @endif</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">How it work</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="how_it_work_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_how_it_work'])) ? '' : 'digital_classroom_how_it_work' ) }}">
                        <div class="row">

                            <div class="py-3 col-md-6">
                                <label for="how_it_work_sec_title" class="required form-label">Title</label>
                                <input type="text" name="how_it_work_sec_title" id="how_it_work_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_how_it_work'])) ? '' : $data['digital_classroom_how_it_work']['how_it_work_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="how_it_work_sec_image" class="required form-label">Image</label>
                                <input type="file" name="how_it_work_sec_image" id="how_it_work_sec_image" class="form-control" />
                                <input id="how_it_work_sec_oldimage" type="hidden" name="how_it_work_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_how_it_work'])) ? '' :  $data['digital_classroom_how_it_work']['how_it_work_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['digital_classroom_how_it_work']))
                                        <a href="{{ $data['digital_classroom_how_it_work']['how_it_work_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="how_it_work_sec_sub_title" class="required form-label">Sub title</label>
                                <textarea name="how_it_work_sec_sub_title" class="form-control form-control-solid" id="how_it_work_sec_sub_title" placeholder="Enter sub title "  rows="1" cols="2" >{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_how_it_work'])) ? '' :  $data['digital_classroom_how_it_work']['how_it_work_sec_sub_title'] ) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Teaching cycle section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="teaching_cycle_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_teaching_cycle'])) ? '' : 'digital_classroom_teaching_cycle' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="teaching_cycle_sec_title" class="required form-label">Title</label>
                                <input type="text" name="teaching_cycle_sec_title" id="teaching_cycle_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_teaching_cycle'])) ? '' : $data['digital_classroom_teaching_cycle']['teaching_cycle_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="teaching_cycle_sec_image" class="required form-label">Image</label>
                                <input type="file" name="teaching_cycle_sec_image" id="teaching_cycle_sec_image" class="form-control" />
                                <input id="teaching_cycle_sec_oldimage" type="hidden" name="teaching_cycle_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_teaching_cycle'])) ? '' :  $data['digital_classroom_teaching_cycle']['teaching_cycle_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['digital_classroom_teaching_cycle']))
                                        <a href="{{ $data['digital_classroom_teaching_cycle']['teaching_cycle_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="teaching_cycle_sec_sub_title" class="required form-label">Sub title</label>
                                <textarea name="teaching_cycle_sec_sub_title" id="teaching_cycle_sec_sub_title" class="form-control form-control-solid" placeholder="Enter sub title " rows="3" cols="3" >{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_teaching_cycle'])) ? '' :  $data['digital_classroom_teaching_cycle']['teaching_cycle_sec_sub_title'] ) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Learning cycle section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="learning_cycle_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_learning_cycle'])) ? '' : 'digital_classroom_learning_cycle' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="learning_cycle_sec_title" class="required form-label">Title</label>
                                <input type="text" name="learning_cycle_sec_title" id="learning_cycle_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_learning_cycle'])) ? '' : $data['digital_classroom_learning_cycle']['learning_cycle_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="learning_cycle_sec_image" class="required form-label">Image</label>
                                <input type="file" name="learning_cycle_sec_image" id="learning_cycle_sec_image" class="form-control" />
                                <input id="learning_cycle_sec_oldimage" type="hidden" name="learning_cycle_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_learning_cycle'])) ? '' :  $data['digital_classroom_learning_cycle']['learning_cycle_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['digital_classroom_learning_cycle']))
                                        <a href="{{ $data['digital_classroom_learning_cycle']['learning_cycle_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="learning_cycle_sec_sub_title" class="required form-label">Sub title</label>
                                <textarea name="learning_cycle_sec_sub_title" id="learning_cycle_sec_sub_title" class="form-control form-control-solid" placeholder="Enter sub title " rows="3" cols="3" >{{ (empty($data)) ? '' : ( (empty($data['digital_classroom_learning_cycle'])) ? '' :  $data['digital_classroom_learning_cycle']['learning_cycle_sec_sub_title'] ) }}</textarea>
                            </div>
                        </div>
                        <div class="py-3">
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </form>

    @section('script')

    <script type="text/javascript">

        var counter = 2;

        // remove features
        function remove_features(id) {
            $("#features_sec_features_title" + id).rules("remove");
            $("#features_sec_features_sub_title" + id).rules("remove");
            $("#remove_" + id).remove();
            counter--;
        }

        $(document).ready(function() {

            $(".add_new_features").click(function(e) {
                var newInput = '<div class="col-md-12" id="remove_' + counter + '"><div class="row"><div class="py-3 col-md-3"><input type="text" name="features_sec_features_title[]" id="features_sec_features_title' + counter + '" class="form-control form-control-solid" placeholder="Enter Title " required /></div><div class="py-3 col-md-3"><textarea name="features_sec_features_sub_title[]" id="features_sec_features_sub_title' + counter + '" class="form-control form-control-solid" placeholder="Enter sub title " rows="3" cols="3" ></textarea></div><div class="py-3 col-md-3"><input type="file" name="features_sec_features_image[]" id="features_sec_features_image' + counter + '" class="form-control form-control-solid" /></div><div class="col-md-3"><a href="javacript:void(0);" onclick="remove_features(' + counter + '); return false;" class="btn btn-danger m-b-15"><i class="bi bi-trash fs-3"></i></a></div></div></div>'
                $(".add_features").append(newInput);
                e.preventDefault();
                $("#choice" + counter).rules("add", {
                    required: true
                });
                $("#order" + counter).rules("add", {
                    required: true
                });

                counter++;
            });

            

            $("#digitalclassroompagesetting").validate({
                rules: {
                    
                },
                submitHandler: function(form) {
                    $(".loading").show();

                    var data = new FormData(form);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ route('digital-classroom-page-setting-post') }}",
                        type: 'POST',
                        contentType: false,
                        data: data,
                        processData: false,
                        cache: false,
                        success: function(response) {
                            $('.loading').hide();
                            swal({
                                title: "Status!",
                                text: response.message,
                                type: "success"
                            },
                            function() {
                                location.reload();
                            });
                        }
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
    @endsection
    @stop
</x-layout-admin-base>
