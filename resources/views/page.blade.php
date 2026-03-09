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
                <h2 class="mb-40">{{ $pagesDetail->page_title ?? '' }}</h2>
                {!! $pagesDetail->page_desc ?? '' !!}
            </div>
        </section>
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
