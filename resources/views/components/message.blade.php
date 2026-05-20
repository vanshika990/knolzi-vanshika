@if ($message = Session::get('success_message'))
<!--begin::Alert-->
<div class="alert alert-success d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
        <h5 class="mb-1">Success!</h5>
        <p>{{ $message }}</p>
    </div>
</div>
@endif
@if($errors->any())
<!--begin::Alert-->
<div class="alert alert-danger d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
    <div>
        <h5 class="mb-1">Opps Something went wrong</h5>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
<!--end::Alert-->
@endif