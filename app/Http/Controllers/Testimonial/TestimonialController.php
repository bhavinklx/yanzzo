<?php

namespace App\Http\Controllers\Testimonial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }

    public function create(Request $request)
    {
        return view("admin.testimonial.create");
    }

    public function insert(Request $request)
    {
        $this->validateData($request);

        $testimonial = new Testimonial();
        $this->saveUpdateData($testimonial, $request);

        Session::flash('successMsg', 'Testimonial added successfully');
        return response()->json(['redirect_url' => route('testimonial-list')]);
    }

    public function edit($id)
    {
        $testimonialDetail = Testimonial::find($id);
        return view("admin.testimonial.edit", compact('testimonialDetail'));
    }

    public function update(Request $request)
    {
        $this->validateData($request);

        $testimonial = Testimonial::findOrFail($request->testimonial_id);
        $this->saveUpdateData($testimonial, $request, true);

        Session::flash('successMsg', 'Testimonial updated successfully');
        return response()->json(['redirect_url' => route('testimonial-list')]);
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
                return '<div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="' . $testimonial->testimonial_id . '"> </div>';
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
            ->editColumn("status", function ($testimonial) {
                if ($testimonial->testimonial_status == '1') {
                    return '<div id="td_status_' . $testimonial->testimonial_id . '"><a href="javascript:void(0)" onclick="change_status(' . $testimonial->testimonial_id . ',0)" ><span class="badge bg-success">Active</span></a></div>';
                } else {
                    return '<div id="td_status_' . $testimonial->testimonial_id . '"><a href="javascript:void(0)" onclick="change_status(' . $testimonial->testimonial_id . ',1)" ><span class="badge bg-danger">Inactive</span></a></div>';
                }
            })
            ->editColumn("action", function ($testimonial){
                $action = '<div class="d-inline-flex gap-1">';
                if (auth()->user()->can('testimonial-delete')) {
                    $action.= '<button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal(' . $testimonial->testimonial_id . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Testimonial"> <i class="ri-delete-bin-line"></i> </button>';
                }
                if (auth()->user()->can('testimonial-edit')) {
                    $action.= '<a href="'.route("testimonial-edit", ['id' => $testimonial->testimonial_id]).'" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Patient"> <i class="ri-edit-box-line"></i> </a>';
                }
                $action.= '</div>';
                return $action;
            })
            //for add table row class
            ->setRowClass(function () {
                return 'row1';
            })
            //for add table row attr
            ->setRowAttr([
                "id" => function ($testimonial) {
                    return 'row_' . $testimonial->testimonial_id;
                },
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
            Testimonial::where("testimonial_id", $request->testimonial_id)->update(["testimonial_status" => $request->status]);
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

        //Dropzone async upload
        if ($request->testimonial_image) {
            $testimonial->testimonial_image = $request->testimonial_image; // filename string
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

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        //Call protected method
        $filename = $this->storeImage($request->file('file'));

        return response()->json([
            'filename' => $filename
        ]);
    }

    protected function storeImage($file)
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
