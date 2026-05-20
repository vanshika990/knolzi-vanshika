<div class="modal fade" id="kt_modal_edit_currency" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-25 mw-lg-25 mw-md-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Currency</h2>
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
                        <form class="kt-form" action="{{route('admin.currency.update',$currency->id)}}" id="updatecurrency" name="updatecurrency" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="py-3">
                                    <label for="Name" class="required form-label">Name</label>
                                    <select name="name" id="name" class="form-select form-select-solid">
                                        <option value="0">Select Name</option>
                                        @foreach($currency_list as $data)
                                        <option data-id="{{ $data['icon'] }}" data-sname="{{ $data['code'] }}" value="{{ $data['name'] }}" @if($currency->name == $data['name']) selected  @endif>{{ $data['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="short_name" id="short_name" value="{{ $currency->short_name }}">
                                </div>

                                <div class="py-3">
                                    <label for="Name" class="required form-label">INR Value</label>
                                    <input type="text" name="inr_value" class="form-control form-control-solid" placeholder="Enter Name" value="{{ $currency->inr_value }}"  required="required" readonly />
                                </div>

                                <div class="py-3">
                                    <label for="Name" class="required form-label">Rate</label>
                                    <input type="text" name="rate" class="form-control form-control-solid" placeholder="Enter Name" value="{{ $currency->rate }}"  required="required" />
                                </div>

                                <div class="py-3">
                                    <label for="Name" class="form-label">Symbol</label>&nbsp;&nbsp;
                                    <input type="hidden" name="symbol" id="symbol" value="{{ $currency->symbol }}">
                                        <div style="font-size: xxx-large;" id="symbol_icon">{{ $currency->symbol }}</div>
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
</div>
<script type="text/javascript">
    $('#kt_modal_edit_currency').modal('show');
    $('#kt_modal_edit_currency').on('hidden.bs.modal', function() {
        $(".modal").remove();
    });
    $(document).ready(function() {
        $("#updatecurrency").validate({
            rules: {
                name: "required"
            }, submitHandler: function(form) {
                var _url = '{{route("admin.currency.update",$currency->id)}}';
                var data = $("#updatecurrency").serialize();
                PostPutAjaxCall(_url, 'PUT', data);
            }
        });
    });

    $('#name').on('change', function() {
        var id = $(this).find(':selected').data('id');
        var sname = $(this).find(':selected').data('sname');
        $("#symbol").val(id);
        $("#short_name").val(sname);
        document.getElementById("symbol_icon").innerHTML = id;
    });

</script>
</div>
