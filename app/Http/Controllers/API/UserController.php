<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Validator;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserWorkQualification;
use App\Models\CourseSubscription;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Userinvitation;
use App\Models\UserHasOrganization;
use App\Models\Feedback;
use App\Models\NotificationHistory;
use App\Models\Payment;
use App\Models\CourseSubscriptionLicence;
use App\Mail\FeedbackEmail;
use App\Mail\SendEmail;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\Support\Str;
use paytm\paytmchecksum\PaytmChecksum;
use Stevebauman\Location\Facades\Location;

class UserController extends BaseController {

    public $paginationlimit = 10;

    /**
     * Register api
     * @param Request $request
     * @return type
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:tbl_user',
                    'password' => 'required',
                    'date_of_birth' => 'required',
                    'c_password' => 'required|same:password',
                    'role_id' => 'required|' . Rule::in([2, 3]),
                    'mobile_no' => 'required|digits:10',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        if ($input['role_id'] == 2) {
            $user->assignRole('individual');
        } else {
            $user->assignRole('organization');
        }

        if (isset($input['company_code']) && $input['company_code'] != '') {
            $invitation = Userinvitation::select('*')->where('company_code', $input['company_code'])->where('status', '0')->first();
            if (!empty($invitation)) {
                $company_user = User::where('email', $invitation['user_email'])->get()->first();
                if (!empty($company_user)) {
                    UserHasOrganization::create(['org_id' => $invitation['company_id'], 'user_id' => $user->id]);
                    $invitation->update(['status' => '1']);
                }
            } else {
                return $this->sendError('Invalid company code.', ['company_code' => ['Invalid company code.']]);
            }
        }

        $user->sendEmailVerificationNotification();
        $success = [];
        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';
        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Delete Account
     * @param Request $request
     * @return type
     */
    public function deleteaccount(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:tbl_user,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::find($request->user_id);
        $user->delete();

        $success['success'] = true;
        return $this->sendResponse($success, 'Account Deleted successfully.');
    }

