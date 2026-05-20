<?php

use App\Models\User;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Currency;
use App\Models\CourseSubscription;
use App\Helper\GetOptionDataHelper;

/**
 * Get Subscriber courses
 * @return type
 */
function getSubscriptCourse() {
    $subscribe_course = [];
    if (Auth::check()) {
        $user_id = auth()->user()->id;
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
    }
    return $subscribe_course;
}

function getCartData() {
    $cart_data = [];
    if (Auth::check()) {
        $user_id = Auth::user()->id;
        $c_data = Cart::select('course_id')->where('user_id', $user_id)->get();
        if (!empty($c_data)) {
            foreach ($c_data as $row) {
                $cart_data[$row->course_id] = $row->course_id;
            }
        }
    } else {
        $cart_data = session()->get('cart', []);
    }
    return $cart_data;
}

function getCartHtml() {
    $total_price = 0;
    $course_ids = [];
    $discount = [];
    $coupon_code = session()->get('coupon_code', '');
    $cart_data = [];
    $html = '<div class="offcanvas-header border-bottom"><h5 class="mb-0">My Cart</h5><button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button></div><div class="offcanvas-body"><div class="cart-dropdown"><ul>';
    if (Auth::check()) {
        $user_id = Auth::user()->id;
        $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_cart`.`id` FROM `tbl_cart` 
                            LEFT JOIN `tbl_course_has_user` ON `tbl_cart`.`course_id` = `tbl_course_has_user`.`course_id` 
                            LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                            LEFT JOIN `tbl_course` ON `tbl_cart`.`course_id` = `tbl_course`.`course_id`
                            WHERE `tbl_cart`.`user_id` = "' . $user_id . '" GROUP BY tbl_cart.course_id');
        $coupon_data = DB::table("tbl_coupon")
                ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                ->leftJoin("tbl_coupon_has_course", function($join) {
                    $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                })
                ->whereIn("tbl_coupon_has_course.course_id", function($query) use($user_id) {
                    $query->from("tbl_cart")
                    ->select("course_id")
                    ->where("user_id", "=", $user_id);
                })
                ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                ->where('tbl_coupon.coupon_code', $coupon_code)
                ->groupBy('tbl_coupon_has_course.course_id')
                ->get();
    } else {
        $cart = implode(',', array_keys(session()->get('cart', [])));
        if (!empty($cart)) {
            $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price` FROM `tbl_course` 
                LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id` 
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                WHERE `tbl_course`.`course_id` IN (' . $cart . ') GROUP BY tbl_course.course_id');

            $cart = session()->get('cart', []);
            $coupon_data = DB::table("tbl_coupon")
                    ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                    ->leftJoin("tbl_coupon_has_course", function($join) {
                        $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                    })
                    ->whereIn("tbl_coupon_has_course.course_id", $cart)
                    ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                    ->where('tbl_coupon.coupon_code', $coupon_code)
                    ->get();
        }
    }
    if (!empty($cart_data)) {
        if (!$coupon_data->isEmpty()) {
            foreach ($coupon_data as $row) {
                $course_ids[] = $row->course_id;
                $discount[$row->course_id] = $row;
            }
        }
        foreach ($cart_data as $row) {
            $html .='<li>
                        <div class="course-image">
                            <a href="">
                                <img src="' . $row->course_image . '" alt="course" class="img-fluid">
                            </a>
                        </div>
                        <div class="cart-content">
                            <div class="course-title"><a href="' . route('coursedetails', $row->slug) . '">' . $row->course_name . '</a></div>
                            <small>' . $row->author_name . '</small>
                            <div class="course-price">
                                ';
            if (in_array($row->course_id, $course_ids)) {
                $di_data = $discount[$row->course_id];
                if (!empty($di_data->coupon_percentage)) {
                    $total_dis = currencyConvert($row->course_price) * $di_data->coupon_percentage / 100;
                    $total_dis = currencyConvert($row->course_price) - $total_dis;
                    $html .='<div class=""main-cart-price"><span>' . getCurrencySymbol() . '<del>' . currencyConvert($row->course_price) . '</del></span></div><div class=""main-cart-discount-price"><span>' . getCurrencySymbol() . $total_dis . '</span></div>';
                    $total_price+=$total_dis;
                } else {
                    $html .='<div class=""main-cart-price"><span>' . getCurrencySymbol() . '<span>' . currencyConvert($row->course_price) . '</span></div>';
                    $total_price+=0;
                }
                $new_cart_data[] = $row;
            } else {
                $html .='<div class=""main-cart-price"><span>' . getCurrencySymbol() . '<span>' . currencyConvert($row->course_price) . '</span></div>';
                $new_cart_data[] = $row;
                $total_price+=currencyConvert($row->course_price);
            }

            $html .='</div></div></li>';
        }
        $html .='</ul><div class = "cart-total-price"><div class = "course-price"><span>Total: </span><span>' . getCurrencySymbol() . $total_price . '</span></div></div></div></div>
                    <div class="offcanvas-footer p-4">
                        <a href="' . route('mycart') . '" class="btn btn-warning w-100">Go to Cart</a>
                    </div>';
    } else {
        $html .= '<li>Your cart is empty.</li></ul></div></div>
                    <div class="offcanvas-footer p-4">
                        <a href="' . route('mycart') . '" class="btn btn-warning w-100">Go to Cart</a>
                    </div>';
    }

    return $html;
}

function getCartHtmlWithCount() {
    $total_price = 0;
    $course_ids = [];
    $discount = [];
    $coupon_code = session()->get('coupon_code', '');
    $cart_data = [];
    $html = '<div class="offcanvas-header border-bottom"><h5 class="mb-0">My Cart</h5><button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button></div><div class="offcanvas-body"><div class="cart-dropdown"><ul>';
    if (Auth::check()) {
        $user_id = Auth::user()->id;
        $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_cart`.`id` FROM `tbl_cart` 
                            LEFT JOIN `tbl_course_has_user` ON `tbl_cart`.`course_id` = `tbl_course_has_user`.`course_id` 
                            LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                            LEFT JOIN `tbl_course` ON `tbl_cart`.`course_id` = `tbl_course`.`course_id`
                            WHERE `tbl_cart`.`user_id` = "' . $user_id . '" GROUP BY tbl_cart.course_id');
        $coupon_data = DB::table("tbl_coupon")
                ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                ->leftJoin("tbl_coupon_has_course", function($join) {
                    $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                })
                ->whereIn("tbl_coupon_has_course.course_id", function($query) use($user_id) {
                    $query->from("tbl_cart")
                    ->select("course_id")
                    ->where("user_id", "=", $user_id);
                })
                ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                ->where('tbl_coupon.coupon_code', $coupon_code)
                ->groupBy('tbl_coupon_has_course.course_id')
                ->get();
    } else {
        $cart = implode(',', array_keys(session()->get('cart', [])));
        if (!empty($cart)) {
            $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price` FROM `tbl_course` 
                LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id` 
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                WHERE `tbl_course`.`course_id` IN (' . $cart . ') GROUP BY tbl_course.course_id');

            $cart = session()->get('cart', []);
            $coupon_data = DB::table("tbl_coupon")
                    ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                    ->leftJoin("tbl_coupon_has_course", function($join) {
                        $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                    })
                    ->whereIn("tbl_coupon_has_course.course_id", $cart)
                    ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                    ->where('tbl_coupon.coupon_code', $coupon_code)
                    ->get();
        }
    }
    if (!empty($cart_data)) {
        if (!$coupon_data->isEmpty()) {
            foreach ($coupon_data as $row) {
                $course_ids[] = $row->course_id;
                $discount[$row->course_id] = $row;
            }
        }
        foreach ($cart_data as $row) {
            $html .='<li>
                        <div class="course-image">
                            <a href="">
                                <img src="' . $row->course_image . '" alt="course" class="img-fluid">
                            </a>
                        </div>
                        <div class="cart-content">
                            <div class="course-title"><a href="' . route('coursedetails', $row->slug) . '">' . $row->course_name . '</a></div>
                            <small>' . $row->author_name . '</small>
                            <div class="course-price">';
            if (in_array($row->course_id, $course_ids)) {
                $di_data = $discount[$row->course_id];
                if (!empty($di_data->coupon_percentage)) {
                    $total_dis = currencyConvert($row->course_price) * $di_data->coupon_percentage / 100;
                    $total_dis = currencyConvert($row->course_price) - $total_dis;
                    $html .='<div class=""main-cart-price"><span>' . getCurrencySymbol() . '<del>' . currencyConvert($row->course_price) . '</del></span></div><div class=""main-cart-discount-price"><i class="fas fa-rupee-sign"></i><span>' . $total_dis . '</span></div>';
                    $total_price+=$total_dis;
                } else {
                    $html .='<div class=""main-cart-price"><span>' . getCurrencySymbol() . '<span>' . currencyConvert($row->course_price) . '</span></div>';
                    $total_price+=0;
                }
                $new_cart_data[] = $row;
            } else {
                $html .='<div class=""main-cart-price"><span>' . getCurrencySymbol() . '<span>' . currencyConvert($row->course_price) . '</span></div>';
                $new_cart_data[] = $row;
                $total_price+=currencyConvert($row->course_price);
            }

            $html .='</div></div></li>';
        }
        $html .='</ul><div class = "cart-total-price"><div class = "course-price"><span>Total: </span><span>' . getCurrencySymbol() . $total_price . '</span></div></div></div></div>
                    <div class="offcanvas-footer p-4">
                        <a href="' . route('mycart') . '" class="btn btn-warning w-100 p-3">Go to Cart</a>
                    </div>';
    } else {
        $html .= '<li>Your cart is empty.</li></ul></div></div>
                    <div class="offcanvas-footer p-4">
                        <a href="' . route('mycart') . '" class="btn btn-warning w-100 p-3">Go to Cart</a>
                    </div>';
    }
    return array("html" => $html, "count" => count($cart_data));
}

