<?php

namespace App\Http\Controllers\City;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cities;
use Validator;
use Session;
use DataTables;

class CityController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:city-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:city-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:city-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:city-delete', ['only' => ['delete']]);
    }

    public function createSlug(Request $request)
    {
        $slug = str_slug($request->city_title);
        $allSlugs = $this->checkSlug($slug);
        if (! $allSlugs->contains('city_slug', $slug)){
            return response()->json(['slug' => $slug]);
        }
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('city_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function checkSlug($slug)
    {
        return Cities::select("city_slug")->where("city_slug", 'like', $slug.'%')->get();
    }

    public function create()
    {
        return view("admin.city.create");
    }

    public function insert(Request $request)
    {
        $lastOrder = Cities::orderBy("city_order", "DESC")->first();
        $validator = Validator::make($request->all(),[
            "city_title" => "required",
            "city_slug" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasFile('city_image')) {
                $image = $request->file('city_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/city/'), $filename);
            } else {
                $filename = "";
            }
            $data                       = new Cities();
            $data->city_title           = $request->city_title;
            $data->city_slug            = $request->city_slug;
            $data->city_image           = $filename;
            $data->city_short_desc      = addslashes($request->city_short_desc);
            $data->city_desc            = $request->city_desc;
            $data->city_meta_title      = $request->city_meta_title;
            $data->city_meta_keyword    = $request->city_meta_keyword;
            $data->city_meta_desc       = $request->city_meta_desc;
            $data->city_canonical       = $request->city_canonical;
            $data->city_order           = (!empty($lastOrder)) ? $lastOrder->city_order + 1 : 1;
            $data->cities_status          = '1';
            $data->created_at           = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Cities details added successfully');
                return ["redirect_url" => "city-add"];
            }
        }
    }

    public function edit($id)
    {
        $cityDetail = Cities::find($id);
        return view("admin.city.edit", compact('cityDetail'));
    }

    public function update(Request $request)
    {
        $data = Cities::find($request->cities_id);
        $validator = Validator::make($request->all(),[
            "city_title" => "required",
            "city_slug" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasfile('city_image')) {
                //echo 'Hello'; exit();
                if ($data->city_image!='' && file_exists(public_path('/uploads/city/'.$data->city_image))) {
                    @unlink(public_path('/uploads/city/'.$data->city_image));
                }
                $image = $request->file('city_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path().'/uploads/city/', $filename);
            } else {
                $filename = $request->old_image;
            }
            $data->city_title           = $request->city_title;
            $data->city_slug            = $request->city_slug;
            $data->city_image           = $filename;
            $data->city_short_desc      = addslashes($request->city_short_desc);
            $data->city_desc            = $request->city_desc;
            $data->city_meta_title      = $request->city_meta_title;
            $data->city_meta_keyword    = $request->city_meta_keyword;
            $data->city_meta_desc       = $request->city_meta_desc;
            $data->city_canonical       = $request->city_canonical;
            $data->cities_status          = '1';
            $data->updated_at           = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Cities details updated successfully');
                return ["redirect_url" => "city-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.city.list");
    }

    public function load_table(Request $request)
    {
        $cityDetail = Cities::where('states_id', '12');
        return DataTables::of($cityDetail)
            ->editColumn("checkbox", function ($city){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$city->cities_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($city){
                return $city->cities_name;
            })
            /*->editColumn("image", function ($city){
                if($city->city_image!='' && file_exists(public_path('/uploads/city/'.$city->city_image))){
                    return "<img src='".url('/uploads/city/'.$city->city_image)."' width='100px'>";
                } else {
                    return;
                }
            })*/
            ->editColumn("date", function ($city){
                return date('d-m-Y h:i:s A', strtotime($city->created_at));
            })
            ->editColumn("status", function ($city){
                if ($city->cities_status == '1') {
                    return '<span id="td_status_'.$city->cities_id.'"><a href="javascript:void(0)" onclick="change_status('.$city->cities_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$city->cities_id.'"><a href="javascript:void(0)" onclick="change_status('.$city->cities_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            /*->editColumn("action", function ($city){
                $action = "";
                if (auth()->user()->can('city-edit'))
                {
                    $action.= '<a href="'.route("city-edit", ['id' => $city->cities_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('city-delete'))
                {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $city->cities_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })*/
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($city) {
                    return $city->cities_id;
                }
            ])
            ->rawColumns(["checkbox", "status", "action"])
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
            Cities::where("cities_id", $request->cities_id)->update(["cities_status" => $request->status]);
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
            Cities::where("cities_id", $order["cities_id"])->update(["city_order" => $order["position"]]);
        }
        echo 'Cities order changed successfully.';
    }

    public function delete(Request $request)
    {
        $image = Cities::find($request->cities_id);
        if ($image->city_image!='' && file_exists(public_path('/uploads/city/'.$image->city_image))) {
            @unlink(public_path('/uploads/city/'.$image->city_image));
        }
        Cities::where("cities_id", $request->cities_id)->delete();
    }
}
