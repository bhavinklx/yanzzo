<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Validator;
use Auth;
use Session;
use DataTables;

class UserController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:user-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:user-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }

    public function create()
    {
        $roleDetail = Role::pluck('name','name')->all();
        //echo '<pre>'; print_r($roleDetail); exit;
        return view("admin.users.create", compact('roleDetail'));
    }

    public function insert(Request $request)
    {
        $validator = Validator::make(array(
            "email" => $request->email,
            "password" => $request->password
        ), array(
            "email" => "required",
            "password" => "required",
            /*"roles" => "required"*/
        ));

        if ($validator->fails())
        {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data               = new User();
            $data->name         = $request->name;
            $data->email        = $request->email;
            $data->password     = bcrypt($request->password);
            $data->phone        = $request->phone;
            $data->created_at   = date('Y-m-d H:i:s');
            //print_r($lastOrder); exit();
            if ($data->save()) {
                $data->assignRole($request->roles);

                Session::flash('successMsg', 'User details added successfully');
                return ["redirect_url" => "user-add"];
            }
        }
    }

    public function edit($id)
    {
        $userDetail = User::find($id);
        $roleDetail = Role::pluck('name','name')->all();
        // get user already role
        $userRole = $userDetail->roles->pluck('name','name')->all();
        return view("admin.users.edit", compact('userDetail', 'roleDetail', 'userRole'));
    }

    public function update(Request $request)
    {
        //print_r($request->all()); exit;
        $data = User::find($request->user_id);
        $validator = Validator::make(array(
            "email" => $request->email,
        ), array(
            "email" => "required",
            //"roles" => "required"
        ));

        if ($validator->fails())
        {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data->name         = $request->name;
            $data->email        = $request->email;
            $data->phone        = $request->phone;
            $data->updated_at   = date('Y-m-d H:i:s');
            //print_r($lastOrder); exit();
            if ($data->save()) {
                DB::table('model_has_roles')->where('model_id', $data->id)->delete();
                $data->assignRole($request->roles);

                Session::flash('successMsg', 'User details updated successfully');
                return ["redirect_url" => "user-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.users.list");
    }

    public function load_table(Request $request)
    {
        $userDetail = User::get();
        return DataTables::of($userDetail)
            ->editColumn("checkbox", function ($user){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$user->id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($user){
                return $user->name;
            })
            ->editColumn("email", function ($user){
                return $user->email;
            })
            ->editColumn("mobile", function ($user){
                return $user->phone;
            })
            ->editColumn("date", function ($user){
                return date('d-m-Y h:i A', strtotime($user->created_at));
            })
            ->editColumn("action", function ($user){
                $action = "";
                /*if (auth()->user()->can('user-edit')) {*/
                    $action.= '<a href="'.route("user-edit", ['id' => $user->id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                /*}*/
                if (auth()->user()->can('user-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $user->id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                if (auth()->user()->can('user-delete')) {
                    $action.= '<a href="'.route("user-changepassword", ['id' => $user->id]).'" title="Change Password"> <i class="fa fa-lock"></i> </a>';
                }
                return $action;
            })
            //for add table row id
            ->setRowId('id')
            //for add table row class
            ->setRowClass(function () {
                return 'row1';
            })
            //for add table row data
            ->setRowData([
                'id' => 'test',
            ])
            //for add table row attr
            ->setRowAttr([
                'data-id' => function($user) {
                    return $user->id;
                }
            ])
            ->rawColumns(["checkbox", "action"])
            ->make(true);
    }

    public function changepassword($id)
    {
        $userDetail = User::find($id);
        return view("admin.users.changepassword", compact('userDetail'));
    }

    public function changepassword_update(Request $request)
    {
        $data = User::find($request->user_id);
        $validator = Validator::make(array(
            "password" => $request->password,
            "repassword" => $request->repassword
        ), array(
            "password" => "required",
            "repassword" => "required"
        ));

        if ($validator->fails())
        {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            $data->password     = bcrypt($request->password);
            $data->updated_at   = date('Y-m-d H:i:s');
            //print_r($lastOrder); exit();
            if ($data->save()) {
                Session::flash('successMsg', 'User password changed successfully');
                return ["redirect_url" => "user-list"];
            }
        }

    }

    public function delete(Request $request)
    {
        User::where("id", $request->id)->delete();
    }
}
