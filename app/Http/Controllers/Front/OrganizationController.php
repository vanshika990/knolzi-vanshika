<?php

namespace App\Http\Controllers\Front;

use Auth;
use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Userinvitation;
use App\Models\User;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\UserHasOrganization;
use App\DataTables\Common\OrganizationDataTable;
use App\DataTables\Front\InvitationDataTable;
use App\DataTables\Front\ViewCourseLicenceDataTable;
use App\DataTables\Front\OrganizationSubscribeCourseDataTable;
use App\DataTables\Front\MyUserOrgDataTable;
use App\DataTables\Front\GetOrgUserCourseDataTable;

class OrganizationController extends Controller {

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
     * Display a listing of the invitation related to organization.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetOrganizationInvitation(InvitationDataTable $dataTable) {
        if ($this->user->can('view-invitation-org')) {
            return $dataTable->render('front.organization.UserInvitation');
        }
        abort(403);
    }

    /**
     * Organization Create User Invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendInvitation(Request $request) {
        if ($this->user->can('send-invitation-org')) {
            if ($request->ajax()) {
                return view('front.organization.createInvitation');
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Organization Add User Invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendInvitationPost(Request $request) {
        if ($this->user->can('send-invitation-org')) {
            if ($request->ajax()) {
                $email_id = $request->email_id;
                $request->validate([
                    'email_id' => 'required|email:rfc,dns|unique:tbl_users_invitation,user_email,' . $email_id . '|unique:tbl_user,email',
                ]);

                $random = strtoupper(Str::random(6));
                $to = $email_id;
                $send_email = [
                    'template' => 'invitations',
                    'html_body' => [
                        'button_code' => $random,
                    ],
                    'subject' => 'Knolzi Invitation'
                ];
                \Mail::to($to)->send(new SendEmail($send_email));
                $insert = [
                    'company_id' => Auth::user()->id,
                    'user_email' => $email_id,
                    'company_code' => $random
                ];
                $insert_data = Userinvitation::create($insert);
                return ["success" => true, "message" => "Invitation successfully send"];
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Method to resend invitation to user.
     *
     * @return \Illuminate\Http\Response
     */
    public function resendInvitation(Request $request, $id) {
        if ($this->user->can('resend-invitation-org')) {
            if ($request->ajax()) {
                if ($id == "") {
                    return response()->json(['error' => "Data not found"], 401);
                }
                $id = decrypt($id);
                $userinvitation = Userinvitation::where('id', $id)->first();
                if (empty($userinvitation)) {
                    return response()->json(['error' => "Data not found"], 401);
                }
                $random = strtoupper(Str::random(6));
                $to = $userinvitation->user_email;
                $resend_cnt = $userinvitation->resend;
                $resend = $resend_cnt + 1;
                $send_email = [
                    'template' => 'invitations',
                    'html_body' => [
                        'button_code' => $random,
                    ],
                    'subject' => 'Knolzi Invitation'
                ];
                \Mail::to($to)->send(new SendEmail($send_email));
                $userinvitation->update(['resend' => $resend]);
                return ["success" => true, "message" => "Invitation resend successfully"];
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * view Course licence for organization
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function viewCourseLicence(Request $request, ViewCourseLicenceDataTable $dataTable) {
        if ($this->user->can('view-licence-org')) {
            if ($request->ajax()) {
                $validatedData = $request->validate(['id' => $request->id], [
                    'id' => 'required',
                ]);

                $user_id = $this->user->id;
                $id = decrypt($request->id);

                $coursesubscription = CourseSubscription::with('coursesublicence')->where('user_id', $user_id)->where('id', $id)->first();
                $licence_used = count($coursesubscription->coursesublicence);

                $data = [
                    'total_licence' => $coursesubscription->no_of_licence,
                    'used_licence' => $licence_used,
                    'remaining_licence' => $coursesubscription->no_of_licence - $licence_used,
                    'course_id' => encrypt($coursesubscription->course_id),
                ];

                return $dataTable->render('front.organization.viewCourseLicence', ['data' => $data]);
            }
        }
        abort(404);
    }

    /**
     * Organization Add Add New licence to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddNewLicence(Request $request, $id) {
        if ($this->user->can('add-licence-org')) {
            if ($request->ajax()) {
                $user_id = $this->user->id;
                $course_id = decrypt($id);
                $subscription = CourseSubscription::with('licence.user')->where('user_id', $user_id)->where('course_id', $course_id)->first();
                $sub_user_id = [];
                foreach ($subscription->licence as $key => $value) {
                    $sub_user_id[] = $value['user']['id'];
                }
                $user_data = UserHasOrganization::with('user')->where('org_id', $user_id)->whereNotIn('user_id', $sub_user_id)->get();
                $course_sub_id = encrypt($subscription->id);
                return view('front.organization.AddNewLicence')->with(['user_data' => $user_data, 'course_id' => $id, 'course_sub_id' => $course_sub_id]);
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Organization Add Add New licence to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddNewLicencePost(Request $request) {
        if ($this->user->can('add-licence-org')) {
            if ($request->ajax()) {

                $validatedData = $request->validate([
                    'user_id' => 'required',
                    'course_sub_id' => 'required',
                    'course_id' => 'required',
                ]);

                $course_sub_id = decrypt($request->course_sub_id);
                $course_id = decrypt($request->course_id);

                $insertData = [
                    'user_id' => $request->user_id,
                    'course_subscription_id' => $course_sub_id,
                    'course_id' => $course_id,
                ];

                $licence = CourseSubscriptionLicence::create($insertData);


                $user_id = $this->user->id;
                $subscription_id = $course_sub_id;
                $coursesubscription = CourseSubscription::with('coursesublicence')->where('user_id', $user_id)->where('id', $subscription_id)->first();
                $licence_used = count($coursesubscription->coursesublicence);


                return ["success" => true, "message" => "User Added successfully", "total" => $coursesubscription->no_of_licence, "used" => $licence_used, "remain" => $coursesubscription->no_of_licence - $licence_used];
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Delete course User from licence for organization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function RemoveCourseLicence(Request $request, $id) {
        if ($this->user->can('remove-licence-org')) {
            if ($request->ajax()) {
                $id = decrypt($id);
                $subscriptionData = CourseSubscriptionLicence::where('id', $id)->get()->toArray();
                $data = CourseSubscriptionLicence::where('id', $id)->delete();
                $user_id = $this->user->id;
                $subscription_id = $subscriptionData[0]['course_subscription_id'];
                $coursesubscription = CourseSubscription::with('coursesublicence')->where('user_id', $user_id)->where('id', $subscription_id)->first();
                $licence_used = count($coursesubscription->coursesublicence);

                if ($data) {
                    return ["success" => true, "message" => "User deleted successfully", "total" => $coursesubscription->no_of_licence, "used" => $licence_used, "remain" => $coursesubscription->no_of_licence - $licence_used];
                } else {
                    return ["success" => false, "message" => "Data not found"];
                }
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Display a listing of the course related to organization.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetOrganizationcourse(OrganizationSubscribeCourseDataTable $dataTable) {
        if ($this->user->can('view-subscribe-course-org')) {
            return $dataTable->render('front.organization.SubscribeCourse');
        }
        abort(403);
    }

    /**
     * Display a listing of the user for organization.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetMyUser(MyUserOrgDataTable $dataTable) {
        if ($this->user->can('view-my-user-org')) {
            return $dataTable->render('front.organization.my-user.index');
        }
        abort(403);
    }

    /**
     * Get Course question details for organization
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetUserCourseDetails(Request $request, GetOrgUserCourseDataTable $dataTable) {
        if ($this->user->can('view-org-user-course')) {
            if ($request->ajax()) {
                $validatedData = $request->validate(['id' => $request->id], [
                    'id' => 'required',
                ]);
                return $dataTable->render('front.organization.my-user.viewusercourse');
            }
            abort(404);
        }
        abort(403);
    }

}
