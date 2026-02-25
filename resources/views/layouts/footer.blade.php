<!-- Footer -->
<footer class="footer">
    <div class="container">
        <!-- Footer Join -->
        <div class="footer-join aos" data-aos="fade-up">
            <h2>We Welcome Your Passion And Expertise</h2>
            <p class="sub-title">Join our empowering sports community today and grow with us.</p>
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
                <div class="col-lg-3 col-md-6">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Quick Links</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [1, 6, 3])->orderBy('page_order')->get();
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
                <div class="col-lg-3 col-md-6">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Support</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [4, 5, 8])->orderBy('page_order')->get();
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
                <div class="col-lg-3 col-md-6">
                    <!-- Footer Widget -->
                    <div class="footer-widget footer-menu">
                        <h4 class="footer-title">Other Links</h4>
                        <ul>
                            @php
                                $footerPages = App\Models\Pages::where(['page_parent' => 0, 'page_status' => '1', 'page_footer_status' => '1'])->whereIn('page_id', [2, 7])->orderBy('page_order')->get();
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
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="copyright-text">
                            <p class="mb-0">&copy; {{ date('Y') }} Yanzzo  - All rights reserved.</p>
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