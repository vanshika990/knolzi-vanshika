<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\ReviewerCourse;
use Illuminate\Http\Request;
use App\DataTables\Common\ReviewerDataTable;

class ReviewerController extends Controller {

    /**
     * Display a listing of the reviewer user.
     *
     * @return \Illuminate\Http\Response
     * @param \App\DataTables\Common\ReviewerDataTable $dataTable
     */
    public function index(ReviewerDataTable $dataTable) {
        return $dataTable->render('admin.reviewer.index');
    }

    /**
     * Show the form for creating a new reviewer user.
     *
     * @return \Illuminate\Http\Response
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Course
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            return view('admin.reviewer.create');
        }
        abourt(404);
    }

    /**
     * Store a newly created reviewer user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @param  \App\Models\User
     * @param  \App\Models\ReviewerCourse
     * 
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|unique:tbl_user,email',
                'mobile_no' => 'required|numeric|digits:10',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
                'select_course' => 'required',
                    ], [
                "unique" => "The email address has already been taken",
                'password.required' => 'password and confirm password fields should not be blank',
            ]);

            $insertData = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'password' => \Hash::make($request->password),
                'source_from' => 'web',
                'status' => '1',
                'email_verified_at' => date('Y-m-d H:i:s'),
            ];

            $user = User::create($insertData);
            $user->assignRole('reviewer');
            $course_data = $request->select_course;
            $insert_reviewer = [];
            foreach ($course_data as $key => $value) {
                $insert_reviewer[] = [
                    'course_id' => $value,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ReviewerCourse::insert($insert_reviewer);

            return ["success" => true, "message" => "Reviewer created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) {
        abort(404);
    }

    /**
     * Show the form for editing the reviewer user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @param  \App\Models\Course
     * @param  \App\Models\ReviewerCourse
     * 
     */
    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $courseData = [];

            $course_id = ReviewerCourse::with('course')->select(['course_id'])->where('user_id', $id)->get();
            $course_data = [];
            foreach ($course_id as $key => $value) {
                $course_data[] = [
                    'course_id' => $value['course']['course_id'],
                    'course_name' => $value['course']['course_name'],
                ];
            }

            $reviewer_user = User::where('id', $id)->get();
            return view('admin.reviewer.edit')->with([ 'userDetail' => $reviewer_user[0], 'courseData' => $courseData, 'sel_course_id' => $course_data]);
        }
        abort(404);
    }

    /**
     * Update the reviewer user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
                'mobile_no' => 'required|numeric|digits:10',
                'select_course' => 'required',
                    ]
            );

            $updatedata = [
                'name' => $request->name,
                'mobile_no' => $request->mobile_no
            ];

            if (isset($request->password) && $request->password != '') {
                $request->validate([
                    'confirm_password' => 'required|same:password',
                        ], [
                    'confirm_password.required' => 'password and confirm password fields should not be blank',
                ]);
                $updatedata['password'] = \Hash::make($request->password);
            }

            $updatedata = User::where('id', $id)->update($updatedata);
            ReviewerCourse::where('user_id', $id)->delete();
            $course_data = $request->select_course;

            $insert_reviewer = [];
            foreach ($course_data as $key => $value) {
                $insert_reviewer[] = [
                    'course_id' => $value,
                    'user_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            ReviewerCourse::insert($insert_reviewer);

            return ["success" => true, "message" => "Reviewer user updated successfully"];
        }
        abort(404);
    }

    /**
     * Delete reviewer user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $data = User::where('id', $id)->update(['status' => '0']);
            return ["success" => true, "message" => "Reviewer user deleted successfully"];
        }
        abort(404);
    }

    /**
     * reviewer search course
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SearchCourse(Request $request) {
        $response = array();
        if (!empty($request->searchTerm)) {
            $courseData = Course::select('course_name', 'course_id')->where('is_delete', '0')->where('course_name', 'like', '%' . $request->searchTerm . '%')->get();
            if (!empty($courseData)) {
                foreach ($courseData as $row) {
                    $response[] = array(
                        "id" => $row['course_id'],
                        "text" => $row['course_name']
                    );
                }
            }
        }
        return json_encode($response);
    }

}
