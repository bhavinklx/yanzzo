<?php

namespace App\Http\Controllers\Usp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Usp;
use Validator;
use Session;
use DataTables;

class UspController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:usp-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:usp-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:usp-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:usp-delete', ['only' => ['delete']]);
    }

    public function create()
    {
        $cityDetail = City::where("city_status", "1")->get();
        return view("admin.usp.create", compact('cityDetail'));
    }

    public function insert(Request $request)
    {
        $lastOrder = Usp::orderBy("usp_order", "DESC")->first();
        $validator = Validator::make($request->all(),[
            "city_id" => "required|not_in:0",
            "usp_title" => "required"
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasFile('usp_image')) {
                $image = $request->file('usp_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/usp/'), $filename);
            } else {
                $filename = "";
            }
            $data                   = new Usp();
            $data->city_id          = (count($request->city_id) > 0) ? implode(',', $request->city_id) : '';
            $data->usp_title        = $request->usp_title;
            $data->usp_image        = $filename;
            $data->usp_desc         = $request->usp_desc;
            $data->usp_order        = (!empty($lastOrder)) ? $lastOrder->usp_order + 1 : 1;
            $data->usp_status       = $request->usp_status;
            $data->created_at       = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Usp details added successfully');
                return ["redirect_url" => "usp-add"];
            }
        }
    }

    public function edit($id)
    {
        $uspDetail = Usp::find($id);
        $cityDetail = City::where("city_status", "1")->get();
        return view("admin.usp.edit", compact('uspDetail', 'cityDetail'));
    }

    public function update(Request $request)
    {
        $data = Usp::find($request->usp_id);
        $validator = Validator::make($request->all(),[
            "city_id" => "required|not_in:0",
            "usp_title" => "required"
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasfile('usp_image')) {
                //echo 'Hello'; exit();
                if ($data->usp_image!='' && file_exists(public_path('/uploads/usp/'.$data->usp_image))) {
                    @unlink(public_path('/uploads/usp/'.$data->usp_image));
                }
                $image = $request->file('usp_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path().'/uploads/usp/', $filename);
            } else {
                $filename = $request->old_image;
            }
            $data->city_id          = (count($request->city_id) > 0) ? implode(',', $request->city_id) : '';
            $data->usp_title        = $request->usp_title;
            $data->usp_image        = $filename;
            $data->usp_desc         = $request->usp_desc;
            $data->usp_status       = $request->usp_status;
            $data->updated_at       = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Usp details updated successfully');
                return ["redirect_url" => "usp-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.usp.list");
    }

    public function load_table(Request $request)
    {
        $uspDetail = Usp::orderBy("usp_order")->get();
        return DataTables::of($uspDetail)
            ->editColumn("checkbox", function ($usp){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$usp->usp_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("city", function ($usp){
                $cityDetail = City::get()->toArray();
                $cityArray = $cityNameArray = [];
                for ($c=0; $c < count($cityDetail); $c++) {
                    $cityArray[$cityDetail[$c]['city_id']] = $cityDetail[$c]['city_title'];
                }
                $cityId = explode(',', $usp->city_id);
                for ($c=0; $c < count($cityId); $c++) {
                    $cityNameArray[] = $cityArray[$cityId[$c]];
                }
                return implode(', ', $cityNameArray);
            })
            ->editColumn("title", function ($usp){
                return $usp->usp_title;
            })
            ->editColumn("image", function ($usp){
                if($usp->usp_image!='' && file_exists(public_path('/uploads/usp/'.$usp->usp_image))){
                    return "<img src='".asset('/uploads/usp/'.$usp->usp_image)."' width='100px'>";
                } else {
                    return;
                }
            })
            ->editColumn("date", function ($usp){
                return date('d-m-Y h:i:s A', strtotime($usp->created_at));
            })
            ->editColumn("status", function ($usp){
                if ($usp->usp_status == '1') {
                    return '<span id="td_status_'.$usp->usp_id.'"><a href="javascript:void(0)" onclick="change_status('.$usp->usp_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$usp->usp_id.'"><a href="javascript:void(0)" onclick="change_status('.$usp->usp_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($usp){
                $action = "";
                if (auth()->user()->can('usp-edit'))
                {
                    $action.= '<a href="'.route("usp-edit", ['id' => $usp->usp_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('usp-delete'))
                {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $usp->usp_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($usp) {
                    return $usp->usp_id;
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
            Usp::where("usp_id", $request->usp_id)->update(["usp_status" => $request->status]);
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
            Usp::where("usp_id", $order["usp_id"])->update(["usp_order" => $order["position"]]);
        }
        echo 'Usp order changed successfully.';
    }

    public function delete(Request $request)
    {
        $image = Usp::find($request->usp_id);
        if ($image->usp_image!='' && file_exists(public_path('/uploads/usp/'.$image->usp_image))) {
            @unlink(public_path('/uploads/usp/'.$image->usp_image));
        }
        Usp::where("usp_id", $request->usp_id)->delete();
    }
}