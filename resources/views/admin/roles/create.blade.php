@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Role</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Role</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="roleFrm" method="post" action="{{ route('role-insert') }}" >
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="row card-body m-t-20">

                                                    <div class="form-group col-md-12 m-b-40" id="r_title">
                                                        <input type="text" class="form-control" name="name" id="name" >
                                                        <span class="bar"></span>
                                                        <label for="name">Role Title</label>
                                                        <span class="help-block"><small id="msg_name" class="text-danger"></small></span>
                                                    </div>

                                                    <div class="row form-group col-md-12 m-b-40" id="r_permission">
                                                        <label for="entry_papplication_id" class="p-l-10" style="top: -20px; font-weight: 500">Permission</label>
                                                        @if ($permission && count($permission) > 0)
                                                            @foreach ($permission as $key => $value)
                                                                <div class="custom-control custom-checkbox col-lg-2 p-10 m-b-15 {{ ($key < 6) ? 'm-t-10' : '' }}">
                                                                    <input type="checkbox" class="custom-control-input" id="permission{{ $value->id }}" name="permission[]" value="{{ $value->id }}" />
                                                                    <label class="custom-control-label p-l-20" for="permission{{ $value->id }}">{{ $value->name }}</label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        <div class="col-md-12">
                                                            <span class="help-block"><small id="msg_permission" class="text-danger"></small></span>
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
        $('#roleFrm').on('submit', function (e)
        {
            e.preventDefault();
            var form    = $('#roleFrm')[0];
            var formData= new FormData(form);
            $("#roleFrm").find(".has-error").removeClass("has-error");
            $(".bar").html("");
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
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
                        window.location = "{{ url('admin/role-list') }}";
                    }
                }
            });
        });
    </script>
@endsection
