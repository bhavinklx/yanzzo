<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Controllers\HomeController;
use App\Models\Pages;
use App\Models\Lounge;
use App\Models\LoungeTime;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\CartLog;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Membership;
use App\Models\Order;
use App\Models\MembershipOrder;
use App\Models\Payment;
use App\Models\Setting;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Razorpay\Api\Api;
use Carbon\Carbon;
use DateTime;

class CheckoutController extends Controller
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

    public function bookingLounge($id) {
        /*$homeController = new HomeController();
        $url = "https://apiv1.anantya.ai/api/Campaign/SendSingleTemplateMessage?templateId=36";
        $postFields = [
            "ContactNo" => "919173905270",
            "Attribute1" => "Bhavin Kalena",
            "Attribute2" => '06-12-2025',
            "Attribute3" => '',
            "Attribute4" => 'Adults: 2 Children: 0'
        ];
        $response = $homeController->postMethod($url, $postFields);
        print_r($response); die;*/

        //$this->orderRescheduleEmailTemplate(14);
        try {
            Session::forget('discount');
            Session::forget('discount_text');
            Session::forget('discount_code');
            Session::forget('discount_id');

            $decryptId = Crypt::decrypt($id);
            $pagesDetail = Pages::where('page_id', 2)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $loungeDetail = Lounge::where('lounge_id', trim($decryptId))->first();
            $ltimeDetail = DB::select("SELECT 
                                            GROUP_CONCAT(ltime_day ORDER BY FIELD(ltime_day, 'MON','TUE','WED','THU','FRI','SAT','SUN')) AS days,
                                            time_range,
                                            rate
                                        FROM (
                                            SELECT 
                                                ltime_day,
                                                CONCAT(
                                                    LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap,
                                                    ' - ',
                                                    LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap
                                                ) AS time_range,
                                                ltime_text AS rate
                                            FROM lounge_time
                                            WHERE lounge_id = '".trim($decryptId)."'
                                        ) AS sub
                                        GROUP BY 
                                            time_range, rate
                                        ORDER BY 
                                            MIN(FIELD(ltime_day, 'MON','TUE','WED','THU','FRI','SAT','SUN')),
                                            CAST(rate AS UNSIGNED)");
            return view('bookinglounge')->with(['pagesDetail'=>$pagesDetail, 'loungeDetail'=>$loungeDetail, 'ltimeDetail'=>$ltimeDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error bookinglounge: ' . $e->getMessage());
        }
    }

    public function loadTimeSlot(Request $request) {
        try {
            $selectedDate = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $startDate = $selectedDate->format('Y-m-d');
            $dayName = strtoupper(date('D', strtotime($startDate)));
            $loungeId = $request->lounge_id ?? 0;
            $orderId = $request->order_id ?? 0;
            $isToday = $selectedDate->isToday(); // Check if selected date is today
            $dayName = strtoupper(date('D', strtotime($startDate)));
            $nowTimestamp = time(); // current timestamp
            $ltimeDetail = LoungeTime::where('lounge_id', $loungeId)->where('ltime_day', $dayName)->get();
            //echo "<pre>"; print_r($ltimeDetail); die;

            //start code run only reschedule time
            $maintenanceTimeSlotsArray = $isFulldayClose = $timeSlotsArray = $ranges = $expandedSlots = $maintenanceSlots = [];
            $ltimeData = DB::select("SELECT 
                                        l.lounge_id,
                                        l.ltime_text,
                                        GROUP_CONCAT(
                                            CONCAT(
                                                DATE_FORMAT(
                                                    MAKETIME(
                                                        CASE 
                                                            WHEN ltime_open_ap = 'PM' AND ltime_open_hour < 12 THEN ltime_open_hour + 12
                                                            WHEN ltime_open_ap = 'AM' AND ltime_open_hour = 12 THEN 0
                                                            ELSE ltime_open_hour
                                                        END, 0, 0
                                                    ), '%h:%i '
                                                ),
                                                ltime_open_ap,
                                                ' - ',
                                                DATE_FORMAT(
                                                    MAKETIME(
                                                        CASE 
                                                            WHEN ltime_close_ap = 'PM' AND ltime_close_hour < 12 THEN ltime_close_hour + 12
                                                            WHEN ltime_close_ap = 'AM' AND ltime_close_hour = 12 THEN 0
                                                            ELSE ltime_close_hour
                                                        END, 0, 0
                                                    ), '%h:%i '
                                                ),
                                                ltime_close_ap,
                                                ' => ',
                                                l.ltime_text
                                            )
                                            ORDER BY ltime_open_hour ASC
                                            SEPARATOR ', '
                                        ) AS time_slots
                                    FROM lounge_time l
                                    WHERE ltime_day = '".$dayName."' AND lounge_id = $loungeId
                                    GROUP BY l.lounge_id, l.ltime_text;");

            //maintenance time slot get
            $lmtimeData = DB::select("SELECT 
                                        CONCAT(
                                            lmtime_open_hour, ':', lmtime_open_time, ' ', lmtime_open_ap,
                                            ' - ',
                                            lmtime_close_hour, ':', lmtime_close_time, ' ', lmtime_close_ap
                                        ) AS slot_time,
                                        is_fullday_close
                                    FROM lounge_maintenance_time
                                    WHERE lmtime_open_date = '".$startDate."' AND lounge_id = $loungeId AND deleted_at IS NULL");

            foreach ($lmtimeData as $row) {
                $maintenanceTimeSlotsArray[] = $row->slot_time;
                $isFulldayClose[] = $row->is_fullday_close;
            }

            foreach ($maintenanceTimeSlotsArray as $slot) {
                // Split start & end
                list($start, $end) = array_map('trim', explode('-', $slot));

                // Convert to timestamps
                $startTime = strtotime($start);
                $endTime = strtotime($end);

                // If start and end time are same → include only that slot
                if ($startTime == $endTime) {
                    $maintenanceSlots[] = date('h:i A', $startTime);
                    continue;
                }

                // Loop hour by hour (end time excluded)
                while ($startTime < $endTime) {
                    $maintenanceSlots[] = date('h:i A', $startTime);
                    $startTime = strtotime('+1 hour', $startTime);
                }
            }

            for ($l = 0; $l < count($ltimeData); $l++) {
                //$alreadyBooked[] = $startDateTime->copy()->addHours($i)->format('h:i A');
                $timeSlotsArray[] = $ltimeData[$l]->time_slots;
            }

            foreach ($timeSlotsArray as $item) {
                [$key, $value] = explode(" => ", $item);
                $ranges[trim($key)] = (int) trim($value);
            }

            foreach ($ranges as $range => $price) {
                // Remove brackets if they exist
                $range = str_replace(['[', ']'], '', $range);

                // Split start and end time
                [$start, $end] = explode(" - ", $range);

                // Convert to timestamps
                $startTime = strtotime($start);
                $endTime   = strtotime($end);

                // Loop hour by hour (inclusive)
                for ($time = $startTime; $time <= $endTime; $time = strtotime("+1 hour", $time)) {
                    $expandedSlots[date("h:i A", $time)] = $price;
                }
            }

            if ($orderId > 0) {
                $orderDetail = Order::where('order_id', $orderId)->first(); // returns a collection of cart_ids
                $filterPrices = CartDetail::where('cart_id', $orderDetail->cart_id)->pluck('cdetail_amount')->toArray();

                // Filter the slots using the filterPrices array
                /*$expandedSlots = array_filter($expandedSlots, function($price) use ($filterPrices) {
                    return in_array($price, $filterPrices);
                });*/

                $filterPrices = max($filterPrices);
                $expandedSlots = array_filter($expandedSlots, function($price) use ($filterPrices) {
                    return $price <= $filterPrices;
                });

                // Sort by time
                uksort($expandedSlots, function($a, $b) {
                    return strtotime($a) - strtotime($b);
                });
            }
            //end code run only reschedule time

            //get already booked slot for selected date
            $cartDetail = Cart::where('lounge_id', $loungeId)->where('cart_start_date', $startDate)->where('cart_status', '3')->get();
            $tenMinutesAgo = Carbon::now()->subMinutes(10);
            /*$cartDetail = Cart::where('cart_start_date', $startDate)
                ->where(function ($query) use ($tenMinutesAgo) {
                    $query->where('cart_status', '3')
                        ->orWhere(function ($q) use ($tenMinutesAgo) {
                            $q->where('cart_status', '1')
                                ->where(function ($qq) use ($tenMinutesAgo) {
                                    $qq->where('created_at', '>=', $tenMinutesAgo)
                                        ->orWhere('updated_at', '>=', $tenMinutesAgo);
                                });
                        });
                })
                ->get();*/
            $alreadyBooked = [];
            foreach ($cartDetail as $cart) {
                $startTime = $cart->cart_start_time;
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' ' . $startTime);
                $hoursToAdd = explode(',', $cart->cart_duration);
                for ($i = 0; $i < count($hoursToAdd); $i++) {
                    //$alreadyBooked[] = $startDateTime->copy()->addHours($i)->format('h:i A');
                    $alreadyBooked[] = $hoursToAdd[$i];
                }
            }

            //get booked slot for selected date 10 min
            $cartDetail = Cart::where('lounge_id', $loungeId)->where('cart_start_date', $startDate)
                ->where('cart_status', '1')
                ->where(function ($query) use ($tenMinutesAgo) {
                    $query->where('created_at', '>=', $tenMinutesAgo)
                        ->orWhere('updated_at', '>=', $tenMinutesAgo);
                })
                ->get();
            $progressBooked = [];
            foreach ($cartDetail as $cart) {
                $startTime = $cart->cart_start_time;
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $startDate . ' ' . $startTime);
                $hoursToAdd = explode(',', $cart->cart_duration);
                for ($i = 0; $i < count($hoursToAdd); $i++) {
                    //$progressBooked[] = $startDateTime->copy()->addHours($i)->format('h:i A');
                    $progressBooked[] = $hoursToAdd[$i];
                }
            }

            // Prepare all valid time ranges in Carbon intervals
            $timeRanges = [];
            foreach ($ltimeDetail as $ltime) {
                $openTimeStr = $ltime->ltime_open_hour . ':' . $ltime->ltime_open_time . ' ' . $ltime->ltime_open_ap;
                $closeTimeStr = $ltime->ltime_close_hour . ':' . $ltime->ltime_close_time . ' ' . $ltime->ltime_close_ap;
                $timeRanges[] = $openTimeStr."--".$closeTimeStr;
            }
            //echo "<pre>"; print_r($timeRanges); die;

            $allTimes = [];
            foreach ($timeRanges as $range) {
                list($startTime, $endTime) = explode('--', $range);

                $startTimestamp = strtotime($startTime);
                $endTimestamp = strtotime($endTime);
                $current = $startTimestamp;

                while ($current < $endTimestamp) {
                    // Only add if time is greater than current time
                    if (!$isToday || $current > $nowTimestamp) {
                        $allTimes[] = date("h:i A", $current);
                    }
                    $current = strtotime("+1 hour", $current);
                }
            }
            // Remove duplicates in case times overlap between ranges
            $allTimes = array_unique($allTimes);
            //sort($allTimes);
            // Sort chronologically using strtotime
            usort($allTimes, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            if ($orderId > 0) {
                // Keep only times that exist in $filterPrices keys
                $allTimes = array_values(array_filter($allTimes, function($time) use ($expandedSlots) {
                    return array_key_exists($time, $expandedSlots);
                }));
            }

            $html = '<div class="row mx-0">';
            foreach ($allTimes as $time) {
                $isBooked = in_array($time, $alreadyBooked) ? 'disabled' : '';
                $isDisabled = in_array($time, $progressBooked) ? 'disabled' : '';
                $isMaintenance = (in_array($time, $maintenanceSlots) || (isset($isFulldayClose[0]) && $isFulldayClose[0] == 1)) ? 'disabled' : '';
                $class = 'citiesbox px-2 select-time-btn';
                $title = '';
                if ($isBooked) {
                    $class .= ' time-booked';
                    $title = 'Already Booked';
                }
                if ($isDisabled) {
                    $class .= ' time-disabled';
                    $title = 'Not Available';
                }
                if ($isMaintenance) {
                    $class .= ' time-maintenance'; // New class for maintenance
                    $title = 'Under Maintenance';
                }
                $html .= '<div class="col-3 py-1 my-1"><button class="' . $class . '" title="' . $title . '" data-time="' . htmlspecialchars($time) . '" ' . $isBooked . ' ' . $isDisabled . '>' . htmlspecialchars($time) . '</button></div>';
            }
            $html .= '</div>';

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            Log::error('Catch error loadtimeslot: ' . $e->getMessage());
        }
    }

    public function bookingLounge_insert(Request $request) {
        Session::forget('discount');
        Session::forget('discount_text');
        Session::forget('discount_code');
        Session::forget('discount_id');

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            /*'duration' => 'required|integer|min:1',*/
            'adults' => 'required|integer|min:2',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $timeSlots = $timeRanges = $conditions = $ltimeDetail = $cartArray = [];
            $cartAmount = $membershipDiscount = 0;
            $membershipDetail = null;

            $encryptedId = Crypt::encrypt($request->lounge_id);
            if (isset($request->order_id) && $request->order_id > 0) {
                $orderDetail = Order::where('order_id', $request->order_id)->first();
                $cartDetail = Cart::where(['cart_id'=>$orderDetail->cart_id, 'cart_status'=>'3'])->first();
                $request->customer_id = $orderDetail->customer_id;
                $request->lounge_id = $orderDetail->lounge_id;
                $request->children = $cartDetail->cart_children;
            } else {
                $cartDetail = Cart::where(['customer_id'=>$request->customer_id, 'lounge_id'=>$request->lounge_id, 'cart_status'=>'1'])->first();
            }
            $startDate = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');

            // Parse time safely
            //$startTime = Carbon::createFromFormat('g:i A', $request->start_time)->format('H:i');
            //$startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startDate . ' ' . $startTime);
            //$hoursToAdd = $request->duration;

            /*for ($i = 0; $i <= $hoursToAdd; $i++) {
                $timeSlots[] = $startDateTime->copy()->addHours($i)->format('H:i:s');
            }*/

            if (!empty($request->start_time)) {
                $timeSlots = explode(',', $request->start_time);
            }
            if (isset($request->order_id) && $request->order_id > 0) {
                $bookedSlots = explode(',', $cartDetail->cart_duration);
                if (count($timeSlots) > count($bookedSlots)) {
                    $validator = Validator::make($request->all(), [
                        'start_time' => [
                            function ($attribute, $value, $fail) {
                                $fail('Sorry, the selected time slot is greater than booked.');
                            },
                        ],
                    ]);
                    if ($validator->fails()) {
                        return ['status' => 'validation-error', 'data' => $validator->errors()];
                    }
                }
            }
            if (count($timeSlots) == 1) {
                $startTime = Carbon::createFromFormat('g:i A', $timeSlots[0])->format('H:i');
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startDate . ' ' . $startTime);
                $timeSlots[] = $startDateTime->copy()->addHours(1)->format('h:i A');
            } else {
                $startTime = Carbon::createFromFormat('g:i A', end($timeSlots))->format('H:i');
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startDate . ' ' . $startTime);
                $timeSlots[] = $startDateTime->copy()->addHours(1)->format('h:i A');
            }
            //echo "<pre>"; print_r($timeSlots); die;
            // Calculate end time
            //$endTime = $startDateTime->copy()->addHours($hoursToAdd)->format('H:i:s');
            $dayName = strtoupper(date('D', strtotime($startDate)));

            for ($i = 0; $i < count($timeSlots) - 1; $i++) {
                $startTime  = DateTime::createFromFormat('h:i A', $timeSlots[$i]);
                //$endTime  = DateTime::createFromFormat('h:i A', $timeSlots[$i + 1]);
                $endTime    = clone $startTime; // clone so we don’t affect $startTime
                $endTime->modify('+1 hour');

                $timeRanges[] = [
                    'start' => $startTime->format('H:i:s'),
                    'end'   => $endTime->format('H:i:s'),
                ];
            }
            //echo "<pre>"; print_r($timeRanges); die;

            foreach ($timeRanges as $range) {
                $start = $range['start'];
                $end = $range['end'];
                $ltimeDetail = DB::select("SELECT *
                                        FROM lounge_time
                                        WHERE lounge_id = '".$request->lounge_id."'
                                        AND ltime_day = '".$dayName."'
                                        AND (
                                            -- Normal time range (same day)
                                            (
                                                STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p') 
                                                > STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                                AND
                                                -- Overlaps with 18:00 to 19:00
                                                STR_TO_DATE('".$start."', '%H:%i:%s') < STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p')
                                                AND
                                                STR_TO_DATE('".$end."', '%H:%i:%s') > STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                            )
                                            OR
                                            -- Overnight time range (past midnight)
                                            (
                                                STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p') 
                                                <= STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                                AND (
                                                    STR_TO_DATE('".$start."', '%H:%i:%s') >= STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                                    OR
                                                    STR_TO_DATE('".$end."', '%H:%i:%s') <= STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p')
                                                )
                                            )
                                        ) ");

                if ($ltimeDetail) { foreach ($ltimeDetail as $ltime) {
                    $cartAmount += $ltime->ltime_text;

                    $cartArray[] = [
                        'start' => date('h:i A', strtotime($start)),
                        'end' => date('h:i A', strtotime($end)),
                        'amount' => $ltime->ltime_text,

                    ];
                } }
            }
            //echo "<pre>"; print_r($cartArray); die;
            /*$ltimeDetail = DB::select("SELECT *
                            FROM lounge_time
                            WHERE lounge_id = '".$request->lounge_id."'
                            AND (
                                -- Normal time range (same day)
                                (
                                    STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p') 
                                    > STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                    AND '".$startTime."' BETWEEN
                                        STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                        AND
                                        STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p')
                                )
                                OR
                                -- Overnight time range (past midnight)
                                (
                                    STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p') 
                                    <= STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                    AND (
                                        '".$startTime."' >= STR_TO_DATE(CONCAT(LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap), '%h:%i %p')
                                        OR
                                        '".$startTime."' <= STR_TO_DATE(CONCAT(LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap), '%h:%i %p')
                                    )
                                )
                            )
                            AND ltime_day = '".$dayName."' ");*/

            if ($cartDetail) {
                $cartId = $cartDetail->cart_id;
                if (isset($request->order_id) && $request->order_id > 0) {
                    CartLog::create([
                        'cart_id' => $cartId,
                        'customer_id' => $cartDetail->customer_id,
                        'lounge_id' => $cartDetail->lounge_id,
                        'clog_start_date' => $cartDetail->cart_start_date,
                        'clog_start_time' => $cartDetail->cart_start_time,
                        'clog_duration' => $cartDetail->cart_duration,
                        'created_ip' => $cartDetail->created_ip,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $this->orderRescheduleEmailTemplate($request->order_id);
                }

                Cart::where('cart_id', $cartId)->update([
                    'customer_id' => $request->customer_id,
                    'lounge_id' => $request->lounge_id,
                    'cart_start_date' => $startDate,
                    'cart_start_time' => $timeRanges[0]['start'] ?? '',
                    //'cart_end_time' => NULL,
                    'cart_duration' => $request->start_time,
                    'cart_adults' => $request->adults,
                    'cart_children' => $request->children,
                    'cart_amount' => $cartAmount ?? 0,
                    'cart_reschedule' => (isset($request->order_id) && $request->order_id > 0) ? '1' : '0',
                    'updated_ip' => $request->ip(),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                CartDetail::where('cart_id', $cartId)->delete();
                if (count($cartArray) > 0) { foreach ($cartArray as $cart) {
                    CartDetail::create([
                        'cart_id' => $cartId,
                        'cdetail_start_time' => $cart['start'],
                        'cdetail_end_time' => $cart['end'],
                        'cdetail_amount' => $cart['amount'],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                } }
            } else {
                $cartId = Cart::create([
                    'customer_id' => $request->customer_id,
                    'lounge_id' => $request->lounge_id,
                    'cart_start_date' => $startDate,
                    'cart_start_time' => $timeRanges[0]['start'] ?? '',
                    //'cart_end_time' => NULL,
                    'cart_duration' => $request->start_time,
                    'cart_adults' => $request->adults,
                    'cart_children' => $request->children,
                    'cart_amount' => $cartAmount ?? 0,
                    'cart_status' => '1',
                    'cart_reschedule' => '0',
                    'created_ip' => $request->ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                ])->cart_id;

                if (count($cartArray) > 0) { foreach ($cartArray as $cart) {
                    CartDetail::create([
                        'cart_id' => $cartId,
                        'cdetail_start_time' => $cart['start'],
                        'cdetail_end_time' => $cart['end'],
                        'cdetail_amount' => $cart['amount'],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                } }
            }

            //off 999 above cart value 999
            $morderDetail = MembershipOrder::where('customer_id', $request->customer_id)->where('msorder_status', '1')->where('msorder_end_date', '>=', date('Y-m-d'))->orderBy("msorder_id", "DESC")->first();
            $totalOrder = Order::where('customer_id', $request->customer_id)->where('order_status', '1')->count();
            if (isset($morderDetail)) {
                $membershipDetail = Membership::where('membership_id', $morderDetail->membership_id)->first();
                $membershipDiscount = @ceil(($cartAmount * $membershipDetail->membership_discount) / 100);
            }
            if ($totalOrder == 0) {
                $firstOrderDiscount = $cartAmount - $membershipDiscount;
                $discountDetail = Discount::where('discount_code', 'FIRSTORDER')->where('discount_status', '1')->where('discount_level', '1')->first();
                if (isset($discountDetail) && $firstOrderDiscount > 999) {
                    if (count(explode(',', $request->start_time)) == 1) {
                        $discountDetail->discount_amount = $firstOrderDiscount - 999;
                        Session::put('discount', @ceil($discountDetail->discount_amount));
                        Session::put('discount_text', "₹ ".@ceil($discountDetail->discount_amount)." Off On Total Order.");
                        Session::put('discount_code', $discountDetail->discount_code);
                        Session::put('discount_id', $discountDetail->discount_id);
                    } else {
                        $minAmount = CartDetail::where('cart_id', $cartId)->min('cdetail_amount');
                        if ($minAmount > 999) {
                            $discountDetail->discount_amount = $minAmount - 999;
                            Session::put('discount', @ceil($discountDetail->discount_amount));
                            Session::put('discount_text', "₹ ".@ceil($discountDetail->discount_amount)." Off On Total Order.");
                            Session::put('discount_code', $discountDetail->discount_code);
                            Session::put('discount_id', $discountDetail->discount_id);
                        }
                    }
                }
            }
            return ["redirect_url" => url("payment-lounge/".$encryptedId)];
        }
    }

    public function confirmLounge($id) {
        try {
            $decryptId = Crypt::decrypt($id);
            $pagesDetail = Pages::where('page_id', 2)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $loungeDetail = Lounge::where('lounge_id', trim($decryptId))->first();
            $cartDetail = Cart::where(['customer_id'=>Session::get('customer_id'), 'lounge_id'=>trim($decryptId), 'cart_status'=>'1'])->first();
            $customerDetail = Customer::where('customer_id', $cartDetail->customer_id)->first();
            return view('confirmlounge')->with(['pagesDetail'=>$pagesDetail, 'loungeDetail'=>$loungeDetail, 'cartDetail'=>$cartDetail, 'customerDetail'=>$customerDetail, 'loungeId'=>$id]);
        } catch (\Exception $e) {
            Log::error('Catch error confirmlounge: ' . $e->getMessage());
        }
    }

    public function paymentLounge($id) {
        try {
            $membershipDetail = null;
            $decryptId = Crypt::decrypt($id);
            $pagesDetail = Pages::where('page_id', 2)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            /*$orderDetail = Order::where('order_unique_id', Session::get('order_unique_id'))->whereNotNull('order_token')->first();
            if(!empty($orderDetail)){
                return redirect()->route('payment-expired');
            }*/
            $loungeDetail = Lounge::where('lounge_id', trim($decryptId))->first();
            $cartDetail = Cart::where(['lounge_id' => trim($decryptId), 'customer_id' => Session::get('customer_id'), 'cart_status' => '1'])->first();
            $customerDetail = Customer::where('customer_id', $cartDetail->customer_id)->first();
            $totalOrder = Order::where('customer_id', Session::get('customer_id'))->where('order_status', '1')->count();
            $morderDetail = MembershipOrder::where('customer_id', Session::get('customer_id'))->where('msorder_status', '1')->where('msorder_end_date', '>=', date('Y-m-d'))->orderBy("msorder_id", "DESC")->first();
            if (isset($morderDetail)) {
                $membershipDetail = Membership::where('membership_id', $morderDetail->membership_id)->first();
            }
            return view('paymentlounge')->with(['pagesDetail'=>$pagesDetail, 'loungeDetail'=>$loungeDetail, 'cartDetail'=>$cartDetail, 'customerDetail'=>$customerDetail, 'totalOrder'=>$totalOrder, 'morderDetail'=>$morderDetail, 'membershipDetail'=>$membershipDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error paymentlounge: ' . $e->getMessage());
        }
    }

    public function applyDiscount(Request $request) {
        try {
            $discountAmount = 0;
            // get discount detail through code
            $discountDetail = Discount::where('discount_code', $request->discount_code)->where('discount_status', '1')->where('discount_level', '0')->first();
            if($discountDetail){
                $startTime  = date("Y-m-d H:i:s", strtotime($discountDetail->discount_start_date.' '.$discountDetail->discount_start_time));
                $endTime    = date("Y-m-d H:i:s", strtotime($discountDetail->discount_end_date.' '.$discountDetail->discount_end_time));
                $cartSubtotal= base64_decode($request->cart_subtotal);
                // check discount coupon expire or not
                if (strtotime($startTime) < time() && strtotime($endTime) > time()) {
                    if ($discountDetail->discount_scenario_type == 1) {
                        if ($discountDetail->discount_type == "percentage") {
                            $discountAmount = ($cartSubtotal * $discountDetail->discount_amount) / 100;
                            if ($discountAmount > $cartSubtotal) {
                                return response()->json(["message" => "minimum"]);
                            }
                            Session::put('discount', @ceil($discountAmount));
                            Session::put('discount_text', "₹ ".@ceil($discountAmount)." Off On Total Order.");
                            Session::put('discount_code', $request->discount_code);
                            Session::put('discount_id', $discountDetail->discount_id);

                            return response()->json(["message" => "success", "msg_text" => "Offer Applied Successfully - ".$discountDetail->discount_title]);
                        } else if ($discountDetail->discount_type == "cash") {
                            $discountAmount = $cartSubtotal - $discountDetail->discount_amount;
                            if($discountAmount >= 0) {
                                if ($discountAmount > $cartSubtotal) {
                                    return response()->json(["message" => "minimum"]);
                                }
                                Session::put('discount', @ceil($discountDetail->discount_amount));
                                Session::put('discount_text', "₹ ".@ceil($discountDetail->discount_amount)." Off On Total Order.");
                                Session::put('discount_code', $request->discount_code);
                                Session::put('discount_id', $discountDetail->discount_id);

                                return response()->json(["message" => "success", "msg_text" => "Offer Applied Successfully - ".$discountDetail->discount_title]);
                            } else {
                                return response()->json(["message" => "00"]);
                            }
                        } else {
                            return response()->json(["message" => "failed"]);
                        }
                    } else {
                        // check discount minimum order amount
                        if ($cartSubtotal > $discountDetail->discount_min_amount) {
                            // check discount type
                            if ($discountDetail->discount_type == "percentage") {
                                $discountAmount = ($cartSubtotal * $discountDetail->discount_amount) / 100;
                                Session::put('discount', ($discountAmount < $discountDetail->discount_max_discount) ? @ceil($discountAmount) : @ceil($discountDetail->discount_max_discount));
                                Session::put('discount_text', ($discountAmount < $discountDetail->discount_max_discount) ? "₹ ".@ceil($discountAmount)." Off On Total Order." : "₹ ".@ceil($discountDetail->discount_max_discount)." Off On Total Order.");
                                Session::put('discount_code', $request->discount_code);
                                Session::put('discount_id', $discountDetail->discount_id);

                                return response()->json(["message" => "success", "msg_text" => "Offer Applied Successfully - ".$discountDetail->discount_title]);
                            } else if ($discountDetail->discount_type == "cash") {
                                $discountAmount = $cartSubtotal - $discountDetail->discount_amount;
                                if($discountAmount >= 0 ) {
                                    Session::put('discount', ($discountDetail->discount_amount < $discountDetail->discount_max_discount) ? @ceil($discountDetail->discount_amount) : @ceil($discountDetail->discount_max_discount));
                                    Session::put('discount_text', ($discountDetail->discount_amount < $discountDetail->discount_max_discount) ? "₹ ".@ceil($discountDetail->discount_amount)." Off On Total Order." : "₹ ".@ceil($discountDetail->discount_max_discount)." Off On Total Order.");
                                    Session::put('discount_code', $request->discount_code);
                                    Session::put('discount_id', $discountDetail->discount_id);

                                    return response()->json(["message" => "success", "msg_text" => "Offer Applied Successfully - ".$discountDetail->discount_title]);
                                } else {
                                    return response()->json(["message" => "00"]);
                                }
                            } else {
                                return response()->json(["message" => "failed"]);
                            }
                        } else {
                            return response()->json(["message" => "minimum"]);
                        }
                    }
                } else {
                    return response()->json(["message" => "expire"]);
                }
            } else {
                return response()->json(["message" => "failed"]);
            }
        } catch (\Exception $e) {
            Log::error('Catch error applydiscount: ' . $e->getMessage());
        }
    }

    public function removeDiscount(Request $request) {
        try {
            // Clear specific session data
            Session::forget('discount');
            Session::forget('discount_text');
            Session::forget('discount_code');
            Session::forget('discount_id');

            // Redirect to login or homepage
            return response()->json(["message" => "success"]);
        } catch (\Exception $e) {
            Log::error('Catch error removediscount: ' . $e->getMessage());
        }
    }

    public function paymentLounge_insert(Request $request) {
        try {
            $membershipDiscount = 0;
            $membershipDetail = null;
            $encryptedId = Crypt::encrypt($request->lounge_id);
            $cartDetail = Cart::where(['customer_id'=>$request->customer_id, 'lounge_id'=>$request->lounge_id, 'cart_status'=>'1'])->first();
            $customerDetail = Customer::where('customer_id', $request->customer_id)->first();
            $loungeDetail = Lounge::where('lounge_id', trim($request->lounge_id))->first();
            $morderDetail = MembershipOrder::where('customer_id', Session::get('customer_id'))->where('msorder_status', '1')->where('msorder_end_date', '>=', date('Y-m-d'))->orderBy("msorder_id", "DESC")->first();
            if (isset($morderDetail)) {
                $membershipDetail = Membership::where('membership_id', $morderDetail->membership_id)->first();
            }
            if ($cartDetail) {
                if(isset($morderDetail)) {
                    $membershipDiscount = @ceil(($cartDetail->cart_amount * $membershipDetail->membership_discount) / 100);
                }
                $paidAmount = $cartDetail->cart_amount - $membershipDiscount - Session::get('discount');
                $orderUniqueId = 'YK-'.rand(100000, 999999);
                if ($paidAmount > 0) {
                    Order::create([
                        'order_unique_id' => $orderUniqueId,
                        'customer_id' => $request->customer_id,
                        'lounge_id' => $request->lounge_id,
                        'cart_id' => $cartDetail->cart_id,
                        'order_date' => date('Y-m-d'),
                        'customer_name' => $customerDetail->customer_name,
                        'customer_mobile' => $customerDetail->customer_mobile,
                        'discount_id' => Session::get('discount_id') ?? 0,
                        'discount_code' => Session::get('discount_code') ?? NULL,
                        'discount_price' => Session::get('discount') ?? 0,
                        'membership_id' => $membershipDetail->membership_id ?? 0,
                        'membership_discount' => $membershipDiscount,
                        'order_gst' => 0,
                        'order_paid_price' => $paidAmount,
                        'order_type' => 'Razorpay',
                        'order_ostatus' => 'pending',
                        'order_status' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                    $order = $api->order->create([
                        'receipt' => $orderUniqueId,
                        'amount' => ($cartDetail->cart_amount - Session::get('discount') - $membershipDiscount) * 100,
                        'currency' => 'INR',
                        'payment_capture' => 1
                    ]);

                    return response()->json([
                        "message" => "success",
                        'order_unique_id' => $orderUniqueId,
                        'order_id' => $order->id,
                        'razorpay_key' => config('services.razorpay.key'),
                        'amount' => ($cartDetail->cart_amount - Session::get('discount') - $membershipDiscount) * 100,
                        'name' => $loungeDetail->lounge_name,
                        'description' => $loungeDetail->lounge_short_desc,
                        "customer_name" => $customerDetail->customer_name,
                        "customer_email" => $customerDetail->customer_email,
                        "customer_phone" => $customerDetail->customer_mobile
                    ]);
                } else {
                    Order::create([
                        'order_unique_id' => $orderUniqueId,
                        'customer_id' => $request->customer_id,
                        'lounge_id' => $request->lounge_id,
                        'cart_id' => $cartDetail->cart_id,
                        'order_date' => date('Y-m-d'),
                        'customer_name' => $customerDetail->customer_name,
                        'customer_mobile' => $customerDetail->customer_mobile,
                        'discount_id' => Session::get('discount_id') ?? 0,
                        'discount_code' => Session::get('discount_code') ?? NULL,
                        'discount_price' => Session::get('discount') ?? 0,
                        'membership_id' => $membershipDetail->membership_id ?? 0,
                        'membership_discount' => $membershipDiscount,
                        'order_gst' => 0,
                        'order_paid_price' => $paidAmount,
                        'order_type' => 'FREE',
                        'order_ostatus' => 'pending',
                        'order_status' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    return response()->json([
                        "message" => "free",
                        'order_unique_id' => $orderUniqueId,
                        'order_id' => '',
                        'razorpay_key' => '',
                        'amount' => ($cartDetail->cart_amount - Session::get('discount') - $membershipDiscount) * 100,
                        'name' => $loungeDetail->lounge_name,
                        'description' => $loungeDetail->lounge_short_desc,
                        "customer_name" => $customerDetail->customer_name,
                        "customer_email" => $customerDetail->customer_email,
                        "customer_phone" => $customerDetail->customer_mobile
                    ]);
                }
            } else {
                return response()->json(["message" => "wrong"]);
            }
        } catch (\Exception $e) {
            Log::error('Catch error paymentlounge_insert: ' . $e->getMessage());
        }
    }

    public function paymentLounge_verify(Request $request) {
        $data = $request->all();
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        try {
            Log::error('Payment verification: ' . json_encode($data));
            $orderDetail = Order::where('order_unique_id', $data['order_unique_id'])->first();
            if (isset($data['razorpay_payment_id'])) {
                $attributes = [
                    'razorpay_order_id' => $data['razorpay_order_id'],
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                    'razorpay_signature' => $data['razorpay_signature']
                ];

                $api->utility->verifyPaymentSignature($attributes);

                $paymentDetail = $api->payment->fetch($data['razorpay_payment_id']);
                Log::error('Payment success reponse: ' . json_encode($paymentDetail->toArray()));
                // Payment is successful and verified
                $paymentId = Payment::create([
                    'order_id' => $orderDetail->order_id,
                    'ORDERID' => $orderDetail->order_unique_id,
                    'TXNAMOUNT' => $paymentDetail->amount / 100,
                    'CURRENCY' => $paymentDetail->currency,
                    'TXNID' => $paymentDetail->id,
                    'BANKTXNID' => $paymentDetail->acquirer_data->bank_transaction_id ?? NULL,
                    'STATUS' => $paymentDetail->status,
                    'RESPCODE' => 0,
                    'RESPMSG' =>  NULL,
                    'TXNDATE' => date('Y-m-d H:i:s'),
                    'GATEWAYNAME' => NULL,
                    'PAYMENTMODE' => $paymentDetail->method,
                    'CHECKSUMHASH' => $data['razorpay_signature'],
                    'BANKNAME' => $paymentDetail->bank ?? NULL,
                    'REFERENCE' => NULL,
                    'created_at' => now(),
                ])->payment_id;

            } else {
                // Payment is successful and verified
                $request['razorpay_payment_id'] = 'pay_'.time();
                //$request['razorpay_signature'] = bin2hex(random_bytes(32));
                $paymentId = Payment::create([
                    'order_id' => $orderDetail->order_id,
                    'ORDERID' => $orderDetail->order_unique_id,
                    'TXNAMOUNT' => $orderDetail->order_paid_price / 100,
                    'CURRENCY' => 'INR',
                    'TXNID' => $request['razorpay_payment_id'],
                    'BANKTXNID' => NULL,
                    'STATUS' => 'captured',
                    'RESPCODE' => 0,
                    'RESPMSG' =>  NULL,
                    'TXNDATE' => date('Y-m-d H:i:s'),
                    'GATEWAYNAME' => NULL,
                    'PAYMENTMODE' => 'free',
                    'CHECKSUMHASH' => $data['razorpay_signature'] ?? NULL,
                    'BANKNAME' => NULL,
                    'REFERENCE' => NULL,
                    'created_at' => now(),
                ])->payment_id;
            }

            $cart = Cart::where([
                'lounge_id' => $orderDetail->lounge_id,
                'customer_id' => Session::get('customer_id'),
                'cart_status' => '1'
            ])->first();

            if ($cart) {
                $cart->update([
                    'cart_status' => '3',
                    'updated_ip' => $request->ip(),
                    'updated_at' => now()
                ]);

                $cartId = $cart->cart_id;
            } else {
                // No matching cart found
                $cartId = 0;
            }

            Order::where('order_id', $orderDetail->order_id)->update([
                'cart_id' => $cartId,
                'order_status' => '1',
                'order_token' => $data['razorpay_signature'] ?? NULL,
                'payment_id' => $paymentId,
                'payment_date' => date('Y-m-d'),
                'payment_time' => date('H:i:s')
            ]);

            $adults = $cart->cart_adults ?? 0;
            $children = $cart->cart_children ?? 0;
            $homeController = new HomeController();
            $url = "https://apiv1.anantya.ai/api/Campaign/SendSingleTemplateMessage?templateId=36";
            $postFields = [
                "ContactNo" => (int)"91" . $orderDetail->customer_mobile,
                "Attribute1" => $orderDetail->customer_name,
                "Attribute2" => date('d-m-Y', strtotime($cart->cart_start_date)),
                "Attribute3" => date('h:i A', strtotime($cart->cart_start_time)),
                "Attribute4" => 'Adults: ' . $adults . ' Children: ' . $children
            ];
            $homeController->postMethod($url, $postFields);

            $mobileNumbers = [
                "9879565478",
                "9879565475"
            ];
            foreach ($mobileNumbers as $mobile) {
                $postFields1 = [
                    "ContactNo"  => "91" . $mobile,   // no need to cast int
                    "Attribute1" => $orderDetail->customer_name,
                    "Attribute2" => date('d-m-Y', strtotime($cart->cart_start_date)),
                    "Attribute3" => date('h:i A', strtotime($cart->cart_start_time)),
                    "Attribute4" => 'Adults: ' . $adults . ' Children: ' . $children
                ];

                // Example: sending the request
                $homeController->postMethod($url, $postFields1);
            }
            //print_r($response); die;

            // Save session
            //Session::put('order_unique_id', $data['order_unique_id']);

            return response()->json(['success' => true, 'razorpay_payment_id' => $request['razorpay_payment_id']]);
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            $paymentDetail = $api->payment->fetch($request['razorpay_payment_id']);
            Log::error('Payment success reponse: ' . json_encode($paymentDetail->toArray()));
            return response()->json(['success' => false, 'error' => $e->getMessage() , 'razorpay_payment_id' => $request['razorpay_payment_id']]);
        }
    }

    public function paymentMembership($id) {
        try {
            $decryptId = Crypt::decrypt($id);
            $pagesDetail = Pages::where('page_id', 3)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $membershipDetail = Membership::where('membership_id', trim($decryptId))->first();
            $customerDetail = Customer::where('customer_id', Session::get('customer_id'))->first();
            return view('paymentmembership')->with(['pagesDetail'=>$pagesDetail, 'membershipDetail'=>$membershipDetail, 'customerDetail'=>$customerDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error paymentMembership: ' . $e->getMessage());
        }
    }

    public function paymentMembership_insert(Request $request) {
        try {
            $customerDetail = Customer::where('customer_id', $request->customer_id)->first();
            $membershipDetail = Membership::where('membership_id', trim($request->membership_id))->first();
            if ($membershipDetail) {
                $msorderUniqueId = 'YK-M-'.rand(100000, 999999);
                $msorderAmount = ($membershipDetail->membership_offer_price > 0) ? $membershipDetail->membership_offer_price : $membershipDetail->membership_price;
                MembershipOrder::create([
                    'msorder_unique_id' => $msorderUniqueId,
                    'customer_id' => $request->customer_id,
                    'membership_id' => $request->membership_id,
                    'msorder_date' => date('Y-m-d'),
                    'customer_name' => $customerDetail->customer_name,
                    'customer_mobile' => $customerDetail->customer_mobile,
                    'membership_title' => $membershipDetail->membership_title,
                    'membership_price' => $msorderAmount,
                    'discount_id' => Session::get('discount_id') ?? 0,
                    'discount_code' => Session::get('discount_code') ?? NULL,
                    'discount_price' => Session::get('discount') ?? 0,
                    'msorder_paid_price' => $msorderAmount - Session::get('discount'),
                    'msorder_type' => 'Razorpay',
                    'msorder_status' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $order = $api->order->create([
                    'receipt' => $msorderUniqueId,
                    'amount' => ($msorderAmount - Session::get('discount')) * 100,
                    'currency' => 'INR',
                    'payment_capture' => 1
                ]);

                return response()->json([
                    "message" => "success",
                    'msorder_unique_id' => $msorderUniqueId,
                    'order_id' => $order->id,
                    'razorpay_key' => config('services.razorpay.key'),
                    'amount' => ($msorderAmount - Session::get('discount')) * 100,
                    'name' => $membershipDetail->membership_title,
                    'description' => $membershipDetail->membership_short_desc,
                    "customer_name" => $customerDetail->customer_name,
                    "customer_email" => $customerDetail->customer_email,
                    "customer_phone" => $customerDetail->customer_mobile
                ]);
            } else {
                return response()->json(["message" => "wrong"]);
            }
        } catch (\Exception $e) {
            Log::error('Catch error paymentMembership_insert: ' . $e->getMessage());
        }
    }

    public function paymentMembership_verify(Request $request) {
        $data = $request->all();
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        try {
            Log::error('Payment verification: ' . json_encode($data));
            $attributes = [
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature']
            ];

            $api->utility->verifyPaymentSignature($attributes);

            $paymentDetail = $api->payment->fetch($data['razorpay_payment_id']);
            Log::error('Payment success reponse: ' . json_encode($paymentDetail->toArray()));
            // Payment is successful and verified
            $orderDetail = MembershipOrder::where('msorder_unique_id', $data['msorder_unique_id'])->first();
            $paymentId = Payment::create([
                'msorder_id' => $orderDetail->msorder_id,
                'ORDERID' => $orderDetail->msorder_unique_id,
                'TXNAMOUNT' => $paymentDetail->amount / 100,
                'CURRENCY' => $paymentDetail->currency,
                'TXNID' => $paymentDetail->id,
                'BANKTXNID' => $paymentDetail->acquirer_data->bank_transaction_id ?? NULL,
                'STATUS' => $paymentDetail->status,
                'RESPCODE' => 0,
                'RESPMSG' =>  NULL,
                'TXNDATE' => date('Y-m-d H:i:s'),
                'GATEWAYNAME' => NULL,
                'PAYMENTMODE' => $paymentDetail->method,
                'CHECKSUMHASH' => $data['razorpay_signature'],
                'BANKNAME' => $paymentDetail->bank ?? NULL,
                'REFERENCE' => NULL,
                'created_at' => now(),
            ])->payment_id;

            MembershipOrder::where('msorder_id', $orderDetail->msorder_id)->update([
                'msorder_start_date' => date('Y-m-d'),
                'msorder_end_date' => ($orderDetail->membership_id <> 3) ? date('Y-m-d', strtotime('+6 months')) : date('Y-m-d', strtotime('+1 year')),
                'msorder_status' => '1',
                'msorder_token' => $data['razorpay_signature'],
                'payment_id' => $paymentId,
                'payment_date' => date('Y-m-d'),
                'payment_time' => date('H:i:s')
            ]);

            // Save session
            //Session::put('order_unique_id', $data['order_unique_id']);

            return response()->json(['success' => true, 'razorpay_payment_id' => $request['razorpay_payment_id']]);
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            $paymentDetail = $api->payment->fetch($request['razorpay_payment_id']);
            Log::error('Payment success reponse: ' . json_encode($paymentDetail->toArray()));
            return response()->json(['success' => false, 'error' => $e->getMessage() , 'razorpay_payment_id' => $request['razorpay_payment_id']]);
        }
    }

    public function paymentSuccess($id) {
        try {
            Session::forget('discount');
            Session::forget('discount_text');
            Session::forget('discount_code');
            Session::forget('discount_id');

            $pagesDetail = Pages::where('page_id', 15)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $paymentDetail = Payment::where('TXNID', $id)->first();
            if(!$paymentDetail){
                return redirect()->route('404');
            }
            if ($paymentDetail->msorder_id > 0) {
                $orderDetail = MembershipOrder::where('msorder_id', $paymentDetail->msorder_id)->first();
                //send email to customer
                $this->membershipEmailTemplate($paymentDetail->msorder_id);
                return view('paymentresponse')->with(['pagesDetail'=>$pagesDetail, 'orderDetail'=>$orderDetail, 'paymentDetail'=>$paymentDetail]);
            } else {
                $orderDetail = Order::where('order_id', $paymentDetail->order_id)->first();
                $cartDetail = Cart::where(['customer_id'=>$orderDetail->customer_id, 'lounge_id'=>$orderDetail->lounge_id, 'cart_id'=>$orderDetail->cart_id, 'cart_status'=>'3'])->first();
                //send email to customer
                $this->orderEmailTemplate($paymentDetail->order_id);
                return view('paymentresponse')->with(['pagesDetail'=>$pagesDetail, 'orderDetail'=>$orderDetail, 'paymentDetail'=>$paymentDetail, 'cartDetail'=>$cartDetail]);
            }
        } catch (\Exception $e) {
            Log::error('Catch error paymentsuccess: ' . $e->getMessage());
        }
    }

    public function paymentFailed() {
        try {
            Session::forget('discount');
            Session::forget('discount_text');
            Session::forget('discount_code');
            Session::forget('discount_id');
            
            $pagesDetail = Pages::where('page_id', 15)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            return view('paymentresponse')->with(['pagesDetail'=>$pagesDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error paymentfailed: ' . $e->getMessage());
        }
    }

    public function paymentExpired() {
        try {
            $pagesDetail = Pages::where('page_id', 14)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            return view('paymentexpired')->with(['pagesDetail'=>$pagesDetail]);
        } catch (\Exception $e) {
            Log::error('Catch error paymentexpired: ' . $e->getMessage());
        }
    }

    public function membershipEmailTemplate($msorderId) {
        $orderDetail = MembershipOrder::where('msorder_id', $msorderId)->first();
        $paymentDetail = Payment::where('payment_id', $orderDetail->payment_id)->first();
        $customerDetail = Customer::where('customer_id', $orderDetail->customer_id)->first();
        $html ='<html>
                <head>
                    <meta charset="utf-8">
                    <title>Invoice</title>
                    <style type="text/css">
                        /* reset */
                        *
                        {
                            border: 0;
                            box-sizing: content-box;
                            color: inherit;
                            font-family: inherit;
                            font-size: inherit;
                            font-style: inherit;
                            font-weight: inherit;
                            line-height: inherit;
                            list-style: none;
                            margin: 0;
                            padding: 0;
                            text-decoration: none;
                            vertical-align: top;
                        }
                        p{  margin-bottom: 5px;}
                        /* content editable */
                        *[contenteditable] { border-radius: 0.25em; min-width: 1em; outline: 0; }
                        *[contenteditable] { cursor: pointer; }
                        *[contenteditable]:hover, *[contenteditable]:focus, td:hover *[contenteditable], td:focus *[contenteditable], img.hover { background: #DEF; box-shadow: 0 0 1em 0.5em #DEF; }
                        span[contenteditable] { display: inline-block; }
                        /* heading */
                        h1 { font: bold 100% sans-serif; letter-spacing: 0.5em; text-align: center; text-transform: uppercase; }
                        /* table */
                        table { font-size: 75%; table-layout: fixed; width: 100%; }
                        table { border-collapse: separate; border-spacing: 2px; }
                        th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
                        th, td { border-radius: 0.25em; border-style: solid; }
                        th { background: #EEE; border-color: #BBB; }
                        td { border-color: #DDD; }
                        /* page */
                        html { font: 16px/1 \'Open Sans\', sans-serif; overflow: auto; padding: 0.5in; }
                        html { background: #999; cursor: default; }
                        body { box-sizing: border-box; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
                        body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
                        /* header */
                        /*header { margin: 0 0 3em; }*/
                        header { margin: 0 0 0; }
                        header:after { clear: both; content: ""; display: table; }
                        header h1 { background: #0f75bc; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
                        header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
                        header address p { margin: 0 0 0.25em; }
                        header span, header img { display: block; float: left; }
                        header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
                        header img { max-height: 100%; max-width: 100%; }
                        header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }
                        /* article */
                        article, article address, table.meta, table.inventory { margin: 0 0 3em; }
                        article:after { clear: both; content: ""; display: table; }
                        article h1 { clip: rect(0 0 0 0); position: absolute; }
                        article address { float: left; font-size: 75%;}
                        /* table meta & balance */
                        table.meta, table.balance { float: right; width: 36%; }
                        table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
                        /* table meta */
                        table.meta th { width: 80%; }
                        table.meta td { width: 90%; }
                        /* table items */
                        table.inventory { clear: both; width: 100%; }
                        table.inventory th { font-weight: bold; text-align: center; }
                        table.inventory td:nth-child(1) { width: 26%; }
                        table.inventory td:nth-child(2) { width: 38%; }
                        table.inventory td:nth-child(3) { width: 12%; }
                        table.inventory td:nth-child(4) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(5) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(6) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(7) { text-align: right; width: 12%; }
                        /* table balance */
                        table.balance th, table.balance td { width: 50%; }
                        table.balance td { text-align: right; }
                        /* aside */
                        aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 1em; }
                        aside h1 { border-color: #999; border-bottom-style: solid; }
                        /* javascript */
                        .add, .cut
                        {
                            border-width: 1px;
                            display: block;
                            font-size: .8rem;
                            padding: 0.25em 0.5em;
                            float: left;
                            text-align: center;
                            width: 0.6em;
                        }
                        .add, .cut
                        {
                            background: #9AF;
                            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                            background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
                            background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
                            border-radius: 0.5em;
                            border-color: #0076A3;
                            color: #FFF;
                            cursor: pointer;
                            font-weight: bold;
                            text-shadow: 0 -1px 2px rgba(0,0,0,0.333);
                        }
                        .add { margin: -2.5em 0 0; }
                        .add:hover { background: #00ADEE; }
                        .cut { opacity: 0; position: absolute; top: 0; left: -1.5em; }
                        .cut { -webkit-transition: opacity 100ms ease-in; }
                        tr:hover .cut { opacity: 1; }
                        @media print {
                            * { -webkit-print-color-adjust: exact; }
                            html { background: none; padding: 0; }
                            body { box-shadow: none; margin: 0; }
                            span:empty { display: none; }
                            .add, .cut { display: none; }
                        }
                        @page { margin: 0; }
                    </style>
                </head>
                <body>
                    <header>
                       <h1>Membership Order Detail</h1>
                       <span><img alt="" src="https://www.yaarioke.com/public/img/logo.png"></span>
                    </header>
                    <article>
                        <!--<address contenteditable>
                            <p style="font-weight: bold; font-size:15px; margin-bottom: 8px">COMPANY INFORMATION</p>
                            <p>Healthark Wellness Solutions LLP</p>
                            <p>GST Number: 24AAIFH6271L2Z2</p>
                            <p>821, Sun Avenue One, Manekbaug Road, Ambawadi, Ahmedabad - 380015</p>
                            <p>Phone: +919316397935</p>
                            <p>Email: info@insights10.com</p>
                        </address>  -->  
                        <table class="meta">
                        <tr>
                            <th><span contenteditable>Order #</span></th>
                            <td style="text-align: right;"><span>'.$orderDetail->msorder_unique_id.'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Order Date</span></th>
                            <td style="text-align: right;"><span>'.date('d-m-Y', strtotime($orderDetail->msorder_date)).'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Payment Status</span></th>';
                        if ($orderDetail->msorder_status == 1) {
                            $html .='<td style="text-align: right;"><span></span><span>Completed</span></td>';
                        } else if ($orderDetail->msorder_status == 2) {
                            $html .='<td style="text-align: right;"><span></span><span>Failed</span></td>';
                        } else {
                            $html .='<td style="text-align: right;"><span></span><span>Pending</span></td>';
                        }
            $html .=   '</tr>
                        <tr>
                            <th><span contenteditable>Payment Method</span></th>
                            <td style="text-align: right;"><span>'.$orderDetail->msorder_type.'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Trnsaction#</span></th>
                            <td style="text-align: right;"><span>'.$paymentDetail->TXNID.'</span></td>
                        </tr>';
            $html .=   '</table>
                        <address contenteditable style="margin-left: 20px">
                            <p style="font-weight: bold; font-size:15px; margin-bottom: 8px">CUSTOMER INFORMATION</p>
                            <p>Name: '.$customerDetail->customer_name.'</p>
                            <p>Email Id: '.$customerDetail->customer_email.'</p>
                            <p>Phone: '.$customerDetail->customer_mobile.'</p>
                        </address>
    
                        <table class="inventory">
                            <thead>
                            <tr>
                                <th width="8%"><span>Sr.No</span></th>
                                <th width="30%"><span>Membership</span></th>
                                <th width="8%"><span>Quantity</span></th>
                                <th width="9%"><span>Unit Cost</span></th>
                                <th width="9%"><span>Total</span></th>
                            </tr>
                            </thead>
                            <tbody>';
            $html .=       '<tr>
                                <td><span>1</span></td>
                                <td><span>'.$orderDetail->membership_title.'</span></td>
                                <td><span>1</span></td>
                                <td style="text-align: right;"><span>₹ '.$orderDetail->membership_price.'</span></td>
                                <td style="text-align: right;"><span>₹ '.$orderDetail->membership_price.'</span></td>
                            </tr>';
            $html .=       '</tbody>
                        </table>
    
                        <table class="balance">
                            <tr>
                                <th><span contenteditable>Subtotal</span></th>
                                <td><span data-prefix></span><span>₹ '.$orderDetail->membership_price.'</span></td>
                            </tr>';
                            if ($orderDetail->discount_id > 0 && $orderDetail->discount_code!="") {
            $html .=        '<tr>
                                <th><span contenteditable>Discount ('.$orderDetail->discount_code.')</span></th>
                                <td><span data-prefix></span><span>(-) ₹ '.$orderDetail->discount_price.'</span></td>
                             </tr>';
                            }
            $html .=       '<tr>
                                <th><span contenteditable>Paid Amount</span></th>
                                <td><span data-prefix></span><span>₹ '.$orderDetail->msorder_paid_price.'</span></td>
                            </tr>
                        </table>
                    </article>
                </body>
                </html>';

        $fromEmail      = FROM_EMAIL;
        $fromName       = 'YAARIOKE';
        $SubjectUser    = "Membership Order Notification from YAARIOKE - The Karaoke Lounge";
        $Message        = $html;

        //mail sent to user
        $homeController = new HomeController();
        $homeController->sendMail($fromEmail, $customerDetail->customer_email, $fromName, ucwords(strtolower($orderDetail->customer_name)), $SubjectUser, $Message);
        $homeController->sendMail($fromEmail, PRIMARY_EMAIL, $fromName, '', $SubjectUser, $Message);
        return true;
    }

    public function orderEmailTemplate($orderId) {
        $orderDetail = Order::where('order_id', $orderId)->first();
        $paymentDetail = Payment::where('payment_id', $orderDetail->payment_id)->first();
        $customerDetail = Customer::where('customer_id', $orderDetail->customer_id)->first();
        $loungeDetail = Lounge::where('lounge_id', $orderDetail->lounge_id)->first();
        $html ='<html>
                <head>
                    <meta charset="utf-8">
                    <title>Invoice</title>
                    <style type="text/css">
                        /* reset */
                        *
                        {
                            border: 0;
                            box-sizing: content-box;
                            color: inherit;
                            font-family: inherit;
                            font-size: inherit;
                            font-style: inherit;
                            font-weight: inherit;
                            line-height: inherit;
                            list-style: none;
                            margin: 0;
                            padding: 0;
                            text-decoration: none;
                            vertical-align: top;
                        }
                        p{  margin-bottom: 5px;}
                        /* content editable */
                        *[contenteditable] { border-radius: 0.25em; min-width: 1em; outline: 0; }
                        *[contenteditable] { cursor: pointer; }
                        *[contenteditable]:hover, *[contenteditable]:focus, td:hover *[contenteditable], td:focus *[contenteditable], img.hover { background: #DEF; box-shadow: 0 0 1em 0.5em #DEF; }
                        span[contenteditable] { display: inline-block; }
                        /* heading */
                        h1 { font: bold 100% sans-serif; letter-spacing: 0.5em; text-align: center; text-transform: uppercase; }
                        /* table */
                        table { font-size: 75%; table-layout: fixed; width: 100%; }
                        table { border-collapse: separate; border-spacing: 2px; }
                        th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
                        th, td { border-radius: 0.25em; border-style: solid; }
                        th { background: #EEE; border-color: #BBB; }
                        td { border-color: #DDD; }
                        /* page */
                        html { font: 16px/1 \'Open Sans\', sans-serif; overflow: auto; padding: 0.5in; }
                        html { background: #999; cursor: default; }
                        body { box-sizing: border-box; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
                        body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
                        /* header */
                        /*header { margin: 0 0 3em; }*/
                        header { margin: 0 0 0; }
                        header:after { clear: both; content: ""; display: table; }
                        header h1 { background: #0f75bc; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
                        header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
                        header address p { margin: 0 0 0.25em; }
                        header span, header img { display: block; float: left; }
                        header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
                        header img { max-height: 100%; max-width: 100%; }
                        header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }
                        /* article */
                        article, article address, table.meta, table.inventory { margin: 0 0 3em; }
                        article:after { clear: both; content: ""; display: table; }
                        article h1 { clip: rect(0 0 0 0); position: absolute; }
                        article address { float: left; font-size: 75%;}
                        /* table meta & balance */
                        table.meta, table.balance { float: right; width: 36%; }
                        table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
                        /* table meta */
                        table.meta th { width: 80%; }
                        table.meta td { width: 90%; }
                        /* table items */
                        table.inventory { clear: both; width: 100%; }
                        table.inventory th { font-weight: bold; text-align: center; }
                        table.inventory td:nth-child(1) { width: 26%; }
                        table.inventory td:nth-child(2) { width: 38%; }
                        table.inventory td:nth-child(3) { width: 12%; }
                        table.inventory td:nth-child(4) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(5) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(6) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(7) { text-align: right; width: 12%; }
                        /* table balance */
                        table.balance th, table.balance td { width: 50%; }
                        table.balance td { text-align: right; }
                        /* aside */
                        aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 1em; }
                        aside h1 { border-color: #999; border-bottom-style: solid; }
                        /* javascript */
                        .add, .cut
                        {
                            border-width: 1px;
                            display: block;
                            font-size: .8rem;
                            padding: 0.25em 0.5em;
                            float: left;
                            text-align: center;
                            width: 0.6em;
                        }
                        .add, .cut
                        {
                            background: #9AF;
                            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                            background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
                            background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
                            border-radius: 0.5em;
                            border-color: #0076A3;
                            color: #FFF;
                            cursor: pointer;
                            font-weight: bold;
                            text-shadow: 0 -1px 2px rgba(0,0,0,0.333);
                        }
                        .add { margin: -2.5em 0 0; }
                        .add:hover { background: #00ADEE; }
                        .cut { opacity: 0; position: absolute; top: 0; left: -1.5em; }
                        .cut { -webkit-transition: opacity 100ms ease-in; }
                        tr:hover .cut { opacity: 1; }
                        @media print {
                            * { -webkit-print-color-adjust: exact; }
                            html { background: none; padding: 0; }
                            body { box-shadow: none; margin: 0; }
                            span:empty { display: none; }
                            .add, .cut { display: none; }
                        }
                        @page { margin: 0; }
                    </style>
                </head>
                <body>
                    <header>
                       <h1>Order Detail</h1>
                       <span><img alt="" src="https://www.yaarioke.com/public/img/logo.png"></span>
                    </header>
                    <article>
                        <!--<address contenteditable>
                            <p style="font-weight: bold; font-size:15px; margin-bottom: 8px">COMPANY INFORMATION</p>
                            <p>Healthark Wellness Solutions LLP</p>
                            <p>GST Number: 24AAIFH6271L2Z2</p>
                            <p>821, Sun Avenue One, Manekbaug Road, Ambawadi, Ahmedabad - 380015</p>
                            <p>Phone: +919316397935</p>
                            <p>Email: info@insights10.com</p>
                        </address>  -->  
                        <table class="meta">
                        <tr>
                            <th><span contenteditable>Order #</span></th>
                            <td style="text-align: right;"><span>'.$orderDetail->order_unique_id.'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Order Date</span></th>
                            <td style="text-align: right;"><span>'.date('d-m-Y', strtotime($orderDetail->order_date)).'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Payment Status</span></th>';
        if ($orderDetail->order_status == 1) {
            $html .='<td style="text-align: right;"><span></span><span>Completed</span></td>';
        } else if ($orderDetail->order_status == 2) {
            $html .='<td style="text-align: right;"><span></span><span>Failed</span></td>';
        } else {
            $html .='<td style="text-align: right;"><span></span><span>Pending</span></td>';
        }
        $html .=   '</tr>
                        <tr>
                            <th><span contenteditable>Payment Method</span></th>
                            <td style="text-align: right;"><span>'.$orderDetail->order_type.'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Trnsaction#</span></th>
                            <td style="text-align: right;"><span>'.$paymentDetail->TXNID.'</span></td>
                        </tr>';
        $html .=   '</table>
                        <address contenteditable style="margin-left: 20px">
                            <p style="font-weight: bold; font-size:15px; margin-bottom: 8px">CUSTOMER INFORMATION</p>
                            <p>Name: '.$customerDetail->customer_name.'</p>
                            <p>Email Id: '.$customerDetail->customer_email.'</p>
                            <p>Phone: '.$customerDetail->customer_mobile.'</p>
                        </address>
    
                        <table class="inventory">
                            <thead>
                            <tr>
                                <th width="8%"><span>Sr.No</span></th>
                                <th width="30%"><span>Lounge</span></th>
                                <th width="8%"><span>Quantity</span></th>
                                <th width="9%"><span>Unit Cost</span></th>
                                <th width="9%"><span>Total</span></th>
                            </tr>
                            </thead>
                            <tbody>';
        $html .=       '<tr>
                                <td><span>1</span></td>
                                <td><span>'.$loungeDetail->lounge_name.'</span></td>
                                <td><span>1</span></td>
                                <td style="text-align: right;"><span>₹ '.($orderDetail->discount_price + $orderDetail->membership_discount + $orderDetail->order_paid_price).'</span></td>
                                <td style="text-align: right;"><span>₹ '.($orderDetail->discount_price + $orderDetail->membership_discount + $orderDetail->order_paid_price).'</span></td>
                            </tr>';
        $html .=       '</tbody>
                        </table>
    
                        <table class="balance">
                            <tr>
                                <th><span contenteditable>Subtotal</span></th>
                                <td><span data-prefix></span><span>₹ '.($orderDetail->discount_price + $orderDetail->membership_discount + $orderDetail->order_paid_price).'</span></td>
                            </tr>';
        if ($orderDetail->membership_id > 0 && $orderDetail->membership_discount!="") {
            $html .=        '<tr>
                                <th><span contenteditable>Membership Discount</span></th>
                                <td><span data-prefix></span><span>(-) ₹ '.$orderDetail->membership_discount.'</span></td>
                             </tr>';
        }
        if ($orderDetail->discount_id > 0 && $orderDetail->discount_code!="") {
            $html .=        '<tr>
                                <th><span contenteditable>Discount ('.$orderDetail->discount_code.')</span></th>
                                <td><span data-prefix></span><span>(-) ₹ '.$orderDetail->discount_price.'</span></td>
                             </tr>';
        }
        $html .=       '<tr>
                                <th><span contenteditable>Paid Amount</span></th>
                                <td><span data-prefix></span><span>₹ '.$orderDetail->order_paid_price.'</span></td>
                            </tr>
                        </table>
                    </article>
                </body>
                </html>';

        $fromEmail      = FROM_EMAIL;
        $fromName       = 'YAARIOKE';
        $SubjectUser    = "Order Notification from YAARIOKE - The Karaoke Lounge";
        $Message        = $html;

        //mail sent to user
        $homeController = new HomeController();
        $homeController->sendMail($fromEmail, $customerDetail->customer_email, $fromName, ucwords(strtolower($orderDetail->customer_name)), $SubjectUser, $Message);
        $homeController->sendMail($fromEmail, PRIMARY_EMAIL, $fromName, '', $SubjectUser, $Message);
        return true;
    }

    public function orderRescheduleEmailTemplate($orderId) {
        $orderDetail = Order::where('order_id', $orderId)->first();
        $cartDetail = Cart::where('cart_id', $orderDetail->cart_id)->first();
        $cartlogDetail = CartLog::where('cart_id', $orderDetail->cart_id)->first();
        $paymentDetail = Payment::where('payment_id', $orderDetail->payment_id)->first();
        $customerDetail = Customer::where('customer_id', $orderDetail->customer_id)->first();
        $loungeDetail = Lounge::where('lounge_id', $orderDetail->lounge_id)->first();
        $html ='<html>
                <head>
                    <meta charset="utf-8">
                    <title>Invoice</title>
                    <style type="text/css">
                        /* reset */
                        *
                        {
                            border: 0;
                            box-sizing: content-box;
                            color: inherit;
                            font-family: inherit;
                            font-size: inherit;
                            font-style: inherit;
                            font-weight: inherit;
                            line-height: inherit;
                            list-style: none;
                            margin: 0;
                            padding: 0;
                            text-decoration: none;
                            vertical-align: top;
                        }
                        p{  margin-bottom: 5px;}
                        /* content editable */
                        *[contenteditable] { border-radius: 0.25em; min-width: 1em; outline: 0; }
                        *[contenteditable] { cursor: pointer; }
                        *[contenteditable]:hover, *[contenteditable]:focus, td:hover *[contenteditable], td:focus *[contenteditable], img.hover { background: #DEF; box-shadow: 0 0 1em 0.5em #DEF; }
                        span[contenteditable] { display: inline-block; }
                        /* heading */
                        h1 { font: bold 100% sans-serif; letter-spacing: 0.5em; text-align: center; text-transform: uppercase; }
                        /* table */
                        table { font-size: 75%; table-layout: fixed; width: 100%; }
                        table { border-collapse: separate; border-spacing: 2px; }
                        th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
                        th, td { border-radius: 0.25em; border-style: solid; }
                        th { background: #EEE; border-color: #BBB; }
                        td { border-color: #DDD; }
                        /* page */
                        html { font: 16px/1 \'Open Sans\', sans-serif; overflow: auto; padding: 0.5in; }
                        html { background: #999; cursor: default; }
                        body { box-sizing: border-box; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
                        body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
                        /* header */
                        /*header { margin: 0 0 3em; }*/
                        header { margin: 0 0 0; }
                        header:after { clear: both; content: ""; display: table; }
                        header h1 { background: #0f75bc; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
                        header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
                        header address p { margin: 0 0 0.25em; }
                        header span, header img { display: block; float: left; }
                        header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
                        header img { max-height: 100%; max-width: 100%; }
                        header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }
                        /* article */
                        article, article address, table.meta, table.inventory { margin: 0 0 3em; }
                        article:after { clear: both; content: ""; display: table; }
                        article h1 { clip: rect(0 0 0 0); position: absolute; }
                        article address { float: left; font-size: 75%;}
                        /* table meta & balance */
                        table.meta, table.balance { float: right; width: 36%; }
                        table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
                        /* table meta */
                        table.meta th { width: 80%; }
                        table.meta td { width: 90%; }
                        /* table items */
                        table.inventory { clear: both; width: 100%; }
                        table.inventory th { font-weight: bold; text-align: center; }
                        table.inventory td:nth-child(1) { width: 26%; }
                        table.inventory td:nth-child(2) { width: 38%; }
                        table.inventory td:nth-child(3) { width: 12%; }
                        table.inventory td:nth-child(4) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(5) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(6) { text-align: right; width: 12%; }
                        table.inventory td:nth-child(7) { text-align: right; width: 12%; }
                        /* table balance */
                        table.balance th, table.balance td { width: 50%; }
                        table.balance td { text-align: right; }
                        /* aside */
                        aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 1em; }
                        aside h1 { border-color: #999; border-bottom-style: solid; }
                        /* javascript */
                        .add, .cut
                        {
                            border-width: 1px;
                            display: block;
                            font-size: .8rem;
                            padding: 0.25em 0.5em;
                            float: left;
                            text-align: center;
                            width: 0.6em;
                        }
                        .add, .cut
                        {
                            background: #9AF;
                            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                            background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
                            background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
                            border-radius: 0.5em;
                            border-color: #0076A3;
                            color: #FFF;
                            cursor: pointer;
                            font-weight: bold;
                            text-shadow: 0 -1px 2px rgba(0,0,0,0.333);
                        }
                        .add { margin: -2.5em 0 0; }
                        .add:hover { background: #00ADEE; }
                        .cut { opacity: 0; position: absolute; top: 0; left: -1.5em; }
                        .cut { -webkit-transition: opacity 100ms ease-in; }
                        tr:hover .cut { opacity: 1; }
                        @media print {
                            * { -webkit-print-color-adjust: exact; }
                            html { background: none; padding: 0; }
                            body { box-shadow: none; margin: 0; }
                            span:empty { display: none; }
                            .add, .cut { display: none; }
                        }
                        @page { margin: 0; }
                    </style>
                </head>
                <body>
                    <header>
                       <h1 style="background-color: #af0ee3 !important;">Booking Reschedule Detail</h1>
                       <span><img alt="" src="https://www.yaarioke.com/public/img/logo.png"></span>
                    </header>
                    <article>
                        <!--<address contenteditable>
                            <p style="font-weight: bold; font-size:15px; margin-bottom: 8px">COMPANY INFORMATION</p>
                            <p>Healthark Wellness Solutions LLP</p>
                            <p>GST Number: 24AAIFH6271L2Z2</p>
                            <p>821, Sun Avenue One, Manekbaug Road, Ambawadi, Ahmedabad - 380015</p>
                            <p>Phone: +919316397935</p>
                            <p>Email: info@insights10.com</p>
                        </address>  -->  
                        <table class="meta">
                        <tr>
                            <th><span contenteditable>Order #</span></th>
                            <td style="text-align: right;"><span>'.$orderDetail->order_unique_id.'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Order Date</span></th>
                            <td style="text-align: right;"><span>'.date('d-m-Y', strtotime($orderDetail->order_date)).'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Payment Status</span></th>';
        if ($orderDetail->order_status == 1) {
            $html .='<td style="text-align: right;"><span></span><span>Completed</span></td>';
        } else if ($orderDetail->order_status == 2) {
            $html .='<td style="text-align: right;"><span></span><span>Failed</span></td>';
        } else {
            $html .='<td style="text-align: right;"><span></span><span>Pending</span></td>';
        }
        $html .=   '</tr>
                        <tr>
                            <th><span contenteditable>Payment Method</span></th>
                            <td style="text-align: right;"><span>'.$orderDetail->order_type.'</span></td>
                        </tr>
                        <tr>
                            <th><span contenteditable>Trnsaction#</span></th>
                            <td style="text-align: right;"><span>'.$paymentDetail->TXNID.'</span></td>
                        </tr>';
        $html .=   '</table>
                        <address contenteditable style="margin-left: 20px">
                            <p style="font-weight: bold; font-size:15px; margin-bottom: 8px">CUSTOMER INFORMATION</p>
                            <p>Name: '.$customerDetail->customer_name.'</p>
                            <p>Email Id: '.$customerDetail->customer_email.'</p>
                            <p>Phone: '.$customerDetail->customer_mobile.'</p>
                        </address>
    
                        <table class="inventory">
                            <thead>
                            <tr>
                                <th width="50%"><span>New Booking Detail</span></th>
                                <th width="50%"><span>Old Booking Detail</span></th>
                            </tr>
                            </thead>
                            <tbody>';
        $html .=            '<tr>
                                <td><span>'.date('d, M Y', strtotime($cartlogDetail->clog_start_date)).'<br>'.str_replace(',', ', ', $cartlogDetail->clog_duration).'</span></td>
                                <td><span><del>'.date('d, M Y', strtotime($cartDetail->cart_start_date)).'<br>'.str_replace(',', ', ', $cartDetail->cart_duration).'</del></span></td>
                            </tr>';
        $html .=       '</tbody>
                        </table>
                    </article>
                </body>
                </html>';

        $fromEmail      = FROM_EMAIL;
        $fromName       = 'YAARIOKE';
        $SubjectUser    = "Booking Reschedule Notification from YAARIOKE - The Karaoke Lounge";
        $Message        = $html;

        //mail sent to user
        $homeController = new HomeController();
        $homeController->sendMail($fromEmail, $customerDetail->customer_email, $fromName, ucwords(strtolower($orderDetail->customer_name)), $SubjectUser, $Message);
        //$homeController->sendMail($fromEmail, PRIMARY_EMAIL, $fromName, '', $SubjectUser, $Message);
        return true;
    }
}