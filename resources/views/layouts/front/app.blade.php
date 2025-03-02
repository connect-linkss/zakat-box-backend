<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('/public/images/logo.png')}}">
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/vendor/icon-set/style.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/theme.minc619.css?v=1.0')}}">
    <link href="{{asset('public/assets/admin/css/dropzone.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/style.css')}}">
    @stack('css_or_js')

    <link rel="stylesheet" href="{{asset('public/assets/admin/css/toastr.css')}}">
</head>

<body class="footer-offset">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" class="d--none">
                    <div class="loader-wrap">
                        <img width="200" src="{{asset('public/assets/admin/img/loader.gif')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.front.partials._header')
    <main id="content" style="margin-top: 40px;" role="main" class="main pointer-event">
        @yield('content')
        @include('layouts.front.partials._footer')
    </main>
    <span id="message-send-successfully" data-text="{{ translate('Okay') }}"></span>
    @stack('script')
    <script src="{{asset('public/assets/admin/js/vendor.min.js')}}"></script>
    <script src="{{asset('public/assets/admin/js/theme.min.js')}}"></script>
    <script src="{{asset('public/assets/admin/js/sweet_alert.js')}}"></script>
    <script src="{{asset('public/assets/admin/js/toastr.js')}}"></script>
    {!! Toastr::message() !!}
    @if ($errors->any())
        <script>
            @foreach($errors->all() as $error)
                toastr.error('{{$error}}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    @stack('script_2')
    <audio id="myAudio">
        <source src="{{asset('public/assets/admin/sound/notification.mp3')}}" type="audio/mpeg">
    </audio>

    <script>
        let audio = document.getElementById("myAudio");

        function playAudio() {
            audio.play();
        }

        function pauseAudio() {
            audio.pause();
        }
    </script>
    <script>
        $('.route-alert').on('click', function () {
            let route = $(this).data('route');
            let message = $(this).data('message');
            route_alert(route, message)
        });
        function route_alert(route, message) {
            Swal.fire({
                title: '{{translate("Are you sure?")}}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#673ab7',
                cancelButtonText: '{{translate("No")}}',
                confirmButtonText: '{{translate("Yes")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = route;
                }
            })
        }
        $('.form-alert').on('click', function () {
            let id = $(this).data('id');
            let message = $(this).data('message');
            form_alert(id, message)
        });
        function form_alert(id, message) {
            Swal.fire({
                title: '{{translate("Are you sure?")}}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#673ab7',
                cancelButtonText: '{{translate("No")}}',
                confirmButtonText: '{{translate("Yes")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }

        let initialImages = [];
        $(window).on('load', function () {
            $("form").find('img').each(function (index, value) {
                initialImages.push(value.src);
            })
        })
        $(document).ready(function () {
            $('form').on('reset', function (e) {
                $("form").find('img').each(function (index, value) {
                    $(value).attr('src', initialImages[index]);
                })
            });
        });
    </script>

    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
</body>

</html>