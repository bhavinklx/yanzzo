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
                <li class="active"><h5><a href="javascript: void (0)"><span>1</span>Book a Lounge</a></h5></li>
                {{--<li><h5><a href="javascript: void (0)"><span>2</span>Order Confirmation</a></h5></li>--}}
                <li><h5><a href="javascript: void (0)"><span>2</span>Payment</a></h5></li>
            </ul>
        </div>
    </section>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <section>
                <div class="text-center mb-40">
                    <h3 class="mb-1">Book A Lounge</h3>
                    <p class="sub-title mb-0">Hassle-free lounge bookings.</p>
                </div>
                <div class="row checkout">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-7">
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
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-5">
                        <section class="card booking-form booking-date">
                            <h3 class="border-bottom">Booking Form</h3>
                            <form method="post" id="bookingFrm" action="{{ route("booking-lounge-insert") }}">
                                <input type="hidden" id="customer_id" name="customer_id" value="{{ Session::get('customer_id'); }}">
                                <input type="hidden" id="lounge_id" name="lounge_id" value="{{ $loungeDetail->lounge_id }}">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6">
                                        <label for="start_date" class="form-label">Date</label>
                                        <div class="form-icon">
                                            <input type="text" class="form-control datetimepicker" placeholder="Select Date" id="start_date" name="start_date">
                                            <span class="cus-icon">
										<i class="feather-calendar icon-date"></i>
									</span>
                                        </div>
                                        <div id="msg_start_date"></div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6">
                                        <label for="start_time" class="form-label">Start Time</label>
                                        <div class='form-icon'>
                                            <input type="text" class="form-control start_date" placeholder="Select Start Time" id="start_time" name="start_time" readonly {{--data-bs-toggle="modal" data-bs-target="#timeSlotModal"--}} onclick="loadTimePopup();">
                                            <span class="cus-icon"><i class="feather-clock icon-time"></i></span>
                                        </div>
                                        <div id="msg_start_time"></div>
                                    </div>
                                </div>

                                {{--<div class="select-guest mb-3">
                                    <h6>Start Time</h6>
                                    <div class="qty-item text-center">
                                        <a href="javascript:void(0);" class="dec1 d-flex justify-content-center align-items-center disabled" style="position: absolute; top: 22px; left: 20px;"><i class="feather-minus-circle"></i></a>
                                        <input type="number" class="form-control text-center" id="duration" name="duration" value="0" readonly>
                                        <a href="javascript:void(0);" class="inc1 d-flex justify-content-center align-items-center disabled" style="position: absolute; top: 22px; right: 20px;"><i class="feather-plus-circle"></i></a>
                                    </div>
                                    <div id="msg_start_time"></div>
                                </div>--}}

                                <div class="select-guest">
                                    <h6>Select Guest</h6><span class="primary-text"> ({{ $loungeDetail->lounge_max_person }} guests maximum)</span>
                                    <div class="d-md-flex justify-content-between align-items-center">
                                        <div class="qty-item text-center">
                                            <a href="javascript:void(0);" class="dec d-flex justify-content-center align-items-center"><i class="feather-minus-circle"></i></a>
                                            <input type="number" class="form-control text-center" id="adults" name="adults" value="0" max="{{ $loungeDetail->lounge_max_person }}" readonly>
                                            <a href="javascript:void(0);" class="inc d-flex justify-content-center align-items-center"><i class="feather-plus-circle"></i></a>
                                            <label for="adults">
                                                <span class="dark-text">Adults</span>
                                                <span class="dull-text">Ages 12 and up</span>
                                            </label>
                                            <div id="msg_adults"></div>
                                        </div>

                                        <div class="qty-item text-center">
                                            <a href="javascript:void(0);" class="dec1 d-flex justify-content-center align-items-center" style="position: absolute; top: 22px; left: 20px;"><i class="feather-minus-circle"></i></a>
                                            <input type="number" class="form-control text-center" id="children" name="children" value="0" max="5" readonly>
                                            <a href="javascript:void(0);" class="inc1 d-flex justify-content-center align-items-center"  style="position: absolute; top: 22px; right: 20px;"><i class="feather-plus-circle"></i></a>
                                            <label for="children">
                                                <span class="dark-text">Children</span>
                                                <span class="dull-text">Below 5 years</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>

                <div class="text-center btn-row">
                    <a class="btn btn-primary me-3 btn-icon" href="javascript:void(0);"><i class="feather-arrow-left-circle me-1"></i> Back</a>
                    <a class="btn btn-secondary btn-icon" href="javascript:void(0);" id="bookingBtn" onclick="return validate_booking()">Next <i class="feather-arrow-right-circle ms-1"></i></a>
                </div>
            </section>
        </div>
        <!-- Container -->
    </div>
    <!-- /Page Content -->
