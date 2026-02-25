@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
        <span class="primary-right-round"></span>
        <div class="container">
            <h1 class="text-white">{{ $pagesDetail->page_title ?? '' }}</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $pagesDetail->page_title ?? '' }}</li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row mb-40">
                <div class="col-sm-10 col-md-10 col-lg-10 mx-auto text-center">
                    @if((isset($orderDetail) && $orderDetail->order_status == '1') || (isset($orderDetail) && $orderDetail->msorder_status == '1'))
                        <img src="{{ url('/public/img/success.png') }}" width="10%" class="img-fluid" alt="success">
                    @else
                        <img src="{{ url('/public/img/failure.png') }}" width="10%" class="img-fluid" alt="failure">
                    @endif
                    <div class="card-body mb-5">
                        @if((isset($orderDetail) && $orderDetail->order_status == '1') || (isset($orderDetail) && $orderDetail->msorder_status == '1'))
                            <h3 class="mb-0 item-title">Your Payment Successfully</h3>
                            <h5 class="mb-0">TXN ID: {{ $paymentDetail->TXNID ?? '' }}</h5>
                            <h5 class="mb-0">BANK TXN ID: {{ $paymentDetail->BANKTXNID ?? '' }}</h5>
                        @else
                            <h3 class="mb-0 item-title">Sorry... your payment has been cancelled.</h3>
                        @endif
                    </div>
                    <a href="{{ url('/') }}" class="btn btn-secondary d-inline-flex justify-content-center align-items-center btn-icon">Go to Home<i class="feather-arrow-right-circle ms-2"></i></a>
                </div>
            </div>

            @if(isset($orderDetail) && $orderDetail->order_status == '1' && isset($cartDetail))
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
                            <h6>Booked Slot</h6>
                            <p>{{ str_replace(',', ', ', $cartDetail->cart_duration) }}</p>
                        </li>
                    </ul>
                    <h5 class="mb-3">Contact  Information</h5>
                    <ul class="contact-info d-lg-flex justify-content-start align-items-center">
                        <li>
                            <h6>Name</h6>
                            <p>{{ $orderDetail->customer_name }}</p>
                        </li>
                        @if($orderDetail->customer_email!='')
                            <li>
                                <h6>Contact Email Address</h6>
                                <p>{{ $orderDetail->customer_email }}</p>
                            </li>
                        @endif
                        <li>
                            <h6>Phone Number</h6>
                            <p>{{ $orderDetail->customer_mobile }}</p>
                        </li>
                    </ul>
                    <h5 class="mb-3">Payment Information</h5>
                    <ul class="payment-info d-lg-flex justify-content-start align-items-center">
                        <li>
                            <h6>Amount</h6>
                            <p>₹ {{ number_format($orderDetail->order_paid_price + $orderDetail->discount_price + $orderDetail->membership_discount, 2) }}</p>
                        </li>
                        @if($orderDetail->discount_price > 0)
                            <li>
                                <h6>Discount Amount ({{ $orderDetail->discount_code }})</h6>
                                <p>(-) ₹ {{ number_format($orderDetail->discount_price, 2) }}</p>
                            </li>
                        @endif
                        @if($orderDetail->membership_discount > 0)
                            <li>
                                <h6>Membership Discount</h6>
                                <p>(-) ₹ {{ number_format($orderDetail->membership_discount, 2) }}</p>
                            </li>
                        @endif
                        <li>
                            <h6>Paid Amount</h6>
                            <p class="primary-text">₹ {{ number_format($orderDetail->order_paid_price, 2) }}</p>
                        </li>
                    </ul>
                </section>
            @endif
        </div>
    </div>
    <!-- /Page Content -->
@endsection