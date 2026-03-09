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

        // Fetch All Customer monthly data
        $customers = Customer::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('total', 'month')
            ->toArray();
 
        // Fetch Product monthly data
        $products = Product::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('total', 'month')
            ->toArray();
 
        // Prepare 12 months data
        $customerData = $productData = [];
        for ($i = 1; $i <= 12; $i++) {
            $customerData[] = $customers[$i] ?? 0;
            $productData[] = $products[$i] ?? 0;
        }

        // Fetch counts for dashboard stats
        $totalBanner = Banner::count();
        $totalPages = Pages::count();
        $totalBlog = Blog::count();
        $totalTestimonial= Testimonial::count();
        $totalSponsor = Sponsor::count();
        $totalContact = Contact::count();

        return view("admin.dashboard.dashboard", compact(
            'customerData', 
            'productData',
            'totalBanner',
            'totalPages',
            'totalBlog',
            'totalTestimonial',
            'totalSponsor',
            'totalContact'
        ));
    }
}
