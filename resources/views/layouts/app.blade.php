<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">
    <link rel="canonical" href="@yield('canonical')" />

    <meta property="og:title" content="@yield('title')" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:url" content="@yield('canonical')" />
    <meta property="og:type" content="Website">
    <link rel="shortcut icon" href="{{ asset('assets/image/favicon.ico') }}">

    <!-- This page CSS -->
    @include('layouts.styles')
    @yield('page-css')
</head>

<body>
    <!-- Main Wrapper -->
    @if (request()->is('machines/*') || request()->is('my-account') || request()->is('seller-inquiry') || request()->is('chat') || request()->is('my-listing'))
        <div class="main-wrapper terms-page contact-us-page venue-coach-details coach-detail">
    @else
        <div class="main-wrapper terms-page contact-us-page">
    @endif

        @include('layouts.header')
        @yield('content')
        @include('layouts.footer')
    </div>
    <!-- Page wrapper ends -->

    <!-- All Jquery -->
    @include('layouts.scripts')
    @yield('page-js')
</body>
</html>
