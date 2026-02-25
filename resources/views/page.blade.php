@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
        {{--<span class="primary-right-round"></span>--}}
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
        <div class="container policy-section">
            <h3>{{ $pagesDetail->page_title ?? '' }}</h3>

            {!! $pagesDetail->page_desc ?? '' !!}
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
