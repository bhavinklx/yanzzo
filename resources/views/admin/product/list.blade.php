@extends("admin.layouts.app")
@section('content')
    <!-- App hero header starts -->
    <div class="app-hero-header d-flex align-items-center">
        <!-- Breadcrumb starts -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route("dashboard") }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary" aria-current="page">
                Product List
            </li>
        </ol>
        <!-- Breadcrumb ends -->
    </div>
    <!-- App Hero header ends -->

    <!-- App body starts -->
    <div class="app-body">
        <!-- Row starts -->
        <div class="row gx-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title">Product List</h5>
                        @if (auth()->user()->can('product-add'))
                            <a href="{{ route("product-add") }}" class="btn btn-primary ms-auto">Add Product</a>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Table starts -->
                        <div class="table-responsive">
                            <table id="basicExample" class="table m-0 align-middle">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" value="" id="checkall" name="checkall">
                                        </div>
                                    </th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Sold Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="tablecontents" />
                            </table>
                        </div>
                        <!-- Table ends -->

                        <!-- Modal Delete Row -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="delRowLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="delRowLabel">
                                            Confirm
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="product_id">
                                        Are you sure you want to delete?
                                    </div>
                                    <div class="modal-footer">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">No</button>
                                            <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close" onclick="deleteData()">Yes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row ends -->
    </div>
    <!-- App body ends -->
@endsection

@section('page-js')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#checkall").click(function(){
                if(this.checked){
                    $(".check_class").attr("checked",true);
                    $(".check_class").parent().addClass("checked");
                }else{
                    $(".check_class").attr("checked",false);
                    $(".check_class").parent().removeClass("checked");
                }
            });
            $("#status_msg").hide();
            $("#alert_msg").hide();
        });

        var table = $('#basicExample').DataTable({
            pageLength: 25,
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: true,
            autoWidth: false,
            ajax: '{{ route("product-load-table") }}',
            columns: [
                { data: 'checkbox', orderable: false, searchable: false },
                { data: 'title', name: 'product_title' },
                { data: 'price', name: 'product_price' },
                { data: 'date', name: 'created_at' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'sold_status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ],
            language: {
                lengthMenu: "Display _MENU_ Records Per Page",
                info: "Showing Page _PAGE_ of _PAGES_",
            },
            drawCallback: function () {
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        $(document).ready(function () {
            $("#tablecontents").sortable({
                items: "tr",
                cursor: "move",
                opacity: 0.8,
                helper: function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function () {
                    sendOrderToServer();
                }
            });
        });

        function sendOrderToServer() {
            var order = [];
            $('#tablecontents tr.row1').each(function(index) {
                order.push({
                    product_id: $(this).data('id'),
                    position: index + 1
                });
            });

            $.ajax({
                url: "{{ route('product-update-order') }}",
                type: "POST",
                data: {
                    order:order,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response,
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        padding: '0.5em 1em',
                    });
                }
            });
        }

        function change_status(product_id, status) {
            $.ajax({
                url: "{{ route('product-change-status') }}",
                method: "POST",
                data: {
                    product_id:product_id,
                    status:status,
                    _token:"{{ csrf_token() }}"
                },
                success: function (response) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response,
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        padding: '0.5em 1em',
                    });
                    if (status == 1){
                        $("#td_status_"+product_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+product_id+"', '0')\" ><span class=\"badge bg-success\">Active</span></a>");
                    } else {
                        $("#td_status_"+product_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+product_id+"', '1')\" ><span class=\"badge bg-danger\">Inactive</span></a>");
                    }
                }
            });
        }

        function openDeleteModal(product_id) {
            $('#product_id').val(product_id);
            $('#deleteModal').modal('show');
        }

        function deleteData() {
            let product_id = $('#product_id').val();
            $.ajax({
                url: "{{ route('product-delete') }}",
                type: "POST",
                data: {
                    _token:'{{ csrf_token() }}',
                    product_id:product_id
                },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    table.row($('#row_' + product_id))
                            .remove()
                            .draw(false);
                }
            });
        }
    </script>
@endsection
