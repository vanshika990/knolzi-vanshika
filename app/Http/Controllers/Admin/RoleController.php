<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\Permissions;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\Common\RolesDataTable;

class RoleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RolesDataTable $dataTable) {
        return $dataTable->render('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            $perms = Permissions::all();
            $permissions = array();
            foreach ($perms as $permission) {
                $permissions[$permission->module][] = $permission;
            }
            $modules = config('global.permission_modules');

            return view('admin.roles.create')->with([
                        'modules' => $modules,
                        'permissions' => $permissions,
            ]);
        }
        abourt(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required|unique:tbl_roles,name',
                'display_name' => 'required',
                'permission.*' => 'required',
                    ], ["unique" => "The role name has already been taken"]
            );

            $data = $request->all();
            $data["name"] = $data["name"];
            $data["display_name"] = $data["display_name"];
            $data["description"] = $data["description"];
            $role = Roles::create($data);
            $permissions = $request->get("permission");
            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }
            return ["success" => true, "message" => "Role created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Roles $roles) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Roles $role) {
        if ($request->ajax()) {
            $role_permission = $role->with('permissions')->find($role->id);
            $role_permissions = array();
            foreach ($role_permission->permissions as $rp) {
                $role_permissions[] = $rp->id;
            }

            $perms = Permissions::all();
            $permissions = array();
            foreach ($perms as $permission) {
                $permissions[$permission->module][] = $permission;
            }
            $modules = config('global.permission_modules');
            return view('admin.roles.edit')->with([
                        'roles' => $role,
                        'modules' => $modules,
                        'permissions' => $permissions,
                        'role_permissions' => $role_permissions,
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Roles $role) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
                'display_name' => 'required',
                'permission.*' => 'required',
                    ]
            );

            $data = $request->all();
            $data["name"] = $data["name"];
            $data["display_name"] = $data["display_name"];
            $data["description"] = $data["description"];
            $role->update($data);
            $permissions = $request->get("permission");
            $role->syncPermissions($permissions);
            return ["success" => true, "message" => "Role updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Roles $role) {
        if ($request->ajax()) {
            $roles = User::role($role)->count();
            if ($roles > 0) {
                return \Response::json(array("errors" => ["role_all_ready_exist" => ["You can't delete this role because this role already assigned in $roles users!"]]), 422);
            } else {
                $role->delete();
                return ["success" => true, "message" => "Role deleted successfully"];
            }
        }
        abort(404);
    }

}
