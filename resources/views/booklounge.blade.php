@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
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

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="featured-slider-group ">
                    <div class="row">
                        <!-- Featured Item -->
                        @if(is_array($loungeDetail) && count($loungeDetail) > 0)
                            @for($l=0; $l < count($loungeDetail); $l++)
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 featured-venues-item aos" data-aos="fade-up">
                                    <div class="listing-item mb-0">
                                        <div class="listing-img">
                                            @if($loungeDetail[$l]['lounge_image']!='' && file_exists(public_path('/uploads/lounge/'.$loungeDetail[$l]['lounge_image'])))
                                                <a href="{{ url('/book-lounge/' . $loungeDetail[$l]['lounge_slug']) }}">
                                                    <img src="{{ asset('/uploads/lounge/'.$loungeDetail[$l]['lounge_image']) }}" alt="{{ $loungeDetail[$l]['lounge_name'] }}">
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
        </div>
    </div>
    <!-- /Page Content -->
@endsection