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
                    <img src="{{ url('/image/logo.png') }}" class="img-fluid" alt="Logo">
                </a>
            </div>
            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ url('/') }}" class="menu-logo">
                        <img src="{{ url('/image/logo.png') }}" class="img-fluid" alt="Logo">
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
                </ul>
            </div>
            <ul class="nav header-navbar-rht">
                <li class="nav-item">
                    <div class="nav-link btn btn-white log-register">
                        <a href="login.html"><span><i class="feather-users"></i></span>Login</a> / <a href="register.html">Register</a>
                    </div>
                </li>
                {{--<li class="nav-item">
                    <div class="search-wrapper">
                        <!-- Toggle Button -->
                        <button class="search-toggle-btn" id="searchToggle">
                            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </button>

                        <!-- Dropdown Form -->
                        <div class="search-dropdown" id="searchDropdown">
                            <div class="search-dropdown-inner">
                                <form action="coaches-grid.html" id="searchForm">
                                    <div class="search-input-group">
                                        <svg class="search-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <circle cx="11" cy="11" r="8"/>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                        <input
                                                type="text"
                                                name="q"
                                                class="search-input"
                                                placeholder="Search coaches, sports, location..."
                                                autocomplete="off"
                                        >
                                    </div>
                                    <button type="submit" class="search-submit">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>--}}
            </ul>
        </nav>
    </div>
</header>
<!-- /Header -->