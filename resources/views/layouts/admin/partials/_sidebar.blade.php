<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container text-capitalize">
            <div class="navbar-vertical-footer-offset">
                <div class="d-flex align-items-center gap-3 py-2 px-3 justify-content-between">
                    @php($logo = \app\CentralLogics\Helpers::get_business_settings('logo'))
                    <a class="navbar-brand w-75" href="{{route('admin.dashboard')}}" aria-label="Front">
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
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.dashboard')}}" title="{{translate('Dashboards')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('dashboard')}}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/pos/pos')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.pos.index')}}">
                                <i class="tio-shopping nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('pos')}}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/order*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.order.list')}}">
                                <i class="tio-shopping-cart nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('orders')}}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/service*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.service.list')}}">
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('service')}}
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
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/staff*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.staff.list')}}">
                                <i class="tio-group-equal nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('staff')}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{translate('payment')}}">{{translate('payment')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/payment*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.payment.list')}}">
                                <i class="tio-chart-pie-1 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('transaction')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/expense*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle">
                                <i class="tio-money-vs nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('expenses')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/expense*')?'block':'none'}}">
                                <li class="nav-item {{Request::is('admin/expense/list') ?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.expense.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('expenses list')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('expense/purchase')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{route('admin.expense.category.add')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{translate('expenses category')}}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>



                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{translate('report')}}">{{translate('report')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/daily')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.daily')}}">
                                <i class="tio-chart-bar-2 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('daily_report')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/monthlyearning')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.monthlyearning')}}">
                                <i class="tio-chart-bar-2 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('monthly_report')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/sales')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.sales')}}">
                                <i class="tio-chart-bar-2 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('sales_report')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/expence')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.expence')}}">
                                <i class="tio-chart-bar-2 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('expence_report')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/earning')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.earning')}}">
                                <i class="tio-chart-pie-1 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('earning_report')}}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/order')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.report.order')}}">
                                <i class="tio-chart-bar-2 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('order_report')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.business-settings.db-index')}}">
                                <i class="tio-chart-pie-1 nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('customer export')}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{translate('Business Section')}}">{{translate('system_settings')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu mb-4 {{Request::is('admin/business-settings/ecom-setup') || Request::is('admin/business-settings/otp-setup') || Request::is('admin/business-settings/cookies-setup') || Request::is('admin/business-settings/delivery-fee-setup')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.business-settings.ecom-setup')}}"
                            >
                                <i class="tio-settings nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('business_Setup')}}</span>
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

