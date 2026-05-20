<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Start Teaching page setting</small>
                </h1>
            </div>
        </div>
    </div>
    <form class="kt-form" action="{{ route('teaching-page-setting-post') }}" name="teachingpagesetting" id="teachingpagesetting" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">TeachingPage hero section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="hero_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_hero_section'])) ? '' : 'teachingpage_hero_section' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="hero_sec_title" class="required form-label">Hero Image Title</label>
                                <input type="text" name="hero_sec_title" id="hero_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_hero_section'])) ? '' : $data['teachingpage_hero_section']['hero_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="hero_sec_image" class="required form-label">Hero Image</label>
                                <input type="file" name="hero_sec_image" id="hero_sec_image" class="form-control" />
                                <input id="hero_sec_oldimage" type="hidden" name="hero_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_hero_section'])) ? '' :  $data['teachingpage_hero_section']['hero_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['teachingpage_hero_section']))
                                        <a href="{{ $data['teachingpage_hero_section']['hero_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="hero_sec_description" class="required form-label">Description</label>
                                <textarea name="hero_sec_description" id="hero_sec_description" rows="10" cols="80" >
                                    @if(!(empty($data)))
                                        @if(!empty($data['teachingpage_hero_section']))
                                            {{ $data['teachingpage_hero_section']['hero_sec_description'] }}
                                        @endif
                                    @endif
                                </textarea>
                            </div>
                            <div class="py-3">
                                <label for="hero_sec_btn_url" class="required form-label">Hero Image Button URL</label>
                                <input type="text" name="hero_sec_btn_url" id="hero_sec_btn_url" class="form-control form-control-solid" placeholder="Enter Button URL " value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_hero_section'])) ? '' : $data['teachingpage_hero_section']['hero_sec_btn_url'] ) }}" required />
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
                            <span class="card-label fw-bolder fs-3 mb-1">Teachingpage Boost your income section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="sell_course_online_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_boost_income_section'])) ? '' : 'teachingpage_boost_income_section' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="teachingpage_boost_income_sec_title" class="required form-label">Title</label>
                                <input type="text" name="teachingpage_boost_income_sec_title" id="teachingpage_boost_income_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_boost_income_section'])) ? '' : $data['teachingpage_boost_income_section']['teachingpage_boost_income_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="teachingpage_boost_income_sec_image" class="required form-label">Image</label>
                                <input type="file" name="teachingpage_boost_income_sec_image" id="teachingpage_boost_income_sec_image" class="form-control" />
                                <input id="teachingpage_boost_income_sec_oldimage" type="hidden" name="teachingpage_boost_income_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_boost_income_section'])) ? '' : $data['teachingpage_boost_income_section']['teachingpage_boost_income_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['teachingpage_boost_income_section']))
                                        <a href="{{ $data['teachingpage_boost_income_section']['teachingpage_boost_income_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="teachingpage_boost_income_sec_description" class="required form-label">Description</label>
                                <textarea name="teachingpage_boost_income_sec_description" id="teachingpage_boost_income_sec_description" rows="10" cols="80" >
                                    @if(!(empty($data)))
                                        @if(!empty($data['teachingpage_boost_income_section']))
                                            {{ $data['teachingpage_boost_income_section']['teachingpage_boost_income_sec_description'] }}
                                        @endif
                                    @endif
                                </textarea>
                            </div>
                            <div class="py-3">
                                <label for="teachingpage_boost_income_sec_btnUrl" class="required form-label">Button URL</label>
                                <input type="text" name="teachingpage_boost_income_sec_btnUrl" id="teachingpage_boost_income_sec_btnUrl" class="form-control form-control-solid" placeholder="Enter Button URL " value="{{ (empty($data)) ? '' : ( (empty($data['teachingpage_boost_income_section'])) ? '' : $data['teachingpage_boost_income_section']['teachingpage_boost_income_sec_btnUrl'] ) }}" required />
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

        $(document).ready(function() {

            CKEDITOR.replace('hero_sec_description');
            CKEDITOR.replace('teachingpage_boost_income_sec_description');
            
            $("#teachingpagesetting").validate({
                rules: {
                    hero_sec_title: "required",
                    hero_sec_btn_url: "required",
                    teachingpage_boost_income_sec_title: "required",
                    teachingpage_boost_income_sec_btnUrl: "required",
                    
                },
                submitHandler: function(form) {
                    $(".loading").show();

                    var data = new FormData(form);

                    data.append('hero_sec_description', CKEDITOR.instances['hero_sec_description'].getData());
                    data.append('teachingpage_boost_income_sec_description', CKEDITOR.instances['teachingpage_boost_income_sec_description'].getData());
                                        
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ route('teaching-page-setting-post') }}",
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
