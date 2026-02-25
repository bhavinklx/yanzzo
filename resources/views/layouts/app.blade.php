<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yanzzo</title>

    <!-- Meta -->
    <meta name="description" content="Marketplace for Bootstrap Admin Dashboards">
    <meta property="og:title" content="Admin Templates - Dashboard Templates">
    <meta property="og:description" content="Marketplace for Bootstrap Admin Dashboards">
    <meta property="og:type" content="Website">
    <link rel="shortcut icon" href="assets/images/favicon.svg">

    <!-- This page CSS -->
    @include('layouts.styles')
</head>

<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper terms-page contact-us-page">
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
