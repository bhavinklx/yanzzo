@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Lounge</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Lounge</li>
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
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#lounge_info" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Basic Information</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#lounge_time" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Opening / Closing Time</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#lounge_maintenance_time" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Maintenance Time</span></a> </li>
                    </ul>
                    <!-- Tab panes -->

                    <form class="floating-labels" id="loungeFrm" method="post" action="{{ route("lounge-insert") }}">
                        {{ csrf_field() }}

                        <div class="tab-content tabcontent-border p-10">
                            <!--Restaurant Basic Info-->
                            <div class="tab-pane active" id="lounge_info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body m-t-20">
                                                <div class="row">
                                                    <div class="form-group col-md-6 m-b-40">
                                                        <input type="text" class="form-control" name="lounge_name" id="lounge_name">
                                                        <span class="bar"></span>
                                                        <label for="lounge_name">Lounge Name <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_name" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <input type="text" class="form-control" id="lounge_slug" name="lounge_slug">
                                                        <span class="bar"></span>
                                                        <label for="lounge_slug">Lounge Slug <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_slug" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <input class="form-control" name="lounge_email" id="lounge_email">
                                                        <span class="bar"></span>
                                                        <label for="lounge_email">Email</label>
                                                        <span class="help-block"><small id="msg_lounge_email" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <input class="form-control isNumber" name="lounge_mobile" id="lounge_mobile" maxlength="10">
                                                        <span class="bar"></span>
                                                        <label for="lounge_mobile">Mobile</label>
                                                        <span class="help-block"><small id="msg_lounge_mobile" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <textarea class="form-control" name="lounge_short_desc" id="lounge_short_desc"></textarea>
                                                        <span class="bar"></span>
                                                        <label for="lounge_short_desc">Short Description</label>
                                                        <span class="help-block"><small id="msg_lounge_short_desc" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40 m-t-20">
                                                        <input class="form-control" name="lounge_meta_title" id="lounge_meta_title">
                                                        <span class="bar"></span>
                                                        <label for="lounge_meta_title">Meta Title</label>
                                                        <span class="help-block"><small id="msg_lounge_meta_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="col-md-6 m-b-20">
                                                        <label>Lounge Image</label><br><br>
                                                        <input type="file" class="dropify" data-default-file="" id="lounge_image" name="lounge_image" aria-describedby="fileHelp">
                                                        <span style="color: red;font-size: 12px;">Best size: (Width:420px X Height:260px)</span>
                                                    </div>

                                                    <div class="col-md-6 m-b-20">
                                                        <div class="form-group m-b-40">
                                                            <textarea class="form-control" name="lounge_meta_keyword" id="lounge_meta_keyword"></textarea>
                                                            <span class="bar"></span>
                                                            <label for="lounge_meta_keyword">Meta Keyword</label>
                                                            <span class="help-block"><small id="msg_lounge_meta_keyword" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40">
                                                            <textarea class="form-control" name="lounge_meta_desc" id="lounge_meta_desc"></textarea>
                                                            <span class="bar"></span>
                                                            <label for="lounge_meta_desc">Meta Description</label>
                                                            <span class="help-block"><small id="msg_lounge_meta_desc" class="text-danger"></small></span>
                                                        </div>

                                                        {{--<div class="form-group">
                                                            <input class="form-control" name="lounge_canonical" id="lounge_canonical">
                                                            <span class="bar"></span>
                                                            <label for="lounge_canonical">Canonical Url</label>
                                                            <span class="help-block"><small id="msg_lounge_canonical" class="text-danger"></small></span>
                                                        </div>--}}
                                                        <div class="form-group m-b-40">
                                                            <input class="form-control isNumber" name="lounge_max_person" id="lounge_max_person">
                                                            <span class="bar"></span>
                                                            <label for="lounge_max_person">Max Persons per Box <span class="form-asterisk">*</span></label>
                                                            <span class="help-block"><small id="msg_lounge_max_person" class="text-danger"></small></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <label for="lounge_includes">Includes</label><br>
                                                        <input class="form-control" name="lounge_includes" id="lounge_includes" data-role="tagsinput">
                                                        <span class="bar"></span>
                                                        <span class="help-block"><small id="msg_lounge_includes" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40 focused">
                                                        <label for="lounge_amenities">Amenities</label><br>
                                                        <input class="form-control" name="lounge_amenities" id="lounge_amenities" data-role="tagsinput">
                                                        <span class="bar"></span>
                                                        <span class="help-block"><small id="msg_lounge_amenities" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <input class="form-control" name="lounge_address" id="lounge_address">
                                                        <span class="bar"></span>
                                                        <label for="lounge_address">Address <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_address" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40">
                                                        <input class="form-control" name="lounge_area" id="lounge_area">
                                                        <span class="bar"></span>
                                                        <label for="lounge_area">Area <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_area" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40 m-t-15">
                                                        <select class="select2 form-control p-0" name="cities_id" id="cities_id">
                                                            <option value="0">Select as City</option>
                                                            @foreach($cityDetail as $city)
                                                                <option value="{{ $city->cities_id }}">{{ $city->cities_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="page_parent">City <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_cities_id" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-12 m-b-40">
                                                        <input class="form-control" name="lounge_google_map" id="lounge_google_map">
                                                        <span class="bar"></span>
                                                        <label for="lounge_google_map">Google Map Location <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_google_map" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40 m-t-5">
                                                        <select class="form-control p-0"  name="lounge_unit" id="lounge_unit">
                                                            <option value="0">Select a Unit Type</option>
                                                            <option value="1 Box">1 Box</option>
                                                            <option value="2 Box">2 Box</option>
                                                            <option value="3 Box">3 Box</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="lounge_unit">Lounge Unit Type <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_unit" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40 m-t-5">
                                                        <select class="form-control p-0" name="lounge_ownership" id="lounge_ownership">
                                                            <option value="0">Select a Type</option>
                                                            <option value="Owned">Owned</option>
                                                            <option value="Rented">Rented</option>
                                                            <option value="Lease">Lease</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="lounge_ownership">Ownership Type <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_ownership" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40 dob">
                                                        <input class="form-control mdate" name="lounge_agreement_start_date" id="lounge_agreement_start_date" >
                                                        <span class="bar"></span>
                                                        <label for="lounge_agreement_start_date">Agreement Start Date <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_agreement_start_date" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40 dob">
                                                        <input class="form-control mdate" name="lounge_agreement_end_date" id="lounge_agreement_end_date" >
                                                        <span class="bar"></span>
                                                        <label for="lounge_agreement_end_date">Agreement End Date <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_agreement_end_date" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40">
                                                        <select class="form-control p-0"  name="lounge_gst_invoice" id="lounge_gst_invoice">
                                                            <option value="0">Select a GST Invoice</option>
                                                            <option value="no" >No</option>
                                                            <option value="yes">Yes</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="lounge_gst_invoice">GST Invoice <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_gst_invoice" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40">
                                                        <select class="form-control p-0"  name="lounge_franchise_status" id="lounge_franchise_status">
                                                            <option value="0">Select a Status</option>
                                                            <option value="1">Agreement Signed</option>
                                                            <option value="2">Site Ready</option>
                                                            <option value="3">Live</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="lounge_franchise_status">Franchise Status <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_franchise_status" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40">
                                                        <input class="form-control isNumber" name="lounge_plateform_fee" id="lounge_plateform_fee" maxlength="2">
                                                        <span class="bar"></span>
                                                        <label for="lounge_plateform_fee">Plateform Fee <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_lounge_plateform_fee" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="lounge_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="lounge_desc" name="lounge_desc"></textarea>
                                                        <script type="text/javascript">
                                                            CKEDITOR.replace( 'lounge_desc',
                                                                {
                                                                    filebrowserBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html') }}',
                                                                    filebrowserUploadUrl : '{{ url('assets/ckfinder/userfiles') }}',
                                                                    filebrowserImageBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html?Type=Images') }}',
                                                                    filebrowserFlashBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html?Type=Flash') }}',
                                                                    filebrowserUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
                                                                    filebrowserImageUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}',
                                                                    filebrowserFlashUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}',
                                                                    enterMode: CKEDITOR.ENTER_P,
                                                                }
                                                            );
                                                        </script>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="lounge_rules" class="m-b-20" style="position: initial;">Rules</label>
                                                        <textarea id="lounge_rules" name="lounge_rules"></textarea>
                                                        <script type="text/javascript">
                                                            CKEDITOR.replace( 'lounge_rules',
                                                                {
                                                                    filebrowserBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html') }}',
                                                                    filebrowserUploadUrl : '{{ url('assets/ckfinder/userfiles') }}',
                                                                    filebrowserImageBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html?Type=Images') }}',
                                                                    filebrowserFlashBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html?Type=Flash') }}',
                                                                    filebrowserUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
                                                                    filebrowserImageUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}',
                                                                    filebrowserFlashUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}',
                                                                    enterMode: CKEDITOR.ENTER_P,
                                                                }
                                                            );
                                                        </script>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="card-body p-l-10">
                                                            <div class="row">
                                                                <label for="pimage_image" class="m-b-0" style="position: initial;">Lounge Extra Image</label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 p-l-0 p-r-0">
                                                                <div id="uploader">Upload</div>
                                                                <span class="p-l-5" style="color: red;font-size: 12px;">Best size: (Width:380px X Height:440px)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="lounge_time" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            {{--<h3>Working Day & Time</h3>--}}<span style="color: red;"><br>(Checked days indicate open)</span>
                                            <div class="form-group ">
                                                <div class="col-md-1 col-sm-1 col-xs-1" style="float:left;">Select</div>
                                                <div class="col-md-1 col-sm-1 col-xs-1" style="float:left;">Days</div>
                                                <div class="col-md-4 col-sm-4 col-xs-4" style="float:left;">Opening Time / Closing Time</div>
                                                <div  class="col-md-1 col-sm-1 col-xs-1" style="float:left;"><a href="javascript:void(0)" onclick="autoFillTime()">Apply to all</a></div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $loungeTimeArray = $loungeHourArray = array();
                                        for ($i=0; $i<=59; $i++){
                                            array_push($loungeTimeArray, sprintf("%02d",$i));
                                        }

                                        for ($i=0; $i<=12; $i++){
                                            array_push($loungeHourArray, sprintf("%02d",$i));
                                        }
                                        $timeArray = array('00:00','00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30');
                                        $fullDayArray = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
                                        $dayArray = array('MON','TUE','WED','THU','FRI','SAT','SUN');
                                        $ampmArray = array('AM','PM');
                                    @endphp
                                    @for($i=0; $i<=6; $i++)
                                        <div class="form-control col-md-12 education_fields{{ $i }}" id="education_fields{{ $i }}">
                                            <div class="col-md-1 col-sm-1 col-xs-1 float-left">
                                                <input type="checkbox" onclick="chnageDay(this.value)" class="dt{{ $dayArray[$i] }}" name="lounge_day[{{ $dayArray[$i] }}][]" id="lounge_day" value="{{ $dayArray[$i] }}" checked="checked">
                                            </div>

                                            <div class="col-md-1 col-sm-1 col-xs-1 float-left">
                                                <label><b><?php echo $fullDayArray[$i]; ?></b></label>
                                            </div>

                                            <span id="{{ $dayArray[$i] }}time">
                                                <div class="col-md-2 col-sm-2 col-xs-2 float-left ">
                                                    <!--from time-->
                                                    <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                        <select class="form-control p-0 lounge_open_hour" name="lounge_open_hour[{{ $dayArray[$i] }}][]" id="page_parent">
                                                            @if($loungeHourArray) @foreach($loungeHourArray as $hour)
                                                                <option value="{{ $hour }}">{{ $hour }}</option>
                                                            @endforeach @endif
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                        <select class="form-control p-0 lounge_open_time" name="lounge_open_time[{{ $dayArray[$i] }}][]" id="page_parent">
                                                            @if($loungeTimeArray) @foreach($loungeTimeArray as $time)
                                                                <option value="{{ $time }}">{{ $time }}</option>
                                                            @endforeach @endif
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 col-sm-4 col-xs-4 float-left p-0">
                                                        <select id="lounge_open_ap" name="lounge_open_ap[{{ $dayArray[$i] }}][]" class="form-control lounge_open_ap">
                                                            @if($ampmArray) @foreach($ampmArray as $ampm)
                                                                <option value="{{ $ampm }}">{{ $ampm }}</option>
                                                            @endforeach @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-1 col-sm-1 col-xs-1 float-left p-0" style="text-align: center">
                                                    <label><b>To</b></label>
                                                </div>

                                                <div class="col-md-2 col-sm-2 col-xs-2 float-left ">
                                                    <!--to time-->
                                                    <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                        <select class="form-control p-0 lounge_close_hour" name="lounge_close_hour[{{ $dayArray[$i] }}][]" id="page_parent">
                                                            @if($loungeHourArray) @foreach($loungeHourArray as $hour)
                                                                <option value="{{ $hour }}">{{ $hour }}</option>
                                                            @endforeach @endif
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                        <select class="form-control p-0 lounge_close_time" name="lounge_close_time[{{ $dayArray[$i] }}][]" id="page_parent">
                                                            @if($loungeTimeArray) @foreach($loungeTimeArray as $time)
                                                                <option value="{{ $time }}">{{ $time }}</option>
                                                            @endforeach @endif
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 col-sm-4 col-xs-4 float-left p-0">
                                                        <select id="lounge_close_ap" name="lounge_close_ap[{{ $dayArray[$i] }}][]" class="form-control lounge_close_ap">
                                                            @if($ampmArray) @foreach($ampmArray as $ampm)
                                                                <option value="{{ $ampm }}">{{ $ampm }}</option>
                                                            @endforeach @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                    <input type="text" class="form-control lounge_text" name="lounge_text[{{ $dayArray[$i] }}][]" placeholder="Enter Your Price">
                                                </div>
                                            </span>

                                            <spna id="{{ $dayArray[$i] }}msg"></spna>

                                            <div class="input-group-append">
                                                <input type="hidden" name="rowcount" id="rowcount{{ $dayArray[$i] }}" value="1">
                                                <button class="btn btn-success" type="button" onclick="education_fields('{{ $i }}','{{ $dayArray[$i] }}');"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="tab-pane" id="lounge_maintenance_time" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body m-t-20">
                                                <div class="row">
                                                    <div class="form-group col-md-2 dob">
                                                        <input type="text" class="form-control mdate" id="lounge_maintenance_open_date" name="lounge_maintenance_open_date">
                                                        <span class="bar"></span>
                                                        <label for="lounge_maintenance_open_date">Start Date</label>
                                                        <span class="help-block"><small id="msg_lounge_maintenance_open_date" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="col-md-2 col-sm-2 col-xs-2 float-left m-t-5" id="lm_open">
                                                        <!--from time-->
                                                        <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                            <select class="form-control p-0 lounge_maintenance_open_hour" name="lounge_maintenance_open_hour" id="page_parent">
                                                                @if($loungeHourArray) @foreach($loungeHourArray as $hour)
                                                                    <option value="{{ $hour }}">{{ $hour }}</option>
                                                                @endforeach @endif
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                            <select class="form-control p-0 lounge_maintenance_open_time" name="lounge_maintenance_open_time" id="page_parent">
                                                                @if($loungeTimeArray) @foreach($loungeTimeArray as $time)
                                                                    <option value="{{ $time }}">{{ $time }}</option>
                                                                @endforeach @endif
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-4 float-left p-0">
                                                            <select id="lounge_maintenance_open_ap" name="lounge_maintenance_open_ap" class="form-control lounge_maintenance_open_ap">
                                                                @if($ampmArray) @foreach($ampmArray as $ampm)
                                                                    <option value="{{ $ampm }}">{{ $ampm }}</option>
                                                                @endforeach @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 col-sm-2 col-xs-2 float-left m-t-5" id="lm_close">
                                                        <!--from time-->
                                                        <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                            <select class="form-control p-0 lounge_maintenance_close_hour" name="lounge_maintenance_close_hour" id="page_parent">
                                                                @if($loungeHourArray) @foreach($loungeHourArray as $hour)
                                                                    <option value="{{ $hour }}">{{ $hour }}</option>
                                                                @endforeach @endif
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-4 float-left">
                                                            <select class="form-control p-0 lounge_maintenance_close_time" name="lounge_maintenance_close_time" id="page_parent">
                                                                @if($loungeTimeArray) @foreach($loungeTimeArray as $time)
                                                                    <option value="{{ $time }}">{{ $time }}</option>
                                                                @endforeach @endif
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-4 float-left p-0">
                                                            <select id="lounge_maintenance_close_ap" name="lounge_maintenance_close_ap" class="form-control lounge_maintenance_close_ap">
                                                                @if($ampmArray) @foreach($ampmArray as $ampm)
                                                                    <option value="{{ $ampm }}">{{ $ampm }}</option>
                                                                @endforeach @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="custom-control custom-checkbox col-lg-2 p-10 m-t-15">
                                                        <input type="checkbox" class="custom-control-input" id="is_fullday_close" name="is_fullday_close" value="1" />
                                                        <label class="custom-control-label p-l-20" for="is_fullday_close">Closed 24 Hours</label>
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
                        </div>
                    </form>
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
                $("#v_unit").addClass("focused");
                $("#v_category").addClass("focused");
            }, 100);

            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

            $('#lounge_includes').tagsinput();
            $('#lounge_amenities').tagsinput();

            $.each(['MON','TUE','WED','THU','FRI','SAT','SUN'], function( index, value ) {
                chnageDay(value);
                chnageTime(value);
            });

            $('#is_fullday_close').change(function() {
                if ($(this).is(':checked')) {
                    $('#lm_open, #lm_close').hide();
                } else {
                    $('#lm_open, #lm_close').show();
                }
            });
        });

        function autoFillTime(){
            var rowvalue = $("#rowcountMON").val();
            for(var j=0 ; j<= parseInt(rowvalue) ;j++ ) {
                if(j==0){
                    j="";
                }
                var lounge_open_hour = document.getElementsByClassName("lounge_open_hour"+j)[0].value;
                var lounge_open_time = document.getElementsByClassName("lounge_open_time"+j)[0].value;
                var lounge_open_ap = document.getElementsByClassName("lounge_open_ap"+j)[0].value;
                var lounge_close_hour = document.getElementsByClassName("lounge_close_hour"+j)[0].value;
                var lounge_close_time = document.getElementsByClassName("lounge_close_time"+j)[0].value;
                var lounge_close_ap = document.getElementsByClassName("lounge_close_ap"+j)[0].value;
                var lounge_text = document.getElementsByClassName("lounge_text"+j)[0].value;

                for (var i = 0; i <= 6; i++) {
                    document.getElementsByClassName("lounge_open_hour"+j)[i].value = lounge_open_hour;
                    document.getElementsByClassName("lounge_open_time"+j)[i].value = lounge_open_time;
                    document.getElementsByClassName("lounge_open_ap"+j)[i].value = lounge_open_ap;
                    document.getElementsByClassName("lounge_close_hour"+j)[i].value = lounge_close_hour;
                    document.getElementsByClassName("lounge_close_time"+j)[i].value = lounge_close_time;
                    document.getElementsByClassName("lounge_close_ap"+j)[i].value = lounge_close_ap;
                    document.getElementsByClassName("lounge_text"+j)[i].value = lounge_text;

                }
            }
        }

        function chnageTime(day) {
            if ($(".hour"+day+"").is(":checked")) {
                $("#"+day+"time :input").attr("disabled", true);
                $("#"+day+"time").hide();
                if(day=="MON"){
                    $(".span1").hide();
                }
                $("#"+day+"msg").html("Open 24 hours");
            }else{
                $("#"+day+"time :input").attr("disabled", false);
                $("#"+day+"time").show();
                if(day=="MON"){
                    $(".span1").show();
                }
                $("#"+day+"msg").html("");
            }
        }

        function chnageDay(day) {
            if ($(".dt"+day+"").is(":checked")) {
                $("#"+day+"time :input").attr("disabled", false);
                $("#"+day+"time").show();
                if(day=="MON"){
                    $(".span1").show();
                }
                $("#"+day+"msg").html("");
            }else{
                $("#"+day+"time :input").attr("disabled", true);
                /*if(day=="MON"){
                    $(".span1").hide();
                }*/
                /*setTimeout(function () {
                    $("#"+day+"time").hide();
                    $("#"+day+"msg").html("Close");
                }, 100);*/
            }
        }

        $(document).ready(function () {
            $("#uploader").pluploadQueue({
                runtimes : 'html5',
                dragdrop : false,
                url : "{{ route('lounge-pupload') }}",
                resize: {
                    width: 800
                }
            });
        });

        $('#lounge_name').keyup(function(e) {
            $.ajax({
                url: "{{ route('lounge-create-slug') }}",
                type: "GET",
                data: {
                    'lounge_name': $(this).val()
                },
                success: function(response) {
                    $('#l_slug').addClass('focused')
                    $('#lounge_slug').val(response.slug);
                }
            });
        });

        $("#loungeFrm").on('submit', function(e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form = $('#loungeFrm')[0];
            var formData = new FormData(form);
            $("#loungeFrm").find(".has-error").removeClass("has-error");
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
                        window.location = "{{ url('admin/lounge-list') }}";
                    }
                }
            });
        });

        var room = 1;
        function education_fields(id,day) {
            var rowcount = $("#rowcount"+day).val();
            $("#rowcount"+day).val(parseInt(rowcount)+1);
            room++;
            var objTo = document.getElementById('education_fields'+id);
            var divtest = document.createElement("div");
            divtest.setAttribute("class", "form-control col-md-12 removeclass" + room);
            divtest.setAttribute("id", "datetime");
            var rdiv = 'removeclass' + room;
            divtest.innerHTML = '<div class="col-md-1 col-sm-1 col-xs-1 float-left"> <input type="checkbox" onclick="chnageDay(this.value)" class="dt'+day+'" name="lounge_day['+day+'][]" id="lounge_day" value="'+day+'" checked="checked"> </div> <div class="col-md-1 col-sm-1 col-xs-1 float-left"> <label><b></b></label> </div> <span id="'+day+'time"> <div class="col-md-2 col-sm-2 col-xs-2 float-left "> <!--from time--> <div class="col-md-4 col-sm-4 col-xs-4 float-left "> <select class="form-control p-0 lounge_open_hour'+rowcount+'" name="lounge_open_hour['+day+'][]" id="page_parent"> @if($loungeHourArray) @foreach($loungeHourArray as $hour) <option value="{{ $hour }}">{{ $hour }}</option> @endforeach @endif </select> </div> <div class="col-md-4 col-sm-4 col-xs-4 float-left "> <select class="form-control p-0 lounge_open_time'+rowcount+'" name="lounge_open_time['+day+'][]" id="page_parent"> @if($loungeTimeArray) @foreach($loungeTimeArray as $time) <option value="{{ $time }}">{{ $time }}</option> @endforeach @endif </select> </div> <div class="col-md-4 col-sm-4 col-xs-4 float-left p-0"> <select id="lounge_open_ap lounge_open_ap'+rowcount+'" name="lounge_open_ap['+day+'][]" class="form-control lounge_open_ap"> @if($ampmArray) @foreach($ampmArray as $ampm) <option value="{{ $ampm }}">{{ $ampm }}</option> @endforeach @endif </select> </div> </div> <div class="col-md-1 col-sm-1 col-xs-1 float-left p-0" style="text-align: center"> <label><b>To</b></label> </div> <div class="col-md-2 col-sm-2 col-xs-2 float-left "> <!--to time--> <div class="col-md-4 col-sm-4 col-xs-4 float-left"> <select class="form-control p-0 lounge_close_hour'+rowcount+'" name="lounge_close_hour['+day+'][]" id="page_parent"> @if($loungeHourArray) @foreach($loungeHourArray as $hour) <option value="{{ $hour }}">{{ $hour }}</option> @endforeach @endif </select> </div> <div class="col-md-4 col-sm-4 col-xs-4 float-left"> <select class="form-control p-0 lounge_close_time'+rowcount+'" name="lounge_close_time['+day+'][]" id="page_parent"> @if($loungeTimeArray) @foreach($loungeTimeArray as $time) <option value="{{ $time }}">{{ $time }}</option> @endforeach @endif </select> </div> <div class="col-md-4 col-sm-4 col-xs-4 float-left p-0"> <select id="lounge_close_ap lounge_close_ap'+rowcount+'" name="lounge_close_ap['+day+'][]" class="form-control lounge_close_ap"> @if($ampmArray) @foreach($ampmArray as $ampm) <option value="{{ $ampm }}">{{ $ampm }}</option> @endforeach @endif </select> </div> </div> <div class="col-md-4 col-sm-4 col-xs-4 float-left"> <input type="text" class="form-control lounge_text" name="lounge_text['+day+'][]" placeholder="Enter Your Price"> </div> </span> <spna id="'+day+'msg"></spna> <div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="remove_education_fields('+room+');"><i class="fa fa-minus"></i></button> </div>';
            objTo.appendChild(divtest)
        }

        function remove_education_fields(rid) {
            $('.removeclass' + rid).remove();
        }
    </script>
@endsection
