<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\SEOmeta;

class LoginController extends Controller {
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
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function showLoginForm(Request $request) {
        $seometa = SEOmeta::where('slug', 'login')->first();
        return view('frontend.auth.login')->with(['seometa' => $seometa]);
        // return view('auth.login')->with(['seometa' => $seometa]);
    }

    /**
     *
     * @return type
     */
    protected function authenticated() {
        if (Auth::user()->status == '2' || Auth::user()->status == '0') {
            Auth::logout();
            return redirect('login')->withErrors(['Your account is inactive']);
        }
        if (empty(Auth::user()->email_verified_at)) {
            Auth::logout();
            return redirect('login')->withErrors(['Please verify your email address by clicking the link in the email we sent.']);
        }
        return redirect()->route('dashboard');
    }

}
