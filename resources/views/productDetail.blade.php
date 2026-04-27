@extends("layouts.app")
@section('title', $productDetail->product_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $productDetail->product_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $productDetail->product_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    @if($pagesDetail->page_image!='' && file_exists(public_path('/uploads/pages/'.$pagesDetail->page_image)))
        @php
            $pageBanner = asset('/uploads/pages/'.$pagesDetail->page_image);
        @endphp
    @else
        @php
            $pageBanner = asset('image/innerbanner.jpg');
        @endphp
    @endif
    <!-- Banner -->
    <section class="breadcrumb breadcrumb-list mb-0" style="background-image: url({{ $pageBanner }});"></section>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <!-- Row -->
            <div class="row move-top">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                    <div class="dull-bg corner-radius-10 coach-info">
                        <div class="gallery-wrap">
                            <div class="owl-carousel gallery-slider owl-theme">
                                @if(count($productDetail->pimages) > 0)
                                    @foreach($productDetail->pimages as $image)
                                        <div class="gallery-widget-item">
                                            <a class="corner-radius-10" href="{{ asset('uploads/product/' . $image->pimage_image) }}" data-fancybox="gallery2">
                                                <img class="img-fluid corner-radius-10" alt="{{ $productDetail->product_title }}" src="{{ asset('uploads/product/' . $image->pimage_image) }}">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="info w-100">
                            <div class="d-sm-flex justify-content-between align-items-start">
                                <h3 class="d-flex align-items-center justify-content-start mb-0">
                                    {{ $productDetail->product_title }}
                                    @if($productDetail->product_is_sold == '1')
                                        <span class="badge bg-danger ms-2 text-white" style="font-size: 14px; vertical-align: middle; text-transform: uppercase; padding: 5px 12px; display: inline-flex; align-items: center; border-radius: 8px;">
                                            <i class="feather-x-circle me-1" style="font-size: 16px;"></i> SOLD OUT
                                        </span>
                                    @endif
                                </h3>
                            </div>
                            <p>{{ $productDetail->product_short_desc }}</p>
                            <ul class="d-sm-flex align-items-center listing-details-group">
                                <li class="d-flex align-items-center me-3">
                                    <div class="white-bg d-flex align-items-center review shadow-sm mb-0">
                                        <span class="dark-yellow-bg d-flex align-items-center justify-content-center me-2">
                                            <i class="feather-eye"></i>
                                        </span>
                                        <span class="text-dark fw-bold">{{ $productDetail->product_view }} Views</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="white-bg d-flex align-items-center review shadow-sm mb-0">
                                        <span class="bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 28px; border-radius: 5px; padding: 5px;">
                                            <i class="feather-map-pin"></i>
                                        </span>
                                        <span class="text-dark fw-bold">{{ $productDetail->city->city_name ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center ms-sm-auto">
                                    <button type="button" class="btn btn-favourite {{ $isFavourite ? 'active' : '' }}" onclick="toggleFavourite({{ $productDetail->product_id }}, this)">
                                        <i class="{{ $isFavourite ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                        <span>Favourite</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <!-- Modern Spec Cards -->
                        <div class="spec-cards-grid mt-4">
                            <!-- Price -->
                            <div class="spec-card">
                                <div class="spec-card__body">
                                    <span class="spec-card__label">Asking Price</span>
                                    @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                        <span class="spec-card__value spec-card__value--price">₹ {{ number_format($productDetail->product_price) }}/-</span>
                                    @else
                                        <span class="spec-card__value spec-card__value--masked">₹ XXXXX/-</span>
                                        <a href="javascript:void(0)" class="spec-card__login-link" onclick="return signin_popup()">
                                            <i class="feather-lock me-1"></i>Login to View
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if($productDetail->product_brand)
                            <div class="spec-card">
                                <div class="spec-card__body">
                                    <span class="spec-card__label">Brand</span>
                                    <span class="spec-card__value">{{ $productDetail->product_brand }}</span>
                                </div>
                            </div>
                            @endif

                            @if($productDetail->product_model)
                            <div class="spec-card">
                                <div class="spec-card__body">
                                    <span class="spec-card__label">Model / Year</span>
                                    <span class="spec-card__value">{{ $productDetail->product_model }}</span>
                                </div>
                            </div>
                            @endif

                            @if($productDetail->product_location)
                            <div class="spec-card">
                                <div class="spec-card__body">
                                    <span class="spec-card__label">Location</span>
                                    <span class="spec-card__value">{{ $productDetail->city->city_name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            @endif

                            @if($productDetail->product_listing_id)
                            <div class="spec-card">
                                <div class="spec-card__body">
                                    <span class="spec-card__label">Listing ID</span>
                                    <span class="spec-card__value">
                                        <span class="spec-tag">{{ $productDetail->product_listing_id }}</span>
                                    </span>
                                </div>
                            </div>
                            @endif

                            @if($productDetail->category)
                            <div class="spec-card">
                                <div class="spec-card__body">
                                    <span class="spec-card__label">Category</span>
                                    <span class="spec-card__value">{{ $productDetail->category->category_title }}</span>
                                </div>
                            </div>
                            @endif

                            @if($productDetail->subCategory)
                            <div class="spec-card">
                                <div class="spec-card__body">
                                    <span class="spec-card__label">Sub Category</span>
                                    <span class="spec-card__value">{{ $productDetail->subCategory->category_title }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="venue-options white-bg mb-4">
                        <ul class="clearfix">
                            <li class="active"><a href="#specification">Specification</a></li>
                            <li><a href="#description">Description</a></li>
                        </ul>
                    </div>

                    <!-- Accordian Contents -->
                    <div class="accordion" id="accordionPanel">
                        <div class="accordion-item mb-4" id="specification">
                            <h4 class="accordion-header" id="panelsStayOpen-short-bio">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Specification
                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-short-bio" data-bs-parent="#accordionPanel">
                                <div class="accordion-body">
                                    <div class="text show-more-height">
                                        {!! $productDetail->product_specification !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4" id="description">
                            <h4 class="accordion-header" id="panelsStayOpen-lesson-with-me">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                    Description
                                </button>
                            </h4>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-lesson-with-me" data-bs-parent="#accordionPanel">
                                <div class="accordion-body">
                                    <div class="text show-more-height">
                                        {!! $productDetail->product_desc !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Accordian Contents -->
                </div>

                <aside class="col-12 col-sm-12 col-md-12 col-lg-4 theiaStickySidebar">
                    {{--<div class="white-bg">
                        <div class="shadow-card">
                            <h2>Login</h2>
                            <p>Login to view Price And Seller Details</p>
                            <form action="user-dashboard.html">
                                <div class="form-group">
                                    <div class="group-img">
                                        <i class="feather-user"></i>
                                        <input type="text" class="form-control" placeholder="Email / Username">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="pass-group group-img">
                                        <i class="toggle-password feather-eye-off"></i>
                                        <input type="password" class="form-control pass-input" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group d-sm-flex align-items-center justify-content-between">
                                    <div class="form-check form-switch d-flex align-items-center justify-content-start">
                                        <input class="form-check-input" type="checkbox" id="user-pass">
                                        <label class="form-check-label" for="user-pass">Remember Password</label>
                                    </div>
                                    <span><a href="forgot-password.html" class="forgot-pass">Forgot Password</a></span>
                                </div>
                                <button class="btn btn-secondary register-btn d-inline-flex justify-content-center align-items-center w-100 btn-block" type="submit">Sign In<i class="feather-arrow-right-circle ms-2"></i></button>
                            </form>
                        </div>
                    </div>--}}
                    <div class="white-bg listing-owner">
                        <h4 class="border-bottom">Seller Details</h4>
                        @if(!session()->has('customer_id') || session()->get('customer_id') <= 0)
                            <p>Login to view Price And Seller Details</p>
                        @endif
                        @if($productDetail->customer)
                            <ul>
                                <li>
                                    <div class="owner-info">
                                        @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                            <h5>{{ $productDetail->customer->customer_name }}</h5>
                                            <p><i class="feather-mail"></i><span>{{ $productDetail->customer->customer_email }}</span></p>
                                            <p>
                                                <i class="feather-phone"></i>
                                                <span>+91 {{ $productDetail->customer->customer_mobile }}</span>
                                            </p>
                                            @if($productDetail->customer->customer_is_whatsapp == '1')
                                                <p>
                                                    <a href="https://wa.me/91{{ $productDetail->customer->customer_mobile }}?text={{ urlencode('Hello, I am interested in ' . $productDetail->product_title) }}" target="_blank">
                                                        <i class="fab fa-whatsapp text-success"></i>
                                                        <span>+91 {{ $productDetail->customer->customer_mobile }} <span class="text-success fw-bold">(Click here to WhatsApp)</span></span>
                                                    </a>
                                                </p>
                                            @endif
                                        @else
                                            @php
                                                $name = $productDetail->customer->customer_name;
                                                $maskedName = substr($name, 0, 1) . str_repeat('*', strlen($name) - 2) . substr($name, -1);

                                                $email = $productDetail->customer->customer_email;
                                                $emailParts = explode('@', $email);
                                                $maskedEmail = substr($emailParts[0], 0, 2) . '****@' . $emailParts[1];

                                                $mobile = $productDetail->customer->customer_mobile;
                                                $maskedMobile = substr($mobile, 0, 2) . '******' . substr($mobile, -2);
                                            @endphp
                                            <h5>{{ $maskedName }}</h5>
                                            <p><i class="feather-mail"></i><span>{{ $maskedEmail }}</span></p>
                                            <p><i class="feather-phone"></i><span>+91 {{ $maskedMobile }}</span></p>
                                            @if($productDetail->customer->customer_is_whatsapp == '1')
                                                <p>
                                                    <a href="javascript:void(0)" onclick="return signin_popup()">
                                                        <i class="fab fa-whatsapp text-success"></i>
                                                        <span>+91 {{ $maskedMobile }} <span class="text-success fw-bold">(Click here to WhatsApp)</span></span>
                                                    </a>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                    @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                        @if(session()->get('customer_id') != $productDetail->customer_id)
                                            <div class="mt-3">
                                                <a href="{{ url('chat?receiver_id=' . \Crypt::encrypt($productDetail->customer_id) . '&product_id=' . \Crypt::encrypt($productDetail->product_id)) }}" class="btn btn-secondary w-100 shadow-sm rounded-pill mb-2" style="background: linear-gradient(135deg, #0d6e7a 0%, #39a68d 100%); border: none;">
                                                    <i class="feather-message-circle me-2"></i> Chat with Seller
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="mt-3">
                                            <a href="javascript:void(0)" onclick="return signin_popup()" class="btn btn-secondary w-100 shadow-sm rounded-pill" style="background: linear-gradient(135deg, #0d6e7a 0%, #39a68d 100%); border: none;">
                                                <i class="feather-lock me-2"></i> Login to View
                                            </a>
                                        </div>
                                    @endif
                                </li>
                            </ul>
                        @else
                            <p>No seller information available</p>
                        @endif
                    </div>
                    <div class="white-bg">
                        <h4 class="border-bottom">Share Venue</h4>
                        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                            <a class="a2a_button_facebook"></a>
                            <a class="a2a_button_x"></a>
                            <a class="a2a_button_linkedin"></a>
                            <a class="a2a_button_whatsapp"></a>
                            <a class="a2a_button_threads"></a>
                            <!--<a class="a2a_dd" href="https://www.addtoany.com/share"></a>-->
                        </div>
                    </div>
                </aside>
            </div>
            <!-- /Row -->
        </div>
        <!-- /container -->

        @if(count($similarDetail) > 0)
            <section class="section innerpagebg pt-0">
                <div class="container">
                    <div class="featured-slider-group">
                        <h3 class="mb-40">Similar Products</h3>
                        <div class="owl-carousel featured-venues-slider owl-theme">
                            <!-- Featured Item -->
                            @for($p=0; $p < count($similarDetail); $p++)
                                <div class="featured-venues-item">
                                    <div class="listing-item listing-item-grid">
                                        <div class="listing-img" style="position: relative;">
                                            @if($similarDetail[$p]['product_is_sold'] == '1')
                                                <div class="fav-item-ls" style="position: absolute; top: 10px; right: 10px; z-index: 2;">
                                                    <span class="badge bg-danger text-white px-3 py-2" style="font-weight: 700; text-transform: uppercase; border-radius: 6px;">SOLD OUT</span>
                                                </div>
                                            @endif
                                            <a href="{{ url('machines/' . $similarDetail[$p]['product_slug']) }}">
                                                @if(count($similarDetail[$p]->pimages) > 0)
                                                    <img src="{{ asset('uploads/product/' . $similarDetail[$p]->pimages[0]->pimage_image) }}" alt="{{ $similarDetail[$p]['product_title'] }}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="listing-content">
                                            <h3 class="listing-title">
                                                <a href="{{ url('machines/' . $similarDetail[$p]['product_slug']) }}">{{ $similarDetail[$p]['product_title'] }}</a>
                                            </h3>
                                            <div class="listing-details-group d-flex justify-content-between align-items-center mb-3">
                                                <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                                    <span class="dark-yellow-bg d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 22px; font-size: 12px; border-radius: 4px;">
                                                        <i class="feather-cpu"></i>
                                                    </span>
                                                    <span class="text-dark fw-bold" style="font-size: 13px;">Model: {{ $similarDetail[$p]['product_model'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                                    <span class="bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 22px; font-size: 12px; border-radius: 4px;">
                                                        <i class="feather-map-pin"></i>
                                                    </span>
                                                    <span class="text-dark fw-bold" style="font-size: 13px;">{{ $similarDetail[$p]->city->city_name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="listing-details-group coach-btn">
                                                <p class="mb-0">
                                                    {!! \Illuminate\Support\Str::limit(strip_tags($similarDetail[$p]['product_short_desc']), 125, '') !!}
                                                    <a href="{{ url('machines/' . $similarDetail[$p]['product_slug']) }}" class="text-primary fw-bold">Read More...</a>
                                                </p>
                                            </div>
                                            <div class="avalbity-review">
                                                <ul>
                                                    <li>
                                                        <div class="avalibity-date">
                                                            <div class="avalibity-datecontent px-3 py-2 border border-primary rounded-pill bg-light shadow-sm">
                                                                @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                                                    <h5 class="mb-0 fw-bold primary-text text-center"><i class="fa-solid fa-indian-rupee-sign"></i> {{ number_format($similarDetail[$p]['product_price'] ?? 15000) }}/-</h5>
                                                                @else
                                                                    <h5 class="mb-0 fw-bold primary-text text-center"><i class="fa-solid fa-indian-rupee-sign"></i> XXXXX/-</h5>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="list-reviews mb-0">
                                                            <div class="d-flex align-items-center">
                                                                <a href="{{ url('machines/' . $similarDetail[$p]['product_slug']) }}" class="btn btn-primary w-100"><i class="feather-eye me-2"></i>View details</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                            <!-- /Featured Item -->
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
    <!-- /Page Content -->
@endsection
@section('page-js')
<script>
function toggleFavourite(productId, btn) {
    @if(!session()->has('customer_id') || session()->get('customer_id') <= 0)
        signin_popup();
        return false;
    @endif

    $.ajax({
        url: "{{ route('favourite-toggle') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            product_id: productId
        },
        success: function(response) {
            if (response.message === 'added') {
                $(btn).addClass('active');
                $(btn).find('i').removeClass('fa-regular').addClass('fa-solid');
            } else if (response.message === 'removed') {
                $(btn).removeClass('active');
                $(btn).find('i').removeClass('fa-solid').addClass('fa-regular');
            } else if (response.message === 'login_required') {
                signin_popup();
            }
        },
        error: function() {
            alert('Something went wrong. Please try again.');
        }
    });
}
</script>
<style>
</style>
@endsection
