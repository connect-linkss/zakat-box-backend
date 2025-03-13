@extends('layouts.front.app')
@section('title', translate('Donate'))
@section('content')
    <div class="container mt-5 text-center">
        <h2>تبرع عشوائي</h2>
        <button id="start_donation" class="btn btn-success">ابدأ التبرع</button>
        <button id="stop_donation" class="btn btn-danger">أوقف التبرع</button>
    </div>
@endsection
@push('script_2')
    <script>
        let intervalId;

        function startRandomDonations() {
            intervalId = setInterval(() => {
                submitDonation();
            }, 1000); // Fixed interval at 1 second
        }

        function stopRandomDonations() {
            clearInterval(intervalId);
            toastr.info("تم إيقاف التبرع التلقائي.", { CloseButton: true, ProgressBar: true });
        }

        function submitDonation() {
            let randomAmount = Math.floor(Math.random() * (100 - 10 + 1)) + 10; // Random amount between 10 and 100
            let randomPhone = "70" + Math.floor(Math.random() * 9000000 + 1000000); // Random Lebanese phone number
            let data = {
                name: "متبرع عشوائي",
                phone: randomPhone,
                address: "عنوان عشوائي",
                payment_currency: "1",
                payment_type: "1",
                amount: randomAmount,
            };

            $.ajax({
                url: '/store', // Change this URL to your donation endpoint
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: JSON.stringify(data),
                contentType: 'application/json',
                processData: false,
                success: function (response) {
                    toastr.success("تم حفظ التبرع بمبلغ " + randomAmount + " دولار بنجاح!", { CloseButton: true, ProgressBar: true });
                },
                error: function (xhr) {
                    let response = xhr.responseJSON;
                    toastr.error(response?.message || "حدث خطأ.", { CloseButton: true, ProgressBar: true });
                }
            });
        }

        $(document).ready(function () {
            $("#start_donation").click(startRandomDonations);
            $("#stop_donation").click(stopRandomDonations);
        });
    </script>
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
@endpush