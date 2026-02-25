@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Discount</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Discount</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="discountFrm" method="post" action="{{ route("discount-update") }}">
                <input type="hidden" name="discount_id" value="{{ $discountDetail->discount_id }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!--<h4 class="card-title m-b-40">Tab with dropdown</h4>-->
                                <div class="tab-content p-20" id="myTabContent">
                                    <div role="tabpanel" class="tab-pane fade show active" id="english" aria-labelledby="english-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row m-t-20">
                                                    <input type="hidden" name="discount_scenario_type" id="discount_scenario_type" value="1">
                                                    {{--<div class="form-group col-md-12 m-b-40" id="d_type">
                                                        <label>Discount Amount Type</label>
                                                        <select class="form-control p-0"  name="discount_scenario_type" id="discount_scenario_type" onchange="return enableTextBox()">
                                                            <option value="1" {{ ($discountDetail->discount_scenario_type == "1") ? 'selected="selected"' : '' }} >Basic</option>
                                                            <option value="2" {{ ($discountDetail->discount_scenario_type == "2") ? 'selected="selected"' : '' }} >Fix Amount</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <span class="help-block"><small id="msg_discount_scenario_type" class="text-danger"></small></span>
                                                    </div>--}}

                                                    <div class="form-group col-md-6 m-b-40" id="d_title">
                                                        <input type="text" class="form-control" name="discount_title" id="discount_title" value="{{ $discountDetail->discount_title }}">
                                                        <span class="bar"></span>
                                                        <label for="discount_title">Discount Title <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_discount_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="d_code">
                                                        <input type="text" class="form-control"  name="discount_code" id="discount_code" value="{{ $discountDetail->discount_code }}" onkeyup="this.value = this.value.toUpperCase();">
                                                        <span class="bar"></span>
                                                        <label for="discount_code">Discount Code <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_discount_code" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-t-5 m-b-40" id="d_atype">
                                                        <label>Discount Amount Type <span class="form-asterisk">*</span></label>
                                                        <select class="form-control p-0"  name="discount_type" id="discount_type">
                                                            <option value="0">Select</option>
                                                            <option value="percentage" {{ ($discountDetail->discount_type == "percentage") ? 'selected="selected"' : '' }} >Percentage</option>
                                                            <option value="cash" {{ ($discountDetail->discount_type == "cash") ? 'selected="selected"' : '' }} >Cash</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <span class="help-block"><small id="msg_discount_type" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="d_amount">
                                                        <input type="text" class="form-control" name="discount_amount" id="discount_amount" value="{{ $discountDetail->discount_amount }}" onkeypress="return isNumberKey(event, this)">
                                                        <span class="bar"></span>
                                                        <label for="engineer_nationality">Discount Amount <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_discount_amount" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40 dob" id="d_sdate" >
                                                        <input type="text" class="form-control sdate" id="discount_start_date" name="discount_start_date" value="{{ (isset($discountDetail->discount_start_date) && $discountDetail->discount_start_date!="0000-00-00") ? date("d-m-Y", strtotime($discountDetail->discount_start_date)) : ""; }}">
                                                        <span class="bar"></span>
                                                        <label for="discount_start_date">Discount Start Date <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_discount_start_date" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40" id="d_stime">
                                                        <input class="form-control" type="time" id="discount_start_time" name="discount_start_time" value="{{ ($discountDetail->discount_start_time!="") ? $discountDetail->discount_start_time : date('H:i', time()); }}">
                                                        <span class="bar"></span>
                                                        <label for="discount_start_time">Discount Start Time <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_discount_start_time" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-20 dob" id="d_edate" >
                                                        <input type="text" class="form-control sdate" id="discount_end_date" name="discount_end_date" value="{{ (isset($discountDetail->discount_end_date) && $discountDetail->discount_end_date!="0000-00-00") ? date("d-m-Y", strtotime($discountDetail->discount_end_date)) : ""; }}">
                                                        <span class="bar"></span>
                                                        <label for="discount_end_date">Discount End Date <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_discount_end_date" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40" id="d_etime">
                                                        <input class="form-control" type="time" id="discount_end_time" name="discount_end_time" value="{{ ($discountDetail->discount_end_time!="") ? $discountDetail->discount_end_time : date('H:i', time()); }}">
                                                        <span class="bar"></span>
                                                        <label for="discount_end_time">Discount End Time <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_discount_end_time" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="d_minamount">
                                                        <input type="text" class="form-control isNumber" name="discount_min_amount" id="discount_min_amount" value="{{ $discountDetail->discount_min_amount }}">
                                                        <span class="bar"></span>
                                                        <label for="discount_min_amount">Discount Min Amount (Enter Min Amount For Order)</label>
                                                        <span class="help-block"><small id="msg_discount_min_order" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="d_maxamount">
                                                        <input type="text" class="form-control isNumber" name="discount_max_discount" id="discount_max_discount" value="{{ $discountDetail->discount_max_discount }}">
                                                        <span class="bar"></span>
                                                        <label for="discount_max_discount">Discount Max Amount For Discount</label>
                                                        <span class="help-block"><small id="msg_discount_max_discount" class="text-danger"></small></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions p-b-10 text-center">
                    <button type="submit" name="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                </div>
            </form>
            <!-- End PAge Content -->
        </div>
        <!-- End Container fluid  -->
    </div>
    <!-- End Page wrapper  -->
@endsection

@section('page-js')
    <script type="text/javascript">
        $(document).ready(function() {
            enableTextBox();
        });

        function enableTextBox() {
            var type = $('#discount_scenario_type').val();
            if (type != 1) {
                $('#d_minamount').show();
                $('#d_maxamount').show();
            } else {
                $('#d_minamount').hide();
                $('#d_maxamount').hide();
            }
        }

        function isNumberKey(evt, element) {
            var charCode = evt.which ? evt.which : evt.keyCode;

            // Allow digits (0–9)
            if (charCode >= 48 && charCode <= 57) {
                return true;
            }

            // Allow ONE dot (.)
            if (charCode === 46) {
                return element.value.indexOf('.') === -1;
            }

            // Block everything else
            return false;
        }

        $("#discountFrm").on('submit', function(e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form = $('#discountFrm')[0];
            var formData = new FormData(form);
            $("#discountFrm").find(".has-error").removeClass("has-error");
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
                        window.location = "{{ url('admin/discount-list') }}";
                    }
                }
            });
        });
    </script>
@endsection
