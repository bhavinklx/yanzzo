@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
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
    <section class="breadcrumb breadcrumb-list mb-0" style="background-image: url({{ $pageBanner }});">
        <div class="container">
            <h1 class="text-white">{{ $pagesDetail->page_title ?? '' }}</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $pagesDetail->page_title ?? '' }}</li>
            </ul>
        </div>
    </section>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <!-- Sort By -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="sortby-section">
                        <div class="sorting-info">
                            <div class="row d-flex align-items-center">
                                <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
                                    <div class="count-search">
                                        <p><span>{{ $productDetail->total() }}</span> Machines are listed</p>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                                    <div class="sortby-filter-group">
                                        @php
                                            $getFilterUrl = function($params) use ($categorySlug, $subcategorySlug, $locationId) {
                                                $query = [
                                                    'category' => $params['category'] ?? $categorySlug,
                                                    'subcategory' => $params['subcategory'] ?? $subcategorySlug,
                                                    'location' => $params['location'] ?? $locationId,
                                                ];
                                                // Handle "All" options (resetting filter)
                                                if (isset($params['category']) && $params['category'] === '') $query['category'] = null;
                                                if (isset($params['subcategory']) && $params['subcategory'] === '') $query['subcategory'] = null;
                                                if (isset($params['location']) && $params['location'] === '') $query['location'] = null;

                                                return route('machines', array_filter($query));
                                            };
                                        @endphp
                                        <div class="sortbyset">
                                            <span class="sortbytitle">Sort By</span>
                                            <div class="sorting-select">
                                                <select class="form-control select" onchange="if(this.value) window.location.href=this.value">
                                                    <option value="{{ $getFilterUrl(['subcategory' => '']) }}">Sub Categories</option>
                                                    @foreach($subcategoryDetail as $subcategory)
                                                        <option value="{{ $getFilterUrl(['subcategory' => $subcategory->category_slug]) }}" {{ $subcategorySlug == $subcategory->category_slug ? 'selected' : '' }}>
                                                            {{ $subcategory->category_title }}({{ $subcategory->product_count }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="sorting-select">
                                                <select class="form-control select" onchange="if(this.value) window.location.href=this.value">
                                                    <option value="{{ $getFilterUrl(['location' => '']) }}">Location</option>
                                                    @foreach($stateDetail as $state)
                                                        <option value="{{ $getFilterUrl(['location' => $state->state_id]) }}" {{ $locationId == $state->state_id ? 'selected' : '' }}>
                                                            {{ $state->state_name }}({{ $state->product_count }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Sort By -->

            <div class="row justify-content-center">
                <!-- Featured Item -->
                @if(count($productDetail) > 0) @for($p=0; $p < count($productDetail); $p++)
                    <div class="col-lg-4 col-md-6">
                        <div class="featured-venues-item">
                            <div class="listing-item listing-item-grid">
                                <div class="listing-img" style="position: relative;">
                                    @if($productDetail[$p]['product_is_sold'] == '1')
                                        <div class="fav-item-ls" style="position: absolute; top: 10px; right: 10px; z-index: 2;">
                                            <span class="badge bg-danger text-white px-3 py-2" style="font-weight: 700; text-transform: uppercase; border-radius: 6px;">SOLD OUT</span>
                                        </div>
                                    @endif
                                    <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}">
                                        @if(count($productDetail[$p]->pimages) > 0)
                                            <img src="{{ asset('uploads/product/' . $productDetail[$p]->pimages[0]->pimage_image) }}" alt="{{ $productDetail[$p]['product_title'] }}">
                                        @else
                                            <img src="{{ asset('image/product-img.jpg') }}" alt="{{ $productDetail[$p]['product_title'] }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="listing-content">
                                    <h3 class="listing-title">
                                        <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}">{{ $productDetail[$p]['product_title'] }}</a>
                                    </h3>
                                    <div class="listing-details-group d-flex justify-content-between align-items-center mb-3">
                                        <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                            <span class="dark-yellow-bg d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 22px; font-size: 12px; border-radius: 4px;">
                                                <i class="feather-cpu"></i>
                                            </span>
                                            <span class="text-dark fw-bold" style="font-size: 13px;">Model: {{ $productDetail[$p]['product_model'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                            <span class="bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 22px; font-size: 12px; border-radius: 4px;">
                                                <i class="feather-map-pin"></i>
                                            </span>
                                            <span class="text-dark fw-bold" style="font-size: 13px;">{{ $productDetail[$p]->city->city_name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="listing-details-group coach-btn">
                                        <p class="mb-0">
                                            {!! \Illuminate\Support\Str::limit(strip_tags($productDetail[$p]['product_short_desc']), 125, '') !!}
                                            <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}" class="text-primary fw-bold">Read More...</a>
                                        </p>
                                    </div>
                                    <div class="avalbity-review">
                                        <ul>
                                            <li>
                                                <div class="avalibity-date">
                                                    <div class="avalibity-datecontent px-3 py-2 border border-primary rounded-pill bg-light shadow-sm">
                                                        @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                                            <h5 class="mb-0 fw-bold primary-text text-center"><i class="fa-solid fa-indian-rupee-sign"></i> {{ number_format($productDetail[$p]['product_price'] ?? 15000) }}/-</h5>
                                                        @else
                                                            <h5 class="mb-0 fw-bold primary-text text-center"><i class="fa-solid fa-indian-rupee-sign"></i> XXXXX/-</h5>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="list-reviews mb-0">
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}" class="btn btn-primary w-100"><i class="feather-eye me-2"></i>View details</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor @endif
                <!-- /Featured Item -->

                <!--Pagination-->
                @if($productDetail->hasPages())
                    <div class="blog-pagination mt-4">
                        <nav>
                            {{ $productDetail->links() }}
                        </nav>
                    </div>
                @endif
                <!--Pagination-->
            </div>
        </div>

    </div>
    <!-- /Page Content -->
@endsection
@section('page-js')
    <style>
        .pagination .page-item.active .page-link {
            background-color: #2d4487;   /* your highlight color */
            border-color: #2d4487;
            color: #fff;
        }

        .pagination .page-link {
            color: #333;
        }

        .pagination .page-link:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection
