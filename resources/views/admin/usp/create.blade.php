@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Usp</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Usp</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="uspFrm" method="post" action="{{ route("usp-insert") }}">
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
                                                        <div class="form-group m-b-40 m-t-20">
                                                            <label style="position: static !important; margin-top: 5px; margin-bottom: 0px;">Usp City</label>
                                                            <select class="select2 form-control p-0 select2-multiple" name="city_id[]" id="city_id" multiple="multiple">
                                                                <option value="0">Select as City</option>
                                                                @foreach($cityDetail as $city)
                                                                    <option value="{{ $city->city_id }}">{{ $city->city_title }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"><small id="msg_city_id" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40" id="s_title">
                                                            <input type="text" class="form-control" name="usp_title" id="usp_title">
                                                            <span class="bar"></span>
                                                            <label for="usp_title">Usp Title</label>
                                                            <span class="help-block"><small id="msg_usp_title" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40">
                                                            <select class="form-control p-0" name="usp_status" id="usp_status">
                                                                <option value="1" >Active</option>
                                                                <option value="0" >Inactive</option>
                                                            </select><span class="bar"></span>
                                                            <label for="usp_status">Status</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Usp Image</label><br><br>
                                                        <input type="file" class="dropify" data-default-file="" id="usp_image" name="usp_image" aria-describedby="fileHelp">
                                                        {{--<span style="color: red;font-size: 12px;">Best size: (Width:325px X Height:249px)</span>--}}
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="usp_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="usp_desc" name="usp_desc"></textarea>
                                                        <script>
                                                            CKEDITOR.replace( 'usp_desc',
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
        $(document).ready(function() {
            setTimeout(function() {
                $(".dropify-wrapper").css("width", "100%");
            }, 100);
        });

        $("#uspFrm").on('submit', function(e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form = $('#uspFrm')[0];
            var formData = new FormData(form);
            $("#uspFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
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
                        window.location = "{{ url('admin/usp-list') }}";
                    }
                }
            });
        });
    </script>
@endsection
