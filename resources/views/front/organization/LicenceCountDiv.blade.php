<div class="row g-0" id="LicenceCountDiv">
    <div class="col bg-light-danger px-6 py-8 rounded-2 me-7 mb-7">
        <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
            {{ $data['total_licence'] }}
        </span>
        <a href="javascript:void(0)" class="text-danger fw-bold fs-6">Total Licence</a>
    </div>

    <div class="col bg-light-success px-6 py-8 rounded-2 me-7 mb-7">
        <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
            {{ $data['used_licence'] }}
        </span>
        <a href="javascript:void(0)" class="text-success fw-bold fs-6">Used Licence</a>
    </div>
    <div class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7">
        <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
            {{ $data['remaining_licence'] }}
        </span>
        <a href="javascript:void(0)" class="text-warning fw-bold fs-6">Remaining Licence</a>
    </div>

</div>