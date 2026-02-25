<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Hash;
use Validator;
use Auth;
use Session;
use DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:role-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:role-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        //echo '<pre>'; print_r($permissionDetail); exit();
        return view("admin.roles.create", compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name'), 'created_at' => date("Y-m-d H:i:s")]);
        $role->syncPermissions($request->input('permission'));

        Session::flash('successMsg', 'Role details added successfully');
        return ["redirect_url" => "role-add"];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view("admin.roles.edit", compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role       = Role::find($request->role_id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        Session::flash('successMsg', 'Role details updated successfully');
        return ["redirect_url" => "bcategory-add"];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
        /*$roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('admin.roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);*/
        //$roleDetail = Role::get();
        return view("admin.roles.list");
    }

    public function load_table(Request $request)
    {
        $roleDetail = Role::get();
        return DataTables::of($roleDetail)
            ->editColumn("checkbox", function ($role){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$role->id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($role){
                return $role->name;
            })
            ->editColumn("date", function ($role){
                return date('d-m-Y h:i:s A', strtotime($role->created_at));
            })
            ->editColumn("action", function ($role){
                $action = "";
                if (Auth::user()->hasPermissionTo('role-edit')) {
                    $action.= '<a href="'.route("role-edit", ['id' => $role->id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (Auth::user()->hasPermissionTo('role-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $role->id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            //for add table row class
            ->setRowClass(function () {
                return 'row1';
            })
            //for add table row attr
            ->setRowAttr([
                'data-id' => function($role) {
                    return $role->id;
                }
            ])
            ->rawColumns(["checkbox", "image", "status", "action"])
            ->make(true);
    }

    public function delete(Request $request)
    {
        Role::where('id', $request->id)
            ->delete();
    }

    public function access_denied()
    {
        return view('admin.access-denied');
    }
}
