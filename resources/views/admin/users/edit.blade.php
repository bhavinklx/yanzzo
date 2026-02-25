@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">

            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Administrators</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Administrator</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->

            <!-- Start Page Content -->
            <form id="userFrm" class="floating-labels" method="post" action="{{ route("user-update") }}">
                <input type="hidden" id="user_id" name="user_id" value="{{ $userDetail->id }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="tab-content p-20" id="myTabContent">
                                    <div role="tabpanel" class="tab-pane fade show active" aria-labelledby="english-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <h4 class="card-title m-b-30">Basic Details</h4>
                                                        <div class="form-group m-b-40" id="a_name">
                                                            <input type="text" class="form-control" name="name" id="name" value="{{ $userDetail->name }}">
                                                            <span class="bar"></span>
                                                            <label for="name">Name</label>
                                                            <span class="help-block"><small id="msg_name" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40" id="a_email">
                                                            <input type="text" class="form-control" name="email" id="email" value="{{ $userDetail->email }}">
                                                            <span class="bar"></span>
                                                            <label for="email">Email</label>
                                                            <span class="help-block"><small id="msg_email" class="text-danger"></small></span>
                                                        </div>

                                                        <div class="form-group m-b-40" id="a_mobile">
                                                            <input type="text" class="form-control isNumber" name="phone" id="phone" value="{{ $userDetail->phone }}">
                                                            <span class="bar"></span>
                                                            <label for="phone">Mobile</label>
                                                            <span class="help-block"><small id="msg_phone" class="text-danger"></small></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 m-t-15">
                                                        <div class="row m-b-40">
                                                            <div class="col-md-12">
                                                                <label>Profile Image</label><br><br>
                                                                <input type="file" class="dropify" id="admin_image" data-default-file="" name="admin_image" aria-describedby="fileHelp">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6" id="a_country">
                                                        <select class="select2 form-control" id="roles" name="roles[]">
                                                            <option value="0" >Select as Role</option>
                                                            @if (is_array($roleDetail) && count($roleDetail) > 0)
                                                                @foreach ($roleDetail as $role)
                                                                    <option value="{{ $role }}" {{ (@in_array($role, $userRole)) ? 'selected="selected"' : '' }}>{{ $role }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        {{--{!! Form::select('roles[]', $roleDetail, [], array('class' => 'select2 form-control','multiple')) !!}--}}
                                                        <span class="bar"></span>
                                                        <label for="admin_role">Role Type</label>
                                                        <span class="help-block"><small id="msg_admin_role" class="text-danger"></small></span>
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
        $(document).ready(function () {
            setTimeout(function(){
                $(".dropify-wrapper").css("width","100%");

            },100);
        });

        $('#userFrm').on('submit', function(e)
        {
            e.preventDefault();
            var form    = $('#userFrm')[0];
            var formData= new FormData(form);
            $("#userFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                url: $(this).attr('action'),
                cache : false,
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data : formData,
                success: function(response){
                    //alert(response.redirect_url)
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
                        window.location = "{{ url('admin/user-list') }}";
                    }
                },
            });
        });
    </script>
@endsection
