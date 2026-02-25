@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
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
    <div class="content blog-details contact-group">
        <div class="container">
            <h2 class="text-center mb-40">Contact Information</h2>
            <div class="row mb-40">
                @if(PRIMARY_EMAIL)
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="d-flex justify-content-start align-items-center details">
                            <i class="feather-mail d-flex justify-content-center align-items-center"></i>
                            <div class="info">
                                <h4>Email Address</h4>
                                <p><a href="mailto:{{ PRIMARY_EMAIL }}">{{ PRIMARY_EMAIL }}</a></p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(PRIMARY_CONTACT)
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="d-flex justify-content-start align-items-center details">
                            <i class="feather-phone-call d-flex justify-content-center align-items-center"></i>
                            <div class="info">
                                <h4>Phone Number</h4>
                                <p><a href="tel:{{ preg_replace('/[^A-Za-z0-9\-]/', '', PRIMARY_CONTACT) }}">{{ PRIMARY_CONTACT }}</a></p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(PRIMARY_ADDRESS)
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <div class="d-flex justify-content-start align-items-center details">
                            <i class="feather-map-pin d-flex justify-content-center align-items-center"></i>
                            <div class="info">
                                <h4>Location</h4>
                                <p>{{ PRIMARY_ADDRESS }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            {{--<div class="row">
                <div class="col-12">
                    <div class="google-maps">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2967.8862835683544!2d-73.98256668525309!3d41.93829486962529!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89dd0ee3286615b7%3A0x42bfa96cc2ce4381!2s132%20Kingston%20St%2C%20Kingston%2C%20NY%2012401%2C%20USA!5e0!3m2!1sen!2sin!4v1670922579281!5m2!1sen!2sin" height="445" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>--}}
        </div>
        <section class="section dull-bg">
            <div class="container">
                <h2 class="text-center mb-40">Reach out to us and let's smash your inquiries
                </h2>
                <form class="contact-us">
                    <div id="contactForm">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <label for="first-name" class="form-label">First Name <label class="text-danger">*</label></label>
                                <input type="text" class="form-control" id="contact_fname" name="contact_fname" placeholder="Enter First Name">
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <label for="last-name" class="form-label">Last Name <label class="text-danger">*</label></label>
                                <input type="text" class="form-control" id="contact_lname" name="contact_lname" placeholder="Enter Last Name">
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <label for="e-mail" class="form-label">Email <label class="text-danger">*</label></label>
                                <input type="text" class="form-control" id="contact_email" name="contact_email" placeholder="Enter Email Address">
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 mb-3">
                                <input class="country" type="hidden" id="country" name="country">
                                <input class="prefix" type="hidden" id="prefix" name="prefix">
                                <label for="phone" class="form-label">Phone Number <label class="text-danger">*</label></label>
                                <input type="text" class="form-control allownumericwithoutdecimal mobile" id="contact_mobile" name="contact_mobile" maxlength="10" placeholder="Enter Phone Number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="subject" class="form-label">Subject <label class="text-danger">*</label></label>
                                <input type="text" class="form-control" id="contact_subject" name="contact_subject" placeholder="Enter Subject">
                            </div>
                        </div>
                        <div>
                            <label for="comments" class="form-label">Comments <label class="text-danger">*</label></label>
                            <textarea class="form-control" id="contact_message" name="contact_message" rows="3" placeholder="Enter Comments"></textarea>
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
