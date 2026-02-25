@extends("layouts.app")
@section('title', $cityDetail->city_meta_title ?? $pagesDetail->page_meta_title)
@section('keywords', $cityDetail->city_meta_keyword ?? $pagesDetail->page_meta_keyword)
@section('description', $cityDetail->city_meta_desc ?? $pagesDetail->page_meta_desc)
@section('canonical', $cityDetail->city_canonical ?? 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
@section("content")
    <!--city page start-->
    <div class="city-banner-section" >
        <div class="banner-right">
            <div class="container">
                <div class="col-xl-6 p-0">
                    <div class="inner-space-left">
                        <div class="custom-content">
                            <span>Arten space</span>
                            {!! $cityDetail->city_desc ?? '' !!}
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 cityForm">
                    <form>
                        <div class="row">
                            <div class="col-xl-6 ">
                                <input class="city-input" type="text" id="contact_fname"  name="contact_fname" placeholder="First Name" >
                            </div>
                            <div class="col-xl-6 ">
                                <input class="city-input" type="text" id="contact_lname" name="contact_lname" placeholder="Last Name" >
                            </div>
                            <div class="col-xl-6 ">
                                <input class="city-input" type="text" id="contact_email" name="contact_email" placeholder="Email Address" >
                            </div>
                            <div class="col-xl-6 ">
                                <input class="city-input" type="number" id="contact_mobile" name="contact_mobile" placeholder="Phone Number" >
                            </div>
                            <div class="col-xl-12"> <input class="city-input" type="text" id="contact_message" name="contact_message" placeholder="How can we help you?" ></div>
                            <span class="inquiry-btn">
                            <a href="javascript: void (0)" onclick="return validate_contact();">Enquiry Now
                                <svg width="33" height="24" viewBox="0 0 33 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.5271 11.25H31.4728M31.4728 11.25L21.4728 1.25M31.4728 11.25L21.4728 22.75" stroke="white" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </a>
                        </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="experience-wrap bockCont">
        <div class="container">
            <div class="extensive-wrap">
                <h2>Our Extensive Furniture Manufacturing Portfolio</h2>
                <p>{{ $cityDetail->city_short_desc }}</p>
            </div>
        </div>
    </div>

    @if(count($ftypeDetail) > 0)
        <div class="container">
            <div class="experience-tabbing">
                <div class="col-12 experience-content">
                    <ul class="tabs-content">
                        @for($f=0; $f < count($ftypeDetail); $f++)
                            <li>
                                <div class="row">
                                    <div class="col-xl-2 col-4 text-center">
                                        @if($ftypeDetail[$f]['ftype_icon']!='' && file_exists(public_path('/uploads/city/'.$ftypeDetail[$f]['ftype_icon'])))
                                            <i><img src="{{ asset('/uploads/city/'.$ftypeDetail[$f]['ftype_icon']) }}"></i>
                                        @endif
                                    </div>
                                    <div class="col-xl-3 col-8">
                                        <h3>{{ $ftypeDetail[$f]['ftype_title'] }}</h3>
                                    </div>

                                    <div class="col-xl-7 para-cont">
                                        <p>{{ $ftypeDetail[$f]['ftype_desc'] }}</p>
                                    </div>
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>
                <div class="col-12">
                    <ul class="tabs02">
                        @for($f=0; $f < count($ftypeDetail); $f++)
                            <li class="{{ ($f == 0) ? 'active-tab' : '' }}">{{ $ftypeDetail[$f]['ftype_title'] }}</li>
                        @endfor
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(count($serviceDetail) > 0)
        <div class="experience-wrap bockCont">
            <div class="container">
                <div class="extensive-wrap">
                    <h2>Benefits of Buying<br> from the Best Furniture Manufacturers in {{ $cityDetail->city_title }} City</h2>
                    <p>Choosing the proper furniture manufacturer can make all the difference. Here are some compelling benefits of buying from the best furniture manufacturers in {{ $cityDetail->city_title }} City, such as Arten Space:</p>
                </div>
                <div class="benefits-list">
                    @for($s=0; $s < count($serviceDetail); $s++)
                        <div class="benefits-box">
                            @if($serviceDetail[$s]['service_image']!='' && file_exists(public_path('/uploads/service/'.$serviceDetail[$s]['service_image'])))
                                <img src="{{ asset('/uploads/service/'.$serviceDetail[$s]['service_image']) }}">
                            @endif
                            <div class="benefits-inner-cont">
                                <h4>{{ $serviceDetail[$s]['service_title'] ?? '' }}</h4>
                                {!! $serviceDetail[$s]['service_desc'] ?? '' !!}
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @endif

    @if(count($uspDetail) > 0)
        <div class="experience-wrap bockCont pt-0">
            <div class="container">
                <div class="extensive-wrap">
                    <h2>What Makes Us the Best Furniture Manufacturing Company in {{ $cityDetail->city_title }}?</h2>
                    <p>At Arten Space, one of the best furniture suppliers, our commitment to excellence sets us apart as the best furniture manufacturers company in Udaipur. Here's what makes us stand out:</p>
                </div>

                <div class="manufacturing-wrapper row align-items-center">
                    <div class="col-xl-5 imageFull">
                        <img src="{{ asset('/img/manufacturing.png') }}">
                    </div>
                    <div class="col-xl-7">
                        <ul class="manufacturing-list">
                            @for($u=0; $u < count($uspDetail); $u++)
                                <li>
                                    @if($uspDetail[$u]['usp_image']!='' && file_exists(public_path('/uploads/usp/'.$uspDetail[$u]['usp_image'])))
                                        <img src="{{ asset('/uploads/usp/'.$uspDetail[$u]['usp_image']) }}">
                                    @endif
                                    <div class="manufact-content">
                                        <h5>{{ $uspDetail[$u]['usp_title'] ?? '' }}</h5>
                                        {!! $uspDetail[$u]['usp_desc'] ?? '' !!}
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(count($faqDetail) > 0)
        <div class="experience-wrap">
            <div class="container">
                <div class="extensive-wrap">
                    <h2> FAQ (Frequently Asked Questions)</h2>
                </div>
                <ul class="faq-wrap">
                    @for($f=0; $f < count($faqDetail); $f++)
                        <li>
                            <div class="faq-list">
                                <h4>{{ $faqDetail[$f]['faq_title'] ?? '' }}</h4>
                                {!! $faqDetail[$f]['faq_desc'] ?? '' !!}
                            </div>
                        </li>
                    @endfor
                </ul>
            </div>
        </div>
    @endif
    <!--city page end-->
@endsection