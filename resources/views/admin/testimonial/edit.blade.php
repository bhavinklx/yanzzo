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
                Add Testimonial
            </li>
        </ol>
        <!-- Breadcrumb ends -->
    </div>
    <!-- App Hero header ends -->

    <!-- App body starts -->
    <form id="testimonialFrm" method="post" action="{{ route('testimonial-update') }}">
        <input type="hidden" name="testimonial_id" value="{{ $testimonialDetail->testimonial_id }}" >
        {{ csrf_field() }}
        <div class="app-body">
            <!-- Row starts -->
            <div class="row gx-3">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Add Testimonial</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Page Fields -->
                                <div class="col-lg-6">
                                    <div class="row g-3">
                                        <div class="col-xxl-6 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="testimonial_title">Testimonial Title</label>
                                                <input type="text" class="form-control" id="testimonial_title" name="testimonial_title" placeholder="Enter Testimonial Title" value="{{ $testimonialDetail->testimonial_title }}">
                                                <div class="invalid-feedback" id="msg_testimonial_title"></div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="testimonial_designation">Testimonial Designation</label>
                                                <input type="text" class="form-control" id="testimonial_designation" name="testimonial_designation" placeholder="Enter Testimonial Designation" value="{{ $testimonialDetail->testimonial_designation }}">
                                                <div class="invalid-feedback" id="msg_testimonial_designation"></div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-6 col-lg-6 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="testimonial_status">Status</label>
                                                <select class="form-select" id="testimonial_status" name="testimonial_status">
                                                    <option value="1" {{ ($testimonialDetail->testimonial_status == "1") ? 'selected="selected"' : '' }} >Active</option>
                                                    <option value="0" {{ ($testimonialDetail->testimonial_status == "0") ? 'selected="selected"' : '' }} >Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Page Image -->
                                <div class="col-lg-6">
                                    <form class="dropzone" id="image-upload" method="POST" action="{{ route('testimonial-image-upload') }}" enctype="multipart/form-data">
                                        @csrf
                                        <label class="form-label">Testimonial Image</label>
                                        <div class="dropzone dz-clickable" id="image-upload">
                                            <div class="dz-message">
                                                <button type="button" class="dz-button">
                                                    Click here to upload your photo
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <input type="hidden" name="testimonial_image" id="testimonial_image"
                                           value="{{ $testimonialDetail->testimonial_image ?? '' }}">
                                </div>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label class="form-label">Description</label>
                                <div id="fullEditor">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('testimonial-list') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            Add Testimonial
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
        $('#testimonialFrm').submit(function(e) {
            e.preventDefault();

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

        Dropzone.autoDiscover = false;
        let existingImage = "{{ $testimonialDetail->testimonial_image ?? '' }}";
        let dz = new Dropzone("#image-upload", {
            url: "{{ route('testimonial-image-upload') }}",
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
                            "{{ asset('uploads/testimonial') }}/" + existingImage
                    );
                    myDropzone.emit("complete", mockFile);

                    myDropzone.files.push(mockFile);
                    document.getElementById('testimonial_image').value = existingImage;
                }

                // Upload new image
                myDropzone.on("success", function(file, response) {
                    document.getElementById('testimonial_image').value = response.filename;
                });

                // Remove image
                myDropzone.on("removedfile", function() {
                    document.getElementById('testimonial_image').value = "";
                });
            }
        });
    </script>
@endsection