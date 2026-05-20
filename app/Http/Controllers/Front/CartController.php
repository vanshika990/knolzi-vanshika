<?php

namespace App\Http\Controllers\Front;

use Auth;
use DB;
use App\Http\Controllers\Controller;
use DataTables;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class CartController extends Controller {

    /**
     * Add to cart Post
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function addToCartPost(Request $request) {
        if ($request->ajax()) {
            $ids = explode(',', $request->course_id);
            if (!empty($ids)) {
                if (Auth::check()) {
                    $user_id = Auth::user()->id;
                    $subscribe_course = getSubscriptCourse();
                    foreach ($ids as $id) {
                        if (in_array($id, $subscribe_course)) {
                            return response()->json(['errors' => ['already' => ['You already purchased this course']]], 422);
                        }
                        Cart::updateOrCreate(['user_id' => $user_id, 'course_id' => $id], ['user_id' => $user_id, 'course_id' => $id]);
                        Wishlist::where(['user_id' => $user_id, 'course_id' => $id])->delete();
                    }
                } else {
                    $cart = session()->get('cart', []);
                    foreach ($ids as $id) {
                        $cart[$id] = [$id];
                        session()->put('cart', $cart);
                    }
                }
            }
            $cart_count = getCartData();
            $response = [
                'success' => true,
                'html' => getCartHtml(),
                'cart_count' => count($cart_count),
            ];
            return response()->json($response, 200);
        }
        abort(404);
    }

    /**
     * Add to wishlist
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function addToWishlistPost(Request $request) {
        if ($request->ajax()) {
            $id = $request->course_id;
            if (!empty($id)) {
                if (Auth::check()) {
                    $user_id = Auth::user()->id;
                    Cart::where(['user_id' => $user_id, 'course_id' => $id])->delete();
                    Wishlist::updateOrCreate(['user_id' => $user_id, 'course_id' => $id], ['user_id' => $user_id, 'course_id' => $id]);
                    $response = [
                        'success' => true,
                        'login' => true,
                        'html' => getCartHtmlWithCount(),
                        'message' => 'Wishlist added successfully'
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'login' => false,
                        'url' => route('login')
                    ];
                }
                return response()->json($response, 200);
            }
            $response['error'] = "something went wrong please try again";
            return response()->json($response, 401);
        }
        abort(404);
    }

    /**
     * Remove to wishlist
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function removeToWishlistPost(Request $request) {
        if ($request->ajax()) {
            $id = $request->course_id;
            if (!empty($id)) {
                if (Auth::check()) {
                    $user_id = Auth::user()->id;
                    Wishlist::where(['user_id' => $user_id, 'course_id' => $id])->delete();
                    $response = [
                        'success' => true,
                        'login' => true,
                        'html' => getCartHtml(),
                        'message' => 'Wishlist remove successfully'
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'login' => false,
                        'url' => route('login')
                    ];
                }
                return response()->json($response, 200);
            }
            $response['error'] = "something went wrong please try again";
            return response()->json($response, 401);
        }
        abort(404);
    }

    /**
     * Apply Coupon Code
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function applyCoupon(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'coupon_code' => 'required'
            ]);
            $coupon_code = $request->coupon_code;
            if (Auth::check()) {
                $user_id = Auth::user()->id;
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
            if (!$coupon_data->isEmpty()) {
                if (Auth::check()) {
                    $user_id = Auth::user()->id;
                    $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_cart`.`id` FROM `tbl_cart`
                            LEFT JOIN `tbl_course_has_user` ON `tbl_cart`.`course_id` = `tbl_course_has_user`.`course_id`
                            LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                            LEFT JOIN `tbl_course` ON `tbl_cart`.`course_id` = `tbl_course`.`course_id`
                            WHERE `tbl_cart`.`user_id` = "' . $user_id . '" GROUP BY tbl_cart.course_id');
                } else {
                    $cart = implode(',', array_keys(session()->get('cart', [])));
                    $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name,`tbl_course`.`course_id`, `tbl_course`.`course_id` as id,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price` FROM `tbl_course`
                            LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                            LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                            WHERE `tbl_course`.`course_id` IN (' . $cart . ') GROUP BY tbl_course.course_id');
                }
                session()->put('coupon_code', $coupon_code);
                $course_ids = [];
                $discount = [];
                foreach ($coupon_data as $row) {
                    $course_ids[] = $row->course_id;
                    $discount[$row->course_id] = $row;
                }
                $new_cart_data = [];
                $total_course_count = count($cart_data);
                $total_price = 0;
                foreach ($cart_data as $row) {
                    if (in_array($row->course_id, $course_ids)) {
                        $di_data = $discount[$row->course_id];
                        if (!empty($di_data->coupon_percentage)) {
                            $total_dis = currencyConvert($row->course_price) * $di_data->coupon_percentage / 100;
                            $total_dis = currencyConvert($row->course_price) - $total_dis;
                            $row->discount_price = $total_dis;
                            $total_price+=$total_dis;
                        } else {
                            $row->discount_price = 0;
                            $total_price+=0;
                        }
                        $new_cart_data[] = $row;
                    } else {
                        $row->discount_price = "";
                        $new_cart_data[] = $row;
                        $total_price+=currencyConvert($row->course_price);
                    }
                }
                $html = view('front.cart.coupon_cart')->with(['cart_data' => $new_cart_data, 'total_course_count' => $total_course_count]);
                return response()->json(['success' => true, "message" => 'Coupon successfully apply', 'html' => $html->render(), 'total_price' => $total_price], 200);
            } else {

                session()->put('coupon_code', '');
                $invalid = $this->getCouponInvalidcartData();
                return response()->json(['success' => false, "message" => 'Coupon expired or invalid.', 'html' => $invalid['html'], 'total_price' => $invalid['total_price']], 200);
            }
        }
        abort(404);
    }

    /**
     * Apply Coupon Code for Buy-Now
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function ApplyBuynowCoupon(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'coupon_code' => 'required'
            ]);
            $coupon_code = $request->coupon_code;
            $course_id = $request->course_id;

            $coupon_data = DB::table("tbl_coupon")
                    ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                    ->leftJoin("tbl_coupon_has_course", function($join) {
                        $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                    })
                    ->where("tbl_coupon_has_course.course_id", $course_id)
                    ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                    ->where('tbl_coupon.coupon_code', $coupon_code)
                    ->first();
//            $course = Course::select('course_price')->find($course_id);
            if (!empty($coupon_data)) {
//                $total_dis = $course->course_price * $coupon_data->coupon_percentage / 100;
//                if ($coupon_data->coupon_type == '1') {
//                    $total_dis_price = $course->course_price - $total_dis;
//                } else {
//                    $total_dis_price = 0;
//                }
//                session()->put('coupon_code', $coupon_code);
//                $html = view('front.cart.buy_now_coupon')->with(['total_price' => $course->course_price, 'total_dis_price' => $total_dis_price, 'is_discount' => true]);
//                return response()->json(['success' => true, "message" => 'Coupon successfully apply', 'html' => $html->render()], 200);
                session()->put('coupon_code', $coupon_code);
                return response()->json(['success' => true, "message" => 'Coupon successfully apply'], 200);
            } else {
                session()->put('coupon_code', '');
//                $html = view('front.cart.buy_now_coupon')->with(['total_price' => $course->course_price, 'is_discount' => false]);
                return response()->json(['success' => false, "message" => 'Coupon expired or invalid.'], 200);
            }
        }
        abort(404);
    }

    /**
     * When invalid coupon then return actual cart data
     * @return type
     */
    public function getCouponInvalidcartData() {
        $data = [];
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_cart`.`id` FROM `tbl_cart`
                LEFT JOIN `tbl_course_has_user` ON `tbl_cart`.`course_id` = `tbl_course_has_user`.`course_id`
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                LEFT JOIN `tbl_course` ON `tbl_cart`.`course_id` = `tbl_course`.`course_id`
                WHERE `tbl_cart`.`user_id` = "' . $user_id . '" GROUP BY tbl_cart.course_id');
        } else {
            $cart = implode(',', array_keys(session()->get('cart', [])));
            if (!empty($cart)) {
                $data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_id` as id,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price` FROM `tbl_course`
                LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                WHERE `tbl_course`.`course_id` IN (' . $cart . ') GROUP BY tbl_course.course_id');
            }
        }
        $total_course_count = count($data);
        $total_price = 0;

        foreach ($data as $key => $value) {
            $total_price += currencyConvert($value->course_price);
        }

        $html = view('front.cart.coupon_invalid_cart')->with(['cart_data' => $data, 'total_course_count' => $total_course_count]);
        return ['html' => $html->render(), 'total_price' => $total_price];
    }

    /**
     * Cart page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetMyCart(Request $request) {
        $data = [];
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_cart`.`id` FROM `tbl_cart`
                LEFT JOIN `tbl_course_has_user` ON `tbl_cart`.`course_id` = `tbl_course_has_user`.`course_id`
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                LEFT JOIN `tbl_course` ON `tbl_cart`.`course_id` = `tbl_course`.`course_id`
                WHERE `tbl_cart`.`user_id` = "' . $user_id . '" GROUP BY tbl_cart.course_id');
        } else {
            $cart = implode(',', array_keys(session()->get('cart', [])));
            if (!empty($cart)) {
                $cart_data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_id` as id,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price` FROM `tbl_course`
                LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                WHERE `tbl_course`.`course_id` IN (' . $cart . ') GROUP BY tbl_course.course_id');
            }
        }
        $new_cart_data = [];
        $total_price = 0;
        $total_course_count = 0;
        if (!empty($cart_data)) {
            $coupon_code = session()->get('coupon_code', '');
            $course_ids = [];
            $discount = [];
            if (!empty($coupon_code)) {
                if (Auth::check()) {
                    $user_id = Auth::user()->id;
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
                if (!$coupon_data->isEmpty()) {
                    $course_ids = [];
                    $discount = [];
                    foreach ($coupon_data as $row) {
                        $course_ids[] = $row->course_id;
                        $discount[$row->course_id] = $row;
                    }
                }
            }

            $total_course_count = count($cart_data);

            foreach ($cart_data as $row) {
                if (in_array($row->course_id, $course_ids)) {
                    $di_data = $discount[$row->course_id];
                    if (!empty($di_data->coupon_percentage)) {
                        $total_dis = currencyConvert($row->course_price) * $di_data->coupon_percentage / 100;
                        $total_prices = currencyConvert($row->course_price) - $total_dis;
                        $row->discount_price = $total_prices;
                        $total_price+=$total_prices;
                    } else {
                        $row->discount_price = 0;
                        $total_price+=0;
                    }
                    $new_cart_data[] = $row;
                } else {
                    $row->discount_price = "";
                    $new_cart_data[] = $row;
                    $total_price+=currencyConvert($row->course_price);
                }
            }
        } else {
            session()->put('coupon_code', '');
        }
        /* $total_course_count = count($data);
          $total_price = 0;

          foreach ($data as $key => $value) {
          $total_price += $value->course_price;
          } */

        // return view('front.cart.index')->with(['cart_data' => $new_cart_data, 'total_price' => $total_price, 'total_course_count' => $total_course_count]);
        return view('frontend.cart')->with(['cart_data' => $new_cart_data, 'total_price' => $total_price, 'total_course_count' => $total_course_count]);
    }

    /**
     * Remove cart
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return type
     */
    public function RemoveFromCart(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            if (Auth::check()) {
                Cart::where('id', $id)->delete();
            } else {
                $cart = session()->get('cart', []);
                if (isset($cart[$id])) {
                    unset($cart[$id]);
                    session()->put('cart', $cart);
                }
            }
            return response(["success" => true, "message" => "Course Successsfully removed from cart"]);
        }
        abort(404);
    }

    /**
     * Move to wishlist
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return type
     */
    public function MoveToWishlist(Request $request, $id) {
        if ($request->ajax()) {
            $user_id = Auth::user()->id;
            $id = decrypt($id);
            $data = Cart::find($id);
            $insert_data = [
                'user_id' => $user_id,
                'course_id' => $data['course_id'],
            ];
            Wishlist::create($insert_data);
            Cart::where('id', $id)->delete();
            return response(["success" => true, "message" => "Course Successsfully moved to wishlist"]);
        }
        abort(404);
    }

    /**
     * Display wishlist of User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function GetMywishlist(Request $request) {
        $user_id = Auth::user()->id;
        $data = DB::table('tbl_wishlists')
                ->select(DB::Raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name'), DB::Raw('IFNULL(s.rate, 0) AS rate'), DB::Raw('IFNULL(s.total_record, 0) AS total_record'), 'tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_image', 'tbl_course.slug', 'tbl_course.course_price', 'tbl_wishlists.id')
                ->leftJoin('tbl_course_has_user', 'tbl_course_has_user.course_id', '=', 'tbl_wishlists.course_id')
                ->leftJoin('tbl_user', 'tbl_user.id', '=', 'tbl_course_has_user.user_id')
                ->leftJoin('tbl_course', 'tbl_course.course_id', '=', 'tbl_wishlists.course_id')
                ->leftJoin(DB::Raw('(SELECT r.course_id, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM tbl_course_has_review_rate AS r) s'), 's.course_id', '=', 'tbl_wishlists.course_id')
                ->where('tbl_wishlists.user_id', $user_id)
                ->groupBy('tbl_wishlists.course_id')
                ->paginate(9);
//        $data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, IFNULL(`s`.`rate`, 0) AS `rate`,IFNULL(`s`.`total_record`, 0) AS `total_record`, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_wishlists`.`id` FROM `tbl_wishlists`
//                LEFT JOIN `tbl_course_has_user` ON `tbl_wishlists`.`course_id` = `tbl_course_has_user`.`course_id`
//                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
//                LEFT JOIN `tbl_course` ON `tbl_wishlists`.`course_id` = `tbl_course`.`course_id`
//                LEFT JOIN (SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r) s ON (`tbl_wishlists`.`course_id` = s.course_id)
//                WHERE `tbl_wishlists`.`user_id` = "' . $user_id . '" GROUP BY tbl_wishlists.course_id');
//

        // return view('front.wishlist.my-wishlist')->with(['data' => $data]);
        return view('frontend.wishlist')->with(['data' => $data]);
    }

    public function RemoveCouponFromCart(Request $request) {
        session()->put('coupon_code', '');
        return redirect()->back();
    }

}
