@extends('admin.layouts.app')
@section('content')
    <!-- App hero header starts -->
    <div class="app-hero-header d-flex align-items-center">
        <!-- Breadcrumb starts -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Edit Category
            </li>
        </ol>
        <!-- Breadcrumb ends -->
    </div>
    <!-- App Hero header ends -->

    <!-- App body starts -->
    <form id="pagesFrm" method="post" action="{{ route('category-update') }}">
        <input type="hidden" name="category_id" value="{{ $categoryDetail->category_id }}">
        {{ csrf_field() }}
        <div class="app-body">
            <!-- Row starts -->
            <div class="row gx-3">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Edit Category</h5>
                        </div>
                        <div class="card-body">
                            <!-- Row starts -->
                            <div class="row gx-3">
                                <div class="col-xxl-6 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="roles">Category Parent</label>
                                        <select class="form-select" id="category_parent" name="category_parent" value="{{ $categoryDetail->category_id }}">
                                            <option value="0">Select as Parent</option>
                                            @foreach ($parentCategory as $category)
                                                <option value="{{ $category->category_id }}"
                                                    {{ $category->category_id == $categoryDetail->category_parent ? 'selected="selected"' : '' }}>
                                                    {{ $category->category_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="category_title">Category Title</label>
                                        <input type="text" class="form-control" id="category_title" name="category_title" placeholder="Enter Category Title" value="{{ $categoryDetail->category_title }}">
                                        <div class="invalid-feedback" id="msg_category_title"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="category_slug">Category Slug</label>
                                        <input type="text" class="form-control" id="category_slug" name="category_slug" placeholder="Enter Category Slug" value="{{ $categoryDetail->category_slug }}">
                                        <div class="invalid-feedback" id="msg_category_slug"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Category Image -->
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">Category Image</label>
                                    <div id="image-upload" class="dropzone text-center">
                                        <div class="dz-message">
                                            <button type="button" class="dz-button">
                                                Click here to upload your photo
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="category_image" id="category_image"
                                           value="{{ $categoryDetail->category_image ?? '' }}">
                                </div>

                                <!-- Category Icon -->
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">Category Icon</label>
                                    <div id="image-upload1" class="dropzone text-center">
                                        <div class="dz-message">
                                            <button type="button" class="dz-button">
                                                Click here to upload your photo
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="category_icon" id="category_icon"
                                           value="{{ $categoryDetail->category_icon ?? '' }}">
                                </div>

                                <!-- Category Fields -->
                                <div class="col-lg-6">
                                    <div class="row g-3">
                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="category_meta_title">Meta Title</label>
                                                <input type="text" class="form-control" id="category_meta_title" name="category_meta_title" placeholder="Enter Meta Title" value="{{ $categoryDetail->category_meta_title }}">
                                                <div class="invalid-feedback" id="msg_category_meta_title"></div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="category_meta_keyword">Meta Keyword</label>
                                                <input type="text" class="form-control" id="category_meta_keyword" name="category_meta_keyword" placeholder="Meta Keyword" value="{{ $categoryDetail->category_meta_keyword }}">
                                                <div class="invalid-feedback" id="msg_category_meta_keyword"></div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-12 col-lg-6 col-sm-6 mt-sm-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="category_meta_desc">Meta Description</label>
                                                <textarea type="text" class="form-control" id="category_meta_desc" name="category_meta_desc" rows="2">{{ $categoryDetail->category_meta_desc }}</textarea>
                                                <div class="invalid-feedback" id="msg_category_meta_desc"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="category_desc" class="form-label">Description</label>
                                <textarea id="category_desc" name="category_desc">{{ $categoryDetail->category_desc }}</textarea>
                                <script type="text/javascript">
                                    CKEDITOR.replace( 'category_desc',
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

                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('category-list') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            Update Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row ends -->
        </div>
    </form>
    <!-- App body ends -->
@endsection
@section('page-js')
    <script type="text/javascript">
        $('#category_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('category-create-slug') }}",
                type: "GET",
                data: {
                    'category_title': $(this).val()
                },
                success: function(response) {
                    $('#category_slug').val(response.slug);
                }
            });
        });

        $('#pagesFrm').submit(function(e) {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }

            $('#loading-wrapper').fadeIn(200);
            let formData = new FormData(this);
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').html('');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#loading-wrapper').fadeOut(200);
                    window.location.href = res.redirect_url;
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function(key, val) {
                            $('#' + key).addClass('is-invalid');
                            $('#msg_' + key).html(val[0]);
                        });
                    }
                    $('#loading-wrapper').fadeOut(200);
                }
            });
        });

        // Dropzone image upload
        Dropzone.autoDiscover = false;

        // Category Image
        let existingImage = "{{ $categoryDetail->category_image ?? '' }}";
        let dz = new Dropzone("#image-upload", {
            url: "{{ route('category-image-upload') }}",
            maxFiles: 1,
            acceptedFiles: ".jpg,.jpeg,.png,.webp",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            init: function() {
                let myDropzone = this;

                //PRELOAD EXISTING IMAGE
                if (existingImage) {
                    let mockFile = {
                        name: existingImage,
                        size: 12345,
                        accepted: true
                    };

                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit(
                            "thumbnail",
                            mockFile,
                            "{{ asset('uploads/category') }}/" + existingImage
                    );
                    myDropzone.emit("complete", mockFile);

                    myDropzone.files.push(mockFile);
                    document.getElementById('category_image').value = existingImage;
                }

                // Upload new image
                myDropzone.on("success", function(file, response) {
                    document.getElementById('category_image').value = response.filename;
                });

                // Remove image
                myDropzone.on("removedfile", function() {
                    document.getElementById('category_image').value = "";
                });
            }
        });

        // Category Icon
        let existingImage1 = "{{ $categoryDetail->category_icon ?? '' }}";
        let dz1 = new Dropzone("#image-upload1", {
            url: "{{ route('category-image-upload') }}",
            maxFiles: 1,
            acceptedFiles: ".jpg,.jpeg,.png,.webp",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            init: function() {
                let myDropzone = this;

                //PRELOAD EXISTING IMAGE
                if (existingImage1) {
                    let mockFile = {
                        name: existingImage1,
                        size: 12345,
                        accepted: true
                    };

                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit(
                            "thumbnail",
                            mockFile,
                            "{{ asset('uploads/category') }}/" + existingImage1
                    );
                    myDropzone.emit("complete", mockFile);

                    myDropzone.files.push(mockFile);
                    document.getElementById('category_icon').value = existingImage1;
                }

                // Upload new image
                myDropzone.on("success", function(file, response) {
                    document.getElementById('category_icon').value = response.filename;
                });

                // Remove image
                myDropzone.on("removedfile", function() {
                    document.getElementById('category_icon').value = "";
                });
            }
        });
    </script>
@endsection
