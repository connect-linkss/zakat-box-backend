<div id="headerMain">
    <header id="header"
        class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">

            <div class="navbar-brand-wrapper">
                @php($logo = \app\CentralLogics\Helpers::get_business_settings('logo'))
                <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="">
                    <img class="navbar-brand-logo" src="{{'/public/images/' . $logo}}">
                </a>
            </div>


            <div class="navbar-nav-wrap-content-right">
                <ul class="navbar-nav align-items-center flex-row">

                    <li class="nav-item ml-md-3">
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper media align-items-center gap-3 bg-transparent dropdown-toggle dropdown-toggle-left-arrow"
                                href="javascript:;" data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="d-none d-md-block media-body text-right">
                                    <h5 class="profile-name text-capitalize mb-0">{{auth('web')->user()->f_name}}</h5>
                                    <span class="fs-12 text-capitalize">{{ translate('Super Admin') }}</span>
                                </div>
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="{{auth('web')->user()->image}}"
                                        alt="{{ translate('Image') }}">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                                <div class="dropdown-item-text">
                                    <div class="media gap-3 align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img" src="{{auth('web')->user()->image}}"
                                                alt="{{ translate('Image') }}">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{auth('web')->user()->name}}</span>
                                            <span class="card-text">{{auth('web')->user()->email}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{route('admin.settings')}}">
                                    <span class="text-truncate pr-2" title="Settings">settings</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title:'{{translate("Do you want to logout?")}}',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#673ab7',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: '{{translate("Yes")}}',
                                    cancelButtonText: '{{translate("No")}}',
                                    denyButtonText: `{{translate("Don't Logout")}}`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('admin.auth.logout')}}';
                                    } else{
                                    Swal.fire('{{ translate("Canceled")}}', '', 'info')
                                    }
                                    })">
                                    <span class="text-truncate pr-2" title="Sign out">sign_out</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
