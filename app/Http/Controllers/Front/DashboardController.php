<?php

namespace App\Http\Controllers\Front;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class DashboardController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $cart = session()->get('cart', []);
        if (Auth::user()->hasRole('reviewer')) {
            session()->put('cart', []);
        }
        $user_id = Auth::user()->id;
        $subscribed_course = getSubscriptCourse();
        $c_data = Cart::select('course_id')->where('user_id', $user_id)->get()->pluck('course_id')->toArray();
        $cart_data = [];
        if (!empty($cart)) {
            foreach ($cart as $k => $v) {
                if (!in_array($k, $c_data) && !in_array($k, $subscribed_course)) {
                    $cart_data[] = [
                        'user_id' => $user_id, 'course_id' => $k, 'created_at' => now(), 'updated_at' => now()
                    ];
                }
            }
        }
        if (!empty($cart_data)) {
            Cart::insert($cart_data);
        }
        if (Auth::user()->hasRole('individual') || Auth::user()->hasRole('reviewer')) {
            return redirect('/');
        } elseif (Auth::user()->hasRole('institute') || Auth::user()->hasRole('author')) {
            return redirect()->route('author-dashboard');
        } elseif (Auth::user()->hasRole('organization')) {
            return redirect()->route('org-my-course');
        } elseif (Auth::user()->hasRole('reviewer')) {
            return redirect()->route('getreviewercourse');
        } else {
            return redirect('/');
        }
        return view('front.dashboard.index');
    }

}
