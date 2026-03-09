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
    <div class="content contact-group mb-0">
        <section class="seller-section">
            <div class="container">
                <div class="row" id="filter_by">
                    @if(is_array($blogDetail) && count($blogDetail) > 0)
                        @for($b=0; $b < count($blogDetail); $b++)
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <!-- Blog -->
                                <div class="featured-venues-item">
                                    <div class="listing-item">
                                        <div class="listing-img">
                                            @if($blogDetail[$b]['blog_image']!='' && file_exists(public_path('/uploads/blog/'.$blogDetail[$b]['blog_image'])))
                                                <a href="{{ url('/' . $pagesDetail->page_slug . '/'. $blogDetail[$b]['blog_slug']) }}">
                                                    <img src="{{ asset('/uploads/blog/'.$blogDetail[$b]['blog_image']) }}" class="img-fluid" alt="{{ $blogDetail[$b]['blog_title'] }}">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="listing-content news-content">
                                            <div class="listing-venue-owner">
                                                <div class="navigation">
                                                    <i class="feather-calendar"></i> {{ date('d M, Y', strtotime($blogDetail[$b]['blog_date'])) }}
                                                </div>
                                            </div>
                                            <h3 class="listing-title blog-title">
                                                <a href="{{ url('/' . $pagesDetail->page_slug . '/'. $blogDetail[$b]['blog_slug']) }}">{{ $blogDetail[$b]['blog_title'] }}</a>
                                            </h3>
                                            <div class="listing-button read-new text-center">
                                                <a href="{{ url('/' . $pagesDetail->page_slug . '/'. $blogDetail[$b]['blog_slug']) }}"><span>5 Min To Read</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Blog -->
                            </div>
                        @endfor
                    @endif
                </div>

                <div class="view-all text-center aos blog-pagination" data-aos="fade-up" id="view_message">
                    <a href="javascript: void(0);" onclick="load_blog()" class="btn btn-secondary d-inline-flex align-items-center">Load More<span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span></a>
                </div>
            </div>
        </section>
    </div>
    <!-- /Page Content -->

    <!-- /Page Content -->
    <input type="hidden" id="page" value="2">
    <input type="hidden" id="rows" value="{{ $pagecount }}">
    <input type="hidden" id="allrows" value="{{ $rows }}">
@endsection
@section('page-js')
    <script>
        $(document).ready(function () {
            if(parseInt($("#rows").val()) >= parseInt($("#allrows").val())){
                $('#view_message').html('');
            }
        });

        function load_blog()
        {
            var page = $("#page").val();
            $("#view_more").html('<img src="{{ asset('/img/loader.gif') }}" width="20px">');
            setTimeout(function (){
                $.ajax({
                    url: "blogs/",
                    type: "GET",
                    data: { act:'load_blog', page:page },
                    success: function (response) {
                        //alert(response)
                        $('#filter_by').append(response);
                        if (response == '') {
                            $('#view_message').html('');
                        } else {
                            var start_no = parseInt($("#page").val()) + 1;
                            $("#rows").val(parseInt($("#page").val()) * 9);
                            $("#page").val(parseInt(start_no));
                            if(parseInt($("#rows").val()) >= parseInt($("#allrows").val())){
                                $('#view_message').html('');
                            } else {
                                $('#view_message').html('<a href="javascript: void(0);" onclick="load_blog()" class="btn btn-secondary d-inline-flex align-items-center">Load More<span class="lh-1"><i class="feather-arrow-right-circle ms-2"></i></span></a>');
                            }
                        }
                    }
                });
            }, 100)
        }
    </script>
@endsection
