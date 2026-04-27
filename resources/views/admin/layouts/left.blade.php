@php
    $fileName = request()->route()->getName();
@endphp

<!-- Sidebar wrapper starts -->
<nav id="sidebar" class="sidebar-wrapper">
    <!-- Sidebar profile starts -->
    <div class="sidebar-profile">
        <img src="{{ url('assets/images/user6.png') }}" class="img-shadow img-3x me-3 rounded-5" alt="Hospital Admin Templates">
        <div class="m-0">
            <h5 class="mb-1 profile-name text-nowrap text-truncate">{{ Auth::user()->name }}</h5>
            <p class="m-0 small profile-name text-nowrap text-truncate">{{ Auth::user()->getRoleNames()->first() ?? 'No Role' }}</p>
        </div>
    </div>
    <!-- Sidebar profile ends -->

    <!-- Sidebar menu starts -->
    <div class="sidebarMenuScroll">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route("dashboard") }}">
                    <i class="ri-home-6-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->hasPermissionTo('user-list', 'web') || auth()->user()->hasPermissionTo('role-list', 'web'))
                <li class="treeview {{ ($fileName == 'user-list' || $fileName == 'user-add' || $fileName == 'user-edit' || $fileName == 'role-list' || $fileName == 'role-add' || $fileName == 'role-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-nurse-line"></i>
                        <span class="menu-text">Administrators</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('user-list')
                            <li>
                                <a href="{{ route("user-list") }}" class="{{ ($fileName == 'user-list') ? 'active-sub' : '' }}">Administrator List</a>
                            </li>
                        @endcan
                        @can('user-add')
                            <li>
                                <a href="{{ route("user-add") }}" class="{{ ($fileName == 'user-add' || $fileName == 'user-edit') ? 'active-sub' : '' }}">Add Administrator</a>
                            </li>
                        @endcan
                        @can('role-list')
                            <li>
                                <a href="{{ route("role-list") }}" class="{{ ($fileName == 'role-list') ? 'active-sub' : '' }}">Role List</a>
                            </li>
                        @endcan
                        @can('role-add')
                            <li>
                                <a href="{{ route("role-add") }}" class="{{ ($fileName == 'role-add' || $fileName == 'role-edit') ? 'active-sub' : '' }}">Add Role</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('customer-list') || auth()->user()->can('customer-add'))
                <li class="treeview {{ ($fileName == 'customer-list') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-team-line"></i>
                        <span class="menu-text">Yanzzo User</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('customer-list')
                            <li>
                                <a href="{{ route("customer-list") }}" class="{{ ($fileName == 'customer-list') ? 'active-sub' : '' }}">User List</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('doctor-list') || auth()->user()->can('doctor-add'))
                <li class="treeview {{ ($fileName == 'doctor-list' || $fileName == 'doctor-add' || $fileName == 'doctor-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-stethoscope-line"></i>
                        <span class="menu-text">Doctors</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('doctor-list')
                            <li>
                                <a href="{{ route("doctor-list") }}" class="{{ ($fileName == 'doctor-list') ? 'active-sub' : '' }}">Doctor List</a>
                            </li>
                        @endcan
                        {{--@can('doctor-add')
                            <li>
                                <a href="{{ route("doctor-add") }}" class="{{ ($fileName == 'doctor-add' || $fileName == 'doctor-edit') ? 'active-sub' : '' }}">Add Doctor</a>
                            </li>
                        @endcan--}}
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('banner-list') || auth()->user()->can('banner-add'))
                <li class="treeview {{ ($fileName == 'banner-list' || $fileName == 'banner-add' || $fileName == 'banner-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-file-image-line"></i>
                        <span class="menu-text">Banners</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('banner-list')
                            <li>
                                <a href="{{ route("banner-list") }}" class="{{ ($fileName == 'banner-list') ? 'active-sub' : '' }}">Banner List</a>
                            </li>
                        @endcan
                        @can('banner-add')
                            <li>
                                <a href="{{ route("banner-add") }}" class="{{ ($fileName == 'banner-add' || $fileName == 'banner-edit') ? 'active-sub' : '' }}">Add Banner</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('pages-list') || auth()->user()->can('pages-add'))
                <li class="treeview {{ ($fileName == 'pages-list' || $fileName == 'pages-add' || $fileName == 'pages-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-pantone-line"></i>
                        <span class="menu-text">Pages</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('pages-list')
                            <li>
                                <a href="{{ route("pages-list") }}" class="{{ ($fileName == 'pages-list') ? 'active-sub' : '' }}">Page List</a>
                            </li>
                        @endcan
                        @can('pages-add')
                            <li>
                                <a href="{{ route("pages-add") }}" class="{{ ($fileName == 'pages-add' || $fileName == 'pages-edit') ? 'active-sub' : '' }}">Add Page</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('bcategory-list', 'web') || auth()->user()->can('blog-list', 'web'))
                <li class="treeview {{ ($fileName == 'bcategory-list' || $fileName == 'bcategory-add' || $fileName == 'bcategory-edit' || $fileName == 'blog-list' || $fileName == 'blog-add' || $fileName == 'blog-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-news-line"></i>
                        <span class="menu-text">Blogs</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('bcategory-list')
                            <li>
                                <a href="{{ route("bcategory-list") }}" class="{{ ($fileName == 'bcategory-list') ? 'active-sub' : '' }}">Category List</a>
                            </li>
                        @endcan
                        @can('bcategory-add')
                            <li>
                                <a href="{{ route("bcategory-add") }}" class="{{ ($fileName == 'bcategory-add' || $fileName == 'bcategory-edit') ? 'active-sub' : '' }}">Add Category</a>
                            </li>
                        @endcan
                        @can('blog-list')
                            <li>
                                <a href="{{ route("blog-list") }}" class="{{ ($fileName == 'blog-list') ? 'active-sub' : '' }}">Blog List</a>
                            </li>
                        @endcan
                        @can('blog-add')
                            <li>
                                <a href="{{ route("blog-add") }}" class="{{ ($fileName == 'blog-add' || $fileName == 'blog-edit') ? 'active-sub' : '' }}">Add Blog</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('testimonial-list') || auth()->user()->can('testimonial-add'))
                <li class="treeview {{ ($fileName == 'testimonial-list' || $fileName == 'testimonial-add' || $fileName == 'testimonial-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-text-snippet"></i>
                        <span class="menu-text">Testimonials</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('testimonial-list')
                            <li>
                                <a href="{{ route("testimonial-list") }}" class="{{ ($fileName == 'testimonial-list') ? 'active-sub' : '' }}">Testimonial List</a>
                            </li>
                        @endcan
                        @can('testimonial-add')
                            <li>
                                <a href="{{ route("testimonial-add") }}" class="{{ ($fileName == 'testimonial-add' || $fileName == 'testimonial-edit') ? 'active-sub' : '' }}">Add Testimonial</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('category-list', 'web') || auth()->user()->can('product-list', 'web'))
                <li class="treeview {{ ($fileName == 'category-list' || $fileName == 'category-add' || $fileName == 'category-edit' || $fileName == 'product-list' || $fileName == 'product-add' || $fileName == 'product-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-shopping-bag-3-line"></i>
                        <span class="menu-text">Products</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('category-list')
                            <li>
                                <a href="{{ route("category-list") }}" class="{{ ($fileName == 'category-list') ? 'active-sub' : '' }}">Category List</a>
                            </li>
                        @endcan
                        @can('category-add')
                            <li>
                                <a href="{{ route("category-add") }}" class="{{ ($fileName == 'category-add' || $fileName == 'category-edit') ? 'active-sub' : '' }}">Add Category</a>
                            </li>
                        @endcan
                        @can('product-list')
                            <li>
                                <a href="{{ route("product-list") }}" class="{{ ($fileName == 'product-list') ? 'active-sub' : '' }}">Product List</a>
                            </li>
                        @endcan
                        @can('product-add')
                            <li>
                                <a href="{{ route("product-add") }}" class="{{ ($fileName == 'product-add' || $fileName == 'product-edit') ? 'active-sub' : '' }}">Add Product</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('service-list') || auth()->user()->can('service-add'))
                <li class="treeview {{ ($fileName == 'service-list' || $fileName == 'service-add' || $fileName == 'service-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-customer-service-2-line"></i>
                        <span class="menu-text">Services</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('service-list')
                            <li>
                                <a href="{{ route("service-list") }}" class="{{ ($fileName == 'service-list') ? 'active-sub' : '' }}">Service List</a>
                            </li>
                        @endcan
                        @can('service-add')
                            <li>
                                <a href="{{ route("service-add") }}" class="{{ ($fileName == 'service-add' || $fileName == 'service-edit') ? 'active-sub' : '' }}">Add Service</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('sponsor-list') || auth()->user()->can('sponsor-add'))
                <li class="treeview {{ ($fileName == 'sponsor-list' || $fileName == 'sponsor-add' || $fileName == 'sponsor-edit') ? 'active current-page' : '' }}">
                    <a href="javascript: void (0)">
                        <i class="ri-color-filter-line"></i>
                        <span class="menu-text">Sponsors</span>
                    </a>
                    <ul class="treeview-menu">
                        @can('sponsor-list')
                            <li>
                                <a href="{{ route("sponsor-list") }}" class="{{ ($fileName == 'sponsor-list') ? 'active-sub' : '' }}">Sponsor List</a>
                            </li>
                        @endcan
                        @can('sponsor-add')
                            <li>
                                <a href="{{ route("sponsor-add") }}" class="{{ ($fileName == 'sponsor-add' || $fileName == 'sponsor-edit') ? 'active-sub' : '' }}">Add Sponsor</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if(auth()->user()->can('contact-list'))
                <li class="{{ ($fileName == 'contact-list') ? 'active current-page' : '' }}">
                    <a href="{{ route("contact-list") }}">
                        <i class="ri-mail-send-line"></i>
                        <span class="menu-text">Contact Inquiry</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->can('setting-edit'))
                <li class="{{ ($fileName == 'setting') ? 'active current-page' : '' }}">
                    <a href="{{ route("setting") }}">
                        <i class="ri-settings-5-line"></i>
                        <span class="menu-text">Settings</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <!-- Sidebar menu ends -->

    <!-- Sidebar contact starts -->
    <div class="sidebar-contact">
        <p class="fw-light mb-1 text-nowrap text-truncate">Emergency Contact</p>
        <h5 class="m-0 lh-1 text-nowrap text-truncate">09173905270</h5>
        <i class="ri-phone-line"></i>
    </div>
    <!-- Sidebar contact ends -->
</nav>
<!-- Sidebar wrapper ends -->
