<?php

namespace App\Http\Controllers\Front;

use Auth;
use Hash;
use App\Http\Controllers\Controller;
use App\DataTables\Front\GetUserAuthorDataTable;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\UserEducation;
use App\Models\UserWorkQualification;
use App\Models\Notification;
use App\Models\NotificationHistory;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use DocumentUploadS3Helper;

class UserController extends Controller {

    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the institute author.
     *
     * @param  App\DataTables\Front\GetUserAuthorDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function GetAuthor(GetUserAuthorDataTable $dataTable) {
        if ($this->user->can('view-own-author')) {
            return $dataTable->render('front.author.index');
        }
        abort(403);
    }

    /**
     * Display details of the institute author.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function GetAuthorDetails(Request $request, $id) {
        if ($this->user->can('view-author-details')) {
            if ($request->ajax()) {
                $id = decrypt($id);
                $author_data = User::where('id', $id)->get();
                return view('front.author.authordetail')->with(['user' => $author_data[0]]);
            }
            abort(403);
        }
    }

    /**
     * Display details of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function GetUserDetails(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $author_data = User::where('id', $id)->get();
            return view('front.organization.UserDetail')->with(['user' => $author_data[0]]);
        }
        abort(404);
    }

    /**
     * Display personal details of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function PersonalProfile() {
        $user_id = Auth::user()->id;
        $data = User::select(['name', 'email', 'age_group', 'profile_image'])->where('id', $user_id)->get();
        // return view('front.profile.personalprofile')->with(['user' => $data[0]]);
        return view('frontend.profile.index')->with(['user' => $data[0]]);
    }

    /**
     * edit personal details of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function EditPersonalProfile() {
        $user_id = Auth::user()->id;
        $data = User::select(['name', 'email', 'age_group', 'profile_image'])->where('id', $user_id)->get();

        // return view('front.profile.editpersonalprofile')->with(['user' => $data[0]]);
        return view('frontend.profile.edit')->with(['user' => $data[0]]);
    }

    /**
     * edit personal details of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UpdatePersonalProfile(Request $request) {
        $request->validate([
            'name' => 'required',
            'age' => 'required',
        ]);

        $update_data = [
            'name' => $request->name,
            'age_group' => $request->age,
        ];

        $user_id = Auth::user()->id;

        User::where('id', $user_id)->update($update_data);

        return redirect()->route('personal-profile');
    }

    /**
     * edit personal details of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UpdateProfileImage(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'picture' => "required|mimes:jpg,jpeg,png"
            ]);
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            if ($user['profile_image'] != '') {
                DocumentUploadS3Helper::deleteToBucket($user['profile_image']);
            }

            $image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('picture'));
            User::where('id', $user_id)->update(['profile_image' => $image_url]);

            return ["success" => true, "message" => 'Profile image uploaded success fully.'];
        }
        abort(404);
    }

    /**
     * get education qualification of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function EduQualification() {
        $user_id = Auth::user()->id;
        $data = UserEducation::where('user_id', $user_id)->get();
        // return view('front.education.eduqualification')->with(["data" => $data]);
        return view('frontend.education.index')->with(["data" => $data, 'user' => Auth::user()]);
    }

    /**
     * delete education qualification of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function DeleteEduQualification(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            UserEducation::where('id', $id)->delete();
            return ["success" => true, "message" => 'Education delete successfully.'];
        }
        abort(404);
    }

    /**
     * add education qualification of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddEduQualification(Request $request) {
        if ($request->ajax()) {
            // return view('front.education.add');
            return view('frontend.education.add');
        }
        abort(404);
    }

    /**
     * add education qualification of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddEduQualificationpost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'degree' => "required",
                'university' => "required",
                'institute' => "required",
                'stream' => "required",
                'year' => "required",
                'grade' => "required",
            ]);
            $id = Auth::user()->id;
            $insert_data = [
                'user_id' => $id,
                'degree' => $request->degree,
                'university' => $request->university,
                'institute' => $request->institute,
                'stream' => $request->stream,
                'year' => $request->year,
                'grade' => $request->grade,
            ];

            UserEducation::create($insert_data);
            return ["success" => true, "message" => "Education added successfully"];
        }
        abort(404);
    }

    /**
     * edit education qualification of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function EditEduQualification(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = UserEducation::find($id);

            // return view('front.education.edit')->with(['education' => $data]);
            return view('frontend.education.edit')->with(['education' => $data]);
        }
        abort(404);
    }

    /**
     * edit education qualification of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UpdateEduQualification(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'degree' => "required",
                'university' => "required",
                'institute' => "required",
                'stream' => "required",
                'year' => "required",
                'grade' => "required",
            ]);
            $id = decrypt($request->id);
            $update_data = [
                'degree' => $request->degree,
                'university' => $request->university,
                'institute' => $request->institute,
                'stream' => $request->stream,
                'year' => $request->year,
                'grade' => $request->grade,
            ];

            UserEducation::where('id', $id)->update($update_data);
            return ["success" => true, "message" => "Education updated successfully"];
        }
        abort(404);
    }

    /**
     * get work experience of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function WorkExperience() {
        $user_id = Auth::user()->id;
        $data = UserWorkQualification::where('user_id', $user_id)->get();
        // return view('front.experience.experience')->with(["data" => $data]);
        return view('frontend.experience.index')->with(["data" => $data, 'user' => Auth::user()]);
    }

    /**
     * add work experience of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddWorkexperience(Request $request) {
        if ($request->ajax()) {
            // return view('front.experience.add');
            return view('frontend.experience.add');
        }
        abort(404);
    }

    /**
     * add work experiance of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddWorkexperiencepost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'company_name' => "required",
                'experience' => "required",
                'year' => "required",
                'role' => "required",
                'designation' => "required",
            ]);

            $id = Auth::user()->id;
            $insert_data = [
                'user_id' => $id,
                'company_name' => $request->company_name,
                'experience' => $request->experience,
                'year' => $request->year,
                'role' => $request->role,
                'year' => $request->year,
                'designation' => $request->designation,
            ];

            UserWorkQualification::create($insert_data);

            return ["success" => true, "message" => "Experience added successfully"];
        }
        abort(404);
    }

    /**
     * edit work experiance of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function EditWorkexperience(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = UserWorkQualification::find($id);
            // return view('front.experience.edit')->with(['experience' => $data]);
            return view('frontend.experience.edit')->with(['experience' => $data]);
        }
        abort(404);
    }

    /**
     * edit work experiance of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UpdateWorkexperience(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'company_name' => "required",
                'experience' => "required",
                'year' => "required",
                'role' => "required",
                'designation' => "required",
            ]);

            $id = decrypt($request->id);
            $update_data = [
                'company_name' => $request->company_name,
                'experience' => $request->experience,
                'year' => $request->year,
                'role' => $request->role,
                'year' => $request->year,
                'designation' => $request->designation,
            ];

            UserWorkQualification::where('id', $id)->update($update_data);
            return ["success" => true, "message" => "Experience updated successfully"];
        }
        abort(404);
    }

    /**
     * delete work experiance of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function DeleteWorkexperience(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            UserWorkQualification::where('id', $id)->delete();
            return ["success" => true, "message" => 'Expeerience delete successfully.'];
        }
        abort(404);
    }

    /**
     * change password of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Changepassword(Request $request){
        // return view('front.profile.changepassword');
        return view('frontend.profile.change-password')->with(['user' => Auth::user()]);
    }

    /**
     * change password of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ChangepasswordPost(Request $request){

        if($request->ajax()) {

            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|same:new_password|min:5',
                'confirm_password' => 'required|same:new_password',
            ]);

            $current_password = Auth::User()->password;

            if(Hash::check($request['old_password'], $current_password)) {
                $user_id = Auth::User()->id;
                $new_password = Hash::make($request['new_password']);
                User::where('id',$user_id)->update(['password' => $new_password]);
                return ["success" => true, "message" => 'Password updated successfully.'];
            }
            else {
                $error = ['old_password' => ['Please enter correct current password']];
                return response()->json(array('errors' => $error), 422);
            }
        }
    }


    /**
     * get notification of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNotification(Request $request){

    	$user_id = Auth::user()->id;
        $data = NotificationHistory::where('user_id',$user_id)->get();

        //return view('front.profile.changepassword');
    }

}

