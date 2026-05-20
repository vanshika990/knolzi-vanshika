<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use DataTables;
use Illuminate\Http\Request;
use App\DataTables\Common\PermissionsDataTable;

class PermissionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PermissionsDataTable $dataTable) {
        return $dataTable->render('admin.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            $modules = config('global.permission_modules');
            return view('admin.permission.create')->with(['modules' => $modules]);
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
                'name' => 'required',
                'display_name' => 'required',
            ]);
            $data = $request->all();
            Permissions::create($data);
            return ["success" => true, "message" => "Permission created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function show(Permissions $permissions) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Permissions $permission) {
        if ($request->ajax()) {
            $modules = config('global.permission_modules');
            return view('admin.permission.edit')->with([
                        'permission' => $permission, 'modules' => $modules
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permissions $permission) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
                'display_name' => 'required',
            ]);
            $data = $request->all();
            $permission->update($data);
            return ["success" => true, "message" => "Permission updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Permissions $permission) {
        if ($request->ajax()) {
            $permission->delete();
            return ["success" => true, "message" => "Permission deleted successfully"];
        }
        abort(404);
    }

}
