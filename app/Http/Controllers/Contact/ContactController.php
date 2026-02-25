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
        date_default_timezone_set('Asia/Kolkata');
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
                return '<div class="form-check m-0"> <input class="form-check-input check_class" type="checkbox" id="check[]" name="check[]" value="' . $contact->contact_id . '"> </div>';
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
                $action = '<div class="d-inline-flex gap-1">';
                if (auth()->user()->can('contact-delete')) {
                    $action.= '<button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal(' . $contact->contact_id . ');" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Contact"> <i class="ri-delete-bin-line"></i> </button>';
                }
                $action.= '</div>';
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
