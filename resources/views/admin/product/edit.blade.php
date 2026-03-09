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
            <li class="breadcrumb-item">
                <a href="{{ route('product-list') }}">Product List</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Edit Product
            </li>
        </ol>
        <!-- Breadcrumb ends -->
    </div>
    <!-- App Hero header ends -->

    <!-- App body starts -->
    <form id="productFrm" method="post" action="{{ route('product-update') }}">
        {{ csrf_field() }}
        <input type="hidden" name="product_id" value="{{ $productDetail->product_id }}">
        <div class="app-body">
            <!-- Row starts -->
            <div class="row gx-3">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Edit Product</h5>
                        </div>
                        <div class="card-body">
                            <!-- Row starts -->
                            <div class="row gx-3">
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="category_id">Category</label>
                                        <select class="form-select" id="category_id" name="category_id">
                                            <option value="0">Select Category</option>
                                            @foreach($categoryDetail as $category)
                                                <option value="{{ $category->category_id }}" {{ $productDetail->category_id == $category->category_id ? 'selected' : '' }}>{{ $category->category_title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="msg_category_id"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="subcategory_id">Sub Category</label>
                                        <select class="form-select" id="subcategory_id" name="subcategory_id">
                                            <option value="0">Select Sub Category</option>
                                            @foreach($subcategoryDetail as $subcategory)
                                                <option value="{{ $subcategory->category_id }}" {{ $productDetail->subcategory_id == $subcategory->category_id ? 'selected' : '' }}>{{ $subcategory->category_title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="msg_subcategory_id"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="state_id">State</label>
                                        <select class="form-select" id="state_id" name="state_id">
                                            <option value="0">Select State</option>
                                            @foreach($stateDetail as $state)
                                                <option value="{{ $state->state_id }}" {{ $productDetail->state_id == $state->state_id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="msg_state_id"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="city_id">City</label>
                                        <select class="form-select" id="city_id" name="city_id">
                                            <option value="0">Select City</option>
                                            @foreach($cityDetail as $city)
                                                <option value="{{ $city->city_id }}" {{ $productDetail->city_id == $city->city_id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="msg_city_id"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_title">Product Title</label>
                                        <input type="text" class="form-control" id="product_title" name="product_title" placeholder="Enter Product Title" value="{{ $productDetail->product_title }}">
                                        <div class="invalid-feedback" id="msg_product_title"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_slug">Product Slug</label>
                                        <input type="text" class="form-control" id="product_slug" name="product_slug" placeholder="Enter Product Slug" value="{{ $productDetail->product_slug }}">
                                        <div class="invalid-feedback" id="msg_product_slug"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="customer_id">Customer</label>
                                        <select class="form-select" id="customer_id" name="customer_id">
                                            <option value="0">Select Customer</option>
                                            @foreach($customerDetail as $customer)
                                                <option value="{{ $customer->customer_id }}" {{ $productDetail->customer_id == $customer->customer_id ? 'selected' : '' }}>{{ $customer->customer_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="msg_customer_id"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_price">Price</label>
                                        <input type="text" class="form-control" id="product_price" name="product_price" placeholder="Enter Product Price" value="{{ $productDetail->product_price }}">
                                        <div class="invalid-feedback" id="msg_product_price"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_brand">Brand</label>
                                        <input type="text" class="form-control" id="product_brand" name="product_brand" placeholder="Enter Brand" value="{{ $productDetail->product_brand }}">
                                        <div class="invalid-feedback" id="msg_product_brand"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_model">Model</label>
                                        <input type="text" class="form-control" id="product_model" name="product_model" placeholder="Enter Model" value="{{ $productDetail->product_model }}">
                                        <div class="invalid-feedback" id="msg_product_model"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_meta_title">Meta Title</label>
                                        <input type="text" class="form-control" id="product_meta_title" name="product_meta_title" placeholder="Enter Meta Title" value="{{ $productDetail->product_meta_title }}">
                                        <div class="invalid-feedback" id="msg_product_meta_title"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_meta_keyword">Meta Keyword</label>
                                        <input type="text" class="form-control" id="product_meta_keyword" name="product_meta_keyword" placeholder="Meta Keyword" value="{{ $productDetail->product_meta_keyword }}">
                                        <div class="invalid-feedback" id="msg_product_meta_keyword"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-xxl-6 col-lg-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="product_short_desc" class="form-label">Short Description</label>
                                        <textarea id="product_short_desc" name="product_short_desc" class="form-control" rows="2">{{ $productDetail->product_short_desc }}</textarea>
                                        <div class="invalid-feedback" id="msg_product_short_desc"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-lg-12 col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="product_meta_desc">Meta Description</label>
                                        <textarea type="text" class="form-control" id="product_meta_desc" name="product_meta_desc" rows="2">{{ $productDetail->product_meta_desc }}</textarea>
                                        <div class="invalid-feedback" id="msg_product_meta_desc"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Product Image</label>
                                        <div class="dropzone dz-clickable" id="product-images-upload">
                                            <div class="dz-message">
                                                <button type="button" class="dz-button">
                                                    Click here to upload product photos
                                                </button>
                                            </div>
                                        </div>
                                        <div id="product-images-container"></div>
                                        <div class="row mt-3" id="existing-images">
                                            @foreach($productDetail->pimages as $pimage)
                                                <div class="col-sm-2 mb-3 text-center" id="pimage_{{ $pimage->pimage_id }}">
                                                    <img src="{{ asset('uploads/product/' . $pimage->pimage_image) }}" class="img-thumbnail mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteExistingImage({{ $pimage->pimage_id }})">Delete</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6 mb-3">
                                    <label for="product_desc" class="form-label">Description</label>
                                    <textarea id="product_desc" name="product_desc">{{ $productDetail->product_desc }}</textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace( 'product_desc',
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
                                <div class="col-sm-6 mb-3">
                                    <label for="product_specification" class="form-label">Specification</label>
                                    <textarea id="product_specification" name="product_specification">{{ $productDetail->product_specification }}</textarea>
                                    <script type="text/javascript">
                                        CKEDITOR.replace( 'product_specification',
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

                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('product-list') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            Update Product
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
        $('#state_id').change(function() {
            var state_id = $(this).val();
            if (state_id != 0) {
                $.ajax({
                    url: "{{ route('product-get-city') }}",
                    type: "GET",
                    data: { 'state_id': state_id },
                    success: function(response) {
                        $('#city_id').html('<option value="0">Select City</option>');
                        $.each(response, function(key, val) {
                            $('#city_id').append('<option value="' + val.city_id + '">' + val.city_name + '</option>');
                        });
                    }
                });
            } else {
                $('#city_id').html('<option value="0">Select City</option>');
            }
        });

        $('#category_id').change(function() {
            var category_id = $(this).val();
            if (category_id != 0) {
                $.ajax({
                    url: "{{ route('product-get-subcategory') }}",
                    type: "GET",
                    data: {
                        'category_id': category_id
                    },
                    success: function(response) {
                        $('#subcategory_id').html('<option value="0">Select Sub Category</option>');
                        $.each(response, function(key, val) {
                            $('#subcategory_id').append('<option value="' + val.category_id + '">' + val.category_title + '</option>');
                        });
                    }
                });
            } else {
                $('#subcategory_id').html('<option value="0">Select Sub Category</option>');
            }
        });

        $('#product_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('product-create-slug') }}",
                type: "GET",
                data: {
                    'product_title': $(this).val()
                },
                success: function(response) {
                    $('#product_slug').val(response.slug);
                }
            });
        });

        $('#productFrm').submit(function(e) {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#loading-wrapper').fadeIn(200);
            let formData = new FormData(this);
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').html('');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
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
        let productImagesDz = new Dropzone("#product-images-upload", {
            url: "{{ route('product-image-upload') }}",
            maxFiles: 20,
            acceptedFiles: ".jpg,.jpeg,.png,.webp",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        productImagesDz.on("success", function(file, response) {
            $(file.previewElement).attr('data-filename', response.filename);
            $('#product-images-container').append('<input type="hidden" name="product_images[]" value="' + response.filename + '" id="img_' + response.filename.replace(/\.[^/.]+$/, "") + '">');
        });

        productImagesDz.on("removedfile", function(file) {
            let filename = $(file.previewElement).attr('data-filename');
            if (filename) {
                $.post("{{ route('product-image-delete') }}", {
                    filename: filename,
                    _token: "{{ csrf_token() }}"
                });
                $('#img_' + filename.replace(/\.[^/.]+$/, "")).remove();
            }
        });

        function deleteExistingImage(pimageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                $.post("{{ route('product-image-delete') }}", {
                    pimage_id: pimageId,
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    if (res.success) {
                        $('#pimage_' + pimageId).remove();
                    }
                });
            }
        }
    </script>
@endsection
