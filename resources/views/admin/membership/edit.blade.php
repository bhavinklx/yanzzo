@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Membership</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Membership</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="membershipFrm" method="post" action="{{ route("membership-update") }}">
                <input type="hidden" name="membership_id" value="{{ $membershipDetail->membership_id }}">
                {{ csrf_field() }}
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <!--<h4 class="card-title m-b-40">Tab with dropdown</h4>-->
                                <div class="tab-content p-20" id="myTabContent">
                                    <div role="tabpanel" class="tab-pane fade show active" id="english" aria-labelledby="english-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row m-t-20">
                                                    <div class="form-group col-md-6 m-b-40" id="m_title">
                                                        <input type="text" class="form-control" name="membership_title" id="membership_title" value="{{ $membershipDetail->membership_title }}">
                                                        <span class="bar"></span>
                                                        <label for="membership_title">Membership Title <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_membership_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-6 m-b-40" id="m_slug">
                                                        <input type="text" class="form-control" id="membership_slug" name="membership_slug" value="{{ $membershipDetail->membership_slug }}">
                                                        <span class="bar"></span>
                                                        <label for="membership_slug">Membership Slug <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_blog_slug" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40">
                                                        <input class="form-control isNumber" name="membership_price" id="membership_price" value="{{ $membershipDetail->membership_price }}">
                                                        <span class="bar"></span>
                                                        <label for="membership_price">Price <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_membership_price" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40">
                                                        <input class="form-control isNumber" name="membership_offer_price" id="membership_offer_price" value="{{ $membershipDetail->membership_offer_price }}">
                                                        <span class="bar"></span>
                                                        <label for="membership_offer_price">Offer Price <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_membership_offer_price" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40" id="m_slug">
                                                        <input type="text" class="form-control" id="membership_duration" name="membership_duration" value="{{ $membershipDetail->membership_duration }}">
                                                        <span class="bar"></span>
                                                        <label for="membership_duration">Duration <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_membership_duration" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-md-3 m-b-40" id="m_slug">
                                                        <input type="text" class="form-control isNumber" id="membership_discount" name="membership_discount" value="{{ $membershipDetail->membership_discount }}">
                                                        <span class="bar"></span>
                                                        <label for="membership_discount">Discount (%) <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_membership_discount" class="text-danger"></small></span>
                                                    </div>

                                                    {{--<div class="form-group col-md-3 m-b-40 m-t-20">
                                                        <select class="form-control p-0"  name="membership_recommended" id="membership_recommended">
                                                            <option value="0" {{ ($membershipDetail->membership_recommended == "0") ? 'selected': '' }}>No</option>
                                                            <option value="1" {{ ($membershipDetail->membership_recommended == "1") ? 'selected': '' }}>Yes</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="membership_recommended">Is Recommended? <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_membership_recommended" class="text-danger"></small></span>
                                                    </div>--}}

                                                    <div class="form-group col-md-12">
                                                        <label for="membership_desc" class="m-b-20" style="position: initial;">Description</label>
                                                        <textarea id="membership_desc" name="membership_desc">{{ $membershipDetail->membership_desc }}</textarea>
                                                        <script type="text/javascript">
                                                            CKEDITOR.replace( 'membership_desc',
                                                                    {
                                                                        filebrowserBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html') }}',
                                                                        filebrowserUploadUrl : '{{ url('assets/ckfinder/userfiles') }}',
                                                                        filebrowserImageBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html?Type=Images') }}',
                                                                        filebrowserFlashBrowseUrl : '{{ url('assets/ckfinder/ckfinder.html?Type=Flash') }}',
                                                                        filebrowserUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
                                                                        filebrowserImageUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}',
                                                                        filebrowserFlashUploadUrl : '{{ url('assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}',
                                                                        enterMode: CKEDITOR.ENTER_P,
                                                                    }
                                                            );
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions p-b-10 text-center">
                    <button type="submit" name="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                </div>
            </form>

            <!-- End PAge Content -->
        </div>
        <!-- End Container fluid  -->
    </div>
    <!-- End Page wrapper  -->
@endsection

@section('page-js')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $(".dropify-wrapper").css("width", "100%");
            }, 100);
        });

        $('#membership_title').keyup(function(e) {
            $.ajax({
                url: "{{ route('membership-create-slug') }}",
                type: "GET",
                data: {
                    'membership_title': $(this).val()
                },
                success: function(response) {
                    $('#m_slug').addClass('focused');
                    $('#membership_slug').val(response.slug);
                }
            });
        });

        $("#membershipFrm").on('submit', function(e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form = $('#membershipFrm')[0];
            var formData = new FormData(form);
            $("#membershipFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $(".text-danger").html("");
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                cache: false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    //alert(response.redirect_url)
                    if (response.status == "validation-error") {
                        $.each(response.data, function(key, value) {
                            $("#" + key).parent("div").addClass("has-error");
                            //$("#"+key).next().html("<small class='text-danger'>" + value + "</small>");
                            $("#msg_" + key).html(value);
                        });
                    } else if (response.redirect_url !== undefined) {
                        window.location = "{{ url('admin/membership-list') }}";
                    }
                }
            });
        });
    </script>
@endsection
