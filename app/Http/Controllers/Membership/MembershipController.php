<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership;
use Validator;
use Session;
use DataTables;

class MembershipController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:membership-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:membership-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:membership-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:membership-delete', ['only' => ['delete']]);
    }

    public function createSlug(Request $request)
    {
        $slug = str_slug($request->membership_title);
        $allSlugs = $this->checkSlug($slug);
        if (! $allSlugs->contains('membership_slug', $slug)){
            return response()->json(['slug' => $slug]);
        }
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('membership_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function checkSlug($slug)
    {
        return Membership::select("membership_slug")->where("membership_slug", 'like', $slug.'%')->get();
    }

    public function create()
    {
        return view("admin.membership.create");
    }

    public function insert(Request $request)
    {
        $lastOrder = Membership::orderBy("membership_order", "DESC")->first();
        //print_r($request->all()); die;
        $validator = Validator::make($request->all(),[
            "membership_title" => "required",
            "membership_slug" => "required",
            "membership_price" => 'required|not_in:0',
            "membership_duration" => "required",
            "membership_discount" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data                           = new Membership();
            $data->membership_title         = $request->membership_title;
            $data->membership_slug          = $request->membership_slug;
            $data->membership_price         = $request->membership_price;
            $data->membership_offer_price   = $request->membership_offer_price;
            $data->membership_duration      = $request->membership_duration;
            $data->membership_discount      = $request->membership_discount;
            $data->membership_desc          = $request->membership_desc;
            $data->membership_order         = (!empty($lastOrder)) ? $lastOrder->membership_order + 1 : 1;
            $data->membership_status        = '1';
            $data->created_at               = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Membership details added successfully');
                return ["redirect_url" => "membership-add"];
            }
        }
    }

    public function edit($id)
    {
        $membershipDetail = Membership::find($id);
        return view("admin.membership.edit", compact('membershipDetail'));
    }

    public function update(Request $request)
    {
        $data = Membership::find($request->membership_id);
        $validator = Validator::make($request->all(),[
            "membership_title" => "required",
            "membership_slug" => "required",
            "membership_price" => 'required|not_in:0',
            "membership_duration" => "required",
            "membership_discount" => "required",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data->membership_title         = $request->membership_title;
            $data->membership_slug          = $request->membership_slug;
            $data->membership_price         = $request->membership_price;
            $data->membership_offer_price   = $request->membership_offer_price;
            $data->membership_duration      = $request->membership_duration;
            $data->membership_discount      = $request->membership_discount;
            $data->membership_desc          = $request->membership_desc;
            $data->updated_at               = date('Y-m-d H:i:s');
            //$rs = Membership::where("id", $request->membership_id)->limit(1)->update($blogData);
            if ($data->save()) {
                Session::flash('successMsg', 'Membership details updated successfully');
                return ["redirect_url" => "membership-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.membership.list");
    }

    public function load_table(Request $request)
    {
        $membershipDetail = Membership::orderBy("membership_order")->get();
        return DataTables::of($membershipDetail)
            ->editColumn("checkbox", function ($membership){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$membership->membership_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($membership){
                return $membership->membership_title;
            })
            ->editColumn("date", function ($membership){
                return date('d-m-Y h:i:s A', strtotime($membership->created_at));
            })
            ->editColumn("status", function ($membership){
                if ($membership->membership_status == '1') {
                    return '<span id="td_status_'.$membership->membership_id.'"><a href="javascript:void(0)" onclick="change_status('.$membership->membership_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$membership->membership_id.'"><a href="javascript:void(0)" onclick="change_status('.$membership->membership_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("recommended", function ($membership){
                if ($membership->membership_recommended == '1') {
                    return '<span id="td_recommended_'.$membership->membership_id.'"><a href="javascript:void(0)" onclick="change_recommended_status('.$membership->membership_id.', 0)" ><div class="label label-table label-success">Yes</div></a></span>';
                } else {
                    return '<span id="td_recommended_'.$membership->membership_id.'"><a href="javascript:void(0)" onclick="change_recommended_status('.$membership->membership_id.', 1)" ><div class="label label-table label-danger">No</div></a></span>';
                }
            })
            ->editColumn("action", function ($membership){
                $action = "";
                if (auth()->user()->can('membership-edit')) {
                    $action.= '<a href="'.route("membership-edit", ['id' => $membership->membership_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('membership-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $membership->membership_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($membership) {
                    return $membership->membership_id;
                }
            ])
            ->rawColumns(["checkbox", "image", "status", "recommended", "action"])
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
            Membership::where("membership_id", $request->membership_id)->update(["membership_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function change_recommended_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Membership::where("membership_id", $request->membership_id)->update(["membership_recommended" => $request->status]);
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
            Membership::where("membership_id", $order["membership_id"])->update(["membership_order" => $order["position"]]);
        }
        echo 'Membership order changed successfully.';
    }

    public function delete(Request $request)
    {
        Membership::where("membership_id", $request->membership_id)->delete();
    }
}
