<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Homepage setting</small>
                </h1>
            </div>
        </div>
    </div>
    <form class="kt-form" action="{{ route('home-page-setting-post') }}" name="homepagesetting" id="homepagesetting" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Homepage hero section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="hero_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_hero_section'])) ? '' : 'homepage_hero_section' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="hero_sec_title" class="required form-label">Hero Image Title</label>
                                <input type="text" name="hero_sec_title" id="hero_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_hero_section'])) ? '' : $data['homepage_hero_section']['hero_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="hero_sec_image" class="required form-label">Hero Image</label>
                                <input type="file" name="hero_sec_image" id="hero_sec_image" class="form-control" />
                                <input id="hero_sec_oldimage" type="hidden" name="hero_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_hero_section'])) ? '' :  $data['homepage_hero_section']['hero_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['homepage_hero_section']))
                                        <a href="{{ $data['homepage_hero_section']['hero_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="hero_sec_description" class="required form-label">Description</label>
                                <textarea name="hero_sec_description" id="hero_sec_description" rows="10" cols="80" >
                                    @if(!(empty($data)))
                                        @if(!empty($data['homepage_hero_section']))
                                            {{ $data['homepage_hero_section']['hero_sec_description'] }}
                                        @endif
                                    @endif
                                </textarea>
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="hero_sec_btn_name" class="required form-label">Hero Image Button Name</label>
                                <input type="text" name="hero_sec_btn_name" id="hero_sec_btn_name" class="form-control form-control-solid" placeholder="Enter Button Name " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_hero_section'])) ? '' : $data['homepage_hero_section']['hero_sec_btn_name'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="hero_sec_btn_url" class="required form-label">Hero Image Button URL</label>
                                <input type="text" name="hero_sec_btn_url" id="hero_sec_btn_url" class="form-control form-control-solid" placeholder="Enter Button URL " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_hero_section'])) ? '' : $data['homepage_hero_section']['hero_sec_btn_url'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="hero_broad_selection_description" class="required form-label">Broad selection description</label>
                                <input type="text" name="hero_broad_selection_description" id="hero_broad_selection_description" class="form-control form-control-solid" placeholder="Enter Button URL " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_hero_section'])) ? '' : $data['homepage_hero_section']['hero_broad_selection_description'] ) }}" required />
                            </div>
                            <div class="py-3 choosn-sle col-md-6">
                                <label for="hero_broad_selection_course" class="required form-label">Broad selection of courses</label>
                                <select name="hero_broad_selection_course[]" id="hero_broad_selection_course" class="form-select form-select-solid" multiple required>
                                    @foreach($selected_board_course as $category)
                                        <option value="{{$category['id']}}"  selected >{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Homepage Slogan Section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="slogan_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_slogan_section'])) ? '' : 'homepage_slogan_section' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="slogan_first" class="required form-label">First slogan</label>
                                <input type="text" name="slogan_first" id="slogan_first" class="form-control form-control-solid" placeholder="Enter Slogan " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_slogan_section'])) ? '' : $data['homepage_slogan_section']['slogan_first'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="slogan_first_image" class="required form-label">First slogan image</label>
                                <input type="file" name="slogan_first_image" id="slogan_first_image" class="form-control" />
                                <input id="slogan_first_oldimage" type="hidden" name="slogan_first_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_slogan_section'])) ? '' : $data['homepage_slogan_section']['slogan_first_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['homepage_slogan_section']))
                                        <a href="{{ $data['homepage_slogan_section']['slogan_first_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>

                            <div class="py-3 col-md-6">
                                <label for="slogan_second" class="required form-label">Second slogan</label>
                                <input type="text" name="slogan_second" id="slogan_second" class="form-control form-control-solid" placeholder="Enter Slogan " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_slogan_section'])) ? '' : $data['homepage_slogan_section']['slogan_second'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="slogan_second_image" class="required form-label">Second slogan image</label>
                                <input type="file" name="slogan_second_image" id="slogan_second_image" class="form-control" />
                                <input id="slogan_second_oldimage" type="hidden" name="slogan_second_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_slogan_section'])) ? '' : $data['homepage_slogan_section']['slogan_second_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['homepage_slogan_section']))
                                        <a href="{{ $data['homepage_slogan_section']['slogan_second_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>

                            <div class="py-3 col-md-6">
                                <label for="slogan_third" class="required form-label">Third slogan</label>
                                <input type="text" name="slogan_third" id="slogan_third" class="form-control form-control-solid" placeholder="Enter Slogan " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_slogan_section'])) ? '' : $data['homepage_slogan_section']['slogan_third']) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="slogan_third_image" class="required form-label">Third slogan image</label>
                                <input type="file" name="slogan_third_image" id="slogan_third_image" class="form-control" />
                                <input id="slogan_third_oldimage" type="hidden" name="slogan_third_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_slogan_section'])) ? '' : $data['homepage_slogan_section']['slogan_third_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['homepage_slogan_section']))
                                        <a href="{{ $data['homepage_slogan_section']['slogan_third_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Homepage Sell course online section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="sell_course_online_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_sell_course_online_section'])) ? '' : 'homepage_sell_course_online_section' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="sell_course_online_sec_title" class="required form-label">Title</label>
                                <input type="text" name="sell_course_online_sec_title" id="sell_course_online_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_sell_course_online_section'])) ? '' : $data['homepage_sell_course_online_section']['sell_course_online_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="sell_course_online_sec_image" class="required form-label">Image</label>
                                <input type="file" name="sell_course_online_sec_image" id="sell_course_online_sec_image" class="form-control" />
                                <input id="sell_course_online_sec_oldimage" type="hidden" name="sell_course_online_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_sell_course_online_section'])) ? '' : $data['homepage_sell_course_online_section']['sell_course_online_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['homepage_sell_course_online_section']))
                                        <a href="{{ $data['homepage_sell_course_online_section']['sell_course_online_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-12">
                                <label for="sell_course_online_sec_description" class="required form-label">Description</label>
                                <textarea name="sell_course_online_sec_description" id="sell_course_online_sec_description" rows="10" cols="80" >
                                    @if(!(empty($data)))
                                        @if(!empty($data['homepage_sell_course_online_section']))
                                            {{ $data['homepage_sell_course_online_section']['sell_course_online_sec_description'] }}
                                        @endif
                                    @endif
                                </textarea>
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="sell_course_online_sec_btn_name" class="required form-label">Button Name</label>
                                <input type="text" name="sell_course_online_sec_btn_name" id="sell_course_online_sec_btn_name" class="form-control form-control-solid" placeholder="Enter Button Name " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_sell_course_online_section'])) ? '' : $data['homepage_sell_course_online_section']['sell_course_online_sec_btn_name'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="sell_course_online_sec_btn_url" class="required form-label">Button URL</label>
                                <input type="text" name="sell_course_online_sec_btn_url" id="sell_course_online_sec_btn_url" class="form-control form-control-solid" placeholder="Enter Button URL " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_sell_course_online_section'])) ? '' : $data['homepage_sell_course_online_section']['sell_course_online_sec_btn_url'] ) }}" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Homepage Digital classroom section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="digital_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_digital_classroom_section'])) ? '' : 'homepage_digital_classroom_section' ) }}">
                        <div class="row">

                            <div class="py-3 col-md-6">
                                <label for="digital_sec_title" class="required form-label">Title</label>
                                <input type="text" name="digital_sec_title" id="digital_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_digital_classroom_section'])) ? '' : $data['homepage_digital_classroom_section']['digital_sec_title'] ) }}" required />
                            </div>
                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="digital_sec_image" class="required form-label">Image</label>
                                <input type="file" name="digital_sec_image" id="digital_sec_image" class="form-control" />
                                <input id="digital_sec_oldimage" type="hidden" name="digital_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_digital_classroom_section'])) ? '' : $data['homepage_digital_classroom_section']['digital_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['homepage_digital_classroom_section']))
                                        <a href="{{ $data['homepage_digital_classroom_section']['digital_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="digital_sec_btn_name" class="required form-label">Button Name</label>
                                <input type="text" name="digital_sec_btn_name" id="digital_sec_btn_name" class="form-control form-control-solid" placeholder="Enter Button Name " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_digital_classroom_section'])) ? '' : $data['homepage_digital_classroom_section']['digital_sec_btn_name'] ) }}" required />
                            </div>

                            <div class="py-3 col-md-6">
                                <label for="digital_sec_btn_url" class="required form-label">Button URL</label>
                                <input type="text" name="digital_sec_btn_url" id="digital_sec_btn_url" class="form-control form-control-solid" placeholder="Enter Button URL " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_digital_classroom_section'])) ? '' : $data['homepage_digital_classroom_section']['digital_sec_btn_url'] ) }}" required />
                            </div>
                            
                            <div class="py-3 col-md-12">
                                <label for="digital_sec_description" class="required form-label">Description</label>
                                <textarea name="digital_sec_description" id="digital_sec_description" rows="10" cols="80" >
                                    @if(!(empty($data)))
                                        @if(!empty($data['homepage_digital_classroom_section']))
                                            {{ $data['homepage_digital_classroom_section']['digital_sec_description'] }}
                                        @endif
                                    @endif
                                </textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Homepage Blog section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="blog_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_blog_section'])) ? '' : 'homepage_blog_section' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-12">
                                <label for="blog_sec_title" class="required form-label">Title</label>
                                <input type="text" name="blog_sec_title" id="blog_sec_title" class="form-control form-control-solid" placeholder="Enter Title " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_blog_section'])) ? '' : $data['homepage_blog_section']['blog_sec_title'] ) }}" required />
                            </div>

                            <div class="py-3 col-md-6 course-img-prevw">
                                <label for="blog_sec_image" class="required form-label">Image</label>
                                <input type="file" name="blog_sec_image" id="blog_sec_image" class="form-control" />
                                <input id="blog_sec_oldimage" type="hidden" name="blog_sec_oldimage" value="{{ (empty($data)) ? '' : ( (empty($data['homepage_blog_section'])) ? '' : $data['homepage_blog_section']['blog_sec_image'] ) }}">
                                @if(!empty($data))
                                    @if(!empty($data['homepage_blog_section']))
                                        <a href="{{ $data['homepage_blog_section']['blog_sec_image'] }}" class="btn m-b-5 mt-2" target="_blank"><i class="bi bi-eye"></i> Preview</a>
                                    @endif
                                @endif
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="blog_sec_btn_name" class="required form-label">Button Name</label>
                                <input type="text" name="blog_sec_btn_name" id="blog_sec_btn_name" class="form-control form-control-solid" placeholder="Enter Button Name " value="{{ (empty($data)) ? '' : ( (empty($data['homepage_blog_section'])) ? '' : $data['homepage_blog_section']['blog_sec_btn_name'] ) }}" required />
                            </div>

                            <div class="py-3 col-md-12">
                                <label for="blog_sec_description" class="required form-label">Description</label>
                                <textarea name="blog_sec_description" id="blog_sec_description" rows="10" cols="80" >
                                    @if(!(empty($data)))
                                        @if(!empty($data['homepage_blog_section']))
                                            {{ $data['homepage_blog_section']['blog_sec_description'] }}
                                        @endif
                                    @endif
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-2 pt-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Footer Section</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <input type="hidden" name="footer_sec_id" value="{{ (empty($data)) ? '' : ( (empty($data['footer_section'])) ? '' : 'footer_section' ) }}">
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="facebook_url" class="required form-label">Facebook url</label>
                                <input type="text" name="facebook_url" id="facebook_url" class="form-control form-control-solid" placeholder="Enter Facebook url " value="{{ (empty($data)) ? '' : ( (empty($data['footer_section'])) ? '' : $data['footer_section']['facebook_url'] ) }}" required />
                            </div>

                            <div class="py-3 col-md-6">
                                <label for="twitter_url" class="required form-label">Twitter url</label>
                                <input type="text" name="twitter_url" id="twitter_url" class="form-control form-control-solid" placeholder="Enter Twitter url " value="{{ (empty($data)) ? '' : ( (empty($data['footer_section'])) ? '' : $data['footer_section']['twitter_url'] ) }}" required />
                            </div>

                            <div class="py-3 col-md-6">
                                <label for="instagram_url" class="required form-label">Instagram url</label>
                                <input type="text" name="instagram_url" id="instagram_url" class="form-control form-control-solid" placeholder="Enter Instagram url " value="{{ (empty($data)) ? '' : ( (empty($data['footer_section'])) ? '' : $data['footer_section']['instagram_url'] ) }}" required />
                            </div>
                            
                            <div class="py-3 col-md-6">
                                <label for="linkedin_url" class="required form-label">Linkedin url</label>
                                <input type="text" name="linkedin_url" id="linkedin_url" class="form-control form-control-solid" placeholder="Enter Linkedin url " value="{{ (empty($data)) ? '' : ( (empty($data['footer_section'])) ? '' : $data['footer_section']['linkedin_url']) }}" required />
                            </div>
                            
                            <div class="py-3 col-md-12">
                                <label for="youtube_url" class="required form-label">Youtube url</label>
                                <input type="text" name="youtube_url" id="youtube_url" class="form-control form-control-solid" placeholder="Enter Youtube url " value="{{ (empty($data)) ? '' : ( (empty($data['footer_section'])) ? '' : $data['footer_section']['youtube_url']) }}" required />
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
            CKEDITOR.replace('sell_course_online_sec_description');
            CKEDITOR.replace('digital_sec_description');
            CKEDITOR.replace('blog_sec_description');

            $("#hero_broad_selection_course").select2({
                placeholder: "Search Category",
                ajax: {
                    url: "{{ route('searchcategory') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            "_token": "{{ csrf_token() }}",
                            searchTerm: params.term,
                        };
                    },
                    templateSelection: function(data, container) {
                        // Add custom attributes to the <option> tag for the selected option
                        $(data.element).attr('data-custom-attribute', data.customValue);
                        return data.text;
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            
            $("#homepagesetting").validate({
                rules: {
                    hero_sec_title: "required",
                    hero_sec_btn_url: "required",
                    hero_sec_btn_name: "required",
                    hero_broad_selection_description: "required",
                    'hero_category_id[]': {required: true},
                    slogan_first: "required",
                    slogan_second: "required",
                    slogan_third: "required",
                    sell_course_online_sec_title: "required",
                    sell_course_online_sec_btn_name: "required",
                    sell_course_online_sec_btn_url: "required",
                    digital_sec_title: "required",
                    digital_sec_subtitle: "required",
                    digital_sec_btn_name: "required",
                    digital_sec_btn_url: "required",
                    blog_sec_title: "required",
                    blog_sec_btn_url: "required",
                    facebook_url : "required",
                    twitter_url : "required",
                    youtube_url : "required",
                    instagram_url : "required",
                    linkedin_url : "required",
                },
                submitHandler: function(form) {
                    $(".loading").show();

                    var data = new FormData(form);

                    data.append('hero_sec_description', CKEDITOR.instances['hero_sec_description'].getData());
                    data.append('sell_course_online_sec_description', CKEDITOR.instances['sell_course_online_sec_description'].getData());
                    data.append('digital_sec_description', CKEDITOR.instances['digital_sec_description'].getData());
                    data.append('blog_sec_description', CKEDITOR.instances['blog_sec_description'].getData());
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ route('home-page-setting-post') }}",
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
