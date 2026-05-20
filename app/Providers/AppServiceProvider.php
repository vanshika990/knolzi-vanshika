<?php

namespace App\Providers;

use Session;
use Illuminate\Support\ServiceProvider;
use Stevebauman\Location\Facades\Location;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        if (!request()->is('admin/*')) {
            $country = session()->get('country', '');
//            $is_cache = session()->get('is_cache', '');
//            if (empty($is_cache)) {
//                Session::forget('db_country_data');
//                Session::put('is_cache', 1);
//            }
            if (empty($country)) {
                $request = app(\Illuminate\Http\Request::class);
                $position = Location::get($request->ip());
                if ((!isset($position->countryName) && empty($position->countryName)) || ($position->countryName != 'India')) {
                    $countryName = 'United States';
                    Session::put('country', $countryName);
                } else {
                    Session::put('country', $position->countryName);
                }
            }
        }
    }

}
