<?php

namespace App\Http\Controllers\Faq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Faq;
use Validator;
use Session;
use DataTables;

class FaqController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:faq-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:faq-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:faq-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:faq-delete', ['only' => ['delete']]);
    }

    public function create()
    {
        //$cityDetail = City::where("city_status", "1")->get();
        return view("admin.faq.create");
    }

    public function insert(Request $request)
    {
        $lastOrder = Faq::orderBy("faq_order", "DESC")->first();
        $validator = Validator::make($request->all(),[
            //"city_id" => "required|not_in:0",
            "faq_title" => "required"
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data               = new Faq();
            $data->faq_title    = $request->faq_title;
            $data->faq_desc     = $request->faq_desc;
            $data->faq_order    = (!empty($lastOrder)) ? $lastOrder->faq_order + 1 : 1;
            $data->faq_status   = '1';
            $data->faq_hstatus  = '0';
            $data->created_at   = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Faq details added successfully');
                return ["redirect_url" => "faq-add"];
            }
        }
    }

    public function edit($id)
    {
        $faqDetail = Faq::find($id);
        return view("admin.faq.edit", compact('faqDetail'));
    }

    public function update(Request $request)
    {
        $data = Faq::find($request->faq_id);
        $validator = Validator::make($request->all(),[
            "faq_title" => "required"
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data->faq_title    = $request->faq_title;
            $data->faq_desc     = $request->faq_desc;
            $data->faq_status   = '1';
            $data->updated_at   = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Faq details updated successfully');
                return ["redirect_url" => "service-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.faq.list");
    }

    public function load_table(Request $request)
    {


        $faqDetail = Faq::orderBy("faq_order")->get();
        return DataTables::of($faqDetail)
            ->editColumn("checkbox", function ($faqs){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$faqs->faq_id.'" class="custom-checkbox check_class" />';
            })
            /*->editColumn("city", function ($faqs){
                $cityDetail = City::get()->toArray();
                $cityArray = $cityNameArray = [];
                for ($c=0; $c < count($cityDetail); $c++) {
                    $cityArray[$cityDetail[$c]['city_id']] = $cityDetail[$c]['city_title'];
                }
                $cityId = explode(',', $faqs->city_id);
                for ($c=0; $c < count($cityId); $c++) {
                    $cityNameArray[] = $cityArray[$cityId[$c]];
                }
                return implode(', ', $cityNameArray);
            })*/
            ->editColumn("title", function ($faqs){
                return $faqs->faq_title;
            })
            ->editColumn("date", function ($faqs){
                return date('d-m-Y h:i:s A', strtotime($faqs->created_at));
            })
            ->editColumn("status", function ($faqs){
                if ($faqs->faq_status == '1') {
                    return '<span id="td_status_'.$faqs->faq_id.'"><a href="javascript:void(0)" onclick="change_status('.$faqs->faq_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$faqs->faq_id.'"><a href="javascript:void(0)" onclick="change_status('.$faqs->faq_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            /*->editColumn("home_status", function ($faqs) {
                if ($faqs->faq_hstatus == '1') {
                    return '<span id="td_home_status_' . $faqs->faq_id . '"><a href="javascript:void(0)" onclick="change_home_status(' . $faqs->faq_id . ', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_home_status_' . $faqs->faq_id . '"><a href="javascript:void(0)" onclick="change_home_status(' . $faqs->faq_id . ', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })*/
            ->editColumn("action", function ($faqs){
                $action = "";
                if (auth()->user()->can('faq-edit')) {
                    $action.= '<a href="'.route("faq-edit", ['id' => $faqs->faq_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('faq-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $faqs->faq_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($faqs) {
                    return $faqs->faq_id;
                }
            ])
            ->rawColumns(["checkbox", "status", "home_status", "action"])
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
            Faq::where("faq_id", $request->faq_id)->update(["faq_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function change_home_status(Request $request)
    {
        if (!$request->ajax()) {
            exit('No direct script access allowed');
        }
        if (!empty($request->all())) {
            Faq::where("faq_id", $request->faq_id)->update(['faq_hstatus' => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0) {
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function update_order(Request $request)
    {
        //print_r($request->order); exit();
        foreach ($request->order as $order) {
            Faq::where("faq_id", $order["faq_id"])->update(["faq_order" => $order["position"]]);
        }
        echo 'Faq order changed successfully.';
    }

    public function delete(Request $request)
    {
        Faq::where("faq_id", $request->faq_id)->delete();
    }
}