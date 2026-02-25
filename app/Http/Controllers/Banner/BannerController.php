<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Validator;
use Session;
use DataTables;

class BannerController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');

        $this->middleware('permission:banner-list')->only(['view', 'load_table']);
        $this->middleware('permission:banner-add')->only(['create', 'insert']);
        $this->middleware('permission:banner-edit')->only(['edit', 'update']);
        $this->middleware('permission:banner-delete')->only(['delete']);
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function insert(Request $request)
    {
        $validator = $this->validateData($request);
        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        }

        $banner = new Banner();
        $this->saveUpdateData($banner, $request);

        Session::flash('successMsg', 'Banner details added successfully');
        return ['redirect_url' => 'banner-add'];
    }

    public function edit($id)
    {
        $bannerDetail = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('bannerDetail'));
    }

    public function update(Request $request)
    {
        $validator = $this->validateData($request);
        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        }

        $banner = Banner::findOrFail($request->banner_id);
        $this->saveUpdateData($banner, $request, true);

        Session::flash('successMsg', 'Banner details updated successfully');
        return ['redirect_url' => 'banner-edit', 'id' => $request->banner_id];
    }

    public function view()
    {
        return view('admin.banner.list');
    }

    public function load_table(Request $request)
    {
        //echo 'Hello'; exit();
        $bannerDetail = Banner::orderBy("banner_order")->get();
        return DataTables::of($bannerDetail)
            ->editColumn("checkbox", function ($banner){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$banner->banner_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($banner){
                return $banner->banner_title;
            })
            ->editColumn("image", function ($banner){
                if($banner->banner_image!='' && file_exists(public_path('/uploads/banner/'.$banner->banner_image))){
                    return "<img src='".asset('/uploads/banner/'.$banner->banner_image)."' width='100px'>";
                } else {
                    return;
                }
            })
            ->editColumn("status", function ($banner){
                if ($banner->banner_status == '1') {
                    return '<span id="td_status_'.$banner->banner_id.'"><a href="javascript:void(0)" onclick="change_status('.$banner->banner_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$banner->banner_id.'"><a href="javascript:void(0)" onclick="change_status('.$banner->banner_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($banner){
                $action = "";
                if (auth()->user()->can('banner-edit')) {
                    $action.= '<a href="'.route("banner-edit", ['id' => $banner->banner_id]).'" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('banner-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $banner->banner_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            //for add table row class
            ->setRowClass(function () {
                return 'row1';
            })
            //for add table row attr
            ->setRowAttr([
                'data-id' => function($banner) {
                    return $banner->banner_id;
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
            Banner::where("banner_id", $request->banner_id)->update(['banner_status' => $request->status]);
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
        foreach ($request->order as $order)
        {
            Banner::where("banner_id", $order["banner_id"])->update(["banner_order" => $order["position"]]);
        }
        echo 'Banner order changed successfully.';
    }

    public function delete(Request $request)
    {
        $banner = Banner::findOrFail($request->banner_id);
        $this->deleteFile($banner->banner_image);

        $banner->delete();
        return response('Banner deleted successfully.');
    }

    private function validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'banner_title' => 'required|string|max:255'
        ]);
    }

    private function saveUpdateData(Banner $banner, Request $request, $isUpdate = false)
    {
        if ($request->hasFile('banner_image')) {
            if ($isUpdate) {
                $this->deleteFile($banner->banner_image);
            }
            $banner->banner_image       = $this->uploadFile($request->file('banner_image'));
        }

        if ($request->hasFile('banner_icon')) {
            if ($isUpdate) {
                $this->deleteFile($banner->banner_icon);
            }
            $banner->banner_icon        = $this->uploadFile($request->file('banner_icon'));
        }

        if ($isUpdate) {
            $banner->updated_at         = date('Y-m-d H:i:s');
        } else {
            $banner->created_at         = date('Y-m-d H:i:s');
        }

        $banner->fill([
            'banner_title'              => $request->banner_title,
            'banner_text'               => $request->banner_text,
            'banner_text1'              => $request->banner_text1,
            'banner_desc'               => $request->banner_desc,
            'banner_status'             => '1'
        ]);

        $banner->save();
    }

    private function uploadFile($file)
    {
        $filename = 'IMG-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/banner'), $filename);
        return $filename;
    }

    private function deleteFile($filename)
    {
        if ($filename!='' && file_exists(public_path('/uploads/banner/'.$filename))) {
            @unlink(public_path('/uploads/banner/'.$filename));
        }
    }
}
