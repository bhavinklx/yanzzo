@extends("layouts.app")
@section('title', $bcategoryName->bcategory_meta_title ?? $pagesDetail->page_meta_title)
@section('keywords', $bcategoryName->bcategory_meta_keyword ?? $pagesDetail->page_meta_keyword)
@section('description', $bcategoryName->bcategory_meta_desc ?? $pagesDetail->page_meta_desc)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
        <span class="primary-right-round"></span>
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
    <div class="content court-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="profile-detail-group">
                        <div class="card ">
                            <form >
                                <div class="row" id="franchiseForm">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label  class="form-label">Company Name <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control" id="inquiry_company" name="inquiry_company" placeholder="Enter Company Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label  class="form-label">Your Name <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control" id="inquiry_name" name="inquiry_name" placeholder="Enter Your Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label  class="form-label">Email <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control" id="inquiry_email" name="inquiry_email" placeholder="Enter Email Address">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <input class="country" type="hidden" id="country" name="country">
                                            <input class="prefix" type="hidden" id="prefix" name="prefix">
                                            <label  class="form-label">Phone Number <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control allownumericwithoutdecimal mobile" maxlength="10" id="inquiry_mobile" name="inquiry_mobile" placeholder="Enter Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label  class="form-label">Town / City <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control" id="inquiry_city" name="inquiry_city" placeholder="Enter Town / City">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space">
                                            <label  class="form-label">State <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control" id="inquiry_state" name="inquiry_state" placeholder="Enter State">
                                        </div>
                                    </div>
                                    {{--<div class="col-lg-6 col-md-6">
                                        <div class="input-space mb-0">
                                            <label  class="form-label">Country <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control" id="confirm-password" placeholder="Enter Country">
                                        </div>
                                    </div>--}}
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-space mb-0">
                                            <label  class="form-label">Zip Code <label class="text-danger">*</label></label>
                                            <input type="text" class="form-control" id="inquiry_zipcode" name="inquiry_zipcode" placeholder="Enter Zip Code">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center" id="thankMsg"></div>
                            </form>
                        </div>
                        <div class="save-changes text-center">
                            {{--<a href="javascript:;" class="btn btn-primary reset-profile">Reset</a>--}}
                            <a href="javascript:;" onclick="return validate_franchise();" id="submit" class="btn btn-secondary">Save Change</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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