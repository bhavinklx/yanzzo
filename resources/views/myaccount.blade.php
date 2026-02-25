@extends("layouts.app")
@section('title', $bcategoryName->bcategory_meta_title ?? $pagesDetail->page_meta_title)
@section('keywords', $bcategoryName->bcategory_meta_keyword ?? $pagesDetail->page_meta_keyword)
@section('description', $bcategoryName->bcategory_meta_desc ?? $pagesDetail->page_meta_desc)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
        <div class="container">
            <h1 class="text-white">{{ $pagesDetail->page_title ?? '' }}</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $pagesDetail->page_title ?? '' }}</li>
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
                                <a href="{{ url('/my-account') }}" class="active">
                                    <img src="{{ url('/public/img/icons/profile-icon.svg') }}" alt="Profile Setting">
                                    <span>Profile Setting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-booking') }}">
                                    <img src="{{ url('/public/img/icons/booking-icon.svg') }}" alt="My Bookings">
                                    <span>My Bookings</span>
                                </a>
                            </li>
                            {{--<li>
                                <a href="coach-wallet.html">
                                    <img src="{{ url('/public/img/icons/wallet-icon.svg') }}" alt="Change Password">
                                    <span>Change Password</span>
                                </a>
                            </li>--}}
                            <li>
                                <a href="javascript: void (0)" onclick="return logout()">
                                    <img src="{{ url('/public/img/icons/wallet-icon.svg') }}" alt="Logout">
                                    <span>Logout</span>
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
                    {{--<div class="profile-detail-blk">
                        <ul>
                            <li class="active"><a href="coach-profile.html">Profile Details </a></li>
                        </ul>
                    </div>--}}
                    <div class="profile-detail-group">
                        <div class="card ">
                            <form method="post" id="profileFrm" enctype="multipart/form-data">
                                <div class="row">
                                    {{--<div class="col-md-12">
                                        <div class="file-upload-text">
                                            <div class="file-upload">
                                                @if ($customerDetail->customer_image!="" && file_exists(public_path('/uploads/customer/'.$customerDetail->customer_image)))
                                                    <img src="{{ asset('/uploads/customer/'.$customerDetail->customer_image) }}" id="imagePreview" class="img-fluid" alt="Upload">
                                                    <input type="hidden" name="old_image" value="{{ $customerDetail->customer_image }}">
                                                @else
                                                    <img src="{{ url('/public/img/icons/img-icon.svg') }}" id="imagePreview" class="img-fluid" alt="Upload">
                                                    <p>Upload Photo</p>
                                                @endif
                                                <span>
                                                    <i class="feather-edit-3"></i>
                                                    <input type="file" id="customer_image" name="customer_image">
                                                </span>
                                            </div>
                                            <h5>Upload a logo with a minimum size of 150 * 150 pixels (JPG, PNG, SVG).</h5>
                                        </div>

                                        <span class="badge bg-info"><i class="feather-check-square me-1"></i>Pending</span>
                                    </div>--}}
                                    <div class="col-md-12">
                                        <div class="file-upload-text">
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <label class="form-label">Image</label>
                                                @if(isset($morderDetail))
                                                    @php
                                                        $style = $title = "";
                                                        if (isset($morderDetail) && $morderDetail->membership_id==1) {
                                                            $style  = 'background-color: #343a40 !important';
                                                            $title  = $membershipNameArray[$morderDetail->membership_id];
                                                        } else if (isset($morderDetail) && $morderDetail->membership_id==2) {
                                                            $style  = 'background-color: #fb9678 !important';
                                                            $title  = $membershipNameArray[$morderDetail->membership_id];
                                                        } else if (isset($morderDetail) && $morderDetail->membership_id==3) {
                                                            $style  = 'background-color: #e83e8c !important';
                                                            $title  = $membershipNameArray[$morderDetail->membership_id];
                                                        }
                                                    @endphp
                                                    <span class="badge" style="{{ $style }}">Membership: {{ $title }} <br>Expire on {{ date('d-m-Y', strtotime($morderDetail->msorder_end_date)) }}</span>
                                                @endif
                                            </div>
                                            <div class="file-upload">
                                                @if ($customerDetail->customer_image!="" && file_exists(public_path('/uploads/customer/'.$customerDetail->customer_image)))
                                                    <img src="{{ asset('/uploads/customer/'.$customerDetail->customer_image) }}" id="imagePreview" class="img-fluid" alt="Upload">
                                                    <input type="hidden" name="old_image" value="{{ $customerDetail->customer_image }}">
                                                @else
                                                    <img src="{{ url('/public/img/icons/img-icon.svg') }}" id="imagePreview" class="img-fluid" alt="Upload">
                                                    <p>Upload Photo</p>
                                                @endif
                                                <span>
                                                    <i class="feather-edit-3"></i>
                                                    <input type="file" id="customer_image" name="customer_image">
                                                </span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <h5 class="mb-0">Upload a logo with a minimum size of 150 * 150 pixels (JPG, PNG, SVG).</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Name" value="{{ $customerDetail->customer_name }}">
                                        </div>
                                        <div id="msg_customer_name"></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label for="name" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Enter Email Address" value="{{ $customerDetail->customer_email }}">
                                        </div>
                                        <div id="msg_customer_email"></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label for="name" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="customer_mobile" name="customer_mobile" placeholder="Enter Phone Number" value="{{ $customerDetail->customer_mobile }}" readonly>
                                        </div>
                                        <div id="msg_customer_mobile"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="save-changes text-end">
                            <a href="javascript:;" class="btn btn-primary reset-profile">Reset</a>
                            <button type="button" id="profileBtn" name="profileBtn" class="btn btn-secondary">Save Change</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Content -->
@endsection

@section('page-js')
    <script type="text/javascript">
        document.getElementById('customer_image').addEventListener('change', function (event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('imagePreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        $('#profileBtn').on('click', function(e)
        {
            e.preventDefault();
            var form    = $('#profileFrm')[0];
            var formData= new FormData(form);
            $("#profileFrm").find(".is-invalid").removeClass("is-invalid");
            $(".invalid-feedback").remove();
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url: '{{ route('my-account-update') }}',
                cache : false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data : formData,
                success: function(response){
                    //alert(response.redirect_url)
                    if(response.status == "validation-error")
                    {
                        $.each(response.data, function (key, value)
                        {
                            $("#"+key).addClass("is-invalid");
                            //$("#"+key).next().html("<small class='text-danger'>" + value + "</small>");
                            $("#msg_"+key).html('<div class="invalid-feedback d-block">' + value + '</div>');
                        });
                    }
                    else if (response.redirect_url !== undefined)
                    {
                        window.location = "{{ url('my-account') }}";
                    }
                },
            });
        });
    </script>
@endsection