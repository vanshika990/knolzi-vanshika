<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ThankyouController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Show the thank you page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ThankYouPage() {
        Auth::logout();
        return view('thankyou');
    }

    /**
     * Show the Verify thank you page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function VerifyThankYouPage() {
        Auth::logout();
        return view('verifythankyou');
    }

}
