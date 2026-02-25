<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Http\Controllers\HomeController;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AjaxController extends Controller {

    public function validate_email(Request $request) {
        try {
            if($request->email=='') {
                echo 'Please enter your email';
                exit;
            } else {
                /*if(!validateEmail($request->email)){
                    echo "Invalid email format";
                } else*/ if(Customer::where('customer_email', $request->email)->count() > 0) {
                    echo "Email id already exist";
                    exit;
                } else if(Customer::where('customer_email', $request->email)->count() == 0) {
                    exit;
                }
            }
        } catch (\Exception $e) {
            Log::error('Catch error validate_email: ' . $e->getMessage());
        }
    }

    public function validate_mobile(Request $request) {
        try {
            if (empty($request->mobile) || strlen($request->mobile) < 10) {
                echo "Mobile number must be 10 digits.";
                exit;
            } else {
                if(Customer::where('customer_mobile', $request->mobile)->count() > 0) {
                    echo "Mobile Number already exist";
                    exit;
                } else if(Customer::where('customer_mobile', $request->mobile)->count() == 0) {
                    exit;
                }
            }
        } catch (\Exception $e) {
            Log::error('Catch error validate_mobile: ' . $e->getMessage());
        }
    }

    public function validate_signup(Request $request) {
        try {
            $otp                        = mt_rand(100000, 999999);
            $lastOrder                  = Customer::orderBy('customer_id', 'DESC')->first();
            $userArray[]                = [
                'customer_name'         => htmlspecialchars(addslashes(strip_tags(ucwords(strtolower($request->user_name))))),
                'customer_email'        => addslashes(strip_tags(strtolower($_POST["user_email"]))),
                'customer_mobile'       => addslashes(strip_tags($_POST["user_mobile"])),
                //'customer_password'   => md5(trim($_POST["user_password"])),
                'customer_created_ip'   => $_SERVER["REMOTE_ADDR"],
                'customer_status'       => "0",
                'customer_order'        => (!empty($lastOrder)) ? $lastOrder->customer_order + 1 : 1,
                'customer_otp'          => $otp,
                'created_at'            => date('Y-m-d H:i:s', time())
            ];

            if(Customer::where('customer_mobile', $request->mobile)->count() > 0){
                return response()->json(["msg" => "Mobile Number already exist"]);
            } else {
                if(Customer::insert($userArray)){
                    $last               = substr($_POST["user_mobile"],6,4);
                    /*$email            = explode('@', $_POST["user_email"]);
                    $last               = $email[1];*/
                    $otp_form           =
                        '<div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">OTP Verify</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="otp_verify_form" name="otp_verify_form" onsubmit="return false;">
                                <div id="optFrm">
                                    <!--<h4>Sign up our newsletter and save 25% off for the next purchase!</h4>-->
                                    <!--<p>Subscribe to our newsletters and don’t miss new arrivals, the latest fashion updates and our promotions.</p>-->
                                    <input type="hidden" name="verify_mobile" id="verify_mobile" value="'.base64_encode($_POST['user_mobile']).'">
                                    <p style="margin-bottom: 0px;">OTP has been sent to ******<span id="mobile_id">'.$last.'</span></p>
                                    <div class="mb-3">
                                        <label>OTP</label>
                                        <input class="form-control mb-0" type="password" id="user_otp" name="user_otp" onkeypress="return isNumberKey(event);" maxlength="6">
                                        <span class="help-block" id="uotp_msg"></span>
                                    </div>
                                    <button class="btn btn-primary w-100" type="button" id="verifyOtpBtn" name="verifyOtpBtn" onclick="return verify_otp();">Verify</button>
                                </div>
                                <div class="text-center" id="otpThankMsg" style="margin-top: 15px;"></div>
                            </form>
                        </div>
                        <div class="modal-footer" id="resend_otp" style="display: unset !important;">
                            <p style="text-align: center">Didn\'t receive code? <a href="javascript: void (0)" class="text-blue" onclick="resend_otp();">Resend</a></p>
                        </div>';

                    $fromEmail          = 'no-reply@yaarioke.com';
                    $fromName           = 'YAARIOKE';
                    $subjectUser        = "OTP for YAARIOKE";
                    $Message            = "Your one time password(OTP) code for YAARIOKE is : ".$otp;

                    $homeController     = new HomeController();
                    //mail sent to user
                    //$homeController->sendMail($fromEmail, $_POST['user_email'], $fromName, htmlspecialchars(addslashes(strip_tags(ucwords(strtolower($request->customer_name))))), $subjectUser, $Message);

                    // SEND SMS to User
                    $Message            = $otp . " is your one-time verification code for yaarioke.com. YARIOK";
                    $homeController->sendSMS($_POST['user_mobile'], $Message);
                    return response()->json(["message" => "success", "email" => $_POST["user_email"], "otp_form" => $otp_form]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Catch error validate_signup: ' . $e->getMessage());
        }
    }

    public function resend_otp(Request $request) {
        try {
            $customerDetail             = Customer::where('customer_mobile', base64_decode($request->mobile))->first();

            $fromEmail                  = 'no-reply@yaarioke.com';
            $fromName                   = 'YAARIOKE';
            $subjectUser                = "OTP for YAARIOKE";
            $Message                    = "Your one time password(OTP) code for YAARIOKE is : " . $customerDetail->customer_otp;

            $homeController             = new HomeController();
            //mail sent to user
            //$homeController->sendMail($fromEmail, $customerDetail->customer_email, $fromName, $customerDetail->customer_name, $subjectUser, $Message);

            // SEND SMS to User
            $Message                    = $customerDetail->customer_otp . " is your one-time verification code for yaarioke.com. YARIOK";
            $homeController->sendSMS($customerDetail->customer_mobile, $Message);
            return response()->json(["message" => "success"]);
        } catch (\Exception $e) {
            Log::error('Catch error resend_otp: ' . $e->getMessage());
        }
    }

    public function verify_otp(Request $request) {
        $verifyMobile   = base64_decode($request->verify_mobile);
        $customerDetail = Customer::where(function ($query) use ($verifyMobile){
            $query->where('customer_email', $verifyMobile)->orwhere('customer_mobile', $verifyMobile);
        })->where('customer_otp', $request->user_otp)->first();
        if (!empty($customerDetail)) {
            if ($customerDetail && $customerDetail->customer_status == "0") {
                Customer::where('customer_id', $customerDetail->customer_id)->update([
                    'customer_status'   => '1'
                ]);

                $homeController         = new HomeController();
                $url                    = "https://apiv1.anantya.ai/api/Campaign/SendSingleTemplateMessage?templateId=40";
                $postFields             = [
                    "ContactNo"         => (int)"91" . $customerDetail->customer_mobile,
                    "Attribute1"        => $customerDetail->customer_name,
                    "Attribute2"        => "Your login is " . $customerDetail->customer_mobile
                ];
                $homeController->postMethod($url, $postFields);
                
                $mobileNumbers = [
                    "9879565478",
                    "9879565475"
                ];
                foreach ($mobileNumbers as $mobile) {
                    $postFields1        = [
                        "ContactNo"     => (int)"91" . $mobile,
                        "Attribute1"    => $customerDetail->customer_name,
                        "Attribute2"    => "Your login is " . $customerDetail->customer_mobile
                    ];

                    // Example: sending the request
                    $homeController->postMethod($url, $postFields1);
                }
                //print_r($response); die;
            }
            /*$email                    = explode('@', $customerDetail->customer_email);
            $last                       = $email[1];*/
            $last                       = substr($customerDetail->customer_mobile, 6, 4);
            $reset_form                 =
            '<div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="resetPass_form" name="resetPass_form" onsubmit="return false;">
                    <div class="ps-form__content" id="resetPassFrm">
                        <!--<h4>Sign up our newsletter and save 25% off for the next purchase!</h4>-->
                        <!--<p>Subscribe to our newsletters and don’t miss new arrivals, the latest fashion updates and our promotions.</p>-->
                        <input type="hidden" name="reset_mobile" id="reset_mobile" value="'.base64_encode($customerDetail->customer_mobile).'">
                        <div class="mb-3">
                            <label>Password</label>
                            <input class="form-control mb-0" type="password" id="resetpassword" name="resetpassword">
                            <span class="help-block" id="rpass_msg"></span>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input class="form-control mb-0" type="password" id="resetcpassword" name="resetcpassword">
                            <span class="help-block" id="rcpass_msg"></span>
                        </div>
                        <button class="btn btn-primary w-100" type="button" id="resetPassBtn" name="resetPassBtn" onclick="return reset_password();">Reset Password</button>
                    </div>
                    <div class="text-center" id="fotpThankMsg" style="margin-top: 15px;"></div>
                </form>
            </div>';


            // Save session
            Session::put('customer_id', $customerDetail->customer_id);
            Session::put('customer_name', $customerDetail->customer_name);
            Session::put('customer_email', $customerDetail->customer_email);
            Session::put('customer_phone', $customerDetail->customer_mobile);

            // Update last login info
            $customerDetail->update([
                'customer_otp' => '',
                'customer_last_login_date' => now(),
                'customer_last_login_ip' => $request->ip(),
            ]);

            return response()->json(["message" => "success", "redirect_url" => $request->input('URI') ? base64_decode($request->input('URI')) : url('/')]);
        } /*elseif ($customerDetail && $customerDetail->customer_status == "0") {
            return response()->json(["message"  => "unauthorised", "msg_text" => "Your account has not been activated yet. Please reset your account."]);
        }*/ else {
            return response()->json(["message" => "wrong"]);
        }
    }

    public function validate_forgot(Request $request) {
        $otp                = mt_rand(100000, 999999);
        if($customerDetail  = Customer::where('customer_mobile', $request->forgot_mobile)->first()){
            Customer::where('customer_id', $customerDetail->customer_id)->update([
                'customer_otp' => $otp
            ]);
            $last                       = substr($customerDetail->customer_mobile, 6, 4);
            /*$email                    = explode('@', $customerDetail->customer_email);
            $last                       = $email[1];*/
            $otp_form                   =
                '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">OTP Verify</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="otp_verify_form" name="otp_verify_form" onsubmit="return false;">
                        <div class="ps-form__content" id="optFrm">
                            <!--<h4>Sign up our newsletter and save 25% off for the next purchase!</h4>-->
                            <!--<p>Subscribe to our newsletters and don’t miss new arrivals, the latest fashion updates and our promotions.</p>-->
                            <input type="hidden" name="verify_mobile" id="verify_mobile" value="'.base64_encode($request->forgot_mobile).'">
                            <p style="margin-bottom: 0px;">OTP has been sent to ******<span id="mobile_id">'.$last.'</span></p>
                            <div class="mb-3">
                                <label>OTP</label>
                                <input class="form-control mb-0" type="password" id="user_otp" name="user_otp" onkeypress="return isNumberKey(event);" maxlength="6">
                                <span class="help-block" id="uotp_msg"></span>
                            </div>
                            <button class="btn btn-primary w-100" type="button" id="verifyOtpBtn" name="verifyOtpBtn" onclick="return forgot_password();">Verify</button>
                        </div>
                        <div class="text-center" id="fotpThankMsg" style="margin-top: 15px;"></div>
                    </form>
                </div>
                <div class="modal-footer" id="resend_otp" style="display: unset !important;">
                    <p style="text-align: center">Didn\'t receive code? <a href="javascript: void (0)" class="text-blue" onclick="resend_otp();">Resend</a></p>
                </div>';

            $fromEmail                  = 'no-reply@yaarioke.com';
            $fromName                   = 'YAARIOKE';
            $subjectUser                = "OTP for YAARIOKE";
            $Message                    = "Your one time password(OTP) code for YAARIOKE is : ".$otp;

            $homeController             = new HomeController();
            //mail sent to user
            //$homeController->sendMail($fromEmail, $customerDetail->customer_email, $fromName, $customerDetail->customer_name, $subjectUser, $Message);
            // SEND SMS to User
            // SEND SMS to User
            $Message                    = $customerDetail->customer_otp . " is your one-time verification code for yaarioke.com. YARIOK";
            $homeController->sendSMS($request->forgot_mobile, $Message);
            return response()->json(["message" => "success", "email" => $customerDetail->customer_mobile, "otp_form" => $otp_form]);
        } else {
            return response()->json(["message" => "wrong", "msg_text" => "Mobile Number not exist. Please do not forgot your account"]);
        }
    }

    public function reset_password(Request $request) {
        try {
            $customerDetail             = Customer::where('customer_mobile', $request->reset_mobile)->first();
            if ($customerDetail) {
                Customer::where('customer_id', $customerDetail->customer_id)->update([
                    'customer_password' => md5(trim($request->md5(trim($_POST["resetpassword"])))),
                    'customer_otp' => ''
                ]);

                $successmsg = "Password reset Successfully";
                return response()->json(["message" => "success", "msg_text" => $successmsg]);
            } else {
                $failmsg = "Error during reset password";
                return response()->json(["message" => "wrong", "msg_text" => $failmsg]);
            }
        } catch (\Exception $e) {
            Log::error('Catch error reset_password: ' . $e->getMessage());
        }
    }

    public function validate_login(Request $request) {
        $username = $request->input('username');
        $password = $request->input('userpassword');
        if ($username) {
            $result = Customer::where(function ($query) use ($username) {
                    $query->where('customer_email', $username)->orWhere('customer_mobile', $username);
                })/*->where('customer_password', md5(trim($password)))*/->first();
    
            /*if ($result && $result->customer_status == "1") {
                // Save session
                Session::put('customer_id', $result->customer_id);
                Session::put('customer_name', $result->customer_name);
                Session::put('customer_email', $result->customer_email);
                Session::put('customer_phone', $result->customer_mobile);
    
                // Update last login info
                $result->update([
                    'customer_last_login_date' => now(),
                    'customer_last_login_ip'   => $request->ip(),
                ]);
    
                $successmsg = "Login Successfully";
                return response()->json(["message" => "success", "redirect_url" => $request->input('URI') ? base64_decode($request->input('URI')) : url('/'), "msg_text" => $successmsg]);
            } elseif ($result && $result->customer_status == "0") {
                return response()->json(["message"  => "wrong", "msg_text" => "Your account has not been activated yet. Please reset your account."]);
            } else {
                return response()->json(["message"  => "wrong", "msg_text" => "Invalid username and password"]);
            }*/
            if ($result) {
                $otp                    = mt_rand(100000, 999999);
                $result->update([
                    'customer_otp' => $otp
                ]);
                $redirect_url           = $request->input('URI') ? $request->input('URI') : url('/');
                $last                   = substr($result->customer_mobile,6,4);
                /*$email                = explode('@', $_POST["user_email"]);
                $last                   = $email[1];*/
                $otp_form               =
                    '<div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">OTP Verify</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="otp_verify_form" name="otp_verify_form" onsubmit="return false;">
                        <div id="optFrm">
                            <!--<h4>Sign up our newsletter and save 25% off for the next purchase!</h4>-->
                            <!--<p>Subscribe to our newsletters and don’t miss new arrivals, the latest fashion updates and our promotions.</p>-->
                            <input type="hidden" name="verify_mobile" id="verify_mobile" value="'.base64_encode($result->customer_mobile).'">
                            <input type="hidden" id="URI" name="URI" value="'.$redirect_url.'">
                            <p style="margin-bottom: 0px;">OTP has been sent to ******<span id="mobile_id">'.$last.'</span></p>
                            <div class="mb-3">
                                <label>OTP</label>
                                <input class="form-control mb-0" type="password" id="user_otp" name="user_otp" onkeypress="return isNumberKey(event);" maxlength="6">
                                <span class="help-block" id="uotp_msg"></span>
                            </div>
                            <button class="btn btn-primary w-100" type="button" id="verifyOtpBtn" name="verifyOtpBtn" onclick="return verify_otp();">Verify</button>
                        </div>
                        <div class="text-center" id="otpThankMsg" style="margin-top: 15px;"></div>
                    </form>
                </div>
                <div class="modal-footer" id="resend_otp" style="display: unset !important;">
                    <p style="text-align: center">Didn\'t receive code? <a href="javascript: void (0)" class="text-blue" onclick="resend_otp();">Resend</a></p>
                </div>';

                $fromEmail              = 'no-reply@yaarioke.com';
                $fromName               = 'YAARIOKE';
                $subjectUser            = "OTP for YAARIOKE";
                $Message                = "Your one time password(OTP) code for YAARIOKE is : ".$otp;

                $homeController         = new HomeController();
                //mail sent to user
                //$homeController->sendMail($fromEmail, $result->customer_email, $fromName, htmlspecialchars(addslashes(strip_tags(ucwords(strtolower($result->customer_name))))), $subjectUser, $Message);

                // SEND SMS to User
                $Message                = $otp . " is your one-time verification code for yaarioke.com. YARIOK";
                $homeController->sendSMS($result->customer_mobile, $Message);

                return response()->json(["message" => "success", "email" => $result->customer_email, "otp_form" => $otp_form]);
            } else {
                return response()->json(["message"  => "wrong", "msg_text" => "Mobile number not registered"]);
            }
        }
        return response()->json(["message"  => "wrong", "msg_text" => "Invalid username"]);
    }

    public function logout(Request $request) {
        try {
            // Clear specific session data
            Session::forget('customer_id');
            Session::forget('customer_name');
            Session::forget('customer_email');
            Session::forget('customer_phone');

            // Redirect to login or homepage
            return response()->json(["message" => "success"]);
        } catch (\Exception $e) {
            Log::error('Catch error logout: ' . $e->getMessage());
        }
    }
}