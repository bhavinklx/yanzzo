@extends("layouts.app")
@section('title', 'Sell Your Machine | ' . ($pagesDetail->page_meta_title ?? 'Yanzzo'))
@section('keywords', $pagesDetail->page_meta_keyword ?? '')
@section('description', $pagesDetail->page_meta_desc ?? '')
@section('canonical', url('/seller-inquiry'))

@section("content")
    <!-- Breadcrumb -->
    @if($pagesDetail->page_image!='' && file_exists(public_path('/uploads/pages/'.$pagesDetail->page_image)))
        @php
            $pageBanner = asset('/uploads/pages/'.$pagesDetail->page_image);
        @endphp
    @else
        @php
            $pageBanner = 'image/innerbanner.jpg';
        @endphp
    @endif
    <div class="breadcrumb breadcrumb-list mb-0" style="background-image: url({{ $pageBanner }});">
        <div class="container">
            <h1 class="text-white">Sell Your Machine</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>Sell Your Machine</li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Dashboard Menu -->
    <div class="dashboard-section coach-dash-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard-menu coaurt-menu-dash text-center">
                        <ul>
                            <li>
                                <a href="{{ url('/my-account') }}" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ asset('image/profile-icon.svg') }}') }}" alt="Profile Setting" style="margin-bottom: 0; margin-right: 10px; width: 20px;">
                                    <span style="display: inline-block; font-weight: 600;">Profile Setting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/seller-inquiry') }}" class="active d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ asset('image/u_plus-square.svg') }}') }}" alt="Seller Inquiries" style="margin-bottom: 0; margin-right: 10px; width: 22px;">
                                    <span style="display: inline-block; font-weight: 600;">Sell Your Machine</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-listing') }}" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <i class="feather-list" style="margin-right: 10px; font-size: 20px;"></i>
                                    <span style="display: inline-block; font-weight: 600;">My Machines</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/chat') }}" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <i class="feather-message-square" style="margin-right: 10px; font-size: 20px;"></i>
                                    <span style="display: inline-block; font-weight: 600;">Messages</span>
                                    @php
                                        $unreadDashboard = \App\Models\Chat::where('receiver_id', Session::get('customer_id'))->where('is_read', false)->count();
                                    @endphp
                                    @if($unreadDashboard > 0)
                                        <span class="badge badge-danger rounded-circle ms-2" style="background: #ff4d4d; font-size: 10px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">{{ $unreadDashboard }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="javascript: void (0)" onclick="return logout()" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ asset('image/wallet-icon.svg') }}') }}" alt="Logout" style="margin-bottom: 0; margin-right: 10px; width: 20px;">
                                    <span style="display: inline-block; font-weight: 600;">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dashboard Menu -->

    <!-- Page Content -->
    <div class="content court-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    @if (Session::has('successMsg'))
                        <div class="alert bg-success text-white alert-dismissible fade show" role="alert">
                            {{ Session::get('successMsg') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (Session::has('failedMsg'))
                        <div class="alert bg-danger text-white alert-dismissible fade show" role="alert">
                            {{ Session::get('failedMsg') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="profile-detail-group">
                        <div class="card">
                            <form id="sellerform" method="POST" action="{{ route('seller-inquiry-insert') }}">
                                @csrf
                                
                                <!-- Step 1: Category Selection -->
                                <div id="form-step-1">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <h4 class="fw-bold">Step 1: Choose Category</h4>
                                            <p class="text-muted small">Select the category and sub-category of the machine you want to sell.</p>
                                            <hr>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="input-space mb-0">
                                                <label for="category_id" class="form-label">Category *</label>
                                                <select id="category_id" name="category_id" class="form-select">
                                                    <option value="0">Select Category</option>
                                                    @foreach($categoryDetail as $category)
                                                        <option value="{{ $category->category_id }}">{{ $category->category_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="err_category_id" class="text-danger small mt-1"></div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="input-space mb-0">
                                                <label for="subcategory_id" class="form-label">Sub Category *</label>
                                                <select id="subcategory_id" name="subcategory_id" class="form-select">
                                                    <option value="0">Select Sub Category</option>
                                                </select>
                                            </div>
                                            <div id="err_subcategory_id" class="text-danger small mt-1"></div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <button type="button" onclick="goToStep(2)" class="btn btn-primary px-5 py-2">Next: Enter Details <i class="feather-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Machine Details -->
                                <div id="form-step-2" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4 class="fw-bold mb-0">Step 2: Machine Details</h4>
                                                <button type="button" onclick="goToStep(1)" class="btn btn-link text-muted text-decoration-none p-0"><i class="feather-arrow-left me-1"></i> Back</button>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-space">
                                                <label for="state_id" class="form-label">State *</label>
                                                <select id="state_id" name="state_id" class="form-select">
                                                    <option value="0">Select State</option>
                                                    @foreach($stateDetail as $state)
                                                        <option value="{{ $state->state_id }}">{{ $state->state_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-space">
                                                <label for="city_id" class="form-label">City *</label>
                                                <select id="city_id" name="city_id" class="form-select">
                                                    <option value="0">Select City</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="input-space">
                                                <label for="product_title" class="form-label">Machine Name / Listing Title *</label>
                                                <input type="text" id="product_title" name="product_title" class="form-control" placeholder="Enter Product Title">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-space">
                                                <label for="product_brand" class="form-label">Brand *</label>
                                                <input type="text" id="product_brand" name="product_brand" class="form-control" placeholder="Enter Brand">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3">
                                            <div class="input-space">
                                                <label for="product_price" class="form-label">Price *</label>
                                                <input type="text" id="product_price" name="product_price" class="form-control" placeholder="Enter Price (e.g. 1000)" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3">
                                            <div class="input-space">
                                                <label for="product_model" class="form-label">Model *</label>
                                                <input type="text" id="product_model" name="product_model" class="form-control" placeholder="Enter Model (YYYY) e.g. 2026" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-space">
                                                <label for="product_desc" class="form-label">Detailed Description *</label>
                                                <textarea id="product_desc" name="product_desc" class="form-control" rows="4" placeholder="Detailed info about condition, features, usage, etc..."></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-space">
                                                <label for="product_specification" class="form-label">Technical Specifications</label>
                                                <textarea id="product_specification" name="product_specification" class="form-control" rows="4" placeholder="Capacity, Power, etc..."></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="input-space">
                                                <label class="form-label">Machine Photos *</label>
                                                <div class="dropzone dz-clickable bg-light" id="product-images-upload" style="border: 2px dashed #ddd; border-radius: 10px;">
                                                    <div class="dz-message text-center">
                                                        <img src="{{ url('/public/image/img-icon.svg') }}" class="img-fluid mb-2" alt="Upload" style="width: 40px;">
                                                        <p class="mb-0">Click or drop here to upload photos</p>
                                                    </div>
                                                </div>
                                                <div id="product-images-container"></div>
                                                <div id="msg_product_images"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mt-4 text-end">
                                            <button type="button" id="submitBtn" class="btn btn-secondary btn-lg px-5">Submit Details</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

@section('page-js')
<script>
$(document).ready(function() {
    // Step Navigation
    window.goToStep = function(step) {
        if (step == 2) {
            // Validate Step 1
            var category = $('#category_id').val();
            var subcategory = $('#subcategory_id').val();
            var valid = true;

            $('#err_category_id, #err_subcategory_id').html('');
            
            if (category == 0) {
                $('#err_category_id').html('Please select a category');
                valid = false;
            }
            if (subcategory == 0) {
                $('#err_subcategory_id').html('Please select a sub-category');
                valid = false;
            }

            if (!valid) return;

            $('#form-step-1').fadeOut(200, function() {
                $('#form-step-2').fadeIn(200);
                window.scrollTo(0, 0);
            });
        } else {
            $('#form-step-2').fadeOut(200, function() {
                $('#form-step-1').fadeIn(200);
                window.scrollTo(0, 0);
            });
        }
    }

    // Dynamic Subcategory
    $('#category_id').change(function() {
        var category_id = $(this).val();
        if (category_id != 0) {
            $.ajax({
                url: "{{ route('seller-inquiry-get-subcategory') }}",
                type: "GET",
                data: { category_id: category_id },
                success: function(data) {
                    $('#subcategory_id').html('<option value="0">Select Sub Category</option>');
                    $.each(data, function(key, value) {
                        $('#subcategory_id').append('<option value="' + value.category_id + '">' + value.category_title + '</option>');
                    });
                }
            });
        } else {
            $('#subcategory_id').html('<option value="0">Select Sub Category</option>');
        }
    });

    // Dynamic City
    $('#state_id').change(function() {
        var state_id = $(this).val();
        if (state_id != 0) {
            $.ajax({
                url: "{{ route('seller-inquiry-get-city') }}",
                type: "GET",
                data: { state_id: state_id },
                success: function(data) {
                    $('#city_id').html('<option value="0">Select City</option>');
                    $.each(data, function(key, value) {
                        $('#city_id').append('<option value="' + value.city_id + '">' + value.city_name + '</option>');
                    });
                }
            });
        } else {
            $('#city_id').html('<option value="0">Select City</option>');
        }
    });

    // Dropzone image upload
    let productImagesDz = new Dropzone("#product-images-upload", {
        url: "{{ route('seller-inquiry-image-upload') }}",
        maxFiles: 20,
        acceptedFiles: ".jpg,.jpeg,.png,.webp",
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        init: function() {
            this.on("success", function(file, response) {
                $(file.previewElement).attr('data-filename', response.filename);
                $('#product-images-container').append('<input type="hidden" name="product_images[]" value="' + response.filename + '" id="img_' + response.filename.replace(/\.[^/.]+$/, "").replace(/[^a-zA-Z0-9]/g, "") + '">');
            });
            this.on("removedfile", function(file) {
                let filename = $(file.previewElement).attr('data-filename');
                if (filename) {
                    $.post("{{ route('seller-inquiry-image-delete') }}", {
                        filename: filename,
                        _token: "{{ csrf_token() }}"
                    });
                    $('#img_' + filename.replace(/\.[^/.]+$/, "").replace(/[^a-zA-Z0-9]/g, "")).remove();
                }
            });
        }
    });

    // Form Submit
    $('#submitBtn').click(function(e) {
        e.preventDefault();
        var form = $('#sellerform');
        var btn = $(this);
        
        btn.prop('disabled', true).html('<i class="feather-loader me-2"></i> Processing...');
        
        form.find(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: new FormData(form[0]),
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == "validation-error") {
                    var firstErrorField = null;
                    $.each(response.data, function(key, value) {
                        if (key == 'product_images') {
                            $('#product-images-upload').addClass("is-invalid");
                            $("#msg_product_images").html('<div class="invalid-feedback d-block"><small class="text-danger">' + value + '</small></div>');
                            if (!firstErrorField) firstErrorField = 'product-images-upload';
                        } else {
                            $("#" + key).addClass("is-invalid");
                            $("#" + key).after('<div class="invalid-feedback d-block"><small class="text-danger">' + value + '</small></div>');
                            if (!firstErrorField) firstErrorField = key;
                        }
                    });
                    btn.prop('disabled', false).text('Submit Details');
                    
                    if (firstErrorField) {
                        var errorElement = $("#" + firstErrorField);
                        // Check if error is in Step 1 but we are in Step 2
                        if (firstErrorField == 'category_id' || firstErrorField == 'subcategory_id') {
                            goToStep(1);
                        }
                        $('html, body').animate({
                            scrollTop: errorElement.offset().top - 150
                        }, 500);
                    }
                } else if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).text('Submit Details');
                if (xhr.status === 419) {
                    alert('Your session has expired. The page will now refresh so you can safely submit again.');
                    window.location.reload();
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });
});
</script>
@endsection
