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
                {{--<li><h5><a href="javascript: void (0)"><span>2</span>Order Confirmation</a></h5></li>--}}
                <li class="active"><h5><a href="javascript: void (0)"><span>2</span>Payment</a></h5></li>
            </ul>
        </div>
    </section>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <section>
                <div class="text-center mb-40">
                    <h3 class="mb-1">Payment</h3>
                    <p class="sub-title">Securely make your payment for the booking. Contact support for assistance.</p>
                </div>
                <div class="master-academy dull-whitesmoke-bg card mb-40">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-sm-flex justify-content-start align-items-center">
                            @if($loungeDetail->lounge_image!='' && file_exists(public_path('/uploads/lounge/'.$loungeDetail->lounge_image)))
                                <a href="javascript:void(0);"><img class="corner-radius-10" src="{{ asset('/uploads/lounge/'.$loungeDetail->lounge_image) }}" alt="{{ $loungeDetail->lounge_name }}" width="100"></a>
                            @endif
                            <div class="info">
                                <h3 class="mb-2">{{ $loungeDetail->lounge_name }}</h3>
                                {{--<p>{{ $loungeDetail->lounge_short_desc }}</p>--}}
                                <p>{{ $loungeDetail->lounge_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row checkout">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-7">
                        <div class="card booking-details">
                            <h3 class="border-bottom">Order Summary</h3>
                            @php
                                $firstOrderDiscount = $membershipDiscount = 0;
                                // Convert to timestamps
                                $startTimeStamp = strtotime(date('h:i A', strtotime($cartDetail->cart_start_time)));
                                $endTimeStamp = strtotime(date('h:i A', strtotime($cartDetail->cart_end_time)));

                                // If end time is less than or equal to start time, add 1 day (86400 seconds)
                                if ($endTimeStamp <= $startTimeStamp) {
                                    $endTimeStamp += 86400;
                                }
                                // Calculate difference in seconds
                                $diffSeconds = $endTimeStamp - $startTimeStamp;

                                // Convert to hours (including fraction)
                                $totalHours = $diffSeconds / 3600;
                            @endphp
                            @if(isset($morderDetail))
                                @php
                                    $membershipDiscount = @ceil(($cartDetail->cart_amount * $membershipDetail->membership_discount) / 100);
                                @endphp
                            @endif
                            <ul>
                                <li><i class="feather-calendar me-2"></i>{{ date('d, F Y', strtotime($cartDetail->cart_start_date)) }}</li>
                                {{--<li><i class="feather-clock me-2"></i>{{ date('h:i A', strtotime($cartDetail->cart_start_time)) }} - {{ date('h:i A', strtotime($cartDetail->cart_end_time)) }} ({{ round($totalHours, 2) }} Hrs)</li>--}}
                                <li><i class="feather-clock me-2"></i>{{ str_replace(',', ', ', $cartDetail->cart_duration) }}</li>
                                <li><i class="feather-users me-2"></i>{{ $cartDetail->cart_adults }}  Adults, {{ $cartDetail->cart_children }} Children</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                        <aside class="card payment-modes">
                            <h3 class="border-bottom">Checkout</h3>
                            <h6 class="mb-3">Select Payment Gateway</h6>
                            <input type="hidden" id="customer_id" name="customer_id" value="{{ Session::get('customer_id'); }}">
                            <input type="hidden" id="lounge_id" name="lounge_id" value="{{ $loungeDetail->lounge_id }}">
                            <div class="radio">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input default-check me-2" type="radio" name="order_payment_method" id="order_payment_method" value="Razorpay" checked>
                                    <label class="form-check-label" for="order_payment_method">Razorpay</label>
                                </div>
                                {{--<div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input default-check me-2" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Paypal">
                                    <label class="form-check-label" for="inlineRadio2">Paypal</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input default-check me-2" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="Wallet">
                                    <label class="form-check-label" for="inlineRadio3">Wallet</label>
                                </div>--}}
                                <div id="msg_opm"></div>
                            </div>
                            <hr>
                            @if(session()->has('discount_id') && session()->has('discount_id') > 0)
                                <p style="color: green; margin-bottom: 0px">{{ Session::get('discount_text') }} | Discount Code : {{ Session::get('discount_code') }}</p>
                            @else
                                <form class="form-inline row" method="post">
                                    <div class="form-group col-md-6">
                                        <input type="hidden" id="cart_subtotal" value="<?= base64_encode($cartDetail->cart_amount - $membershipDiscount); ?>">
                                        <input type="text" id="discount_code" name="discount_code" class="form-control" placeholder="Enter Coupon code" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <button type="button" class="btn btn-primary w-100" id="applyCouponBtn" onclick="apply_discount()">Apply coupon</button>
                                    </div>
                                    <span id="discount_error"></span>
                                </form>
                            @endif
                            <hr>
                            <ul class="order-sub-total">
                                <li>
                                    <p>Sub total</p>
                                    <h6>₹ {{ number_format($cartDetail->cart_amount, 2) }}</h6>
                                </li>
                                {{--@if(isset($totalOrder) && $totalOrder == 0)
                                    @php
                                        $firstOrderDiscount = 999;
                                    @endphp
                                    <li>
                                        <p>First Order Discount</p>
                                        <h6 class="text-success">- ₹ {{ number_format($firstOrderDiscount, 2) }}</h6>
                                    </li>
                                @endif--}}
                                @if($membershipDiscount > 0)
                                    <li>
                                        <p>Membership Discount <br>(<span>{{ $membershipDetail->membership_title . ' - ' . $membershipDetail->membership_discount }}%</span>)</p>
                                        <h6 class="text-success">- ₹ {{ number_format($membershipDiscount, 2) }}</h6>
                                    </li>
                                @endif
                                @if(session()->has('discount_id') && session()->has('discount_id') > 0)
                                    <li>
                                        <p>Discount (<a href="javascript: void (0);" class="remove text-danger" data-bs-toggle="modal" data-bs-target="#removeDiscount" title="Remove Discount"><i class="feather-trash-2"></i></a>)</p>
                                        <h6 class="text-success">- ₹ {{ number_format(Session::get('discount'), 2) }}</h6>
                                    </li>
                                @endif
                            </ul>
                            <div class="order-total d-flex justify-content-between align-items-center">
                                <h5>Order Total</h5>
                                <h5>₹ {{ number_format($cartDetail->cart_amount - $membershipDiscount - Session::get('discount'), 2) }}</h5>
                            </div>
                            <div class="form-check d-flex justify-content-start align-items-center policy">
                                <div class="d-inline-block">
                                    <input class="form-check-input" type="checkbox" id="order_tc">
                                </div>
                                <label class="form-check-label" for="policy">By clicking 'Send Request', I agree to YAARIOKE - The Karaoke Lounge <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy Policy</a> and <a href="{{ url('/terms-conditions') }}" target="_blank">Terms of Use</a></label>
                            </div>
                            <div class="d-grid btn-block">
                                <button type="button" id="placeOrder" name="placeOrder" class="btn btn-primary">Proceed  ₹ {{ number_format($cartDetail->cart_amount - $membershipDiscount - Session::get('discount'), 2) }}</button>
                            </div>
                            <div class="mt-3 policy">
                                <p style="margin-bottom: 0px"><strong>Disclaimer:</strong></p>
                                <ul>
                                    <li>• CCTV surveillance is active for your safety and security.</li>
                                    <li>• Customers are responsible for the content they play.</li>
                                    <li>• Please avoid offensive or pirated material.</li>
                                    <li>• Management is not liable for song availability or technical interruptions.</li>
                                    <li>• Any damage to equipment may be chargeable.</li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </div>
        <!-- Container -->
    </div>
    <!-- /Page Content -->
@endsection

@section('page-js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#confirmRemoveDiscount').click(function () {
                $.ajax({
                    url: '{{ route('remove-discount') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        location.reload(true);
                    }
                });
            });
        });

        function apply_discount() {
            var error = false;
            if ($("#discount_code").val() == "") {
                var error = true;
                $('#discount_code').css('border', '1px solid red');
                $('#discount_error').show().css('color', 'red').text('please enter coupon code');
            } else {
                $('#discount_code').css('border', '');
                $('#discount_error').hide();
            }

            if (error == false) {
                $('#applyCouponBtn').prop('disabled', true).text('Applying...');
                var cart_subtotal = $("#cart_subtotal").val();
                var discount_code = $("#discount_code").val();
                $.ajax({
                    url: '{{ route('apply-discount') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cart_subtotal,
                        discount_code
                    },
                    dataType: 'json',
                    success: function(response) {
                        //alert(response)
                        if (response.message == 'failed') {
                            $('#applyCouponBtn').prop('disabled', false).text('Apply coupon');
                            $('#discount_code').css('border', '1px solid red');
                            $('#discount_error').show().css('color', 'red').text('Invalid Coupon code');
                        } else if (response.message == '00') {
                            $('#applyCouponBtn').prop('disabled', false).text('Apply coupon');
                            $('#discount_code').css('border', '1px solid red');
                            $('#discount_error').show().css('color', 'red').text('This Offer Code Is Not Applied For This Price!');
                        } else if (response.message == 'expire') {
                            $('#applyCouponBtn').prop('disabled', false).text('Apply coupon');
                            $('#discount_code').css('border', '1px solid red');
                            $('#discount_error').show().css('color', 'red').text('This Offer Code Is Expired Use Different Code!');
                        } else if (response.message == 'minimum') {
                            $('#applyCouponBtn').prop('disabled', false).text('Apply coupon');
                            $('#discount_code').css('border', '1px solid red');
                            $('#discount_error').show().css('color', 'red').text('This Offer Code Is Not Applied For This Price!');
                        } else if (response.message == 'already') {
                            $('#applyCouponBtn').prop('disabled', false).text('Apply coupon');
                            $('#discount_code').css('border', '1px solid red');
                            $('#discount_error').show().css('color', 'red').text('This Offer Code Is Already Used For This Package!');
                        } else {
                            $('#discount_error').show().css('color', 'green').text(response.msg_text);
                            setTimeout(function () {
                                location.reload(true);
                            }, 1000)
                        }
                    }
                });
            }
        }

        $('#placeOrder').on('click', function () {
            let customer_id = $('#customer_id').val();
            let lounge_id = $('#lounge_id').val();
            var error = false;
            //alert($("input[name='order_payment_method']:checked").val())
            if($("input[name='order_payment_method']:checked").val() == undefined){
                error = true;
                $('#msg_opm').html('Please select any one payment method');
                $('#msg_opm').css('color', 'red');
            } else {
                $('#msg_opm').html("");
            }

            if($("#order_tc").prop("checked") == false){
                error = true;
                $('#msg_tc').html('Please accept terms and conditions');
                $('#msg_tc').css('color', 'red');
                $('#order_tc').css({'border' : '1px solid red','margin-bottom':'0px','margin-left':'15px','padding-left': '1px','border-radius':'3px'});
            } else {
                $('#order_tc').css({'border' : 'none','margin-bottom':'0px !important','margin-left':'0px !important','padding-left': '0px'});
                $('#msg_tc').html("");
            }

            if (error == false) {
                $.ajax({
                    url: '{{ route('payment-lounge-insert') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        customer_id,
                        lounge_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.message == 'success') {
                            $('#placeOrder').prop('disabled', true);
                            var order_unique_id = data.order_unique_id;
                            var options = {
                                "key": data.razorpay_key,
                                "amount": data.amount,
                                "currency": "INR",
                                "name": "YAARIOKE - The Karaoke Lounge",
                                "description": data.description,
                                "order_id": data.order_id,
                                "handler": function (response) {
                                    // Verify payment
                                    $.ajax({
                                        url: '{{ route('payment-lounge-verify') }}',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            order_unique_id: order_unique_id,
                                            razorpay_payment_id: response.razorpay_payment_id,
                                            razorpay_order_id: response.razorpay_order_id,
                                            razorpay_signature: response.razorpay_signature
                                        },
                                        success: function (res) {
                                            if (res.success) {
                                                //alert('Payment Successful!');
                                                window.location.href = '/payment-success/'+res.razorpay_payment_id;
                                            } else {
                                                //alert('Payment verification failed!');
                                                window.location.href = '/payment-failed/'+res.razorpay_payment_id;
                                            }
                                        }
                                    });
                                },
                                /*modal: {
                                    ondismiss: function (response) {
                                        alert('Payment verification failed!');
                                        // User closed the popup
                                        window.location.href = "/payment-failed/"+response.razorpay_payment_id;
                                    }
                                },*/
                                "prefill": {
                                    name: data.customer_name,
                                    email: data.customer_email,
                                    contact: data.customer_phone
                                },
                                "theme": {
                                    "color": "#528FF0"
                                }
                            };
                            var rzp1 = new Razorpay(options);
                            rzp1.open();
                        } else if (data.message == 'free') {
                            $.ajax({
                                url: '{{ route('payment-lounge-verify') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    order_unique_id: data.order_unique_id
                                },
                                success: function (res) {
                                    if (res.success) {
                                        //alert('Payment Successful!');
                                        window.location.href = '/payment-success/'+res.razorpay_payment_id;
                                    } else {
                                        //alert('Payment verification failed!');
                                        window.location.href = '/payment-failed/'+res.razorpay_payment_id;
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });
    </script>
@endsection