@extends("layouts.app")
@section('title', $bcategoryName->bcategory_meta_title ?? $pagesDetail->page_meta_title)
@section('keywords', $bcategoryName->bcategory_meta_keyword ?? $pagesDetail->page_meta_keyword)
@section('description', $bcategoryName->bcategory_meta_desc ?? $pagesDetail->page_meta_desc)
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
            <div class="section-heading aos" data-aos="fade-up">
                <h2>We Have Excellent <span>Plans For You</span></h2>
                <p class="sub-title">Become a Yaarioke Club Member and enjoy discounted rates on every booking. One membership, unlimited fun — valid across all Yaarioke lounges in India!</p>
            </div>
            <div class="price-wrap aos" data-aos="fade-up">
                <div class="row justify-content-center">
                    @if(is_array($membershipDetail) && count($membershipDetail) > 0)
                        @for($m=0; $m < count($membershipDetail); $m++)
                            <div class="col-lg-4 d-flex col-md-6">
                                <!-- Price Card -->
                                <div class="price-card flex-fill ">
                                    <div class="price-head {{ ($membershipDetail[$m]['membership_recommended'] == '1') ? 'expert-price' : '' }}">
                                        <img  src="{{ url('/public/img/icons/price-01.svg') }}" alt="Price">
                                        <h3>{{ $membershipDetail[$m]['membership_title'] }}</h3>
                                        @if($membershipDetail[$m]['membership_recommended'] == '1')
                                            <span>Recommended</span>
                                        @endif
                                    </div>
                                    <div class="price-body">
                                        {{--<div class="per-month">
                                            <h2><sup>₹</sup><strike><span> {{ number_format($membershipDetail[$m]['membership_price'], 2) }}</span></strike> 51</h2>
                                            <span>{{ $membershipDetail[$m]['membership_duration'] }}</span>
                                        </div>--}}
                                        <div class="per-month">
                                            @if($membershipDetail[$m]['membership_offer_price'] > 0)
                                                <h2>
                                                    <sup>₹</sup>
                                                    <strike>{{ number_format($membershipDetail[$m]['membership_price'], 2) }}</strike>
                                                    <span class="offer-price"><sup>₹</sup> {{ $membershipDetail[$m]['membership_offer_price'] }}</span>
                                                </h2>
                                            @else
                                                <div class="per-month">
                                                    <h2><sup>₹</sup><span> {{ number_format($membershipDetail[$m]['membership_price'], 2) }}</span></h2>
                                                </div>
                                            @endif
                                            <span>{{ $membershipDetail[$m]['membership_duration'] }}</span>
                                        </div>
                                        <div class="features-price-list">
                                            @if($membershipDetail[$m]['membership_discount'] > 0)
                                                <h5>Discount on Lounge Booking</h5>
                                                <ul>
                                                    <li class="active"><span style="font-size:30px;"><strong>{{ $membershipDetail[$m]['membership_discount'] }}% OFF</strong></span></li>
                                                </ul>
                                            @endif

                                            {!! $membershipDetail[$m]['membership_desc'] !!}
                                        </div>
                                        <div class="price-choose">
                                            @if(isset($orderDetail['msorder_end_date']) && strtotime($orderDetail['msorder_end_date']) > time())
                                                @if(isset($orderDetail['membership_id']) && $orderDetail['membership_id'] == $membershipDetail[$m]['membership_id'])
                                                    <a href="javascript:;" class="btn viewdetails-btn">Expire on {{ date('d, F Y', strtotime($orderDetail['msorder_end_date'])) }}</a>
                                                @else
                                                    <a href="javascript:;" class="btn viewdetails-btn">Choose Plan</a>
                                                @endif
                                            @else
                                                @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                                    <a href="{{ url('/payment-membership/'.Illuminate\Support\Facades\Crypt::encrypt($membershipDetail[$m]['membership_id'])) }}" class="btn viewdetails-btn"><i class="feather-calendar"></i>Choose Plan</a>
                                                @else
                                                    <a href="javascript:;" onclick="return signin_popup()" class="btn viewdetails-btn">Choose Plan</a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- /Price Card -->
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection