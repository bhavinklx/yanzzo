@extends('admin.layouts.app')
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Testimonials</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Testimonial</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="testimonialFrm" method="post" action="{{ route('testimonial-update') }}" >
                <input type="hidden" name="testimonial_id" value="{{ $testimonialDetail->testimonial_id }}" >
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row card-body">

                                                    <div class="col-lg-6 m-t-20">
                                                        <div class="form-group m-b-40" id="t_title">
                                                            <input type="text" class="form-control" name="testimonial_title" id="testimonial_title" value="{{ $testimonialDetail->testimonial_title }}" >
                                                            <span class="bar"></span>
                                                            <label for="testimonial_title">Testimonial Title <span class="form-asterisk">*</span></label>
                                                            <span class="help-block"><small id="msg_testimonial_title" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40">
                                                            <input type="text" class="form-control" name="testimonial_designation" id="testimonial_designation" value="{{ $testimonialDetail->testimonial_designation }}" >
                                                            <span class="bar"></span>
                                                            <label for="testimonial_designation">Testimonial Designation</label>
                                                            <span class="help-block"><small id="msg_testimonial_designation" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40">
                                                            <select class="form-control p-0" name="testimonial_status" id="testimonial_status">
                                                                <option value="1" {{ ($testimonialDetail->testimonial_status == 1) ? 'selected="selected"' : '' }}>Active</option>
                                                                <option value="0" {{ ($testimonialDetail->testimonial_status == 0) ? 'selected="selected"' : '' }} >Inactive</option>
                                                            </select><span class="bar"></span>
                                                            <label for="testimonial_status">Status</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Testimonial Image</label><br><br>
                                                        @if($testimonialDetail->id!="0")
                                                            <input type="hidden" name="old_image" value="{{ $testimonialDetail->testimonial_image }}">
                                                        @endif
                                                        <input type="file" class="dropify" data-default-file="{{ ($testimonialDetail->testimonial_image!="" && file_exists(public_path('/uploads/testimonial/'.$testimonialDetail->testimonial_image))) ? asset('/uploads/testimonial/'.$testimonialDetail->testimonial_image) : "" }}" id="testimonial_image" name="testimonial_image" aria-describedby="fileHelp">
                                                    </div>

                                                    <div class="form-group col-lg-12 m-b-40">
                                                        <label for="testimonial_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="testimonial_desc" name="testimonial_desc">{{ $testimonialDetail->testimonial_desc }}</textarea>
                                                        <script type="text/javascript">
                                                            CKEDITOR.replace( 'testimonial_desc',
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

        $('#testimonialFrm').on('submit', function (e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form    = $('#testimonialFrm')[0];
            var formData= new FormData(form);
            $("#testimonialFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                cache : false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data : formData,
                success: function (response) {
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
                        window.location = "{{ url('admin/testimonial-list') }}";
                    }
                }
            });
        });
    </script>
@endsection