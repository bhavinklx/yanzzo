@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Setting</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Home</a></li>
                            <li class="breadcrumb-item active">Setting</li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- Start Page Content -->
            <form class="floating-labels" id="settingFrm" method="post" action="{{ route('setting-update') }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Theme Option</h4>

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist" id="myTab">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#homepage" role="tab"><span class="hidden-sm-up"><i class="fa fa-home"></i></span> <span class="hidden-xs-down">Homepage</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#contact" role="tab"><span class="hidden-sm-up"><i class="fa fa-phone"></i></span> <span class="hidden-xs-down">Contact</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#globalseo" role="tab"><span class="hidden-sm-up"><i class="fa fa-support (alias)"></i></span> <span class="hidden-xs-down">Global SEO Meta</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#social" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Social Media</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content tabcontent-border">
                                    <div class="tab-pane active" id="homepage" role="tabpanel">
                                        <div class="p-20">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($homepageSetting as $key => $homepage)
                                                        <div class="form-group m-b-40 {{ ($homepage->setting_type == 'input' || $homepage->setting_type == 'image') ? 'col-md-3' : 'col-md-6' }}" id="a_name">
                                                            @if ($homepage->setting_type == 'input')
                                                                <input type="text" class="form-control" name="settings[{{ $homepage->setting_id }}]" value="{{ $homepage->setting_value }}">
                                                            @elseif ($homepage->setting_type=='textarea')
                                                                <textarea class="form-control" name="settings[{{ $homepage->setting_id }}]" >{{ $homepage->setting_value }}</textarea>
                                                            @elseif ($homepage->setting_type == 'image')
                                                                <label for="settings['{{ $homepage->setting_name }}']">{{ $homepage->setting_label }}</label><br><br>
                                                                <input type="file" class="dropify" data-default-file="{{ ($homepage->setting_value!="" && file_exists(public_path('/uploads/setting/'.$homepage->setting_value))) ? asset('/uploads/setting/'.$homepage->setting_value) : "" }}" name="settings[{{ $homepage->setting_id }}]" aria-describedby="fileHelp">
                                                            @elseif ($homepage->setting_type == 'editor')
                                                                <label for="settings['{{ $homepage->setting_name }}']">{{ $homepage->setting_label }}</label><br><br>
                                                                <textarea id="settings_{{ $homepage->setting_id }}" name="settings[{{ $homepage->setting_id }}]">{{ $homepage->setting_value }}</textarea>
                                                                <script>
                                                                    CKEDITOR.replace( 'settings_{{ $homepage->setting_id }}',
                                                                        {
                                                                            toolbar :
                                                                                [
                                                                                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source'] },
                                                                                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                                                                                    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
                                                                                    { name: 'insert', items: [ 'Image' ] },
                                                                                    { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
                                                                                    { name: 'paragraph', items : [ 'NumberedList','BulletedList' ] }
                                                                                ],
                                                                            height:200
                                                                        });
                                                                </script>
                                                            @endif
                                                            <span class="bar"></span>
                                                            @if ($homepage->setting_type!='image')
                                                                <label for="settings['{{ $homepage->setting_name }}']">{{ $homepage->setting_label }}</label>
                                                            @endif
                                                            <span class="help-block"><small id="msg_admin_name" class="text-danger"></small></span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="contact" role="tabpanel">
                                        <div class="p-20">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($contactSetting as $key => $contact)
                                                        <div class="form-group m-b-40 col-md-6" id="a_name">
                                                            @if ($contact->setting_type == 'input')
                                                                <input type="text" class="form-control" name="settings[{{ $contact->setting_id }}]" value="{{ $contact->setting_value }}">
                                                            @elseif ($contact->setting_type == 'textarea')
                                                                <textarea class="form-control" name="settings[{{ $contact->setting_id }}]" >{{ $contact->setting_value }}</textarea>
                                                            @elseif ($contact->setting_type == 'image')
                                                                <label for="settings['{{ $contact->setting_name }}']">{{ $contact->setting_label }}</label><br><br>
                                                                <input type="file" class="dropify" data-default-file="" name="settings[{{ $contact->setting_name }}]" id="settings[{{ $contact->setting_name }}]" aria-describedby="fileHelp">
                                                            @elseif ($contact->setting_type == 'editor')
                                                                <label for="settings['{{ $contact->setting_name }}']">{{ $contact->setting_label }}</label><br><br>
                                                                <textarea id="settings_{{ $contact->setting_id }}" name="settings[{{ $contact->setting_id }}]">{{ $contact->setting_value }}</textarea>
                                                                <script>
                                                                    CKEDITOR.replace( 'settings_{{ $contact->setting_id }}',
                                                                        {
                                                                            toolbar :
                                                                                [
                                                                                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source'] },
                                                                                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                                                                                    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
                                                                                    { name: 'insert', items: [ 'Image' ] },
                                                                                    { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
                                                                                    { name: 'paragraph', items : [ 'NumberedList','BulletedList' ] }
                                                                                ],
                                                                            height:200
                                                                        });
                                                                </script>
                                                            @endif
                                                            <span class="bar"></span>
                                                            @if ($contact->setting_type != 'image')
                                                                <label for="settings['{{ $contact->setting_name }}']">{{ $contact->setting_label }}</label>
                                                            @endif
                                                            <span class="help-block"><small id="msg_admin_name" class="text-danger"></small></span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="globalseo" role="tabpanel">
                                        <div class="p-20">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($globalseoSetting as $key => $globalseo)
                                                        <div class="form-group m-b-40 {{ ($globalseo->setting_type=='textarea') ? 'col-md-12' : 'col-md-6' }}" id="a_name">
                                                            @if ($globalseo->setting_type == 'input')
                                                                <input type="text" class="form-control" name="settings[{{ $globalseo->setting_id }}]" value="{{ $globalseo->setting_value }}">
                                                            @elseif ($globalseo->setting_type == 'textarea')
                                                                <textarea class="form-control" name="settings[{{ $globalseo->setting_id }}]" >{{ $globalseo->setting_value }}</textarea>
                                                            @elseif ($globalseo->setting_type == 'image')
                                                                <label for="settings['{{ $globalseo->setting_name }}']">{{ $globalseo->setting_label }}</label><br><br>
                                                                <input type="file" class="dropify" data-default-file="" name="settings[{{ $globalseo->setting_id }}]" aria-describedby="fileHelp">
                                                            @elseif ($globalseo->setting_type == 'editor')
                                                                <label for="settings['{{ $globalseo->setting_name }}']">{{ $globalseo->setting_label }}</label><br><br>
                                                                <textarea id="settings_{{ $globalseo->setting_id }}" name="settings[{{ $globalseo->setting_id }}]">{{ $globalseo->setting_value }}</textarea>
                                                                <script>
                                                                    CKEDITOR.replace( 'settings_{{ $globalseo->setting_id }}',
                                                                        {
                                                                            toolbar :
                                                                                [
                                                                                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source'] },
                                                                                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                                                                                    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
                                                                                    { name: 'insert', items: [ 'Image' ] },
                                                                                    { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
                                                                                    { name: 'paragraph', items : [ 'NumberedList','BulletedList' ] }
                                                                                ],
                                                                            height:200
                                                                        });
                                                                </script>
                                                            @endif
                                                            <span class="bar"></span>
                                                            @if ($globalseo->setting_type != 'image')
                                                                <label for="settings['{{ $globalseo->setting_name }}']">{{ $globalseo->setting_label }}</label>
                                                            @endif
                                                            <span class="help-block"><small id="msg_admin_name" class="text-danger"></small></span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="social" role="tabpanel">
                                        <div class="p-20">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($socialSetting as $key => $social)
                                                        <div class="form-group m-b-40 {{ ($social->setting_type=='textarea') ? 'col-md-12' : 'col-md-6' }}" id="a_name">
                                                            @if ($social->setting_type == 'input')
                                                                <input type="text" class="form-control" name="settings[{{ $social->setting_id }}]" value="{{ $social->setting_value }}">
                                                            @elseif ($social->setting_type == 'textarea')
                                                                <textarea class="form-control" name="settings[{{ $social->setting_id }}]" >{{ $social->setting_value }}</textarea>
                                                            @elseif ($social->setting_type == 'image')
                                                                <label for="settings['{{ $social->setting_name }}']">{{ $social->setting_label }}</label><br><br>
                                                                <input type="file" class="dropify" data-default-file="" name="settings[{{ $social->setting_id }}]" aria-describedby="fileHelp">
                                                            @elseif ($social->setting_type == 'editor')
                                                                <label for="settings['{{ $social->setting_name }}']">{{ $social->setting_label }}</label><br><br>
                                                                <textarea id="settings_{{ $social->setting_id }}" name="settings[{{ $social->setting_id }}]">{{ $social->setting_value }}</textarea>
                                                                <script>
                                                                    CKEDITOR.replace( 'settings_{{ $social->setting_id }}',
                                                                        {
                                                                            toolbar :
                                                                                [
                                                                                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source'] },
                                                                                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                                                                                    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
                                                                                    { name: 'insert', items: [ 'Image' ] },
                                                                                    { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
                                                                                    { name: 'paragraph', items : [ 'NumberedList','BulletedList' ] }
                                                                                ],
                                                                            height:200
                                                                        });
                                                                </script>
                                                            @endif
                                                            <span class="bar"></span>
                                                            @if ($social->setting_type != 'image')
                                                                <label for="settings['{{ $social->setting_name }}']">{{ $social->setting_label }}</label>
                                                            @endif
                                                            <span class="help-block"><small id="msg_admin_name" class="text-danger"></small></span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
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
        $(document).ready(function () {
            setTimeout(function(){
                $(".dropify-wrapper").css("width","100%");
            },100);
        });

        $('#settingFrm').on('submit',function (e)
        {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.config.allowedContent=true;
            }
            var form    = $('#settingFrm')[0];
            var formData= new FormData(form);
            $("#settingFrm").find(".has-error").removeClass("has-error");
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
                    if (response.redirect_url !== undefined)
                    {
                        window.location = "{{ url('admin/setting') }}";
                    }
                }
            });
        });

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
@endsection
