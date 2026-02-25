@php
    $url = url()->current();
    $segments = explode('/', $url);
    $slug = end($segments);
@endphp

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <!-- Footer Join -->
        <div class="footer-join aos" data-aos="fade-up">
            <h2>We Welcome Your Voice and Energy</h2>
            <p class="sub-title">Join our vibrant karaoke community today and sing, celebrate, and shine with us!</p>
            {{--<a href="register.html" class="btn btn-primary"><i class="feather-user-plus"></i> Join With Us</a>--}}
        </div>
        <!-- /Footer Join -->

        <!-- Footer Top -->
        <div class="footer-top">
            <div class="row">
                <div class="col col-lg-3 col-md-6 col-sm-6 widget-border">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Contact us</h4>
                        <div class="footer-address-blk">
                            @if(PRIMARY_CONTACT)
                                <div class="footer-call">
                                    <span>Customer Care</span>
                                    <p>{{--<a href="tel:{{ preg_replace('/[^A-Za-z0-9\-]/', '', PRIMARY_CONTACT) }}">--}}{{ PRIMARY_CONTACT }}{{--</a>--}}</p>
                                </div>
                            @endif
                            @if(PRIMARY_EMAIL)
                                <div class="footer-call">
                                    <span>Need Support</span>
                                    <p>{{--<a href="mailto:{{ PRIMARY_EMAIL }}">--}}{{ PRIMARY_EMAIL }}{{--</a>--}}</p>
                                </div>
                            @endif
                        </div>
                        <div class="social-icon">
                            <ul>
                                @if(SOCIAL_FACEBOOK)
                                    <li>
                                        <a href="{{ SOCIAL_FACEBOOK }}" class="facebook" target="_blank"><i class="fab fa-facebook-f"></i> </a>
                                    </li>
                                @endif
                                @if(SOCIAL_TWITTER)
                                    <li>
                                        <a href="{{ SOCIAL_TWITTER }}" class="twitter" target="_blank"><i class="fab fa-twitter"></i> </a>
                                    </li>
                                @endif
                                @if(SOCIAL_INSTAGRAM)
                                    <li>
                                        <a href="{{ SOCIAL_INSTAGRAM }}" class="instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                                    </li>
                                @endif
                                @if(SOCIAL_LINKEDIN)
                                    <li>
                                        <a href="{{ SOCIAL_LINKEDIN }}" class="linked-in" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    </li>
                                @endif
                                @if(SOCIAL_YOUTUBE)
                                    <li>
                                        <a href="{{ SOCIAL_YOUTUBE }}" class="instagram" target="_blank"><i class="fab fa-youtube"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- /Footer Widget -->
                </div>
                <div class="col col-lg-3 col-md-6 col-sm-6 widget-border">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Quick Links</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereNotIn('page_id', [7, 8, 9, 10, 11, 12, 13])->orderBy('page_order')->get();
                            @endphp
                            @if(isset($footerPages) && count($footerPages) > 0)
                                @foreach($footerPages as $key => $pages)
                                    @if($pages->page_link!='')
                                        @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                                    @elseif($pages->page_slug=="home")
                                        @php $SITE_URL = url('/'); @endphp
                                    @else
                                        @php $SITE_URL = url($pages->page_slug . '/'); @endphp
                                    @endif
                                    <li><a href="{{ $SITE_URL }}">{{ $pages->page_title }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- /Footer Widget -->
                </div>
                <div class="col col-lg-3 col-md-6 col-sm-6 widget-border">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Support</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [7, 8, 9, 12, 13])->orderBy('page_order')->get();
                            @endphp
                            @if(isset($footerPages) && count($footerPages) > 0)
                                @foreach($footerPages as $key => $pages)
                                    @if($pages->page_link!='')
                                        @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                                    @elseif($pages->page_slug=="home")
                                        @php $SITE_URL = url('/'); @endphp
                                    @else
                                        @php $SITE_URL = url($pages->page_slug . '/'); @endphp
                                    @endif
                                    <li><a href="{{ $SITE_URL }}">{{ $pages->page_title }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- /Footer Widget -->
                </div>
                <div class="col col-lg-3 col-md-6 col-sm-6 widget-border">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Other Links</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [10, 11])->orderBy('page_order')->get();
                            @endphp
                            @if(isset($footerPages) && count($footerPages) > 0)
                                @foreach($footerPages as $key => $pages)
                                    @if($pages->page_link!='')
                                        @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                                    @elseif($pages->page_slug=="home")
                                        @php $SITE_URL = url('/'); @endphp
                                    @else
                                        @php $SITE_URL = url($pages->page_slug . '/'); @endphp
                                    @endif
                                    <li><a href="{{ $SITE_URL }}">{{ $pages->page_title }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- /Footer Widget -->
                </div>
            </div>
        </div>
        <!-- /Footer Top -->
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <!-- Copyright -->
            <div class="copyright">
                <div class="row align-items-center text-center">
                    <div class="col-md-12">
                        <div class="copyright-text">
                            <p class="mb-0">&copy; {{ date('Y') }} YAARIOKE - Powered By - ALANKRIT TECHNOLOGIES PRIVATE LIMITED - All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Copyright -->
        </div>
    </div>
    <!-- /Footer Bottom -->
</footer>
<!-- /Footer -->

<!-- signup-modal -->
<div class="modal fade" id="signup-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" id="form-area-signup">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="signup_form" name="signup_form" enctype="multipart/form-data">
                    <input type="hidden" id="URI" name="URI" value="<?= base64_encode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>">
                    <div class="mb-3">
                        <label>Your Name</label>
                        <input class="form-control mb-0" type="text" id="user_name" name="user_name">
                        <span class="help-block" id="uname_msg"></span>
                    </div>
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input class="form-control mb-0 " type="text" id="user_email" name="user_email">
                        <span class="help-block" id="uemail_msg"></span>
                    </div>
                    <div class="mb-3">
                        <label>Mobile Number</label>
                        <input class="form-control mb-0 mobile1" type="text" id="user_mobile" name="user_mobile" autocomplete="new-password" minlength="10" maxlength="10" onkeypress="return isNumberKey(event);">
                        <span class="help-block" id="umobile_msg"></span>
                    </div>
                    {{--<div class="mb-3">
                        <label>Password</label>
                        <input class="form-control mb-0" type="password" id="user_password" name="user_password" autocomplete="new-password">
                        <span class="help-block" id="upass_msg"></span>
                    </div>--}}
                    <div class="mb-3">
                        <input class="form-check-input" type="checkbox" id="user_tc" name="user_tc" value="yes" checked>
                        <label for="user_tc">I Agree With <a href="{{ url('/terms-conditions') }}" target="_blank">Terms & Conditions</a> And <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy Policy</a></label>
                        <br>
                        <span class="help-block" id="user_tc_msg"></span>
                    </div>
                    <button class="btn btn-primary w-100" type="button" type="button" id="signupBtn" name="signupBtn" onclick="return validate_signup();">Sign Up</button>
                </form>
            </div>
            <div class="modal-footer" style="display: unset !important;">
                <p style="text-align: center">Already have an Account? <a href="javascript: void (0)" class="text-blue" onclick="signin_popup();">Login</a></p>
            </div>
        </div>
    </div>
</div>
<!-- signup-modal -->

<!-- signin-modal -->
<div class="modal fade" id="signin-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" id="form-area-login">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="login_form" name="login_form" enctype="multipart/form-data">
                    <input type="hidden" id="URI" name="URI" value="<?= base64_encode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>">
                    <div class="mb-3">
                        <label>Mobile Number</label>
                        <input class="form-control mb-0 allownumericwithoutdecimal" type="text" id="username" name="username" maxlength="10" >
                        <span class="help-block" id="username_msg"></span>
                    </div>
                    {{--<div class="mb-3">
                        <label>Password</label>
                        <input class="form-control mb-0" type="password" id="userpassword" name="userpassword" autocomplete="new-password">
                        <span class="help-block" id="userpassword_msg"></span>
                    </div>--}}
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <div class="form-group col-lg-6" style="text-align: left">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" value="yes" checked>
                                <label for="remember_me">Remember Password</label>
                            </div>
                            {{--<div class="form-group col-lg-6" style="text-align: right">
                                <a href="javascript: void (0)" onclick="forgot_popup()">
                                    Forgot Password?
                                </a>
                            </div>--}}
                        </div>
                    </div>
                    <button class="btn btn-primary w-100" type="button" id="loginBtn" name="loginBtn" onclick="return validate_login()">Sign In</button>
                </form>
                <div style="text-align: center" id="loginThankMsg"></div>
            </div>
            <div class="modal-footer" style="display: unset !important;">
                <p style="text-align: center">Create an Account? <a href="javascript: void (0)" class="text-blue" onclick="signup_popup();">Register</a></p>
            </div>
        </div>
    </div>
</div>
<!-- signin-modal -->

<!-- forgot-modal -->
<div class="modal fade" id="forgot-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" id="form-area-forgot">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="forgot_form" name="forgot_form" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Mobile Number</label>
                        <input class="form-control mb-0 allownumericwithoutdecimal" type="text" id="forgot_mobile" name="forgot_mobile" maxlength="10">
                        <span class="help-block" id="forgotmobile_msg"></span>
                    </div>
                    <button class="btn btn-primary w-100" type="button" id="forgotBtn" name="forgotBtn" onclick="return validate_forgot()">Forgot Password</button>
                </form>
                <div style="text-align: center" id="fotpThankMsg"></div>
            </div>
        </div>
    </div>
</div>


<!-- Deactive Modal -->
<div class="modal custom-modal fade deactive-modal" id="removeDiscount" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <a  class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span class="align-center" aria-hidden="true"><i class="feather-x"></i></span>
                </a>
            </div>
            <div class="modal-body">
                <!-- Deactive Account -->
                <div class="account-deactive">
                    <img src="{{ url('/public/img/icons/deactive-profile.svg') }}" alt="Icon">
                    <h3>Are You Sure You Want to Remove Discount</h3>
                    <p>If yes please click “Yes” button</p>
                    <div class="convenient-btns">
                        <a href="javascript:void(0);" id="confirmRemoveDiscount" data-bs-dismiss="modal" class="btn btn-primary d-inline-flex align-items-center">
                            Yes <span><i class="feather-arrow-right-circle ms-2"></i></span>
                        </a>
                        <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-secondary d-inline-flex align-items-center">
                            No <span><i class="feather-arrow-right-circle ms-2"></i></span>
                        </a>
                    </div>
                </div>
                <!-- /Deactive Account -->
            </div>
        </div>
    </div>
</div>
<!-- /Deactive Modal -->

<!-- Modal -->
<div class="modal fade" id="timeSlotModal" tabindex="-1" aria-labelledby="timeSlotModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="timeSlotModalLabel">Choose start time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Status indicators -->
                <div class="status-info d-flex justify-content-around mb-3 text-center">
                    <div class="status-label already-booked">
                        <span></span> Already Booked
                    </div>
                    <div class="status-label under-maintenance">
                        <span></span> Under Maintenance
                    </div>
                    <div class="status-label available">
                        <span></span> Available
                    </div>
                </div>
                <hr class="m-t-0">
                <div id="timeSlotsContainer" class="row g-2"></div>
                <hr class="m-t-0">
                <div class="text-center">
                    <button class="btn btn-primary" type="button" id="submitTimes">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" id="form-area-login">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="bookingFrm" name="bookingFrm" enctype="multipart/form-data">
                    <input type="hidden" id="order_id" name="order_id">
                    <input type="hidden" id="adults" name="adults">
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="text" class="form-control datetimepicker" placeholder="Select Date" id="start_date" name="start_date">
                        <span class="help-block" id="msg_start_date"></span>
                    </div>
                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="text" class="form-control" placeholder="Select Start Time" id="start_time" name="start_time" readonly onclick="loadTimePopup();">
                        <span class="help-block" id="msg_start_time"></span>
                    </div>
                    {{--<div class="mb-3">
                        <div class="select-guest">
                            <h6>Select Guest</h6>
                            <span class="primary-text"> (<span id="maxguest"></span> guests maximum)</span>

                            <div class="d-md-flex justify-content-between align-items-center mt-3">
                                <div class="qty-item text-center">
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <a href="javascript:void(0);" class="dec d-flex justify-content-center align-items-center me-2">
                                            <i class="feather-minus-circle"></i>
                                        </a>
                                        <input type="number" class="form-control text-center" id="adults" name="adults" value="0" max="" readonly style="width: 70px;">
                                        <a href="javascript:void(0);" class="inc d-flex justify-content-center align-items-center ms-2">
                                            <i class="feather-plus-circle"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <span class="dark-text d-block fw-semibold">Adults</span>
                                        <span class="dull-text small">Ages 12 and up</span>
                                    </div>
                                    <div id="msg_adults" class="mt-1 text-danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                    <button class="btn btn-primary w-100" type="button" id="bookingBtn" onclick="return validate_booking()">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>