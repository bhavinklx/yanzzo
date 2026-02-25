<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;
use Session;
use DataTables;

class CategoryController extends Controller
{

    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:category-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:category-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['delete']]);
    }

    public function createSlug(Request $request)
    {
        $slug = str_slug($request->category_title);
        $allSlugs = $this->checkSlug($slug);
        if (!$allSlugs->contains('category_slug', $slug)) {
            return response()->json(['slug' => $slug]);
        }
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('category_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function checkSlug($slug)
    {
        return Category::select("category_slug")->where("category_slug", 'like', $slug . '%')->get();
    }

    public function create()
    {
        $parentCategory = Category::where(["category_status" => "1", "category_parent"=>"0"])->get();
        return view("admin.category.create")->with(['parentCategory' => $parentCategory]);
    }

    public function insert(Request $request)
    {
        $lastOrder = Category::orderBy('category_order', 'DESC')->first();
        $validator = Validator::make($request->all(), [
            "category_title" => "required",
            "category_slug" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasfile('category_image')) {
                $image = $request->file('category_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/category/'), $filename);
            } else {
                $filename = "";
            }

            if ($request->hasfile('category_icon')) {
                $icon = $request->file('category_icon');
                $iconname = "ICON-" . time() . '.' . $icon->getClientOriginalExtension();
                $icon->move(public_path('/uploads/category/'), $filename);
            } else {
                $iconname = "";
            }

            $data                           = new Category();
            $data->category_title           = $request->category_title;
            $data->category_slug            = $request->category_slug;
            $data->category_image           = $filename;
            $data->category_icon            = $iconname;
            $data->category_short_desc      = addslashes($request->category_short_desc);
            $data->category_desc            = $request->category_desc;
            $data->category_meta_title      = $request->category_meta_title;
            $data->category_meta_keyword    = $request->category_meta_keyword;
            $data->category_meta_desc       = $request->category_meta_desc;
            $data->category_order           = (!empty($lastOrder)) ? $lastOrder->category_order + 1 : 1;
            $data->category_status          = $request->category_status;
            $data->category_hstatus         = '0';
            $data->created_at               = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Category details added successfully');
                return ["redirect_url" => "category-add"];
            }
        }
    }

    public function edit($id)
    {
        $categoryDetail = Category::find($id);
        $parentCategory = Category::where(["category_status" => "1", "category_parent"=>"0"])->get();
        return view("admin.category.edit")->with(['parentCategory' => $parentCategory, 'categoryDetail' => $categoryDetail]);
    }

    public function update(Request $request)
    {
        $data = Category::find($request->category_id);
        $validator = Validator::make($request->all(), [
            "category_title" => "required",
            "category_slug" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasfile('category_image')) {
                //echo 'Hello'; exit();
                if ($data->category_image!='' && file_exists(public_path('/uploads/category/'.$data->category_image))) {
                    @unlink(public_path('/uploads/category/'.$data->category_image));
                }
                $image = $request->file('category_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path().'/uploads/category/', $filename);
            } else {
                $filename = $request->old_image;
            }

            if ($request->hasfile('category_icon')) {
                //echo 'Hello'; exit();
                if ($data->category_icon!='' && file_exists(public_path('/uploads/category/'.$data->category_icon))) {
                    @unlink(public_path('/uploads/category/'.$data->category_icon));
                }
                $icon = $request->file('category_icon');
                $iconname = "ICON-" . time() . '.' . $icon->getClientOriginalExtension();
                $icon->move(public_path().'/uploads/category/', $iconname);
            } else {
                $iconname = $request->old_icon;
            }

            $data->category_title           = $request->category_title;
            $data->category_slug            = $request->category_slug;
            $data->category_image           = $filename;
            $data->category_icon            = $iconname;
            $data->category_short_desc      = addslashes($request->category_short_desc);
            $data->category_desc            = $request->category_desc;
            $data->category_meta_title      = $request->category_meta_title;
            $data->category_meta_keyword    = $request->category_meta_keyword;
            $data->category_meta_desc       = $request->category_meta_desc;
            $data->category_status          = $request->category_status;
            $data->updated_at               = date('Y-m-d H:i:s');

            //dd($rs); exit();
            if ($data->save()) {
                Session::flash('successMsg', 'Category details updated successfully');
                return ["redirect_url" => "bcategory-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.category.list");
    }

    public function load_table(Request $request)
    {
        $categoryDetail = Category::orderBy("category_order")->get();
        return DataTables::of($categoryDetail)
            ->editColumn("checkbox", function ($category) {
                return '<input type="checkbox" name="check[]" id="check[]" value="' . $category->category_id . '" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($category) {
                return $category->category_title;
            })
            ->editColumn("image", function ($category){
                if($category->category_image!='' && file_exists(public_path('/uploads/category/'.$category->category_image))){
                    return "<img src='".asset('/uploads/category/'.$category->category_image)."' width='100px'>";
                } else {
                    return;
                }
            })
            ->editColumn("date", function ($category) {
                return date('d-m-Y h:i A', strtotime($category->created_at));
            })
            ->editColumn("status", function ($category) {
                if ($category->category_status == '1') {
                    return '<span id="td_status_' . $category->category_id . '"><a href="javascript:void(0)" onclick="change_status(' . $category->category_id . ', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_' . $category->category_id . '"><a href="javascript:void(0)" onclick="change_status(' . $category->category_id . ', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("home_status", function ($category) {
                if ($category->category_hstatus == '1') {
                    return '<span id="td_home_status_' . $category->category_id . '"><a href="javascript:void(0)" onclick="change_home_status(' . $category->category_id . ', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_home_status_' . $category->category_id . '"><a href="javascript:void(0)" onclick="change_home_status(' . $category->category_id . ', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($category) {
                $action = "";
                if (auth()->user()->can('category-edit'))
                {
                    $action.= '<a href="' . route("category-edit", ['id' => $category->category_id]) . '" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('category-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $category->category_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($category) {
                    return $category->category_id;
                }
            ])
            ->rawColumns(["checkbox","image", "status", "home_status", "action"])
            ->make(true);
    }

    public function change_status(Request $request)
    {
        if (!$request->ajax()) {
            exit('No direct script access allowed');
        }
        if (!empty($request->all())) {
            Category::where("category_id", $request->category_id)->update(['category_status' => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0) {
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
            Category::where("category_id", $request->category_id)->update(['category_hstatus' => $request->status]);
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
            Category::where("category_id", $order["category_id"])->update(["category_order" => $order["position"]]);
        }
        echo 'Category order changed successfully.';
    }

    public function delete(Request $request)
    {
        $image = Category::find($request->category_id);
        if ($image->category_image!='' && file_exists(public_path('/uploads/category/'.$image->category_image))) {
            @unlink(public_path('/uploads/category/'.$image->category_image));
        }
        if ($image->category_icon!='' && file_exists(public_path('/uploads/category/'.$image->category_icon))) {
            @unlink(public_path('/uploads/category/'.$image->category_icon));
        }
        Category::where("category_id", $request->category_id)->delete();
    }
}