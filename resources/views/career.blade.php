@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    @if($pagesDetail->page_image!='' && file_exists(public_path('/uploads/pages/'.$pagesDetail->page_image)))
        <div class="career-section" style="background: url({{ asset('/uploads/pages/'.$pagesDetail->page_image) }}) center center no-repeat; background-size: cover; background-color: #ee5625;">
    @else
        <div class="career-section">
    @endif
        <div class="container">
            <div class="arten-space">
                <h1>
                    {{ $pagesDetail->page_title ?? '' }}
                </h1>
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>></li>
                    <li>{{ $pagesDetail->page_title ?? '' }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="experience-wrap row bockCont">
            <div class="col-xl-4">
                <div class="custom-content">
                    <span>FIND OPPORTUNITIES</span>
                    <h2>Share Your <br> Details</h2>
                </div>
            </div>
            <div class="col-xl-8 contact-form">
                <form action="{{ route("career-insert") }}" method="post" enctype="multipart/form-data" onsubmit="return validate_career();">
                    <span class="form-text">Send us your C.V. and we'll get back to you within 5 working days.</span>
                    <div class="row" id="careerForm">
                        <div class="col-xl-6">
                            <div class="row">
                                <div class="col-6">
                                    <input class="custom-input allowstring" type="text" id="career_fname" name="fname" placeholder="First Name">
                                </div>
                                <div class="col-6">
                                    <input class="custom-input allowstring" type="text" id="career_lname" name="lname" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <input class="custom-input" type="email" id="career_email" name="email" placeholder="Email Address">
                                </div>
                                <div class="col-xl-12" style="margin-bottom: 10px">
                                    <input class="country" type="hidden" id="country" name="country">
                                    <input class="prefix" type="hidden" id="prefix" name="prefix">
                                    <input class="custom-input allownumericwithoutdecimal mobile" type="text" id="career_mobile" name="mobile" maxlength="10" placeholder="Mobile Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <textarea id="career_message" name="message" placeholder="Your Message" class="custom-textarea"></textarea>
                        </div>
                        <div class="col-xl-12" style="margin-bottom: 10px">
                            <input type="file" id="career_cv" name="career_cv" accept=".doc,.docx,.pdf" class="custom-textarea" value="" />
                            <!--<button type="button" id="custom-button">Upload Document</button>
                            <span id="custom-text">Attach your C.V.</span>-->
                        </div>
                        <div class="col-xl-12">
                            <span id="common_msg" style="color: red"></span>
                        </div>
                        <div class="sbn-btn col-xl-12">
                            <button type="submit" id="submit">Submit</button>
                        </div>
                    </div>
                    <div id="thankMsg"></div>
                </form>
            </div>
        </div>
    </div>
@endsection
