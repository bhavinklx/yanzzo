<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\State;
use App\Models\City;
use App\Models\Pimage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }

    public function createSlug(Request $request)
    {
        $slug = Str::slug($request->product_title);
        $allSlugs = $this->checkSlug($slug);

        if (! $allSlugs->contains('product_slug', $slug)) {
            return response()->json(['slug' => $slug]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (! $allSlugs->contains('product_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }
        return response()->json(['error' => 'Unable to generate unique slug'], 422);
    }

    protected function checkSlug($slug)
    {
        return Product::select('product_slug')
            ->where('product_slug', 'like', $slug . '%')
            ->get();
    }

    public function create()
    {
        $categoryDetail = Category::where(["category_status" => "1", "category_parent" => "0"])->get();
        $customerDetail = Customer::where("customer_status", "1")->get();
        $stateDetail = State::where("country_id", 101)->where("state_status", "1")->orderBy("state_name")->get();
        return view("admin.product.create", compact('categoryDetail', 'customerDetail', 'stateDetail'));
    }

    public function insert(Request $request)
    {
        $this->validateData($request);

        $product = new Product();
        $this->saveUpdateData($product, $request);

        Session::flash('successMsg', 'Product added successfully');
        return response()->json(['redirect_url' => route('product-list')]);
    }
    
    public function edit($id)
    {
        $productDetail = Product::find($id);
        $categoryDetail = Category::where(["category_status" => "1", "category_parent" => "0"])->get();
        $subcategoryDetail = Category::where(["category_status" => "1", "category_parent" => $productDetail->category_id])->get();
        $customerDetail = Customer::where("customer_status", "1")->get();
        $stateDetail = State::where("country_id", 101)->where("state_status", "1")->orderBy("state_name")->get();
        $cityDetail = $productDetail->state_id ? City::where(["state_id" => $productDetail->state_id, "city_status" => "1"])->orderBy("city_name")->get() : collect();
        return view("admin.product.edit", compact('productDetail', 'categoryDetail', 'subcategoryDetail', 'customerDetail', 'stateDetail', 'cityDetail'));
    }

    public function update(Request $request)
    {
        $this->validateData($request);

        $product = Product::findOrFail($request->product_id);
        $this->saveUpdateData($product, $request, true);

        Session::flash('successMsg', 'Product updated successfully');
        return response()->json(['redirect_url' => route('product-list')]);
    }

    public function view()
    {
        return view("admin.product.list");
    }

    public function load_table(Request $request)
    {
        $productDetail = Product::orderBy("product_order", "DESC");
        return DataTables::of($productDetail)
            ->editColumn("checkbox", function ($product){
                return '<div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="' . $product->product_id . '"> </div>';
            })
            ->editColumn("title", function ($product){
                return $product->product_title;
            })
            ->editColumn("price", function ($product){
                return $product->product_price;
            })
            ->editColumn("date", function ($product){
                return date('d-m-Y h:i:s A', strtotime($product->created_at));
            })
            ->editColumn("status", function ($product) {
                if ($product->product_status == '1') {
                    return '<div id="td_status_' . $product->product_id . '"><a href="javascript:void(0)" onclick="change_status(' . $product->product_id . ',0)" ><span class="badge bg-success">Active</span></a></div>';
                } else {
                    return '<div id="td_status_' . $product->product_id . '"><a href="javascript:void(0)" onclick="change_status(' . $product->product_id . ',1)" ><span class="badge bg-danger">Inactive</span></a></div>';
                }
            })
            ->editColumn("sold_status", function ($product) {
                if ($product->product_is_sold == '1') {
                    return '<span class="badge badge-danger-bg">Sold</span>';
                } else {
                    return '<span class="badge badge-success-light-bg">Available</span>';
                }
            })
            ->editColumn("action", function ($product){
                $action = '<div class="d-inline-flex gap-1">';
                if (auth()->user()->can('product-delete')) {
                    $action.= '<button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal(' . $product->product_id . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Product"> <i class="ri-delete-bin-line"></i> </button>';
                }
                if (auth()->user()->can('product-edit')) {
                    $action.= '<a href="'.route("product-edit", ['id' => $product->product_id]).'" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Product"> <i class="ri-edit-box-line"></i> </a>';
                }
                $action.= '</div>';
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "id" => function ($product) {
                    return 'row_' . $product->product_id;
                },
                "data-id" => function ($product) {
                    return $product->product_id;
                }
            ])
            ->rawColumns(["checkbox", "status", "sold_status", "action"])
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
            Product::where("product_id", $request->product_id)->update(["product_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function update_order(Request $request)
    {
        foreach ($request->order as $order) {
            Product::where("product_id", $order["product_id"])->update(["product_order" => $order["position"]]);
        }
        echo 'Product order changed successfully.';
    }

    public function delete(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        // Delete associated images
        foreach ($product->pimages as $pimage) {
            $this->removeFile($pimage->pimage_image);
            $pimage->delete();
        }

        $product->delete();
        return response('Product deleted successfully.');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            "product_title"             => 'required|string|max:255',
            "product_slug"              => 'required|string|max:255',
            "category_id"               => "required|not_in:0",
            "subcategory_id"            => "required|not_in:0",
            "product_price"             => "required",
        ]);
    }

    private function saveUpdateData(Product $product, Request $request, $isUpdate = false)
    {
        if ($isUpdate) {
            $product->updated_at        = date('Y-m-d H:i:s');
        } else {
            $lastOrder                  = Product::orderBy("product_order", "DESC")->first();
            $product->product_order     = (!empty($lastOrder)) ? $lastOrder->product_order + 1 : 1;
            $product->created_at        = date('Y-m-d H:i:s');
            $product->product_listing_id = $this->generateUniqueListingId();
        }

        $product->fill([
            'customer_id'               => $request->customer_id,
            'category_id'               => $request->category_id,
            'subcategory_id'            => $request->subcategory_id,
            'state_id'                  => $request->state_id,
            'city_id'                   => $request->city_id,
            'product_title'             => $request->product_title,
            'product_slug'              => $request->product_slug,
            'product_date'              => $request->product_date ? date('Y-m-d', strtotime($request->product_date)) : date('Y-m-d'),
            'product_short_desc'        => addslashes($request->product_short_desc),
            'product_desc'              => $request->product_desc,
            'product_specification'     => $request->product_specification,
            'product_price'             => $request->product_price,
            'product_brand'             => $request->product_brand,
            'product_model'             => $request->product_model,
            'product_location'          => $request->product_location,
            'product_meta_title'        => $request->product_meta_title,
            'product_meta_keyword'      => $request->product_meta_keyword,
            'product_meta_desc'         => $request->product_meta_desc,
            'product_listing_id'        => $product->product_listing_id,
            'product_status'            => '1'
        ]);

        $product->save();
        if ($request->product_images) {
            foreach ($request->product_images as $image) {
                $pimage                 = new Pimage();
                $pimage->product_id     = $product->product_id;
                $pimage->pimage_image   = $image;
                $pimage->created_at     = date('Y-m-d H:i:s');
                $pimage->save();
            }
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
        $pimage = Pimage::where('pimage_id', $request->pimage_id)->first();
        if ($pimage) {
            $this->removeFile($pimage->pimage_image);
            $pimage->delete();
            return response()->json(['success' => true]);
        }
        
        // If it's a temp file (not saved in DB yet)
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

    private function generateUniqueListingId()
    {
        do {
            $listingId = 'YANZZO' . mt_rand(100000, 999999);
            $exists = Product::where('product_listing_id', $listingId)->exists();
        } while ($exists);

        return $listingId;
    }
}
