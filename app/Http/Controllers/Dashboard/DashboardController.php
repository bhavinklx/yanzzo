<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\Pages;
use App\Models\Contact;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $totalUser = User::count();
        $totalCustomer = Customer::count();
        $totalBanner = Banner::count();
        $totalBlog = Blog::count();
        $totalTestimonial = Testimonial::count();
        $totalPage = Pages::count();
        $totalContact = Contact::count();
        return view("admin.dashboard.dashboard")->with([
            'totalUser' => $totalUser,
            'totalCustomer' => $totalCustomer,
            'totalBanner' => $totalBanner,
            'totalBlog' => $totalBlog,
            'totalTestimonial' => $totalTestimonial,
            'totalPage' => $totalPage,
            'totalContact' => $totalContact
        ]);
    }
}
