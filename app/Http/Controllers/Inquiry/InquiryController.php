<?php

namespace App\Http\Controllers\Inquiry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Validator;
use Session;
use DataTables;
use App\Exports\InquiryExport;
use Maatwebsite\Excel\Facades\Excel;

class InquiryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:inquiry-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:inquiry-delete', ['only' => ['delete']]);
    }

    public function view()
    {
        return view("admin.inquiry.list");
    }

    public function load_table(Request $request)
    {
        $inquiryDetail = Inquiry::orderBy("inquiry_order", "DESC")->get();
        return DataTables::of($inquiryDetail)
            ->editColumn("checkbox", function ($inquiry){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$inquiry->inquiry_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($inquiry){
                return $inquiry->inquiry_name;
            })
            ->editColumn("email", function ($inquiry){
                return $inquiry->inquiry_email;
            })
            ->editColumn("mobile", function ($inquiry){
                return $inquiry->inquiry_mobile;
            })
            ->editColumn("city", function ($inquiry){
                return $inquiry->inquiry_city;
            })
            ->editColumn("zipcode", function ($inquiry){
                return $inquiry->inquiry_zipcode;
            })
            ->editColumn("country", function ($inquiry){
                return $inquiry->inquiry_country;
            })
            ->editColumn("date", function ($inquiry){
                return date('d-m-Y h:i:s A', strtotime($inquiry->created_at));
            })
            /*->editColumn("ip", function ($inquiry){
                return $inquiry->contact_ip;
            })*/
            ->editColumn("action", function ($inquiry){
                $action = "";
                if (auth()->user()->can('inquiry-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $inquiry->inquiry_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($inquiry) {
                    return $inquiry->inquiry_id;
                }
            ])
            ->rawColumns(["checkbox", "action"])
            ->make(true);
    }

    public function delete(Request $request)
    {
        Inquiry::where("inquiry_id", $request->inquiry_id)->delete();
    }

    public function export()
    {
        // Store on a different disk (e.g. s3)
        //Excel::store(new InquiryExport(), 'contact.xlsx', 'public');
        return Excel::download(new InquiryExport(), 'inquiry.xlsx');
    }
}