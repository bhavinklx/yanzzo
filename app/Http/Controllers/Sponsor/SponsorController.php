<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SponsorController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }

    public function create(Request $request)
    {
        return view("admin.sponsor.create");
    }

    public function insert(Request $request)
    {
        $this->validateData($request);

        $sponsor = new Sponsor();
        $this->saveUpdateData($sponsor, $request);

        Session::flash('successMsg', 'Sponsor added successfully');
        return response()->json(['redirect_url' => route('sponsor-list')]);
    }

    public function edit($id)
    {
        $sponsorDetail = Sponsor::find($id);
        return view("admin.sponsor.edit", compact('sponsorDetail'));
    }

    public function update(Request $request)
    {
        $this->validateData($request);

        $sponsor = Sponsor::findOrFail($request->sponsor_id);
        $this->saveUpdateData($sponsor, $request, true);

        Session::flash('successMsg', 'Sponsor updated successfully');
        return response()->json(['redirect_url' => route('sponsor-list')]);
    }

    public function view()
    {
        return view("admin.sponsor.list");
    }

    public function load_table(Request $request)
    {
        $sponsorDetail = Sponsor::orderBy("sponsor_order");
        return DataTables::of($sponsorDetail)
            ->editColumn("checkbox", function ($sponsor){
                return '<div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="' . $sponsor->sponsor_id . '"> </div>';
            })
            ->editColumn("title", function ($sponsor){
                return $sponsor->sponsor_title;
            })
            ->editColumn("image", function ($sponsor){
                if($sponsor->sponsor_image!='' && file_exists(public_path('/uploads/sponsor/'.$sponsor->sponsor_image))){
                    return "<img src='".asset('/uploads/sponsor/'.$sponsor->sponsor_image)."' width='100px'>";
                } else {
                    return;
                }
            })
            ->editColumn("date", function ($sponsor){
                return date('d-m-Y h:i A', strtotime($sponsor->created_at));
            })
            ->editColumn("status", function ($sponsor) {
                if ($sponsor->sponsor_status == '1') {
                    return '<div id="td_status_' . $sponsor->sponsor_id . '"><a href="javascript:void(0)" onclick="change_status(' . $sponsor->sponsor_id . ',0)" ><span class="badge bg-success">Active</span></a></div>';
                } else {
                    return '<div id="td_status_' . $sponsor->sponsor_id . '"><a href="javascript:void(0)" onclick="change_status(' . $sponsor->sponsor_id . ',1)" ><span class="badge bg-danger">Inactive</span></a></div>';
                }
            })
            ->editColumn("action", function ($sponsor){
                $action = '<div class="d-inline-flex gap-1">';
                if (auth()->user()->can('sponsor-delete')) {
                    $action.= '<button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal(' . $sponsor->sponsor_id . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Sponsor"> <i class="ri-delete-bin-line"></i> </button>';
                }
                if (auth()->user()->can('sponsor-edit')) {
                    $action.= '<a href="'.route("sponsor-edit", ['id' => $sponsor->sponsor_id]).'" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Patient"> <i class="ri-edit-box-line"></i> </a>';
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
                "id" => function ($sponsor) {
                    return 'row_' . $sponsor->sponsor_id;
                },
                'data-id' => function($sponsor) {
                    return $sponsor->sponsor_id;
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
            Sponsor::where("sponsor_id", $request->sponsor_id)->update(["sponsor_status" => $request->status]);
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
            Sponsor::where("sponsor_id", $order["sponsor_id"])->update(["sponsor_order" => $order["position"]]);
        }
        echo 'Sponsor order changed successfully.';
    }

    public function delete(Request $request)
    {
        $sponsor = Sponsor::findOrFail($request->sponsor_id);
        $this->deleteFile($sponsor->sponsor_image);

        $sponsor->delete();
        return response('Sponsor deleted successfully.');
    }

    private function validateData(Request $request)
    {
        return Validator::make($request->all(), [
            'sponsor_title'             => 'required|string|max:255'
        ]);
    }

    private function saveUpdateData(Sponsor $sponsor, Request $request, $isUpdate = false)
    {
        if ($request->hasFile('sponsor_image')) {
            if ($isUpdate) {
                $this->deleteFile($sponsor->sponsor_image);
            }
            $sponsor->sponsor_image = $this->uploadFile($request->file('sponsor_image'));
        }

        //Dropzone async upload
        if ($request->sponsor_image) {
            $sponsor->sponsor_image = $request->sponsor_image; // filename string
        }

        if ($isUpdate) {
            $sponsor->updated_at        = date('Y-m-d H:i:s');
        } else {
            $lastOrder                  = Sponsor::orderBy("sponsor_order", "DESC")->first();
            $sponsor->sponsor_order = (!empty($lastOrder)) ? $lastOrder->sponsor_order + 1 : 1;
            $sponsor->created_at        = date('Y-m-d H:i:s');
        }

        $sponsor->fill([
            'sponsor_title'             => $request->sponsor_title,
            'sponsor_status'            => $request->sponsor_status
        ]);

        $sponsor->save();
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
        $file->move(public_path('uploads/sponsor'), $filename);
        return $filename;
    }

    private function deleteFile($filename)
    {
        if ($filename!='' && file_exists(public_path('/uploads/sponsor/'.$filename))) {
            @unlink(public_path('/uploads/sponsor/'.$filename));
        }
    }
}
