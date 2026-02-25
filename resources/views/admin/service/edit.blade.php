@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Service</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Service</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="serviceFrm" method="post" action="{{ route("service-update") }}">
                <input type="hidden" name="service_id" value="{{ $serviceDetail->service_id }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <!--<h4 class="card-title m-b-40">Tab with dropdown</h4>-->
                                <div class="tab-content p-20" id="myTabContent">
                                    <div role="tabpanel" class="tab-pane fade show active" id="english" aria-labelledby="english-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row m-t-20">
                                                    <div class="col-md-6">
                                                        @php
                                                            $cityIdArray = [];
                                                        @endphp
                                                        @if(isset($serviceDetail->city_id) && $serviceDetail->city_id!='')
                                                            @php
                                                                $cityIdArray = explode(',', $serviceDetail->city_id);
                                                            @endphp
                                                        @endif
                                                        <div class="form-group m-b-40 m-t-20">
                                                            <label style="position: static !important; margin-top: 5px; margin-bottom: 0px;">Service City</label>
                                                            <select class="select2 form-control p-0 select2-multiple" name="city_id[]" id="city_id" multiple="multiple">
                                                                <option value="0">Select as City</option>
                                                                @foreach($cityDetail as $city)
                                                                    <option value="{{ $city->city_id }}" {{ ($cityIdArray && in_array($city->city_id, $cityIdArray)) ? 'selected' : '' }}>{{ $city->city_title }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"><small id="msg_city_id" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40" id="s_title">
                                                            <input type="text" class="form-control" name="service_title" id="service_title" value="{{ $serviceDetail->service_title }}">
                                                            <span class="bar"></span>
                                                            <label for="service_title">Service Title</label>
                                                            <span class="help-block"><small id="msg_service_title" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40">
                                                            <select class="form-control p-0" name="service_status" id="service_status">
                                                                <option value="1" {{ ($serviceDetail->service_status == '1') ? 'selected' : '' }}>Active</option>
                                                                <option value="0" {{ ($serviceDetail->service_status == '0') ? 'selected' : '' }}>Inactive</option>
                                                            </select><span class="bar"></span>
                                                            <label for="service_status">Status</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Service Image</label><br><br>
                                                        @if($serviceDetail->service_id > 0)
                                                            <input type="hidden" name="old_image" value="{{ $serviceDetail->service_image }}">
                                                        @endif
                                                        <input type="file" class="dropify" data-default-file="{{ ($serviceDetail->service_image!="" && file_exists(public_path('/uploads/service/'.$serviceDetail->service_image))) ? asset('/uploads/city/'.$serviceDetail->service_image) : "" }}" id="service_image" name="service_image" aria-describedby="fileHelp">
                                                        <span style="color: red;font-size: 12px;">Best size: (Width:325px X Height:250px)</span>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="service_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="service_desc" name="service_desc">{{ $serviceDetail->service_desc }}</textarea>
                                                        <script>
                                                            CKEDITOR.replace( 'service_desc',
                                                                    {
                                                                        toolbar :
                                                                                [
                                                                                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source'] },
                                                                                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                                                                                    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
                                                                                    { name: 'insert', items: [ 'Image' ] },
                                                                                    { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
                                                                                    { name: 'paragraph', items : [ 'NumberedList','BulletedList' ] }
                                                                                ],
                                                                        height:200
                                                                    });
                                                        </script>
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
        $(document).ready(function () {
            setTimeout(function(){
                $(".dropify-wrapper").css("width","100%");
            },100);
        });


        $("#serviceFrm").on('submit', function (e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form    = $('#serviceFrm')[0];
            var formData= new FormData(form);
            $("#serviceFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                cache : false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data : formData,
                success: function (response) {
                    //alert(response.redirect_url)
                    if(response.status == "validation-error")
                    {
                        $.each(response.data, function (key, value)
                        {
                            $("#"+key).parent("div").addClass("has-error");
                            //$("#"+key).next().html("<small class='text-danger'>" + value + "</small>");
                            $("#msg_"+key).html(value);
                        });
                    }
                    else if (response.redirect_url !== undefined)
                    {
                        window.location = "{{ url('admin/service-list') }}";
                    }
                }
            });
        });
    </script>
@endsection