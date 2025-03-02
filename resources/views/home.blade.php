@extends('layouts.blank')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-3">
                <div class="card mt-3">
                    <div class="card-body text-center">
                        @php($logo = \app\CentralLogics\Helpers::get_business_settings('logo'))
                        <img class="w-200px"
                             src="{{'/public/images/'.$logo}}">
                        <br><hr>
                        <a class="btn btn-primary" href="{{route('admin.dashboard')}}">{{ translate('Dashboard') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
