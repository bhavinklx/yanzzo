<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Franchise;
use App\Models\Lounge;
use App\Models\LoungeTime;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\CartLog;
use App\Models\Discount;
use App\Models\Membership;
use App\Models\MembershipOrder;
use App\Models\Order;
use App\Models\Payment;
use Validator;
use Session;
use DataTables;
use Carbon\Carbon;
use DateTime;
//use App\Exports\ContactExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:customer-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:customer-delete', ['only' => ['delete']]);
    }

    public function create()
    {
        // Logged in user
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        if ($role == 'Super Admin') {
            $loungeDetail = Lounge::where('lounge_status', '1')->orderBy("lounge_order")->get();
        } else {
            $franchise = Franchise::where('franchise_id', $user->franchise_id)->first();
            $loungeId = [];
            if ($franchise && $franchise->lounge_id) {
                $loungeId = explode(',', $franchise->lounge_id); // convert CSV to array
            }
            $loungeDetail = Lounge::whereIn('lounge_id', $loungeId)->orderBy("lounge_order")->get();
        }
        return view("admin.customer.create", compact('loungeDetail'));
    }


    public function view()
    {
        return view("admin.customer.list");
    }

    public function load_table(Request $request)
    {
        $customerDetail = Customer::orderBy("customer_order", "DESC")->get();
        return DataTables::of($customerDetail)
            ->editColumn("checkbox", function ($customer){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$customer->customer_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($customer){
                return $customer->customer_name;
            })
            ->editColumn("email", function ($customer){
                return $customer->customer_email;
            })
            ->editColumn("mobile", function ($customer){
                return $customer->customer_mobile;
            })
            ->editColumn("date", function ($customer){
                return date('d-m-Y h:i:s A', strtotime($customer->created_at));
            })
            ->editColumn("login_date", function ($customer){
                if (!empty($customer->customer_last_login_date)) {
                    return date('d-m-Y h:i:s A', strtotime($customer->customer_last_login_date));
                } else {
                    return '--';
                }
            })
            ->editColumn("ip", function ($customer){
                return $customer->customer_last_login_ip;
            })
            ->editColumn("membership", function ($customer){
                $membershipNameArray = [];
                $membershipDetail = Membership::get()->toArray();
                for ($m=0; $m < count($membershipDetail); $m++) {
                    $membershipNameArray[$membershipDetail[$m]['membership_id']] = $membershipDetail[$m]['membership_title'];
                }
                $morderDetail = MembershipOrder::where('customer_id', $customer->customer_id)->where('msorder_status', '1')->where('msorder_end_date', '>=', date('Y-m-d'))->orderBy("msorder_id", "DESC")->first();
                $style      = "";
                $class      = "";
                if (isset($morderDetail) && $morderDetail->membership_id==1) {
                    $class  = "label-inverse";
                    $title  = $membershipNameArray[$morderDetail->membership_id];
                } else if (isset($morderDetail) && $morderDetail->membership_id==2) {
                    $class  = "label-primary";
                    $title  = $membershipNameArray[$morderDetail->membership_id];
                } else if (isset($morderDetail) && $morderDetail->membership_id==3) {
                    $style  = 'style="background-color: #e83e8c !important"';
                    $title  = $membershipNameArray[$morderDetail->membership_id];
                }
                if (!empty($title)) {
                    return '<div class="label label-table text-center '.$class.'" '.$style.'>'.$title.'</div><br><span style="font-size: 10px;">Expire on ' . date('d-m-Y', strtotime($morderDetail->msorder_end_date)).'</span>';   
                } else {
                    return '--';
                }
            })
            ->editColumn("status", function ($customer){
                if ($customer->customer_status == '1') {
                    return '<span id="td_status_'.$customer->customer_id.'"><a href="javascript:void(0)" onclick="change_status('.$customer->customer_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$customer->customer_id.'"><a href="javascript:void(0)" onclick="change_status('.$customer->customer_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("action", function ($customer){
                $action = "";
                if (auth()->user()->can('customer-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $customer->customer_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($customer) {
                    return $customer->customer_id;
                }
            ])
            ->rawColumns(["checkbox", "membership", "status", "action"])
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
            Customer::where("customer_id", $request->customer_id)->update(['customer_status' => $request->status]);
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
        foreach ($request->order as $order)
        {
            Customer::where("customer_id", $order["customer_id"])->update(["customer_order" => $order["position"]]);
        }
        echo 'User order changed successfully.';
    }

    public function delete(Request $request)
    {
        Customer::where("customer_id", $request->customer_id)->delete();
    }

    public function export()
    {
        // Store on a different disk (e.g. s3)
        //Excel::store(new ContactExport(), 'contact.xlsx', 'public');
        return Excel::download(new ContactExport(), 'contact.xlsx');
    }

    public function search_customer(Request $request)
    {
        try {
            if (!$request->ajax())
            {
                exit('No direct script access allowed');
            }
            if (!empty($request->all()))
            {
                $customer = Customer::where('customer_mobile', $request->mobile)->latest()->first();
                return response()->json(["message" => "success", "customer_id" => $customer->customer_id, "customer_mobile" => $customer->customer_mobile]);
            }
        } catch (\Exception $e) {
            Log::error('Catch error resend_otp: ' . $e->getMessage());
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

    public function customerLounge_insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lounge_id' => ['required', 'not_in:0'],
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            /*'duration' => 'required|integer|min:1',*/
            'adults' => 'required|integer|min:2',
            'method' => 'required|in:card,upi,netbanking',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $timeSlots = $timeRanges = $conditions = $ltimeDetail = $cartArray = [];
            $cartAmount = $membershipDiscount = 0;
            $membershipDetail = null;

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
                    'cart_adults' => $request->adults ?? 0,
                    'cart_children' => $request->children ?? 0,
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
                    'cart_adults' => $request->adults ?? 0,
                    'cart_children' => $request->children ?? 0,
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
            $orderUniqueId = 'YK-M-'.rand(100000, 999999);
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
            }

            $orderDetail = Order::where('order_unique_id', $orderUniqueId)->first();

            $paymentId = Payment::create([
                'order_id' => $orderDetail->order_id,
                'ORDERID' => $orderDetail->order_unique_id,
                'TXNAMOUNT' => $orderDetail->order_paid_price / 100,
                'CURRENCY' => 'INR',
                'TXNID' => 'pay_'.time(),
                'BANKTXNID' => NULL,
                'STATUS' => 'captured',
                'RESPCODE' => 0,
                'RESPMSG' =>  NULL,
                'TXNDATE' => date('Y-m-d H:i:s'),
                'GATEWAYNAME' => NULL,
                'PAYMENTMODE' => $request->method . ' (offline)',
                'CHECKSUMHASH' => NULL,
                'BANKNAME' => NULL,
                'REFERENCE' => NULL,
                'created_at' => now(),
            ])->payment_id;

            $cart = Cart::where([
                'lounge_id' => $orderDetail->lounge_id,
                'customer_id' => $request->customer_id,
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
                'order_token' => NULL,
                'payment_id' => $paymentId,
                'payment_date' => date('Y-m-d'),
                'payment_time' => date('H:i:s')
            ]);

            //send email to customer
            $checkoutControllerObj = new CheckoutController();
            $checkoutControllerObj->orderEmailTemplate($orderDetail->order_id);

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

            return response()->json(['success' => true, 'redirect_url' => 'customer-add']);
        }
    }
}
