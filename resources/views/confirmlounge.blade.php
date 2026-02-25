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

    <!-- /Breadcrumb -->
    <section class="booking-steps py-30">
        <div class="container">
            <ul class="d-lg-flex justify-content-center align-items-center">
                <li><h5><a href="javascript: void (0)"><span>1</span>Book a Lounge</a></h5></li>
                <li class="active"><h5><a href="javascript: void (0)"><span>2</span>Order Confirmation</a></h5></li>
                <li><h5><a href="javascript: void (0)"><span>3</span>Payment</a></h5></li>
            </ul>
        </div>
    </section>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <section class="card mb-40">
                <div class="text-center mb-40">
                    <h3 class="mb-1">Order Confirmation</h3>
                    <p class="sub-title">Booking confirmed. Contact support for changes/inquiries. Enjoy your coaching experience with us.</p>
                </div>
                <div class="master-academy dull-whitesmoke-bg card">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <div class="d-sm-flex justify-content-start align-items-center">
                            @if($loungeDetail->lounge_image!='' && file_exists(public_path('/uploads/lounge/'.$loungeDetail->lounge_image)))
                                <a href="javascript:void(0);"><img class="corner-radius-10" src="{{ asset('/uploads/lounge/'.$loungeDetail->lounge_image) }}" alt="{{ $loungeDetail->lounge_name }}" width="100"></a>
                            @endif
                            <div class="info">
                                <h3 class="mb-2">{{ $loungeDetail->lounge_name }}</h3>
                                <p>{{ $loungeDetail->lounge_short_desc }}</p>
                            </div>
                        </div>
                        {{--<div class="white-bg">
                            <p class="mb-1">Starts From</p>
                            <h3 class="d-inline-block primary-text mb-0">$150</h3><span>/hr</span>
                        </div>--}}
                    </div>
                </div>
            </section>
            <section class="card booking-order-confirmation">
                <h5 class="mb-3">Booking Details</h5>
                <ul class="booking-info d-lg-flex justify-content-start align-items-center">
                    <li>
                        <h6>Date</h6>
                        <p>{{ date('D, M Y', strtotime($cartDetail->cart_start_date)) }}</p>
                    </li>
                    <li>
                        <h6>Start time</h6>
                        <p>{{ date('h:i A', strtotime($cartDetail->cart_start_time)) }}</p>
                    </li>
                    <li>
                        <h6>End time</h6>
                        <p>{{ date('h:i A', strtotime($cartDetail->cart_end_time)) }}</p>
                    </li>
                </ul>
                <h5 class="mb-3">Contact  Information</h5>
                <ul class="contact-info d-lg-flex justify-content-start align-items-center">
                    <li>
                        <h6>Name</h6>
                        <p>{{ $customerDetail->customer_name }}</p>
                    </li>
                    <li>
                        <h6>Contact Email Address</h6>
                        <p>{{ $customerDetail->customer_email }}</p>
                    </li>
                    <li>
                        <h6>Phone Number</h6>
                        <p>{{ $customerDetail->customer_mobile }}</p>
                    </li>
                </ul>
                <h5 class="mb-3">Payment Information</h5>
                <ul class="payment-info d-lg-flex justify-content-start align-items-center">
                    <li>
                        <h6>Subtotal</h6>
                        <p class="primary-text">₹ {{ number_format($cartDetail->cart_amount, 2) }}</p>
                    </li>
                </ul>
            </section>
            <div class="text-center btn-row">
                <a class="btn btn-primary me-3 btn-icon" href=""><i class="feather-arrow-left-circle me-1"></i> Back</a>
                <a class="btn btn-secondary btn-icon" href="{{ url('payment-lounge/'.$loungeId) }}">Next <i class="feather-arrow-right-circle ms-1"></i></a>
            </div>
        </div>
        <!-- /Container -->
    </div>
    <!-- /Page Content -->
@endsection