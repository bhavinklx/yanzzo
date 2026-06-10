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
                    <img src="{{ asset('image/logo.png') }}') }}" class="img-fluid" alt="Logo">
                </a>
            </div>
            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ url('/') }}" class="menu-logo">
                        <img src="{{ asset('image/logo.png') }}') }}" class="img-fluid" alt="Logo">
                    </a>
                    <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
                </div>
                <ul class="main-nav">
                    @php
                        $headerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_header_status' => '1'])->orderBy('page_order')->get();
                    @endphp
                    @if(isset($headerPages) && count($headerPages) > 0)
                        @foreach($headerPages as $key => $pages)
                            @if($pages->page_link != '')
                                @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                            @elseif($pages->page_slug == "home")
                                @php $SITE_URL = url('/'); @endphp
                            @else
                                @php $SITE_URL = url($pages->page_slug . '/'); @endphp
                            @endif
                            @php $ACTIVE_LINK = (url()->current() == $SITE_URL) ? 'active' : ''; @endphp
                            @php
                                $subPages = App\Models\Pages::where(['page_parent' => $pages->page_id, 'page_status' => '1', 'page_header_status' => '1'])->orderBy('page_order')->get();
                                $categories = [];
                                if ($pages->page_id == 3) {
                                    $categories = App\Models\Category::where(['category_parent' => 0, 'category_status' => '1'])->orderBy('category_title', 'ASC')->get();
                                }
                            @endphp
                            @if((isset($subPages) && count($subPages) > 0) || (isset($categories) && count($categories) > 0))
                                <li class="has-submenu">
                                    <a href="{{ ($pages->page_id == 3) ? $SITE_URL : 'javascript: void (0);' }}">{{ $pages->page_title }}
                                        <i class="fas fa-chevron-down"></i></a>
                                    <ul class="submenu">
                                        @if(isset($subPages) && count($subPages) > 0)
                                            @foreach($subPages as $key => $sub)
                                                @if($sub->page_link != '')
                                                    @php $SITE_URL = ($sub->page_link != '#') ? $sub->page_link : 'javascript: void(0)'; @endphp
                                                @elseif($sub->page_slug == "home")
                                                    @php $SITE_URL = url('/'); @endphp
                                                @else
                                                    @php $SITE_URL = url($sub->page_slug . '/'); @endphp
                                                @endif
                                                <li><a href="{{ $SITE_URL }}">{{ $sub->page_title }}</a></li>
                                            @endforeach
                                        @endif
                                        @if(isset($categories) && count($categories) > 0)
                                            @foreach($categories as $cat)
                                                <li><a
                                                        href="{{ url($pages->page_slug . '?category=' . $cat->category_slug) }}">{{ $cat->category_title }}</a>
                                                </li>
                                            @endforeach
                                        @endif
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
                            <a href="javascript: void (0)" {{--class="dropdown-toggle nav-link" data-bs-toggle="dropdown"
                                --}}><span><i class="feather-users"></i></span> {{ Session::get('customer_name') }}</a>
                            {{--<div class="dropdown-menu dropdown-menu-end" style="right: 11%">
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item"
                                        href="{{ url('/my-account') }}">Dashboard</a></p>
                                <p style="margin-bottom: 0px !important;"><a class="dropdown-item"
                                        href="javascript: void (0)" onclick="return logout()">Logout</a></p>
                            </div>--}}
                        </li>
                        <li class="login-link">
                            <a href="{{ url('/my-account') }}">Dashboard</a>
                        </li>
                        <li class="login-link">
                            <a href="{{ url('/seller-inquiry') }}">Sell Your Machine</a>
                        </li>
                        <li class="login-link">
                            <a href="{{ url('/my-listing') }}">My Machines</a>
                        </li>
                        <li class="login-link">
                            <a href="{{ url('/chat') }}">Messages
                                @php
                                    $unreadHeader = \App\Models\Chat::where('receiver_id', Session::get('customer_id'))->where('is_read', false)->count();
                                @endphp
                                @if($unreadHeader > 0)
                                    <span class="badge badge-danger rounded-circle ms-1"
                                          style="background: #ff4d4d; font-size: 8px; width: 14px; height: 14px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">{{ $unreadHeader }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="login-link">
                            <a href="javascript: void (0)" onclick="return logout()">Logout</a>
                        </li>
                    @else
                        <li class="login-link">
                            <a href="javascript: void (0)" onclick="return signin_popup()">Sell</a>
                        </li>
                        <li class="login-link">
                            <a href="javascript: void (0)" onclick="return signin_popup()">Login</a>
                        </li>
                    @endif
                </ul>
            </div>
            <ul class="nav header-navbar-rht">
                @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                    <li class="nav-item">
                        <div class="user-header-dropdown">
                            <a href="javascript:void(0)" class="user-header-toggle">
                                <i class="feather-user"></i> <span class="user-name-text">{{ Session::get('customer_name') }}</span> <i class="fas fa-chevron-down" style="font-size:10px;"></i>
                            </a>
                            <div class="user-header-menu">
                                <a class="user-header-item" href="{{ url('/my-account') }}"><i class="feather-grid"></i>Dashboard</a>
                                <a class="user-header-item" href="{{ url('/seller-inquiry') }}"><i class="feather-plus-square"></i> Sell Your Machine</a>
                                <a class="user-header-item" href="{{ url('/my-listing') }}"><i class="feather-list"></i> My Machines</a>
                                <a class="user-header-item" href="{{ url('/chat') }}">
                                    <i class="feather-message-square"></i> Messages
                                    @php
                                        $unreadHeader = \App\Models\Chat::where('receiver_id', Session::get('customer_id'))->where('is_read', false)->count();
                                    @endphp
                                    @if($unreadHeader > 0)
                                        <span class="badge badge-danger rounded-circle ms-1"
                                            style="background: #ff4d4d; font-size: 8px; width: 14px; height: 14px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">{{ $unreadHeader }}</span>
                                    @endif
                                </a>
                                <a class="user-header-item" href="javascript:void(0)" onclick="return logout()"><i class="feather-log-out"></i> Logout</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="{{ url('/seller-inquiry') }}" class="btn-sell-new">
                            <i class="feather-plus-circle me-1"></i> SELL
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="javascript: void (0)" onclick="return signin_popup()" class="btn-sell-new">
                            <i class="feather-user me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="javascript: void (0)" onclick="return signin_popup()" class="btn-sell-new">
                            <i class="feather-plus-circle me-1"></i> SELL
                        </a>
                    </li>
                @endif
            </ul>

        </nav>
    </div>
</header>
<!-- /Header -->
