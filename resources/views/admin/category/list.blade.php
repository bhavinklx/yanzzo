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
                Category List
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
                        <h5 class="card-title">Category List</h5>
                        @if (auth()->user()->can('category-add'))
                            <a href="{{ route("category-add") }}" class="btn btn-primary ms-auto">Add Category</a>
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
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="tablecontents">
                                @foreach($categoryDetail as $category)
                                    <tr class="row1" data-id="{{ $category->category_id }}" id="row_{{ $category->category_id }}">
                                        <td>
                                            <div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="{{ $category->category_id }}"></div>
                                        </td>
                                        <td>{{ $category->category_title }}</td>
                                        <td>{{ date('d-m-Y h:i:s A', strtotime($category->created_at)) }}</td>
                                        <td>
                                            @if($category->category_status=='1')
                                                <div id="td_status_{{ $category->category_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $category->category_id }}',0)" ><span class="badge bg-success">Active</span></a></div>
                                            @else
                                                <div id="td_status_{{ $category->category_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $category->category_id }}',1)" ><span class="badge bg-danger">Inactive</span></a></div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-inline-flex gap-1">
                                                {{--@if(auth()->user()->can('category-delete'))--}}
                                                    <button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal('{{ $category->category_id }}');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Category"> <i class="ri-delete-bin-line"></i> </button>
                                                {{--@endif--}}
                                                {{--@if(auth()->user()->can('category-edit'))--}}
                                                    <a href="{{ route("category-edit", ['id' => $category->category_id]) }}" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Page"> <i class="ri-edit-box-line"></i> </a>
                                                {{--@endif--}}
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($category->subCategory as $subCategory)
                                        <tr class="row1" data-id="{{ $subCategory->category_id }}" id="row_{{ $subCategory->category_id }}">
                                            <td>
                                                <div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="{{ $subCategory->category_id }}"></div>
                                            </td>
                                            <td>&nbsp; <img src="{{ url("assets/img/arrow-01.gif") }}">&nbsp; {{ $subCategory->category_title }}</td>
                                            <td>{{ date('d-m-Y h:i:s A', strtotime($subCategory->created_at)) }}</td>
                                            <td>
                                                @if($subCategory->category_status=='1')
                                                    <div id="td_status_{{ $subCategory->category_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $subCategory->category_id }}',0)" ><span class="badge bg-success">Active</span></a></div>
                                                @else
                                                    <div id="td_status_{{ $subCategory->category_id }}"><a href="javascript:void(0)" onclick="change_status('{{ $subCategory->category_id }}',1)" ><span class="badge bg-danger">Inactive</span></a></div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-inline-flex gap-1">
                                                    {{--@if(auth()->user()->can('category-delete'))--}}
                                                        <button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal('{{ $subCategory->category_id }}');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Category"> <i class="ri-delete-bin-line"></i> </button>
                                                    {{--@endif--}}
                                                    {{--@if(auth()->user()->can('category-edit'))--}}
                                                        <a href="{{ route("category-edit", ['id' => $subCategory->category_id]) }}" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Page"> <i class="ri-edit-box-line"></i> </a>
                                                    {{--@endif--}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
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
                                        <input type="hidden" id="category_id">
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
        var page_length = 25;
        var table = $("#basicExample").DataTable({
            pageLength: 25,
            language: {
                lengthMenu: "Display _MENU_ Records Per Page",
                info: "Showing Page _PAGE_ of _PAGES_",
            },
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
                    category_id: $(this).data('id'),
                    position: index + 1
                });
            });

            //alert(order)
            $.ajax({
                url: "{{ route('category-update-order') }}",
                type: "POST",
                //dataType: "json",
                data: {
                    order:order,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',      // top-right corner
                        icon: 'success',          // success, error, warning, info
                        title: response,          // message text
                        showConfirmButton: false, // no OK button
                        timer: 3500,              // auto close after 3.5 seconds
                        timerProgressBar: true,
                        padding: '0.5em 1em',      // smaller padding
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
                    Swal.fire({
                        toast: true,
                        position: 'top-end',      // top-right corner
                        icon: 'success',          // success, error, warning, info
                        title: response,          // message text
                        showConfirmButton: false, // no OK button
                        timer: 3500,              // auto close after 3.5 seconds
                        timerProgressBar: true,
                        padding: '0.5em 1em',      // smaller padding
                    });
                    if (status == 1){
                        $("#td_status_"+category_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+category_id+"', '0')\" ><span class=\"badge bg-success\">Active</span></a>");
                    } else {
                        $("#td_status_"+category_id).html("<a href=\"javascript:void(0)\" onclick=\"change_status('"+category_id+"', '1')\" ><span class=\"badge bg-danger\">Inactive</span></a>");
                    }
                }
            });
        }

        function openDeleteModal(category_id) {
            $('#category_id').val(category_id);
            $('#deleteModal').modal('show');
        }

        function deleteData() {
            let category_id = $('#category_id').val();
            $.ajax({
                url: "{{ route('category-delete') }}",
                type: "POST",
                data: {
                    _token:'{{ csrf_token() }}',
                    category_id:category_id,
                },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    /*$('#row_' + category_id).remove();
                     setTimeout(function(){
                     location.reload();
                     },2000);*/
                    table
                            .row($('#row_' + category_id))
                            .remove()
                            .draw(false);
                }
            });
        }
    </script>
@endsection
