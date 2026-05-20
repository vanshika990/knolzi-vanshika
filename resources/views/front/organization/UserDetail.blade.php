<div class="modal fade" id="kt_modal_view_user_detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="mb-0">User Details</h2>
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
                <div class="card mb-xl-8">
                    <div class="card-body p-3">
                        <div class="mb-10">
                            <div class="symbol symbol-200px w-100">
                                <div class="cnter-prf-img text-center">
                                    @if (!isset($user->profile_image) && $user->profile_image != "")
                                        <img src="{{ $user->profile_image }}" alt="User Logo" class="w-50">
                                    @else
                                        <img src="{{asset('assets/img/default.png')}}" alt="User Logo" class="w-50">
                                    @endif
                                </div>
                                <div class="mt-4 mb-2">
                                    <div class="fw-bold fs-6 mb-4 text-center">
                                        <div class="mb-2">
                                            <a href="javascript:void(0)" class="text-primary fs-3 fw-bolder me-1 d-flex align-items-center justify-content-center">
                                                {{ $user->name }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="#00A3FF"></path>
                                                    <path class="permanent" d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <a href="javascript:void(0)" class="d-block text-primary mb-2">
                                            <span class="fas fa-user me-1"></span>
                                            {{ $user->getRoleNames()->first() }}
                                        </a>
                                        <a href="mailto:{{ $user->email }}" class="d-block text-primary mb-2">
                                            <span class="fas fa-envelope me-1"></span>
                                            {{ $user->email }}
                                        </a>
                                    </div>
                                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                        
                                        @if ($user->mobile_no != "")
                                        <tr>
                                            <th><b>Phone</b></th>
                                            <td><a href="tel:{{ $user->mobile_no }}">{{ $user->mobile_no }}</a></td>
                                        </tr>
                                        @endif

                                        @if ($user->age_group != "")
                                        <tr>
                                            <th><b>Age Group</b></th>
                                            <td>{{ $user->age_group }}</td>
                                        </tr>
                                        @endif
                                        @if ($user->skillstest != "")
                                        <tr>
                                            <th><b>Skill Test</b></th>
                                            <td>{{ $user->skillstest }}</td>
                                        </tr>
                                        @endif
                                        @if ($user->goal != "")
                                        <tr>
                                            <th><b>Goal</b></th>
                                            <td>{{ $user->goal }}</td>
                                        </tr>
                                        @endif
                                        @if ($user->email_verified_at != "")
                                        <tr>
                                            <th><b>Email Verified At</b></th>
                                            <td>{{ $user->email_verified_at }}</td>
                                        </tr>
                                        @endif
                                        @if ($user->last_login_time != "")
                                        <tr>
                                            <th><b>Last Login Time</b></th>
                                            <td>{{ $user->last_login_time }}</td>
                                        </tr>
                                        @endif
                                        @if ($user->status != "")
                                        <tr>
                                            <th><b>Status</b></th>
                                            <td>
                                                @if ($user->status == "1")
                                                    <button name="status" class="btn btn-sm btn-success">Active</button>
                                                @elseif($user->status == "2")
                                                    <button name="status" class="btn btn-sm btn-danger">Deactive</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($user->created_at !== "")
                                        <tr>
                                            <th><b>Create Date</b></th>
                                            <td>{{ $user->created_at }}</td>
                                        </tr>
                                        @endif
                                        @if ($user->updated_at !== "")
                                        <tr>
                                            <th><b>Update Date</b></th>
                                            <td>{{ $user->updated_at }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $('#kt_modal_view_user_detail').modal('show');
    $('#kt_modal_view_user_detail').on('hidden.bs.modal', function() {
        $("#kt_modal_view_user_detail").remove();
        $("#kt_modal_view_course_User").show();
    });
</script>
</div>
