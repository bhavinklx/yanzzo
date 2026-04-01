<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Cities;
use App\Models\Testimonial;
use App\Models\Pages;
use App\Models\Membership;
use App\Models\Lounge;
use App\Models\Franchise;
use App\Models\Contact;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Sponsor;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $year = Carbon::now()->year;
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(15);
        $endDate = $today->copy()->addDays(15);

        // Fetch Daily Customer data for the range needed (including 6 extra days for moving average calculation)
        $fetchStartDate = $startDate->copy()->subDays(6);
        $customersRaw = Customer::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw("COUNT(*) as total")
        )
            ->whereBetween('created_at', [$fetchStartDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->groupBy(DB::raw("DATE(created_at)"))
            ->pluck('total', 'date')
            ->toArray();

        $customerLabels = [];
        $customerDaily = [];
        $movingAverage = [];

        // Loop through the 31-day window (-15 to +15)
        for ($i = -15; $i <= 15; $i++) {
            $currentDate = $today->copy()->addDays($i);
            $dateStr = $currentDate->format('Y-m-d');
            
            if ($i == 0) {
                $label = "Today";
            } else {
                $label = $currentDate->format('d M');
            }
            
            $customerLabels[] = $label;
            $count = $customersRaw[$dateStr] ?? 0;
            $customerDaily[] = $count;

            // Calculate 7-day moving average
            $sum = 0;
            for ($j = 0; $j < 7; $j++) {
                $checkDate = $currentDate->copy()->subDays($j)->format('Y-m-d');
                $sum += $customersRaw[$checkDate] ?? 0;
            }
            $movingAverage[] = round($sum / 7, 2);
        }

        // Fetch Daily Product data for the range needed (including 6 extra days for moving average calculation)
        $productsRaw = Product::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw("COUNT(*) as total")
        )
            ->whereBetween('created_at', [$fetchStartDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->groupBy(DB::raw("DATE(created_at)"))
            ->pluck('total', 'date')
            ->toArray();

        $productDaily = [];
        $productMovingAverage = [];

        // Loop through the 31-day window (-15 to +15)
        for ($i = -15; $i <= 15; $i++) {
            $currentDate = $today->copy()->addDays($i);
            $dateStr = $currentDate->format('Y-m-d');
            
            $count = $productsRaw[$dateStr] ?? 0;
            $productDaily[] = $count;

            // Calculate 7-day moving average
            $sum = 0;
            for ($j = 0; $j < 7; $j++) {
                $checkDate = $currentDate->copy()->subDays($j)->format('Y-m-d');
                $sum += $productsRaw[$checkDate] ?? 0;
            }
            $productMovingAverage[] = round($sum / 7, 2);
        }

        // Fetch counts for dashboard stats
        $totalBanner = Banner::count();
        $totalPages = Pages::count();
        $totalBlog = Blog::count();
        $totalTestimonial= Testimonial::count();
        $totalSponsor = Sponsor::count();
        $totalContact = Contact::count();

        return view("admin.dashboard.dashboard", compact(
            'customerDaily',
            'movingAverage',
            'productDaily',
            'productMovingAverage',
            'customerLabels',
            'totalBanner',
            'totalPages',
            'totalBlog',
            'totalTestimonial',
            'totalSponsor',
            'totalContact'
        ));
    }
}
