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

<!-- Page Content -->
<div class="content">
    <div class="container">
        <section>
            <div class="text-center mb-40">
                <h3 class="mb-1">Payment</h3>
                <p class="sub-title">Securely make your payment for the booking. Contact support for assistance.</p>
            </div>
            {{--<div class="master-academy dull-whitesmoke-bg card mb-40">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-sm-flex justify-content-start align-items-center">
                        @if($loungeDetail->lounge_image!='' && file_exists(public_path('/uploads/lounge/'.$loungeDetail->lounge_image)))
                        <a href="javascript:void(0);"><img class="corner-radius-10" src="{{ asset('/uploads/lounge/'.$loungeDetail->lounge_image) }}" alt="{{ $loungeDetail->lounge_name }}" width="100"></a>
                        @endif
                        <div class="info">
                            <h3 class="mb-2">{{ $loungeDetail->lounge_name }}</h3>
                            <p>{{ $loungeDetail->lounge_short_desc }}</p>
                        </div>
                    </div>
                </div>
            </div>--}}
            <div class="row checkout">
                <div class="col-12 col-sm-12 col-md-12 col-lg-7">
                    <div class="card booking-details">
                        <h3 class="border-bottom">Order Summary</h3>
                        <ul>
                            <li><i class="feather-calendar me-2"></i>{{ $membershipDetail->membership_title }}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                    <aside class="card payment-modes">
                        <h3 class="border-bottom">Checkout</h3>
                        <h6 class="mb-3">Select Payment Gateway</h6>
                        <input type="hidden" id="customer_id" name="customer_id" value="{{ Session::get('customer_id'); }}">
                        <input type="hidden" id="membership_id" name="membership_id" value="{{ $membershipDetail->membership_id }}">
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
                                @if($membershipDetail->membership_offer_price > 0)
                                    <input type="hidden" id="cart_subtotal" value="<?= base64_encode($membershipDetail->membership_offer_price); ?>">
                                @else
                                    <input type="hidden" id="cart_subtotal" value="<?= base64_encode($membershipDetail->membership_price); ?>">
                                @endif
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
                                @if($membershipDetail->membership_offer_price > 0)
                                    <h6>₹ {{ number_format($membershipDetail->membership_offer_price, 2) }}</h6>
                                @else
                                    <h6>₹ {{ number_format($membershipDetail->membership_price, 2) }}</h6>
                                @endif
                            </li>
                            @if(session()->has('discount_id') && session()->has('discount_id') > 0)
                                <li>
                                    <p>Discount (<a href="javascript: void (0);" class="remove text-danger" data-bs-toggle="modal" data-bs-target="#removeDiscount" title="Remove Discount"><i class="feather-trash-2"></i></a>)</p>
                                    <h6 class="text-success">- ₹ {{ Session::get('discount') }}</h6>
                                </li>
                            @endif
                        </ul>
                        <div class="order-total d-flex justify-content-between align-items-center">
                            <h5>Order Total</h5>
                            @if($membershipDetail->membership_offer_price > 0)
                                <h5>₹ {{ number_format($membershipDetail->membership_offer_price - Session::get('discount'), 2) }}</h5>
                            @else
                                <h5>₹ {{ number_format($membershipDetail->membership_price - Session::get('discount'), 2) }}</h5>
                            @endif
                        </div>
                        <div class="form-check d-flex justify-content-start align-items-center policy">
                            <div class="d-inline-block">
                                <input class="form-check-input" type="checkbox" id="order_tc">
                            </div>
                            <label class="form-check-label" for="policy">By clicking 'Send Request', I agree to YAARIOKE - The Karaoke Lounge <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy Policy</a> and <a href="{{ url('/terms-conditions') }}" target="_blank">Terms of Use</a></label>
                        </div>
                        <div class="d-grid btn-block">
                            @if($membershipDetail->membership_offer_price > 0)
                                <button type="button" id="membershipOrder" name="membershipOrder" class="btn btn-primary">Proceed  ₹ {{ number_format($membershipDetail->membership_offer_price - Session::get('discount'), 2) }}</button>
                            @else
                                <button type="button" id="membershipOrder" name="membershipOrder" class="btn btn-primary">Proceed  ₹ {{ number_format($membershipDetail->membership_price - Session::get('discount'), 2) }}</button>
                            @endif
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

    $('#membershipOrder').on('click', function () {
        let customer_id = '{{ Session::get('customer_id'); }}';
        let membership_id = $('#membership_id').val();
        var error = false;
        if($("#order_tc").prop("checked") == false){
            error = true;
            $('#msg_tc').html('Please accept terms and conditions');
            $('#msg_tc').css('color', 'red');
            $('#order_tc').css({'border' : '1px solid red','margin-bottom':'0px','margin-left':'15px','padding-left': '1px','border-radius':'3px'});
        } else {
            $('#order_tc').css({'border' : 'none','margin-bottom':'0px !important','margin-left':'0px !important','padding-left': '0px'});
            $('#msg_tc').html("");
        }

        //alert($("input[name='order_payment_method']:checked").val())
        if (error == false && customer_id > 0 && membership_id > 0) {
            $.ajax({
                url: '{{ route('payment-membership-insert') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_id,
                    membership_id
                },
                dataType: 'json',
                success: function (data) {
                    if (data.message == 'success') {
                        $('#membershipOrder').prop('disabled', true);
                        var msorder_unique_id = data.msorder_unique_id;
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
                                    url: '{{ route('payment-membership-verify') }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        msorder_unique_id: msorder_unique_id,
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
                    }
                }
            });
        }
    });
</script>
@endsection