@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    @if($pagesDetail->page_image!='' && file_exists(public_path('uploads/pages/'.$pagesDetail->page_image)))
        @php
            $pageBanner = asset('uploads/pages/'.$pagesDetail->page_image);
        @endphp
    @else
        @php
            $pageBanner = asset('image/innerbanner.jpg');
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
                <h2 class="text-center mb-40">Contact Information</h2>
                <div class="row">
                    @if(PRIMARY_EMAIL)
                        <div class="col-12 col-sm-12 col-md-4 mb-4">
                            <div class="contact-info-card">
                                <div class="contact-icon-box">
                                    <i class="feather-mail"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h4>Email Address</h4>
                                    <p><a href="mailto:{{ PRIMARY_EMAIL }}">{{ PRIMARY_EMAIL }}</a></p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(PRIMARY_CONTACT)
                        <div class="col-12 col-sm-12 col-md-4 mb-4">
                            <div class="contact-info-card">
                                <div class="contact-icon-box">
                                    <i class="feather-phone-call"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h4>Phone Number</h4>
                                    <p><a href="tel:{{ preg_replace('/[^A-Za-z0-9\-]/', '', PRIMARY_CONTACT) }}">{{ PRIMARY_CONTACT }}</a></p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(PRIMARY_ADDRESS)
                        <div class="col-12 col-sm-12 col-md-4 mb-4">
                            <div class="contact-info-card">
                                <div class="contact-icon-box">
                                    <i class="feather-map-pin"></i>
                                </div>
                                <div class="contact-info-content">
                                    <h4>Location</h4>
                                    <p>{{ PRIMARY_ADDRESS }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <section class="section dull-bg">
            <div class="container">
                <h2 class="text-center mb-40">Reach out to us and let's smash your inquiries
                </h2>
                <form class="contact-us">
                    <div id="contactForm">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <div class="input-space mb-0">
                                    <label for="first-name" class="form-label">First Name <label class="text-danger">*</label></label>
                                    <input type="text" class="form-control" id="contact_fname" name="contact_fname" placeholder="Enter First Name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <div class="input-space mb-0">
                                    <label for="last-name" class="form-label">Last Name <label class="text-danger">*</label></label>
                                    <input type="text" class="form-control" id="contact_lname" name="contact_lname" placeholder="Enter Last Name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <div class="input-space mb-0">
                                    <label for="e-mail" class="form-label">Email <label class="text-danger">*</label></label>
                                    <input type="text" class="form-control" id="contact_email" name="contact_email" placeholder="Enter Email Address">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <div class="input-space mb-0">
                                    <input class="country" type="hidden" id="country" name="country">
                                    <input class="prefix" type="hidden" id="prefix" name="prefix">
                                    <label for="phone" class="form-label">Phone Number <label class="text-danger">*</label></label>
                                    <input type="text" class="form-control allownumericwithoutdecimal mobile" id="contact_mobile" name="contact_mobile" maxlength="10" placeholder="Enter Phone Number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="input-space mb-0">
                                    <label for="subject" class="form-label">Subject <label class="text-danger">*</label></label>
                                    <input type="text" class="form-control" id="contact_subject" name="contact_subject" placeholder="Enter Subject">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="input-space mb-0">
                                <label for="comments" class="form-label">Comments <label class="text-danger">*</label></label>
                                <textarea class="form-control" id="contact_message" name="contact_message" rows="3" placeholder="Enter Comments"></textarea>
                            </div>
                        </div>
                        <button type="button" onclick="return validate_contact();" id="submit" class="btn btn-secondary d-flex align-items-center">Submit<i class="feather-arrow-right-circle ms-2"></i></button>
                    </div>
                    <div class="text-center" id="thankMsg"></div>
                </form>
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