function getWhishlistCourse() {
    $wishlist = [0];
    if (Auth::check()) {
        $wishlist = Wishlist::select('course_id')->where('user_id', auth()->user()->id)->get()->pluck('course_id')->toArray();
    }
    return $wishlist;
}

function currencyConvert($price) {
    $db_currency = session()->get('db_country_data', '');
    $new_price = $price;
    $html = "";
    if (!empty($db_currency)) {
        $new_price = $price * $db_currency['rate'] / $db_currency['inr_value'];
    } else {
        $country = session()->get('country', '');
        $get_c = Currency::where('name', $country)->first();
        if (!empty($get_c)) {
            $get_c = $get_c->toArray();
            session()->put('db_country_data', $get_c);
            $new_price = $price * $get_c['rate'] / $get_c['inr_value'];
        } else {
            $get_c = Currency::where('name', 'United States')->first();
            $get_c = $get_c->toArray();
            session()->put('db_country_data', $get_c);
            $new_price = $price * $get_c['rate'] / $get_c['inr_value'];
        }
    }
    return $new_price;
}

function getCurrencySymbol() {
    $db_currency = session()->get('db_country_data', '');
    $html = "";
    if (!empty($db_currency)) {
        $html = $db_currency["symbol"];
    } else {
        $country = session()->get('country', '');
        $get_c = Currency::where('name', $country)->first();
        if (!empty($get_c)) {
            $get_c = $get_c->toArray();
            session()->put('db_country_data', $get_c);
            $html = $get_c["symbol"];
        } else {
            $get_c = Currency::where('name', 'United States')->first();
            $get_c = $get_c->toArray();
            session()->put('db_country_data', $get_c);
            $html = $get_c["symbol"];
        }
    }
    return $html;
}

function getSocialMediaLink() {
    $footer['facebook_url'] = '';
    $footer['twitter_url'] = '';
    $footer['youtube_url'] = '';
    $footer['instagram_url'] = '';
    $footer['linkedin_url'] = '';

    $data = GetOptionDataHelper::getOptionData(['footer_section']);
    if (isset($data['footer_section'])) {
        $footer['facebook_url'] = $data['footer_section']['facebook_url'];
        $footer['twitter_url'] = $data['footer_section']['twitter_url'];
        $footer['youtube_url'] = $data['footer_section']['youtube_url'];
        $footer['instagram_url'] = $data['footer_section']['instagram_url'];
        $footer['linkedin_url'] = $data['footer_section']['linkedin_url'];
    }

    return $footer;
}
