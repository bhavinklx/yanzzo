@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <style>
        #heroSearchInput:focus {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }
    </style>
    <!-- Hero Section -->
    @if(is_array($bannerDetail) && count($bannerDetail) > 0)
        <section class="hero-section position-relative p-0" style="overflow: hidden;">
            <div class="container-fluid p-0">
                <div class="home-banner">
                    <!-- Owl Carousel yahan start -->
                    <div class="owl-carousel owl-theme hero-carousel">
                        @for($b=0; $b < count($bannerDetail); $b++)
                            <div class="hero-slide w-100" style="background-image: url('{{ $bannerDetail[$b]['banner_image'] != '' ? asset('uploads/banner/'.$bannerDetail[$b]['banner_image']) : '' }}'); background-size: cover; background-position: center; min-height: 600px; display: flex; align-items: center; justify-content: center; position: relative;">
                                <div class="overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(14, 38, 59, 0.75);"></div>
                                <div class="container position-relative z-1">
                                    <div class="row">
                                        <div class="col-lg-10 mx-auto text-center py-5">
                                            <div class="section-search aos" data-aos="fade-up">
                                                @if(!empty($bannerDetail[$b]['banner_title']))
                                                    <h1 class="text-white fw-bold mb-3" style="font-size: 3rem; letter-spacing: -0.5px;">{!! strip_tags($bannerDetail[$b]['banner_title']) !!}</h1>
                                                @endif
                                                @if(!empty($bannerDetail[$b]['banner_text']))
                                                    <p class="sub-info text-white mb-4" style="font-size: 1.15rem;">{{ $bannerDetail[$b]['banner_text'] }}</p>
                                                @endif

                                                <form action="{{ url('machines') }}" method="GET" class="mt-4" id="heroSearchForm">
                                                    <div class="position-relative mx-auto" style="max-width: 650px;">
                                                        <div class="input-group shadow-lg align-items-center" style="border-radius: 50px; background: #fff; padding: 6px 8px 6px 25px;">
                                                            <input type="text" name="q" id="heroSearchInput" class="form-control border-0 shadow-none p-0" placeholder="Search by title, keyword, country or region..." aria-label="Search" style="background: transparent; font-size: 15px;" required autocomplete="off">
                                                            <button class="btn text-white px-4 border-0 ms-2" type="submit" style="background: linear-gradient(135deg, #0d6e7a 0%, #39a68d 100%); border-radius: 50px; font-weight: 600; padding-top: 10px; padding-bottom: 10px;">
                                                                <i class="fas fa-search me-1"></i> Search
                                                            </button>
                                                        </div>
                                                        <div id="searchSuggestions" class="d-none" style="position: absolute; top: calc(100% + 8px); left: 0; right: 0; background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); z-index: 1000; overflow: hidden; max-height: 360px; overflow-y: auto;">
                                                            <!-- suggestions injected here -->
                                                        </div>
                                                    </div>

                                                    {{--
                                                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-4" style="gap: 10px;">
                                                        @php
                                                            $topCategories = \App\Models\Category::where('category_status', '1')->take(8)->get();
                                                        @endphp
                                                        @if(isset($topCategories) && count($topCategories) > 0)
                                                            @foreach($topCategories as $cat)
                                                                <a href="{{ url('machines?category='.$cat->category_slug) }}" class="btn btn-sm rounded-pill px-3 py-1" style="background: rgba(27, 132, 238, 0.9); color: #fff; border: 1px solid rgba(255,255,255,0.2); font-weight: 500; font-size: 13px; text-decoration: none;">{{ $cat->category_title }}</a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    --}}
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <!-- Owl Carousel end -->
                </div>
            </div>
        </section>
    @endif
    <!-- /Hero Section -->

    <!-- Journey -->
    {{--<section class="section journey-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="start-your-journey aos" data-aos="fade-up">
                        <h2>Start Your Industrial Journey With <span class="active-sport">Yanzzo Machines</span>.</h2>
                        <p>At Yanzzo Machines, we specialize in delivering reliable, high-performance industrial machinery designed to meet the evolving needs of modern manufacturing. With a strong focus on quality, precision, and innovation, we help businesses improve productivity and operational efficiency.</p>
                        <p>Our expertise lies in providing advanced machine solutions backed by engineering excellence, rigorous quality standards, and customer-centric service. From consultation to installation and after-sales support, we ensure seamless execution at every stage.</p>
                        <p>Driven by technology and industry insight, Yanzzo Machines is committed to building long-term partnerships by offering durable machines that perform consistently in demanding industrial environments.</p>
                        <span class="stay-approach">Stay Ahead With Our Advanced Engineering Approach:</span>
                        <div class="journey-list">
                            <ul>
                                <li><i class="fa-solid fa-circle-check"></i>High-Quality Industrial Machinery</li>
                                <li><i class="fa-solid fa-circle-check"></i>Precision Engineering & Manufacturing</li>
                                <li><i class="fa-solid fa-circle-check"></i>Customized Machine Solutions</li>
                            </ul>
                            <ul>
                                <li><i class="fa-solid fa-circle-check"></i>Modern Technology Integration</li>
                                <li><i class="fa-solid fa-circle-check"></i>Strict Quality Control Standards</li>
                                <li><i class="fa-solid fa-circle-check"></i>Reliable After-Sales Support</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="journey-img aos" data-aos="fade-up">
                        <img src="{{ asset('image/about-img.jpg') }}" class="img-fluid" alt="User">
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
    <!-- /Journey -->

    <!-- Latest News -->
    @if(count($productDetail) > 0)
        <section class="section featured-venues latest-news">
            <div class="container">
                <div class="section-heading aos" data-aos="fade-up">
                    <h2>Latest Posted <span>Used Machines</span></h2>
                    {{--<p class="sub-title">Get the latest buzz from the badminton world- stay informed and inspired by the thrilling updates and remarkable achievements in the sport.</p>--}}
                </div>
                <div class="row g-4 mb-5">
                    @foreach(array_slice($productDetail, 0, 8) as $product)
                        <div class="col-lg-3 col-md-6">
                            <div class="featured-venues-item h-100">
                                <div class="listing-item listing-item-grid mb-0 h-100 d-flex flex-column" style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); transition: 0.3s; border: 1px solid #f0f0f0;">
                                    <div class="listing-img" style="position: relative; height: 200px; overflow: hidden;">
                                        @if($product['product_is_sold'] == '1')
                                            <div class="fav-item-ls" style="position: absolute; top: 10px; right: 10px; z-index: 2;">
                                                <span class="badge bg-danger text-white px-3 py-2" style="font-weight: 700; text-transform: uppercase; border-radius: 6px;">SOLD OUT</span>
                                            </div>
                                        @endif
                                        <a href="{{ url('machines/' . $product['product_slug']) }}" class="h-100 w-100 d-block">
                                            @if(count($product['pimages']) > 0)
                                                <img src="{{ asset('uploads/product/' . $product['pimages'][0]['pimage_image']) }}" class="img-fluid h-100 w-100 object-fit-cover" alt="{{ $product['product_title'] }}">
                                            @else
                                                <img src="{{ asset('image/product-img.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="{{ $product['product_title'] }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="listing-content d-flex flex-column flex-grow-1" style="padding: 20px;">
                                        <h3 class="listing-title" style="font-size: 17px;">
                                            <a href="{{ url('machines/' . $product['product_slug']) }}">{{ $product['product_title'] }}</a>
                                        </h3>
                                        <div class="listing-details-group d-flex justify-content-between align-items-center mb-3">
                                            <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                                <span class="dark-yellow-bg d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 22px; font-size: 11px; border-radius: 4px;">
                                                    Year
                                                </span>
                                                <span class="text-dark fw-bold" style="font-size: 12px;">{{ $product['product_model'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="white-bg d-flex align-items-center review shadow-sm py-1 px-2 border rounded">
                                                <span class="dark-yellow-bg d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 22px; font-size: 11px; border-radius: 4px;">
                                                    <i class="feather-map-pin"></i>
                                                </span>
                                                <span class="text-dark fw-bold" style="font-size: 12px;">{{ $product['city']['city_name'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="listing-details-group coach-btn mb-3 flex-grow-1">
                                            <p class="mb-0 small text-muted" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8em;">
                                                {!! strip_tags($product['product_short_desc']) !!}
                                            </p>
                                        </div>
                                        <div class="avalbity-review mt-auto">
                                            <ul class="d-block w-100">
                                                <li class="w-100 mb-0">
                                                    <div class="avalibity-datecontent px-3 py-2 border border-primary rounded bg-light text-center">
                                                        @if(session()->has('customer_id') && session()->has('customer_id') > 0)
                                                            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-indian-rupee-sign"></i> {{ \App\Helpers\FormatHelper::formatIndianPrice($product['product_price']) }}/-</h5>
                                                        @else
                                                            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-indian-rupee-sign"></i> XXXXX/-</h5>
                                                        @endif
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- View More -->
                <div class="view-all text-center aos" data-aos="fade-up">
                    <a href="{{ url('/machines') }}" class="btn btn-secondary d-inline-flex align-items-center">View All Machines <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span></a>
                </div>
                <!-- View More -->
            </div>
        </section>
    @endif
    <!-- /Latest News -->

    <!-- Group Coaching -->
    @if(is_array($ourFeatureDetail) && count($ourFeatureDetail) > 0)
        <section class="section group-coaching">
            <div class="container">
                <div class="section-heading aos" data-aos="fade-up">
                    <h2>Why <span>Choose Us</span></h2>
                    <p class="sub-title">Simplifying the buying and selling of pre-owned industrial machinery through a trusted, transparent and technology-driven marketplace.</p>
                </div>
                {{--<div class="row">
                    <div class="col-lg-12">
                        <div class="work-grid coaching-grid w-100 aos" data-aos="fade-up">
                            <div class="work-content">
                                <h3>High Demand machines</h3>
                                <ul>
                                    <li><a href="#">Turret Punching Machine</a></li>
                                    <li><a href="#">Bending Machine</a></li>
                                    <li><a href="#">Shearing Machine</a></li>
                                    <li><a href="#">Laser Cutting Machine</a></li>
                                    <li><a href="#">Water Jet Cutting Machine</a></li>
                                    <li><a href="#">CNC Milling Machine</a></li>
                                    <li><a href="#">CNC Turning Machine</a></li>
                                    <li><a href="#">HMC Machines</a></li>
                                    <li><a href="#">VMC Machines</a></li>
                                    <li><a href="#">Silos And Tanks</a></li>
                                    <li><a href="#">Corrugation Machine</a></li>
                                    <li><a href="#">Pasting Machine</a></li>
                                    <li><a href="#">Printing Machine</a></li>
                                    <li><a href="#">Injection Moulding Machines</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>--}}

                <div class="row justify-content-center">
                    @for($o=0; $o < count($ourFeatureDetail); $o++)
                        <div class="col-lg-4 col-md-6 d-flex">
                            <div class="work-grid coaching-grid w-100 aos" data-aos="fade-up">
                                <div class="work-content">
                                    <h3>{{ $ourFeatureDetail[$o]['service_title'] }}</h3>
                                    {!! $ourFeatureDetail[$o]['service_desc'] !!}
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>
    @endif
    <!-- Group Coaching -->

    <!-- How It Works -->
    @if(is_array($whyChooseDetail) && count($whyChooseDetail) > 0)
        <section class="section work-section">
            <div class="container">
                <div class="section-heading aos" data-aos="fade-up">
                    <h2>Why <span>Trade With Us?</span></h2>
                    <p class="sub-title">Making industrial machinery trading simpler, faster, and more reliable for businesses across India.</p>
                </div>
                <div class="row justify-content-center ">
                    @for($w=0; $w < count($whyChooseDetail); $w++)
                        <div class="col-lg-4 col-md-6 d-flex">
                            <div class="work-grid w-100 aos" data-aos="fade-up">
                                @if($whyChooseDetail[$w]['service_image']!='' && file_exists(public_path('/uploads/service/'.$whyChooseDetail[$w]['service_image'])))
                                    <div class="work-icon">
                                        <div class="work-icon-inner">
                                            <img src="{{ asset('/uploads/service/'.$whyChooseDetail[$w]['service_image']) }}" alt="{{ $whyChooseDetail[$w]['service_title'] }}">
                                        </div>
                                    </div>
                                @endif
                                <div class="work-content">
                                    <h5>{{ $whyChooseDetail[$w]['service_title'] }}</h5>
                                    {!! $whyChooseDetail[$w]['service_desc'] !!}
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>
    @endif
    <!-- /How It Works -->

    <!-- Testimonials -->
    @if(is_array($testimonialDetail) && count($testimonialDetail) > 0)
        <section class="section our-testimonials">
            <div class="container">
                <div class="section-heading aos" data-aos="fade-up">
                    <h2>Our <span>Testimonials</span></h2>
                    <p class="sub-title">Glowing testimonials from passionate badminton enthusiasts worldwide, showcasing our exceptional services.</p>
                </div>
                <div class="row">
                    <div class="featured-slider-group aos" data-aos="fade-up">
                        <div class="owl-carousel testimonial-slide featured-venues-slider owl-theme">
                            <!-- Testimonials Item -->
                            @for($t=0; $t < count($testimonialDetail); $t++)
                                <div class="testimonial-group">
                                    <div class="testimonial-review">
                                        <div class="rating-point">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <span > 5.0</span>
                                        </div>
                                        <h5>{{ $testimonialDetail[$t]['testimonial_designation'] }}</h5>
                                        {!! $testimonialDetail[$t]['testimonial_desc'] !!}
                                    </div>
                                    <div class="listing-venue-owner">
                                        @if($testimonialDetail[$t]['testimonial_image']!='' && file_exists(public_path('/uploads/testimonial/'.$testimonialDetail[$t]['testimonial_image'])))
                                            <a class="navigation">
                                                <img src="{{ asset('/uploads/testimonial/'.$testimonialDetail[$t]['testimonial_image']) }}" alt="{{ $testimonialDetail[$t]['testimonial_title'] }}">
                                            </a>
                                        @endif
                                        <div class="testimonial-content">
                                            <h5><a href="javascript:;">{{ $testimonialDetail[$t]['testimonial_title'] }}</a></h5>
                                        </div>
                                    </div>
                                </div>
                        @endfor
                        <!-- /Testimonials Item -->
                        </div>
                    </div>

                    @if(is_array($sponsorDetail) && count($sponsorDetail) > 0)
                        @for($s=0; $s < count($sponsorDetail); $s++)
                            <div class="brand-slider-group aos" data-aos="fade-up">
                                <div class="owl-carousel testimonial-brand-slider owl-theme">
                                    @if($sponsorDetail[$s]['sponsor_image']!='' && file_exists(public_path('/uploads/sponsor/'.$sponsorDetail[$s]['sponsor_image'])))
                                        <div class="brand-logos">
                                            <img src="{{ asset('/uploads/sponsor/'.$sponsorDetail[$s]['sponsor_image']) }}" alt="{{ $sponsorDetail[$s]['sponsor_title'] }}">
                                        </div>
                                    @endif
                                    <div class="brand-logos">
                                        <img  src="{{ asset('image/testimonial-icon-04.svg') }}" alt="Brand">
                                    </div>
                                    <div class="brand-logos">
                                        <img  src="{{ asset('image/testimonial-icon-03.svg') }}" alt="Brand">
                                    </div>
                                    <div class="brand-logos">
                                        <img  src="{{ asset('image/testimonial-icon-04.svg') }}" alt="Brand">
                                    </div>
                                    <div class="brand-logos">
                                        <img  src="{{ asset('image/testimonial-icon-05.svg') }}" alt="Brand">
                                    </div>
                                    <div class="brand-logos">
                                        <img  src="{{ asset('image/testimonial-icon-03.svg') }}" alt="Brand">
                                    </div>
                                    <div class="brand-logos">
                                        <img  src="{{ asset('image/testimonial-icon-04.svg') }}" alt="Brand">
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </section>
    @endif
    <!-- /Testimonials -->

    <!-- Latest News -->
    @if(is_array($blogDetail) && count($blogDetail) > 0)
        <section class="section latest-news">
            <div class="container">
                <div class="section-heading aos" data-aos="fade-up">
                    <h2>The Latest <span>News</span></h2>
                    <p class="sub-title">Get the latest buzz from the karaoke lounge world- stay informed and inspired by the thrilling updates</p>
                </div>
                <div class="row" style="padding: 15px">
                    <div class="featured-slider-group ">
                        <div class="owl-carousel featured-venues-slider owl-theme">
                            <!-- News -->
                            @for($b=0; $b < count($blogDetail); $b++)
                                <div class="featured-venues-item aos" data-aos="fade-up">
                                    <div class="listing-item mb-0">
                                        <div class="listing-img">
                                            @if($blogDetail[$b]['blog_image']!='' && file_exists(public_path('/uploads/blog/'.$blogDetail[$b]['blog_image'])))
                                                <a href="{{ url('/blogs/'. $blogDetail[$b]['blog_slug']) }}">
                                                    <img src="{{ asset('/uploads/blog/'.$blogDetail[$b]['blog_image']) }}" alt="{{ $blogDetail[$b]['blog_title'] }}">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="listing-content news-content">
                                            <div class="listing-venue-owner listing-dates">
                                                <i class="feather-calendar"></i> {{ date('d M, Y', strtotime($blogDetail[$b]['blog_date'])) }}
                                            </div>
                                            <h3 class="listing-title">
                                                <a href="{{ url('/blogs/'. $blogDetail[$b]['blog_slug']) }}">{{ $blogDetail[$b]['blog_title'] }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                        @endfor
                        <!-- /News -->
                        </div>
                    </div>
                </div>

                <!-- View More -->
                <div class="view-all text-center aos" data-aos="fade-up">
                    <a href="{{ url('/blogs') }}" class="btn btn-secondary d-inline-flex align-items-center">View All News <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span></a>
                </div>
                <!-- View More -->
            </div>
        </section>
    @endif
    <!-- /Latest News -->

    <!-- Newsletter -->
    {{--<section class="section newsletter-sport">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="subscribe-style aos" data-aos="fade-up">
                        <div class="banner-blk">
                            <img src="{{ asset('image/newsletter.png') }}" class="img-fluid" alt="Subscribe">
                        </div>
                        <div class="banner-info ">
                            <img src="{{ asset('image/subscribe.svg') }}" class="img-fluid" alt="Subscribe">
                            <h2>Subscribe to Newsletter</h2>
                            <p>Just for you, exciting badminton news updates.</p>
                            <div class="subscribe-blk bg-white">
                                <div class="input-group align-items-center">
                                    <i class="feather-mail"></i>
                                    <input type="email" class="form-control" placeholder="Enter Email Address" aria-label="email">
                                    <div class="subscribe-btn-grp">
                                        <input type="submit" class="btn btn-secondary" value="Subscribe">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
    <!-- /Newsletter -->
@endsection

@section('page-js')
    <script>
        (function() {
            const input = document.getElementById('heroSearchInput');
            const dropdown = document.getElementById('searchSuggestions');
            let debounceTimer = null;
            let activeIndex = -1;
            let currentData = [];

            if (!input || !dropdown) return;

            function closeDropdown() {
                dropdown.classList.add('d-none');
                activeIndex = -1;
                currentData = [];
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function selectText(text) {
                input.value = text;
                closeDropdown();
                input.focus();
            }

            function renderSuggestions(data) {
                currentData = data;
                activeIndex = -1;
                if (!data || data.length === 0) {
                    dropdown.innerHTML = '<div class="px-3 py-3 text-muted" style="font-size: 14px; text-align: left !important;">No results found</div>';
                    dropdown.classList.remove('d-none');
                    return;
                }

                let html = '';
                data.forEach(function(item, index) {
                    html += '<div class="suggestion-item px-3 py-2" data-index="' + index + '" data-text="' + escapeHtml(item.text) + '" style="cursor: pointer; border-bottom: 1px solid #f1f1f1; transition: background 0.15s; font-size: 14px; color: #333; text-align: left !important;">' +
                        escapeHtml(item.text) +
                        '</div>';
                });

                dropdown.innerHTML = html;
                dropdown.classList.remove('d-none');

                dropdown.querySelectorAll('.suggestion-item').forEach(function(el) {
                    el.addEventListener('click', function() {
                        selectText(this.dataset.text);
                    });
                });
            }

            function updateActiveItem() {
                const items = dropdown.querySelectorAll('.suggestion-item');
                items.forEach(function(item, idx) {
                    item.style.background = (idx === activeIndex) ? '#f0f4ff' : 'transparent';
                });
            }

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const keyword = this.value.trim();
                if (keyword.length < 2) {
                    closeDropdown();
                    return;
                }

                debounceTimer = setTimeout(function() {
                    fetch('{{ route("search-suggestions") }}?q=' + encodeURIComponent(keyword), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) { renderSuggestions(data); })
                    .catch(function() { closeDropdown(); });
                }, 300);
            });

            input.addEventListener('keydown', function(e) {
                if (dropdown.classList.contains('d-none')) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = Math.min(activeIndex + 1, currentData.length - 1);
                    updateActiveItem();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, -1);
                    updateActiveItem();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (activeIndex >= 0 && currentData[activeIndex]) {
                        selectText(currentData[activeIndex].text);
                    } else {
                        document.getElementById('heroSearchForm').submit();
                    }
                } else if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });
        })();
    </script>
@endsection
