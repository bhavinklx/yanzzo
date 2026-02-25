@php
    $fileName = request()->route()->getName();
@endphp
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li> <a href="{{ route("dashboard") }}" class="waves-effect waves-dark" aria-expanded="false"><i class="ti-home"></i><span class="hide-menu">Dashboard</span></a></li>

                @if(auth()->user()->can('user-list') || auth()->user()->can('role-list'))
                    <li class="{{ ($fileName == 'user-list' || $fileName == 'user-add' || $fileName == 'user-edit' || $fileName == 'role-list' || $fileName == 'role-add' || $fileName == 'role-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'user-list' || $fileName == 'user-add' || $fileName == 'user-edit' || $fileName == 'role-list' || $fileName == 'role-add' || $fileName == 'role-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Administrators</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'user-list' || $fileName == 'user-add' || $fileName == 'user-edit' || $fileName == 'role-list' || $fileName == 'role-add' || $fileName == 'role-edit') ? 'in' : '' }}">
                            @can('user-list')
                                <li class="{{ ($fileName == 'user-list' || $fileName == 'user-add' || $fileName == 'user-edit') ? 'active' : '' }}"><a href="{{ route("user-list") }}" class="{{ ($fileName == 'user-list' || $fileName == 'user-add' || $fileName == 'user-edit') ? 'active' : '' }}">Administrator List</a></li>
                            @endcan
                            @can('role-list')
                                <li class="{{ ($fileName == 'role-list' || $fileName == 'role-add' || $fileName == 'role-edit') ? 'active' : '' }}"><a href="{{ route("role-list") }}" class="{{ ($fileName == 'role-list' || $fileName == 'role-add' || $fileName == 'role-edit') ? 'active' : '' }}">Role List</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{--@if(auth()->user()->can('customer-list'))
                    <li class="{{ ($fileName == 'customer-list') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'customer-list') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-user-o"></i><span class="hide-menu">Yaarioke User</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'customer-list') ? 'in' : '' }}">
                            @can('customer-list')
                                <li class="{{ ($fileName == 'customer-list') ? 'active' : '' }}"><a href="{{ route("customer-list") }}" class="{{ ($fileName == 'customer-list') ? 'active' : '' }}">User List</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif--}}

                {{--@if(auth()->user()->can('order-list'))
                    <li class="{{ ($fileName == 'order-list') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'order-list') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="ti-shopping-cart-full"></i><span class="hide-menu">Booking Order</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'order-list') ? 'in' : '' }}">
                            --}}{{--@can('order-list')--}}{{--
                            <li class="{{ ($fileName == 'order-list' && request('status') === 'pending') ? 'active' : '' }}"><a href="{{ route("order-list", ['status' => 'pending']) }}" class="{{ ($fileName == 'order-list' && request('status') === 'pending') ? 'active' : '' }}">All Booking</a></li>
                            --}}{{--@endcan--}}{{--
                            --}}{{--@can('order-list')--}}{{--
                            <li class="{{ ($fileName == 'order-list' && request('status') === 'completed') ? 'active' : '' }}"><a href="{{ route("order-list", ['status' => 'completed']) }}" class="{{ ($fileName == 'order-list' && request('status') === 'completed') ? 'active' : '' }}">Completed Booking</a></li>
                            --}}{{--@endcan--}}{{--
                            --}}{{--@can('order-list')--}}{{--
                            <li class="{{ ($fileName == 'order-list' && request('status') === 'cancel') ? 'active' : '' }}"><a href="{{ route("order-list", ['status' => 'cancel']) }}" class="{{ ($fileName == 'order-list' && request('status') === 'cancel') ? 'active' : '' }}">Cancel Booking</a></li>
                            --}}{{--@endcan--}}{{--
                            --}}{{--@can('order-list')--}}{{--
                            <li class="{{ ($fileName == 'customer-add') ? 'active' : '' }}"><a href="{{ route("customer-add") }}" class="{{ ($fileName == 'customer-add') ? 'active' : '' }}">Add Booking</a></li>
                            --}}{{--@endcan--}}{{--
                        </ul>
                    </li>
                @endif--}}

                {{--@if(auth()->user()->can('membership-order-list'))
                    <li class="{{ ($fileName == 'membership-order-list') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'membership-order-list') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="ti-harddrives"></i><span class="hide-menu">Membership Order</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'membership-order-list') ? 'in' : '' }}">
                            --}}{{--@can('membership-order-list')--}}{{--
                            <li class="{{ ($fileName == 'membership-order-list' && request('status') === 'pending') ? 'active' : '' }}"><a href="{{ route("membership-order-list", ['status' => 'pending']) }}" class="{{ ($fileName == 'membership-order-list' && request('status') === 'pending') ? 'active' : '' }}">Pending Membership</a></li>
                            --}}{{--@endcan--}}{{--
                            --}}{{--@can('membership-order-list')--}}{{--
                            <li class="{{ ($fileName == 'membership-order-list' && request('status') === 'completed') ? 'active' : '' }}"><a href="{{ route("membership-order-list", ['status' => 'completed']) }}" class="{{ ($fileName == 'membership-order-list' && request('status') === 'completed') ? 'active' : '' }}">Completed Membership</a></li>
                            --}}{{--@endcan--}}{{--
                            --}}{{--@can('membership-order-list')--}}{{--
                            <li class="{{ ($fileName == 'membership-order-list' && request('status') === 'cancel') ? 'active' : '' }}"><a href="{{ route("membership-order-list", ['status' => 'cancel']) }}" class="{{ ($fileName == 'membership-order-list' && request('status') === 'cancel') ? 'active' : '' }}">Cancel Membership</a></li>
                            --}}{{--@endcan--}}{{--
                        </ul>
                    </li>
                @endif--}}

                {{--@if(auth()->user()->can('discount-list') || auth()->user()->can('discount-add'))
                    <li class="{{ ($fileName == 'discount-list' || $fileName == 'discount-add' || $fileName == 'discount-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'discount-list' || $fileName == 'discount-add' || $fileName == 'discount-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-ticket"></i><span class="hide-menu">Discount</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'discount-list' || $fileName == 'discount-add' || $fileName == 'discount-edit') ? 'in' : '' }}">
                            @can('discount-list')
                                <li class="{{ ($fileName == 'discount-list') ? 'active' : '' }}"><a href="{{ route("discount-list") }}" class="{{ ($fileName == 'discount-list') ? 'active' : '' }}">Discount List</a></li>
                            @endcan
                            @can('discount-add')
                                <li class="{{ ($fileName == 'discount-add' || $fileName == 'discount-edit') ? 'active' : '' }}"><a href="{{ route("discount-add") }}" class="{{ ($fileName == 'discount-add' || $fileName == 'discount-edit') ? 'active' : '' }}">Add Discount</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif--}}

                @if(auth()->user()->can('banner-list') || auth()->user()->can('banner-add'))
                    <li class="{{ ($fileName == 'banner-list' || $fileName == 'banner-add' || $fileName == 'banner-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'banner-list' || $fileName == 'banner-add' || $fileName == 'banner-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="ti-gallery"></i><span class="hide-menu">Banners</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'banner-list' || $fileName == 'banner-add' || $fileName == 'banner-edit') ? 'in' : '' }}">
                            @can('banner-list')
                                <li class="{{ ($fileName == 'banner-list') ? 'active' : '' }}"><a href="{{ route("banner-list") }}" class="{{ ($fileName == 'banner-list') ? 'active' : '' }}">Banner List</a></li>
                            @endcan
                            @can('banner-add')
                                <li class="{{ ($fileName == 'banner-add' || $fileName == 'banner-edit') ? 'active' : '' }}"><a href="{{ route("banner-add") }}" class="{{ ($fileName == 'banner-add' || $fileName == 'banner-edit') ? 'active' : '' }}">Add Banner</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('bcategory-list') || auth()->user()->can('blog-list'))
                    <li class="{{ ($fileName == 'bcategory-list' || $fileName == 'bcategory-add' || $fileName == 'bcategory-edit' || $fileName == 'blog-list' || $fileName == 'blog-add' || $fileName == 'blog-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'bcategory-list' || $fileName == 'bcategory-add' || $fileName == 'bcategory-edit' || $fileName == 'blog-list' || $fileName == 'blog-add' || $fileName == 'blog-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="ti-gift"></i><span class="hide-menu">Blogs</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'bcategory-list' || $fileName == 'bcategory-add' || $fileName == 'bcategory-edit' || $fileName == 'blog-list' || $fileName == 'blog-add' || $fileName == 'blog-edit') ? 'in' : '' }}">
                            @can('bcategory-list')
                                <li class="{{ ($fileName == 'bcategory-list' || $fileName == 'bcategory-add' || $fileName == 'bcategory-edit') ? 'active' : '' }}"><a href="{{ route("bcategory-list") }}" class="{{ ($fileName == 'bcategory-list' || $fileName == 'bcategory-add' || $fileName == 'bcategory-edit') ? 'active' : '' }}">Category List</a></li>
                            @endcan
                            @can('blog-list')
                                <li class="{{ ($fileName == 'blog-list' || $fileName == 'blog-add' || $fileName == 'blog-edit') ? 'active' : '' }}"><a href="{{ route("blog-list") }}" class="{{ ($fileName == 'blog-list' || $fileName == 'blog-add' || $fileName == 'blog-edit') ? 'active' : '' }}">Blog List</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('city-list') || auth()->user()->can('faq-list'))
                    <li class="{{ ($fileName == 'city-list' || $fileName == 'city-add' || $fileName == 'city-edit' || $fileName == 'faq-list' || $fileName == 'faq-add' || $fileName == 'faq-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'city-list' || $fileName == 'city-add' || $fileName == 'city-edit' || $fileName == 'faq-list' || $fileName == 'faq-add' || $fileName == 'faq-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="ti-world"></i><span class="hide-menu">Cities</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'city-list' || $fileName == 'city-add' || $fileName == 'city-edit' || $fileName == 'faq-list' || $fileName == 'faq-add' || $fileName == 'faq-edit') ? 'in' : '' }}">
                            @can('city-list')
                                <li class="{{ ($fileName == 'city-list' || $fileName == 'city-add' || $fileName == 'city-edit') ? 'active' : '' }}"><a href="{{ route("city-list") }}" class="{{ ($fileName == 'city-list' || $fileName == 'city-add' || $fileName == 'city-edit') ? 'active' : '' }}">City List</a></li>
                            @endcan
                            @can('faq-list')
                                <li class="{{ ($fileName == 'faq-list' || $fileName == 'faq-add' || $fileName == 'faq-edit') ? 'active' : '' }}"><a href="{{ route("faq-list") }}" class="{{ ($fileName == 'faq-list' || $fileName == 'faq-add' || $fileName == 'faq-edit') ? 'active' : '' }}">FAQ List</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('testimonial-list') || auth()->user()->can('testimonial-add'))
                    <li class="{{ ($fileName == 'testimonial-list' || $fileName == 'testimonial-add' || $fileName == 'testimonial-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'testimonial-list' || $fileName == 'testimonial-add' || $fileName == 'testimonial-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-text-width"></i><span class="hide-menu">Testimonials</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'testimonial-list' || $fileName == 'testimonial-add' || $fileName == 'testimonial-edit') ? 'in' : '' }}">
                            @can('testimonial-list')
                                <li class="{{ ($fileName == 'testimonial-list') ? 'active' : '' }}"><a href="{{ route("testimonial-list") }}" class="{{ ($fileName == 'testimonial-list') ? 'active' : '' }}">Testimonial List</a></li>
                            @endcan
                            @can('testimonial-add')
                                <li class="{{ ($fileName == 'testimonial-add' || $fileName == 'testimonial-edit') ? 'active' : '' }}"><a href="{{ route("testimonial-add") }}" class="{{ ($fileName == 'testimonial-add' || $fileName == 'testimonial-edit') ? 'active' : '' }}">Add Testimonial</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('pages-list') || auth()->user()->can('pages-add'))
                    <li class="{{ ($fileName == 'pages-list' || $fileName == 'pages-add' || $fileName == 'pages-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'pages-list' || $fileName == 'pages-add' || $fileName == 'pages-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="ti-layers"></i><span class="hide-menu">Pages</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'pages-list' || $fileName == 'pages-add' || $fileName == 'pages-edit') ? 'in' : '' }}">
                            @can('pages-list')
                                <li class="{{ ($fileName == 'pages-list') ? 'active' : '' }}"><a href="{{ route("pages-list") }}" class="{{ ($fileName == 'pages-list') ? 'active' : '' }}">Page List</a></li>
                            @endcan
                            @can('pages-add')
                                <li class="{{ ($fileName == 'pages-add' || $fileName == 'pages-edit') ? 'active' : '' }}"><a href="{{ route("pages-add") }}" class="{{ ($fileName == 'pages-add' || $fileName == 'pages-edit') ? 'active' : '' }}">Add Page</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{--@if(auth()->user()->can('lounge-list') || auth()->user()->can('lounge-add'))
                    <li class="{{ ($fileName == 'lounge-list' || $fileName == 'lounge-add' || $fileName == 'lounge-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'lounge-list' || $fileName == 'lounge-add' || $fileName == 'lounge-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-university"></i><span class="hide-menu">Lounge</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'lounge-list' || $fileName == 'lounge-add' || $fileName == 'lounge-edit') ? 'in' : '' }}">
                            @can('lounge-list')
                                <li class="{{ ($fileName == 'lounge-list') ? 'active' : '' }}"><a href="{{ route("lounge-list") }}" class="{{ ($fileName == 'lounge-list') ? 'active' : '' }}">Lounge List</a></li>
                            @endcan
                            @can('lounge-add')
                                <li class="{{ ($fileName == 'lounge-add' || $fileName == 'lounge-edit') ? 'active' : '' }}"><a href="{{ route("lounge-add") }}" class="{{ ($fileName == 'lounge-add' || $fileName == 'lounge-edit') ? 'active' : '' }}">Add Lounge</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif--}}

                {{--@if(auth()->user()->can('franchise-list') || auth()->user()->can('franchise-add'))
                    <li class="{{ ($fileName == 'franchise-list' || $fileName == 'franchise-add' || $fileName == 'franchise-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'franchise-list' || $fileName == 'franchise-add' || $fileName == 'franchise-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-cubes"></i><span class="hide-menu">Franchise</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'franchise-list' || $fileName == 'franchise-add' || $fileName == 'franchise-edit') ? 'in' : '' }}">
                            @can('franchise-list')
                                <li class="{{ ($fileName == 'franchise-list') ? 'active' : '' }}"><a href="{{ route("franchise-list") }}" class="{{ ($fileName == 'franchise-list') ? 'active' : '' }}">Franchise List</a></li>
                            @endcan
                            @can('franchise-add')
                                <li class="{{ ($fileName == 'franchise-add' || $fileName == 'franchise-edit') ? 'active' : '' }}"><a href="{{ route("franchise-add") }}" class="{{ ($fileName == 'franchise-add' || $fileName == 'franchise-edit') ? 'active' : '' }}">Add Franchise</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif--}}

                @if(auth()->user()->can('membership-list') || auth()->user()->can('membership-add'))
                    <li class="{{ ($fileName == 'membership-list' || $fileName == 'membership-add' || $fileName == 'membership-edit') ? 'active' : '' }}"> <a class="has-arrow waves-effect waves-dark {{ ($fileName == 'membership-list' || $fileName == 'membership-add' || $fileName == 'membership-edit') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-shopping-bag"></i><span class="hide-menu">Membership</span></a>
                        <ul aria-expanded="false" class="collapse {{ ($fileName == 'membership-list' || $fileName == 'membership-add' || $fileName == 'membership-edit') ? 'in' : '' }}">
                            @can('membership-list')
                                <li class="{{ ($fileName == 'membership-list') ? 'active' : '' }}"><a href="{{ route("membership-list") }}" class="{{ ($fileName == 'membership-list') ? 'active' : '' }}">Membership List</a></li>
                            @endcan
                            @can('membership-add')
                                <li class="{{ ($fileName == 'membership-add' || $fileName == 'membership-edit') ? 'active' : '' }}"><a href="{{ route("membership-add") }}" class="{{ ($fileName == 'membership-add' || $fileName == 'membership-edit') ? 'active' : '' }}">Add Membership</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('contact-list') || auth()->user()->can('inquiry-list'))
                    <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-envelope-o"></i><span class="hide-menu">Inquiries</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @can('contact-list')
                                <li><a href="{{ route("contact-list") }}">Contact Inquiry</a></li>
                            @endcan
                            @can('inquiry-list')
                                <li><a href="{{ route("inquiry-list") }}">Partner Inquiry</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
