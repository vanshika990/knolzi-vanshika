<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\VerifyAdminUser;

class AdminAuthController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Login Form
     * @return type
     */
    public function index() {
        if (auth()->guard('admin')->user()) {
            return redirect('admin/dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Login Form
     * @return type
     */
    public function getLogin() {
        if (auth()->guard('admin')->user()) {
            return redirect('admin/dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Show the application login process.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
//        $user = \App\Models\Admin::create(['password' => \Illuminate\Support\Facades\Hash::make('123456789'), 'email' => 'admin1@gmail.com', 'name' => 'Edupme']);
//        $user->password = Hash::make('123456789');
//        $user->email = 'admin1@gmail.com';
//        $user->name = 'Edupme';
//        $user->save();
        if (auth()->guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = auth()->guard('admin')->user();

            //check whether email is confirmed or not
            if (!$user->verified) {
                auth()->guard('admin')->logout();
                return response()->json(['message' => "You didn't confirm your email yet."], 422);
            }

            return redirect()->route('admindashboard');
        } else {
            return response()->json(['message' => "The given data was invalid.", 'errors' => ["email" => ['These credentials do not match our records.']]], 422);
        }
    }

    /**
     * Show the application logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        auth()->guard('admin')->logout();
        \Session::flush();
        \Session::put('success', 'You are logout successfully');
        return redirect(route('adminLogin'));
    }

    /**
     * 
     * @param type $token
     * @return type
     */
    public function verifyUser($token) {
        $verifyUser = VerifyAdminUser::where('token', $token)->first();
        if (isset($verifyUser)) {
            $user = $verifyUser->admin;
            if (!$user->verified) {
                auth()->guard('admin')->logout();
                \Session::flush();
                $verifyUser->admin->verified = 1;
                $verifyUser->admin->save();
                $status = "Your e-mail is verified. You can now login.";
            } else {
                $status = "Your e-mail is already verified. You can now login.";
            }
        } else {
            return redirect()->route('adminLogin')->with('warning', "Sorry your email cannot be identified.");
        }
        return redirect()->route('adminLogin')->with('success', $status);
    }

}
