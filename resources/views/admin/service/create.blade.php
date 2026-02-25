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
                Add Service
            </li>
        </ol>
        <!-- Breadcrumb ends -->
    </div>
    <!-- App Hero header ends -->

    <!-- App body starts -->
    <form id="serviceFrm" method="post" action="{{ route('service-insert') }}">
        {{ csrf_field() }}
        <div class="app-body">
            <!-- Row starts -->
            <div class="row gx-3">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Add Service</h5>
                        </div>
                        <div class="card-body">
                            <!-- Row starts -->
                            <div class="row gx-3">
                                <div class="col-xxl-6 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="service_title">Service Title</label>
                                        <input type="text" class="form-control" id="service_title" name="service_title" placeholder="Enter Service Title">
                                        <div class="invalid-feedback" id="msg_service_title"></div>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="service_slug">Service Slug</label>
                                        <input type="text" class="form-control" id="service_slug" name="service_slug" placeholder="Enter Service Slug">
                                        <div class="invalid-feedback" id="msg_service_slug"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Page Image -->
                                <div class="col-lg-6">
                                    <form class="dropzone" id="image-upload" method="POST" action="{{ route('service-image-upload') }}" enctype="multipart/form-data">
                                        @csrf
                                        <label class="form-label">Service Image</label>
                                        <div class="dropzone dz-clickable" id="image-upload">
                                            <div class="dz-message">
                                                <button type="button" class="dz-button">
                                                    Click here to upload your photo
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <input type="hidden" name="service_image" id="service_image">
                                </div>

                                <!-- Page Fields -->
                                <div class="col-lg-6">
                                    <div class="row g-3">
                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="service_meta_title">Meta Title</label>
                                                <input type="text" class="form-control" id="service_meta_title" name="service_meta_title" placeholder="Enter Meta Title">
                                                <div class="invalid-feedback" id="msg_service_meta_title"></div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="service_meta_keyword">Meta Keyword</label>
                                                <input type="text" class="form-control" id="service_meta_keyword" name="service_meta_keyword" placeholder="Meta Keyword">
                                                <div class="invalid-feedback" id="msg_service_meta_keyword"></div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-12 col-lg-6 col-sm-6 mt-sm-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="service_meta_desc">Meta Description</label>
                                                <textarea type="text" class="form-control" id="service_meta_desc" name="service_meta_desc" rows="2"></textarea>
                                                <div class="invalid-feedback" id="msg_service_meta_desc"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="service_desc" class="form-label">Description</label>
                                <textarea id="service_desc" name="service_desc"></textarea>
                                <script type="text/javascript">
                                    CKEDITOR.replace( 'service_desc',
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
                                        <a href="{{ route('service-list') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            Add Service
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
        $('#service_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('service-create-slug') }}",
                type: "GET",
                data: {
                    'service_title': $(this).val()
                },
                success: function(response) {
                    $('#service_slug').val(response.slug);
                }
            });
        });

        $('#serviceFrm').submit(function(e) {
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
        let dz = new Dropzone("#image-upload", {
            url: "{{ route('service-image-upload') }}",
            maxFiles: 1,
            acceptedFiles: ".jpg,.jpeg,.png,.webp",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        dz.on("success", function(file, response) {
            document.getElementById('service_image').value = response.filename;
        });
    </script>
@endsection