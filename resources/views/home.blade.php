@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Hero Section -->
    @if(is_array($bannerDetail) && count($bannerDetail) > 0)
        <section class="hero-section position-relative">
            <div class="container">
                <div class="home-banner">
                    <!-- Owl Carousel yahan start -->
                    <div class="owl-carousel owl-theme hero-carousel">
                        <!-- Slide 1 -->
                        @for($b=0; $b < count($bannerDetail); $b++)
                            <div class="hero-slide">
                                <div class="row align-items-center w-100">
                                    <div class="col-lg-7 col-md-10 mx-auto">
                                        <div class="section-search aos" data-aos="fade-up">
                                            @if(!empty($bannerDetail[$b]['banner_text']))
                                                <h4>{{ $bannerDetail[$b]['banner_text'] }}</h4>
                                            @endif
                                            @if(!empty($bannerDetail[$b]['banner_title']))
                                                <h1>{!! $bannerDetail[$b]['banner_title'] !!}</h1>
                                            @endif
                                            @if(!empty($bannerDetail[$b]['banner_text1']))
                                                <p class="sub-info">{{ $bannerDetail[$b]['banner_text1'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        @if($bannerDetail[$b]['banner_image']!='' && file_exists(public_path('/uploads/banner/'.$bannerDetail[$b]['banner_image'])))
                                            <div class="banner-imgs text-center aos" data-aos="fade-up">
                                                <img src="{{ asset('/uploads/banner/'.$bannerDetail[$b]['banner_image']) }}" alt="{{ $bannerDetail[$b]['banner_image'] }}">
                                            </div>
                                        @endif
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
                        <img src="image/about-img.jpg" class="img-fluid" alt="User">
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
                    <p class="sub-title">Get the latest buzz from the badminton world- stay informed and inspired by the thrilling updates and remarkable achievements in the sport.</p>
                </div>
                <div class="row">
                    <div class="featured-slider-group ">
                        <div class="owl-carousel featured-venues-slider owl-theme">
                            <!-- Featured Item -->
                            @for($p=0; $p < count($productDetail); $p++)
                                <div class="featured-venues-item">
                                    <div class="listing-item listing-item-grid">
                                        <div class="listing-img">
                                            @if($productDetail[$p]['product_is_sold'] == '1')
                                                <div class="fav-item-ls" style="position: absolute; top: 10px; right: 10px; z-index: 2;">
                                                    <span class="badge bg-danger text-white px-3 py-2" style="font-weight: 700; text-transform: uppercase; border-radius: 6px;">SOLD OUT</span>
                                                </div>
                                            @endif
                                            <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}">
                                                <img src="http://127.0.0.1:8000/image/product-img.jpg" alt="{{ $productDetail[$p]['product_title'] }}">
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
                                                    <span class="text-dark fw-bold" style="font-size: 13px;">{{ $productDetail[$p]['city']['city_name'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="listing-details-group">
                                                <p class="mb-0">
                                                    {!! \Illuminate\Support\Str::limit(strip_tags($productDetail[$p]['product_short_desc']), 125, '') !!}
                                                    <a href="{{ url('machines/' . $productDetail[$p]['product_slug']) }}" class="text-primary fw-bold">Read More...</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        <!-- /Featured Item -->
                        </div>
                    </div>
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
                    <h2>Our <span>Features</span></h2>
                    <p class="sub-title">Discover your potential with our comprehensive training, expert trainers, and advanced facilities. Join us to improve your athletic career.</p>
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
                    <h2>Why <span>Choose Us</span></h2>
                    <p class="sub-title">Simplifying the booking process for coaches, venues, and athletes.</p>
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
    <section class="section our-testimonials">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>Our <span>Testimonials</span></h2>
                <p class="sub-title">Glowing testimonials from passionate badminton enthusiasts worldwide, showcasing our exceptional services.</p>
            </div>
            <div class="row">
                @if(is_array($testimonialDetail) && count($testimonialDetail) > 0)
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
                @endif

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
                                    <img  src="image/testimonial-icon-04.svg" alt="Brand">
                                </div>
                                <div class="brand-logos">
                                    <img  src="image/testimonial-icon-03.svg" alt="Brand">
                                </div>
                                <div class="brand-logos">
                                    <img  src="image/testimonial-icon-04.svg" alt="Brand">
                                </div>
                                <div class="brand-logos">
                                    <img  src="image/testimonial-icon-05.svg" alt="Brand">
                                </div>
                                <div class="brand-logos">
                                    <img  src="image/testimonial-icon-03.svg" alt="Brand">
                                </div>
                                <div class="brand-logos">
                                    <img  src="image/testimonial-icon-04.svg" alt="Brand">
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>
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
                            <img src="image/newsletter.png" class="img-fluid" alt="Subscribe">
                        </div>
                        <div class="banner-info ">
                            <img src="image/subscribe.svg" class="img-fluid" alt="Subscribe">
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