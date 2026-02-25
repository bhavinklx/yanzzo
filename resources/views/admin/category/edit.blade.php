@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Category</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Category</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="categoryFrm" method="post" action="{{ route("category-update") }}">
                <input type="hidden" name="category_id" value="{{ $categoryDetail->category_id }}">
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
                                                <div class="row">
                                                    <div class="col-md-6 m-t-30">
                                                        <div class="form-group m-b-40 m-t-10">
                                                            <select class="select form-control p-0" name="category_parent" id="category_parent">
                                                                <option value="0" >Select as Category</option>
                                                                @foreach ($parentCategory as $category)
                                                                    <option value="{{ $category->category_id }}" {{ ($category->category_id == $categoryDetail->category_parent) ? 'selected="selected"' : '' }}>{{ $category->category_title }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="bar"></span>
                                                            <label for="category_parent">Category Parent</label>
                                                        </div>

                                                        <div class="form-group m-b-40" id="c_title">
                                                            <input type="text" class="form-control" name="category_title" id="category_title" value="{{ $categoryDetail->category_title }}">
                                                            <span class="bar"></span>
                                                            <label for="category_title">Category Title</label>
                                                            <span class="help-block"><small id="msg_category_title" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40" id="c_slug">
                                                            <input type="text" class="form-control" id="category_slug" name="category_slug" value="{{ $categoryDetail->category_slug }}">
                                                            <span class="bar"></span>
                                                            <label for="category_slug">Category Slug</label>
                                                            <span class="help-block"><small id="msg_category_slug" class="text-danger"></small></span>
                                                        </div>
                                                    </div>

                                                    <div class="row m-b-40">
                                                        <div class="col-md-6">
                                                            <label>Category Image</label><br><br>
                                                            @if($categoryDetail->category_id > 0)
                                                                <input type="hidden" name="old_image" value="{{ $categoryDetail->category_image }}">
                                                            @endif
                                                            <input type="file" class="dropify" data-default-file="{{ ($categoryDetail->category_image!="" && file_exists(public_path('/uploads/category/'.$categoryDetail->category_image))) ? asset('/uploads/category/'.$categoryDetail->category_image) : "" }}" id="category_image" name="category_image" aria-describedby="fileHelp">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Category Icon</label><br><br>
                                                            @if($categoryDetail->category_id > 0)
                                                                <input type="hidden" name="old_icon" value="{{ $categoryDetail->category_icon }}">
                                                            @endif
                                                            <input type="file" class="dropify" data-default-file="{{ ($categoryDetail->category_icon!="" && file_exists(public_path('/uploads/category/'.$categoryDetail->category_icon))) ? asset('/uploads/category/'.$categoryDetail->category_icon) : "" }}" id="category_icon" name="category_icon" aria-describedby="fileHelp">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12 m-b-40">
                                                        <textarea class="form-control" name="category_short_desc" id="category_short_desc">{{ $categoryDetail->category_short_desc }}</textarea>
                                                        <span class="bar"></span>
                                                        <label for="category_short_desc">Short Description</label>
                                                        <span class="help-block"><small id="msg_category_short_desc" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="category_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="category_desc" name="category_desc">{{ $categoryDetail->category_desc }}</textarea>
                                                        <script>
                                                            CKEDITOR.replace( 'category_desc',
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

                                                    <div class="col-md-6 m-t-20">
                                                        <div class="form-group col-md-12 m-b-40">
                                                            <input type="text" class="form-control" name="category_meta_title" id="category_meta_title" value="{{ $categoryDetail->category_meta_title }}">
                                                            <span class="bar"></span>
                                                            <label for="category_meta_title">Meta Title</label>
                                                            <span class="help-block"><small id="msg_category_meta_title" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group col-md-12 m-b-40">
                                                            <textarea class="form-control" name="category_meta_keyword" id="category_meta_keyword">{{ $categoryDetail->category_meta_keyword }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="category_meta_keyword">Meta Keyword</label>
                                                            <span class="help-block"><small id="msg_category_meta_keyword" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group col-md-12 m-b-40">
                                                            <textarea class="form-control" name="category_meta_desc" id="category_meta_desc">{{ $categoryDetail->category_meta_desc }}</textarea>
                                                            <span class="bar"></span>
                                                            <label for="category_meta_desc">Meta Description</label>
                                                            <span class="help-block"><small id="msg_category_meta_desc" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group col-md-12 m-b-40">
                                                            <select class="form-control p-0" name="category_status" id="category_status">
                                                                <option value="1" {{ ($categoryDetail->category_status == 1) ? 'selected="selected"' : '' }}>Active</option>
                                                                <option value="0" {{ ($categoryDetail->category_status == 0) ? 'selected="selected"' : '' }}>Inactive</option>
                                                            </select>
                                                            <span class="bar"></span>
                                                            <label for="category_status">Status</label>
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

        $('#category_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('category-create-slug') }}",
                type: "GET",
                data: {
                    'category_title': $(this).val()
                },
                success: function(response) {
                    $('#c_slug').addClass('focused')
                    $('#category_slug').val(response.slug);
                }
            });
        });

        $("#categoryFrm").on('submit', function(e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form = $('#categoryFrm')[0];
            var formData = new FormData(form);
            $("#categoryFrm").find(".has-error").removeClass("has-error");
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
                        window.location = "{{ url('admin/category-list') }}";
                    }
                }
            });
        });
    </script>
@endsection