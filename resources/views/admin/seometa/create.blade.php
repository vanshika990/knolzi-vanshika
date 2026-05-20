<div class="modal fade" id="kt_modal_create_seometa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50 mw-lg-50 mw-md-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>SEO meta</h2>
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
                        <form class="kt-form" action="{{ route('admin.seometa.store') }}" name="createseometa" id="createseometa" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="py-3 col-md-6">
                                    <label for="Page" class="required form-label">Select Page</label>
                                    <select name="page_slug" class="form-select form-select-solid" required>
                                        <option value="">Select Option</option>
                                        <option value="homepage-after-login">Homepage after login</option>
                                        <option value="homepage-before-login">Homepage before login</option>
                                        <option value="term-&-condition">Term & Condition</option>
                                        <option value="privacy-policy">Privacy Policy</option>
                                        <option value="contact-us">Contact us</option>
                                        <option value="digital-class">Digital Class</option>
                                        <option value="start-teaching">Start Teaching</option>
                                        <option value="search-page">Search Page</option>
                                        <option value="disclaimer">Disclaimer</option>
                                        <option value="login">Login</option>
                                        <option value="register">Register</option>
                                        <option value="sitemap">Sitemap</option>
                                        <option value="about-us">About Us</option>
                                    </select>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="meta title" class="required form-label">SEO meta title</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control form-control-solid" placeholder="Enter SEO meta title" value="{{ old('meta_title') }}" required />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="meta keyword" class="required form-label">SEO meta keyword</label>
                                    <input type="text" name="meta_keyword" id="meta_keyword" class="form-control form-control-solid" placeholder="Enter SEO meta keyword" value="{{ old('meta_keyword') }}" required />
                                </div>
                                <div class="py-3 col-md-12">
                                    <label for="meta description" class="required form-label">SEO meta description</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control form-control-solid" rows="3" cols="119" placeholder="Enter SEO meta description..." required ></textarea>
                                </div>
                                <div class="py-3">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-sm btn-primary"> Submit </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#kt_modal_create_seometa').modal('show');
    $(document).ready(function() {

        $("#createseometa").validate({
            rules: {
                meta_title: "required",
                meta_keyword: "required",
                meta_description: "required",
                page_slug: "required",
            },
            submitHandler: function(form) {
                $(".loading").show();
                var _url = '{{ route("admin.seometa.store") }}';
                var data = $("#createseometa").serialize();
                PostPutAjaxCall(_url, 'POST', data);
            }
        });
    });
</script>
</div>
