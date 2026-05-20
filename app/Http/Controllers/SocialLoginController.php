<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SocialLoginController extends Controller {

    /**
     * If User login then comes this page
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Redirect to Facebbok
     * @return type
     */
    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook Callback
     */
    public function handleFacebookCallback() {
        try {
            $user = Socialite::driver('facebook')->user();
            if (!isset($user->email)) {
                return redirect()->route('login')->withErrors(["Email not Found from Facebook"]);
            }
            $finduser = User::where('email', $user->email)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect()->route('dashboard');
            } else {
                $newUser = User::create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'password' => Hash::make(rand(1, 10000)),
                            'source_from' => "Facebook",
                            'status' => "1",
                            'email_verified_at' => now(),
                ]);

                Auth::login($newUser);
                $newUser->assignRole('individual');
                return redirect()->route('dashboard');
            }
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback() {
        try {
            $user = Socialite::driver('google')->user();
            if (!isset($user->email)) {
                return redirect()->route('login')->withErrors(["Email not Found from Gmail"]);
            }
            $finduser = User::where('email', $user->email)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect()->route('dashboard');
            } else {
                $newUser = User::create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'password' => Hash::make(rand(1, 10000)),
                            'source_from' => "Google",
                            'status' => "1",
                            'email_verified_at' => now(),
                ]);

                Auth::login($newUser);
                $newUser->assignRole('individual');
                return redirect()->route('dashboard');
            }
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([$e->getMessage()]);
        }
    }

}
