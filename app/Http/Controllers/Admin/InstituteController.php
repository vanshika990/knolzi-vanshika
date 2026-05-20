<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\Common\InstituteDataTable;
use App\DataTables\Common\InstituteAuthorDataTable;
use App\Models\InstituteHasAuthor;

class InstituteController extends Controller {

    /**
     * Display a listing of the institute.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InstituteDataTable $dataTable) {
        return $dataTable->render('admin.institute.index');
    }

    /**
     * Show the form for creating a new institute.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        abort(404);
    }

    /**
     * Store a newly created institute.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        abort(404);
    }

    /**
     * Display the institute resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $user = User::find($id);

            $author_data = InstituteHasAuthor::with('user')->where('institute_id', $id)->get()->toArray();

            $author_name = [];
            foreach ($author_data as $key => $value) {
                $author_name[] = $value['user']['name'];
            }

            $author_names = implode(', ', $author_name);

            return view('admin.institute.show')->with(['user' => $user, 'author_name' => $author_names]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, $id) {
        $id = decrypt($id);
        $author_id = InstituteHasAuthor::where('institute_id', $id)->get()->toArray();
        $author_ids = array_column($author_id, 'author_id');
        $users = User::select('id', 'name')->role(['author'])->whereIn('id', $author_ids)->get();
        return view('admin.institute.edit')->with(['users' => $users, 'id' => encrypt($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);

            $request->validate([
                'author_id.*' => 'required|numeric',
            ]);

            // delete old institute author
            $institute_author = InstituteHasAuthor::where('institute_id', $id)->delete();

            // add institute author
            $insert_institute_author = [];
            $institute_author = $request['author_id'];
            if (!empty($institute_author)) {
                foreach ($institute_author as $key => $value) {
                    $insert_institute_author[] = [
                        'author_id' => $value,
                        'institute_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_course_user = InstituteHasAuthor::insert($insert_institute_author);
                return ["success" => true, "message" => "Institute update successfully"];
            }
        }
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
     * Get institute author
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function getAuthor(Request $request, InstituteAuthorDataTable $dataTable) {
        if ($request->ajax()) {
            $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);
            return $dataTable->render('admin.institute.author');
        }
        abort(404);
    }

    // search author
    public function searchAuthor(Request $request) {
        $response = array();
        if (!empty($request->searchTerm)) {
            $users = User::select('id', 'name')->role(['author'])->where('name', 'like', '%' . $request->searchTerm . '%')->get();
            if (!empty($users)) {
                foreach ($users as $row) {
                    $response[] = array(
                        "id" => $row['id'],
                        "text" => $row['name']
                    );
                }
            }
        }
        return json_encode($response);
    }

}
