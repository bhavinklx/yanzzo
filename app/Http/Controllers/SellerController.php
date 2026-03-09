<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\State;
use App\Models\City;
use App\Models\Pimage;
use App\Models\Pages;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $settingDetail = Setting::get()->toArray();
        for ($s=0; $s < count($settingDetail); $s++) {
            if (!defined($settingDetail[$s]['setting_name'])) {
                define($settingDetail[$s]['setting_name'], $settingDetail[$s]['setting_value']);
            }
        }
    }

    public function index()
    {
        try {
            $pagesDetail = Pages::where('page_id', 11)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
            $categoryDetail = Category::where(["category_status" => "1", "category_parent" => "0"])->get();
            $stateDetail = State::where("country_id", 101)->where("state_status", "1")->orderBy("state_name")->get();
            $customerDetail = Customer::where('customer_id', Session::get('customer_id'))->first();
            
            return view("seller", compact('categoryDetail', 'stateDetail', 'pagesDetail', 'customerDetail'));
        } catch (\Exception $e) {
            return back()->with('failedMsg', 'Error loading page: ' . $e->getMessage());
        }
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "category_id"                       => "required|not_in:0",
            "subcategory_id"                    => "required|not_in:0",
            "state_id"                          => "required|not_in:0",
            "city_id"                           => "required|not_in:0",
            "product_title"                     => "required|string|max:255",
            "product_brand"                     => "required|string|max:255",
            "product_model"                     => "required|string|max:255",
            "product_price"                     => "required|numeric",
            "product_short_desc"                => "required|string",
            "product_desc"                      => "required|string",
            "product_specification"             => "required|string",
            "product_images"                    => "required|array|min:1"
        ], [
            "product_images.required"           => "Please upload at least one machine photo."
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validation-error', 'data' => $validator->errors()]);
        }

        try {
            $product                            = new Product();
            $product_title                      = $request->product_title;
            $lastOrder                          = Product::orderBy("product_order", "DESC")->first();
            
            $product->fill([
                'customer_id'                   => Session::get('customer_id'),
                'category_id'                   => $request->category_id,
                'subcategory_id'                => $request->subcategory_id,
                'state_id'                      => $request->state_id,
                'city_id'                       => $request->city_id,
                'product_title'                 => $product_title,
                'product_slug'                  => $this->generateUniqueSlug($product_title),
                'product_date'                  => $request->product_date ? date('Y-m-d', strtotime($request->product_date)) : date('Y-m-d'),
                'product_short_desc'            => addslashes($request->product_short_desc),
                'product_desc'                  => $request->product_desc,
                'product_specification'         => $request->product_specification,
                'product_price'                 => $request->product_price,
                'product_brand'                 => $request->product_brand,
                'product_model'                 => $request->product_model,
                'product_location'              => $request->product_location,
                'product_meta_title'            => $product_title,
                'product_meta_keyword'          => $product_title,
                'product_meta_desc'             => substr(strip_tags($request->product_desc), 0, 160),
                'product_listing_id'            => $this->generateUniqueListingId(),
                'product_order'                 => (!empty($lastOrder)) ? $lastOrder->product_order + 1 : 1,
                'product_status'                => '0', // Pending approval
                'created_at'                    => date('Y-m-d H:i:s')
            ]);

            $product->save();

            if ($request->product_images) {
                foreach ($request->product_images as $image) {
                    if ($image != "") {
                        $pimage                 = new Pimage();
                        $pimage->product_id     = $product->product_id;
                        $pimage->pimage_image   = $image;
                        $pimage->created_at     = date('Y-m-d H:i:s');
                        $pimage->save();
                    }
                }
            }

            Session::flash('successMsg', 'Machine listing submitted successfully and pending approval.');
            return response()->json(['status' => 'success', 'redirect_url' => url('/my-account')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function getSubcategory(Request $request)
    {
        $subcategory = Category::where(["category_status" => "1", "category_parent" => $request->category_id])->get();
        return response()->json($subcategory);
    }

    public function getCity(Request $request)
    {
        $cities = City::where(["state_id" => $request->state_id, "city_status" => "1"])->orderBy("city_name")->get();
        return response()->json($cities);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // Increased limit for machine photos
        ]);

        $filename = $this->storeImage($request->file('file'));

        return response()->json([
            'filename' => $filename
        ]);
    }

    protected function storeImage($file)
    {
        $filename = 'IMG-' . time() . '-' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/product'), $filename);
        return $filename;
    }

    public function deleteImage(Request $request)
    {
        if ($request->filename) {
            $this->removeFile($request->filename);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    private function removeFile($filename)
    {
        if ($filename != '' && file_exists(public_path('/uploads/product/' . $filename))) {
            @unlink(public_path('/uploads/product/' . $filename));
        }
    }

    private function generateUniqueListingId()
    {
        do {
            $listingId = 'YANZZO' . mt_rand(100000, 999999);
            $exists = Product::where('product_listing_id', $listingId)->exists();
        } while ($exists);

        return $listingId;
    }

    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $allSlugs = Product::select('product_slug')
            ->where('product_slug', 'like', $slug . '%')
            ->get();

        if (! $allSlugs->contains('product_slug', $slug)) {
            return $slug;
        }

        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug . '-' . $i;
            if (! $allSlugs->contains('product_slug', $newSlug)) {
                return $newSlug;
            }
        }
        return $slug . '-' . time();
    }
}
