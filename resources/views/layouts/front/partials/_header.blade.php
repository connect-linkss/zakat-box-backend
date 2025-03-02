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
        </div>
    </header>
</div>
