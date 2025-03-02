<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container text-capitalize">
            <div class="navbar-vertical-footer-offset">
                <div class="d-flex align-items-center gap-3 py-2 px-3 justify-content-between">
                    @php($logo = \app\CentralLogics\Helpers::get_business_settings('logo'))
                    <a class="navbar-brand w-75" href="{{route('admin.order.list')}}" aria-label="Front">
                        <img class="navbar-brand-logo"
                             alt="{{ translate('logo') }}"
                             src="{{'/public/images/'.$logo}}"
                        >
                        <img class="navbar-brand-logo-mini"
                             alt="{{ translate('logo') }}"
                             src="{{'/public/images/'.$logo}}"
                        >
                    </a>
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mt-1">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                           title="Expand"></i>
                    </button>
                </div>
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- <li class="navbar-vertical-aside-has-menu {{Request::is('admin')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.dashboard')}}" title="{{translate('Dashboards')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('dashboard')}}
                                </span>
                            </a>
                        </li> -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/order*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.order.list')}}">
                                <i class="tio-shopping-cart nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('orders')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.customer.list')}}">
                                <i class="tio-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('customer')}}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>
