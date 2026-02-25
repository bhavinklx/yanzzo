@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Blog Category</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Blog Category</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="bcategoryFrm" method="post" action="{{ route("bcategory-update") }}">
                {{ csrf_field() }}
                <input type="hidden" name="bcategory_id" value="{{ $bcategoryDetail->bcategory_id }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row card-body m-t-20">

                                                    <div class="form-group col-lg-6 m-b-40" id="b_title">
                                                        <input type="text" class="form-control" name="bcategory_title" id="bcategory_title" value="{{ $bcategoryDetail->bcategory_title }}" >
                                                        <span class="bar"></span>
                                                        <label for="bcategory_title">Category Title <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_bcategory_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-lg-6 m-b-40" id="b_slug">
                                                        <input type="text" class="form-control" id="bcategory_slug" name="bcategory_slug" value="{{ $bcategoryDetail->bcategory_slug }}" >
                                                        <span class="bar"></span>
                                                        <label for="bcategory_slug">Category Slug <span class="form-asterisk">*</span></label>
                                                        <span class="help-block"><small id="msg_bcategory_slug" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-lg-6 m-b-40">
                                                        <input type="text" class="form-control" name="bcategory_meta_title" id="bcategory_meta_title" value="{{ $bcategoryDetail->bcategory_meta_title }}" >
                                                        <span class="bar"></span>
                                                        <label for="bcategory_meta_title">Meta Title</label>
                                                        <span class="help-block"><small id="msg_bcategory_meta_title" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-lg-6 m-b-40">
                                                        <input type="text" class="form-control" name="bcategory_meta_keyword" id="bcategory_meta_keyword" value="{{ $bcategoryDetail->bcategory_meta_keyword }}">
                                                        <span class="bar"></span>
                                                        <label for="bcategory_meta_keyword">Meta Keyword</label>
                                                        <span class="help-block"><small id="msg_bcategory_meta_keyword" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-lg-6">
                                                        <textarea class="form-control" name="bcategory_meta_desc" id="bcategory_meta_desc">{{ $bcategoryDetail->bcategory_meta_desc }}</textarea>
                                                        <span class="bar"></span>
                                                        <label for="bcategory_meta_desc">Meta Description</label>
                                                        <span class="help-block"><small id="msg_bcategory_meta_desc" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="form-group col-lg-6 m-t-20">
                                                        <select class="form-control p-0" name="bcategory_status" id="bcategory_status">
                                                            <option value="1" {{ ($bcategoryDetail->bcategory_status == 1) ? 'selected="selected"' : '' }}>Active</option>
                                                            <option value="0" {{ ($bcategoryDetail->bcategory_status == 0) ? 'selected="selected"' : '' }} >Inactive</option>
                                                        </select>
                                                        <span class="bar"></span>
                                                        <label for="bcategory_status">Status</label>
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
    <script type="application/javascript">
        $('#bcategory_title').keyup(function (e) {
            $.ajax({
                url: "{{ route('bcategory-create-slug') }}",
                type: "GET",
                data: {'bcategory_title' : $(this).val()},
                success: function (response) {
                    $('#b_slug').addClass('focused')
                    $('#bcategory_slug').val(response.slug);
                }
            });
        });

        $('#bcategoryFrm').on('submit',function (e)
        {
            e.preventDefault();
            var form    = $('#bcategoryFrm')[0];
            var formData= new FormData(form);
            $("#bcategoryFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: $(this).attr('action'),
                cache : false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data : formData,
                success: function (response) {
                    if(response.status == "validation-error")
                    {
                        $.each(response.data, function (key, value)
                        {
                            $("#"+key).parent("div").addClass("has-error");
                            //$("#"+key).next().html("<small class='text-danger'>" + value + "</small>");
                            $("#msg_"+key).html(value);
                        });
                    }
                    else if (response.redirect_url !== undefined)
                    {
                        window.location = "{{ url('admin/bcategory-list') }}";
                    }
                }
            });
        });
    </script>
@endsection
