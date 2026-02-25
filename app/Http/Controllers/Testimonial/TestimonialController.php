<?php

namespace App\Http\Controllers\Testimonial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Validator;
use Session;
use DataTables;

class TestimonialController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:testimonial-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:testimonial-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:testimonial-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:testimonial-delete', ['only' => ['delete']]);
    }

    public function create(Request $request)
    {
        return view("admin.testimonial.create");
    }

    public function insert(Request $request)
    {
        $validator = $this->validateData($request);
        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        }

        $testimonial = new Testimonial();
        $this->saveUpdateData($testimonial, $request);

        Session::flash('successMsg', 'Testimonial details added successfully');
        return ["redirect_url" => "testimonial-add"];
    }

    public function edit($id)
    {
        $testimonialDetail = Testimonial::find($id);
        return view("admin.testimonial.edit", compact('testimonialDetail'));
    }

    public function update(Request $request)
    {
        $validator = $this->validateData($request);
        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        }

        $testimonial = Testimonial::findOrFail($request->testimonial_id);
        $this->saveUpdateData($testimonial, $request, true);

        Session::flash('successMsg', 'Testimonial details updated successfully');
        return ['redirect_url' => 'testimonial-edit', 'id' => $request->testimonial_id];
    }

    public function view()
    {
        return view("admin.testimonial.list");
    }

    public function load_table(Request $request)
    {
        $testimonialDetail = Testimonial::orderBy("testimonial_order");
        return DataTables::of($testimonialDetail)
            ->editColumn("checkbox", function ($testimonial){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$testimonial->testimonial_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($testimonial){
                return $testimonial->testimonial_title;
            })
            ->editColumn("image", function ($testimonial){
                if($testimonial->testimonial_image!='' && file_exists(public_path('/uploads/testimonial/'.$testimonial->testimonial_image))){
                    return "<img src='".asset('/uploads/testimonial/'.$testimonial->testimonial_image)."' width='100px'>";
                } else {
                    return;
                }
            })
            ->editColumn("date", function ($testimonial){
                return date('d-m-Y h:i A', strtotime($testimonial->created_at));
            })
            ->editColumn("status", function ($testimonial){
                if ($testimonial->testimonial_status == '1') {
                    return '<span id="td_status_'.$testimonial->testimonial_id.'"><a href="javascript:void(0)" onclick="change_status('.$testimonial->testimonial_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$testimonial->testimonial_id.'"><a href="javascript:void(0)" onclick="change_status('.$testimonial->testimonial_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($testimonial){
                $action = "";
                if (auth()->user()->can('testimonial-edit')) {
                    $action.= '<a href="'.route("testimonial-edit", ['id' => $testimonial->testimonial_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('testimonial-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $testimonial->testimonial_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            //for add table row class
            ->setRowClass(function () {
                return 'row1';
            })
            //for add table row attr
            ->setRowAttr([
                'data-id' => function($testimonial) {
                    return $testimonial->testimonial_id;
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
            Testimonial::where('testimonial_id', $request->testimonial_id)->update(["testimonial_status" => $request->status]);
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
            Testimonial::where("testimonial_id", $order["testimonial_id"])->update(["testimonial_order" => $order["position"]]);
        }
        echo 'Testimonial order changed successfully.';
    }

    public function delete(Request $request)
    {
        $testimonial = Testimonial::findOrFail($request->testimonial_id);
        $this->deleteFile($testimonial->testimonial_image);

        $testimonial->delete();
        return response('Testimonial deleted successfully.');
    }

    private function validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'testimonial_title'             => 'required|string|max:255'
        ]);
    }

    private function saveUpdateData(Testimonial $testimonial, Request $request, $isUpdate = false)
    {
        if ($request->hasFile('testimonial_image')) {
            if ($isUpdate) {
                $this->deleteFile($testimonial->testimonial_image);
            }
            $testimonial->testimonial_image = $this->uploadFile($request->file('testimonial_image'));
        }

        if ($isUpdate) {
            $testimonial->updated_at        = date('Y-m-d H:i:s');
        } else {
            $lastOrder                      = Testimonial::orderBy("testimonial_order", "DESC")->first();
            $testimonial->testimonial_order = (!empty($lastOrder)) ? $lastOrder->testimonial_order + 1 : 1;
            $testimonial->created_at        = date('Y-m-d H:i:s');
        }
        
        $testimonial->fill([
            'testimonial_title'             => $request->testimonial_title,
            'testimonial_designation'       => $request->testimonial_designation,
            'testimonial_desc'              => $request->testimonial_desc,
            'testimonial_status'            => $request->testimonial_status
        ]);

        $testimonial->save();
    }

    private function uploadFile($file)
    {
        $filename = 'IMG-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/testimonial'), $filename);
        return $filename;
    }

    private function deleteFile($filename)
    {
        if ($filename!='' && file_exists(public_path('/uploads/testimonial/'.$filename))) {
            @unlink(public_path('/uploads/testimonial/'.$filename));
        }
    }
}
