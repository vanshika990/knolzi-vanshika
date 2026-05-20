<div class="modal fade" id="kt_modal_view_roles" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Role</h2>
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
                        <form class="kt-form" action="{{ route('admin.roles.store') }}" name="createroles" id="createroles" method="POST">
                            @csrf
                            <div class="row">
                                <div class="py-3 col-xxl-6">
                                    <label for="Name" class="required form-label">Name</label>
                                    <input type="text" name="name" class="form-control form-control-solid" placeholder="Enter Name" value="{{ old('name') }}"  required="required" />
                                </div>
                                <div class="py-3 col-xxl-6">
                                    <label for="Display Name" class="required form-label">Display Name</label>
                                    <input type="text" name="display_name" class="form-control form-control-solid" placeholder="Enter Display Name" value="{{ old('display_name') }}"  required="required" />
                                </div>
                            </div>
                            <div class="py-3">
                                <label for="Description" class="required form-label">Description</label>
                                <textarea name="description" class="form-control form-control-solid" placeholder="Enter Description">{{ old('description') }}</textarea>
                            </div>
                            <div class="py-3">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-tools">
                                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" role="tablist">
                                            @php
                                                $i=0;
                                            @endphp
                                            @if(!empty($modules))
                                                @foreach($modules as $key => $module)
                                                    <li class="nav-item">
                                                        <a class="nav-link @if($i==0) show active @endif" data-bs-toggle="tab" href="#m_tab_{{ $key }}">
                                                            <i class="flaticon-share m--hide"></i>
                                                            {{ $module }}
                                                        </a>
                                                    </li>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    @if(!empty($modules))
                                        @foreach($modules as $key => $module)
                                            <div class="tab-pane fade @if($key==0) show active @endif" id="m_tab_{{ $key }}">
                                                <div class="py-3">
                                                    @if(isset($permissions[$module]))
                                                        @foreach($permissions[$module] as $permission)
                                                            <div class="form-check mb-4">
                                                                <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->id }}">
                                                                <label class="form-check-label">
                                                                    {{ $permission->display_name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="py-3">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#kt_modal_view_roles').modal('show');
        $(document).ready(function () {
            $("#createroles").validate({
                rules: {
                    name: "required",
                    display_name: "required",
                    description: "required",
                    'permission[]': "required",
                }, submitHandler: function (form) {
                    $(".loading").show();
                    var _url = '{{ route("admin.roles.store") }}';
                    var data = $("#createroles").serialize();
                    PostPutAjaxCall(_url, 'POST', data);
                }
            });
        });
    </script>
</div>
