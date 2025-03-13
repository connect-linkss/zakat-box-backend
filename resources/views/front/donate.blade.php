@extends('layouts.front.app')
@section('title', translate('Donate'))
@section('content')
<div class="content container-fluid" style="padding-top: 5px">

    <div class="d-flex justify-content-center mb-4">
        @php($logo = \app\CentralLogics\Helpers::get_business_settings('logo'))
        <img style="width: 150px;" src="{{'/public/images/' . $logo}}">
    </div>

    <div class="add-services-container">
        <div class="col-sm-12 col-md-10 col-lg-8  mb-12 mx-auto card" style="max-width: 500px;">
            <div class="card-body">
                <div class="pl-2">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label class="input-label">{{ translate('الاسم الثلاثي') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="{{ translate('') }}"
                                required id="name_input"
                                oninput="this.value = this.value.replace(/\b\w/g, char => char.toUpperCase())">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="input-label">{{ translate('رقم الهاتف') }}<span
                                    class="input-label-secondary text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" id="phone_input"
                                placeholder="{{ translate('') }}">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="input-label">{{ translate('العنوان (اختياري)') }}</label>
                            <input type="text" name="address" class="form-control" id="address_input"
                                placeholder="{{ translate('') }}">
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{translate('آلية الدفع')}}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <div class="d-flex gap-4" style="margin-top: 18px">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="payment_type_cash" value="1" checked>
                                        <label class="form-check-label" for="payment_type_cash">نقدا</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="payment_type_later" value="2">
                                        <label class="form-check-label" for="payment_type_later">مراجعة لاحقا</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{translate('العملة')}}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <select name="brand" class="form-control payment_currency_select "
                                    id="payment_currency_select">
                                    <option value="1">دولار</option>
                                    <option value="2">لبناني</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{translate('قيمة التبرع')}}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="numeric" name="amount" class="form-control" value="" id="amount_input"
                                    placeholder="{{ translate('0') }}">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <form action="javascript:" id='order_place' method="post">
                                <button type="submit" class="btn  btn-primary btn-block"><i
                                        class="fa fa-shopping-bag"></i>
                                    {{translate('تبرع')}} </button>
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
                <div class="d-flex align-items-center gap-2 justify-content-center" style="flex-direction: column;">
                    <div class="d-flex justify-content-center mb-4">
                        <img style="width: 150px;" src="{{'/public/images/' . $logo}}">
                    </div>
                    <p style="font-size: 24px; margin-bottom: 30px;">أخلف الله عليكم من فضله</p>
                    <a href="{{ route('indexCustomer') }}"
                        class="btn btn-success non-printable">{{ translate('الذهاب للصفحة الرئيسية') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script_2')
    <script>
        "use strict";
        document.getElementById('phone_input').addEventListener('input', function () {
            let phone = this.value.trim();
            let lebanonPhonePattern = /^(03|70|71|76|78|79)\d{6}$/;
            if (!lebanonPhonePattern.test(phone)) {
                this.setCustomValidity("Please enter a valid Lebanese phone number (8 digits starting with 03, 70, 71, 76, 78, or 79).");
            } else {
                this.setCustomValidity("");
            }
        });

        document.getElementById('amount_input').addEventListener('input', function () {
            let arabicNumbers = "٠١٢٣٤٥٦٧٨٩";
            let englishNumbers = "0123456789";
            let rawValue = this.value.replace(/[٠١٢٣٤٥٦٧٨٩]/g, d => englishNumbers[arabicNumbers.indexOf(d)]);
            rawValue = rawValue.replace(/[^0-9.]/g, '');
            if (!isNaN(rawValue) && rawValue !== '') {
                this.value = Number(rawValue).toLocaleString('en-US');
            }
        });
        document.getElementById('order_place').addEventListener('submit', function (e) {
            e.preventDefault();
            function convertArabicToEnglish(text) {
                if (!text) return "";
                let arabicNumbers = "٠١٢٣٤٥٦٧٨٩";
                return text.replace(/[٠١٢٣٤٥٦٧٨٩]/g, d => arabicNumbers.indexOf(d));
            }
            let amountField = document.getElementById('amount_input');
            let rawAmount = convertArabicToEnglish(amountField.value).replace(/,/g, '');
            let formIsValid = true;
            let phone = convertArabicToEnglish($('#phone_input').val().trim());
            let phoneForValidation = phone.replace(/[٠١٢٣٤٥٦٧٨٩]/g, d => "٠١٢٣٤٥٦٧٨٩".indexOf(d));
            let lebanonPhonePattern = /^(03|70|71|76|81|78|79)\d{6}$/;
            if (!lebanonPhonePattern.test(phone)) {
                toastr.error("الرجاء إدخال رقم هاتف لبناني صحيح.", { CloseButton: true, ProgressBar: true });
                formIsValid = false;
            }

            if (rawAmount === '' || isNaN(rawAmount) || parseFloat(rawAmount) <= 0) {
                toastr.error("الرجاء إدخال قيمة تبرع صالحة (يجب أن يكون رقمًا أكبر من 0).", { CloseButton: true, ProgressBar: true });
                formIsValid = false;
            }

            if (formIsValid) {
                $('#loading').show();
                let data = {
                    name: $('#name_input').val(),
                    phone: $('#phone_input').val(),
                    address: $('#address_input').val(),
                    payment_currency: $('#payment_currency_select').val(),
                    payment_type: $('input[name="payment_type"]:checked').val(),
                    amount: rawAmount,
                };
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
                            // toastr.success('{{ translate("donate saved successfully!") }}', { CloseButton: true, ProgressBar: true });
                            $('#thanks_message').modal('show');
                            $('#name_input').val('');
                            $('#phone_input').val('');
                            $('#address_input').val('');
                            $('#amount_input').val(0);
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
                toastr.error('{{ translate("يرجى ملء جميع الحقول المطلوبة.") }}', { CloseButton: true, ProgressBar: true });
            }
        });
    </script>
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
@endpush