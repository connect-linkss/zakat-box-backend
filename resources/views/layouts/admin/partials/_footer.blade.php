<footer class="footer">
    <div class="row justify-content-between align-items-center gy-2">
        <div class="col-lg-4">
            <p class="text-capitalize text-center text-lg-left mb-0">
                copyright connect global
            </p>
        </div>
        <div class="col-lg-8">
            <div class="d-flex justify-content-center justify-content-lg-end">
                <ul class="list-inline-menu justify-content-center">


                    <li>
                        <a href="{{route('admin.settings')}}">
                            <span>{{translate('profile')}}</span>
                            <i class="tio-user"></i>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('admin.dashboard')}}">
                            <span>{{translate('Home')}}</span>
                            <i class="tio-home-outlined"></i>
                        </a>
                    </li>
                    <li>
                        <label class="badge badge-soft-success">
                            {{ translate('Software Version') }} : 1
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
