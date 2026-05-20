@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- <div>
                        <h1>docs.google.com</h1>
                    </div>
                    <iframe src="https://docs.google.com/gview?url=https://devapp.edupme.com/storage/image/dummy.pdf&embedded=true" frameborder="0" width='500px' height='700px'> </iframe>
                    <iframe src="https://docs.google.com/gview?url=https://devapp.edupme.com/storage/image/SampleDOCFile_1000kb.doc&embedded=true" frameborder="0" width='500px' height='700px'> </iframe>
                    <iframe src="https://docs.google.com/gview?url=https://devapp.edupme.com/storage/image/tests-example.xls&embedded=true" frameborder="0" width='500px' height='700px'> </iframe>
                    <iframe src="https://docs.google.com/gview?url=https://devapp.edupme.com/storage/image/sample.ppt&embedded=true" frameborder="0" width='500px' height='700px'> </iframe>
                     -->
                    <div>
                        <h1>officeapps</h1>
                    </div>
                    <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=https://devapp.edupme.com/storage/image/tests-example.xls' width='550px' height='500px' frameborder='0'></iframe>
                    <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=https://devapp.edupme.com/storage/image/SampleDOCFile_1000kb.doc' width='550px' height='500px' frameborder='0'></iframe>
                    <!-- <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=https://devapp.edupme.com/storage/image/dummy.pdf' width='550px' height='500px' frameborder='0'></iframe> -->
                    <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=https://devapp.edupme.com/storage/image/sample.ppt' width='550px' height='500px' frameborder='0'></iframe>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection