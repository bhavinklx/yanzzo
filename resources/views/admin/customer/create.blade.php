@extends("admin.layouts.app")
@section("content")
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Search Member</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Search Member</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->

            <!-- Start Page Content -->
            <form method="post" id="customerFrm" enctype="multipart/form-data" onsubmit="return false;">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body m-t-10">
                                <div class="row">
                                    {{--<div class="col-lg-12">
                                        <div class="alert alert-warning m-b-20"><i class="fa fa-exclamation-triangle"></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                    </div>--}}

                                    <div class="col-lg-2 p-l-20">
                                        <input type="text" id="customer_mobile" name="customer_mobile" class="form-control isNumber" maxlength="10" placeholder="Mobile Number">
                                    </div>

                                    <div class="col-lg-2">
                                        <button type="button" id="searchBtn" class="btn btn-block btn-primary" >search</button>
                                    </div>

                                    <div class="col-lg-2" style="display: none;">
                                        <button type="button" name="createBtn" class="btn btn-block btn-primary" >Create</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
            </form>

            <form class="floating-labels" method="post" id="bookingFrm" enctype="multipart/form-data" style="display: none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="row">
                                            <input type="hidden" id="customer_id" name="customer_id">
                                            <div class="form-group col-md-12 m-b-40 m-t-25">
                                                <select class="form-control p-0"  name="lounge_id" id="lounge_id">
                                                    <option value="0">Select a Lounge</option>
                                                    @foreach($loungeDetail as $lounge)
                                                        <option value="{{ $lounge->lounge_id }}">{{ $lounge->lounge_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="bar"></span>
                                                <label for="lounge_id">Lounge</label>
                                                <span class="help-block"><small id="msg_lounge_id" class="text-danger"></small></span>
                                            </div>

                                            <div class="form-group col-md-3 m-b-40 dob">
                                                <input type="text" class="form-control mdate" id="start_date" name="start_date">
                                                <span class="bar"></span>
                                                <label for="start_date">Date <span class="form-asterisk">*</span></label>
                                                <span class="help-block"><small id="msg_start_date" class="text-danger"></small></span>
                                            </div>

                                            <div class="form-group col-md-3 m-b-40" id="s_time">
                                                <input type="text" class="form-control" id="start_time" name="start_time" onclick="loadTimePopup();">
                                                <span class="bar"></span>
                                                <label for="start_time">Start Time <span class="form-asterisk">*</span></label>
                                                <span class="help-block"><small id="msg_start_time" class="text-danger"></small></span>
                                            </div>

                                            <div class="form-group col-md-3 m-b-40">
                                                <input type="text" class="form-control isNumber" id="adults" name="adults">
                                                <span class="bar"></span>
                                                <label for="adults">Adults <span class="form-asterisk">*</span></label>
                                                <span class="help-block">Min:2 Max:12</span>
                                                <span class="help-block"><small id="msg_adults" class="text-danger"></small></span>
                                            </div>

                                            <div class="form-group col-md-3 m-b-40">
                                                <input type="text" class="form-control isNumber" id="children" name="children">
                                                <span class="bar"></span>
                                                <label for="children">Children</label>
                                                <span class="help-block"><small id="msg_children" class="text-danger"></small></span>
                                            </div>

                                            <div class="form-group col-md-3 m-b-40">
                                                <select class="form-control p-0"  name="method" id="method">
                                                    <option value="0">Select payment method</option>
                                                    <option value="card">Credit / Debit Card</option>
                                                    <option value="upi">UPI</option>
                                                    <option value="netbanking">Net Banking</option>
                                                </select>
                                                <span class="bar"></span>
                                                <label for="method">Payment Mode</label>
                                                <span class="help-block"><small id="msg_method" class="text-danger"></small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions p-b-10 text-center">
                    <button type="button" id="bookingBtn" name="bookingBtn" class="btn btn-success" onclick="return validate_booking()"> <i class="fa fa-check"></i> Save</button>
                </div>
            </form>
            <!-- End Page Content -->

        </div>
        <!-- End Container fluid  -->
    </div>
    <!-- End Page wrapper  -->

    <!-- Modal -->
    <div class="modal fade" id="timeSlotModal" tabindex="-1" aria-labelledby="timeSlotModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content shadow rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="timeSlotModalLabel">Choose start time</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal">X</button>
                </div>
                <div class="modal-body">
                    <!-- Status indicators -->
                    <div class="status-info d-flex justify-content-around mb-3 text-center">
                        <div class="status-label already-booked">
                            <span></span> Already Booked
                        </div>
                        <div class="status-label under-maintenance">
                            <span></span> Under Maintenance
                        </div>
                        <div class="status-label available">
                            <span></span> Available
                        </div>
                    </div>
                    <hr class="m-t-0">
                    <div id="timeSlotsContainer" class="row g-2"></div>
                    <hr class="m-t-0">
                    <div class="text-center">
                        <button class="btn btn-primary" type="button" id="submitTimes">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script type="text/javascript">
        document.getElementById('closeModal').addEventListener('click', function () {
            $('#timeSlotModal').modal('hide');
        });

        $("#searchBtn").click(function(e) {
            var error = false;
            if ($('#customer_mobile').val() == '') {
                error = true;
                $('#customer_mobile').css('border', '1px solid red');
            } else {
                $('#customer_mobile').css('border', '');
            }

            if(error == false){
                var mobile  = $('#customer_mobile').val();
                setTimeout(function(){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        url  : "{{ route('search-customer') }}",
                        type : "POST",
                        data : {mobile:mobile},
                        success : function(response) {
                            if (response.message == 'success') {
                                $('#customerFrm').hide();
                                $('#bookingFrm').show();
                                $('#customer_id').val(response.customer_id);
                            } else if (response.message == 'wrong') {
                                $('#customerFrm').show();
                                $('#bookingFrm').hide();
                            }
                        }
                    });
                }, 100);
            }
        });

        function loadTimePopup() {
            let lounge_id = $('#lounge_id').val();
            let start_date = $('#start_date').val();
            $.ajax({
                type: 'POST',
                url  : '{{ route("customer-load-time-slot") }}',
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
                //const modal = bootstrap.Modal.getInstance(document.getElementById('timeSlotModal'));
                //modal.hide();
                $('#s_time').addClass('focused');
                $('#timeSlotModal').modal('hide');
            });
        });

        function validate_booking() {
            $('#bookingBtn').prop('disabled', true);
            var form    = $('#bookingFrm')[0];
            var formData= new FormData(form);
            $("#bookingFrm").find(".has-error").removeClass("has-error");
            $("#lounge_id, #start_date, #start_time, #adults, #method").removeClass("is-invalid");
            $("#msg_lounge_id, #msg_start_date, #msg_start_time, #msg_adults, #msg_method").html('');
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url  : '{{ route("customer-lounge-insert") }}',
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
                            $("#msg_" + key).html(value);
                        });
                    } else if (response.redirect_url !== undefined) {
                        window.location = "{{ url('admin/order-list/pending') }}";
                    }
                },
            });
        }
    </script>
@endsection