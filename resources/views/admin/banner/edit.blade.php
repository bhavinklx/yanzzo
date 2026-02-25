@extends("admin.layouts.app")
@section("content")
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Banners</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Banner</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="bannerFrm" action="{{ route("banner-update") }}" method="post">
                <input type="hidden" name="banner_id" value="{{ $bannerDetail->banner_id }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="tab-content p-20" id="myTabContent">
                                    <div role="tabpanel" class="tab-pane fade show active" id="english" aria-labelledby="english-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row">
                                                    <div class="col-md-6 m-t-25">
                                                        <div class="form-group m-b-40 m-t-25" id="b_title">
                                                            <input type="text" class="form-control" name="banner_title" id="banner_title" value="{{ $bannerDetail->banner_title }}">
                                                            <span class="bar"></span>
                                                            <label for="banner_title">Banner Title</label>
                                                            <span class="help-block"><small id="msg_banner_title" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40" id="b_link">
                                                            <input type="text" class="form-control" name="banner_text" id="banner_text" value="{{ $bannerDetail->banner_text }}">
                                                            <span class="bar"></span>
                                                            <label for="banner_text">Banner Text (Top Text)</label>
                                                            <span class="help-block"><small id="msg_banner_text" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40" id="b_link">
                                                            <input type="text" class="form-control" name="banner_text1" id="banner_text1" value="{{ $bannerDetail->banner_text1 }}">
                                                            <span class="bar"></span>
                                                            <label for="banner_text">Banner Text (Bottom Text)</label>
                                                            <span class="help-block"><small id="msg_banner_text" class="text-danger"></small></span>
                                                        </div>

                                                        {{--<div class="form-group">
                                                            <select class="form-control p-0" name="banner_status" id="banner_status">
                                                                <option value="1" {{ ($bannerDetail->banner_status == 1) ? 'selected="selected"' : '' }}>Active</option>
                                                                <option value="0" {{ ($bannerDetail->banner_status == 0) ? 'selected="selected"' : '' }} >Inactive</option>
                                                            </select><span class="bar"></span>
                                                            <label for="banner_status">Status</label>
                                                        </div>--}}
                                                    </div>

                                                    <div class="col-md-6 m-t-15">
                                                        <div class="row m-b-40">
                                                            <div class="col-md-6">
                                                                <label>Banner Image</label><br><br>
                                                                @if ($bannerDetail->id!="0")
                                                                    <input type="hidden" name="old_image" value="{{ $bannerDetail->banner_image }}">
                                                                @endif
                                                                <input type="file" class="dropify" data-default-file="{{ ($bannerDetail->banner_image!="" && file_exists(public_path('/uploads/banner/'.$bannerDetail->banner_image))) ? asset('/uploads/banner/'.$bannerDetail->banner_image) : "" }}" id="banner_image" name="banner_image" aria-describedby="fileHelp">
                                                                <span style="color: red;font-size: 12px;">Best size: (Width:1920px X Height:800px)</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Banner Image</label><br><br>
                                                                @if ($bannerDetail->id!="0")
                                                                    <input type="hidden" name="old_image1" value="{{ $bannerDetail->banner_icon }}">
                                                                @endif
                                                                <input type="file" class="dropify" data-default-file="{{ ($bannerDetail->banner_icon!="" && file_exists(public_path('/uploads/banner/'.$bannerDetail->banner_icon))) ? asset('/uploads/banner/'.$bannerDetail->banner_icon) : "" }}" id="banner_icon" name="banner_icon" aria-describedby="fileHelp">
                                                                <span style="color: red;font-size: 12px;">Best size: (Width:480px X Height:640px)</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="banner_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="banner_desc" name="banner_desc">{{ $bannerDetail->banner_desc }}</textarea>
                                                        <script>
                                                            CKEDITOR.replace( 'banner_desc',
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

        $('#bannerFrm').on('submit', function(e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form    = $('#bannerFrm')[0];
            var formData= new FormData(form);
            $("#bannerFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url: $(this).attr('action'),
                cache : false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data : formData,
                success: function(response){
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
                        window.location = "{{ url('admin/banner-list') }}";
                    }
                }
            });
        });
    </script>
@endsection
