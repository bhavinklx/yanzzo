<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Pages;
use App\Models\Lounge;
use App\Models\Customer;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Payment;
use App\Models\MembershipOrder;
use App\Models\Setting;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Razorpay\Api\Api;
use Carbon\Carbon;
use DateTime;

class ProfileController extends Controller
{
    function __construct()
    {
        // Check if 'customer' exists in session and is not
        $this->middleware('checkCustomerLogin');

        date_default_timezone_set('Asia/Kolkata');
        $settingDetail = Setting::get()->toArray();
        for ($s = 0; $s < count($settingDetail); $s++) {
            define($settingDetail[$s]['setting_name'], $settingDetail[$s]['setting_value']);
        }
    }

    public function myAccount(Request $request) {
        try {
            $pagesDetail = Pages::where('page_id', 16)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $membershipNameArray = [];
            $membershipDetail = Membership::get()->toArray();
            for ($m=0; $m < count($membershipDetail); $m++) {
                $membershipNameArray[$membershipDetail[$m]['membership_id']] = $membershipDetail[$m]['membership_title'];
            }
            $customerDetail = Customer::where('customer_id', Session::get('customer_id'))->first();
            $morderDetail = MembershipOrder::where('customer_id', Session::get('customer_id'))->where('msorder_status', '1')->where('msorder_end_date', '>=', date('Y-m-d'))->orderBy("msorder_id", "DESC")->first();
            return view('myaccount')->with(['pagesDetail' => $pagesDetail, 'customerDetail' => $customerDetail, 'membershipNameArray' => $membershipNameArray, 'morderDetail' => $morderDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error myaccount: ' . $e->getMessage());
        }
    }

    public function myAccountUpdate(Request $request) {
        try {
            //echo 'Hello'; exit();
            $data = Customer::find(Session::get('customer_id'));
            //$log = DB::getQueryLog();
            //dd($log);
            $validator = Validator::make($request->all(),[
                "customer_name" => "required",
                'customer_email'  => 'required|email|max:255',
                'customer_mobile' => 'required|digits_between:7,15'
            ]);

            if ($validator->fails()) {
                return ['status' => 'validation-error', 'data' => $validator->errors()];
            } else {
                if ($request->hasfile('customer_image') && $request->file('customer_image')->isValid()) {
                    //echo 'Hello'; exit();
                    if ($data->customer_image!='' && file_exists(public_path('/uploads/customer/'.$data->customer_image)))
                    {
                        @unlink(public_path('/uploads/customer/'.$data->customer_image));
                    }
                    $image = $request->file('customer_image');
                    $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('/uploads/customer/'), $filename);
                } else {
                    $filename = $request->old_image;
                }

                $data->customer_name        = $request->customer_name;
                $data->customer_email       = $request->customer_email;
                $data->customer_mobile      = $request->customer_mobile;
                $data->customer_image       = $filename;
                $data->updated_at           = date('Y-m-d H:i:s');
                //print_r($lastOrder); exit();
                if ($data->save()) {
                    Session::flash('successMsg', 'Customer details updated successfully');
                    return ["redirect_url" => "my-account"];
                } else {

                }
            }
        } catch (\Exception $e) {
            Log::error('Catch error myaccountupdate: ' . $e->getMessage());
        }
    }

    public function myBooking(Request $request) {
        try {
            $pagesDetail = Pages::where('page_id', 17)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $orderDetail = Order::where('customer_id', Session::get('customer_id'))->orderBy("created_at", "DESC")->get();
            //echo '<pre>'; print_r($orderDetail); die;
            return view('mybooking')->with(['pagesDetail' => $pagesDetail, 'orderDetail' => $orderDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error mybooking: ' . $e->getMessage());
        }
    }
}