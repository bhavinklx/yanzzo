@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">City</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit City</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="cityFrm" method="post" action="{{ route("city-update") }}">
                <input type="hidden" name="city_id" value="{{ $cityDetail->city_id }}">
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
                                                    <div class="form-group col-md-6 m-b-40" id="b_title">
                                                        <input type="text" class="form-control" name="city_title" id="city_title" value="{{ $cityDetail->city_title }}" >
                                                        <span class="bar"></span>
                                                        <label for="city_title">City Title</label>
                                                        <span class="help-block"><small id="msg_city_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="b_slug">
                                                        <input type="text" class="form-control" id="city_slug" name="city_slug" value="{{ $cityDetail->city_slug }}" >
                                                        <span class="bar"></span>
                                                        <label for="city_slug">City Slug</label>
                                                        <span class="help-block"><small id="msg_city_slug" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <textarea class="form-control" name="city_short_desc" id="city_short_desc">{{ $cityDetail->city_short_desc }}</textarea>
                                                        <span class="bar"></span>
                                                        <label for="city_short_desc">Short Description</label>
                                                        <span class="help-block"><small id="msg_city_short_desc" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40 m-t-20">
                                                        <input class="form-control" name="city_meta_title" id="city_meta_title" value="{{ $cityDetail->city_meta_title }}" >
                                                        <span class="bar"></span>
                                                        <label for="city_meta_title">Meta Title</label>
                                                        <span class="help-block"><small id="msg_city_meta_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>City Image</label><br><br>
                                                        @if($cityDetail->city_id > 0)
                                                            <input type="hidden" name="old_image" value="{{ $cityDetail->city_image }}">
                                                        @endif
                                                        <input type="file" class="dropify" data-default-file="{{ ($cityDetail->city_image!="" && file_exists(public_path('/uploads/city/'.$cityDetail->city_image))) ? asset('/uploads/city/'.$cityDetail->city_image) : "" }}" id="city_image" name="city_image" aria-describedby="fileHelp">
                                                        {{--<span style="color: red;font-size: 12px;">Best size: (Width:1920px X Height:720px)</span>--}}
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group col-md-12 m-b-40">
                                                            <textarea class="form-control" name="city_meta_keyword" id="city_meta_keyword">{{ $cityDetail->city_meta_keyword }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="city_meta_keyword">Meta Keyword</label>
                                                            <span class="help-block"><small id="msg_city_meta_keyword" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group col-md-12 m-b-40">
                                                            <textarea class="form-control" name="city_meta_desc" id="city_meta_desc">{{ $cityDetail->city_meta_desc }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="city_meta_desc">Meta Description</label>
                                                            <span class="help-block"><small id="msg_city_meta_desc" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group col-md-12 m-b-40">
                                                            <input class="form-control" name="city_canonical" id="city_canonical" value="{{ $cityDetail->city_canonical }}">
                                                            <span class="bar"></span>
                                                            <label for="city_canonical">Canonical Url</label>
                                                            <span class="help-block"><small id="msg_city_canonical" class="text-danger"></small></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="city_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="city_desc" name="city_desc">{{ $cityDetail->city_desc }}</textarea>
                                                        <script type="text/javascript">
                                                            CKEDITOR.replace( 'city_desc',
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

        $('#city_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('city-create-slug') }}",
                type: "GET",
                data: {'city_title' : $(this).val()},
                success: function (response) {
                    $('#b_slug').addClass('focused')
                    $('#city_slug').val(response.slug);
                }
            });
        });

        $("#cityFrm").on('submit', function (e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form    = $('#cityFrm')[0];
            var formData= new FormData(form);
            $("#cityFrm").find(".has-error").removeClass("has-error");
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
                        window.location = "{{ url('admin/city-list') }}";
                    }
                }
            });
        });
    </script>
@endsection