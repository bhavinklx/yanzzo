@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Hero Section -->
    @if($bannerDetail->banner_image!='' && file_exists(public_path('/uploads/banner/'.$bannerDetail->banner_image)))
        <section class="hero-section" style="background: url({{ asset('/uploads/banner/'.$bannerDetail->banner_image) }}); background-size: cover;">
    @else
        <section class="hero-section">
    @endif
        <div class="container">
            <div class="home-banner">
                <div class="row align-items-center w-100">
                    <div class="col-lg-7 col-md-10 mx-auto">
                        <div class="section-search aos" data-aos="fade-up">
                            @if(isset($bannerDetail))
                                @if(!empty($bannerDetail->banner_text))
                                    <h4>{{ $bannerDetail->banner_text }}</h4>
                                @endif
                                @if(!empty($bannerDetail->banner_title))
                                    <h1>{!! $bannerDetail->banner_title !!}</h1>
                                @endif
                                @if(!empty($bannerDetail->banner_desc))
                                    {!! $bannerDetail->banner_desc !!}
                                @endif
                            @else
                                <h4>India's First Private Singing Lounge Chain</h4>
                                <h1>Find your <span>City</span> book your private karaoke Lounge</h1>
                                <p class="sub-info">Yaarioke is India’s first private karaoke lounge chain, offering a unique space where you can sing your heart out with friends in a fun, vibrant, and private setting. Perfect for parties, celebrations, or just a musical escape!.</p>
                            @endif
                            <div class="search-box">
                                <form action="{{ route('book-lounge') }}" method="GET" onsubmit="return validate_search();">
                                    <div class="search-input line">
                                        <div class="form-group mb-0">
                                            <label>Search for private karaoke Lounge</label>
                                            <select class="form-control select" id="city" name="city">
                                                <option value="">Select City</option>
                                                @php
                                                    $cityIdArray = [];
                                                @endphp

                                                @if(is_array($loungeDetail) && count($loungeDetail) > 0)
                                                    @for($l = 0; $l < count($loungeDetail); $l++)
                                                        @php
                                                            $cityId = $loungeDetail[$l]['cities_id'];
                                                            $cityName = $cityNameArray[$cityId] ?? null;
                                                        @endphp

                                                        @if(!in_array($loungeDetail[$l]['cities_id'], $cityIdArray))
                                                            <option value="{{ strtolower($cityName) }}">{{ $cityName }}</option>
                                                            @php
                                                                $cityIdArray[] = $cityId;
                                                            @endphp
                                                        @endif
                                                    @endfor
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="search-btn">
                                        <button class="btn" type="submit"><i class="feather-search"></i><span class="search-text">Search</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="banner-imgs text-center aos" data-aos="fade-up">
                            @if(isset($bannerDetail))
                                @if($bannerDetail->banner_icon!='' && file_exists(public_path('/uploads/banner/'.$bannerDetail->banner_icon)))
                                    <img class="img-fluid" src="{{ asset('/uploads/banner/'.$bannerDetail->banner_icon) }}" alt="Banner">
                                @endif
                            @else
                                <img class="img-fluid" src="{{ url('/public/img/bg/banner-right.png') }}" alt="Banner">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Hero Section -->

    <!-- Rental Deals -->
    <section class="section featured-venues">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>Featured <span>Lounge</span></h2>
                <p class="sub-title">Karaoke lounges with modern facilities, vibrant vibes, and private spaces for an unforgettable singing experience.</p>
            </div>
            <div class="row" style="padding: 15px">
                <div class="featured-slider-group ">
                    <div class="owl-carousel featured-venues-slider owl-theme">
                        <!-- Featured Item -->
                        @if(is_array($loungeDetail) && count($loungeDetail) > 0)
                            @for($l=0; $l < count($loungeDetail); $l++)
                                <div class="featured-venues-item aos" data-aos="fade-up">
                                    <div class="listing-item mb-0">
                                        <div class="listing-img">
                                            @if($loungeDetail[$l]['lounge_image']!='' && file_exists(public_path('/uploads/lounge/'.$loungeDetail[$l]['lounge_image'])))
                                                <a href="{{ url('/book-lounge/' . $loungeDetail[$l]['lounge_slug']) }}">
                                                    <img src="{{ asset('/uploads/lounge/'.$loungeDetail[$l]['lounge_image']) }}" alt="User">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="listing-content">
                                            <h3 class="listing-title">
                                                <a href="{{ url('/book-lounge/' . $loungeDetail[$l]['lounge_slug']) }}">{{ $loungeDetail[$l]['lounge_name'] }}</a>
                                            </h3>
                                            <div class="listing-details-group">
                                                <p>{{ $loungeDetail[$l]['lounge_short_desc'] }}</p>
                                                <ul>
                                                    <li>
                                                        <span>
                                                            <i class="feather-map-pin"></i>{{ $loungeDetail[$l]['lounge_area'] ?? '--' }}, {{ $cityNameArray[$loungeDetail[$l]['cities_id']] ?? '--' }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="listing-button">
                                                <a href="{{ url('/book-lounge/' . $loungeDetail[$l]['lounge_slug']) }}" class="btn btn-secondary d-inline-flex justify-content-center align-items-center w-100"><i class="feather-calendar"></i>&nbsp; Book a Lounge</a>
                                                {{--<a href="{{ url('/book-lounge/' . $loungeDetail[$l]['lounge_slug']) }}" class="user-book-now"><span><i class="feather-calendar me-2"></i></span>Reserve your Lounge</a>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                        <!-- /Featured Item -->
                    </div>
                </div>
            </div>

            <!-- View More -->
            {{--<div class="view-all text-center aos" data-aos="fade-up">
                <a href="{{ url('/book-lounge') }}" class="btn btn-secondary d-inline-flex align-items-center">View All Featured<span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span></a>
            </div>--}}
            <!-- View More -->
        </div>
    </section>
    <!-- /Rental Deals -->

    <!-- How It Works -->
    <section class="section work-section">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>How It <span>Works</span></h2>
                <p class="sub-title">Book online, walk in with friends, and enjoy your private karaoke experience!</p>
            </div>
            <div class="row justify-content-center ">
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ url('/public/img/icons/work-icon2.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content">
                            <h5>
                                <a href="javascript: void(0)">Select Lounge</a>
                            </h5>
                            <p>Choose from our vibrant karaoke lounges in your city and find the perfect spot for your celebration.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ url('/public/img/icons/work-icon1.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content">
                            <h5>
                                <a href="javascript: void(0)">Book Online</a>
                            </h5>
                            <p>Quick and Easy Registration: Get started on our platform with a simple account creation process.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ url('/public/img/icons/work-icon3.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content">
                            <h5>
                                <a href="javascript: void(0)">Pay</a>
                            </h5>
                            <p>Easily book, pay, and enjoy a seamless experience on our user-friendly platform.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /How It Works -->

    <!-- Convenient -->
    <section class="section convenient-section">
        <div class="container">
            <div class="convenient-content aos" data-aos="fade-up">
                <h2>Convenient & Flexible Scheduling</h2>
                <p>Find and book lounge conveniently with our online system that matches your schedule and location.</p>
            </div>
            {{--<div class="convenient-btns aos" data-aos="fade-up">
                <a href="javascript: void(0)" class="btn btn-primary d-inline-flex align-items-center">
                    Book a Training <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span>
                </a>
                <a href="pricing.html" class="btn btn-secondary d-inline-flex align-items-center">
                    View Pricing Plan <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span>
                </a>
            </div>--}}
        </div>
    </section>
    <!-- /Convenient -->

    <!-- Group Coaching -->
    <section class="section featured-section {{--group-coaching--}}">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>Our <span>Features</span></h2>
                <p class="sub-title">We offer easy online booking for our karaoke lounge, giving you a hassle-free way to reserve your private singing space anytime.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid coaching-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ url('/public/img/icons/coache-icon-01.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content text-center">
                            <h3>Easy Online Booking</h3>
                            <p>Reserve your slot in just a few clicks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid coaching-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ url('/public/img/icons/coache-icon-02.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content text-center">
                            <h3>Private Karaoke Rooms</h3>
                            <p>Enjoy singing with friends in your own space.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="work-grid coaching-grid w-100 aos" data-aos="fade-up">
                        <div class="work-icon">
                            <div class="work-icon-inner">
                                <img src="{{ url('/public/img/icons/coache-icon-06.svg') }}" alt="Icon">
                            </div>
                        </div>
                        <div class="work-content text-center">
                            <h3>Food & Beverages</h3>
                            <p>Refreshing drinks and snacks to keep the vibe going.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Group Coaching -->

    <!-- Testimonials -->
    <section class="section our-testimonials">
        <div class="container">
            <div class="section-heading aos" data-aos="fade-up">
                <h2>Our <span>Testimonials</span></h2>
                <p class="sub-title">Glowing testimonials from passionate karaoke enthusiasts worldwide, showcasing our exceptional services.</p>
            </div>
            <div class="row" style="padding: 15px">
                <div class="featured-slider-group aos" data-aos="fade-up">
                    <div class="owl-carousel testimonial-slide featured-venues-slider owl-theme">
                        <!-- Testimonials Item -->
                        @if(is_array($testimonialDetail) && count($testimonialDetail) > 0)
                            @for($t=0; $t < count($testimonialDetail); $t++)
                                <div class="testimonial-group">
                                    <div class="testimonial-review">
                                        <h5>{{ $testimonialDetail[$t]['testimonial_designation'] }}</h5>
                                        {!! $testimonialDetail[$t]['testimonial_desc'] !!}
                                    </div>
                                    <div class="listing-venue-owner">
                                        @if($testimonialDetail[$t]['testimonial_image']!='' && file_exists(public_path('/uploads/blog/'.$testimonialDetail[$t]['testimonial_image'])))
                                            <a class="navigation">
                                                <img src="{{ asset('/uploads/blog/'.$testimonialDetail[$t]['testimonial_image']) }}" alt="User">
                                            </a>
                                        @endif
                                        <div class="testimonial-content">
                                            <h5><a href="javascript:;">{{ $testimonialDetail[$t]['testimonial_title'] }}</a></h5>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                        <!-- /Testimonials Item -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Testimonials -->

    <!-- Latest News -->
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
                        @if(is_array($blogDetail) && count($blogDetail) > 0)
                            @for($b=0; $b < count($blogDetail); $b++)
                                <div class="featured-venues-item aos" data-aos="fade-up">
                                    <div class="listing-item mb-0">
                                        <div class="listing-img">
                                            <a href="{{ url('/blogs/'. $blogDetail[$b]['blog_slug']) }}">
                                                <img src="{{ asset('/uploads/blog/'.$blogDetail[$b]['blog_image']) }}" alt="{{ $blogDetail[$b]['blog_title'] }}">
                                            </a>
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
                        @endif
                        <!-- /News -->
                    </div>
                </div>
            </div>

            <!-- View More -->
            <div class="view-all text-center aos" data-aos="fade-up">
                <a href="{{ url('/blogs/') }}" class="btn btn-secondary d-inline-flex align-items-center">View All News <span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span></a>
            </div>
            <!-- View More -->
        </div>
    </section>
    <!-- /Latest News -->
@endsection

@section('page-js')
    <script type="text/javascript">
        const realFileBtn = document.getElementById("real-file");
        const customBtn = document.getElementById("custom-button");
        const customTxt = document.getElementById("custom-text");

        customBtn.addEventListener("click", function() {
            realFileBtn.click();
        });

        realFileBtn.addEventListener("change", function() {
            if (realFileBtn.value) {
                customTxt.innerHTML = realFileBtn.value.match(
                        /[\/\\]([\w\d\s\.\-\(\)]+)$/
                )[1];
            } else {
                customTxt.innerHTML = "No file chosen, yet.";
            }
        });
    </script>
@endsection
