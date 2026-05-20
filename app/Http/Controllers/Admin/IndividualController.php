<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CourseSubscription;
use Illuminate\Http\Request;
use App\DataTables\Common\IndividualDataTable;

class IndividualController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndividualDataTable $dataTable) {
        return $dataTable->render('admin.individual.index');
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
            $user = User::with('OrgData')->find($id);
            return view('admin.individual.show')->with('user', $user);
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
     * Update User Status
     * @param  \App\Models\User  $user
     * @param \Illuminate\Http\Request $request
     */
    public function userChangeStatus(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'id' => 'required',
            ]);
            $user_id = decrypt($request->id);
            $userdetail = User::find($user_id);
            $label = "activated";
            if ($userdetail->status == 1) {
                $users = \Laravel\Passport\Token::where('user_id', $user_id)
                        ->delete();
                $status = '2';
                $label = "deactivated ";
            }
            if ($userdetail->status == 2) {
                $status = '1';
            }
            $data = [];
            $data['status'] = $status;
            $userdetail->update($data);
            return ["success" => true, "message" => "User $label successfully."];
        }
        return abort(404);
    }

}
