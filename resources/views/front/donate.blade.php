@extends('layouts.front.app')
@section('title', translate('POS'))
@section('content')
    <div class="content container-fluid" style="padding-top: 5px">

        <div class="header-title-page mb-4">
            <h3>{{translate('donate') }} </h3>
        </div>

        <div class="add-services-container">
            <div class="col-sm-12 col-md-10 col-lg-8  mb-12 mx-auto card">
                <div class="card-body">
                    <div class="pl-2">
                        <div class="row">
                            <div class="col-sm-3 form-group">
                                <label class="input-label">{{ translate('Name') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="{{ translate('Name') }}"
                                    required id="name_input"
                                    oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase())">
                            </div>
                            <div class="col-sm-3 form-group">
                                <label class="input-label">{{ translate('phone') }}</label>
                                <input type="text" name="phone" class="form-control" id="phone_input"
                                    placeholder="{{ translate('phone') }}">
                            </div>
                            <div class="col-sm-3 form-group">
                                <label class="input-label">{{ translate('address') }}</label>
                                <input type="text" name="address" class="form-control" id="address_input"
                                    placeholder="{{ translate('address') }}">
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="input-label">{{translate('payment_currency')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="brand" class="form-control payment_currency_select "
                                        id="payment_currency_select">
                                        <option value="1">dollar</option>
                                        <option value="2">lebanon</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="input-label">{{translate('payment_type')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="brand" id="payment_type_select" class="form-control payment_type_select ">
                                        <option value="1">now</option>
                                        <option value="2">latter</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="input-label">{{translate('amount')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="numeric" name="amount" class="form-control" value="0" id="amount_input"
                                        placeholder="{{ translate('amount') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <form action="javascript:" id='order_place' method="post">
                                    <button type="submit" class="btn  btn-primary btn-block"><i
                                            class="fa fa-shopping-bag"></i>
                                        {{translate('donate')}} </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="thanks_message" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 justify-content-center">
                        <a href="{{ route('index') }}"
                            class="btn btn-success non-printable">{{ translate('see_the donation list') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_2')
    <script>
        "use strict";
        document.getElementById('order_place').addEventListener('submit', function (e) {
            e.preventDefault();
            let formIsValid = true;
            $('#loading').show();
            let data = {
                name: $('#name_input').val(),
                phone: $('#phone_input').val(),
                address: $('#address_input').val(),
                payment_currency: $('#payment_currency_select').val(),
                payment_type: $('#payment_type_select').val(),
                amount: $('#amount_input').val(),
            };

            if (formIsValid) {
                $.ajax({
                    url: '{{ route('store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    processData: false,
                    success: function (response) {
                        $('#loading').hide();
                        if (response.errors) {
                            toastr.error('{{ translate("Error occurred while saving order.") }}', { CloseButton: true, ProgressBar: true });
                        } else {
                            toastr.success('{{ translate("donate saved successfully!") }}', { CloseButton: true, ProgressBar: true });
                            $('#thanks_message').modal('show');
                            print_invoice(response.id);
                        }
                    },
                    error: function (xhr) {
                        $('#loading').hide();
                        let response = xhr.responseJSON;
                        toastr.error(response?.message || '{{ translate("An error occurred.") }}', { CloseButton: true, ProgressBar: true });
                    }
                });
            } else {
                $('#loading').hide();
                toastr.error('{{ translate("Please fill all required fields.") }}', { CloseButton: true, ProgressBar: true });
            }
        });
    </script>
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
@endpush