@endsection

@section('page-js')
    <script type="text/javascript">
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

        /*function updateValue(obj, delta) {
            var item = $(obj).parent().find("input");
            var newValue = parseInt(item.val(), 10) + delta;
            item.val(Math.max(newValue, 0));
        }*/

        function updateValue(obj, delta) {
            var item = $(obj).parent().find("input");
            var currentValue = parseInt(item.val(), 10) || 0;
            var maxValue = parseInt(item.attr("max")) || 5; // read from input[max]
            var newValue = currentValue + delta;

            // Limit between 0 and max
            if (newValue >= 0 && newValue <= maxValue) {
                item.val(newValue);
            }
        }

        /*document.addEventListener('DOMContentLoaded', function () {
            const maxGuests = parseInt('{{ $loungeDetail->lounge_max_person }}');

            // Plus button
            document.querySelector('.inc').addEventListener('click', function () {
                const input = document.getElementById('adults');
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
        });*/

        document.addEventListener('DOMContentLoaded', function () {
            const maxGuests = parseInt('{{ $loungeDetail->lounge_max_person }}');

            // Plus button
            document.querySelector('.inc').addEventListener('click', function () {
                const input = document.getElementById('adults');
                let currentVal = parseInt(input.value) || 0;

                if (currentVal < maxGuests) {
                    input.value = currentVal + 1;
                } else {
                    // Optional: Show a small warning message
                    document.getElementById('msg_adults').innerHTML =
                            `<small class="text-danger">Maximum ${maxGuests} adults allowed</small>`;
                }
            });

            // Minus button
            document.querySelector('.dec').addEventListener('click', function () {
                const input = document.getElementById('adults');
                let currentVal = parseInt(input.value) || 0;

                if (currentVal > 0) {
                    input.value = currentVal - 1;
                    document.getElementById('msg_adults').innerHTML = ''; // clear message
                }
            });
        });

        function loadTimePopup() {
            let lounge_id = $('#lounge_id').val();
            let start_date = $('#start_date').val();
            $.ajax({
                type: 'POST',
                url  : '{{ route("load-time-slot") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    lounge_id,
                    start_date
                },
                success: function(response){
                    // Inject the HTML into the div with ID timeSlotsContainer
                    $('#timeSlotsContainer').html(response.html);
                    $('#timeSlotModal').modal('show');  // Show the modal after loading content
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        // Global event listener for dynamically loaded buttons
        /*$(document).on('click', '.select-time-btn', function () {
            const selectedTime = $(this).data('time');

            // Set the selected time in the input field
            $('#start_time').val(selectedTime);

            // Optional: highlight selected time
            $('.select-time-btn').removeClass('active');
            $(this).addClass('active');
            // Set duration to 1
            $('#duration').val(1);
            // Enable duration buttons
            $('.inc1, .dec1').removeClass('disabled').css('opacity', '1').css('pointer-events', 'auto');

            // Close the modal
            $('#timeSlotModal').modal('hide');
        });*/

        $(document).ready(function () {
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
                        window.location = response.redirect_url;
                    }
                },
            });
        }
    </script>
@endsection