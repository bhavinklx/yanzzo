<!-- Header -->
<header class="header header-trans">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg header-nav">
            <div class="navbar-header">
                <a id="mobile_btn" href="javascript:void(0);">
			        <span class="bar-icon">
			        	<span></span>
			        	<span></span>
			        	<span></span>
			        </span>
                </a>
                <a href="{{ url('/') }}" class="navbar-brand logo">
                    <img src="{{ url('/public/img/logo.png') }}" class="img-fluid" alt="Logo" style=" max-width: 150px; ">
                </a>
            </div>
            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ url('/') }}" class="menu-logo">
                        <img src="{{ url('/public/img/logo.png') }}" class="img-fluid" alt="Logo">
                    </a>
                    <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
                </div>
                <ul class="main-nav">
                    @php
                        $headerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_header_status' => '1'])->orderBy('page_order')->get();
                    @endphp
                    @if(isset($headerPages) && count($headerPages) > 0)
                        @foreach($headerPages as $key => $pages)
                            @if($pages->page_link!='')
                                @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                            @elseif($pages->page_slug=="home")
                                @php $SITE_URL = url('/'); @endphp
                            @else
                                @php $SITE_URL = url($pages->page_slug . '/'); @endphp
                            @endif
                            @php $ACTIVE_LINK = (url()->current() == $SITE_URL) ? 'active' : ''; @endphp
                            @php
                                $subPages = App\Models\Pages::where(['page_parent' => $pages->page_id, 'page_status' => '1', 'page_header_status' => '1'])->orderBy('page_order')->get();
                            @endphp
                            @if(isset($subPages) && count($subPages) > 0)
                                <li class="has-submenu">
                                    <a href="javascript: void (0);">{{ $pages->page_title }} <i class="fas fa-chevron-down"></i></a>
                                    <ul class="submenu">
                                        @foreach($subPages as $key => $sub)
                                            @if($sub->page_link!='')
                                                @php $SITE_URL = ($sub->page_link != '#') ? $sub->page_link : 'javascript: void(0)'; @endphp
                                            @elseif($sub->page_slug=="home")
                                                @php $SITE_URL = url('/'); @endphp
                                            @else
                                                @php $SITE_URL = url($sub->page_slug . '/'); @endphp
                                            @endif
                                                <li><a href="{{ $SITE_URL }}">{{ $sub->page_title }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="{{ $ACTIVE_LINK }}"><a href="{{ $SITE_URL }}">{{ $pages->page_title }}</a>
                            @endif
                            </li>
                        @endforeach
                    @endif
                    @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                        <li class="login-link">
                            <a href="javascript: void (0)" {{--class="dropdown-toggle nav-link" data-bs-toggle="dropdown"--}}><span><i class="feather-users"></i></span> {{ Session::get('customer_name') }}</a>
                            {{--<div class="dropdown-menu dropdown-menu-end" style="right: 11%">
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item" href="{{ url('/my-account') }}">Dashboard</a></p>
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item" href="javascript: void (0)" onclick="return logout()">Logout</a></p>
                            </div>--}}
                        </li>
                        <li class="login-link">
                            <a href="{{ url('/my-account') }}">Dashboard</a>
                        </li>
                        <li class="login-link">
                            <a href="javascript: void (0)" onclick="return logout()">Logout</a>
                        </li>
                    @else
                        <li class="login-link">
                            <a href="javascript: void (0)" onclick="return signup_popup()">Register</a>
                        </li>
                        <li class="login-link">
                            <a href="javascript: void (0)" onclick="return signin_popup()">Login</a>
                        </li>
                        <li>
                            <a href="{{ url('/become-partner') }}">Become Partner</a>
                        </li>
                    @endif
                </ul>
            </div>
            <ul class="nav header-navbar-rht">
                <li class="nav-item">
                    <div class="nav-link btn btn-white log-register">
                        @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                            <a href="javascript: void (0)" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><span><i class="feather-users"></i></span> {{ Session::get('customer_name') }}</a>
                            <div class="dropdown-menu dropdown-menu-end" style="right: 11%">
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item" href="{{ url('/my-account') }}">Dashboard</a></p>
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item" href="javascript: void (0)" onclick="return logout()">Logout</a></p>
                            </div>
                        @else
                            <a href="javascript: void (0)" onclick="return signin_popup()"><span><i class="feather-users"></i></span>Login</a> / <a href="javascript: void (0)" onclick="return signup_popup()">Register</a>
                        @endif
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-secondary" href="{{ url('/become-partner') }}"><span><i class="feather-check-circle"></i></span>Become Partner</a>
                </li>
            </ul>
            <ul class="nav header-navbar-rht nav-mobile">
                <li class="nav-item">
                    <div class="nav-link btn btn-white log-register">
                        @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                            <a href="javascript: void (0)" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><span><i class="feather-users"></i></span> {{ ucwords(substr(strtok(Session::get('customer_name'), ' '), 0, 4)) }}</a>
                            <div class="dropdown-menu dropdown-menu-end" style="right: 11%">
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item" href="{{ url('/my-account') }}">Dashboard</a></p>
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item" href="javascript: void (0)" onclick="return logout()">Logout</a></p>
                            </div>
                        @else
                            <a href="javascript: void (0)" onclick="return signin_popup()"><span><i class="feather-users"></i></span>Login</a>
                        @endif
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</header>
<!-- /Header -->