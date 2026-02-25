@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Blogs</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Blog</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="blogFrm" method="post" action="{{ route("blog-update") }}">
                <input type="hidden" name="blog_id" value="{{ $blogDetail->blog_id }}">
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
                                                    <div class="form-group col-md-6 m-b-40 m-t-5">
                                                        <select class="form-control p-0" name="bcategory_id" id="bcategory_id">
                                                            <option value="0" >Select as Category</option>
                                                            @foreach($bcategoryDetail as $bcategory)
                                                                <option value="{{ $bcategory->bcategory_id }}" {{ ($bcategory->bcategory_id == $blogDetail->bcategory_id) ? 'selected="selected"' : '' }} >{{ $bcategory->bcategory_title }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label>Blog Category <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_bcategory_id" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40 dob">
                                                        <input type="text" class="form-control mdate" id="blog_date" name="blog_date" value="{{ date('d-m-Y', strtotime($blogDetail->blog_date)) }}" >
                                                        <span class="bar"></span>
                                                        <label for="blog_date">Blog Date <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_blog_date" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="b_title">
                                                        <input type="text" class="form-control" name="blog_title" id="blog_title" value="{{ $blogDetail->blog_title }}" >
                                                        <span class="bar"></span>
                                                        <label for="blog_title">Blog Title <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_blog_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="b_slug">
                                                        <input type="text" class="form-control" id="blog_slug" name="blog_slug" value="{{ $blogDetail->blog_slug }}" >
                                                        <span class="bar"></span>
                                                        <label for="blog_slug">Blog Slug <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_blog_slug" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40">
                                                        <textarea class="form-control" name="blog_short_desc" id="blog_short_desc">{{ $blogDetail->blog_short_desc }}</textarea>
                                                        <span class="bar"></span>
                                                        <label for="blog_short_desc">Short Description</label>
                                                        <span class="help-block"><small id="msg_blog_short_desc" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40 m-t-20">
                                                        <input class="form-control" name="blog_meta_title" id="blog_meta_title" value="{{ $blogDetail->blog_meta_title }}" >
                                                        <span class="bar"></span>
                                                        <label for="blog_meta_title">Meta Title</label>
                                                        <span class="help-block"><small id="msg_blog_meta_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Blog Image</label><br><br>
                                                        @if($blogDetail->blog_id > 0)
                                                            <input type="hidden" name="old_image" value="{{ $blogDetail->blog_image }}">
                                                        @endif
                                                        <input type="file" class="dropify" data-default-file="{{ ($blogDetail->blog_image!="" && file_exists(public_path('/uploads/blog/'.$blogDetail->blog_image))) ? asset('/uploads/blog/'.$blogDetail->blog_image) : "" }}" id="blog_image" name="blog_image" aria-describedby="fileHelp">
                                                        <span style="color: red;font-size: 12px;">Best size: (Width:1920px X Height:720px)</span>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group col-md-12 m-b-40">
                                                            <textarea class="form-control" name="blog_meta_keyword" id="blog_meta_keyword">{{ $blogDetail->blog_meta_keyword }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="blog_meta_keyword">Meta Keyword</label>
                                                            <span class="help-block"><small id="msg_blog_meta_keyword" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group col-md-12 m-b-40">
                                                            <textarea class="form-control" name="blog_meta_desc" id="blog_meta_desc">{{ $blogDetail->blog_meta_desc }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="blog_meta_desc">Meta Description</label>
                                                            <span class="help-block"><small id="msg_blog_meta_desc" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group col-md-12 m-b-40">
                                                            <input class="form-control" name="blog_canonical" id="blog_canonical" value="{{ $blogDetail->blog_canonical }}">
                                                            <span class="bar"></span>
                                                            <label for="blog_canonical">Canonical Url</label>
                                                            <span class="help-block"><small id="msg_blog_canonical" class="text-danger"></small></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="blog_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="blog_desc" name="blog_desc">{!! $blogDetail->blog_desc !!}</textarea>
                                                        <script type="text/javascript">
                                                            CKEDITOR.replace( 'blog_desc',
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

        $('#blog_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('blog-create-slug') }}",
                type: "GET",
                data: {'blog_title' : $(this).val()},
                success: function (response) {
                    $('#b_slug').addClass('focused')
                    $('#blog_slug').val(response.slug);
                }
            });
        });

        $("#blogFrm").on('submit', function (e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form    = $('#blogFrm')[0];
            var formData= new FormData(form);
            $("#blogFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $(".text-danger").html("");
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
                        window.location = "{{ url('admin/blog-list') }}";
                    }
                }
            });
        });
    </script>
@endsection