@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Category</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Category List</li>
                        </ol>
                        @if(auth()->user()->can('blog-add'))
                            <a href="{{ route("category-add") }}" class="btn-sm btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</a>
                        @endif
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
                                    <table id="myTable1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" class="custom-checkbox" name="checkall" id="checkall"/></th>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th>Homepage Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tablecontents">

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
        $('#myTable1').DataTable({
            pageLength: page_length,
            processing: true,
            serverSide: true,
            responsive: true,
            rowReorder: true,
            order: [],
            paging: true,
            ajax: '{{ route("category-load-table") }}',
            columns: [
                { data: 'checkbox'},
                { data: 'title'},
                { data: 'image', orderable: false, searchable: false },
                { data: 'date', orderable: false, searchable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'home_status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        $("#tablecontents").sortable({
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
                    category_id: $(this).attr('data-id'),
                    position: index + 1
                });
            });

            $.ajax({
                url: "{{ route('category-update-order') }}",
                method: "POST",
                data:{
                    order:order,
                    _token: '{{csrf_token()}}'
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
                }
            });
        }

        function change_status(category_id, status) {
            $.ajax({
                url: "{{ route('category-change-status') }}",
                method: "POST",
                data: {
                    category_id:category_id,
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
                        $("#td_status_"+category_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+category_id+"', '0')\" ><div class=\"label label-table label-success\">Active</div></a>");
                    } else {
                        $("#td_status_"+category_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+category_id+"', '1')\" ><div class=\"label label-table label-danger\">Inactive</div></a>");
                    }
                }
            });
        }

        function change_home_status(category_id, status) {
            $.ajax({
                url: "{{ route('category-change-home-status') }}",
                method: "POST",
                data: {
                    category_id:category_id,
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
                        $("#td_home_status_"+category_id).html("<a href=\"javascript:void(0)\" onclick=\"change_home_status('"+category_id+"', '0')\" ><div class=\"label label-table label-success\">Active</div></a>");
                    } else {
                        $("#td_home_status_"+category_id).html("<a href=\"javascript:void(0)\" onclick=\"change_home_status('"+category_id+"', '1')\" ><div class=\"label label-table label-danger\">Inactive</div></a>");
                    }
                }
            });
        }

        function deleteData(category_id) {
            $.ajax({
                url: "{{ route('category-delete') }}",
                type: "POST",
                data: {
                    category_id:category_id,
                    _token:'{{ csrf_token() }}'
                },
                success: function (response) {
                }
            });
        }
    </script>
@endsection