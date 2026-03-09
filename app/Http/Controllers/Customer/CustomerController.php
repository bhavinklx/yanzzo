<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }

    public function view()
    {
        return view("admin.customer.list");
    }

    public function load_table(Request $request)
    {
        $customerDetail = Customer::orderBy("customer_order", "DESC");
        return DataTables::of($customerDetail)
            ->editColumn("checkbox", function ($customer){
                return '<div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="' . $customer->customer_id . '"> </div>';
            })
            ->editColumn("title", function ($customer){
                return $customer->customer_name;
            })
            ->editColumn("email", function ($customer){
                return $customer->customer_email;
            })
            ->editColumn("phone", function ($customer){
                return $customer->customer_mobile;
            })
            ->editColumn("image", function ($customer){
                if($customer->customer_image!='' && file_exists(public_path('/uploads/customer/'.$customer->customer_image))){
                    return "<img src='".asset('/uploads/customer/'.$customer->customer_image)."' width='50px'>";
                } else {
                    return "--";
                }
            })
            ->editColumn("date", function ($customer){
                return date('d-m-Y h:i:s A', strtotime($customer->created_at));
            })
            ->editColumn("login_date", function ($customer) {
                if (!empty($customer->customer_last_login_date)) {
                    return date('d-m-Y h:i:s A', strtotime($customer->customer_last_login_date));
                }

                return '--';
            })
            ->editColumn("status", function ($customer) {
                if ($customer->customer_status == '1') {
                    return '<div id="td_status_' . $customer->customer_id . '"><a href="javascript:void(0)" onclick="change_status(' . $customer->customer_id . ',0)" ><span class="badge bg-success">Active</span></a></div>';
                } else {
                    return '<div id="td_status_' . $customer->customer_id . '"><a href="javascript:void(0)" onclick="change_status(' . $customer->customer_id . ',1)" ><span class="badge bg-danger">Inactive</span></a></div>';
                }
            })
            ->editColumn("action", function ($customer){
                $action = '<div class="d-inline-flex gap-1">';
                if (auth()->user()->can('customer-delete')) {
                    $action.= '<button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal(' . $customer->customer_id . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Customer"> <i class="ri-delete-bin-line"></i> </button>';
                }
                if (auth()->user()->can('customer-edit')) {
                    $action.= '<a href="'.route("customer-edit", ['id' => $customer->customer_id]).'" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Customer"> <i class="ri-edit-box-line"></i> </a>';
                }
                $action.= '</div>';
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "id" => function ($customer) {
                    return 'row_' . $customer->customer_id;
                },
                "data-id" => function ($customer) {
                    return $customer->customer_id;
                }
            ])
            ->rawColumns(["checkbox", "image", "status", "action"])
            ->make(true);
    }

    public function change_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Customer::where("customer_id", $request->customer_id)->update(["customer_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function update_order(Request $request)
    {
        foreach ($request->order as $order) {
            Customer::where("customer_id", $order["customer_id"])->update(["customer_order" => $order["position"]]);
        }
        echo 'Customer order changed successfully.';
    }

    public function delete(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $this->deleteFile($customer->customer_image);

        $customer->delete();
        return response('Customer deleted successfully.');
    }

    private function deleteFile($filename)
    {
        if ($filename!='' && file_exists(public_path('/uploads/customer/'.$filename))) {
            @unlink(public_path('/uploads/customer/'.$filename));
        }
    }
}
