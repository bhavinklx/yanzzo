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

    <!-- Dashboard Menu -->
    <div class="dashboard-section coach-dash-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard-menu coaurt-menu-dash text-center">
                        <ul>
                            <li>
                                <a href="{{ url('/my-account') }}">
                                    <img src="{{ url('/public/img/icons/profile-icon.svg') }}" alt="Profile Setting">
                                    <span>Profile Setting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-booking') }}" class="active">
                                    <img src="{{ url('/public/img/icons/booking-icon.svg') }}" alt="My Bookings">
                                    <span>My Bookings</span>
                                </a>
                            </li>
                            {{--<li>
                                <a href="coach-wallet.html">
                                    <img src="{{ url('/public/img/icons/wallet-icon.svg') }}" alt="Change Password">
                                    <span>Change Password</span>
                                </a>
                            </li>--}}
                            <li>
                                <a href="javascript: void (0)" onclick="return logout()">
                                    <img src="{{ url('/public/img/icons/wallet-icon.svg') }}" alt="Logout">
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dashboard Menu -->

    <!-- Page Content -->
    <div class="content court-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="court-tab-content">
                        <div class="card card-tableset">
                            <div class="card-body">
                                <div class="coache-head-blk">
                                    <div class="row align-items-center">
                                        <div class="col-lg-5">
                                            <div class="court-table-head">
                                                <h4>Transaction</h4>
                                                {{--<p>Reserve courts, buy equipment, and pay coaching fees with just a few taps.</p>--}}
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="table-search-top invoice-search-top">
                                                <div id="tablefilter"></div>
                                                {{--<div class="sortby-section invoice-sort">
                                                    <div class="sorting-info">
                                                        <div class="sortby-filter-group court-sortby">
                                                            <div class="sortbyset week-bg me-0">
                                                                <div class="sorting-select">
                                                                    <select class="form-control select">
                                                                        <option>This Week</option>
                                                                        <option>One Day</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="request-coach-list select-filter">
                                                    <div class="sortby-filter-group court-sortby">
                                                        <div class="sortbyset m-0">
                                                            <div class="sorting-select">
                                                                <select class="form-control select">
                                                                    <option>All Transactions</option>
                                                                    <option>One Month</option>
                                                                    <option>Two Month</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive table-datatble">
                                    <table class="table datatable">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Ref ID</th>
                                            <th>Transaction for</th>
                                            <th>Booking Date & Time</th>
                                            <th>Payment Details</th>
                                            <th>Payment</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($orderDetail && count($orderDetail) > 0) @foreach($orderDetail as $order)
                                            <tr>
                                                @php
                                                    date_default_timezone_set('Asia/Kolkata');
                                                    $loungeDetail = App\Models\Lounge::where('lounge_id', $order->lounge_id)->first();
                                                    $paymentDetail = App\Models\Payment::where('payment_id', $order->payment_id)->first();
                                                    $cartDetail = App\Models\Cart::where('cart_id', $order->cart_id)->first();
                                                    $cartlogDetail = App\Models\CartLog::where('cart_id', $order->cart_id)->first();
                                                    // Convert to DateTime
                                                    $customerInput = $cartDetail->cart_start_date . ' ' . $cartDetail->cart_start_time;
                                                    // One hour minus customerInput
                                                    $minusOneHour = date('Y-m-d H:i:s', strtotime($customerInput . ' -1 hour'));
                                                @endphp
                                                <td><a href="javascript: void (0)" class="text-primary">#{{ $order->order_unique_id }}</a></td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        @if($loungeDetail->lounge_image!='' && file_exists(public_path('/uploads/lounge/'.$loungeDetail->lounge_image)))
                                                            <a href="javascript: void (0)" class="avatar avatar-sm flex-shrink-0">
                                                                <img class="avatar-img" src="{{ asset('/uploads/lounge/'.$loungeDetail->lounge_image) }}" alt="{{ $order->customer_name }}">
                                                            </a>
                                                        @else
                                                            <a href="javascript: void (0)" class="avatar avatar-sm flex-shrink-0">
                                                                <img class="avatar-img" src="{{ url('/public/img/profiles/avatar-01.jpg') }}" alt="{{ $order->customer_name }}">
                                                            </a>
                                                        @endif
                                                        <span class="table-head-name flex-grow-1">
                                                            <a href="javascript: void (0)">{{ $loungeDetail->lounge_name }}</a>
                                                        </span>
                                                    </h2>
                                                </td>
                                                <td class="table-date-time">
                                                    @if($order->order_status == '1' && $cartDetail->cart_reschedule == '1')
                                                        @if(isset($cartDetail))
                                                            <h4><strike>
                                                                    {{ date('d, M Y', strtotime($cartlogDetail->clog_start_date)) }}
                                                                    <span>
                                                                        {{ str_replace(',', ', ', $cartlogDetail->clog_duration) }}
                                                                    </span>
                                                                    <span>
                                                                        {{ $cartDetail->cart_adults }}  Adults, {{ $cartDetail->cart_children }} Children
                                                                    </span>
                                                                </strike>
                                                            </h4>
                                                            <h4>
                                                                {{ date('d, M Y', strtotime($cartDetail->cart_start_date)) }}
                                                                <span>
                                                                    {{ str_replace(',', ', ', $cartDetail->cart_duration) }}
                                                                </span>
                                                                <span>
                                                                    {{ $cartDetail->cart_adults }}  Adults, {{ $cartDetail->cart_children }} Children
                                                                </span>
                                                            </h4>
                                                        @else
                                                            --
                                                        @endif
                                                    @else
                                                        @if(isset($cartDetail))
                                                            <h4>{{ date('d, M Y', strtotime($cartDetail->cart_start_date)) }}
                                                                <span>
                                                                    {{ str_replace(',', ', ', $cartDetail->cart_duration) }}
                                                                </span>
                                                                <span>
                                                                    {{ $cartDetail->cart_adults }}  Adults, {{ $cartDetail->cart_children }} Children
                                                                </span>
                                                            </h4>
                                                        @else
                                                            --
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <b>Total Amount: </b>₹ {{ $order->order_paid_price + $order->discount_price + $order->membership_discount }}<br>
                                                    <b>Paid Amount: </b>₹ {{ $order->order_paid_price }}<br>
                                                    @if(!empty($order->discount_code))
                                                        <b>Discount Amount: </b>₹ {{ $order->discount_price }} ({{ $order->discount_code }})<br>
                                                        {{--<b>Discount Code: </b>{{ $order->discount_code }}<br>--}}
                                                    @endif
                                                    @if(!empty($order->membership_discount))
                                                        <b>Membership Discount: </b>₹ {{ $order->membership_discount }}<br>
                                                        {{--<b>Discount Code: </b>{{ $order->discount_code }}<br>--}}
                                                    @endif
                                                    <b>Order Date: </b>{{ date('d-m-Y',strtotime($order->created_at)) }} {{ date('h:i A',strtotime($order->created_at)) }}<br>
                                                    <b>Payment Method: </b>{{ $order->order_type }}<br>
                                                    @if(!empty($paymentDetail))
                                                        <b>Payment Mode: </b>{{ $paymentDetail->PAYMENTMODE }}<br>
                                                        <b>Trnsaction#: </b>{{ $paymentDetail->TXNID }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($order->order_status == '0')
                                                        <span class="badge bg-info"><i class="feather-check-square me-1"></i>Pending</span>
                                                    @elseif($order->order_status == '1')
                                                        <span class="badge bg-success"><i class="feather-check-square me-1"></i>Completed</span>
                                                    @elseif($order->order_status == '2')
                                                        <span class="badge bg-danger"><i class="feather-check-square me-1"></i>Failed</span>
                                                    @endif

                                                    @if($order->order_status == '1' && $cartDetail->cart_reschedule == '1')
                                                        <br><br><span class="badge bg-warning" style="background-color: #ffc107 !important; color: #ffffff;"><i class="feather-check-square me-1"></i>Rescheduled</span>
                                                    @endif
                                                </td>
                                                <td data-order="{{ strtotime($order->created_at) }}">
                                                    {{ date('d-m-Y h:i:s A', strtotime($order->created_at)) }}
                                                </td>
                                                <td class="text-center">
                                                    @if($order->order_status == '1')
                                                        @if ($cartDetail->cart_reschedule == '1')
                                                            <span class="dropdown-item text-danger">Already Rescheduled</span>
                                                        @elseif(strtotime($minusOneHour) > time())
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="load_popup('{{ $order->order_id }}', '{{ date('d-m-Y', strtotime($cartDetail->cart_start_date)) }}', '{{ $cartDetail->cart_duration }}', '{{ $loungeDetail->lounge_max_person }}', '{{ $cartDetail->cart_adults }}');" ><i class="feather-edit"></i> Reschedule</a>
                                                        @else
                                                            <span class="dropdown-item text-danger">Reschedule not allowed within 1 hour.</span>
                                                        @endif
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="tablelength"></div>
                                </div>
                                <div class="col-md-6">
                                    <div id="tablepage"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Content -->
@endsection

@section('page-js')
    <script type="text/javascript">
        function load_popup(order_id, start_date, start_time, max_person, adults) {
            $('#order_id').val(order_id);
            $('#start_date').val(start_date);
            //$('#start_time').val(start_time);
            $('#adults').val(adults);
            $('#adults').attr('max', max_person);
            $('#maxguest').html(max_person);

            $('#edit-modal').modal("show");
        }

        function loadTimePopup() {
            let start_date = $('#start_date').val();
            let order_id = $('#order_id').val();
            $.ajax({
                type: 'POST',
                url  : '{{ route("load-time-slot") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    start_date,
                    order_id
                },
                success: function(response){
                    // Inject the HTML into the div with ID timeSlotsContainer
                    $('#timeSlotsContainer').html(response.html);
                    $('#timeSlotModal').modal('show');  // Show the modal after loading content

                    // 🔥 Force this modal on top of Edit Order
                    $('#timeSlotModal').css('z-index', 1060);
                    $('.modal-backdrop').last().css('z-index', 1059);
                    $('#start_time').val('');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        $(document).ready(function () {
            $('#start_date').on('click', function() {
                $('#start_time').val(''); // Clear the Start Time field
            });

            // Toggle button active state
            $(document).on('click', '.select-time-btn:not(.disabled)', function () {
                $(this).toggleClass('active');
            });

            // Handle submit button
            $('#submitTimes').on('click', function () {
                const selectedTimes = $('.select-time-btn.active').map(function () {
                    return $(this).data('time');
                }).get(); // Get array of selected times

                // Save to hidden input (comma-separated string)
                $('#start_time').val(selectedTimes.join(','));

                // Optional: Log
                console.log("Selected Times:", selectedTimes);

                // Close the modal using Bootstrap 5's modal API
                const modal = bootstrap.Modal.getInstance(document.getElementById('timeSlotModal'));
                modal.hide();
            });
        });

        $(document).on('click', '.inc1.disabled, .dec1.disabled', function (e) {
            e.preventDefault(); // Prevent any action
            return false;
        });

        $(".inc1").on('click', function() {
            updateValue(this, 1);
        });

        $(".dec1").on('click', function() {
            updateValue(this, -1);
        });

        function updateValue(obj, delta) {
            var item = $(obj).parent().find("input");
            var newValue = parseInt(item.val(), 10) + delta;
            item.val(Math.max(newValue, 0));
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Plus button
            document.querySelector('.inc').addEventListener('click', function () {
                const input = document.getElementById('adults');
                const maxGuests = parseInt($(input).attr('max')); // get fresh max each time
                let currentVal = parseInt(input.value) || 0;

                if (currentVal < maxGuests) {
                    input.value = currentVal + 1;
                }
            });

            // Minus button
            document.querySelector('.dec').addEventListener('click', function () {
                const input = document.getElementById('adults');
                let currentVal = parseInt(input.value) || 0;

                if (currentVal > 0) {
                    input.value = currentVal - 1;
                }
            });
        });

        function validate_booking() {
            $('#bookingBtn').prop('disabled', true);
            var form    = $('#bookingFrm')[0];
            var formData= new FormData(form);
            $("#bookingFrm").find(".has-error").removeClass("has-error");
            $("#start_date, #start_time, #adults").removeClass("is-invalid");
            $("#msg_start_date, #msg_start_time, #msg_adults").html('');
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url  : '{{ route("booking-lounge-insert") }}',
                cache : false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data : formData,
                success: function(response){
                    //alert(response.redirect_url)
                    if(response.status == "validation-error")
                    {
                        $('#bookingBtn').prop('disabled', false);
                        $.each(response.data, function (key, value)
                        {
                            // Add 'is-invalid' to the input
                            $("#" + key).addClass("is-invalid");

                            // Show the error message
                            $("#msg_" + key).html('<div class="invalid-feedback d-block">' + value + '</div>');
                        });
                    } else if (response.redirect_url !== undefined) {
                        window.location = '/my-booking';
                    }
                },
            });
        }
    </script>
@endsection