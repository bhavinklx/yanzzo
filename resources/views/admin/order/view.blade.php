@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">

            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Invoice</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Invoice</li>
                        </ol>
                        <a href="javascript: void (0)" id="print" class="btn-sm btn-danger d-none d-lg-block m-l-15"><i class="fa fa-print"></i> Invoice Print</a>
                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->

            <!-- Start Page Content -->
            <div class="row">
                <div class="col-md-10">
                    <div class="card card-body printableArea">
                        <h3><b>Order Id: #{{ $orderDetail->order_unique_id }}</b> <!--<span class="pull-right">#<?/*= $orderDetail->order_unique_id; */?></span>--></h3>
                        {{--<p class="m-b-0">Customer IP:</p>--}}
                        <hr>
                        <div class="row">
                            <div class="row col-md-12">
                                <div style="width: 33%">
                                    <address>
                                        <h4><b class="text-danger m-l-10">Billing</b></h4>
                                        <h6 class="font-bold m-l-10">{{ $orderDetail->customer_name }}</h6>
                                        <p class="text-muted m-l-10">
                                            <span class="font-medium">Phone: </span>{{ $orderDetail->customer_mobile }}
                                        </p>
                                    </address>
                                </div>
                                <div style="width: 33%">
                                    <address>
                                        <h4><b class="text-danger">Lounge</b></h4>
                                        <h6 class="font-bold">{{ $loungeDetail->lounge_name }}</h6>
                                        <p class="text-muted">
                                            {{ $loungeDetail->lounge_address }}
                                        </p>
                                    </address>
                                </div>
                                @php $paymentStatus = 'Pending'; @endphp
                                @if($orderDetail->order_status == "1")
                                    @php $paymentStatus = 'Completed'; @endphp
                                @elseif($orderDetail->order_status == "2")
                                    @php $paymentStatus = 'Cancelled'; @endphp
                                @elseif($orderDetail->order_status == "3")
                                    @php $paymentStatus = 'Not Confirmed'; @endphp
                                @elseif($orderDetail->order_status == "4")
                                    @php $paymentStatus = 'Confirmed'; @endphp
                                @endif
                                <div style="width: 33%">
                                    <address>
                                        <table class="table table-hover pull-right" style="width: 70%;">
                                            <tr>
                                                <th><span class="font-medium">Order Date</span></th>
                                                <td>{{ date('d-m-Y', strtotime($orderDetail->order_date)) }} ({{ date('h:i A', strtotime($orderDetail->order_created_date)) }})</td>
                                            </tr>
                                            <tr>
                                                <th><span class="font-medium">Payment Method</span></th>
                                                <td>{{ ($orderDetail->order_paid_price > 0) ? $orderDetail->order_type : 'Free' }}</td>
                                            </tr>
                                            @if(isset($paymetDetail->TXNID))
                                                <tr>
                                                    <th><span class="font-medium">Trnsaction#</span></th>
                                                    <td>{{ $paymetDetail->TXNID }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th><span class="font-medium">Payment Status</span></th>
                                                <td>{{ $paymentStatus }}</td>
                                            </tr>
                                        </table>
                                    </address>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" style="clear: both;">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Lounge</th>
                                            <th>Order Summary</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                @if($loungeDetail->lounge_image!='' && file_exists(public_path('/uploads/lounge/'.$loungeDetail->lounge_image)))
                                                    <img class="corner-radius-10" src="{{ asset('/uploads/lounge/'.$loungeDetail->lounge_image) }}" alt="{{ $loungeDetail->lounge_name }}" width="100">
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $loungeDetail->lounge_name }}</strong><br>
                                                {{ $loungeDetail->lounge_address }}
                                            </td>
                                            <td>
                                                @if($orderDetail->order_status == '1' && $cartDetail->cart_reschedule == '1')
                                                    @if(isset($cartDetail))
                                                        <strike>
                                                            {{ date('d, M Y', strtotime($cartlogDetail->clog_start_date)) }}<br>
                                                            {{ str_replace(',', ', ', $cartlogDetail->clog_duration) }}<br>
                                                            <strong>{{ $cartDetail->cart_adults }}  Adults, {{ $cartDetail->cart_children }} Children</strong>
                                                        </strike>
                                                        <br>
                                                        <br>
                                                        {{ date('d, M Y', strtotime($cartDetail->cart_start_date)) }}<br>
                                                        {{ str_replace(',', ', ', $cartDetail->cart_duration) }}<br>
                                                        <strong>{{ $cartDetail->cart_adults }}  Adults, {{ $cartDetail->cart_children }} Children</strong>
                                                    @else
                                                        --
                                                    @endif
                                                @else
                                                    @if(isset($cartDetail))
                                                        {{ date('d, M Y', strtotime($cartDetail->cart_start_date)) }}<br>
                                                        {{ str_replace(',', ', ', $cartDetail->cart_duration) }}<br>
                                                        <strong>{{ $cartDetail->cart_adults }}  Adults, {{ $cartDetail->cart_children }} Children</strong>
                                                    @else
                                                        --
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <i class="fa fa-inr" aria-hidden="true"></i> {{ $orderDetail->order_paid_price + $orderDetail->discount_price + $orderDetail->membership_discount }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="pull-right m-t-30 text-right">
                                    <p>Sub-Total Amount: <span class="text-success"><i class="fa fa-inr" aria-hidden="true"></i> {{ $orderDetail->order_paid_price + $orderDetail->discount_price + $orderDetail->membership_discount }}</span></p>
                                    @if($orderDetail->membership_discount > 0)
                                        <p>Membership Discount: <span class="text-danger">- <i class="fa fa-inr" aria-hidden="true"></i> {{ $orderDetail->membership_discount }}</span></p>
                                    @endif
                                    @if($orderDetail->discount_price > 0)
                                        <p>Discount: ({{ $orderDetail->discount_code }}) <span class="text-danger">- <i class="fa fa-inr" aria-hidden="true"></i> {{ $orderDetail->discount_price }}</span></p>
                                    @endif
                                    <hr>
                                    <h3><b>Total :</b> <i class="fa fa-inr" aria-hidden="true"></i> {{ $orderDetail->order_paid_price }}</h3>
                                </div>
                                <div class="clearfix"></div>
                                <hr>
                                <!--<div class="text-right">
                                    <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card card-body">
                        <h4><b>Payment Action</b></h4>
                        <hr/>
                        @if($orderDetail->order_status == "0")
                            <span class="badge badge-warning badge-pill">Pending</span>
                        @elseif($orderDetail->order_status == "1")
                            <span class="badge badge-success badge-pill">Completed</span>
                        @elseif($orderDetail->order_status == "2")
                            <span class="badge badge-danger badge-pill">Cancel</span>
                        @elseif($orderDetail->order_status == "3")
                            <span class="badge badge-danger badge-pill">Not Confirmed</span>
                        @elseif($orderDetail->order_status == "4")
                            <span class="badge badge-success badge-pill">Confirmed</span>
                        @endif
                    </div>

                    <div class="card card-body">
                        <h4><b>Order Action</b></h4>
                        <hr/>
                        @php
                            $pending = $initiate = $shipped = $completed = $cancelled = $refunded = "";
                        @endphp
                        @if($orderDetail->order_ostatus == "pending")
                            @php $pending = "selected='selected'"; @endphp
                        @elseif($orderDetail->order_ostatus == "completed")
                            @php $completed = "selected='selected'"; @endphp
                        @elseif($orderDetail->order_ostatus == "cancelled")
                            @php $cancelled = "selected='selected'"; @endphp
                        @elseif($orderDetail->order_ostatus == "refunded")
                            @php  $refunded = "selected='selected'"; @endphp
                        @endif

                        @if($orderDetail->order_ostatus == "pending")
                            <select class="form-control p-0" onchange="return change_status('{{ $orderDetail->order_id }}', this.value)">
                                <option value="0">Select Status</option>
                                <option {{ ($orderDetail->order_ostatus == "pending") ? "selected='selected'" : "" }} value="pending">Pending</option>
                                <option {{ ($orderDetail->order_ostatus == "completed") ? "selected='selected'" : "" }} value="completed">Completed</option>
                                <option {{ ($orderDetail->order_ostatus == "cancelled") ? "selected='selected'" : "" }} value="cancelled">Cancelled</option>
                                <option {{ ($orderDetail->order_ostatus == "refunded") ? "selected='selected'" : "" }} value="refunded">Refunded</option>
                            </select>
                        @elseif($orderDetail->order_ostatus == "completed")
                            <select class="form-control p-0">
                                <option value="0">Select Status</option>
                                <option {{ ($orderDetail->order_ostatus == "completed") ? "selected='selected'" : "" }} value="completed">Completed</option>
                            </select>
                        @elseif($orderDetail->order_ostatus == "cancelled")
                            <select class="form-control p-0" onchange="return change_status('{{ $orderDetail->order_id }}', this.value)">
                                <option value="0">Select Status</option>
                                <option {{ ($orderDetail->order_ostatus == "cancelled") ? "selected='selected'" : "" }} value="cancelled">Cancelled</option>
                                <option {{ ($orderDetail->order_ostatus == "refunded") ? "selected='selected'" : "" }} value="refunded">Refunded</option>
                            </select>
                        @elseif($orderDetail->order_ostatus == "refunded")
                            <select class="form-control p-0">
                                <option value="0">Select Status</option>
                                <option {{ ($orderDetail->order_ostatus == "refunded") ? "selected='selected'" : "" }} value="refunded">Refunded</option>
                            </select>
                        @endif
                    </div>

                    <div class="card card-body">
                        <h4><b>General</b></h4>
                        @if($orderDetail->order_cancel_reason!="")
                            <hr/>
                            <p class="text-muted">
                                <span class="font-medium">Cancel Date: </span>{{ date('d-m-Y h:i A', strtotime($orderDetail->order_cancel_date)) }}
                                <br/>
                                <span class="font-medium">Cancel Reason: </span>{{ $orderDetail->order_cancel_reason }}
                            </p>
                        @endif
                        @if($orderDetail->order_refund_reason!="")
                            <hr/>
                            <p class="text-muted">
                                <span class="font-medium">Refund Date: </span>{{ date('d-m-Y h:i A', strtotime($orderDetail->order_refund_date)) }}
                                <br/>
                                <span class="font-medium">Refund Reason: </span>{{ $orderDetail->order_refund_reason }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End PAge Content -->

        </div>
        <!-- End Container fluid  -->
    </div>
    <!-- End Page wrapper  -->

    <!-- sample modal content start -->
    <a id="cancelledModel" data-toggle="modal" data-target="#Cancelled-modal"></a>
    <div id="Cancelled-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; margin-top: 39px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cancelled Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="cancelledFrm" class="floating-labels" method="post">
                        <input type="hidden" name="order_id" id="cancelled_order_id" />
                        <input type="hidden" name="status" id="cancelled_status" />

                        <div class="form-group m-t-20" id="c_reason">
                            <input type="text" class="form-control" name="cancel_reason" id="cancel_reason" />
                            <span class="bar"></span>
                            <label for="cancel_reason">Cancelled Reason</label>
                            <span class="help-block"><small id="msg_cancel_reason" class="text-danger"></small></span>
                        </div>
                    </form>
                    <span id="cancelledMsg" class="help-block text-success"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" id="cancelledBtn" onclick="return validate_cancelled();" class="btn btn-success waves-effect waves-light">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- sample modal content end -->

    <!-- sample modal content start -->
    <a id="refundedModel" data-toggle="modal" data-target="#Refunded-modal"></a>
    <div id="Refunded-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; margin-top: 39px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Refunded Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="refundedFrm" class="floating-labels" method="post">
                        <input type="hidden" name="order_id" id="refunded_order_id" />
                        <input type="hidden" name="status" id="refunded_status" />

                        <div class="form-group m-t-20" id="r_reason">
                            <input type="text" class="form-control" name="refund_reason" id="refund_reason" />
                            <span class="bar"></span>
                            <label for="refund_reason">Refunded Reason</label>
                            <span class="help-block"><small id="msg_refund_reason" class="text-danger"></small></span>
                        </div>
                    </form>
                    <span id="refundedMsg" class="help-block text-success"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" onclick="return validate_refunded();" class="btn btn-success waves-effect waves-light">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- sample modal content end -->
@endsection

@section('page-js')
    <script>
        function change_status(order_id, status) {
            if (status == 'cancelled') {
                $('#cancelledFrm').find('input:text, select, textarea').val('');
                $('#c_reason').removeClass('has-error');
                $('#msg_cancel_reason').html('');
                $("#cancelledModel").click();
                $("#cancelled_order_id").val(order_id);
                $("#cancelled_status").val(status);
            } else if (status == 'refunded') {
                $('#refundedFrm').find('input:text, select, textarea').val('');
                $('#r_reason').removeClass('has-error');
                $('#msg_porder_refund_reason').html('');
                $("#refundedModel").click();
                $("#refunded_order_id").val(order_id);
                $("#refunded_status").val(status);
            } else {
                $.ajax({
                    url: "{{ route('order-change-status') }}",
                    method: "POST",
                    data: {
                        order_id:order_id,
                        status:status,
                        _token:"{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $.toast({
                            heading: response
                            , position: 'top-right'
                            , loaderBg: '#ff6849'
                            , icon: 'success'
                            , hideAfter: 3500
                            , stack: 6
                        });
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                });
            }
        }

        function validate_cancelled() {
            var error = false;
            if ($('#cancel_reason').val() == "") {
                error = true;
                $('#c_reason').addClass('has-error');
                $('#msg_cancel_reason').html('Please enter your cancelled reason.');
            } else {
                $('#c_reason').removeClass('has-error');
                $('#msg_cancel_reason').html('');
            }

            if (error == false) {
                $('#cancelledBtn').addClass('btn-disable');
                var order_id = $('#cancelled_order_id').val();
                var status = $('#cancelled_status').val();
                var cancel_reason = $('#cancel_reason').val();
                $.ajax({
                    url: "{{ route('order-change-cancelled-status') }}",
                    method: "POST",
                    data: {
                        order_id:order_id,
                        status:status,
                        cancel_reason:cancel_reason,
                        _token:"{{ csrf_token() }}"
                    },
                    success: function(msg){
                        $('#submit').hide();
                        $('#cancelledFrm').slideUp();
                        $('#cancelledMsg').html('Cancelled Detail added successfully.');
                        $.toast({
                            heading: msg
                            , position: 'top-right'
                            , loaderBg: '#ff6849'
                            , icon: 'success'
                            , hideAfter: 3500
                            , stack: 6
                        });
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                });
            }
        }

        function validate_refunded() {
            var error = false;
            if ($('#refund_reason').val() == "") {
                error = true;
                $('#r_reason').addClass('has-error');
                $('#msg_refund_reason').html('Please enter your refunded reason.');
            } else {
                $('#r_reason').removeClass('has-error');
                $('#msg_refund_reason').html('');
            }

            if (error == false) {
                $('#refundedBtn').addClass('btn-disable');
                var order_id = $('#refunded_order_id').val();
                var status = $('#refunded_status').val();
                var refund_reason = $('#refund_reason').val();
                $.ajax({
                    url: "{{ route('order-change-refunded-status') }}",
                    method: "POST",
                    data: {
                        order_id:order_id,
                        status:status,
                        refund_reason:refund_reason,
                        _token:"{{ csrf_token() }}"
                    },
                    success: function(msg){
                        $('#refundedBtn').hide();
                        $('#refundedFrm').slideUp();
                        $('#refundedMsg').html('Refunded Detail added successfully.');
                        $.toast({
                            heading: msg
                            , position: 'top-right'
                            , loaderBg: '#ff6849'
                            , icon: 'success'
                            , hideAfter: 3500
                            , stack: 6
                        });
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                });
            }
        }
    </script>
@endsection