<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lounge;
use App\Models\Franchise;
use Validator;
use Session;
use DataTables;

class FranchiseController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:franchise-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:franchise-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:franchise-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:franchise-delete', ['only' => ['delete']]);
    }

    public function create()
    {
        $loungeDetail = Lounge::where('lounge_status', '1')->orderBy("lounge_order")->get();
        return view("admin.franchise.create", compact('loungeDetail'));
    }

    public function insert(Request $request)
    {
        $lastOrder = Franchise::orderBy("franchise_order", "DESC")->first();
        //print_r($request->all()); die;
        $validator = Validator::make($request->all(),[
            "franchise_company_name" => "required",
            "franchise_owner_name" => "required",
            "franchise_email" => "required|email",
            "franchise_mobile1" => "required|regex:/^\+?[0-9]{10,15}$/",
            "franchise_address" => "required",
            "franchise_pan" => "required",
            "franchise_gst" => "required",
            "franchise_bank_ac" => "required",
            "franchise_bank_name" => "required",
            "franchise_bank_ifsc" => "required",
            "franchise_bank_type" => "required|not_in:0",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data                                   = new Franchise();
            $data->lounge_id                        = isset($request->lounge_id) ? implode(',', $request->lounge_id) : NULL;
            $data->franchise_company_name           = $request->franchise_company_name;
            $data->franchise_owner_name             = $request->franchise_owner_name;
            $data->franchise_email                  = $request->franchise_email;
            $data->franchise_mobile1                = $request->franchise_mobile1;
            $data->franchise_mobile2                = $request->franchise_mobile2;
            $data->franchise_mobile3                = $request->franchise_mobile3;
            $data->franchise_address                = $request->franchise_address;
            $data->franchise_pan                    = $request->franchise_pan;
            $data->franchise_gst                    = $request->franchise_gst;
            $data->franchise_gst_percentage         = $request->franchise_gst_percentage;
            $data->franchise_bank_ac                = $request->franchise_bank_ac;
            $data->franchise_bank_name              = $request->franchise_bank_name;
            $data->franchise_bank_ifsc              = $request->franchise_bank_ifsc;
            $data->franchise_bank_type              = $request->franchise_bank_type;

            /*$data->franchise_unit                   = $request->franchise_unit;
            $data->franchise_category               = $request->franchise_category;
            $data->franchise_ownership              = $request->franchise_ownership;
            $data->franchise_property_size          = $request->franchise_property_size;
            $data->franchise_google_map             = $request->franchise_google_map;
            $data->franchise_franchise_fee          = $request->franchise_franchise_fee;
            $data->franchise_setup_cost             = $request->franchise_setup_cost;
            $data->franchise_renewal_fee            = $request->franchise_renewal_fee;
            $data->franchise_gst_invoice            = $request->franchise_gst_invoice;
            $data->franchise_discount               = $request->franchise_discount;
            $data->franchise_payment_mode           = $request->franchise_payment_mode;
            $data->franchise_renewal_fee            = $request->franchise_renewal_fee;
            $data->franchise_payment_status         = $request->franchise_payment_status;
            $data->franchise_payment_date           = date('Y-m-d', strtotime($request->franchise_payment_date));
            $data->franchise_weekly_off             = $request->franchise_weekly_off;
            $data->franchise_minimum_time           = $request->franchise_minimum_time;
            $data->franchise_rescheduling_before    = $request->franchise_rescheduling_before;
            $data->franchise_cancellation_charge    = $request->franchise_cancellation_charge;
            $data->franchise_refund_policy          = $request->franchise_refund_policy;
            $data->franchise_overtime_charge        = $request->franchise_overtime_charge;
            $data->franchise_charge_hour            = $request->franchise_charge_hour;
            $data->franchise_charge_second_hour     = $request->franchise_charge_second_hour;
            $data->franchise_overtime_charge        = $request->franchise_overtime_charge;
            $data->franchise_max_person             = $request->franchise_max_person;
            $data->franchise_washroom_attached      = $request->franchise_washroom_attached;
            $data->franchise_installation_date      = date('Y-m-d', strtotime($request->franchise_installation_date));
            $data->franchise_franchise_status       = $request->franchise_franchise_status;
            $data->franchise_agreement_start_date   = date('Y-m-d', strtotime($request->franchise_agreement_start_date));
            $data->franchise_agreement_end_date     = date('Y-m-d', strtotime($request->franchise_agreement_end_date));
            $data->franchise_validity_period        = $request->franchise_validity_period;
            $data->franchise_assigned_technician    = $request->franchise_assigned_technician;
            $data->franchise_photo_upload           = $request->franchise_photo_upload;
            $data->franchise_franchise_store        = $request->franchise_franchise_store;*/
            $data->franchise_order                  = (!empty($lastOrder)) ? $lastOrder->franchise_order + 1 : 1;
            $data->franchise_status                 = '1';
            $data->created_at                       = date('Y-m-d H:i:s');
            if ($data->save()) {
                $user                               = new User();
                $user->franchise_id                 = $data->franchise_id;
                $user->name                         = $request->franchise_owner_name;
                $user->email                        = $request->email;
                $user->password                     = bcrypt($request->password);
                $user->phone                        = $request->franchise_mobile1;
                $user->created_at                   = date('Y-m-d H:i:s');
                //print_r($lastOrder); exit();
                if ($user->save()) {
                    $user->assignRole('Franchise');
                }

                Session::flash('successMsg', 'Franchise details added successfully');
                return ["redirect_url" => "franchise-add"];
            }
        }
    }

    public function edit($id)
    {
        $franchiseDetail = Franchise::find($id);
        $alreadyAddedIds = explode(',', $franchiseDetail->lounge_id);
        $query = Lounge::where('lounge_status', '1');
        /*if (!empty($alreadyAddedIds)) {
            $query->whereNotIn('lounge_id', $alreadyAddedIds);
        }*/
        $loungeDetail = $query->get();
        return view("admin.franchise.edit", compact('loungeDetail', 'franchiseDetail', 'alreadyAddedIds'));
    }

    public function update(Request $request)
    {
        $data = Franchise::find($request->franchise_id);
        $validator = Validator::make($request->all(),[
            "franchise_company_name" => "required",
            "franchise_owner_name" => "required",
            "franchise_email" => "required|email",
            "franchise_mobile1" => "required|regex:/^\+?[0-9]{10,15}$/",
            "franchise_address" => "required",
            "franchise_pan" => "required",
            "franchise_gst" => "required",
            "franchise_bank_ac" => "required",
            "franchise_bank_name" => "required",
            "franchise_bank_ifsc" => "required",
            "franchise_bank_type" => "required|not_in:0",
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if (!empty($data->lounge_id)) {
                $data->lounge_id                    = $data->lounge_id . ',' .implode(',', $request->lounge_id);
            } else {
                $data->lounge_id                    = isset($request->lounge_id) ? implode(',', $request->lounge_id) : NULL;
            }
            $data->franchise_company_name           = $request->franchise_company_name;
            $data->franchise_owner_name             = $request->franchise_owner_name;
            $data->franchise_email                  = $request->franchise_email;
            $data->franchise_mobile1                = $request->franchise_mobile1;
            $data->franchise_mobile2                = $request->franchise_mobile2;
            $data->franchise_mobile3                = $request->franchise_mobile3;
            $data->franchise_address                = $request->franchise_address;
            $data->franchise_pan                    = $request->franchise_pan;
            $data->franchise_gst                    = $request->franchise_gst;
            $data->franchise_gst_percentage         = $request->franchise_gst_percentage;
            $data->franchise_bank_ac                = $request->franchise_bank_ac;
            $data->franchise_bank_name              = $request->franchise_bank_name;
            $data->franchise_bank_ifsc              = $request->franchise_bank_ifsc;
            $data->franchise_bank_type              = $request->franchise_bank_type;
            $data->updated_at                       = date('Y-m-d H:i:s');
            if ($data->save()) {
                Session::flash('successMsg', 'Franchise details updated successfully');
                return ["redirect_url" => "franchise-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.franchise.list");
    }

    public function load_table(Request $request)
    {
        $franchiseDetail = Franchise::orderBy("franchise_order")->get();
        return DataTables::of($franchiseDetail)
            ->editColumn("checkbox", function ($franchise){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$franchise->franchise_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("company_name", function ($franchise){
                return $franchise->franchise_company_name;
            })
            ->editColumn("title", function ($franchise){
                return $franchise->franchise_owner_name;
            })
            ->editColumn("email", function ($franchise){
                return $franchise->franchise_email;
            })
            ->editColumn("mobile", function ($franchise){
                return $franchise->franchise_mobile1;
            })
            ->editColumn("date", function ($franchise){
                return date('d-m-Y h:i A', strtotime($franchise->created_at));
            })
            ->editColumn("status", function ($franchise){
                if ($franchise->franchise_status == '1') {
                    return '<span id="td_status_'.$franchise->franchise_id.'"><a href="javascript:void(0)" onclick="change_status('.$franchise->franchise_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$franchise->franchise_id.'"><a href="javascript:void(0)" onclick="change_status('.$franchise->franchise_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($franchise){
                $action = "";
                if (auth()->user()->can('franchise-edit')) {
                    $action.= '<a href="'.route("franchise-edit", ['id' => $franchise->franchise_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('franchise-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $franchise->franchise_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($franchise) {
                    return $franchise->franchise_id;
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
            Franchise::where("franchise_id", $request->franchise_id)->update(["franchise_status" => $request->status]);
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
            Franchise::where("franchise_id", $order["franchise_id"])->update(["franchise_order" => $order["position"]]);
        }
        echo 'Franchise order changed successfully.';
    }

    public function delete(Request $request)
    {
        Franchise::where("franchise_id", $request->franchise_id)->delete();
    }

    public function delete_lounge(Request $request)
    {
        try {
            $franchiseDetail = Franchise::find($request->franchise_id);
            if (!empty($franchiseDetail->lounge_id)) {
                $array = explode(',', $franchiseDetail->lounge_id);
                $key = array_search($request->lounge_id, $array); // Find the key/index

                if ($key !== false) {
                    unset($array[$key]); // Remove the item
                }

                // Reindex if necessary
                $array = array_values($array);
                Franchise::where('franchise_id', $request->franchise_id)->update([
                    'lounge_id' => (count($array) > 0) ? implode(',', $array) : NULL
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Catch error delete_lounge: ' . $e->getMessage());
        }
    }
}