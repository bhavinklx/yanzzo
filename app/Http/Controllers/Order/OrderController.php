<?php

namespace App\Http\Controllers\Order;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\Lounge;
use App\Models\LoungeTime;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\CartLog;
use App\Models\Order;
use App\Models\Payment;
use Validator;
use Session;
use DataTables;
//use App\Exports\ContactExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /*function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:customer-delete', ['only' => ['delete']]);
    }*/

    public function view(Request $request)
    {
        return view("admin.order.list")->with('status', $request->status);
    }

    public function load_table(Request $request)
    {
        // Logged in user
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        if ($role == 'Super Admin') {
            if($request->status == "pending"){
                $orderDetail = Order::orderBy("order_id", "DESC")->get();
            } else if($request->status == "completed"){
                $orderDetail = Order::where('order_status', '1')->where('order_ostatus', 'completed')->orderBy("order_id", "DESC")->get();
            } else if($request->status == "cancel"){
                $orderDetail = Order::where('order_ostatus', 'cancelled')->orderBy("order_id", "DESC")->get();
            }
        } else {
            $franchise = Franchise::where('franchise_id', $user->franchise_id)->first();
            $loungeId = [];
            if ($franchise && $franchise->lounge_id) {
                $loungeId = explode(',', $franchise->lounge_id); // convert CSV to array
            }
            if($request->status == "pending"){
                $orderDetail = Order::whereIn('lounge_id', $loungeId)->orderBy("order_id", "DESC")->get();
            } else if($request->status == "completed"){
                $orderDetail = Order::whereIn('lounge_id', $loungeId)->where('order_status', '1')->where('order_ostatus', 'completed')->orderBy("order_id", "DESC")->get();
            } else if($request->status == "cancel"){
                $orderDetail = Order::whereIn('lounge_id', $loungeId)->where('order_ostatus', 'cancelled')->orderBy("order_id", "DESC")->get();
            }
        }

        return DataTables::of($orderDetail)
            ->editColumn("checkbox", function ($order){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$order->order_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("order", function ($order){
                return $order->order_unique_id;
            })
            ->editColumn("billing", function ($order){
                return '<strong>'.$order->customer_name.'</strong><br>
                        <strong>Phone: </strong>'.$order->customer_mobile.'<br>
                        <strong>City: </strong>'.$order->order_city;
            })
            ->editColumn("booking", function ($order){
                $cartDetail = Cart::where('cart_id', $order->cart_id)->first();
                $cartlogDetail = CartLog::where('cart_id', $order->cart_id)->first();
                $html = "";
                if($order->order_status == '1' && $cartDetail->cart_reschedule == '1') {
                    if ($cartDetail) {
                        $html .= '<strike>' .date('d, M Y', strtotime($cartlogDetail->clog_start_date)) . '<br>' . str_replace(',', ', ', $cartlogDetail->clog_duration) . '<br><strong>' . $cartDetail->cart_adults . ' Adults, ' . $cartDetail->cart_children . ' Children</strike></strong><br><br>';
                        $html .= date('d, M Y', strtotime($cartDetail->cart_start_date)) . '<br>' . str_replace(',', ', ', $cartDetail->cart_duration) . '<br><strong>' . $cartDetail->cart_adults . ' Adults, ' . $cartDetail->cart_children . ' Children</strong>';
                    }
                } else {
                    if ($cartDetail) {
                        $html = date('d, M Y', strtotime($cartDetail->cart_start_date)) . '<br>' . str_replace(',', ', ', $cartDetail->cart_duration) . '<br><strong>' . $cartDetail->cart_adults . ' Adults, ' . $cartDetail->cart_children . ' Children</strong>';
                    }
                }
                return $html;
            })
            ->editColumn("payment", function ($order){
                if($order->payment_id > 0){
                    $paymentDetail = Payment::where("payment_id", $order->payment_id)->first();
                }
                $html = '<strong>Total Amount: </strong><i class="fa fa-inr" aria-hidden="true"></i> '.($order->order_paid_price + $order->discount_price + $order->membership_discount).'<br>';
                $html .= '<strong>Paid Amount: </strong><i class="fa fa-inr" aria-hidden="true"></i> '.$order->order_paid_price.'<br>';
                if ($order->discount_code != ""){
                    $html .= '<strong>Discount Amount: </strong><i class="fa fa-inr" aria-hidden="true"></i> '.$order->discount_price.' ('.$order->discount_code.')<br>';
                }
                if ($order->membership_discount != ""){
                    $html .= '<strong>Membership Discount: </strong><i class="fa fa-inr" aria-hidden="true"></i> '.$order->membership_discount.'<br>';
                }
                $html .= "<strong>Order Date: </strong>".date('d-m-Y',strtotime($order->created_at))." (".date('h:i A',strtotime($order->created_at)).")<br><strong>Payment Method: </strong>".$order->order_type."<br>";

                if($order->order_type == "Razorpay" && $order->payment_id > 0 && !empty($paymentDetail)){
                    $html .= '<strong>Payment Mode: </strong>'.$paymentDetail->PAYMENTMODE.'<br><strong>Trnsaction#: </strong>'.$paymentDetail->TXNID.'<br>';
                }
                return $html;
            })
            ->editColumn("status", function ($order){
                if ($order->order_status == "0"){
                    return '<span class="badge badge-warning badge-pill">Pending</span>';
                } else if($order->order_status == "1"){
                    return '<span class="badge badge-success badge-pill">Completed</span>';
                } else if($order->order_status == "2"){
                    return '<span class="badge badge-danger badge-pill">Cancel</span>';
                } else if($order->order_status == "3"){
                    return '<span class="badge badge-danger badge-pill">Not Confirmed</span>';
                } else if($order->order_status == "4"){
                    return '<span class="badge badge-success badge-pill">Confirmed</span>';
                }
            })
            ->editColumn("ostatus", function ($order){
                $pending = $initiate = $shipped = $completed = $cancelled = $refunded = $orderStatus = "";
                if ($order->order_ostatus == "pending") {
                    $pending    = "selected='selected'";
                } else if ($order->order_ostatus == "initiate") {
                    $initiate   = "selected='selected'";
                } else if ($order->order_ostatus == "completed") {
                    $completed  = "selected='selected'";
                } else if ($order->order_ostatus == "cancelled") {
                    $cancelled  = "selected='selected'";
                } else if ($order->order_ostatus == "refunded") {
                    $refunded   = "selected='selected'";
                }
                if ($order->order_ostatus == "pending") {
                    $orderStatus = '<select class="form-control p-0" onchange="return change_status('.$order->order_id.', this.value)">
                                        <option value="0">Select Status</option>
                                        <option value="pending" ' . $pending . '>Pending</option>
                                        <!--<option value="initiate" ' . $initiate . '>Initiate</option>-->
                                        <option value="completed" ' . $completed . '>Completed</option>
                                        <option value="cancelled" ' . $cancelled . '>Cancelled</option>
                                        <option value="refunded" ' . $refunded . '>Refunded</option>
                                    </select>';
                } else if ($order->order_ostatus == "initiate") {
                    $orderStatus = '<select class="form-control p-0" onchange="return change_status('.$order->order_id.', this.value)">
                                        <option value="0">Select Status</option>
                                        <!--<option value="initiate" ' . $initiate . '>Initiate</option>-->
                                        <option value="completed" ' . $completed . '>Completed</option>
                                        <option value="cancelled" ' . $cancelled . '>Cancelled</option>
                                        <option value="refunded" ' . $refunded . '>Refunded</option>
                                    </select>';
                } else if ($order->order_ostatus == "completed") {
                    $orderStatus = '<select class="form-control p-0">
                                        <option value="0">Select Status</option>
                                        <option value="completed" ' . $completed . '>Completed</option>
                                   </select>';
                } else if ($order->order_ostatus == "cancelled") {
                    $orderStatus = '<select class="form-control p-0" onchange="return change_status('.$order->order_id.', this.value)">
                                        <option value="0">Select Status</option>
                                        <option value="cancelled" ' . $cancelled . '>Cancelled</option>
                                        <option value="refunded" ' . $refunded . '>Refunded</option>
                                   </select>';
                } else if ($order->order_ostatus == "refunded") {
                    $orderStatus = '<select class="form-control p-0">
                                        <option value="0">Select Status</option>
                                        <option value="refunded" ' . $refunded . '>Refunded</option>
                                   </select>';
                }
                return $orderStatus;
            })
            ->editColumn("action", function ($order){
                $action = '<a href="'.url('/admin/order-list/vieworder/' .$order->order_id ).'" data-toggle="tooltip" title="View Order" class="img-responsive model_img" > <i class="fa fa-eye text-info"></i> </a>';
                /*if (auth()->user()->can('contact-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $order->order_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }*/
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
            ->rawColumns(["checkbox", "billing", "booking", "payment", "status", "ostatus", "action"])
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
            if ($request->status == 'initiate') {
                Order::where("order_id", $request->order_id)->update([
                    'order_ostatus' => $request->status,
                    'order_initiate_date' => date('Y-m-d H:i:s', time())
                ]);
            } else if ($request->status == 'completed') {
                Order::where("order_id", $request->order_id)->update([
                    'order_ostatus' => $request->status
                ]);
            }
            echo 'Order Status change successfully';
        }
    }

    public function change_cancelled_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Order::where("order_id", $request->order_id)->update([
                'order_ostatus' => $request->status,
                'order_cancel_date' => date('Y-m-d H:i:s', time()),
                'order_cancel_reason' => $request->cancel_reason
            ]);
            echo 'Order Status change successfully';
        }
    }

    public function change_refunded_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Order::where("order_id", $request->order_id)->update([
                'order_ostatus' => $request->status,
                'order_refund_date' => date('Y-m-d H:i:s', time()),
                'order_refund_reason' => $request->refund_reason
            ]);
            echo 'Order Status change successfully';
        }
    }

    public function view_order(Request $request)
    {
        $orderDetail = Order::firstWhere('order_id', $request->id);
        $paymetDetail = Payment::firstWhere('payment_id', $orderDetail->payment_id);
        $loungeDetail = Lounge::firstWhere('lounge_id', $orderDetail->lounge_id);
        $cartDetail = Cart::where('cart_id', $orderDetail->cart_id)->first();
        $cartlogDetail = CartLog::where('cart_id', $orderDetail->cart_id)->first();
        return view("admin.order.view", compact('orderDetail', 'paymetDetail', 'loungeDetail', 'cartDetail', 'cartlogDetail'));
    }

    public function delete(Request $request)
    {
        $orderDetail = Order::where("order_id", $request->order_id)->first();

        Cart::where("cart_id", $orderDetail->cart_id)->where("cart_status", '1')->delete();
        Payment::where("order_id", $orderDetail->payment_id)->delete();
        Order::where("order_id", $request->order_id)->delete();
    }
}
