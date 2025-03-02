@extends('layouts.admin.app')

@section('title', translate('business_setup'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/business-setup.png')}}" alt="{{ translate('business-setup') }}">
                {{translate('business_Setup')}}
            </h2>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="d-flex align-items-center gap-2 mb-0">
                    <i class="tio-settings"></i>
                    {{ translate('General settings form') }}
                </h4>
            </div>
            <div class="card-body">
                <form action="{{route('admin.business-settings.update-setup')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        @php($name=\app\CentralLogics\Helpers::get_business_settings('restaurant_name'))
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('restaurant Name')}}</label>
                                <input type="text" name="restaurant_name" value="{{$name}}" class="form-control"
                                       placeholder="{{ translate('ABC Company') }}" required>
                            </div>
                        </div>


                        @php($phone=\app\CentralLogics\Helpers::get_business_settings('phone'))
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('phone')}}</label>
                                <input type="text" value="{{$phone}}" name="phone" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        @php($email=\app\CentralLogics\Helpers::get_business_settings('email_address'))
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('email')}}</label>
                                <input type="email" value="{{$email}}"
                                       name="email" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        @php($address=\app\CentralLogics\Helpers::get_business_settings('address'))
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('address')}}</label>
                                <input type="text" value="{{$address}}"
                                       name="address" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>


                        @php($footer_text=\app\CentralLogics\Helpers::get_business_settings('footer_text'))
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('footer')}} {{translate('text')}}</label>
                                <input type="text" value="{{$footer_text}}"
                                       name="footer_text" class="form-control" placeholder="" required>
                            </div>
                        </div>


                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                class="btn btn-primary demo-form-submit">{{translate('submit')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/business-settings.js') }}"></script>
@endpush
