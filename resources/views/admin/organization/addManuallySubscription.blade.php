<div class="modal fade" id="kt_modal_view_subscription" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-50 mw-lg-50 mw-md-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Subscribe Courses</h2>
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
                        <form class="kt-form" name="addsubscription" id="addsubscription" method="POST" >
                            @csrf
                            <div class="row">
                                
                                <div class="py-3 col-md-6 choosn-sle">
                                    <label for="select course" class="required form-label">Select Course</label>
                                    <select name="course_id" id="course_id" class="form-select form-select-solid" onchange="priceGet(this.options[this.selectedIndex].getAttribute('dataattr'));sub_day_get(this.options[this.selectedIndex].getAttribute('sub_day'));">
                                        @foreach($course as $courses)
                                            <option dataattr="{{ $courses->course_price }}" sub_day="{{ $courses->subscription_day }}" value="{{ $courses->course_id }}" >{{ $courses->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="course" class="required form-label">Actual Course Price</label>
                                    <input type="number" name="price" id="price" class="form-control form-control-solid" min="1" value="{{ old('price') }}" placeholder="Actual course price"  required="required" />
                                </div>

                                <div class="py-3 col-md-6">
                                    <label for="licence" class="required form-label">Price per Licence</label>
                                    <input type="number" name="licence_price" id="licence_price" class="form-control form-control-solid" placeholder="Enter price per licence" min="1" onblur="setAmount();"  required="required" />
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="no licence" class="required form-label">No. of Licence</label>
                                    <input type="number"  name="licence" id="licence" class="form-control form-control-solid" placeholder="Enter no of licence" min="1" onblur="setAmount();" required="required" />
                                </div>

                                <div class="py-3 col-md-6">
                                    <label for="amount" class="required form-label">Payable Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control form-control-solid" placeholder="Payable amount" min="1" readonly required="required" />
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="payment mode" class="required form-label">Payment Mode</label>
                                    <select name="payment_mode" id="payment_mode" class="form-select form-select-solid">
                                        <option>Choose Payment Mode</option>
                                        <option value="Cheque Payment">Cheque Payment</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </select>
                                </div>

                                <div class="py-3 col-md-6">
                                    <label for="transaction" class="required form-label">Transaction Number</label>
                                    <input type="text" name="transaction" id="transaction" class="form-control form-control-solid" placeholder="Enter transaction number" value="{{ old('transaction') }}"  required="required" />
                                </div>
                                <div class="py-3 col-md-6">
                                    <label for="plan days" class="required form-label">Plan Days</label>
                                    <input type="number" name="plan_days" id="plan_days" class="form-control form-control-solid" placeholder="Enter days" min="1" value="{{ old('plan_days') }}"  required="required" />
                                </div>

                                <div class="py-3 col-md-12">
                                    <label for="remark" class="required form-label">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control form-control-solid" placeholder="Enter remark" rows="2" cols="0" required></textarea>
                                </div>
                                <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">

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
    <script type="text/javascript">

        $('#kt_modal_view_subscription').modal('show');
        $('#kt_modal_view_subscription').on('hidden.bs.modal', function() {
            $(".modal").remove();
        });

        $(document).ready(function() {

            $("#course_id").select2({
                dropdownParent: $('#kt_modal_view_subscription')
            });

            var price = $('#course_id option:selected', this).attr('dataattr');
            var sub_day = $('#course_id option:selected', this).attr('sub_day');

            $('#price').val(price);
            $("#plan_days").val(sub_day);

        });

        function priceGet(price) {
            $('#price').val(price);
            return false;
        }

        function sub_day_get(days){
            $("#plan_days").val(days);
            return false;
        }

        function setAmount() {

            var price = $('#licence_price').val();
            var lic = $('#licence').val();

            if (price != '' && lic == '') {
                $('#amount').val(price);
            }
            else if (price == '' && lic != '') {
                $('#amount').val('0.00');
            }
            else {
            
                if (price > 0 && lic > 0) {
                    var amount = price * lic;
                    $('#amount').val(amount);
                }
                else {
                    $('#amount').val('0.00');
                }
            
            }
        
        }

        $("#addsubscription").submit(function() {
            $(".loading").show();

            var form = document.forms.namedItem("addsubscription");
            var data = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('orgaddmanualsubscriptionpost') }}",
                type: 'POST',
                contentType: false,
                data: data,
                processData: false,
                cache: false,
                success: function(response) {
                    $(".modal,.fade.show").remove();
                    $('.data-table').DataTable().ajax.reload();
                    $('.loading').hide();
                    swal({title: "Status!", text: response.message , type: "success"});
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

        });

    </script>
</div>