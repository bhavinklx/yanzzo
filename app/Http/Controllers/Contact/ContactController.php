<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Validator;
use Session;
use DataTables;
use App\Exports\ContactExport;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:contact-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:contact-delete', ['only' => ['delete']]);
    }

    public function view()
    {
        return view("admin.contact.list");
    }

    public function load_table(Request $request)
    {
        $contactDetail = Contact::orderBy("contact_order", "DESC")->get();
        return DataTables::of($contactDetail)
            ->editColumn("checkbox", function ($contact){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$contact->contact_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($contact){
                return $contact->contact_name;
            })
            ->editColumn("email", function ($contact){
                return $contact->contact_email;
            })
            ->editColumn("mobile", function ($contact){
                return $contact->contact_mobile;
            })
            ->editColumn("city", function ($contact){
                return $contact->contact_city;
            })
            ->editColumn("zipcode", function ($contact){
                return $contact->contact_zipcode;
            })
            ->editColumn("message", function ($contact){
                return $contact->contact_message;
            })
            ->editColumn("date", function ($contact){
                return date('d-m-Y h:i:s A', strtotime($contact->created_at));
            })
            /*->editColumn("ip", function ($contact){
                return $contact->contact_ip;
            })*/
            ->editColumn("action", function ($contact){
                $action = "";
                if (auth()->user()->can('contact-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $contact->contact_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($contact) {
                    return $contact->contact_id;
                }
            ])
            ->rawColumns(["checkbox", "action"])
            ->make(true);
    }

    public function delete(Request $request)
    {
        Contact::where("contact_id", $request->contact_id)->delete();
    }

    public function export()
    {
        // Store on a different disk (e.g. s3)
        //Excel::store(new ContactExport(), 'contact.xlsx', 'public');
        return Excel::download(new ContactExport(), 'contact.xlsx');
    }
}
