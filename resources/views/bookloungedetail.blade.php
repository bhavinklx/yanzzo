@extends("layouts.app")
@section('title', $bcategoryName->bcategory_meta_title ?? $pagesDetail->page_meta_title)
@section('keywords', $bcategoryName->bcategory_meta_keyword ?? $pagesDetail->page_meta_keyword)
@section('description', $bcategoryName->bcategory_meta_desc ?? $pagesDetail->page_meta_desc)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
        <div class="container">
            <h1 class="text-white">{{ $pagesDetail->page_title ?? '' }}</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $pagesDetail->page_title ?? '' }}</li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!--Galler Slider Section-->
    <div class="bannergallery-section">
        <div class="main-gallery-slider owl-carousel owl-theme">
            @if(is_array($limageDetail) && count($limageDetail) > 0) @foreach($limageDetail as $limage)
                <div class="gallery-widget-item">
                    <a href="{{ asset('/uploads/lounge/'.$limage['limage_image']) }}" data-fancybox="gallery1">
                        <img class="img-fluid" alt="Image" src="{{ asset('/uploads/lounge/'.$limage['limage_image']) }}">
                    </a>
                </div>
            @endforeach @endif
        </div>
        <div class="showphotos corner-radius-10">
            <a href="{{ url('/public/img/gallery/gallery1/gallery-03.png') }}" data-fancybox="gallery1"><i class="fa-regular fa-images"></i>More Photos</a>
        </div>
    </div>

    <div class="venue-info white-bg d-block">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <h1 class="d-flex align-items-center justify-content-start">{{ $loungeDetail->lounge_name }}</h1>
                    <ul class="d-sm-flex justify-content-start align-items-center">
                        <li><i class="feather-map-pin"></i>{{ $loungeDetail->lounge_address }}</li>
                        {{--<li><i class="feather-phone-call"></i>{{ $loungeDetail->lounge_mobile }}</li>
                        <li><i class="feather-mail"></i><a href="mailto:{{ $loungeDetail->lounge_email }}"> {{ $loungeDetail->lounge_email }}</a></li>--}}
                    </ul>
                </div>
                {{--<div class="col-12 col-sm-12 col-md-12 col-lg-6 text-right">
                    <ul class="social-options float-lg-end d-sm-flex justify-content-start align-items-center">
                        <li><a href="javascript:void(0);" class="favour-adds"><i class="feather-star"></i>Add to favourite</a></li>
                    </ul>
                </div>--}}
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <!-- Row -->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                    <div class="venue-options white-bg mb-4">
                        <ul class="clearfix">
                            <li class="active"><a href="#overview">Overview</a></li>
                            @if(!empty($loungeDetail->lounge_includes))
                                <li><a href="#includes">Includes</a></li>
                            @endif
                            @if(!empty($loungeDetail->lounge_rules))
                                <li><a href="#rules">Rules</a></li>
                            @endif
                            @if(!empty($loungeDetail->lounge_amenities))
                                <li><a href="#amenities">Amenities</a></li>
                            @endif
                            @if(is_array($limageDetail) && count($limageDetail) > 0)
                                <li><a href="#gallery">Gallery</a></li>
                            @endif
                            <li><a href="#location">Locations</a></li>
                        </ul>
                    </div>

                    <!-- Accordian Contents -->
                    <div class="accordion" id="accordionPanel">
                        <div class="accordion-item mb-4" id="overview">
                            <h4 class="accordion-header" id="panelsStayOpen-overview">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Overview
                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-overview">
                                <div class="accordion-body">
                                    <div class="text show-more-height">
                                       {!! $loungeDetail->lounge_desc !!}
                                    </div>
                                    <div class="show-more d align-items-center primary-text"><i class="feather-plus-circle"></i>Show More</div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($loungeDetail->lounge_includes))
                            <div class="accordion-item mb-4" id="includes">
                                <h4 class="accordion-header" id="panelsStayOpen-includes">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                        Includes
                                    </button>
                                </h4>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-includes">
                                    <div class="accordion-body">
                                        @php
                                            $includesArray = explode(',', $loungeDetail->lounge_includes);
                                        @endphp
                                        <ul class="clearfix">
                                            @if(is_array($includesArray) && count($includesArray) > 0) @foreach($includesArray as $includes)
                                                <li><i class="feather-check-square"></i>{{ $includes }}</li>
                                            @endforeach @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($loungeDetail->lounge_rules))
                            <div class="accordion-item mb-4" id="rules">
                                <h4 class="accordion-header" id="panelsStayOpen-rules">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                        Rules
                                    </button>
                                </h4>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-rules">
                                    <div class="accordion-body">
                                        {!! $loungeDetail->lounge_rules !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($loungeDetail->lounge_amenities))
                            <div class="accordion-item mb-4" id="amenities">
                                <h4 class="accordion-header" id="panelsStayOpen-amenities">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                                        Amenities
                                    </button>
                                </h4>
                                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-amenities">
                                    <div class="accordion-body">
                                        @php
                                            $amenitiesArray = explode(',', $loungeDetail->lounge_amenities);
                                        @endphp
                                        <ul class="justify-content-between align-items-center">
                                            @if(is_array($amenitiesArray) && count($amenitiesArray) > 0) @foreach($amenitiesArray as $amenities)
                                                <li><i class="fa fa-check-circle" aria-hidden="true"></i>{{ $amenities }}</li>
                                            @endforeach @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(is_array($limageDetail) && count($limageDetail) > 0)
                            <div class="accordion-item mb-4" id="gallery">
                                <h4 class="accordion-header" id="panelsStayOpen-gallery">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                                        Gallery
                                    </button>
                                </h4>
                                <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-gallery">
                                    <div class="accordion-body">
                                        <div class="owl-carousel gallery-slider owl-theme">
                                            @foreach($limageDetail as $limage)
                                                <a class="corner-radius-10" href="{{ asset('/uploads/lounge/'.$limage['limage_image']) }}" data-fancybox="gallery3">
                                                    <img class="img-fluid corner-radius-10" alt="Image" src="{{ asset('/uploads/lounge/'.$limage['limage_image']) }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="accordion-item" id="location">
                            <h4 class="accordion-header" id="panelsStayOpen-location">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSeven" aria-expanded="false" aria-controls="panelsStayOpen-collapseSeven">
                                    Location
                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseSeven" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-location">
                                <div class="accordion-body">
                                    <div class="google-maps">
                                        {!! $loungeDetail->lounge_google_map !!}
                                    </div>
                                    <div class="dull-bg d-flex justify-content-start align-items-center mt-3">
                                        <div class="white-bg me-2">
                                            <i class="fas fa-location-arrow"></i>
                                        </div>
                                        <div class="">
                                            <h6>Our Venue Location</h6>
                                            <p>{{ $loungeDetail->lounge_address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Accordian Contents -->
                </div>
                <aside class="col-12 col-sm-12 col-md-12 col-lg-4 theiaStickySidebar">
                    {{--<div class="white-bg d-flex justify-content-start align-items-center availability">
                        <div>
                            <span class="icon-bg"><img class="img-fluid" alt="Icon" src="{{ url('/public/img/icons/head-calendar.svg') }}"></span>
                        </div>
                        <div>
                            <h4>Availability</h4>
                            <p class="mb-0">Check availability on your convenient time</p>
                        </div>
                    </div>--}}
                    @if(count($ltimeDetail) > 0)
                        <div class="white-bg book-court">
                            <h4 class="border-bottom">Book A Lounge</h4>
                            {{--<h5 class="d-inline-block">Badminton Academy,</h5><p class="d-inline-block"> available Now</p>--}}
                            <ul class="d-sm-flex align-items-center justify-content-evenly">
                                <div class="col-12 col-md-12">
                                    <div class="">
                                        @foreach($ltimeDetail as $ltime)
                                            <span class="courtnameText fw-700"><b>{{ str_replace(",", ", ", $ltime->days) }}</b></span>
                                            <br>
                                            <div class="mt-2">
                                                <div class="d-flex justify-content-between">
                                                    <div class="mb-3">
                                                        <label class="courtnameText fw-400 ">({{ $ltime->time_range }})</label>
                                                    </div>
                                                    <div class="">
                                                        <b class="primary-text">₹ {{ number_format((float)$ltime->rate, 2) }} / hr</b>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </ul>

                            <div class="d-grid btn-block mt-3">
                                @if(count($ltimeDetail) > 0 && $loungeDetail->lounge_maintenance_status == '0')
                                    @if(count($ltimeDetail) > 0 && $loungeDetail->lounge_book_status == '1')
                                        @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                            <a href="{{ url('/booking-lounge/'.$loungeId) }}" class="btn btn-secondary d-inline-flex justify-content-center align-items-center"><i class="feather-calendar"></i> Book a Lounge</a>
                                        @else
                                            <a href="javascript: void (0)" onclick="return signin_popup()" class="btn btn-secondary d-inline-flex justify-content-center align-items-center"><i class="feather-calendar"></i> Book a Lounge</a>
                                        @endif
                                    @else
                                        <a class="btn btn-secondary d-inline-flex justify-content-center align-items-center">⏳ Bookings will open shortly. Stay tuned for the rhythm of fun! 🎵</a>
                                    @endif
                                @else
                                    <a class="btn btn-secondary d-inline-flex justify-content-center align-items-center w-100">Lounge is Under Maintenance</a>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="white-bg">
                        <h4 class="border-bottom">Share Venue</h4>
                        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                            <a class="a2a_button_facebook"></a>
                            <a class="a2a_button_twitter"></a>
                            <a class="a2a_button_linkedin"></a>
                            <a class="a2a_button_whatsapp"></a>
                            <!--<a class="a2a_dd" href="https://www.addtoany.com/share"></a>-->
                        </div>
                    </div>
                </aside>
            </div>
            <!-- /Row -->
        </div>
        <!-- /Container -->

    </div>
    <!-- /Page Content -->
@endsection