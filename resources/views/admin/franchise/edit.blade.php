@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Franchise</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Franchise</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab" role="tablist" id="myTab">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#franchise_info" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Basic Information</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#franchise_legal" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Legal Details</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#franchise_lounge" role="tab"><span class="hidden-sm-up"><i class="ti-cup"></i></span> <span class="hidden-xs-down">Lounge Details</span></a> </li>
                    </ul>
                    <!-- Tab panes -->

                    <form class="floating-labels" id="franchiseFrm" method="post" action="{{ route("franchise-update") }}">
                        <input type="hidden" name="franchise_id" value="{{ $franchiseDetail->franchise_id }}">
                        {{ csrf_field() }}

                        <div class="tab-content tabcontent-border p-10">
                            <!--Restaurant Basic Info-->
                            <div class="tab-pane active" id="franchise_info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <div class="row m-t-20">
                                                <div class="form-group col-md-6 m-b-40">
                                                    <input type="text" class="form-control" id="franchise_company_name" name="franchise_company_name" value="{{ $franchiseDetail->franchise_company_name }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_company_name">Company Name</label>
                                                    <span class="help-block"><small id="msg_franchise_company_name" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-6 m-b-40 m-t-5">
                                                    <input type="text" class="form-control" name="franchise_owner_name" id="franchise_owner_name" value="{{ $franchiseDetail->franchise_owner_name }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_owner_name">Contact Person</label>
                                                    <span class="help-block"><small id="msg_franchise_owner_name" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control" name="franchise_email" id="franchise_email" value="{{ $franchiseDetail->franchise_email }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_email">Email</label>
                                                    <span class="help-block"><small id="msg_franchise_email" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_mobile1" id="franchise_mobile1" maxlength="10" value="{{ $franchiseDetail->franchise_mobile1 }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_mobile1">Mobile 1</label>
                                                    <span class="help-block"><small id="msg_franchise_mobile1" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_mobile2" id="franchise_mobile2" maxlength="10" value="{{ $franchiseDetail->franchise_mobile2 }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_mobile2">Mobile 2</label>
                                                    <span class="help-block"><small id="msg_franchise_mobile2" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_mobile3" id="franchise_mobile3" maxlength="10" value="{{ $franchiseDetail->franchise_mobile3 }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_mobile3">Mobile 3</label>
                                                    <span class="help-block"><small id="msg_franchise_mobile3" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-12 m-b-40">
                                                    <textarea class="form-control" name="franchise_address" id="franchise_address">{{ $franchiseDetail->franchise_address }}</textarea>
                                                    <span class="bar"></span>
                                                    <label for="franchise_address">Office Address</label>
                                                    <span class="help-block"><small id="msg_franchise_address" class="text-danger"></small></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="franchise_legal" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <div class="row m-t-20">
                                                <div class="form-group col-md-6 m-b-40">
                                                    <input class="form-control" name="franchise_pan" id="franchise_pan" maxlength="10" value="{{ $franchiseDetail->franchise_pan }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_pan">PAN Card Number</label>
                                                    <span class="help-block"><small id="msg_franchise_pan" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control" name="franchise_gst" id="franchise_gst" value="{{ $franchiseDetail->franchise_gst }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_gst">GST Number</label>
                                                    <span class="help-block"><small id="msg_franchise_gst" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_gst_percentage" id="franchise_gst_percentage" value="{{ $franchiseDetail->franchise_gst_percentage }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_gst_percentage">GST Percentage</label>
                                                    <span class="help-block"><small id="msg_franchise_gst_percentage" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input class="form-control isNumber" name="franchise_bank_ac" id="franchise_bank_ac" value="{{ $franchiseDetail->franchise_bank_ac }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_bank_ac">Bank A/c Number</label>
                                                    <span class="help-block"><small id="msg_franchise_bank_ac" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input class="form-control" name="franchise_bank_name" id="franchise_bank_name" value="{{ $franchiseDetail->franchise_bank_name }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_bank_name">Bank Name</label>
                                                    <span class="help-block"><small id="msg_franchise_bank_name" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input class="form-control" name="franchise_bank_ifsc" id="franchise_bank_ifsc" value="{{ $franchiseDetail->franchise_bank_ifsc }}">
                                                    <span class="bar"></span>
                                                    <label for="franchise_bank_ifsc">Bank IFSC</label>
                                                    <span class="help-block"><small id="msg_franchise_bank_ifsc" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-t-5">
                                                    <select class="form-control p-0" name="franchise_bank_type" id="franchise_bank_type">
                                                        <option value="0">Select a Type</option>
                                                        <option value="saving" {{ ($franchiseDetail->franchise_bank_type == "saving") ? 'selected' : '' }} >Saving</option>
                                                        <option value="current" {{ ($franchiseDetail->franchise_bank_type == "current") ? 'selected' : '' }}>Current</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_bank_type">Account Type</label>
                                                    <span class="help-block"><small id="msg_franchise_bank_type" class="text-danger"></small></span>
                                                </div>

                                                {{--<div class="form-group col-md-3 m-b-40 focused" id="f_unit">
                                                    <select class="form-control p-0"  name="franchise_unit" id="franchise_unit">
                                                        <option value="">Select a Unit Type</option>
                                                        <option value="1 Box">1 Box</option>
                                                        <option value="2 Box">2 Box</option>
                                                        <option value="3 Box">3 Box</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_unit">Lounge Unit Type</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 focused" id="f_category">
                                                    <select class="form-control p-0"  name="franchise_category" id="franchise_category">
                                                        <option value="">Select a Box Category</option>
                                                        <option value="Classic">Classic</option>
                                                        <option value="Deluxe">Deluxe</option>
                                                        <option value="Premium">Premium</option>
                                                        <option value="Platinum">Platinum</option>
                                                        <option value="Suite">Suite</option>
                                                        <option value="VVIP">VVIP</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_category">Karaoke Box Category</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 focused">
                                                    <select class="form-control p-0"  name="franchise_ownership" id="franchise_ownership">
                                                        <option value="0">Select a Type</option>
                                                        <option value="Owned">Owned</option>
                                                        <option value="Rented">Rented</option>
                                                        <option value="Lease">Lease</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_ownership">Ownership Type</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_property_size" id="franchise_property_size" maxlength="10">
                                                    <span class="bar"></span>
                                                    <label for="franchise_property_size">Property Size (in sq.ft)</label>
                                                    <span class="help-block"><small id="msg_franchise_property_size" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-6 m-b-40">
                                                    <input class="form-control" name="franchise_google_map" id="franchise_google_map">
                                                    <span class="bar"></span>
                                                    <label for="franchise_google_map">Google Map Location</label>
                                                    <span class="help-block"><small id="msg_franchise_google_map" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_franchise_fee" id="franchise_franchise_fee">
                                                    <span class="bar"></span>
                                                    <label for="franchise_franchise_fee">Franchise Fee (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_franchise_fee" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_setup_cost" id="franchise_setup_cost">
                                                    <span class="bar"></span>
                                                    <label for="franchise_setup_cost">Setup Cost (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_setup_cost" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_renewal_fee" id="franchise_renewal_fee">
                                                    <span class="bar"></span>
                                                    <label for="franchise_renewal_fee">Renewal Fee (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_renewal_fee" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 m-t-5">
                                                    <select class="form-control p-0"  name="franchise_gst_invoice" id="franchise_gst_invoice">
                                                        <option value="0">Select a GST Invoice</option>
                                                        <option value="no">No</option>
                                                        <option value="yes">Yes</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_gst_invoice">GST Invoice</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_discount" id="franchise_discount">
                                                    <span class="bar"></span>
                                                    <label for="franchise_discount">Discount (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_discount" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 m-t-5">
                                                    <select class="form-control p-0"  name="franchise_payment_mode" id="franchise_payment_mode">
                                                        <option value="0">Select a Payment Mode</option>
                                                        <option value="UPI">UPI</option>
                                                        <option value="NEFT">NEFT</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_payment_mode">Payment Mode</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 m-t-5">
                                                    <select class="form-control p-0"  name="franchise_payment_status" id="franchise_payment_status">
                                                        <option value="0">Select a Payment Status</option>
                                                        <option value="Paid">Paid</option>
                                                        <option value="Partially Paid">Partially Paid</option>
                                                        <option value="Pending">Pending</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_payment_status">Payment Status</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 dob">
                                                    <input class="form-control mdate" name="franchise_payment_date" id="franchise_payment_date">
                                                    <span class="bar"></span>
                                                    <label for="franchise_payment_date">Payment Date</label>
                                                    <span class="help-block"><small id="msg_franchise_payment_date" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control" name="franchise_weekly_off" id="franchise_weekly_off">
                                                    <span class="bar"></span>
                                                    <label for="franchise_weekly_off">Weekly Off / Holiday</label>
                                                    <span class="help-block"><small id="msg_franchise_weekly_off" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control" name="franchise_minimum_time" id="franchise_minimum_time">
                                                    <span class="bar"></span>
                                                    <label for="franchise_minimum_time">Minimum Booking Time (in minutes)</label>
                                                    <span class="help-block"><small id="msg_franchise_minimum_time" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control" name="franchise_rescheduling_before" id="franchise_rescheduling_before">
                                                    <span class="bar"></span>
                                                    <label for="franchise_rescheduling_before">Rescheduling Allowed Before (in hours)</label>
                                                    <span class="help-block"><small id="msg_franchise_rescheduling_before" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_cancellation_charge" id="franchise_cancellation_charge">
                                                    <span class="bar"></span>
                                                    <label for="franchise_cancellation_charge">Cancellation Charges (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_cancellation_charge" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control" name="franchise_refund_policy" id="franchise_refund_policy">
                                                    <span class="bar"></span>
                                                    <label for="franchise_refund_policy">Refund Policy</label>
                                                    <span class="help-block"><small id="msg_franchise_refund_policy" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_overtime_charge" id="franchise_overtime_charge">
                                                    <span class="bar"></span>
                                                    <label for="franchise_overtime_charge">Overtime Charges per Hour (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_overtime_charge" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_charge_hour" id="franchise_charge_hour">
                                                    <span class="bar"></span>
                                                    <label for="franchise_charge_hour">Charges Per Hour (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_charge_hour" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_charge_second_hour" id="franchise_charge_second_hour">
                                                    <span class="bar"></span>
                                                    <label for="franchise_charge_second_hour">Charges Per Second Hour (₹)</label>
                                                    <span class="help-block"><small id="msg_franchise_charge_second_hour" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 m-t-5">
                                                    <select class="form-control p-0"  name="franchise_washroom_attached" id="franchise_washroom_attached">
                                                        <option value="0">Select a Washroom</option>
                                                        <option value="no">No</option>
                                                        <option value="yes">Yes</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_washroom_attached">Washroom Attached</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 dob">
                                                    <input class="form-control mdate" name="franchise_installation_date" id="franchise_installation_date">
                                                    <span class="bar"></span>
                                                    <label for="franchise_installation_date">Installation Date</label>
                                                    <span class="help-block"><small id="msg_franchise_installation_date" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 m-t-5">
                                                    <select class="form-control p-0"  name="franchise_franchise_status" id="franchise_franchise_status">
                                                        <option value="0">Select a Status</option>
                                                        <option value="1">Agreement Signed</option>
                                                        <option value="2">Site Ready</option>
                                                        <option value="3">Live</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_franchise_status">Franchise Status</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 dob">
                                                    <input class="form-control mdate" name="franchise_agreement_start_date" id="franchise_agreement_start_date">
                                                    <span class="bar"></span>
                                                    <label for="franchise_agreement_start_date">Agreement Start Date</label>
                                                    <span class="help-block"><small id="msg_franchise_agreement_start_date" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 dob">
                                                    <input class="form-control mdate" name="franchise_agreement_end_date" id="franchise_agreement_end_date">
                                                    <span class="bar"></span>
                                                    <label for="franchise_agreement_end_date">Agreement End Date</label>
                                                    <span class="help-block"><small id="msg_franchise_agreement_end_date" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control isNumber" name="franchise_validity_period" id="franchise_validity_period">
                                                    <span class="bar"></span>
                                                    <label for="franchise_validity_period">Validity Period (Months)</label>
                                                    <span class="help-block"><small id="msg_franchise_validity_period" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40">
                                                    <input class="form-control" name="franchise_assigned_technician" id="franchise_assigned_technician">
                                                    <span class="bar"></span>
                                                    <label for="franchise_assigned_technician">Assigned Technician</label>
                                                    <span class="help-block"><small id="msg_franchise_assigned_technician" class="text-danger"></small></span>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 m-t-5">
                                                    <select class="form-control p-0" name="franchise_photo_upload" id="franchise_photo_upload">
                                                        <option value="0">Select a Photos</option>
                                                        <option value="no">No</option>
                                                        <option value="yes">Yes</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_photo_upload">Photos Uploaded</label>
                                                </div>

                                                <div class="form-group col-md-3 m-b-40 m-t-5">
                                                    <select class="form-control p-0" name="franchise_franchise_store" id="franchise_franchise_store">
                                                        <option value="0">Select a Photos</option>
                                                        <option value="no">Not Visible</option>
                                                        <option value="yes">Visible</option>
                                                    </select>
                                                    <span class="bar"></span>
                                                    <label for="franchise_franchise_store">Franchise Store</label>
                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="franchise_lounge" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <div class="row m-t-20">
                                                <label class="m-l-10">Lounge</label>
                                                <div class="form-group col-md-12 m-t-5">
                                                    <select class="select2 form-control p-0 select2-multiple" name="lounge_id[]" id="lounge_id" multiple="multiple" style="width: 100% !important;">
                                                        <option value="0">Select a Lounge</option>
                                                        @foreach($loungeDetail as $lounge)
                                                            @if(!in_array($lounge->lounge_id, $alreadyAddedIds))
                                                                <option value="{{ $lounge->lounge_id }}">{{ $lounge->lounge_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block"><small id="msg_lounge_id" class="text-danger"></small></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions p-b-10 text-center">
                                <button type="submit" name="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="listFrm" name="listFrm" action="" method="post">
                                <input type="hidden" id="action" name="action" value="">
                                <div class="table-responsive ">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>SR No.</th>
                                            <th>Title</th>
                                            <th>Agreement Start Date</th>
                                            <th>Agreement End Date</th>
                                            <th>Plateform Fee</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $key=1;
                                        @endphp
                                        @foreach($loungeDetail as $lounge)
                                            @if(in_array($lounge->lounge_id, $alreadyAddedIds))
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $lounge->lounge_name }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($lounge->lounge_agreement_start_date)) }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($lounge->lounge_agreement_end_date)) }}</td>
                                                    <td>{{ $lounge->lounge_plateform_fee }}%</td>
                                                    <td>{{ date('d-m-Y h:i A', strtotime($lounge->created_at)) }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal('{{ $lounge->lounge_id }}');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>
                                                        <a href="{{ url('/book-lounge/' . $lounge->lounge_slug) }}" data-toggle="tooltip" title="View Lounge" class="img-responsive model_img" target="_blank"> <i class="fa fa-eye text-info"></i> </a>
                                                    </td>
                                                </tr>
                                                @php
                                                    $key++
                                                @endphp
                                            @endif
                                        @endforeach
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
@endsection

@section('page-js')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $(".dropify-wrapper").css("width", "100%");
                $('#f_unit, #f_category').addClass('focused');
            }, 100);

            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });

        $("#franchiseFrm").on('submit', function(e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form = $('#franchiseFrm')[0];
            var formData = new FormData(form);
            $("#franchiseFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $(".text-danger").html("");
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                cache: false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    //alert(response.redirect_url)
                    if (response.status == "validation-error") {
                        $.each(response.data, function(key, value) {
                            $("#" + key).parent("div").addClass("has-error");
                            //$("#"+key).next().html("<small class='text-danger'>" + value + "</small>");
                            $("#msg_" + key).html(value);
                        });
                    } else if (response.redirect_url !== undefined) {
                        window.location = "{{ url('admin/franchise-list') }}";
                    }
                }
            });
        });

        function deleteData(lounge_id) {
            $.ajax({
                url: "{{ route('franchise-lounge-delete') }}",
                type: "POST",
                data: {
                    franchise_id:'{{ $franchiseDetail->franchise_id }}',
                    lounge_id:lounge_id,
                    _token:'{{ csrf_token() }}'
                },
                success: function (response) {
                }
            });
        }
    </script>
@endsection
