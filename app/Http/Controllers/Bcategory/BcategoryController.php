<?php

namespace App\Http\Controllers\Bcategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bcategory;
use Validator;
use Session;
use DataTables;
use App\Exports\BcategotyExport;
use Maatwebsite\Excel\Facades\Excel;

class BcategoryController extends Controller
{

    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:bcategory-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:bcategory-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:bcategory-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:bcategory-delete', ['only' => ['delete']]);
    }

    public function createSlug(Request $request)
    {
        $slug = str_slug($request->bcategory_title);
        $allSlugs = $this->checkSlug($slug);
        if (!$allSlugs->contains('bcategory_slug', $slug)) {
            return response()->json(['slug' => $slug]);
        }
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('bcategory_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function checkSlug($slug)
    {
        return Bcategory::select("bcategory_slug")->where("bcategory_slug", 'like', $slug . '%')->get();
    }

    public function create()
    {
        return view("admin.bcategory.create");
    }

    public function insert(Request $request)
    {
        $validator = $this->validateData($request);
        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        }

        $bcategory = new Bcategory();
        $this->saveUpdateData($bcategory, $request);

        Session::flash('successMsg', 'Category details added successfully');
        return ["redirect_url" => "bcategory-add"];
    }

    public function edit($id)
    {
        $bcategoryDetail = Bcategory::find($id);
        return view("admin.bcategory.edit", compact('bcategoryDetail'));
    }

    public function update(Request $request)
    {
        $validator = $this->validateData($request);
        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        }

        $bcategory = Bcategory::findOrFail($request->bcategory_id);
        $this->saveUpdateData($bcategory, $request, true);

        Session::flash('successMsg', 'Category details updated successfully');
        return ["redirect_url" => "bcategory-add", 'id' => $request->bcategory_id];
    }

    public function view()
    {
        return view("admin.bcategory.list");
    }

    public function load_table(Request $request)
    {
        $bcategoryDetail = Bcategory::orderBy("bcategory_order")->get();
        return DataTables::of($bcategoryDetail)
            ->editColumn("checkbox", function ($bcategory) {
                return '<input type="checkbox" name="check[]" id="check[]" value="' . $bcategory->bcategory_id . '" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($bcategory) {
                return $bcategory->bcategory_title;
            })
            ->editColumn("date", function ($bcategory) {
                return date('d-m-Y h:i A', strtotime($bcategory->created_at));
            })
            ->editColumn("status", function ($bcategory) {
                if ($bcategory->bcategory_status == '1') {
                    return '<span id="td_status_' . $bcategory->bcategory_id . '"><a href="javascript:void(0)" onclick="change_status(' . $bcategory->bcategory_id . ', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_' . $bcategory->bcategory_id . '"><a href="javascript:void(0)" onclick="change_status(' . $bcategory->bcategory_id . ', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($bcategory) {
                $action = "";
                if (auth()->user()->can('bcategory-edit')) {
                    $action.= '<a href="' . route("bcategory-edit", ['id' => $bcategory->bcategory_id]) . '" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('bcategory-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $bcategory->bcategory_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($bcategory) {
                    return $bcategory->bcategory_id;
                }
            ])
            ->rawColumns(["checkbox", "status", "action"])
            ->make(true);
    }

    public function change_status(Request $request)
    {
        if (!$request->ajax()) {
            exit('No direct script access allowed');
        }
        if (!empty($request->all())) {
            Bcategory::where("bcategory_id", $request->bcategory_id)->update(['bcategory_status' => $request->status]);
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
            Bcategory::where("bcategory_id", $order["bcategory_id"])->update(["bcategory_order" => $order["position"]]);
        }
        echo 'Category order changed successfully.';
    }

    public function delete(Request $request)
    {
        Bcategory::where("bcategory_id", $request->bcategory_id)->delete();
    }

    private function validateData(Request $request)
    {
        return Validator::make($request->all(), [
            "bcategory_title" => 'required|string|max:255',
            "bcategory_slug" => 'required|string|max:255'
        ]);
    }
    
    private function saveUpdateData(Bcategory $bcategory, Request $request, $isUpdate = false)
    {
        if ($isUpdate) {
            $bcategory->updated_at      = date('Y-m-d H:i:s');
        } else {
            $bcategory->created_at      = date('Y-m-d H:i:s');
        }

        $bcategory->fill([
            'bcategory_title'           => $request->bcategory_title,
            'bcategory_slug'            => $request->bcategory_slug,
            'bcategory_meta_title'      => $request->bcategory_meta_title,
            'bcategory_meta_keyword'    => $request->bcategory_meta_keyword,
            'bcategory_meta_desc'       => $request->bcategory_meta_desc,
            'bcategory_status'          => $request->bcategory_status
        ]);

        $bcategory->save();
    }
}
