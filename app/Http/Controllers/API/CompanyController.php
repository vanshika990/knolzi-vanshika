<?php

namespace App\Http\Controllers\API;

use DB;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use App\Mail\SendEmail;
use App\Models\User;
use App\Models\Course;
use App\Models\Userinvitation;
use App\Models\QuestionIntent;
use App\Models\UserHasOrganization;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;

class CompanyController extends BaseController {

    public $paginationlimit = 10;

    /**
     * Get Update Company Profile api
     *
     * @return \Illuminate\Http\Response
     */
    public function UpdateCompanyProfile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:App\Models\User,id,status,1',
                    'name' => 'required',
                    'company_name' => 'required',
                    'address' => 'required',
                    'company_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        $id = $input['id'];
        $user = User::Where('id', $id)->update($input);
        $success['data'] = $user;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Create or Update Professional Qualification info user api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetListOfCompanyUser(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:App\Models\User,id,status,1',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $company_id = $request->id;
        // $data = User::where('company_id', $company_id)->where('status', '1')->paginate($this->paginationlimit, ['*']);

        $data = UserHasOrganization::where('org_id', $company_id)->with(array('user' => function($query) {
                $query->select('id', 'name', 'email');
            }))->paginate($this->paginationlimit);

        $success['data'] = $data;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Delete Individual User From Company api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function DeleteUserFromCompany(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:App\Models\User,id,status,1',
                    'company_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = User::select('name')->where('id', $request->id)->first();
        $user = UserHasOrganization::where('org_id', $request->company_id)->where('user_id', $request->id)->delete();
        if ($user == 1) {
            $success['success'] = true;
            return $this->sendResponse($success, $data['name'] . ' has remove Successfully.');
        } else {
            $success['success'] = false;
            return $this->sendResponse($success, 'Something went wrong.');
        }
    }

    /**
     * User List Invitation api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetUserListInvitation(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $company_id = $request->company_id;
        $invitation = Userinvitation::where('company_id', $company_id)->paginate($this->paginationlimit, ['*']);
        $success['data'] = $invitation;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * User Invitation api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function createinvitation(Request $request) {
        $email_id = $request->email_id;
        $validator = Validator::make($request->all(), [
//                    'user_email' => 'required|email:rfc,dns',
                    'user_email' => 'required|email:rfc,dns|unique:tbl_users_invitation,user_email,' . $email_id . '|unique:tbl_user,email',
                    'company_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $invitation = Userinvitation::Where(['user_email' => $request->user_email, 'company_id' => $request->company_id])->first();
        if (empty($invitation)) {
            $code = strtoupper(Str::random(6));
            $insert = [
                'company_code' => $code,
                'status' => '0',
                'company_id' => $request->company_id,
                'user_email' => $request->user_email,
            ];
            $to = $request->user_email;
            $data = [
                'template' => 'invitations',
                'html_body' => [
                    'button_code' => $code,
                ],
                'subject' => 'Knolzi Invitation'
            ];
            \Mail::to($to)->send(new SendEmail($data));
            Userinvitation::create($insert);
            $success['success'] = true;
            return $this->sendResponse($success, 'Invitation Successfully send.');
        } else {
            return $this->sendError('this email all ready sent invitation. please check your invitation list and resend.', $request->user_email);
        }
    }

    /**
     * Delete User Invitation api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function DeleteUserInvitation(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:App\Models\Userinvitation,id,status,0',
                    'company_id' => 'required|exists:App\Models\Userinvitation,company_id,id,' . $request->id . '|exists:App\Models\User,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        Userinvitation::where('id', $request->id)->delete();
        $success['success'] = true;
        return $this->sendResponse($success, 'Invitation delete successfully.');
    }

    /**
     * Resend User Invitation api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function ResendUserInvitation(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_email' => 'required|email:rfc,dns|exists:App\Models\Userinvitation,user_email,company_id,' . $request->company_id . ',status,0',
                    'company_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $invitation = Userinvitation::Where(['user_email' => $request->user_email, 'company_id' => $request->company_id])->first();

        $code = strtoupper(Str::random(6));
        $tomail = $request->user_email;
        $data = [
            'template' => 'invitations',
            'html_body' => [
                'button_code' => $code,
            ],
            'subject' => 'Knolzi Invitation'
        ];
        \Mail::to($tomail)->send(new SendEmail($data));

        $update_data = [
            'resend' => $invitation->resend + 1,
            'company_code' => $code,
        ];
        Userinvitation::where('id', $invitation->id)->update($update_data);

        $success['success'] = true;
        return $this->sendResponse($success, 'Resend Invitation Successfully send.');
    }

    /**
     * Individual/COmpany User Dashboard graph api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function IndividualUserDashboard(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $company_id[] = $request->user_id;
        if (isset($request->company_id)) {
            $company_id[] = $request->company_id;
        }

        $all_sub_course = CourseSubscription::select('tbl_course_subscription.course_id', 'tbl_user_course_attempt.id')
                        ->leftJoin('tbl_user_course_attempt', 'tbl_course_subscription.course_id', '=', 'tbl_user_course_attempt.course_id')
                        ->whereIn('tbl_user_course_attempt.id', array(DB::raw("(SELECT MAX(id) FROM tbl_user_course_attempt where `user_id` = $user_id GROUP BY course_id)")))
                        ->whereIn('tbl_course_subscription.user_id', $company_id)
                        ->groupBy('tbl_course_subscription.course_id')->get()->toArray();

        $question_intents = [];
        if (!empty($all_sub_course)) {
            $i = 0;
            foreach ($all_sub_course as $row_sub_course) {
                $course_id = $row_sub_course['course_id'];
                $attempt_id = $row_sub_course['id'];
                $rowDetail = [
                    'course_id' => $row_sub_course['course_id'],
                    'attempt_id' => $row_sub_course['id'],
                ];
                $course = Course::select(['course_name'])->where(['course_id' => $course_id])->first();
                $question_intents[$i]['course_name'] = $course['course_name'];
                $question_intents[$i]['intent_data'] = $this->query($rowDetail, $user_id, "total_count");
                $rightCount = $this->query($rowDetail, $user_id, "right_count");
                $j = 0;
                foreach ($question_intents[$i]['intent_data'] as $intdata) {
                    $id = $intdata['id'];
                    foreach ($rightCount as $key => $val) {
                        if ($val['id'] == $id) {
                            $question_intents[$i]['intent_data'][$j]['right_question_count'] = $val['right_question_count'];
                            $question_intents[$i]['intent_data'][$j]['percentage'] = $val['right_question_count'] * 100 / $question_intents[$i]['intent_data'][$j]['question_intent_count'];
                        }
                    }
                    if (!isset($question_intents[$i]['intent_data'][$j]['right_question_count'])) {
                        $question_intents[$i]['intent_data'][$j]['right_question_count'] = 0;
                        $question_intents[$i]['intent_data'][$j]['percentage'] = 0;
                    }
                    unset($question_intents[$i]['intent_data'][$j]['id']);
                    unset($question_intents[$i]['intent_data'][$j]['right_question_count']);
                    unset($question_intents[$i]['intent_data'][$j]['question_intent_count']);

                    $j++;
                }
                $i++;
            }
            return $this->sendResponse($question_intents, 'Success.');
        }
        return $this->sendError('Error.', ['status' => ['no subscribe course found!.']]);
    }

    /**
     * Individual user dashboard graph
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function UserDashboard(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'company_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $company_id = $request->company_id;
        $user_id = $request->user_id;

        $all_sub_course = CourseSubscription::select('tbl_course_subscription.course_id', 'tbl_user_course_attempt.id')
                        ->leftJoin('tbl_user_course_attempt', 'tbl_course_subscription.course_id', '=', 'tbl_user_course_attempt.course_id')
                        ->where('tbl_course_subscription.user_id', $company_id)
                        ->whereIn('tbl_user_course_attempt.id', array(DB::raw("(SELECT MAX(id) FROM tbl_user_course_attempt where `user_id` = $user_id GROUP BY course_id)")))->get()->toArray();

        $question_intents = [];
        if (!empty($all_sub_course)) {
            $i = 0;
            foreach ($all_sub_course as $row_sub_course) {
                $course_id = $row_sub_course['course_id'];
                $attempt_id = $row_sub_course['id'];
                $rowDetail = [
                    'course_id' => $row_sub_course['course_id'],
                    'attempt_id' => $row_sub_course['id'],
                ];
                $course = Course::select(['course_name'])->where(['course_id' => $course_id])->first();
                $question_intents[$i]['course_name'] = $course['course_name'];
                $question_intents[$i]['intent_data'] = $this->query($rowDetail, $user_id, "total_count");
                $rightCount = $this->query($rowDetail, $user_id, "right_count");

                $j = 0;
                foreach ($question_intents[$i]['intent_data'] as $intdata) {
                    $id = $intdata['id'];
                    foreach ($rightCount as $key => $val) {
                        if ($val['id'] == $id) {
                            $question_intents[$i]['intent_data'][$j]['right_question_count'] = $val['right_question_count'];
                            $question_intents[$i]['intent_data'][$j]['percentage'] = $val['right_question_count'] * 100 / $question_intents[$i]['intent_data'][$j]['question_intent_count'];
                        }
                    }
                    if (!isset($question_intents[$i]['intent_data'][$j]['right_question_count'])) {
                        $question_intents[$i]['intent_data'][$j]['right_question_count'] = 0;
                        $question_intents[$i]['intent_data'][$j]['percentage'] = 0;
                    }
                    unset($question_intents[$i]['intent_data'][$j]['id']);
                    unset($question_intents[$i]['intent_data'][$j]['right_question_count']);
                    unset($question_intents[$i]['intent_data'][$j]['question_intent_count']);
                    $j++;
                }
                $i++;
            }
            return $this->sendResponse($question_intents, 'Success.');
        }
        return $this->sendError('Error.', ['status' => ['no subscribe course found!.']]);
    }

    /**
     * User dashboard
     * @param type $allCourse
     * @param type $user_id
     * @param type $type
     * @return type
     */
    public function query($allCourse, $user_id, $type) {
        $course_id = $allCourse['course_id'];
        $attempt_id = $allCourse['attempt_id'];
        if ($type == 'total_count') {
            return QuestionIntent::select('tbl_question_intent.id', 'tbl_question_intent.name', DB::raw('COUNT(`tbl_user_question_attempt_history`.`id`) AS `question_intent_count`'))
                            ->leftjoin("tbl_course_question", DB::raw("FIND_IN_SET(`tbl_question_intent`.`id`,`tbl_course_question`.`question_intent_id`)"), ">", DB::raw("'0'"))
                            ->leftjoin("tbl_user_question_attempt_history", "tbl_user_question_attempt_history.question_id", "=", "tbl_course_question.id")
                            ->where('tbl_course_question.course_id', $course_id)
                            ->where('tbl_course_question.is_delete', '0')
                            ->where('tbl_course_question.status', '1')
                            ->where('tbl_user_question_attempt_history.user_id', $user_id)
                            ->where('tbl_user_question_attempt_history.course_attempt_id', $attempt_id)
                            ->groupBy('tbl_question_intent.name')->get();
        }
        if ($type == 'right_count') {

            return QuestionIntent::select('tbl_question_intent.id', 'tbl_question_intent.name', DB::raw('COUNT(`tbl_course_question`.`question_intent_id`) AS `right_question_count`'))
                            ->leftjoin("tbl_course_question", DB::raw("FIND_IN_SET(`tbl_question_intent`.`id`,`tbl_course_question`.`question_intent_id`)"), ">", DB::raw("'0'"))
                            ->where('tbl_course_question.course_id', $course_id)
                            ->where('tbl_course_question.is_delete', '0')
                            ->where('tbl_course_question.status', '1')
                            ->whereIn('tbl_course_question.id', [DB::raw("SELECT tbl_user_question_attempt_history.question_id
                    FROM tbl_user_question_attempt_history
                    WHERE tbl_user_question_attempt_history.course_id =$course_id
                    AND tbl_user_question_attempt_history.user_id=$user_id
                    AND tbl_user_question_attempt_history.course_attempt_id=$attempt_id
                    AND `tbl_user_question_attempt_history`.`rightanswer`='1'
                    GROUP BY question_id")])
                            ->groupBy('tbl_question_intent.name')->get();
        }
    }

    /**
     * Company Licence Details
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function LicenseDetails(Request $request) {
        $validator = Validator::make($request->all(), [
                    'subscription_id' => 'required|exists:App\Models\CourseSubscription,id,status,1',
                    'user_id' => 'required|exists:App\Models\CourseSubscription,user_id,id,' . $request->subscription_id . ''
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $subId = $request->subscription_id;
        $courseSubscription = CourseSubscription::select(['id', 'no_of_licence', 'course_id'])->with('course:course_id,course_name')->where('id', $subId)->first()->toArray();

        $licData = CourseSubscriptionLicence::select(['id', 'user_id'])->where('course_subscription_id', $subId)->with('user:id,name')->where('status', '1')->get()->toArray();
        $current_licence = [];
        foreach ($licData as $key => $value) {
            $current_licence[$key]['licence_id'] = $value['id'];
            $current_licence[$key]['username'] = $value['user']['name'];
        }
        $data['course_name'] = $courseSubscription['course']['course_name'];
        $data['current_license'] = $current_licence;
        $data['total_license'] = $courseSubscription['no_of_licence'];
        $data['used_license_total'] = count($current_licence);
        $data['remaining_license_total'] = $courseSubscription['no_of_licence'] - count($current_licence);
        return $this->sendResponse($data, 'Success.');
    }

    /**
     * Select License for member
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SelectLicenseMember(Request $request) {
        $validator = Validator::make($request->all(), [
                    'company_id' => 'required|exists:App\Models\User,id,status,1',
                    'course_id' => 'required|exists:App\Models\Course,course_id,is_delete,0'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $userData = User::select(['id', 'name'])
                        ->whereNotIn('id', CourseSubscriptionLicence::where('user_id', $request->company_id)->where('course_id', $request->course_id)->where('status', '1')->pluck('user_id')->toArray())
                        ->whereIn('id', UserHasOrganization::where('org_id', $request->company_id)->pluck('user_id')->toArray())->get()->toArray();
        $success['data'] = $userData;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Add license to user
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function Addlicensetouser(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id,status,1',
                    'course_id' => 'required|exists:App\Models\Course,course_id,is_delete,0',
                    'subscription_id' => 'required|exists:App\Models\CourseSubscription,id,status,1',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $count = CourseSubscriptionLicence::where(['course_subscription_id' => $request->subscription_id, 'user_id' => $request->user_id, 'course_id' => $request->course_id, 'status' => '1'])->count();
        if ($count != 0) {
            return $this->sendError('Error.', ['status' => ['Selected user all ready license taken in this course.']]);
        }
        CourseSubscriptionLicence::create([
            'course_subscription_id' => $request->subscription_id,
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
        ]);
        $success['success'] = true;
        return $this->sendResponse($success, 'add license successfully.');
    }

    /**
     * Delete licence
     *
     * @return \Illuminate\Http\Response
     */
    public function Deletelicensetouser(Request $request) {
        $validator = Validator::make($request->all(), [
                    'license_id' => 'required|exists:App\Models\CourseSubscriptionLicence,id,status,1',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        CourseSubscriptionLicence::Where('id', $request->license_id)->update(['status' => '0']);
        $success['success'] = true;
        return $this->sendResponse($success, 'license removed successfully.');
    }

}
