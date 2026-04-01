@extends("admin.layouts.app")
@section("content")
    <!-- App hero header starts -->
    <div class="app-hero-header d-flex align-items-center">
        <!-- Breadcrumb starts -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route("dashboard") }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Dashboard
            </li>
        </ol>
        <!-- Breadcrumb ends -->
    </div>
    <!-- App Hero header ends -->

    <!-- App body starts -->
    <div class="app-body">
        <!-- Row starts -->
        @php
            $hour = \Carbon\Carbon::now()->format('H');
            if ($hour < 12) {
                $greeting = "Good Morning";
            } elseif ($hour < 17) {
                $greeting = "Good Afternoon";
            } elseif ($hour < 21) {
                $greeting = "Good Evening";
            } else {
                $greeting = "Good Night";
            }
        @endphp
        {{--<div class="row gx-3">
            <div class="col-xxl-12 col-sm-12">
                <div class="card mb-3 bg-2">
                    <div class="card-body">
                        <div class="py-4 px-3 text-white">
                            <h6>{{ $greeting }},</h6>
                            <h2>{{ Auth::user()->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
        <!-- Row ends -->

        <!-- Row starts -->
        <div class="row gx-3">
            <!-- Banners -->
            <div class="col-xxl-2 col-sm-6">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-box xl bg-primary-subtle rounded-5 mb-2 no-shadow">
                                <i class="ri-file-image-line fs-1 text-primary"></i>
                            </div>
                            <h1 class="text-primary">{{ $totalBanner }}</h1>
                            <h6>Banners</h6>
                            <a href="{{ route("banner-list") }}" class="text-primary text-decoration-none">
                                View All <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pages -->
            <div class="col-xxl-2 col-sm-6">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-box xl bg-success-subtle rounded-5 mb-2 no-shadow">
                                <i class="ri-pantone-line fs-1 text-success"></i>
                            </div>
                            <h1 class="text-success">{{ $totalPages }}</h1>
                            <h6>Pages</h6>
                            <a href="{{ route("pages-list") }}" class="text-success text-decoration-none">
                                View All <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Blogs -->
            <div class="col-xxl-2 col-sm-6">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-box xl bg-warning-subtle rounded-5 mb-2 no-shadow">
                                <i class="ri-news-line fs-1 text-warning"></i>
                            </div>
                            <h1 class="text-warning">{{ $totalBlog }}</h1>
                            <h6>Blogs</h6>
                            <a href="{{ route('blog-list') }}" class="text-warning text-decoration-none">
                                View All <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Testimonials -->
            <div class="col-xxl-2 col-sm-6">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-box xl bg-info-subtle rounded-5 mb-2 no-shadow">
                                <i class="ri-text-snippet fs-1 text-info"></i>
                            </div>
                            <h1 class="text-info">{{ $totalTestimonial }}</h1>
                            <h6>Testimonials</h6>
                            <a href="{{ route('testimonial-list') }}" class="text-info text-decoration-none">
                                View All <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sponsors -->
            <div class="col-xxl-2 col-sm-6">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-box xl bg-danger-subtle rounded-5 mb-2 no-shadow">
                                <i class="ri-color-filter-line fs-1 text-danger"></i>
                            </div>
                            <h1 class="text-danger">{{ $totalSponsor }}</h1>
                            <h6>Sponsors</h6>
                            <a href="{{ route('sponsor-list') }}" class="text-danger text-decoration-none">
                                View All <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contact Inquiry -->
            <div class="col-xxl-2 col-sm-6">
                <div class="card mb-3">
                    <div class="card-body mh-230">
                        <div class="d-flex flex-column align-items-center">
                            <div class="icon-box xl bg-dark-subtle rounded-5 mb-2 no-shadow">
                                <i class="ri-mail-send-line fs-1 text-dark"></i>
                            </div>
                            <h1 class="text-dark">{{ $totalContact }}</h1>
                            <h6>Contact Inquiry</h6>
                            <a href="{{ route('contact-list') }}" class="text-dark text-decoration-none">
                                View All <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row ends -->

        <!-- Row starts -->
        <div class="row gx-3">
            <div class="col-xxl-6 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title">User Registrations</h5>
                        <div class="dropdown">
                            <span
                                class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ date('Y') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="customerGraph" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-sm-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title">Product Registrations</h5>
                        <div class="dropdown">
                            <span
                                class="badge bg-success-subtle text-success border border-success-subtle">{{ date('Y') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="productGraph" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row ends -->
    </div>
    <!-- App body ends -->
@endsection
@section('page-js')
    <script type="text/javascript">
        var options1 = {
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    columnWidth: '40%',
                    borderRadius: 2,
                }
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
                width: [0, 3], // 0 width for bar series, 3 for line
            },
            series: [{
                name: "Registered Users",
                type: "column",
                data: @json($customerDaily),
            }, {
                name: "7-Day Moving Avg",
                type: "line",
                data: @json($movingAverage),
            }],
            grid: {
                borderColor: "#f1f1f1",
                strokeDashArray: 0,
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0,
                },
            },
            xaxis: {
                categories: @json($customerLabels),
                labels: {
                    rotate: -45,
                    rotateAlways: true,
                    style: {
                        fontSize: '12px'
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                title: {
                    text: 'Registered Users',
                },
                labels: {
                    show: true,
                },
            },
            colors: [
                "#3b82f6", // Blue
                "#f59e0b", // Yellow
            ],
            markers: {
                size: 0,
                hover: {
                    size: 5,
                },
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                offsetY: 0,
                markers: {
                    width: 12,
                    height: 12,
                    strokeWidth: 0,
                    strokeColor: '#fff',
                    fillColors: undefined,
                    radius: 12,
                    customHTML: undefined,
                    onClick: undefined,
                    offsetX: 0,
                    offsetY: 0
                },
            }
        };
        var chart1 = new ApexCharts(document.querySelector("#customerGraph"), options1);
        chart1.render();

        // Product Registration Graph
        var options2 = {
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    columnWidth: '40%',
                    borderRadius: 2,
                }
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
                width: [0, 3], // 0 width for bar series, 3 for line
            },
            series: [{
                name: "Registered Products",
                type: "column",
                data: @json($productDaily),
            }, {
                name: "7-Day Moving Avg",
                type: "line",
                data: @json($productMovingAverage),
            }],
            grid: {
                borderColor: "#f1f1f1",
                strokeDashArray: 0,
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0,
                },
            },
            xaxis: {
                categories: @json($customerLabels),
                labels: {
                    rotate: -45,
                    rotateAlways: true,
                    style: {
                        fontSize: '12px'
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                title: {
                    text: 'Registered Products',
                },
                labels: {
                    show: true,
                },
            },
            colors: [
                "#10b981", // Green
                "#ef4444", // Red
            ],
            markers: {
                size: 0,
                hover: {
                    size: 5,
                },
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                offsetY: 0,
                markers: {
                    radius: 12,
                },
            }
        };
        var chart2 = new ApexCharts(document.querySelector("#productGraph"), options2);
        chart2.render();
    </script>
@endsection