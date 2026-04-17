<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/owl-carousel/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/owl-carousel/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/aos/aos.css') }}">
<link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/fontawesome/css/all.min.css') }}">
<!-- Feathericon CSS -->
<link rel="stylesheet" href="{{ url('css/feather.css') }}">
<link rel="stylesheet" href="{{ url('css/style.css') }}">
<link rel="stylesheet" href="{{ url('css/chat.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('build/css/intlTelInput.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('build/css/demo.css') }}">
<!-- jQuery -->
<script src="{{ url('js/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Global Form Styling matched to Seller Inquiry */
    .form-control, 
    .form-select,
    .dropzone,
    .input-space .form-control, 
    .input-space .form-select {
        background-color: #fcfcfc !important;
        border: 1px solid #e0e0e0 !important;
        border-radius: 6px !important;
        transition: all 0.3s ease;
        padding: 10px 15px;
    }
    .form-control:focus, 
    .form-select:focus,
    .input-space .form-control:focus, 
    .input-space .form-select:focus {
        background-color: #fff !important;
        border-color: #1b84ee !important;
        box-shadow: 0 0 0 3px rgba(27, 132, 238, 0.1) !important;
        outline: none;
    }
    .form-label,
    .input-space .form-label {
        color: #444;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }
    .form-control.is-invalid,
    .form-select.is-invalid,
    .input-space .form-control.is-invalid,
    .input-space .form-select.is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff8f8 !important;
    }
    .dropzone {
        border: 2px dashed #d1d1d1 !important;
        background: #fdfdfd !important;
        cursor: pointer;
    }
    .dropzone:hover {
        border-color: #1b84ee !important;
        background: #f8fbff !important;
    }
    /* Modal Specific overrides */
    .modal-content .form-control {
        margin-bottom: 5px !important;
    }
    .modal-content .help-block {
        font-size: 12px;
        margin-bottom: 10px;
        display: block;
    }

    /* Contact Page Info Cards */
    .contact-info-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px 25px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        margin-bottom: 24px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
    }
    .contact-info-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        border-color: rgba(55, 174, 148, 0.2);
    }
    .contact-icon-box {
        width: 65px;
        height: 65px;
        min-width: 65px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0d6e7a 0%, #39a68d 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 20px;
        color: #fff;
        font-size: 26px;
        box-shadow: 0 8px 20px rgba(13, 110, 122, 0.25);
    }
    .contact-info-content h4 {
        margin-bottom: 2px;
        font-weight: 800;
        color: #102a3a;
        font-size: 19px;
        letter-spacing: -0.3px;
    }
    .contact-info-content p {
        margin-bottom: 0;
        color: #7a8a9a;
        font-size: 15px;
        line-height: 1.4;
        word-break: break-all;
    }
    .contact-info-content a {
        color: inherit;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .contact-info-content a:hover {
        color: #37ae94 !important;
    }
    
    .contact-group .seller-section {
        background-color: #f9fbfb;
        padding: 80px 0;
    }
    .contact-group h2 {
        font-weight: 800;
        color: #102a3a;
        margin-bottom: 50px;
    }
    .contact-group .section.dull-bg {
        background-color: #fff;
        padding: 80px 0;
    }
    .contact-group .btn-secondary {
        background: linear-gradient(135deg, #0d6e7a 0%, #39a68d 100%) !important;
        border: none !important;
        padding: 12px 35px !important;
        font-weight: 700 !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 20px rgba(57, 166, 141, 0.25) !important;
        transition: all 0.3s ease !important;
        font-size: 16px !important;
    }
    .contact-group .btn-secondary:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 25px rgba(57, 166, 141, 0.35) !important;
    }

    /* Shared Header Button Styling (Sell & User Profile) */
    .btn-sell, .user-header-toggle {
        background: #fff !important;
        border: 2px solid #2d4487 !important;
        color: #2d4487 !important;
        font-weight: 700 !important;
        border-radius: 50px !important;
        height: 40px !important;
        width: 155px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-transform: uppercase;
        font-size: 13px !important;
        transition: all 0.3s ease;
        padding: 0 !important;
        box-shadow: none !important;
        text-decoration: none !important;
        gap: 8px !important;
    }
    .user-header-dropdown {
        position: relative !important;
        width: 155px !important;
        display: inline-block !important;
    }
    .user-header-menu {
        left: 50% !important;
        right: auto !important;
        top: 100% !important;
        margin-top: 5px !important;
        transform: translateX(-50%) !important;
        width: 200px !important;
        text-align: left;
        pointer-events: auto !important;
    }
    /* Bridge the gap between button and menu to prevent closing on hover */
    .user-header-menu::before {
        content: "";
        position: absolute;
        top: -15px;
        left: 0;
        right: 0;
        height: 15px;
        background: transparent;
    }
    .user-header-toggle {
        text-transform: none !important;
        font-size: 14px !important;
    }
    .user-header-toggle span.user-name-text {
        max-width: 90px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
        text-align: center;
    }
    .btn-sell:hover, .user-header-toggle:hover {
        background: #2d4487 !important;
        color: #fff !important;
        transform: none !important;
    }
    .btn-sell i, .user-header-toggle i {
        font-size: 16px;
    }
</style>