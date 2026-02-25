@extends("admin.layouts.app")
@section("content")
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Pages</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Page</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="pagesFrm" action="{{ route("pages-update") }}" method="post">
                <input type="hidden" name="page_id" value="{{ $pagesDetail->page_id }}" >
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row card-body m-t-20">
                                                    <div class="form-group col-md-6 m-b-40 m-t-5">
                                                        <select class="form-control p-0"  name="page_parent" id="page_parent">
                                                            <option value="0" >Select as Parent</option>
                                                            @foreach ($parentPages as $pages)
                                                                @if ($pages->page_id != $pagesDetail->page_id)
                                                                    <option value="{{ $pages->page_id }}" {{ ($pages->page_id == $pagesDetail->page_parent) ? 'selected="selected"' : '' }}>{{ $pages->page_title }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="page_parent">Page Parent</label>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="p_link">
                                                        <input type="text" class="form-control" name="page_link" id="page_link" value="{{ $pagesDetail->page_link }}" >
                                                        <span class="bar"></span>
                                                        <label for="page_link">Page Link</label>
                                                        <span class="help-block"><small id="msg_page_link" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="p_title">
                                                        <input type="text" class="form-control" name="page_title" id="page_title" value="{{ $pagesDetail->page_title }}" >
                                                        <span class="bar"></span>
                                                        <label for="page_title">Page Title <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_page_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="p_slug">
                                                        <input type="text" class="form-control" id="page_slug" name="page_slug" value="{{ $pagesDetail->page_slug }}" >
                                                        <span class="bar"></span>
                                                        <label for="page_slug">Page Slug <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_page_slug" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="col-md-6 m-b-40">
                                                        <label>Page Image</label><br><br>
                                                        @if($pagesDetail->page_id > 0)
                                                            <input type="hidden" name="old_image" value="{{ $pagesDetail->page_image }}">
                                                        @endif
                                                        <input type="file" class="dropify" data-default-file="{{ ($pagesDetail->page_image!="" && file_exists(public_path('/uploads/pages/'.$pagesDetail->page_image))) ? asset('/uploads/pages/'.$pagesDetail->page_image) : "" }}" id="page_image" name="page_image" aria-describedby="fileHelp">
                                                        <span style="color: red;font-size: 12px;">Best size: (Width:1920px X Height:430px)</span>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-40">
                                                            <input type="text" class="form-control" name="page_meta_title" id="page_meta_title" value="{{ $pagesDetail->page_meta_title }}" >
                                                            <span class="bar"></span>
                                                            <label for="page_meta_title">Meta Title</label>
                                                            <span class="help-block"><small id="msg_page_meta_title" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40">
                                                            <textarea class="form-control" name="page_meta_keyword" id="page_meta_keyword">{{ $pagesDetail->page_meta_keyword }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="page_meta_keyword">Meta Keyword</label>
                                                            <span class="help-block"><small id="msg_page_meta_keyword" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40">
                                                            <textarea class="form-control" name="page_meta_desc" id="page_meta_desc">{{ $pagesDetail->page_meta_desc }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="page_meta_desc">Meta Description</label>
                                                            <span class="help-block"><small id="msg_page_meta_desc" class="text-danger"></small></span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12 m-b-40">
                                                        <label for="page_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="page_desc" name="page_desc">{{ $pagesDetail->page_desc }}</textarea>
                                                        <script type="text/javascript">
                                                            CKEDITOR.replace( 'page_desc',
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

                                                    <div class="form-group col-md-3 m-b-20">
                                                        <select class="form-control p-0" name="page_status" id="page_status">
                                                            <option value="1" {{ ($pagesDetail->page_status == '1') ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ ($pagesDetail->page_status == '0') ? 'selected' : '' }}>Inactive</option>
                                                        </select><span class="bar"></span>
                                                        <label for="page_status">Status</label>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-20">
                                                        <select class="form-control p-0" name="page_header_status" id="page_header_status">
                                                            <option value="1" {{ ($pagesDetail->page_header_status == '1') ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ ($pagesDetail->page_header_status == '0') ? 'selected' : '' }}>Inactive</option>
                                                        </select><span class="bar"></span>
                                                        <label for="page_header_status">Header Status</label>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-20">
                                                        <select class="form-control p-0" name="page_footer_status" id="page_footer_status">
                                                            <option value="1" {{ ($pagesDetail->page_footer_status == '1') ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ ($pagesDetail->page_footer_status == '0') ? 'selected' : '' }}>Inactive</option>
                                                        </select><span class="bar"></span>
                                                        <label for="page_footer_status">Footer Status</label>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-20">
                                                        <select class="form-control p-0" name="page_app_status" id="page_app_status">
                                                            <option value="1" {{ ($pagesDetail->page_app_status == '1') ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ ($pagesDetail->page_app_status == '0') ? 'selected' : '' }}>Inactive</option>
                                                        </select><span class="bar"></span>
                                                        <label for="page_app_status">App Status</label>
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
@section("page-js")
    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function(){
                $(".dropify-wrapper").css("width","100%");

            },100);
        });

        $('#page_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('pages-create-slug') }}",
                type: "GET",
                data: {'page_title' : $(this).val()},
                success: function (response) {
                    $('#p_slug').addClass('focused')
                    $('#page_slug').val(response.slug);
                }
            });
        });

        $('#pagesFrm').on('submit', function (e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form    = $('#pagesFrm')[0];
            var formData= new FormData(form);
            $("#pagesFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: $(this).attr('action'),
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
                        window.location = "{{ url('admin/pages-list') }}";
                    }
                }
            });
        });
    </script>
@endsection
