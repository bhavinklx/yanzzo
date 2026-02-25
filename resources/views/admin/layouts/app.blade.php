<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link href="{{ url('/public/img/favicon.png') }}" rel="icon" type="image/png">
    <title>Admin Panel | Yanzzo</title>

    <!-- This page CSS -->
    @include('admin.layouts.styles')
    <style>
        .citiesbox {
            width: 100%;
            background-color: #f5f5f5;
            border: 1px solid #333;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            padding: 10px 0;
            transition: 0.2s;
            text-align: center;
        }

        .citiesbox:hover {
            background-color: #e6e6e6;
        }

        .citiesbox.slot-active {
            background-color: #a10000;
            color: #fff;
            font-weight: bold;
            border-color: #a10000;
        }

        /*.time-disabled {
            background-color: #f0f0f0 !important; !* Light gray, like datepicker disabled *!
            color: #999 !important;              !* Muted gray text *!
            cursor: not-allowed;
            border: 1px solid #ddd;
            pointer-events: none;
            opacity: 0.6;
        }*/

        /* Normal disabled (e.g., past times) */
        .time-disabled {
            background-color: #f0f0f0 !important;
            color: #999 !important;
            cursor: not-allowed;
            border: 1px solid #ddd;
            pointer-events: none;
            opacity: 0.6;
        }

        /* Booked slot (also disabled, but visually different) */
        .time-booked {
            background-color: #ffcccc !important; /* light red for booked */
            color: #a00 !important; /* dark red text */
            cursor: not-allowed;
            border: 1px solid #ff9999;
            pointer-events: none;
            opacity: 0.9;
        }

        /* Maintenance slot (e.g., closed for cleaning or repair) */
        .time-maintenance {
            background-color: #ffe5b4 !important; /* light orange/yellow */
            color: #a06500 !important;            /* darker orange text */
            cursor: not-allowed;
            border: 1px solid #ffcc80;
            pointer-events: none;
            opacity: 0.9;
        }

        .select-time-btn:hover:not(:disabled):not(.time-disabled) {
            background-color: #097E52;
            color: white;
        }

        .disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        .select-time-btn.active {
            background-color: #198754;  /* Bootstrap green */
            color: #fff;
            border-color: #198754;
        }

        .search-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .search-box form {
            display: flex;
            align-items: stretch;
            gap: 0; /* removes space between select and button */
        }

        .search-input {
            flex: 1;
        }

        .search-input select {
            width: 100%;
            border-radius: 8px 0 0 8px;
            border: 1px solid #ddd;
            padding: 12px;
            height: 100%;
        }

        .search-btn .btn {
            border-radius: 8px;
            padding: 12px 20px;
            height: 100%;
            background-color: #5a1ab2; /* adjust color as needed */
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-btn .btn i {
            font-size: 18px;
        }

        .search-box form {
            margin: 0;
            padding: 0;
        }

        .status-label {
            display: flex;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #000; /* black text */
        }
        .status-label span {
            width: 10px;
            height: 10px;
            display: inline-block;
            margin-right: 6px;
            border-radius: 2px;
        }
        .already-booked span {
            background-color: #ffcccc; /* light red */
        }
        .not-available span {
            background-color: #f0f0f0; /* light yellow */
        }
        .available span {
            background-color: #f5f5f5; /* light yellow */
        }
        .under-maintenance span {
            background-color: #ffe5b4; /* light blue */
        }
    </style>
</head>

<body class="fixed-layout skin-default">

<!-- Preloader - style you can find in spinners.css -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label"></p>
    </div>
</div>

<div id="main-wrapper">
    <!-- End Wrapper -->
    @include('admin.layouts.header')
    @include('admin.layouts.left')
    @yield('content')
    @include('admin.layouts.footer')
    <!-- End Wrapper -->

    <!-- All Jquery -->
    @include('admin.layouts.scripts')
    @yield('page-js')
</div>
</body>
</html>
