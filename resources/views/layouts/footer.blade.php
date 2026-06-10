<!-- Footer -->
<footer class="footer">
    <div class="container">
        <!-- Footer Join -->
        <div class="footer-join aos" data-aos="fade-up">
            <h2>We Welcome Your Passion and Expertise</h2>
            <p class="sub-title">Join our innovative machinery marketplace and grow together with us.</p>
        </div>
        <!-- /Footer Join -->

        <!-- Footer Top -->
        <div class="footer-top">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Contact us</h4>
                        <div class="footer-address-blk">
                            @if(PRIMARY_CONTACT)
                                <div class="footer-call">
                                    <span>Customer Care</span>
                                    <p>{{--<a
                                            href="tel:{{ preg_replace('/[^A-Za-z0-9\-]/', '', PRIMARY_CONTACT) }}">--}}{{ PRIMARY_CONTACT }}{{--</a>--}}
                                    </p>
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
                                        <a href="{{ SOCIAL_FACEBOOK }}" class="facebook" target="_blank"><i
                                                class="fab fa-facebook-f"></i> </a>
                                    </li>
                                @endif
                                @if(SOCIAL_TWITTER)
                                    <li>
                                        <a href="{{ SOCIAL_TWITTER }}" class="twitter" target="_blank"><i
                                                class="fab fa-twitter"></i> </a>
                                    </li>
                                @endif
                                @if(SOCIAL_INSTAGRAM)
                                    <li>
                                        <a href="{{ SOCIAL_INSTAGRAM }}" class="instagram" target="_blank"><i
                                                class="fab fa-instagram"></i></a>
                                    </li>
                                @endif
                                @if(SOCIAL_LINKEDIN)
                                    <li>
                                        <a href="{{ SOCIAL_LINKEDIN }}" class="linked-in" target="_blank"><i
                                                class="fab fa-linkedin-in"></i></a>
                                    </li>
                                @endif
                                @if(SOCIAL_YOUTUBE)
                                    <li>
                                        <a href="{{ SOCIAL_YOUTUBE }}" class="instagram" target="_blank"><i
                                                class="fab fa-youtube"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- /Footer Widget -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Quick Links</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [1, 2, 3, 5])->orderBy('page_order')->get();
                            @endphp
                            @if(isset($footerPages) && count($footerPages) > 0)
                                @foreach($footerPages as $key => $pages)
                                    @if($pages->page_link != '')
                                        @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                                    @elseif($pages->page_slug == "home")
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
                <div class="col-lg-3 col-md-6">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Support</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [7, 8, 9, 12])->orderBy('page_order')->get();
                            @endphp
                            @if(isset($footerPages) && count($footerPages) > 0)
                                @foreach($footerPages as $key => $pages)
                                    @if($pages->page_link != '')
                                        @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                                    @elseif($pages->page_slug == "home")
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
                <div class="col-lg-3 col-md-6">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Other Links</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [4, 6])->orderBy('page_order')->get();
                            @endphp
                            @if(isset($footerPages) && count($footerPages) > 0)
                                @foreach($footerPages as $key => $pages)
                                    @if($pages->page_link != '')
                                        @php $SITE_URL = ($pages->page_link != '#') ? $pages->page_link : 'javascript: void(0)'; @endphp
                                    @elseif($pages->page_slug == "home")
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
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="copyright-text">
                            <p class="mb-0">&copy; {{ date('Y') }} Yanzzo - All rights reserved.</p>
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
<div class="modal fade" id="signup-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" id="form-area-signup">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i
                        class="feather-user-plus me-2 text-primary"></i>Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="signup_form" name="signup_form" enctype="multipart/form-data">
                    <input type="hidden" id="URI" name="URI"
                        value="<?= base64_encode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>">
                    <div class="mb-3">
                        <div class="input-space mb-0">
                            <label class="form-label">Your Name</label>
                            <input class="form-control mb-0" type="text" id="user_name" name="user_name"
                                placeholder="Enter your name">
                        </div>
                        <span class="help-block" id="uname_msg"></span>
                    </div>
                    <div class="mb-3">
                        <div class="input-space mb-0">
                            <label class="form-label">Email Address</label>
                            <input class="form-control mb-0 " type="text" id="user_email" name="user_email"
                                placeholder="Enter your email">
                        </div>
                        <span class="help-block" id="uemail_msg"></span>
                    </div>
                    <div class="mb-3">
                        <div class="input-space mb-0">
                            <label class="form-label">Mobile Number</label>
                            <input class="form-control mb-0 mobile1" type="text" id="user_mobile" name="user_mobile"
                                autocomplete="new-password" minlength="10" maxlength="10"
                                onkeypress="return isNumberKey(event);" placeholder="Enter your mobile number">
                        </div>
                        <span class="help-block" id="umobile_msg"></span>
                    </div>
                    <div class="mb-3">
                        <div class="input-space mb-0">
                            <label class="form-label">Password</label>
                            <div class="position-relative">
                                <input class="form-control mb-0" type="password" id="user_password" name="user_password"
                                    autocomplete="new-password" placeholder="Enter your password"
                                    style="padding-right: 40px;">
                                <span class="position-absolute"
                                    onclick="togglePasswordVisibility('user_password', this)"
                                    style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;">
                                    <i class="far fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                        <span class="help-block" id="upass_msg"></span>
                    </div>
                    <div class="mb-3">
                        <input class="form-check-input" type="checkbox" id="user_is_whatsapp" name="user_is_whatsapp"
                            value="1">
                        <label for="user_is_whatsapp">Is this your WhatsApp number?</label>
                    </div>
                    <div class="mb-3">
                        <input class="form-check-input" type="checkbox" id="user_tc" name="user_tc" value="yes" checked>
                        <label for="user_tc">I Agree With <a href="{{ url('/terms-conditions') }}" target="_blank">Terms
                                & Conditions</a> And <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy
                                Policy</a></label>
                        <br>
                        <span class="help-block" id="user_tc_msg"></span>
                    </div>
                    <button class="btn btn-primary w-100 mb-3" type="button" id="signupBtn" name="signupBtn"
                        onclick="return validate_signup();">Sign Up</button>
                </form>
            </div>
            <div class="modal-footer" style="display: unset !important;">
                <p style="text-align: center">Already have an Account? <a href="javascript: void (0)" class="text-blue"
                        onclick="signin_popup();">Login</a></p>
            </div>
        </div>
    </div>
