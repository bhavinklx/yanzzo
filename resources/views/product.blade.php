@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')

@section('page-css')
@endsection

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
    <div class="content py-5">
        <div class="container">
            <div class="row align-items-start">
                <!-- Filter Sidebar -->
                <div class="col-xl-3 col-lg-4 col-md-12">
                    <div class="card filter-card border-0 shadow-sm mb-4 mt-0">
                        <div class="card-body p-0">
                            <div class="filter-header p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h4 class="fw-bold mb-0 mt-0" style="color: #2d4487; font-size: 18px; line-height: 1.2;">Filters</h4>
                                <a href="{{ route('machines') }}" class="text-primary small fw-bold text-decoration-none">Reset All</a>
                            </div>

                            @php
                                $getFilterUrl = function($params) use ($categorySlug, $subcategorySlug, $locationId, $cityId, $sortBy) {
                                    $query = [
                                        'category' => $params['category'] ?? $categorySlug,
                                        'subcategory' => $params['subcategory'] ?? $subcategorySlug,
                                        'location' => $params['location'] ?? $locationId,
                                        'city' => $params['city'] ?? $cityId,
                                        'sort' => $params['sort'] ?? $sortBy,
                                    ];
                                    if (isset($params['category']) && $params['category'] === '') $query['category'] = null;
                                    if (isset($params['subcategory']) && $params['subcategory'] === '') $query['subcategory'] = null;
                                    if (isset($params['location']) && $params['location'] === '') $query['location'] = null;
                                    if (isset($params['city']) && $params['city'] === '') $query['city'] = null;
                                    if (isset($params['sort']) && $params['sort'] === '') $query['sort'] = null;

                                    return route('machines', array_filter($query));
                                };
                            @endphp

                            <!-- Sub Categories -->
                            <div class="filter-section p-3">
                                <h5 class="fw-bold mb-3" style="font-size: 15px;">Sub Categories</h5>
                                <ul class="filter-list list-unstyled mb-0">
                                    <li class="mb-2 {{ $subcategorySlug == '' ? 'active' : '' }}">
                                        <a href="{{ $getFilterUrl(['subcategory' => '']) }}" class="text-decoration-none {{ $subcategorySlug == '' ? 'text-primary' : 'text-muted' }}">
                                            <span>All Sub Categories</span>
                                            <span class="badge rounded-pill">{{ $productDetail->total() }}</span>
                                        </a>
                                    </li>
                                    @foreach($subcategoryDetail as $subcategory)
                                        <li class="mb-2 {{ $subcategorySlug == $subcategory->category_slug ? 'active' : '' }}">
                                            <a href="{{ $getFilterUrl(['subcategory' => $subcategory->category_slug]) }}" class="text-decoration-none {{ $subcategorySlug == $subcategory->category_slug ? 'text-primary' : 'text-muted' }}">
                                                <span>{{ $subcategory->category_title }}</span>
                                                <span class="badge rounded-pill">{{ $subcategory->product_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Location -->
                            <div class="filter-section p-3">
                                <h5 class="fw-bold mb-3 d-flex justify-content-between align-items-center" style="font-size: 15px;">
                                    Location
                                   {{-- <i class="feather-chevron-down small text-muted"></i>--}}
                                </h5>
                                <div class="filter-list-scroll" style="max-height: 400px; overflow-y: auto;">
                                    <ul class="filter-list list-unstyled mb-0">
                                        @foreach($stateDetail as $state)
                                            @php
                                                $isStateActive = ($locationId == $state->state_id);
                                                
                                                $hasActiveCity = false;
                                                foreach($state->cities as $city) {
                                                    if($cityId == $city->city_id) {
                                                        $hasActiveCity = true;
                                                        break;
                                                    }
                                                }
                                                
                                                $isOpen = $isStateActive || $hasActiveCity;
                                            @endphp
                                            <li class="mb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <a href="javascript:void(0);" class="text-muted me-2 city-toggle" onclick="toggleCities('{{ $state->state_id }}', this)">
                                                            <i class="feather-{{ $isOpen ? 'minus' : 'plus' }}-square" style="font-size: 16px;"></i>
                                                        </a>
                                                        <a href="{{ $getFilterUrl(['location' => $state->state_id, 'city' => '']) }}" 
                                                           class="text-decoration-none {{ $isStateActive ? 'text-primary' : 'text-dark' }}" 
                                                           style="font-size: 14px; font-weight: 600;">
                                                            {{ $state->state_name }}
                                                        </a>
                                                    </div>
                                                    <span class="badge rounded-pill" style="font-size: 10px; background: #f8f9fa; color: #666; border: 1px solid #eee; font-weight: 500;">{{ $state->product_count }}</span>
                                                </div>
                                                
                                                <ul class="list-unstyled mt-2 city-list {{ $isOpen ? '' : 'd-none' }}" id="cities_{{ $state->state_id }}" style="margin-left: 8px; border-left: 1px solid #ddd; padding-left: 20px;">
                                                    @foreach($state->cities as $city)
                                                        <li class="mb-1">
                                                            <a href="{{ $getFilterUrl(['location' => $state->state_id, 'city' => $city->city_id]) }}" 
                                                               class="text-decoration-none d-flex justify-content-between align-items-center {{ $cityId == $city->city_id ? 'text-primary fw-bold' : 'text-dark' }}" 
                                                               style="font-size: 13px; opacity: 0.8;">
                                                                <span>{{ $city->city_name }}</span>
                                                                <span class="ms-1 small text-muted">({{ $city->product_count }})</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Filter Sidebar -->

                <div class="col-xl-9 col-lg-8 col-md-12">
                    <!-- Sort By / Count -->
                    <div class="sortby-section bg-white p-3 rounded shadow-sm mb-4">
                        <div class="count-search mt-0">
                            <p class="mb-0 text-dark fw-medium mt-0" style="line-height: 1.2;">Found <span>{{ $productDetail->total() }}</span> Machines for you</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-muted small me-2">Sort By:</span>
                            <select class="form-select form-select-sm border shadow-none" onchange="window.location.href=this.value" style="width: auto; min-width: 170px; cursor: pointer; font-size: 13px; border-color: #eee !important; padding-right: 25px;">
                                <option value="{{ $getFilterUrl(['sort' => 'newest']) }}" {{ $sortBy == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="{{ $getFilterUrl(['sort' => 'price-low']) }}" {{ $sortBy == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="{{ $getFilterUrl(['sort' => 'price-high']) }}" {{ $sortBy == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Items -->
                        @if(count($productDetail) > 0) @for($p=0; $p < count($productDetail); $p++)
                            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                                <div class="featured-venues-item h-100">
                                    <div class="listing-item listing-item-grid h-100 mb-0">
                                        <div class="listing-img" style="position: relative; height: 200px; overflow: hidden;">
                                            @if($productDetail[$p]['product_is_sold'] == '1')
                                                <div class="fav-item-ls" style="position: absolute; top: 10px; right: 10px; z-index: 2;">
                                                    <span class="badge bg-danger text-white px-3 py-2" style="font-weight: 700; text-transform: uppercase; border-radius: 6px;">SOLD OUT</span>
                                                </div>
                                            @endif
                                            <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}" class="h-100 w-100 d-block">
                                                @if(count($productDetail[$p]->pimages) > 0)
                                                    <img src="{{ asset('uploads/product/' . $productDetail[$p]->pimages[0]->pimage_image) }}" class="img-fluid h-100 w-100 object-fit-cover" alt="{{ $productDetail[$p]['product_title'] }}">
                                                @else
                                                    <img src="{{ asset('image/product-img.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="{{ $productDetail[$p]['product_title'] }}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="listing-content" style="padding: 24px 0 24px 0">
                                            <h3 class="listing-title" style="font-size: 17px;">
                                                <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}">{{ $productDetail[$p]['product_title'] }}</a>
                                            </h3>
                                            <div class="listing-details-group d-flex justify-content-between align-items-center mb-3">
                                                <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                                    <span class="dark-yellow-bg d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 22px; font-size: 11px; border-radius: 4px;">
                                                        <i class="feather-cpu"></i>
                                                    </span>
                                                    <span class="text-dark fw-bold" style="font-size: 12px;">{{ $productDetail[$p]['product_model'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                                    <span class="bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 22px; font-size: 11px; border-radius: 4px;">
                                                        <i class="feather-map-pin"></i>
                                                    </span>
                                                    <span class="text-dark fw-bold" style="font-size: 12px;">{{ $productDetail[$p]->city->city_name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="listing-details-group coach-btn mb-3">
                                                <p class="mb-0 small text-muted" style="line-height: 1.4;">
                                                    {!! \Illuminate\Support\Str::limit(strip_tags($productDetail[$p]['product_short_desc']), 90, '...') !!}
                                                </p>
                                            </div>
                                            <div class="avalbity-review mt-auto">
                                                <ul class="d-block w-100">
                                                    <li class="w-100 mb-2">
                                                        <div class="avalibity-datecontent px-3 py-2 border border-primary rounded bg-light text-center">
                                                            @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                                                <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-indian-rupee-sign"></i> {{ number_format($productDetail[$p]['product_price'] ?? 15000) }}/-</h5>
                                                            @else
                                                                <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-indian-rupee-sign"></i> XXXXX/-</h5>
                                                            @endif
                                                        </div>
                                                    </li>
                                                    <li class="w-100">
                                                        <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}" class="btn btn-primary w-100 btn-sm py-2">View details</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor @else
                            <div class="col-12 text-center py-5">
                                <div class="mb-4"><i class="feather-search" style="font-size: 50px; color: #ddd;"></i></div>
                                <h4>No Machines Found</h4>
                                <p class="text-muted">Try adjusting your filters or search criteria.</p>
                                <a href="{{ route('machines') }}" class="btn btn-primary">Clear All Filters</a>
                            </div>
                        @endif
                        <!-- /Items -->

                        <!--Pagination-->
                        @if($productDetail->hasPages())
                            <div class="col-12">
                                <div class="blog-pagination mt-4 d-flex justify-content-center">
                                    {{ $productDetail->links() }}
                                </div>
                            </div>
                        @endif
                        <!--Pagination-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-js')
    <script>
        function toggleCities(stateId, element) {
            var target = document.getElementById('cities_' + stateId);
            var icon = element.querySelector('i');
            if (target.classList.contains('d-none')) {
                target.classList.remove('d-none');
                icon.classList.remove('feather-plus-square');
                icon.classList.add('feather-minus-square');
            } else {
                target.classList.add('d-none');
                icon.classList.remove('feather-minus-square');
                icon.classList.add('feather-plus-square');
            }
        }
    </script>
@endsection
