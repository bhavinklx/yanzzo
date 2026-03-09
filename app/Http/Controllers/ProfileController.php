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
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Product;

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
        date_default_timezone_set('Asia/Kolkata');
        $settingDetail = Setting::select('setting_name', 'setting_value')->get()->toArray();
        foreach ($settingDetail as $setting) {
            if (!defined($setting['setting_name'])) {
                define($setting['setting_name'], $setting['setting_value']);
            }
        }
    }

    public function myAccount(Request $request) {
        try {
            $pagesDetail = Pages::select('page_id', 'page_title', 'page_meta_title', 'page_meta_keyword', 'page_meta_desc')->where('page_id', 10)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $customerDetail = Customer::select('customer_id', 'customer_name', 'customer_email', 'customer_mobile', 'customer_image')->where('customer_id', Session::get('customer_id'))->first();
            return view('myaccount')->with(['pagesDetail' => $pagesDetail, 'customerDetail' => $customerDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error myaccount: ' . $e->getMessage());
        }
    }

    public function myAccountUpdate(Request $request) {
        try {
            $data = Customer::find(Session::get('customer_id'));
            $validator = Validator::make($request->all(),[
                "customer_name" => "required",
                'customer_email'  => 'required|email|max:255',
                'customer_mobile' => 'required|digits_between:7,15'
            ]);

            if ($validator->fails()) {
                return ['status' => 'validation-error', 'data' => $validator->errors()];
            } else {
                if ($request->hasfile('customer_image') && $request->file('customer_image')->isValid()) {
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
                if ($data->save()) {
                    Session::put('customer_name', $request->customer_name);
                    Session::flash('successMsg', 'Customer details updated successfully');
                    return ["redirect_url" => "my-account"];
                } else {
                    Session::flash('failedMsg', 'Customer details updated failed');
                }
            }
        } catch (\Exception $e) {
            Log::error('Catch error myaccountupdate: ' . $e->getMessage());
        }
    }

    public function myListing(Request $request) {
        try {
            $pagesDetail = Pages::select('page_id', 'page_title', 'page_meta_title', 'page_meta_keyword', 'page_meta_desc')->where('page_id', 11)->first(); // Use appropriate page_id
            if(!$pagesDetail){
                // If 11 doesn't exist, use default or create dummy
                $pagesDetail = (object)[
                    'page_title' => 'My Listings',
                    'page_meta_title' => 'My Listings',
                    'page_meta_keyword' => 'My Listings',
                    'page_meta_desc' => 'My Listings'
                ];
            }
            $customerDetail = Customer::where('customer_id', Session::get('customer_id'))->first();
            $productDetail = Product::with(['pimages', 'city'])
                ->where('customer_id', Session::get('customer_id'))
                ->orderBy('product_id', 'DESC')
                ->get();
            
            return view('mylisting', compact('pagesDetail', 'customerDetail', 'productDetail'));
        } catch (\Exception $e) {
            Log::error('Catch error mylisting: ' . $e->getMessage());
            return redirect()->route('/');
        }
    }

    public function markAsSold(Request $request) {
        try {
            $productId = $request->product_id;
            $customerId = Session::get('customer_id');
            
            $product = Product::where('product_id', $productId)
                ->where('customer_id', $customerId)
                ->first();
                
            if($product) {
                $product->product_is_sold = '1';
                $product->save();
                return response()->json(['status' => 'success', 'message' => 'Product marked as sold.']);
            }
            
            return response()->json(['status' => 'error', 'message' => 'Product not found or unauthorized.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}