</div>
<!-- signup-modal -->

<!-- signin-modal -->
<div class="modal fade" id="signin-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" id="form-area-login">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="feather-log-in me-2 text-primary"></i>Login
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="login_form" name="login_form" enctype="multipart/form-data">
                    <input type="hidden" id="URI" name="URI"
                        value="<?= base64_encode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>">
                    <div class="mb-3">
                        <div class="input-space mb-0">
                            <label class="form-label">Mobile Number</label>
                            <input class="form-control mb-0 allownumericwithoutdecimal" type="text" id="username"
                                name="username" maxlength="10" placeholder="Enter your mobile number">
                        </div>
                        <span class="help-block" id="username_msg"></span>
                    </div>
                    <div class="mb-3">
                        <div class="input-space mb-0">
                            <label class="form-label">Password</label>
                            <div class="position-relative">
                                <input class="form-control mb-0" type="password" id="userpassword" name="userpassword"
                                    autocomplete="new-password" placeholder="Enter your password"
                                    style="padding-right: 40px;">
                                <span class="position-absolute" onclick="togglePasswordVisibility('userpassword', this)"
                                    style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;">
                                    <i class="far fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                        <span class="help-block" id="userpassword_msg"></span>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <div class="form-group col-lg-6" style="text-align: left">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me"
                                    value="yes" checked>
                                <label for="remember_me">Remember Password</label>
                            </div>
                            <div class="form-group col-lg-6" style="text-align: right">
                                <a href="javascript: void (0)" onclick="forgot_popup()">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mb-3" type="button" id="loginBtn" name="loginBtn"
                        onclick="return validate_login()">Sign In</button>
                </form>
                <div style="text-align: center" id="loginThankMsg"></div>
            </div>
            <div class="modal-footer" style="display: unset !important;">
                <p style="text-align: center">Create an Account? <a href="javascript: void (0)" class="text-blue"
                        onclick="signup_popup();">Register</a></p>
            </div>
        </div>
    </div>
</div>
<!-- signin-modal -->

<!-- forgot-modal -->
<div class="modal fade" id="forgot-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" id="form-area-forgot">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="forgot_form" name="forgot_form" enctype="multipart/form-data">
                    <div class="mb-3">
                        <div class="input-space mb-0">
                            <label class="form-label">Email Address</label>
                            <input class="form-control mb-0" type="email" id="forgot_email" name="forgot_email"
                                placeholder="Enter your registered email">
                        </div>
                        <span class="help-block" id="forgotemail_msg"></span>
                    </div>
                    <button class="btn btn-primary w-100 mb-3" type="button" id="forgotBtn" name="forgotBtn"
                        onclick="return validate_forgot()">Forgot Password</button>
                </form>
                <div style="text-align: center" id="fotpThankMsg"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconSpan) {
        var input = document.getElementById(inputId);
        var icon = iconSpan.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>