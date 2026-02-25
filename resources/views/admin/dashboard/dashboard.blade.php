@extends("admin.layouts.app")
@section("content")
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Dashboard</h4>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->

            <!-- Info box -->
            <div class="card-group">

                <!-- Column -->
                @can('user-list')
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary" style="background-color: #6610f2 !important"><i class="ti-layout-grid2"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">{{ $totalUser ?? 0 }}</h3>
                                        <a href="{{ route("user-list") }}"><h5 class="text-muted m-b-0">Administrators</h5></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <!-- Column -->

                <!-- Column -->
                @can('banner-list')
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary" @style('background-color: #a700ff !important')><i class="ti-gallery"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">{{ $totalBanner ?? 0 }}</h3>
                                        <a href="{{ route("banner-list") }}"><h5 class="text-muted m-b-0">Banners</h5></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <!-- Column -->

                <!-- Column -->
                @can('blog-list')
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary" @style('background-color: #737373 !important')><i class="ti-gift"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">{{ $totalBlog ?? 0 }}</h3>
                                        <a href="{{ route("blog-list") }}"><h5 class="text-muted m-b-0">Blogs</h5></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <!-- Column -->

                <!-- Column -->
                @can('testimonial-list')
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary" @style('background-color: #a6a6a6 !important')><i class="fa fa-text-width"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">{{ $totalTestimonial ?? 0 }}</h3>
                                        <a href="{{ route("testimonial-list") }}"><h5 class="text-muted m-b-0">Testimonials</h5></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <!-- Column -->

                <!-- Column -->
                @can('pages-list')
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary" @style('background-color: #baf159 !important')><i class="ti-layers"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">{{ $totalPage ?? 0 }}</h3>
                                        <a href="{{ route("pages-list") }}"><h5 class="text-muted m-b-0">Pages</h5></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                <!-- Column -->

                <!-- Column -->
                @can('contact-list')
                    {{--<div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary" style="background-color: #ab8ce4 !important"><i class="fa fa-envelope-o"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">{{ $totalContact ?? 0 }}</h3>
                                        <a href="{{ route("contact-list") }}"><h5 class="text-muted m-b-0">Contact Enquiry</h5></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                @endcan
                <!-- Column -->

                <!-- Column -->
                @can('inquiry-list')
                    {{--<div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-primary" @style('background-color: #ff00c4 !important')><i class="fa fa-envelope-o"></i></div>
                                    <div class="m-l-10 align-self-center">
                                        <h3 class="m-b-0">{{ $totalInquiry ?? 0 }}</h3>
                                        <a href="{{ route("inquiry-list") }}"><h5 class="text-muted m-b-0">Partner Enquiry</h5></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                @endcan
                <!-- Column -->
            </div>
            <!-- End Info box -->
        </div>
        <!-- End Container fluid  -->
    </div>
    <!-- End Page wrapper  -->
@endsection