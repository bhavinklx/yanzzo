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

    <!-- This page CSS -->
    @include('layouts.styles')
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MK2DYLZ0E4"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-MK2DYLZ0E4');
    </script>
</head>
<body>
    {{--<div id="global-loader" >
        <div class="loader-img">
            <img src="{{ url('/public/img/loader.png') }}" class="img-fluid" alt="Global">
        </div>
    </div>--}}
    <!-- Main Wrapper -->
    @if (url()->current() == route('faqs'))
        <div class="main-wrapper">
    @else
        <div class="main-wrapper terms-page contact-us-page venue-coach-details coach">
    @endif
        <!-- End Wrapper -->
        @include('layouts.header')
        @yield('content')
    </div>
    <!-- /Main Wrapper -->

    @include('layouts.footer')

    <!-- All Jquery -->
    @include('layouts.scripts')
    <!-- End Wrapper -->
    @yield('page-js')
</body>
</html>




