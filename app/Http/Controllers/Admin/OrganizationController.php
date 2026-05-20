<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use App\DataTables\Common\OrganizationDataTable;
use App\DataTables\Common\OrganizationindividualDataTable;
use App\DataTables\Common\OrganizationinvitedUserDataTable;


class OrganizationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, OrganizationDataTable $dataTable) {
        return $dataTable->render('admin.organization.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if ($request->ajax()) {
            $validatedData = $request->validate(['headoffice_id' => $id], [
                'headoffice_id' => 'required',
            ]);
            $id = decrypt($id);
            $user = User::find($id);
            $type = $user->getRoleNames()->first();
            return view('admin.organization.show')->with(['user' => $user, 'type' => $type]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        abort(404);
    }

    /**
     * Get Individual Users List
     * @param \Illuminate\Http\Request $request
     * @param \App\DataTables\Common\OrganizationindividualDataTable $dataTable
     * @return type
     */
    public function GetIndividualUser(Request $request, OrganizationindividualDataTable $dataTable) {
        if ($request->ajax()) {
            $validatedData = $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);
            return $dataTable->render('admin.organization.individual');
        }
        abort(404);
    }

    /**
     * Get Invite User List
     * @param \Illuminate\Http\Request $request
     * @param \App\DataTables\Common\OrganizationinvitedUserDataTable $dataTable
     * @return type
     */
    public function GetinvitedUser(Request $request, OrganizationinvitedUserDataTable $dataTable) {
        if ($request->ajax()) {
            $validatedData = $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);
            return $dataTable->render('admin.organization.Inviteduser');
        }
        abort(404);
    }



}
