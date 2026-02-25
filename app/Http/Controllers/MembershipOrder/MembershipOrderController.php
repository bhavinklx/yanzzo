<?php

namespace App\Http\Controllers\MembershipOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MembershipOrder;
use App\Models\Payment;
use Validator;
use Session;
use DataTables;
//use App\Exports\ContactExport;
use Maatwebsite\Excel\Facades\Excel;

class MembershipOrderController extends Controller
{
    /*function __construct()
    {
        $this->middleware('permission:membershiporder-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:membershiporder-delete', ['only' => ['delete']]);
    }*/

    public function view(Request $request)
    {
        return view("admin.membershiporder.list")->with('status', $request->status);
    }

    public function load_table(Request $request)
    {
        if($request->status == "pending"){
            $orderDetail = MembershipOrder::where('msorder_status', '0')->orderBy("msorder_id", "DESC")->get();
        } else if($request->status == "completed"){
            $orderDetail = MembershipOrder::where('msorder_status', '1')->orderBy("msorder_id", "DESC")->get();
        } else if($request->status == "cancel"){
            $orderDetail = MembershipOrder::where('msorder_status', '2')->orderBy("msorder_id", "DESC")->get();
        }
        return DataTables::of($orderDetail)
            ->editColumn("checkbox", function ($order){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$order->msorder_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("order", function ($order){
                return $order->msorder_unique_id;
            })
            ->editColumn("billing", function ($order){
                return '<strong>'.$order->customer_name.'</strong><br>
                        <strong>Phone: </strong>'.$order->customer_mobile;
            })
            ->editColumn("membership", function ($order){
                $html = '<strong>'.$order->membership_title.'</strong><br>';
                if ($order->msorder_start_date != NULL && $order->msorder_end_date != NULL) {
                    $html .= '<strong>Start Date: </strong><span class="bg-warning">'.date('d-m-Y', strtotime($order->msorder_start_date)).'</span><br>';
                    $html .= '<strong>End Date: </strong><span class="bg-warning">'.date('d-m-Y', strtotime($order->msorder_end_date)).'</span>';
                }
                return $html;
            })
            ->editColumn("payment", function ($order){
                if($order->payment_id > 0){
                    $paymentDetail = Payment::where("payment_id", $order->payment_id)->first();
                }
                $html = '<strong>Total Amount: </strong><i class="fa fa-inr" aria-hidden="true"></i> '.($order->msorder_paid_price + $order->discount_price).'<br>';
                $html .= '<strong>Paid Amount: </strong><i class="fa fa-inr" aria-hidden="true"></i> '.$order->msorder_paid_price.'<br>';
                if ($order->discount_code != ""){
                    $html .= '<strong>Discount Amount: </strong><i class="fa fa-inr" aria-hidden="true"></i> '.$order->discount_price.'<br><strong>Discount Code: </strong> '.$order->discount_code.'<br>';
                }
                $html .= "<strong>Order Date: </strong>".date('d-m-Y',strtotime($order->created_at))." (".date('h:i A',strtotime($order->created_at)).")<br><strong>Payment Method: </strong>".$order->msorder_type."<br>";

                if($order->msorder_type == "Razorpay" && $order->payment_id > 0 && !empty($paymentDetail)){
                    $html .= '<strong>Payment Mode: </strong>'.$paymentDetail->PAYMENTMODE.'<br><strong>Trnsaction#: </strong>'.$paymentDetail->TXNID.'<br>';
                }
                return $html;
            })
            ->editColumn("status", function ($order){
                if ($order->msorder_status == "0"){
                    return '<span class="badge badge-warning badge-pill">Pending</span>';
                } else if($order->msorder_status == "1"){
                    return '<span class="badge badge-success badge-pill">Completed</span>';
                } else if($order->msorder_status == "2"){
                    return '<span class="badge badge-danger badge-pill">Cancel</span>';
                } else if($order->msorder_status == "3"){
                    return '<span class="badge badge-danger badge-pill">Not Confirmed</span>';
                } else if($order->msorder_status == "4"){
                    return '<span class="badge badge-success badge-pill">Confirmed</span>';
                }
            })
            ->editColumn("action", function ($order){
                $action = "";
                if (/*auth()->user()->can('membershiporder-delete') &&*/ $order->msorder_status == '0') {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $order->msorder_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($order) {
                    return $order->customer_id;
                }
            ])
            ->rawColumns(["checkbox", "billing", "membership", "payment", "status", "action"])
            ->make(true);
    }

    public function delete(Request $request)
    {
        Payment::where("msorder_id", $request->msorder_id)->delete();
        MembershipOrder::where("msorder_id", $request->msorder_id)->delete();
    }
}
