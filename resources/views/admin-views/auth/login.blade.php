<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ translate('Admin') }} | {{ translate('Login') }}</title>

    @php($icon = \app\CentralLogics\Helpers::get_business_settings('fav_icon') ?? '1719234897.4746.png')
    <link rel="icon" type="image/x-icon" href="{{ asset('/public/images/' . $icon) }}">
    <link rel="shortcut icon" href="#">

    <link rel="stylesheet" href="{{asset('public/assets/admin/css/font/open-sans.css')}}">

    <link rel="stylesheet" href="{{asset('public/assets/admin/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/vendor/icon-set/style.css')}}">

    <link rel="stylesheet" href="{{asset('public/assets/admin/css/theme.minc619.css?v=1.0')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/toastr.css')}}">
</head>

<body>
    <main id="content" role="main" class="main">
        <div class="d-flex flex-column flex-md-row min-vh-100">
            <div class="d-none d-md-flex justify-content-center flex-grow-1 bg-light login-bg-box"
                data-bg-img="{{asset('public/assets/admin/img/login_bg.png')}}">
                <div class="login-left-content p-3">
                    <a class="d-flex mb-4" href="javascript:">
                        <img class="z-index-2 height-60px" src="{{ $logo }}" alt="{{ translate('Image Description') }}">
                    </a>

                    <h3 class="mb-0">{{ translate('All Service') }} <br /> {{ translate('Your') }} </h3>
                    <h2 class="text-primary font-weight-bold">{{ translate('in one field') }}....</h2>
                </div>
            </div>
            <div class="flex-grow-1 bg-white d-flex justify-content-center">
                <div class="card-content-wrap pb-5 pb-md-0">
                    <div class="card-body">
                        <div class="software-version d-flex justify-content-end" style="visibility: hidden">
                            <label
                                class="badge badge-soft-success __login-badge text-primary">{{ translate('Software version') }}
                                : {{ env('SOFTWARE_VERSION') }}</label>
                        </div>

                        <form id="form-id" action="{{route('admin.auth.login')}}" method="post">
                            @csrf
                            <div class="js-form-message form-group" style="min-width: 300px;">
                                <label class="input-label text-capitalize" for="signinSrEmail">{{translate('your')}}
                                    {{translate('email')}}</label>

                                <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                    tabindex="1" placeholder="{{ translate('email@address.com') }}"
                                    aria-label="email@address.com" required
                                    data-msg="Please enter a valid email address.">
                            </div>

                            <div class="js-form-message form-group">
                                <label class="input-label" for="signupSrPassword" tabindex="0">
                                    <span class="d-flex justify-content-between align-items-center">
                                        {{translate('password')}}
                                    </span>
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control form-control-lg"
                                        name="password" id="signupSrPassword"
                                        placeholder="{{ translate('8+ characters required') }}"
                                        aria-label="{{ translate('8+ characters required') }}" required
                                        data-msg="{{ translate('Your password is invalid. Please try again.') }}"
                                        data-hs-toggle-password-options='{
                                            "target": "#changePassTarget",
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": "#changePassIcon"
                                        }'>
                                    <div id="changePassTarget" class="input-group-append">
                                        <a class="input-group-text" href="javascript:" style="margin-left: 10px;">
                                            <i id="changePassIcon" class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                        name="remember">
                                    <label class="custom-control-label text-muted" for="termsCheckbox">
                                        {{translate('remember')}} {{translate('me')}}
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-primary">{{translate('sign_in')}}</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>



    <script src="{{asset('public/assets/admin/js/vendor.min.js')}}"></script>

    <script src="{{asset('public/assets/admin/js/theme.min.js')}}"></script>
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

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

    <script>
        "use strict";

        $(document).on('ready', function () {
            $('.js-toggle-password').each(function () {
                new HSTogglePassword(this).init()
            });

            $('.js-validate').each(function () {
                $.HSCore.components.HSValidation.init($(this));
            });
        });

    </script>

    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin/vendor/babel-polyfill/polyfill.min.js')}}"><\/script>');
    </script>
</body>

</html>