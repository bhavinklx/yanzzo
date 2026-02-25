<!-- Topbar header - style you can find in pages.scss -->
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- Logo -->
        <div class="navbar-header" style="padding-left: 0px">
            <a class="navbar-brand" href="javascript: void (0)">
                <!-- Logo text -->
                <span>
                    <!-- Light Logo text -->
                    <img src="{{ url('/image/white-logo.jpeg') }}" width="100%" class="light-logo" alt="homepage" />
                </span>
            </a>
        </div>
        <!-- End Logo -->

        <div class="navbar-collapse">
            <!-- toggle and nav items -->

            <ul class="navbar-nav mr-auto">
                <!-- This is  -->
                <li class="nav-item"> <a class="nav-link nav-toggler d-block d-sm-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>

            </ul>
            <!-- User profile and search -->

            <ul class="navbar-nav my-lg-0">
                <!-- User Profile -->
                <li class="nav-item dropdown u-pro">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset("assets/img/nouser.jpg") }}" alt="user" class=""> <span class="hidden-md-down">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></span> </a>
                    <div class="dropdown-menu dropdown-menu-right animated flipInY">
                        <!-- text-->
                        <a href="{{ url("/") }}" class="dropdown-item" target="_blank"><i class="fa fa-home"></i> View Website</a>
                        <div class="dropdown-divider"></div>
                        {{--<a href="{{ route("changepassword", ['id' => Session::get("admin_id")]) }}" class="dropdown-item"><i class="fa fa-unlock-alt"></i> Change Password</a>
                        <div class="dropdown-divider"></div>--}}
                        <a href="{{ route("logout") }}" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                        <!-- text-->
                    </div>
                </li>
                <!-- End User Profile -->
                {{--@can('setting-list')
                    <li class="nav-item right-side-toggle"> <a href="{{ route("setting") }}" class="nav-link waves-effect waves-light"><i class="ti-settings"></i></a></li>
                @endcan--}}
            </ul>
        </div>
    </nav>
</header>
<!-- End Topbar header -->
