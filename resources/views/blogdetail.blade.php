@extends("layouts.app")
{{--@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')--}}
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
                <li><a href="{{ url('/'.$pagesDetail->page_slug) }}">{{ $pagesDetail->page_title ?? '' }}</a></li>
                <li>{{ $blogDetail->blog_title ?? '' }}</li>
            </ul>
        </div>
    </section>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content blog-details">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 mx-auto">
                    <!-- Blog -->
                    <div class="featured-venues-item">
                        <div class="listing-item blog-info">
                            @if($blogDetail->blog_image!='' && file_exists(public_path('/uploads/blog/'.$blogDetail->blog_image)))
                                <div class="listing-img">
                                    <img src="{{ asset('/uploads/blog/'.$blogDetail->blog_image) }}" class="img-fluid" alt="{{ $blogDetail->blog_title }}">
                                </div>
                            @endif
                            <div class="listing-content news-content" style="padding: 24px 0px 24px 0px">
                                <div class="listing-venue-owner blog-detail-owner d-lg-flex justify-content-between align-items-center">
                                    <div class="navigation">
                                        <i class="feather-calendar"></i>{{ date('d M, Y', strtotime($blogDetail->blog_date)) }}
                                    </div>
                                </div>
                                <!-- <hr> -->
                                <!-- <hr> -->
                                <h2 class="listing-title">
                                    {{ $blogDetail->blog_title ?? '' }}
                                </h2>

                                {!! $blogDetail->blog_desc !!}
                            </div>
                            <!-- <hr> -->
                        </div>
                        <div class="row align-items-center">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                                <div class="d-flex align-items-center social-medias-wrapper">
                                    <h6>Share on :</h6>
                                    {{--<ul class="social-medias d-flex">
                                        <li class="facebook"><a href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a></li>
                                        <li class="linkedin"><a href="javascript:void(0);"><i class="fa-brands fa-linkedin"></i></a></li>
                                        <li class="instagram"><a href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a></li>
                                        <li class="twitter"><a href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a></li>
                                        <li class="pinterest"><a href="javascript:void(0);"><i class="fa-brands fa-pinterest"></i></a></li>
                                    </ul>--}}
                                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                                        <a class="a2a_button_facebook"></a>
                                        <a class="a2a_button_twitter"></a>
                                        <a class="a2a_button_linkedin"></a>
                                        <a class="a2a_button_whatsapp"></a>
                                        <!--<a class="a2a_dd" href="https://www.addtoany.com/share"></a>-->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /Blog -->
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection
@section('page-js')
    <script type="text/javascript">
        $(document).ready(function () {
            AOS.init({
                duration:1200,
                once:true
            });
        });
    </script>
@endsection
