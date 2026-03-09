@extends("layouts.app")
@section('title', $bcategoryName->bcategory_meta_title ?? $pagesDetail->page_meta_title)
@section('keywords', $bcategoryName->bcategory_meta_keyword ?? $pagesDetail->page_meta_keyword)
@section('description', $bcategoryName->bcategory_meta_desc ?? $pagesDetail->page_meta_desc)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
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
                                <a href="{{ url('/my-account') }}" class="active d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ url('/image/profile-icon.svg') }}" alt="Profile Setting" style="margin-bottom: 0; margin-right: 10px; width: 20px;">
                                    <span style="display: inline-block; font-weight: 600;">Profile Setting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/seller-inquiry') }}" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ url('/image/u_plus-square.svg') }}" alt="Seller Inquiries" style="margin-bottom: 0; margin-right: 10px; width: 22px;">
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
                                    <img src="{{ url('/image/wallet-icon.svg') }}" alt="Logout" style="margin-bottom: 0; margin-right: 10px; width: 20px;">
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
                    {{--<div class="profile-detail-blk">
                        <ul>
                            <li class="active"><a href="coach-profile.html">Profile Details </a></li>
                        </ul>
                    </div>--}}
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
                                                    <img src="{{ url('/image/img-icon.svg') }}" id="imagePreview" class="img-fluid" alt="Upload">
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
                                                    <img src="{{ url('/image/img-icon.svg') }}" id="imagePreview" class="img-fluid" alt="Upload">
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
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Enter Email Address" value="{{ $customerDetail->customer_email }}" readonly>
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
                            <!-- <a href="javascript:;" class="btn btn-primary reset-profile">Reset</a> -->
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