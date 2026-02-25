@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
        <span class="primary-right-round"></span>
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
            <header class="text-center mt-0">
                <a href="{{ url('/') }}">
                    <img src="{{ url('/public/img/logo.png') }}" class="img-fluid" alt="Logo">
                </a>
            </header>
            <div class="errors-img">
                <img src="{{ url('/public/img/404.png') }}" class="img-fluid" alt="404" width="50%">
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-5 mx-auto text-center">
                    {{--<h3>This site is currently <br> under maintenance</h3>--}}
                    <p>We apologize for the inconvenience caused We’ve almost done.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary d-inline-flex align-items-center">Go to Home<i class="feather-arrow-right-circle ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection