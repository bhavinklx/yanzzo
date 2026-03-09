@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? 'My Listings')
@section('keywords', $pagesDetail->page_meta_keyword ?? 'My Listings')
@section('description', $pagesDetail->page_meta_desc ?? 'My Listings')
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    @if(isset($pagesDetail->page_image) && $pagesDetail->page_image!='' && file_exists(public_path('/uploads/pages/'.$pagesDetail->page_image)))
        @php
            $pageBanner = asset('/uploads/pages/'.$pagesDetail->page_image);
        @endphp
    @else
        @php
            $pageBanner = asset('image/innerbanner.jpg');
        @endphp
    @endif
    <div class="breadcrumb breadcrumb-list mb-0" style="background-image: url({{ $pageBanner }});">
        <div class="container">
            <h1 class="text-white">{{ $pagesDetail->page_title ?? 'My Listings' }}</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>My Listing</li>
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
                                <a href="{{ url('/my-listings') }}" class="active d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
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
                    <div class="court-tab-content">
                        <div class="card card-tableset">
                            <div class="card-body">
                                <div class="coache-head-blk">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6">
                                            <div class="court-table-head">
                                                <h4>My Machine Listing</h4>
                                                <p>Manage your uploaded machines and their status</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 text-end">
                                            <div class="table-search-top">
                                                <div id="tablefilter"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-borderless datatable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Machine Name</th>
                                                <th>Listing ID</th>
                                                <th>Date & Time</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Sold Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($productDetail as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ url('machines/' . $product->product_slug) }}" class="me-2">
                                                            @if($product->pimages->count() > 0)
                                                                <img src="{{ asset('uploads/product/'.$product->pimages[0]->pimage_image) }}" alt="{{ $product->product_title }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                                                            @else
                                                                <img src="{{ asset('image/product-img.jpg') }}" alt="User" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                                                            @endif
                                                        </a>
                                                        <div style="max-width: 250px;">
                                                            <a href="{{ url('machines/' . $product->product_slug) }}" class="machine-title" style="font-size: 15px; display: block; line-height: 1.3;">{{ $product->product_title }}</a>
                                                            <span class="machine-subtitle" style="font-size: 12px;">{{ $product->city->city_name ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span style="font-weight: 600; color: #192335; font-size: 14px;">{{ $product->product_listing_id }}</span>
                                                </td>
                                                <td class="table-date-time">
                                                    <h4 style="font-size: 14px; margin-bottom: 0; font-weight: 600;">{{ date('D, M d, Y', strtotime($product->created_at)) }}</h4>
                                                    <span class="text-muted" style="font-size: 12px;">{{ date('h:i A', strtotime($product->created_at)) }}</span>
                                                </td>
                                                <td>
                                                    <span class="pay-dark" style="font-weight: 700; color: #192335; font-size: 14px;">₹{{ number_format($product->product_price, 2) }}</span>
                                                </td>
                                                <td>
                                                    @if($product->product_status == '1')
                                                        <span class="badge badge-success-bg"><i class="feather-check-circle me-1"></i> Approved</span>
                                                    @else
                                                        <span class="badge badge-warning-bg"><i class="feather-info me-1"></i> Pending</span>
                                                    @endif
                                                </td>
                                                <td class="paid-edit">
                                                    @if($product->product_is_sold == '1')
                                                        <span class="badge badge-danger-bg"><i class="feather-x-circle me-1"></i> Sold</span>
                                                    @else
                                                        <span class="badge badge-success-light-bg"><i class="feather-box me-1"></i> Available</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        @if($product->product_is_sold == '0')
                                                            <a href="javascript:void(0);" onclick="markAsSold({{ $product->product_id }})" class="btn-accept">
                                                                <i class="feather-check-circle"></i> Accept
                                                            </a>
                                                        @endif
                                                        <a href="{{ url('machines/' . $product->product_slug) }}" class="btn-cancel ms-2" target="_blank">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="tablelength"></div>
                                </div>
                                <div class="col-md-6">
                                    <div id="tablepage"></div>
                                </div>
                            </div>
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
    function markAsSold(id) {
        if(confirm('Are you sure you want to mark this machine as sold?')) {
            $.ajax({
                url: '{{ route("mark-as-sold") }}',
                type: 'POST',
                data: {
                    product_id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.status == 'success') {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }
</script>
@endsection
