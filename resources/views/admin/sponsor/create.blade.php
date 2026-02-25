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
                Add Sponsor
            </li>
        </ol>
        <!-- Breadcrumb ends -->
    </div>
    <!-- App Hero header ends -->

    <!-- App body starts -->
    <form id="sponsorFrm" method="post" action="{{ route('sponsor-insert') }}">
        {{ csrf_field() }}
        <div class="app-body">
            <!-- Row starts -->
            <div class="row gx-3">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Add Sponsor</h5>
                        </div>
                        <div class="card-body">
                            <!-- Row starts -->
                            <div class="row gx-3 mb-3">
                                <!-- Page Fields -->
                                <div class="col-lg-6">
                                    <div class="row g-3">
                                        <div class="col-xxl-12 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="sponsor_title">Sponsor Title</label>
                                                <input type="text" class="form-control" id="sponsor_title" name="sponsor_title" placeholder="Enter Sponsor Title">
                                                <div class="invalid-feedback" id="msg_sponsor_title"></div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-12 col-lg-4 col-sm-6" style="margin-top: 2rem !important">
                                            <div class="mb-3">
                                                <label class="form-label" for="sponsor_status">Status</label>
                                                <select class="form-select" id="sponsor_status" name="sponsor_status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <form class="dropzone" id="image-upload" method="POST" action="{{ route('sponsor-image-upload') }}" enctype="multipart/form-data">
                                        @csrf
                                        <label class="form-label">Sponsor Image</label>
                                        <div class="dropzone dz-clickable" id="image-upload">
                                            <div class="dz-message">
                                                <button type="button" class="dz-button">
                                                    Click here to upload your photo
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <input type="hidden" name="sponsor_image" id="sponsor_image">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('sponsor-list') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            Add Sponsor
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
        $('#sponsorFrm').submit(function(e) {
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

        // Dropzone image upload
        Dropzone.autoDiscover = false;
        let dz = new Dropzone("#image-upload", {
            url: "{{ route('sponsor-image-upload') }}",
            maxFiles: 1,
            acceptedFiles: ".jpg,.jpeg,.png,.webp",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        dz.on("success", function(file, response) {
            document.getElementById('sponsor_image').value = response.filename;
        });
    </script>
@endsection