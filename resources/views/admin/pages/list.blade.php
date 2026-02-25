@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Pages</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Page List</li>
                        </ol>
                        @can('pages-add')
                            <a href="{{ route("pages-add") }}" class="btn-sm btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</a>
                        @endcan
                        {{--<a href="javascript: void(0);" onclick="validate_all('activate_all')" class="btn-sm btn-success d-none d-lg-block m-l-15"><i class="fa fa-check-circle"></i> Active All</a>
                        <a href="javascript: void(0);" onclick="validate_all('inactivate_all')" class="btn-sm btn-warning d-none d-lg-block m-l-15"><i class="fa fa-remove"></i> Inactive All</a>
                        <a href="javascript: void(0);" onclick="validate_all('delete_all')" class="btn-sm btn-danger d-none d-lg-block m-l-15"><i class="fa fa-trash-o"></i> Delete All</a>--}}
                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->

            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="listFrm" name="listFrm" action="" method="post">
                                <input type="hidden" id="action" name="action" value="">
                                <div class="table-responsive ">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" class="custom-checkbox" name="checkall" id="checkall"/></th>
                                            <th>Title</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th>Header Status</th>
                                            <th>Footer Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">
                                        @foreach($pagesDetail as $pages)
                                            <tr class="row1" data-id="{{ $pages->page_id }}">
                                                <td><input type="checkbox" name="check[]" id="check[]" value="{{ $pages->page_id }}" class="custom-checkbox check_class" /></td>
                                                <td>{{ $pages->page_title }}</td>
                                                <td>{{ date('d-m-Y h:i:s A', strtotime($pages->created_at)) }}</td>
                                                <td>
                                                    @if($pages->page_status=='1')
                                                        <span id="td_status_{{ $pages->page_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $pages->page_id }}',0)" ><div class="label label-table label-success">Active</div></a></span>
                                                    @else
                                                        <span id="td_status_{{ $pages->page_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $pages->page_id }}',1)" ><div class="label label-table label-danger">Inactive</div></a></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($pages->page_header_status=='1')
                                                        <span id="td_header_status_{{ $pages->page_id }}"><a href="javascript:void(0)" onclick="change_header_status('{{ $pages->page_id }}',0)" ><div class="label label-table label-success">Active</div></a></span>
                                                    @else
                                                        <span id="td_header_status_{{ $pages->page_id }}"><a href="javascript:void(0)" onclick="change_header_status('{{ $pages->page_id }}',1)" ><div class="label label-table label-danger">Inactive</div></a></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($pages->page_footer_status=='1')
                                                        <span id="td_footer_status_{{ $pages->page_id }}"><a href="javascript:void(0)" onclick="change_footer_status('{{ $pages->page_id }}',0)" ><div class="label label-table label-success">Active</div></a></span>
                                                    @else
                                                        <span id="td_footer_status_{{ $pages->page_id }}"><a href="javascript:void(0)" onclick="change_footer_status('{{ $pages->page_id }}',1)" ><div class="label label-table label-danger">Inactive</div></a></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($pages->page_id > 1)
                                                        @if(auth()->user()->can('pages-edit'))
                                                            <a href="{{ route("pages-edit", ['id' => $pages->page_id]) }}" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>
                                                        @endif
                                                        @if(auth()->user()->can('pages-delete'))
                                                            <a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal('{{ $pages->page_id }}');" data-placement="top" title="Delete" > <i class="fa fa-trash text-danger"></i> </a>
                                                        @endif
                                                    @else
                                                        @if(auth()->user()->can('pages-delete'))
                                                            <a href="{{ route("pages-edit", ['id' => $pages->page_id]) }}" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            @foreach($pages->subPages as $subPages)
                                                <tr>
                                                    <td><input type="checkbox" name="check[]" id="check[]" value="{{ $subPages->page_id }}" class="custom-checkbox check_class" /></td>
                                                    <td>&nbsp; <img src="{{ url("assets/img/arrow-01.gif") }}">&nbsp; {{ $subPages->page_title }}</td>
                                                    <td>{{ date('d-m-Y h:i:s A', strtotime($subPages->created_at)) }}</td>
                                                    <td>
                                                        @if($subPages->page_status=='1')
                                                            <span id="td_status_{{ $subPages->page_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $subPages->page_id }}',0)" ><div class="label label-table label-success">Active</div></a></span>
                                                        @else
                                                            <span id="td_status_{{ $subPages->page_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $subPages->page_id }}',1)" ><div class="label label-table label-danger">Inactive</div></a></span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($subPages->page_header_status=='1')
                                                            <span id="td_header_status_{{ $subPages->page_id }}"><a href="javascript:void(0)" onclick="change_header_status('{{ $subPages->page_id }}',0)" ><div class="label label-table label-success">Active</div></a></span>
                                                        @else
                                                            <span id="td_header_status_{{ $subPages->page_id }}"><a href="javascript:void(0)" onclick="change_header_status('{{ $subPages->page_id }}',1)" ><div class="label label-table label-danger">Inactive</div></a></span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($subPages->page_footer_status=='1')
                                                            <span id="td_footer_status_{{ $subPages->page_id }}"><a href="javascript:void(0)" onclick="change_footer_status('{{ $subPages->page_id }}',0)" ><div class="label label-table label-success">Active</div></a></span>
                                                        @else
                                                            <span id="td_footer_status_{{ $subPages->page_id }}"><a href="javascript:void(0)" onclick="change_footer_status('{{ $subPages->page_id }}',1)" ><div class="label label-table label-danger">Inactive</div></a></span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(auth()->user()->can('pages-edit'))
                                                            <a href="{{ route("pages-edit", ['id' => $subPages->page_id]) }}" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>
                                                        @endif
                                                        @if(auth()->user()->can('pages-delete'))
                                                            <a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal('{{ $subPages->page_id }}');" data-placement="top" title="Delete" > <i class="fa fa-trash text-danger"></i> </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End PAge Content -->
        </div>
        <!-- End Container fluid  -->
    </div>
    <!-- End Page wrapper  -->
