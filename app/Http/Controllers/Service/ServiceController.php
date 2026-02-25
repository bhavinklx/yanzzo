<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Service;
use Validator;
use Session;
use DataTables;

class ServiceController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:service-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:service-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:service-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:service-delete', ['only' => ['delete']]);
    }

    public function create()
    {
        $cityDetail = City::where("city_status", "1")->get();
        return view("admin.service.create", compact('cityDetail'));
    }

    public function insert(Request $request)
    {
        $lastOrder = Service::orderBy("service_order", "DESC")->first();
        $validator = Validator::make($request->all(),[
            "city_id" => "required|not_in:0",
            "service_title" => "required"
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasFile('service_image')) {
                $image = $request->file('service_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/service/'), $filename);
            } else {
                $filename = "";
            }
            $data                   = new Service();
            $data->city_id          = (count($request->city_id) > 0) ? implode(',', $request->city_id) : '';
            $data->service_title    = $request->service_title;
            $data->service_image    = $filename;
            $data->service_desc     = $request->service_desc;
            $data->service_order    = (!empty($lastOrder)) ? $lastOrder->service_order + 1 : 1;
            $data->service_status   = $request->service_status;
            $data->created_at       = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Service details added successfully');
                return ["redirect_url" => "service-add"];
            }
        }
    }

    public function edit($id)
    {
        $serviceDetail = Service::find($id);
        $cityDetail = City::where("city_status", "1")->get();
        return view("admin.service.edit", compact('serviceDetail', 'cityDetail'));
    }

    public function update(Request $request)
    {
        $data = Service::find($request->service_id);
        $validator = Validator::make($request->all(),[
            "city_id" => "required|not_in:0",
            "service_title" => "required"
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasfile('service_image')) {
                //echo 'Hello'; exit();
                if ($data->service_image!='' && file_exists(public_path('/uploads/service/'.$data->service_image))) {
                    @unlink(public_path('/uploads/service/'.$data->service_image));
                }
                $image = $request->file('service_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path().'/uploads/service/', $filename);
            } else {
                $filename = $request->old_image;
            }
            $data->city_id          = (count($request->city_id) > 0) ? implode(',', $request->city_id) : '';
            $data->service_title    = $request->service_title;
            $data->service_image    = $filename;
            $data->service_desc     = $request->service_desc;
            $data->service_status   = $request->service_status;
            $data->updated_at       = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Service details updated successfully');
                return ["redirect_url" => "service-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.service.list");
    }

    public function load_table(Request $request)
    {
        $serviceDetail = Service::orderBy("service_order")->get();
        return DataTables::of($serviceDetail)
            ->editColumn("checkbox", function ($service){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$service->service_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("city", function ($service){
                $cityDetail = City::get()->toArray();
                $cityArray = $cityNameArray = [];
                for ($c=0; $c < count($cityDetail); $c++) {
                    $cityArray[$cityDetail[$c]['city_id']] = $cityDetail[$c]['city_title'];
                }
                $cityId = explode(',', $service->city_id);
                for ($c=0; $c < count($cityId); $c++) {
                    $cityNameArray[] = $cityArray[$cityId[$c]];
                }
                return implode(', ', $cityNameArray);
            })
            ->editColumn("title", function ($service){
                return $service->service_title;
            })
            ->editColumn("image", function ($service){
                if($service->service_image!='' && file_exists(public_path('/uploads/service/'.$service->service_image))){
                    return "<img src='".asset('/uploads/service/'.$service->service_image)."' width='100px'>";
                } else {
                    return;
                }
            })
            ->editColumn("date", function ($service){
                return date('d-m-Y h:i:s A', strtotime($service->created_at));
            })
            ->editColumn("status", function ($service){
                if ($service->service_status == '1') {
                    return '<span id="td_status_'.$service->service_id.'"><a href="javascript:void(0)" onclick="change_status('.$service->service_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$service->service_id.'"><a href="javascript:void(0)" onclick="change_status('.$service->service_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($service){
                $action = "";
                if (auth()->user()->can('service-edit'))
                {
                    $action.= '<a href="'.route("service-edit", ['id' => $service->service_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('service-delete'))
                {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $service->service_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($service) {
                    return $service->service_id;
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
            Service::where("service_id", $request->service_id)->update(["service_status" => $request->status]);
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
            Service::where("service_id", $order["service_id"])->update(["service_order" => $order["position"]]);
        }
        echo 'Service order changed successfully.';
    }

    public function delete(Request $request)
    {
        $image = Service::find($request->service_id);
        if ($image->service_image!='' && file_exists(public_path('/uploads/service/'.$image->service_image))) {
            @unlink(public_path('/uploads/service/'.$image->service_image));
        }
        Service::where("service_id", $request->service_id)->delete();
    }
}