@extends("admin.layouts.app")
@section('content')
    <!-- Page wrapper  -->
    <div class="page-wrapper">
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Contact Inquiry</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                            <li class="breadcrumb-item active">Contact Inquiry</li>
                        </ol>
                        {{--<a href="{{ route("contact-export") }}" class="btn-sm btn-warning d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Export All</a>--}}
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
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            {{--<th>City</th>
                                            <th>Zip Code</th>--}}
                                            <th>Message</th>
                                            <th>Created Date</th>
                                            {{--<th>Ip Address</th>--}}
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
            ajax: '{{ route("contact-load-table") }}',
            columns: [
                { data: 'checkbox', name: 'checkbox' },
                { data: 'title', name: 'title' },
                { data: 'email', name: 'email' },
                { data: 'mobile', name: 'mobile' },
                /*{ data: 'city', name: 'city' },
                { data: 'zipcode', name: 'zipcode' },*/
                { data: 'message', name: 'message' },
                { data: 'date', name: 'date', orderable: false, searchable: false },
                /*{ data: 'ip', name: 'ip', orderable: false, searchable: false },*/
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function deleteData(contact_id) {
            $.ajax({
                url: "{{ route('contact-delete') }}",
                type: "POST",
                data: {
                    contact_id:contact_id,
                    _token:'{{ csrf_token() }}'
                },
                success: function (response) {
                }
            });
        }
    </script>
@endsection