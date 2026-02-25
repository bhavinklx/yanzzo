@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">{{ ucwords($status) }} Booking</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ ucwords($status) }} Booking List</li>
                        </ol>
                        {{--<a href="{{ route("contact-export") }}" class="btn-sm btn-warning d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Export All</a>--}}
                        {{--<a href="javascript: void(0);" onclick="validate_all('activate_all')" class="btn-sm btn-success d-none d-lg-block m-l-15"><i class="fa fa-check-circle"></i> Active All</a>
                        <a href="javascript: void(0);" onclick="validate_all('inactivate_all')" class="btn-sm btn-warning d-none d-lg-block m-l-15"><i class="fa fa-remove"></i> Inactive All</a>
                        <a href="javascript: void(0);" onclick="validate_all('delete_all')" class="btn-sm btn-danger d-none d-lg-block m-l-15"><i class="fa fa-trash-o"></i> Delete All</a>--}}
                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->

            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="listFrm" name="listFrm" action="" method="post">
                                <input type="hidden" id="action" name="action" value="">
                                <div class="table-responsive ">
                                    <table id="myTable1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" class="custom-checkbox" name="checkall" id="checkall"/></th>
                                            <th>Order#</th>
                                            <th width="175">Billing</th>
                                            <th>Booking Date & Time</th>
                                            <th width="250">Payment Detail</th>
                                            <th width="135">Payment</th>
                                            <th width="135">Order Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">

                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
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
    <script type="text/javascript">
        var page_length = 25;
        $('#myTable1').DataTable({
            pageLength: page_length,
            processing: true,
            serverSide: true,
            responsive: true,
            rowReorder: true,
            order: [],
            paging: true,
            ajax: {
                url: '{{ route("order-load-table") }}',
                type: 'GET',
                data: function (d) {
                    var urlSegments = window.location.pathname.split('/');
                    d.status = urlSegments[urlSegments.length - 1]; // Pass the extracted status to the request
                }
            },
            columns: [
                { data: 'checkbox', name: 'checkbox' },
                { data: 'order', name: 'order' },
                { data: 'billing', name: 'billing' },
                { data: 'booking', name: 'booking' },
                { data: 'payment', name: 'payment' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'ostatus', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

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

        function deleteData(order_id) {
            $.ajax({
                url: "{{ route('order-delete') }}",
                type: "POST",
                data: {
                    order_id:order_id,
                    _token:'{{ csrf_token() }}'
                },
                success: function (response) {
                }
            });
        }
    </script>
@endsection