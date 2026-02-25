<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use Validator;
use Session;
use DataTables;

class DiscountController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:discount-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:discount-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:discount-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:discount-delete', ['only' => ['delete']]);
    }
    
    public function create()
    {
        return view("admin.discount.create");
    }

    public function insert(Request $request)
    {
        //print_r($request->all()); die;
        $validator = Validator::make($request->all(),[
            "discount_title" => "required",
            "discount_code" => "required",
            'discount_type' => 'required|not_in:0',
            'discount_amount' => 'required|not_in:0',
            "discount_start_date" => "required",
            "discount_start_time" => "required",
            "discount_end_date" => "required",
            "discount_end_time" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {

            $data                           = new Discount();
            $data->discount_scenario_type   = $request->discount_scenario_type;
            $data->discount_title           = $request->discount_title;
            $data->discount_code            = $request->discount_code;
            $data->discount_type            = $request->discount_type;
            $data->discount_amount          = $request->discount_amount;
            $data->discount_start_date      = date("Y-m-d",strtotime($request->discount_start_date));
            $data->discount_start_time      = addslashes($request->discount_start_time);
            $data->discount_end_date        = date("Y-m-d",strtotime($request->discount_end_date));
            $data->discount_end_time        = addslashes($request->discount_end_time);
            $data->discount_min_amount      = $request->discount_min_amount;
            $data->discount_max_discount    = $request->discount_max_discount;
            $data->discount_status          = '1';
            $data->created_at               = date('Y-m-d H:i:s');
            //$rs = Discount::insertGetId($blogData);
            if ($data->save()) {
                Session::flash('successMsg', 'Discount details added successfully');
                return ["redirect_url" => "discount-add"];
            }
        }
    }

    public function edit($id)
    {
        $discountDetail = Discount::find($id);
        return view("admin.discount.edit", compact('discountDetail'));
    }

    public function update(Request $request)
    {
        $data = Discount::find($request->discount_id);
        $validator = Validator::make($request->all(),[
            "discount_title" => "required",
            "discount_code" => "required",
            'discount_type' => 'required|not_in:0',
            'discount_amount' => 'required|not_in:0',
            "discount_start_date" => "required",
            "discount_start_time" => "required",
            "discount_end_date" => "required",
            "discount_end_time" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data->discount_scenario_type   = $request->discount_scenario_type;
            $data->discount_title           = $request->discount_title;
            $data->discount_code            = $request->discount_code;
            $data->discount_type            = $request->discount_type;
            $data->discount_amount          = $request->discount_amount;
            $data->discount_start_date      = date("Y-m-d",strtotime($request->discount_start_date));
            $data->discount_start_time      = addslashes($request->discount_start_time);
            $data->discount_end_date        = date("Y-m-d",strtotime($request->discount_end_date));
            $data->discount_end_time        = addslashes($request->discount_end_time);
            $data->discount_min_amount      = $request->discount_min_amount;
            $data->discount_max_discount    = $request->discount_max_discount;
            $data->updated_at               = date('Y-m-d H:i:s');

            //$rs = Discount::where("id", $request->discount_id)->limit(1)->update($blogData);
            if ($data->save()) {
                Session::flash('successMsg', 'Discount details updated successfully');
                return ["redirect_url" => "discount-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.discount.list");
    }

    public function load_table(Request $request)
    {
        $discountDetail = Discount::orderBy("discount_id")->get();
        return DataTables::of($discountDetail)
            ->editColumn("checkbox", function ($discount){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$discount->discount_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($discount){
                return $discount->discount_title;
            })
            ->editColumn("code", function ($discount){
                return $discount->discount_code;
            })
            ->editColumn("sdate", function ($discount){
                return date('d-m-Y', strtotime($discount->discount_start_date)) .' '. date('h:i:s A', strtotime($discount->discount_start_time));
            })
            ->editColumn("edate", function ($discount){
                return date('d-m-Y', strtotime($discount->discount_end_date)) .' '. date('h:i:s A', strtotime($discount->discount_end_time));
            })
            ->editColumn("date", function ($discount){
                return date('d-m-Y h:i A', strtotime($discount->created_at));
            })
            ->editColumn("status", function ($discount){
                if ($discount->discount_status == '1') {
                    return '<span id="td_status_'.$discount->discount_id.'"><a href="javascript:void(0)" onclick="change_status('.$discount->discount_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$discount->discount_id.'"><a href="javascript:void(0)" onclick="change_status('.$discount->discount_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($discount){
                $action = "";
                if (auth()->user()->can('discount-edit')) {
                    $action.= '<a href="'.route("discount-edit", ['id' => $discount->discount_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('discount-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $discount->discount_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($discount) {
                    return $discount->discount_id;
                }
            ])
            ->rawColumns(["checkbox", "image", "status", "popular_status", "action"])
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
            Discount::where("discount_id", $request->discount_id)->update(["discount_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function update_order(Request $request)
    {
        //print_r($request->order); exit();
        foreach ($request->order as $order) {
            Discount::where("discount_id", $order["discount_id"])->update(["blog_order" => $order["position"]]);
        }
        echo 'Discount order changed successfully.';
    }

    public function delete(Request $request)
    {
        Discount::where("discount_id", $request->discount_id)->delete();
    }
}
