<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }

    public function createSlug(Request $request)
    {
        $slug = Str::slug($request->service_title);
        $allSlugs = $this->checkSlug($slug);

        if (! $allSlugs->contains('service_slug', $slug)) {
            return response()->json(['slug' => $slug]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (! $allSlugs->contains('service_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }

        return response()->json(['error' => 'Unable to generate unique slug'], 422);
    }

    protected function checkSlug($slug)
    {
        return Service::select('service_slug')
            ->where('service_slug', 'like', $slug . '%')
            ->get();
    }

    public function create()
    {
        return view('admin.service.create');
    }

    public function insert(Request $request)
    {
        $this->validateData($request);

        $service = new Service();
        $this->saveUpdateData($service, $request);

        Session::flash('successMsg', 'Service added successfully');
        return response()->json(['redirect_url' => route('service-list')]);
    }

    public function edit($id)
    {
        $serviceDetail = Service::findOrFail($id);
        return view("admin.service.edit", compact('serviceDetail'));
    }

    public function update(Request $request)
    {
        $this->validateData($request);

        $service = Service::findOrFail($request->service_id);
        $this->saveUpdateData($service, $request, true);

        Session::flash('successMsg', 'Service updated successfully');
        return response()->json(['redirect_url' => route('service-list')]);
    }

    public function view()
    {
        $service = Service::orderBy('created_at', 'DESC')->get();
        return view("admin.service.list")->with('service',$service);
    }

    public function load_table(Request $request)
    {
        $serviceDetail = Service::orderBy('service_order');
        return DataTables::of($serviceDetail)
            ->editColumn("checkbox", function ($service){
                return '<div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="' . $service->service_id . '"> </div>';
            })
            ->editColumn("title", function ($service){
                return $service->service_title;
            })
            ->editColumn("date", function ($service){
                if (!$service->created_at) {
                    return '--';
                }
                return date('d-m-Y h:i:s A', strtotime($service->created_at));
            })
            ->editColumn("status", function ($service) {
                if ($service->service_status == '1') {
                    return '<div id="td_status_' . $service->service_id . '"><a href="javascript:void(0)" onclick="change_status(' . $service->service_id . ',0)" ><span class="badge bg-success">Active</span></a></div>';
                } else {
                    return '<div id="td_status_' . $service->service_id . '"><a href="javascript:void(0)" onclick="change_status(' . $service->service_id . ',1)" ><span class="badge bg-danger">Inactive</span></a></div>';
                }
            })
            ->editColumn("action", function ($service){
                $action = '<div class="d-inline-flex gap-1">';
                if (auth()->user()->can('service-delete')) {
                    $action.= '<button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal(' . $service->service_id . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Service"> <i class="ri-delete-bin-line"></i> </button>';
                }
                if (auth()->user()->can('service-edit')) {
                    $action.= '<a href="'.route("service-edit", ['id' => $service->service_id]).'" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Service"> <i class="ri-edit-box-line"></i> </a>';
                }
                $action.= '</div>';
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "id" => function ($service) {
                    return 'row_' . $service->service_id;
                },
                "data-id" => function ($service) {
                    return $service->service_id;
                }
            ])
            ->rawColumns(["checkbox", "status", "action"])
            ->make(true);
    }

    public function change_status(Request $request)
    {
        if (!$request->ajax()) {
            exit('No direct script access allowed');
        }

        Service::where('service_id', $request->service_id)
            ->update(["service_status" => $request->status]);

        echo $request->status == 1
            ? 'Status Activate successfully'
            : 'Status Inactivate successfully';
    }

    public function delete(Request $request)
    {
        $service = Service::findOrFail($request->service_id);

        $this->deleteFile($service->service_image);
        $service->delete();

        return response('Service deleted successfully.');
    }

    public function update_order(Request $request)
    {
        if (!Schema::hasColumn('service', 'service_order')) {
            echo 'Service order column not found.';
            return;
        }

        foreach ($request->order as $order) {
            Service::where("service_id", $order["service_id"])->update(["service_order" => $order["position"]]);
        }
        echo 'Service order changed successfully.';
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            "service_title"             => 'required|string|max:255',
            "service_slug"              => 'required|string|max:255',
        ]);
    }

    private function saveUpdateData(Service $service, Request $request, $isUpdate = false)
    {
        if ($request->hasFile('service_image')) {
            if ($isUpdate && $service->service_image) {
                $this->deleteFile($service->service_image);
            }
            $service->service_image     = $this->uploadImage($request->file('service_image'));
        }

        //Dropzone async upload
        if ($request->service_image) {
            $service->service_image     = $request->service_image; // filename string
        }

        if ($isUpdate) {
            $service->updated_at        = now();
        } else {
            $lastOrder                  = Service::orderBy("service_order", "DESC")->first();
            $service->service_order     = $lastOrder ? $lastOrder->service_order + 1 : 1;
            $service->created_at        = now();
        }

        $service->fill([
            'service_title'             => $request->service_title,
            'service_slug'              => $request->service_slug,
            'service_desc'              => $request->service_desc,
            'service_meta_title'        => $request->service_meta_title,
            'service_meta_keyword'      => $request->service_meta_keyword,
            'service_meta_desc'         => $request->service_meta_desc,
            'service_status'            => '1',
            'service_type'              => '0',
        ]);

        $service->save();
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
        $file->move(public_path('uploads/service'), $filename);
        return $filename;
    }

    private function deleteFile($filename)
    {
        if ($filename!='' && file_exists(public_path('/uploads/service/'.$filename))) {
            @unlink(public_path('/uploads/service/'.$filename));
        }
    }
}