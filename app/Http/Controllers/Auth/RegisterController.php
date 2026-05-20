<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Userinvitation;
use App\Models\UserHasOrganization;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\SEOmeta;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    public function showRegistrationForm(Request $request) {
        $seometa = SEOmeta::where('slug', 'register')->first();
        return view('frontend.auth.register')->with(['seometa' => $seometa]);
        //return view('auth.register')->with(['seometa' => $seometa]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    protected function redirectTo() {
        \Auth::logout();
        session()->flash('success_message', 'Please confirm yourself by clicking on verify user button sent to you on your email.');
        return route('login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        if ($data['type'] === "organization") {
            return Validator::make($data, [
                        'name' => ['required', 'string', 'max:255'],
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_user'],
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                        'mobile_number' => ['required', 'numeric', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'digits:10'],
                        'birth_date' => ['required']
            ]);
        }

        if ($data['type'] === "individual") {
            if (!empty($data['company_code'])) {
                return Validator::make($data, [
                            'name' => ['required', 'string', 'max:255'],
                            'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_user'],
                            'password' => ['required', 'string', 'min:8', 'confirmed'],
                            'mobile_number' => ['required', 'numeric', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'digits:10'],
                            'birth_date' => ['required'],
                            'company_code' => 'unique:tbl_users_invitation,company_code,0,status'
                ]);
            } else {
                return Validator::make($data, [
                            'name' => ['required', 'string', 'max:255'],
                            'email' => ['required', 'string', 'email', 'max:255', 'unique:tbl_user'],
                            'password' => ['required', 'string', 'min:8', 'confirmed'],
                            'mobile_number' => ['required', 'numeric', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'digits:10'],
                            'birth_date' => ['required'],
                ]);
            }
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data) {
        $insertData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile_no' => $data['mobile_number'],
            'password' => Hash::make($data['password']),
            'date_of_birth' => $data['birth_date'],
        ];

        if ($data['type'] === 'organization') {
            $role = 'organization';
        } else {
            $role = 'individual';
            $insertData['company_code'] = $data['company_code'];
        }
        $user = User::create($insertData);
        if (!empty($data['company_code'])) {
            $invitation = Userinvitation::select('*')->where('company_code', $data['company_code'])->where('status', '0')->first();
            if (!empty($invitation)) {
                $company_user = User::where('email', $invitation['user_email'])->get()->first();
                if (!empty($company_user)) {
                    UserHasOrganization::create(['org_id' => $invitation['company_id'], 'user_id' => $user->id]);
                    $invitation->update(['status' => '1']);
                }
            }
        }
        $user->assignRole($role);
        $user->sendEmailVerificationNotification();
        return $user;
    }

}