@endsection

@section('page-js')
    <script type="text/javascript">
        var page_length = 25;
        /*$('#myTable-example').DataTable({
            pageLength: page_length,
            processing: true,
            serverSide: true,
            responsive: true,
            rowReorder: true,
            ajax: '',
            columns: [
                { data: 'checkbox', name: 'checkbox' },
                { data: 'title', name: 'title' },
                { data: 'date', name: 'date', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'header_status', name: 'header_status', orderable: false, searchable: false },
                { data: 'footer_status', name: 'footer_status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });*/

        $( "#tablecontents" ).sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.8,
            update: function() {
                sendOrderToServer();
            }
        });

        function sendOrderToServer() {
            var order = [];
            $('tr.row1').each(function(index, element) {
                order.push({
                    page_id: $(this).attr('data-id'),
                    position: index + 1
                });
            });
            //alert(order)
            $.ajax({
                url: "{{ route('pages-update-order') }}",
                type: "POST",
                //dataType: "json",
                data: {
                    order:order,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    $.toast({
                        heading: response
                        , position: 'top-right'
                        , loaderBg: '#ff6849'
                        , icon: 'success'
                        , hideAfter: 3500
                        , stack: 6
                    });
                }
            });

        }

        function change_status(page_id, status) {
            $.ajax({
                url: "{{ route('pages-change-status') }}",
                method: "POST",
                data: {
                    page_id:page_id,
                    status:status,
                    _token:"{{ csrf_token() }}"
                },
                success: function (response) {
                    $.toast({
                        heading: response
                        , position: 'top-right'
                        , loaderBg: '#ff6849'
                        , icon: 'success'
                        , hideAfter: 3500
                        , stack: 6
                    });
                    if (status == 1){
                        $("#td_status_"+page_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+page_id+"', '0')\" ><div class=\"label label-table label-success\">Active</div></a>");
                    } else {
                        $("#td_status_"+page_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+page_id+"', '1')\" ><div class=\"label label-table label-danger\">Inactive</div></a>");
                    }
                }
            });
        }

        function change_header_status(page_id, status) {
            $.ajax({
                url: "{{ route('pages-change-header-status') }}",
                method: "POST",
                data: {
                    page_id:page_id,
                    status:status,
                    _token:"{{ csrf_token() }}"
                },
                success: function (response) {
                    $.toast({
                        heading: response
                        , position: 'top-right'
                        , loaderBg: '#ff6849'
                        , icon: 'success'
                        , hideAfter: 3500
                        , stack: 6
                    });
                    if (status == 1){
                        $("#td_header_status_"+page_id).html("<a href=\"javascript:void(0)\" onclick=\"change_header_status('"+page_id+"', '0')\" ><div class=\"label label-table label-success\">Active</div></a>");
                    } else {
                        $("#td_header_status_"+page_id).html("<a href=\"javascript:void(0)\" onclick=\"change_header_status('"+page_id+"', '1')\" ><div class=\"label label-table label-danger\">Inactive</div></a>");
                    }
                }
            });
        }

        function change_footer_status(page_id, status) {
            $.ajax({
                url: "{{ route('pages-change-footer-status') }}",
                method: "POST",
                data: {
                    page_id:page_id,
                    status:status,
                    _token:"{{ csrf_token() }}"
                },
                success: function (response) {
                    $.toast({
                          heading: response
                        , position: 'top-right'
                        , loaderBg: '#ff6849'
                        , icon: 'success'
                        , hideAfter: 3500
                        , stack: 6
                    });
                    if (status == 1){
                        $("#td_footer_status_"+page_id).html("<a href=\"javascript:void(0)\" onclick=\"change_footer_status('"+page_id+"', '0')\" ><div class=\"label label-table label-success\">Active</div></a>");
                    } else {
                        $("#td_footer_status_"+page_id).html("<a href=\"javascript:void(0)\" onclick=\"change_footer_status('"+page_id+"', '1')\" ><div class=\"label label-table label-danger\">Inactive</div></a>");
                    }
                }
            });
        }

        function deleteData(page_id) {
            $.ajax({
                url: "{{ route('pages-delete') }}",
                type: "POST",
                data: {
                    _token:'{{ csrf_token() }}',
                    page_id:page_id,
                },
                success: function (response) {
                }
            });
        }
    </script>
@endsection