    /**
     * Login api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            if ($user->email_verified_at !== NULL) {
                $status = (string) $user->status;
                if (($status == "1") && ($user->hasAnyRole(['reviewer', 'individual', 'organization']))) {
                    $this->RevokTokenForUser($user->id);
                    $success['token'] = $user->createToken($user->email . '_Knolzi')->accessToken;
                    $user->role_id = $user->roles()->first()->id;
                    $org = UserHasOrganization::select('org_id')->where('user_id', $user->id)->first();
                    if (!empty($org)) {
                        $user->company_id = $org->org_id;
                    } else {
                        $user->company_id = "";
                    }
                    unset($user->roles);
                    $position = Location::get($request->ip());
                    if ((!isset($position->countryName) && empty($position->countryName)) || ($position->countryName != 'India')) {
                        $user->countryName = 'United States';
                    } else {
                        $user->countryName = $position->countryName;
                    }
                    $success['data'] = $user;
                    return $this->sendResponse($success, 'User login successfully.');
                } else if ($status == "0") {
                    return $this->sendError('Account not activated.', ['status' => ['Your accont is disabled.']]);
                } else if ($status == "2") {
                    return $this->sendError('Account block.', ['status' => ['Your accont is block.']]);
                } else {
                    return $this->sendError('Wrong.', ['status' => ['Something went wrong!.']]);
                }
            } else {
                return $this->sendError('Verify Emai', ['email' => ['Please Verify Emai']]);
            }
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Invalid username or password']);
        }
    }

    /**
     * Login with Apple
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function LoginwithApple(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:tbl_user',
                    'name' => 'required',
                    'token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $email = $request->email;
        $name = $request->name;
        $user = $this->checkUserExist($email);
        if (!$user) {
            $user = User::create([
                        'email' => $email,
                        'name' => $name,
                        'password' => Hash::make(rand(1, 10000)),
                        'source_from' => "Apple",
                        'status' => "1",
                        'email_verified_at' => now(),
                        'apple_token' => $request->token,
            ]);
            $user->assignRole('individual');
            $data = [
                'template' => 'emailVerificationSuccess',
                'html_body' => [
                    'name' => $name,
                ],
                'subject' => 'Hello & Welcome'
            ];
            $to = $email;
            \Mail::to($to)->send(new SendEmail($data));
        } else {
            $email = $user->email;
            $this->RevokTokenForUser($user->id);
        }

        if ($user->status != '1') {
            return $this->sendError('Disable Account', ['account' => ['Your account is disable or something went wrong!']]);
        }
        Auth::loginUsingId($user->id);
        $success['token'] = $user->createToken($email)->accessToken;
        $user->role_id = $user->roles()->first()->id;
        unset($user->roles);
        $success['data'] = $user;

        return $this->sendResponse($success, 'User login successfully.');
    }

    /**
     * Verify Apple Token
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function VerifyAppleToken(Request $request) {
        $validator = Validator::make($request->all(), [
                    'token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = $this->checkUserAppleToken($request->token);
        if (!empty($user)) {
            $user->assignRole('individual');
            $email = $user->email;
            $this->RevokTokenForUser($user->id);

            Auth::loginUsingId($user->id);
            $success['token'] = $user->createToken($email)->accessToken;
            $user->role_id = $user->roles()->first()->id;
            unset($user->roles);
            $success['data'] = $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Token not found', ['account' => ['Token not found!']]);
        }
    }

    /**
     * Login with Google
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function LoginwithGoogle(Request $request) {
        $validator = Validator::make($request->all(), [
                    'token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $all_details = $this->GetDetailsFromGoogle($request->token);
        if (empty($all_details['email'])) {
            return $this->sendError('Email Error.', ['email' => ['Email was not found!']]);
        }
        $email = $all_details['email'];

        $user = $this->checkUserExist($email);
        if (!$user) {
            $user = User::create([
                        'email' => $email,
                        'name' => $all_details['name'],
                        'password' => Hash::make(rand(1, 10000)),
                        'source_from' => "Google",
                        'status' => "1",
                        'email_verified_at' => now(),
            ]);
            $user->assignRole('individual');
            $data = [
                'template' => 'emailVerificationSuccess',
                'html_body' => [
                    'name' => $all_details['name'],
                ],
                'subject' => 'Hello & Welcome'
            ];
            $to = $email;
            \Mail::to($to)->send(new SendEmail($data));
        } else {
            $email = $user->email;
            $this->RevokTokenForUser($user->id);
        }

        if ($user->status != '1') {
            return $this->sendError('Disable Account', ['account' => ['Your account is disable or something went wrong!']]);
        }
        Auth::loginUsingId($user->id);
        $success['token'] = $user->createToken($email)->accessToken;
        $user->role_id = $user->roles()->first()->id;
        unset($user->roles);
        $success['data'] = $user;

        return $this->sendResponse($success, 'User login successfully.');
    }

    /**
     * Get Details From Google api
     * @param type $token
     * @return type
     */
    public function GetDetailsFromGoogle($token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/oauth2/v3/userinfo",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $token",
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * Login with Facebook
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function loginWithFacebook(Request $request) {
        $validator = Validator::make($request->all(), [
                    'token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $all_details = $this->GetDetailsfromFacebook($request->token);
        if (!$request->has('email')) {
            if (empty($all_details['email'])) {
                return $this->sendError('Email not Found from Facebook', ['email' => ['Email was not found!']]);
            }
        } else {
            $validator = Validator::make($request->all(), [
                        'email' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $all_details['email'] = $request->email;
        }
        $email = $all_details['email'];

        $user = $this->checkUserExist($email);
        if (!$user) {
            $user = User::create([
                        'email' => $email,
                        'name' => $all_details['name'],
                        'password' => Hash::make(rand(1, 10000)),
                        'status' => "1",
                        'source_from' => "Facebook",
                        'email_verified_at' => now(),
            ]);
            $user->assignRole('individual');
            $data = [
                'template' => 'emailVerificationSuccess',
                'html_body' => [
                    'name' => $all_details['name'],
                ],
                'subject' => 'Hello & Welcome'
            ];
            $to = $email;
            \Mail::to($to)->send(new SendEmail($data));
            $user = User::find($user->id);
        } else {
            $email = $user->email;
            $this->RevokTokenForUser($user->id);
        }
        if ($user->status != '1') {
            return $this->sendError('Disable Account', ['account' => ['Your account is disable or something went wrong!']]);
        }

        Auth::loginUsingId($user->id);
        $success['token'] = $user->createToken($email)->accessToken;
        $user->role_id = $user->roles()->first()->id;
        unset($user->roles);
        $success['data'] = $user;

        return $this->sendResponse($success, 'User login successfully.');
    }

    /**
     * Get Details From Facebook api
     * @param type $token
     * @return type
     */
    public function GetDetailsfromFacebook($token) {
        $appSecretProof = hash_hmac('sha256', $token, env("FACEBOOK_SECRET_ID", '4bc1bc2ebbd5844a9f9e6a575c78b9a1'));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/v3.3/me?access_token=$token&appsecret_proof=$appSecretProof&fields=name,email,verified,gender,link",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * Logout api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function logout(Request $request) {
        $user = Auth::user();
        $user_id = $user->id;
        $this->RevokTokenForUser($user_id);
        $request->user()->token()->revoke();
        $success['success'] = true;
        return $this->sendResponse($success, 'You have been succesfully logged out!');
    }

    /**
     * Forgot Password
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => "required|email",
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'));
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        $success['success'] = true;
                        return $this->sendResponse($success, trans($response));
                    case Password::INVALID_USER:
                        return $this->sendError('Invalid user.', trans($response));
                }
            } catch (\Swift_TransportException $ex) {
                return $this->sendError('Error.', $ex->getMessage());
            } catch (Exception $ex) {
                return $this->sendError('Error.', $ex->getMessage());
            }
        }
    }

    /**
     * User Change password api
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'old_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $input = $request->all();
            $userid = Auth::guard('api')->user()->id;
            $arr = [];
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => false, "message" => "Check your old password.");
                    return $this->sendError('Check your old password.', $arr);
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => false, "message" => "Please enter a password which is not similar then current password.");
                    return $this->sendError('Please enter a password which is not similar then current password.', $arr);
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => true, "message" => "Password updated successfully.");
                    return $this->sendResponse($arr, 'Password updated successfully.');
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => false, "message" => $msg);
                return $this->sendError($msg, $arr);
            }
        }
    }

    /**
     * Login User details
     *
     * @return \Illuminate\Http\Response
     */
    public function Getuserdetails() {
        $user = Auth::user();
        return $this->sendResponse($user, 'Success.');
    }

    /**
     * User Personal Information api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getUserDetailsbyId(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:tbl_user,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = User::select(['id', 'name', 'email', 'mobile_no', 'age_group', 'skillstest', 'goal', 'time_frame', 'profile_image', 'company_name', 'address'])->where('id', $request->id)->first();
        return $this->sendResponse($user, 'Success.');
    }

    /**
     * update personal information of user api
     *
     * @return \Illuminate\Http\Response
     */
    public function editPersonalInfo(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:tbl_user,id',
                    'name' => 'required',
                    'age_group' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = [
            'name' => request('name'),
            'age_group' => request('age_group'),
            'updated_at' => now(),
        ];
        $id = $request->id;
        $user = User::where('id', $id)->update($input);
        $success = User::select(['id', 'name', 'age_group'])->where('id', $id)->get();
        return $this->sendResponse($success, 'Personal infromation updated successfully.');
    }

    /**
     * update User Profile Image api
     *
     * @return \Illuminate\Http\Response
     */
    public function EditUserProfileImage(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:tbl_user,id',
                    'user_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        $id = $request->user_id;
        $user_data = [];
        if ($request->hasFile('user_image')) {
            $upload_to_s3 = new \App\Helper\DocumentUploadS3Helper();
            $user_data['profile_image'] = $upload_to_s3->uploadToBucketNew('user', $request->file('user_image'));
            $user = User::where('id', $id)->update($user_data);
            $success = User::select(['id', 'name', 'age_group', 'profile_image'])->where('id', $id)->get();
            return $this->sendResponse($success, 'User infromation updated successfully.');
        } else {
            return $this->sendError('Wrong.', ['status' => ['Something went wrong!.']]);
        }
    }

    /**
     * Get Education Qualification info user api
     *
     * @return \Illuminate\Http\Response
     */
    public function getEduQuaInfo(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:tbl_edu_qualification,user_id',
                        ], [
                    'exists' => 'Education Qualification not found!'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $usereducation = UserEducation::select(['id', 'user_id', 'degree', 'university', 'institute', 'stream', 'year', 'grade'])->where('user_id', request('user_id'))->get();
        $success = $usereducation;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Create or Update Education Qualification info user api
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrUpdateEduQuainfo(Request $request) {
        if (is_null($request->id)) {
            $validator = Validator::make($request->all(), [
                        'user_id' => 'required|exists:tbl_user,id',
                        'degree' => 'required',
                        'university' => 'required',
                        'institute' => 'required',
                        'stream' => 'required',
                            ], [
                        'exists' => 'User not found!'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $input = $request->all();
            $usereducation = UserEducation::create($input);
            $success = UserEducation::select(['id', 'user_id', 'degree', 'university', 'institute', 'stream', 'year', 'grade'])->find($usereducation->id);
            return $this->sendResponse($success, 'User education information created successfully.');
        } else {
            $validator = Validator::make($request->all(), [
                        'id' => 'required|exists:tbl_edu_qualification,id,user_id,' . $request->user_id . '',
                        'user_id' => 'required',
                        'degree' => 'required',
                        'university' => 'required',
                        'institute' => 'required',
                        'stream' => 'required',
                            ], [
                        'exists' => 'Qualification not found!'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $input = $request->all();
            $id = $input['id'];
            $usereducation = UserEducation::where('id', $id)->update($input);
            $success = UserEducation::select(['id', 'user_id', 'degree', 'university', 'institute', 'stream', 'year', 'grade'])->find($id);
            return $this->sendResponse($success, 'Education Qualification updated successfully.');
        }
    }

    /**
     * Delete Education Qualification User ID api
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteEduQualification(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:tbl_edu_qualification,id',
                        ], [
                    'exists' => 'Education Qualification not found!'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $usereducation = UserEducation::where('id', request('id'))->delete();
        $success = [];
        $success['success'] = true;
        return $this->sendResponse($success, 'Education Qualification are successfully deleted.');
    }

    /**
     * Get Professional Qualification info user api
     *
     * @return \Illuminate\Http\Response
     */
    public function getProQuaInfo(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:tbl_pro_qualification,user_id',
                        ], [
                    'exists' => 'Professional Qualification not found!'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $userworkqualification = UserWorkQualification::select(['id', 'user_id', 'company_name', 'domain', 'role', 'designation', 'year', 'experience'])->where('user_id', request('user_id'))->get();
        $success = $userworkqualification;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Create or Update Professional Qualification info user api
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrUpdateProQuaInfo(Request $request) {
        if (is_null($request->id)) {
            $validator = Validator::make($request->all(), [
                        'user_id' => 'required|exists:tbl_user,id',
                        'company_name' => 'required',
                        'role' => 'required',
                        'designation' => 'required',
                        'year' => 'required',
                        'experience' => 'required',
                            ], [
                        'exists' => 'User ID not found!'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $input = $request->all();
            $user = UserWorkQualification::create($input);
            $success = UserWorkQualification::select(['id', 'user_id', 'company_name', 'domain', 'role', 'designation', 'year', 'experience'])->find($user->id);
            return $this->sendResponse($success, 'User professional qualification created successfully.');
        } else {
            $validator = Validator::make($request->all(), [
                        'id' => 'required|exists:tbl_pro_qualification,id,user_id,' . $request->user_id . '',
                        'user_id' => 'required|exists:tbl_pro_qualification,user_id',
                        'company_name' => 'required',
                        'role' => 'required',
                        'designation' => 'required',
                        'year' => 'required',
                        'experience' => 'required',
                            ], [
                        'exists' => 'User Professional Qualification not found!'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $input = $request->all();
            $id = $input['id'];
            $userworkqualification = UserWorkQualification::where('id', $id)->update($input);
            $success = UserWorkQualification::select(['id', 'user_id', 'company_name', 'domain', 'role', 'designation', 'year', 'experience'])->find($id);
            return $this->sendResponse($success, 'Professional qualification updated successfully.');
        }
    }

    /**
     * Delete Professional Qualification of User api
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteProQualification(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:tbl_pro_qualification,id',
                        ], [
                    'exists' => 'User Professional Qualification not found!'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $userworkqualification = UserWorkQualification::where('id', request('id'))->delete();
        $success = [];
        $success['success'] = true;
        return $this->sendResponse($success, 'Professional Qualification deleted successfully.');
    }

    /**
     * Get Billing History
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetBillingHistory(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:tbl_user,id',
        ]);
        $new_array = [];
        $new_data = [];
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = request('user_id');
        $data = CourseSubscription::with('course')->whereHas('course', function ($query) {
                    $query->where('status', '1');
                    $query->where('is_delete', '0');
                })->where('user_id', $user_id)->where('status', '1')->get()->toArray();

        foreach ($data as $company_row) {
            $new_array['course_name'] = $company_row['course']['course_name'];
            if (!empty($company_row['sub_expire_date'])) {
                $new_array['sub_expire_date'] = date('d-m-Y', strtotime($company_row['sub_expire_date']));
            } else {
                $new_array['sub_expire_date'] = "-";
            }
            if (strtotime("now") <= strtotime($company_row['sub_expire_date'])) {
                $new_array['status'] = $company_row['status'];
            } else {
                $new_array['status'] = 0;
            }
            $new_array['amount_to_be_paid'] = $company_row['amount_to_be_paid'];
            $new_data[] = $new_array;
        }
        if (empty($new_data)) {
            return $this->sendError('Error.', ['id' => ['User Billing History  not found!']]);
        }
        $success = $new_data;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Get Revoke Token For User
     * @param type $user_id
     * @return boolean
     */
    public function RevokTokenForUser($user_id) {
        $users = \Laravel\Passport\Token::where('user_id', $user_id)
                ->delete();
        $current_date = date('Y-m-d H:i:s');
        User::where('id', $user_id)->update(['last_login_time' => $current_date]);
        return true;
    }

    /**
     * Check User exist or not
     * @param type $email
     * @return type
     */
    public function checkUserExist($email) {
        return User::whereEmail($email)->first();
    }

    /**
     * Verify User Apple Token
     * @param type $email
     * @return type
     */
    public function checkUserAppleToken($token) {
        return User::where('apple_token', $token)->first();
    }

    /**
     * Get User Feedback From App api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetUserFeedback(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_email' => 'required',
                    'feedback_type' => 'required',
                    'feedback_message' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = [
            'user_email' => $request->user_email,
            'feedback_type' => $request->feedback_type,
            'feedback_message' => $request->feedback_message,
        ];
        \Mail::to('support@edupme.com')->send(new FeedbackEmail($data));

        $all_data = Feedback::create($request->all());
        $success['success'] = true;
        return $this->sendResponse($success, 'Feedback Successfully submited.');
    }

    /**
     * My WhishList
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function MyWishLists(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $subscribe_course = [];

        $company_data = User::select('tbl_user_has_org.org_id')->leftJoin('tbl_user_has_org', 'tbl_user.id', '=', 'tbl_user_has_org.user_id')->where('tbl_user_has_org.user_id', $user_id)->first();
        $company_id = [$user_id];
        if (!empty($company_data)) {
            array_push($company_id, $company_data['org_id']);
        }
        $query = CourseSubscription::leftJoin('tbl_course', 'tbl_course_subscription.course_id', '=', 'tbl_course.course_id')
                ->where('tbl_course.status', '1')
                ->whereIn('tbl_course_subscription.user_id', $company_id)
                ->where('tbl_course_subscription.status', '1')
                ->where('tbl_course_subscription.sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                ->groupBy('tbl_course.course_id');
        if (!Auth::user()->hasRole('organization')) {
            $query->leftJoin('tbl_course_subscription_licence', 'tbl_course_subscription.id', '=', 'tbl_course_subscription_licence.course_subscription_id');
            $query->where('tbl_course_subscription_licence.status', '1');
            $query->where('tbl_course_subscription_licence.user_id', $user_id);
        }
        $subscribe_course = $query->select('tbl_course.course_id')->pluck('tbl_course.course_id')->toArray();

        $all_wishlist = Wishlist::select('tbl_wishlists.id', 'tbl_course.course_id', 'tbl_course.course_sub_description', 'tbl_course.course_name', 'tbl_course.course_image', 'tbl_course.course_price', DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'))
                ->leftJoin('tbl_course', 'tbl_wishlists.course_id', '=', 'tbl_course.course_id')
                ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                ->selectRaw('LEFT(tbl_course.course_description, 150) AS course_description')
                ->where('tbl_course.status', '1')
                ->where('tbl_course.is_delete', '0')
                ->where('tbl_wishlists.user_id', $user_id)
                ->whereNotIn('tbl_course.course_id', $subscribe_course)
                ->groupBy('tbl_course.course_id')
                ->paginate($this->paginationlimit);
        if (!empty($all_wishlist)) {
            for ($i = 0; $i < count($all_wishlist); $i++) {
                $all_wishlist[$i]->course_price = (string) currencyConvert($all_wishlist[$i]->course_price);
                $all_wishlist[$i]->symbol = getCurrencySymbol();
            }
        }
        $success = $all_wishlist;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Add to Wishlist
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function AddWishLists(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $course_id = $request->course_id;
        $insert = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        Cart::where(['user_id' => $user_id, 'course_id' => $course_id])->delete();
        $lastInsertedData = Wishlist::updateOrCreate(['user_id' => $user_id, 'course_id' => $course_id], $insert);
        $data['wishlist_id'] = $lastInsertedData['id'];
        $success['data'] = $data;
        return $this->sendResponse($success, 'Wishlist added successfully.');
    }

    /**
     * Remove wishlist
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function RemoveWishLists(Request $request) {
        $validator = Validator::make($request->all(), [
                    'wishlist_id' => 'required|exists:App\Models\Wishlist,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $deleteWishlists = Wishlist::where('id', request('wishlist_id'))->delete();
        $success['success'] = true;
        return $this->sendResponse($success, 'Wishlist deleted successfully.');
    }

    /**
     * Add to cart
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function AddToCart(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $course_id = $request->course_id;
        $insert = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        Wishlist::where(['user_id' => $user_id, 'course_id' => $course_id])->delete();
        $lastInsertedData = Cart::updateOrCreate(['user_id' => $user_id, 'course_id' => $course_id], $insert);
        $data['cart_id'] = $lastInsertedData['id'];
        $success['data'] = $data;
        return $this->sendResponse($success, 'Course successfully added to cart.');
    }

    /**
     * Remove course from cart
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function RemoveFromCart(Request $request) {
        $validator = Validator::make($request->all(), [
                    'cart_id' => 'required|exists:App\Models\Cart,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $deleteWishlists = Cart::where('id', request('cart_id'))->delete();
        $success['success'] = true;
        return $this->sendResponse($success, 'Course deleted from cart successfully.');
    }

    /**
     * My cart
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function MyCart(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;

        $all_cart = Cart::select('tbl_cart.id', 'tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_image', 'tbl_course.course_price')
                        ->leftJoin('tbl_course', 'tbl_cart.course_id', '=', 'tbl_course.course_id')
                        ->selectRaw('LEFT(tbl_course.course_description, 150) AS course_description')
                        ->where('tbl_course.status', '1')
                        ->where('tbl_course.is_delete', '0')
                        ->where('tbl_cart.user_id', $user_id)->paginate($this->paginationlimit);

        $subscribe_course = getSubscriptCourse();
        if (!empty($subscribe_course)) {
            Cart::where(['user_id' => $user_id])->whereIn("course_id", $subscribe_course)->delete();
        }
        if (!empty($all_cart)) {
            for ($i = 0; $i < count($all_cart); $i++) {
                $all_cart[$i]->course_price = (string) currencyConvert($all_cart[$i]->course_price);
                $all_cart[$i]->symbol = getCurrencySymbol();
            }
        }
        $success = $all_cart;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Get User Notification From App api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getNotification(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $notifications = NotificationHistory::select('tbl_notification_history.id AS notification_id', 'title', 'body', 'tbl_notification_history.status', 'tbl_notification_history.created_at')->leftJoin('tbl_notification', 'tbl_notification.id', '=', 'tbl_notification_history.notification_id')->where('tbl_notification_history.user_id', $user_id)->orderBy('notification_id', 'DESC')->paginate($this->paginationlimit);

        for ($i = 0; $i < count($notifications); $i++) {
            $notifications[$i]->times_ago = $notifications[$i]->created_at->diffForHumans();
        }

        $success = $notifications;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     *  Notification Read From App api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function notificationRead(Request $request) {

        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'notification_id' => 'required|exists:tbl_notification_history,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $updateData = [
            'status' => '1',
        ];
        $updated = NotificationHistory::where('id', $request->notification_id)->update($updateData);
        $success['data'] = [];
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Apply coupon for cart page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function applyCoupon(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'coupon_code' => 'required|exists:tbl_coupon,coupon_code'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $coupon_code = $request->coupon_code;
        $user_id = $request->user_id;
        $coupon_data = DB::table("tbl_coupon")
                ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                ->leftJoin("tbl_coupon_has_course", function ($join) {
                    $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                })
                ->whereIn("tbl_coupon_has_course.course_id", function ($query) use ($user_id) {
                    $query->from("tbl_cart")
                    ->select("course_id")
                    ->where("user_id", "=", $user_id);
                })
                ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                ->where('tbl_coupon.coupon_code', $coupon_code)
                ->groupBy('tbl_coupon_has_course.course_id')
                ->get();
        $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_cart`.`id` FROM `tbl_cart`
                LEFT JOIN `tbl_course_has_user` ON `tbl_cart`.`course_id` = `tbl_course_has_user`.`course_id`
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                LEFT JOIN `tbl_course` ON `tbl_cart`.`course_id` = `tbl_course`.`course_id`
                WHERE `tbl_cart`.`user_id` = "' . $user_id . '" GROUP BY tbl_cart.course_id');

        if (!$coupon_data->isEmpty()) {
            $course_ids = [];
            $discount = [];
            foreach ($coupon_data as $row) {
                $course_ids[] = $row->course_id;
                $discount[$row->course_id] = $row;
            }
        } else {
            return $this->sendError('Validation Error.', ['coupon_code_expire' => ['Coupon is expired or not applicable to selected courses.']]);
        }
        $new_cart_data = [];
        $total_price = 0;
        $total_course_count = 0;
        $total_course_count = count($cart_data);

        foreach ($cart_data as $row) {
            $row->course_price = currencyConvert($row->course_price);
            $row->symbol = getCurrencySymbol();
            if (in_array($row->course_id, $course_ids)) {
                $di_data = $discount[$row->course_id];
                if (!empty($di_data->coupon_percentage)) {
                    $total_dis = $row->course_price * $di_data->coupon_percentage / 100;
                    $total_prices = $row->course_price - $total_dis;
                    $row->discount_price = $total_prices;
                    $total_price += $total_prices;
                } else {
                    $row->discount_price = $row->course_price;
                    $total_price += $row->course_price;
                }
                $new_cart_data[] = $row;
            } else {
                $row->discount_price = "";
                $new_cart_data[] = $row;
                $total_price += $row->course_price;
            }
        }
        $total_discount = array_sum(array_column($new_cart_data, 'discount_price'));
        $success['cart_data'] = $new_cart_data;
        $success['sub_total'] = $total_price;
        $success['symbol'] = getCurrencySymbol();
        $success['total_discount'] = $total_discount;
        $success['final_total'] = $total_price - $total_discount;
        session()->put('coupon_code', $coupon_code);
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Apply coupon for buy now page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function buyNowApplyCoupon(Request $request) {
        $validator = Validator::make($request->all(), [
                    'coupon_code' => 'required|exists:tbl_coupon,coupon_code',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $coupon_code = $request->coupon_code;
        $course_id = $request->course_id;
        $coupon_data = DB::table("tbl_coupon")
                ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                ->leftJoin("tbl_coupon_has_course", function ($join) {
                    $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                })
                ->where("tbl_coupon_has_course.course_id", $course_id)
                ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                ->where('tbl_coupon.coupon_code', $coupon_code)
                ->groupBy('tbl_coupon_has_course.course_id')
                ->get();
        $course = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_course`.`course_id` FROM `tbl_course`
                LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                WHERE `tbl_course`.course_id = "' . $course_id . '"');

        if (!$coupon_data->isEmpty()) {
            $course_ids = [];
            $discount = [];
            foreach ($coupon_data as $row) {
                $course_ids[] = $row->course_id;
                $discount[$row->course_id] = $row;
            }
        } else {
            return $this->sendError('Validation Error.', ['coupon_code_expire' => ['Coupon is expired or not applicable to selected courses.']]);
        }
        $new_cart_data = [];
        $total_price = 0;
        foreach ($course as $row) {
            $row->course_price = currencyConvert($row->course_price);
            $row->symbol = getCurrencySymbol();
            if (in_array($row->course_id, $course_ids)) {
                $di_data = $discount[$row->course_id];
                if (!empty($di_data->coupon_percentage)) {
                    $total_dis = $row->course_price * $di_data->coupon_percentage / 100;
                    $total_prices = $row->course_price - $total_dis;
                    $row->discount_price = $total_prices;
                    $total_price += $total_prices;
                } else {
                    $row->discount_price = $row->course_price;
                    $total_price += $row->course_price;
                }
                $new_cart_data[] = $row;
            } else {
                $row->discount_price = "";
                $new_cart_data[] = $row;
                $total_price += $row->course_price;
            }
        }
        $total_discount = array_sum(array_column($new_cart_data, 'discount_price'));
        $success['cart_data'] = $new_cart_data;
        $success['sub_total'] = $total_price;
        $success['total_discount'] = $total_discount;
        $success['symbol'] = getCurrencySymbol();
        $success['final_total'] = $total_price - $total_discount;
        session()->put('coupon_code', $coupon_code);
        return $this->sendResponse($success, 'Success.');
    }

}
