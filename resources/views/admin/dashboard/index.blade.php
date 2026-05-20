<x-layout-admin-base>
    @section('content')
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-lg-0 mb-sm-0 mb-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3"><a href="{{ route('admindashboard') }}">Dashboard</a></h1>
                </div>
            </div>
        </div>
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div id="kt_content_container" class="container-xxl">
                <div class="row g-5 g-xl-8">
                    <div class="col-xl-3">
                        <a href="{{ route('admin.organization.index') }}" class="card bg-primary hoverable card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </svg>
                                </span>
                                <div class="text-white fw-bolder fs-2 mb-2 mt-5 ">{{$total_organization}}</div>
                                <div class="fw-bold text-white">Total Organization</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3">
                        <a href="{{ route('admin.individual.index') }}" class="card bg-success hoverable card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </svg>
                                </span>
                                <div class="text-white fw-bolder fs-2 mb-2 mt-5 ">{{$total_individual}}</div>
                                <div class="fw-bold text-white">Total Individual User</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3">
                        <a href="{{ route('admin.course.index') }}" class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="file-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="svg-inline--fa fa-file-alt fa-w-12 fa-2x">
                                        <path fill="currentColor" d="M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-48-244v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12z" class=""></path>
                                    </svg>
                                </span>
                                <div class="text-white fw-bolder fs-2 mb-2 mt-5 ">{{$total_course}}</div>
                                <div class="fw-bold text-white">Total Course</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3">
                        <a href="{{ route('admin.question.index') }}" class="card bg-warning hoverable card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--la la-question-circle fa-w-16 fa-2x">
                                        <path fill="currentColor" d="M256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28zm7.67-24h-16c-6.627 0-12-5.373-12-12v-.381c0-70.343 77.44-63.619 77.44-107.408 0-20.016-17.761-40.211-57.44-40.211-29.144 0-44.265 9.649-59.211 28.692-3.908 4.98-11.054 5.995-16.248 2.376l-13.134-9.15c-5.625-3.919-6.86-11.771-2.645-17.177C185.658 133.514 210.842 116 255.67 116c52.32 0 97.44 29.751 97.44 80.211 0 67.414-77.44 63.849-77.44 107.408V304c0 6.627-5.373 12-12 12zM256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8z" class=""></path>
                                    </svg>
                                </span>
                                <div class="text-white fw-bolder fs-2 mb-2 mt-5 ">{{$total_question}}</div>
                                <div class="fw-bold text-white">Total Question</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-layout-admin-base>