<div class="modal fade" id="kt_modal_edit_seometa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50 mw-lg-50 mw-md-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit SEO meta</h2>
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
                        <form class="kt-form" action="{{route('admin.seometa.update',encrypt($data->id))}}" id="updateseometa" name="updateseometa" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="Page" class="required form-label">Select Page</label>
                                    <select name="page_slug" class="form-select form-select-solid" disabled>
                                        <option value="homepage-after-login" @if($data->slug == 'homepage-after-login') selected @endif >Homepage after login</option>
                                        <option value="homepage-before-login" @if($data->slug == 'homepage-before-login') selected @endif >Homepage before login</option>
                                        <option value="term-&-condition" @if($data->slug == 'term-&-condition') selected @endif >Term & Condition</option>
                                        <option value="privacy-policy" @if($data->slug == 'privacy-policy') selected @endif >Privacy Policy</option>
                                        <option value="contact-us" @if($data->slug == 'contact-us') selected @endif >Contact us</option>
                                        <option value="digital-class" @if($data->slug == 'digital-class') selected @endif >Digital Class</option>
                                        <option value="start-teaching" @if($data->slug == 'start-teaching') selected @endif >Start Teaching</option>
                                        <option value="search-page" @if($data->slug == 'search-page') selected @endif >Search Page</option>
                                        <option value="disclaimer" @if($data->slug == 'disclaimer') selected @endif>Disclaimer</option>
                                        <option value="login" @if($data->slug == 'login') selected @endif>Login</option>
                                        <option value="register" @if($data->slug == 'register') selected @endif>Register</option>
                                        <option value="sitemap"  @if($data->slug == 'sitemap') selected @endif>Sitemap</option>
                                        <option value="about-us" @if($data->slug == 'about-us') selected @endif>About Us</option>
                                    </select>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="meta title" class="required form-label">SEO meta title</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control form-control-solid" placeholder="Enter SEO meta title" value="{{ $data->title }}" required />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="meta keyword" class="required form-label">SEO meta keyword</label>
                                    <input type="text" name="meta_keyword" id="meta_keyword" class="form-control form-control-solid" placeholder="Enter SEO meta keyword" value="{{ $data->keyword }}" required />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="meta description" class="required form-label">SEO meta description</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control form-control-solid" rows="3" cols="119" placeholder="Enter SEO meta description..." required >{{ $data->description }}</textarea>
                                </div>
                                <div class="py-3">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#kt_modal_edit_seometa').modal('show');
        $(document).ready(function() {
            
            $("#updateseometa").validate({
                rules: {
                    meta_title: "required",
                    meta_keyword: "required",
                    meta_description: "required",
                },
                submitHandler: function(form) {
                    $(".loading").show();
                    var _url = '{{route("admin.seometa.update",encrypt($data->id))}}';
                    var data = $("#updateseometa").serialize();
                    PostPutAjaxCall(_url, 'PUT', data);
                }
            });
        });

    </script>
</div>