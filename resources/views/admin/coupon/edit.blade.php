<x-layout-admin-base>
    @section('content')
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Coupon</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border pt-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Edit Coupon</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <form class="kt-form" action="{{route('admin.coupon.update',$coupon->coupon_id)}}" id="updatecourse" name="updatecourse" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <?php $coupon_id = $coupon->coupon_title; ?>
                        <div class="row">
                            <div class="py-3 col-md-6">
                                <label for="Name" class="required form-label">Coupon Title</label>
                                <input type="text" name="coupon_title" id="coupon_title" class="form-control form-control-solid" placeholder="Enter Coupon Title" value="{{ $coupon->coupon_title }}" required />
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="Name" class="required form-label">Select Courses</label>
                                <select class="form-control form-control-solid select-box mt-multiselect" name="course_id[]" id="course_id" multiple>
                                    @foreach($course as $row)
                                    @if(in_array($row['id'],$coupon_course))
                                    <option value="{{ $row['id'] }}" selected>{{ $row['name'] }}</option>
                                    @else
                                    <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div> 

                            <div class="py-3 col-md-6">
                                <label for="coupon_type" class="required form-label">Coupon type</label>
                                <select class="form-control form-control-solid" name="coupon_type" id="coupon_type" >
                                    <option value="0" @if( $coupon->coupon_type == '0') selected @endif >Free</option>
                                    <option value="1" @if( $coupon->coupon_type == '1') selected @endif >Percentage</option>
                                </select>
                            </div>

                            <div class="py-3 col-md-6" id="duration" style=@if( $coupon->coupon_type == '1') {{ 'display:none' }} @else {{ 'display:block' }} @endif>
                                <label for="coupon_duration" class="required form-label">Course Duration</label>
                                <input type="number" class="form-control form-control-solid" name="coupon_duration" placeholder="Course Duration" min="0" value="{{ $coupon->coupon_duration }}">
                            </div>

                            <div class="py-3 col-md-6" id="percentage" style=@if( $coupon->coupon_type == '1') {{ 'display:block' }} @else {{ 'display:none' }} @endif>
                                <label for="coupon_duration" class="required form-label">Percentage</label>
                                <input type="number" class="form-control form-control-solid" name="percentage" placeholder="Percentage" min="0" value="{{ $coupon->coupon_percentage }}">
                            </div>                          

                            <div class="py-3 col-md-6">
                                <label for="start_date" class="required form-label">Start Date</label>
                                <input type="date" class="form-control form-control-solid" name="start_date"  value="{{ $coupon->coupon_start_date ? $coupon->coupon_start_date : date('Y-m-d') }}">
                            </div>
                            <div class="py-3 col-md-6">
                                <label for="end_date" class="required form-label">End Date</label>
                                <input type="date" class="form-control form-control-solid" name="end_date" min="{{ date('Y-m-d') }}" value="{{ $coupon->coupon_end_date ? $coupon->coupon_end_date : date('Y-m-d') }}">
                            </div>

                            <div class="py-3 col-md-12">
                                <label for="code" class="required form-label">Coupon Code</label>
                                <input type="text" class="form-control form-control-solid" name="code" placeholder="Coupon Code" value="{{ $coupon->coupon_code }}" readonly>
                            </div> 

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
    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#course_id').multiselect();
            $('#coupon_type').on('change', function() {
                var type = $(this).val();
                if (type == 1) {
                    $('#percentage').show();
                    $('#duration').hide();
                }
                else {
                    $('#percentage').hide();
                    $('#duration').show();
                }
            });

            var coupon_id = "<?php echo $coupon->coupon_id; ?>";
            $("#updatecourse").validate({
                rules: {
                    coupon_title: "required"
                }, submitHandler: function(form) {

                    $(".loading").show();
                    var data = new FormData(form);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{route("admin.coupon.update",$coupon->coupon_id)}}",
                        type: 'POST',
                        contentType: false,
                        data: data,
                        processData: false,
                        cache: false,
                        success: function(response) {

                            $('.loading').hide();
                            swal({title: "Status!", text: response.message, type: "success"},
                            function() {
                                window.location.href = "{{ route('admin.coupon.index') }}";
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