<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\RequestDemo;
use App\Models\Teaching;
use DataTables;
use Illuminate\Http\Request;
use App\DataTables\Common\getSubscribeCourseDataTable;
use App\DataTables\Common\GetRequestDemoDataTable;
use App\DataTables\Common\GetTeachingDataTable;
use App\Helper\DocumentUploadS3Helper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RequestDemoExport;
use App\Exports\TeachingExport;

use Auth;

class CommonController extends Controller {

    /**
     * Get Subscriber Course By User
     * @param \Illuminate\Http\Request $request
     * @param \App\DataTables\Common\getSubscribeCourseDataTable $dataTable
     * @return type
     */
    public function getSubscribeCourse(Request $request, getSubscribeCourseDataTable $dataTable) {
        if ($request->ajax()) {
            $validatedData = $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);
            return $dataTable->render('admin.common.subscribecourse');
        }
        abort(404);
    }

    /**
     * send link to user for verify email
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function Verifyuser(Request $request, $email) {

        if ($request->ajax()) {

            $user = User::where('email', $email)->first();
            $success = [
                'status' => 'error',
                'message' => 'Please enter valid email address.'
            ];
            if (!empty($user)) {
                $user->sendEmailVerificationNotification();
                $success = [
                    'status' => 'true',
                    'message' => 'varification email send successfully'
                ];
            }
            return ["success" => $success['status'], "message" => $success['message']];
        }
        abort(404);
    }

    /**
     * Get user details
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getUserDetails(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $user = User::find($id);
            $type = $user->getRoleNames()->first();
            return view('admin.common.viewuserdetails')->with(['user' => $user, 'type' => $type]);
        }
        abort(404);
    }

    /**
     * Update institute and author profile
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function UpdateUserProfile(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);
            $id = decrypt($request->id);
            $user = User::where('id', $id)->get();
            return view('admin.common.updateuserprofile')->with(['user' => $user[0]]);
        }
        abort(404);
    }

    
    /**
     * Remove user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function DeleteUserProfile(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);

            $user = User::find($id);
            $user->delete();
            return ["success" => true, "message" => "User deleted successfully"];
        }
        abort(404);
    }

    /**
     * search category 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SearchCategory(Request $request) {
        $response = array();
        if (!empty($request->searchTerm)) {
            $users = Category::select('id', 'name')->where('status', '1')->where('name', 'like', '%' . $request->searchTerm . '%')->get();
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
    
    /**
     * search related courses 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SearchRelatedCourse(Request $request) {
        $response = array();
        if (!empty($request->id)) {
            if (!empty($request->searchTerm)) {
                $course = Course::select('course_id', 'course_name')->where('is_delete', '0')->where('status', '1')->where('course_id', '!=', $request->id)->where('course_name', 'like', '%' . $request->searchTerm . '%')->get();
            }
        } else {
            if (!empty($request->searchTerm)) {
                $course = Course::select('course_id', 'course_name')->where('is_delete', '0')->where('status', '1')->where('course_name', 'like', '%' . $request->searchTerm . '%')->get();
            }
        }
        if (!empty($course)) {
            foreach ($course as $row) {
                $response[] = array(
                    "id" => $row['course_id'],
                    "text" => $row['course_name']
                );
            }
        }
        return json_encode($response);
    }

    /**
     * Add institute and author profile
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function AddUserProfile(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate(['role' => $request->role], [
                'role' => 'required',
            ]);
            $role = $request->role;
            return view('admin.common.adduserprofile')->with(['role' => $role]);
        }
        abort(404);
    }
    
    /**
     * Add institute and author User
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function AddUserProfilePost(Request $request) {
        if ($request->ajax()) {

            $request->validate([
                'email' => 'required|unique:tbl_user,email',
                'role' => 'required',
                'name' => 'required',
                'profile_title' => 'required',
                'about_me' => 'required',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
                'image' => 'required|mimes:jpg,jpeg,png|max:200'
            ]);
            
            $string = str_replace(' ', '-', $request->name);
            $slug = strtolower($string).'-'.md5(rand(0000,9999));
            
            if (!empty($request->image)) {
                $image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->image);
            }

            $insertData = [
                'name' => $request->name,
                'email' => $request->email,
                'author_slug' => $slug,
                'profile_title' => $request->profile_title,
                'about_me' => $request->about_me,
                'password' => \Hash::make($request->password),
                'status' => '1',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'profile_image' => $image_url,
            ];
            
            $user = User::create($insertData);
            $user->assignRole($request->role);
            
            return ["success" => true, "message" => "User added successfully"];
        }
        abort(404);
    }
    
    /**
     * Update institute and author profile
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function UpdateUserProfilePost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'id' => 'required',
                'name' => 'required',
                'profile_title' => 'required',
                'about_me' => 'required',
            ]);

            $id = decrypt($request->id);

            $update_data = [
                'name' => $request->name,
                'profile_title' => $request->profile_title,
                'about_me' => $request->about_me,
            ];

            if (isset($request->password) && $request->password != '') {
                $request->validate([
                    'confirm_password' => 'required|same:password',
                        ], [
                    'confirm_password.required' => 'password and confirm password fields should not be blank',
                ]);
                $update_data['password'] = \Hash::make($request->password);
            }

            if (!empty($request->image)) {

                $request->validate([
                    'image' => 'required|mimes:jpg,jpeg,png|max:200'
                ]);

                $user = User::where('id', $id)->get();
                DocumentUploadS3Helper::deleteToBucket($user[0]['profile_image']);

                $update_data['profile_image'] = DocumentUploadS3Helper::uploadToBucketNew('images', $request->image);
            }

            User::where('id', $id)->update($update_data);
            return ["success" => true, "message" => "User profile updated successfully"];
        }
        abort(404);
    }

    /**
     * view request demo
     * @param \App\DataTables\Common\GetRequestDemoDataTable $dataTable
     * @return type
     */
    public function GetRequestDemo(GetRequestDemoDataTable $dataTable) {
        return $dataTable->render('admin.requestdemo.index');
    }
    
    /**
     * View Request Demo Details
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return type
     */
    public function GetRequestDemoDetails(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = RequestDemo::find($id);
            return view('admin.requestdemo.show')->with(['data' => $data]);
        }
        abort(404);
    }
    
    /**
     * export request demo
     * @param \App\DataTables\Common\GetRequestDemoDataTable $dataTable
     * @return type
     */
    public function RequestDemoExport(){
        return Excel::download(new RequestDemoExport, time() . '_RequestDemo.xlsx');
    }

    /**
     * View Teaching 
     * @return type
     */
    public function GetTeaching(GetTeachingDataTable $dataTable) {
        return $dataTable->render('admin.teaching.index');
    }

    /**
     * Teaching details
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return type
     */
    public function GetTeachingDetails(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = Teaching::find($id);
            return view('admin.teaching.show')->with(['data' => $data]);
        }
        abort(404);
    }

    /**
     * export teaching
     * @return type
     */
    public function TeachingExport(){
        return Excel::download(new TeachingExport, time() . '_Teaching.xlsx');
    }

}
