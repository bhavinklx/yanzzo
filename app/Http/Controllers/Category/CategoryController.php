<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }

    public function createSlug(Request $request)
    {
        $slug = Str::slug($request->category_title);
        $allSlugs = $this->checkSlug($slug);

        if (! $allSlugs->contains('category_slug', $slug)) {
            return response()->json(['slug' => $slug]);
        }

        for ($i = 1; $i <= 20; $i++) {
            $newSlug = $slug . '-' . $i;
            if (! $allSlugs->contains('category_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }
        return response()->json(['error' => 'Unable to generate unique slug'], 422);
    }

    protected function checkSlug($slug)
    {
        return Category::select('category_slug')
            ->where('category_slug', 'like', $slug . '%')
            ->get();
    }

    public function create()
    {
        $parentCategory = Category::select('category_id', 'category_title')->where(["category_status" => "1", "category_parent"=>"0"])->orderBy('category_order')->get();
        return view('admin.category.create', compact('parentCategory'));
    }

    public function insert(Request $request)
    {
        $this->validateData($request);

        $category = new Category();
        $this->saveUpdateData($category, $request);

        Session::flash('successMsg', 'Category added successfully');
        return response()->json(['redirect_url' => route('category-list')]);
    }

    public function edit($id)
    {
        $categoryDetail = Category::find($id);
        $parentCategory = Category::select('category_id', 'category_title')->where(["category_status" => "1", "category_parent"=>"0"])->orderBy('category_order')->get();
        //echo '<pre>'; print_r($parentCategory); exit();
        return view("admin.category.edit", compact('categoryDetail', 'parentCategory'));
    }

    public function update(Request $request)
    {
        $this->validateData($request);

        $category = Category::findOrFail($request->category_id);
        $this->saveUpdateData($category, $request, true);

        Session::flash('successMsg', 'Category updated successfully');
        return response()->json(['redirect_url' => route('category-list')]);
    }

    public function view()
    {
        $categoryDetail = Category::select('category_id', 'category_title', 'category_order', 'category_status', 'created_at')->with(['subCategory' => function($query) {
            $query->select('category_id', 'category_title', 'category_parent', 'category_order', 'category_status', 'created_at');
        }])->where('category_parent', '0')->orderBy('category_order')->get();
        return view("admin.category.list")->with('categoryDetail', $categoryDetail);
    }

    public function change_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Category::where('category_id', $request->category_id)->update(["category_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function update_order(Request $request)
    {
        foreach ($request->order as $order)
        {
            Category::where("category_id", $order["category_id"])->update(["category_order" => $order["position"]]);
        }
        echo 'Category order changed successfully.';
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $this->deleteFile($category->blog_image);

        $category->delete();
        return response('Category deleted successfully.');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            "category_title"            => 'required|string|max:255',
            "category_slug"             => 'required|string|max:255'
        ]);
    }

    private function saveUpdateData(Category $category, Request $request, $isUpdate = false)
    {
        if ($request->hasFile('category_image')) {
            if ($isUpdate && $category->category_image) {
                $this->deleteFile($category->category_image);
            }
            $category->category_image   = $this->uploadImage($request->file('category_image'));
        }

        //Dropzone async upload
        if ($request->category_image) {
            $category->category_image   = $request->category_image; // filename string
        }

        if ($request->category_icon) {
            $category->category_icon    = $request->category_icon; // filename string
        }

        if ($isUpdate) {
            $category->updated_at       = now();
        } else {
            $lastOrder                  = Category::orderBy("category_order", "DESC")->first();
            $category->category_order   = $lastOrder ? $lastOrder->category_order + 1 : 1;
            $category->created_at       = now();
        }

        $category->fill([
            'category_parent'           => $request->category_parent,
            'category_title'            => $request->category_title,
            'category_slug'             => $request->category_slug,
            'category_desc'             => $request->category_desc,
            'category_meta_title'       => $request->category_meta_title,
            'category_meta_keyword'     => $request->category_meta_keyword,
            'category_meta_desc'        => $request->category_meta_desc,
            'category_status'           => "1"
        ]);

        $category->save();
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        //Call protected method
        $filename = $this->storeImage($request->file('file'));

        return response()->json([
            'filename' => $filename
        ]);
    }

    protected function storeImage($file)
    {
        $filename = 'IMG-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/category'), $filename);
        return $filename;
    }

    private function deleteFile($filename)
    {
        if ($filename!='' && file_exists(public_path('/uploads/category/'.$filename))) {
            @unlink(public_path('/uploads/category/'.$filename));
        }
    }
}
