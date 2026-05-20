<?php

namespace App\Http\Controllers\Front;

use Auth;
use DB;
use PaytmWallet;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\Payment;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\User;
use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CheckoutController extends Controller {

    /**
     * If User login then comes this page
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Checkout page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function index(Request $request) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $user_id = Auth::user()->id;
            $coupon_code = session()->get('coupon_code', '');
            if ($coupon_code != '') {
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
                        ->get()
                        ->toArray();
                $cpn = Coupon::select('coupon_type', 'coupon_duration', 'coupon_percentage')->where('coupon_code', $coupon_code)->get()->first();

                $coupon_type = $cpn['coupon_type'];
                $coupon_duration = $cpn['coupon_duration'];
                $coupon_percentage = $cpn['coupon_percentage'];

                $coupon_course = array_column($coupon_data, 'course_id');
            } else {
                $coupon_type = '';
                $coupon_duration = '';
                $coupon_percentage = '';
                $coupon_course = [];
                $coupon_data = [];
            }
            $data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`subscription_day`,`tbl_course`.`course_price`, `tbl_cart`.`id` FROM `tbl_cart` 
                LEFT JOIN `tbl_course_has_user` ON `tbl_cart`.`course_id` = `tbl_course_has_user`.`course_id` 
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                LEFT JOIN `tbl_course` ON `tbl_cart`.`course_id` = `tbl_course`.`course_id`
                WHERE `tbl_cart`.`user_id` = "' . $user_id . '" GROUP BY tbl_cart.course_id');
            $total_price = 0;
            $total_discount_price = 0;
            $original_price = 0;
            if (!empty($data)) {
                foreach ($data as $price) {
                    $original_price+=currencyConvert($price->course_price);
                }
            }
            foreach ($data as $key => $value) {
                if (in_array($value->course_id, $coupon_course)) {
                    $value->is_discount = '1';
                    if ($coupon_type == '0') {
                        $value->dicount_course_price = 0;
                        $total_price += 0;
                        $total_discount_price += currencyConvert($value->course_price);
                    } else {
                        $discount = currencyConvert($value->course_price) * $coupon_percentage / 100;
                        $value->dicount_course_price = currencyConvert($value->course_price) - $discount;
                        $total_price += $value->dicount_course_price;
                        $total_discount_price += $discount;
                    }
                } else {
                    $value->is_discount = '0';
                    $total_price += currencyConvert($value->course_price);
                }
            }

            if ($total_price > 0) {
                $session_orderId = session()->get('orderId', '');
                $country = session()->get('db_country_data', '');
                $api = new Api(env('RAZORPAY_KEY', 'rzp_live_xEvpWXVcg1Knbn'), env('RAZORPAY_SECRET', 'Uv9GvPEQE41oyJYkKVEN0Vi5'));
                $order = $api->order->create(array('receipt' => Auth::user()->email, 'amount' => $total_price * 100, 'currency' => $country['short_name'])); // Creates order
                $orderId = $order['id'];
                session()->put('orderId', $orderId);

                $sub_data = [];
                $subdata['subscription'] = $data;
                $subdata['order_id'] = $orderId;
                $subdata['user_id'] = $user_id;
                $subdata['final_total'] = $total_price;
                $subdata['total_discount_price'] = $total_discount_price;
                $subdata['coupon_code'] = $coupon_code;
                $subdata['coupon_data'] = $coupon_data;

                $insert = [
                    'user_id' => $user_id,
                    'order_id' => $orderId,
                    'type' => 'web',
                    'price' => $original_price,
                    'amount_to_be_paid' => $total_price,
                    'discount_code' => $coupon_code,
                    'discount' => $total_discount_price,
                    'payment_mode' => 'razorpay',
                    'payment_status' => 'INPROGRESS',
                    'subscription_data' => json_encode($subdata),
                    'status' => '1',
                ];

                $ins = Payment::updateOrCreate(['order_id' => $session_orderId, 'payment_status' => 'INPROGRESS'], $insert);
                return view('front.checkout.index')->with(['orderId' => $orderId, 'checkout_data' => $data, 'total_price' => $total_price, 'total_discount_price' => $total_discount_price, 'original_total' => $original_price]);
            } else {
                $order_id = $this->generateOrderId();
                $transaction_id = $this->generateTransactionId();
                session()->put('orderId', $order_id);

                $sub_data = [];
                $subdata['subscription'] = $data;
                $subdata['order_id'] = $order_id;
                $subdata['user_id'] = $user_id;
                $subdata['final_total'] = $total_price;
                $subdata['total_discount_price'] = $total_discount_price;
                $subdata['coupon_code'] = $coupon_code;
                $subdata['coupon_data'] = $coupon_data;

                $insert = [
                    'user_id' => $user_id,
                    'order_id' => $order_id,
                    'transaction_id' => $transaction_id,
                    'type' => 'manually',
                    'price' => $original_price,
                    'amount_to_be_paid' => $total_price,
                    'discount_code' => $coupon_code,
                    'discount' => $total_discount_price,
                    'payment_mode' => 'Free Coupon',
                    'payment_status' => 'SUCCESS',
                    'subscription_data' => json_encode($subdata),
                    'status' => '1',
                ];

                $ins = Payment::create($insert);
                $all_course = [];
                foreach ($subdata['subscription'] as $row) {
                    $all_course[] = $row->course_id;
                    if (!isset($row->subscription_day) && !empty($row->subscription_day)) {
                        $date = strtotime("+" . $row->subscription_day . " day");
                    } else {
                        $date = strtotime("+120 day");
                    }
                    $expiry = date('Y-m-d', $date);
                    $ins = [
                        'course_id' => $row->course_id,
                        'user_id' => $user_id,
                        'payment_id' => $ins->id,
                        'no_of_licence' => '1',
                        'sub_expire_date' => $expiry,
                        'status' => '1',
                    ];
                    if ($row->is_discount == '1') {
                        $ins['discount_code'] = $coupon_code;
                        $ins['amount_to_be_paid'] = $row->dicount_course_price;
                    } else {
                        $ins['discount_code'] = NULL;
                        $ins['amount_to_be_paid'] = currencyConvert($row->course_price);
                    }
                    $ins_subscription = CourseSubscription::create($ins);

                    $licence = [
                        'course_subscription_id' => $ins_subscription->id,
                        'user_id' => $user_id,
                        'course_id' => $row->course_id,
                        'status' => '1',
                    ];

                    if (!Auth::user()->hasRole('organization')) {
                        $lic = CourseSubscriptionLicence::create($licence);
                    }
                }

                $delete = Cart::where('user_id', $user_id)->delete();
                session()->put('coupon_code', '');
                session()->put('orderId', '');

                /*                 * **********************************Start Email ******************************* */
                $user = User::select('name', 'email')->find($user_id);
                $courses = Course::select('course_name')->whereIn("course_id", $all_course)->get();
                $data = [
                    'template' => 'CourseSubscribe',
                    'html_body' => [
                        'name' => $user->name,
                        'course' => $courses
                    ],
                    'subject' => 'Thank you for subscribing'
                ];
                \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
                /*                 * **********************************End Email ******************************* */
                return redirect()->route('thank_you_payment', ['id' => encrypt(rand())]);
            }
        }
        return redirect()->route('mycart');
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function paypalprocessTransaction(Request $request) {
        $request->validate([
            'amount' => 'required',
        ]);
        try {
            $country = session()->get('db_country_data', '');
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.successTransaction'),
                    "cancel_url" => route('paypal.cancelTransaction'),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => $country['short_name'],
                            "value" => $request->amount
                        ]
                    ]
                ]
            ]);
            if (isset($response['id']) && $response['id'] != null) {
                $order_id = session()->get('orderId', '');

                Payment::where("order_id", $order_id)->update(['transaction_id' => $response['id'], 'payment_mode' => 'paypal']);
                // redirect to approve href
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()
                                ->route('getcheckout')
                                ->with('error', 'Something went wrong.');
            } else {
                return redirect()
                ->route('getcheckout')
                ->with('error', $response['message'] ?? 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect()
                            ->route('getcheckout')
                            ->with('error', $e->getMessage());
        }
    }

    /**
     * Paypal success transaction.
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function paypalSuccessTransaction(Request $request) {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $input = $request->all();
            $transaction_id = $response['id'];

            $data = Payment::where('transaction_id', $transaction_id)->get()->first();
            $payment_status = "success";
            $generated_response = json_encode($response);

            $update = [
                'transaction_id' => $transaction_id,
                'payment_status' => $payment_status,
                'status' => '1',
                'generated_response' => $generated_response,
            ];
            $data->update($update);

            $sub = json_decode($data['subscription_data']);
            $subscription_data = $sub->subscription;
            $all_course = [];
            foreach ($subscription_data as $row) {
                $all_course[] = $row->course_id;
                if (!isset($row->subscription_day) && !empty($row->subscription_day)) {
                    $date = strtotime("+" . $row->subscription_day . " day");
                } else {
                    $date = strtotime("+120 day");
                }
                $expiry = date('Y-m-d', $date);

                $ins = [
                    'course_id' => $row->course_id,
                    'user_id' => $sub->user_id,
                    'payment_id' => $data['id'],
                    'no_of_licence' => '1',
                    'sub_expire_date' => $expiry,
                    'status' => '1',
                ];

                if ($row->is_discount == '1') {
                    $ins['discount_code'] = $sub->coupon_code;
                    $ins['amount_to_be_paid'] = $row->dicount_course_price;
                } else {
                    $ins['discount_code'] = NULL;
                    $ins['amount_to_be_paid'] = currencyConvert($row->course_price);
                }
                $ins_subscription = CourseSubscription::create($ins);
                $licence = [
                    'course_subscription_id' => $ins_subscription->id,
                    'user_id' => $sub->user_id,
                    'course_id' => $row->course_id,
                    'status' => '1',
                ];
                if (!Auth::user()->hasRole('organization')) {
                    $lic = CourseSubscriptionLicence::create($licence);
                }
            }
            $delete = Cart::where('user_id', $sub->user_id)->delete();
            $user = User::select('name', 'email')->find($sub->user_id);
            $courses = Course::select('course_name')->whereIn("course_id", $all_course)->get();
            /*             * **********************************Start Email ******************************* */
            $data = [
                'template' => 'CourseSubscribe',
                'html_body' => [
                    'name' => $user->name,
                    'course' => $courses
                ],
                'subject' => 'Thank you for subscribing'
            ];
            \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
            /*             * **********************************End Email ******************************* */
            if (count($input) && !empty($input['razorpay_payment_id'])) {
                session()->put('success', 'Payment successful');
                session()->put('coupon_code', '');
                session()->put('coupon_code', '');
                session()->put('orderId', '');
            }
            return redirect()->route('thank_you_payment', ['id' => encrypt(rand())]);
        } else {
            return redirect()
            ->route('getcheckout')
            ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    /**
     * Paypal cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function paypalCancelTransaction(Request $request) {
        return redirect()
        ->route('getcheckout')
        ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }

    /**
     * Buy now Checkout page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function BuynowCheckout(Request $request, $id) {
        $course_id = decrypt($id);
        $user_id = Auth::user()->id;
        $coupon_code = session()->get('coupon_code', '');

        if ($coupon_code != '') {
            $coupon_data = DB::table("tbl_coupon")
                    ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                    ->leftJoin("tbl_coupon_has_course", function($join) {
                        $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                    })
                    ->where("tbl_coupon_has_course.course_id", $course_id)
                    ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                    ->where('tbl_coupon.coupon_code', $coupon_code)
                    ->groupBy('tbl_coupon_has_course.course_id')
                    ->get()
                    ->toArray();
            $cpn = Coupon::select('coupon_type', 'coupon_duration', 'coupon_percentage')->where('coupon_code', $coupon_code)->get()->first();

            $coupon_type = $cpn['coupon_type'];
            $coupon_duration = $cpn['coupon_duration'];
            $coupon_percentage = $cpn['coupon_percentage'];

            $coupon_course = array_column($coupon_data, 'course_id');
        } else {
            $coupon_type = '';
            $coupon_duration = '';
            $coupon_percentage = '';
            $coupon_course = [];
            $coupon_data = [];
        }
        $data = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`subscription_day`,`tbl_course`.`course_price` FROM `tbl_course` 
                LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id` 
                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                WHERE `tbl_course`.`course_id` = "' . $course_id . '"');
        $total_price = 0;
        $total_discount_price = 0;
        $original_price = 0;
        if (!empty($data)) {
            foreach ($data as $price) {
                $original_price+=currencyConvert($price->course_price);
            }
        }

        foreach ($data as $key => $value) {
            if (in_array($value->course_id, $coupon_course)) {
                $value->is_discount = '1';
                if ($coupon_type == '0') {
                    $value->dicount_course_price = 0;
                    $total_price += 0;
                    $total_discount_price += currencyConvert($value->course_price);
                } else {
                    $discount = currencyConvert($value->course_price) * $coupon_percentage / 100;
                    $value->dicount_course_price = currencyConvert($value->course_price) - $discount;
                    $total_price += $value->dicount_course_price;
                    $total_discount_price += $discount;
                }
            } else {
                $value->is_discount = '0';
                $total_price += currencyConvert($value->course_price);
            }
        }

        if ($total_price > 0) {
            $country = session()->get('db_country_data', '');
            $session_orderId = session()->get('orderId', '');
            $api = new Api(env('RAZORPAY_KEY', 'rzp_live_xEvpWXVcg1Knbn'), env('RAZORPAY_SECRET', 'Uv9GvPEQE41oyJYkKVEN0Vi5'));
            $order = $api->order->create(array('receipt' => Auth::user()->email, 'amount' => $total_price * 100, 'currency' => $country['short_name'])); // Creates order
            $orderId = $order['id'];
            session()->put('orderId', $orderId);

            $sub_data = [];
            $subdata['subscription'] = $data;
            $subdata['order_id'] = $orderId;
            $subdata['user_id'] = $user_id;
            $subdata['final_total'] = $total_price;
            $subdata['total_discount_price'] = $total_discount_price;
            $subdata['coupon_code'] = $coupon_code;
            $subdata['coupon_data'] = $coupon_data;

            $insert = [
                'user_id' => $user_id,
                'order_id' => $orderId,
                'type' => 'web',
                'price' => $original_price,
                'amount_to_be_paid' => $total_price,
                'discount_code' => $coupon_code,
                'discount' => $total_discount_price,
                'payment_mode' => 'razorpay',
                'payment_status' => 'INPROGRESS',
                'subscription_data' => json_encode($subdata),
                'status' => '1',
            ];

            $ins = Payment::updateOrCreate(['order_id' => $session_orderId, 'payment_status' => 'INPROGRESS'], $insert);
            return view('front.checkout.index')->with(['orderId' => $orderId, 'checkout_data' => $data, 'total_price' => $total_price, 'total_discount_price' => $total_discount_price, 'original_total' => $original_price]);
        } else {
            $order_id = $this->generateOrderId();
            $transaction_id = $this->generateTransactionId();
            session()->put('orderId', $order_id);

            $sub_data = [];
            $subdata['subscription'] = $data;
            $subdata['order_id'] = $order_id;
            $subdata['user_id'] = $user_id;
            $subdata['final_total'] = $total_price;
            $subdata['total_discount_price'] = $total_discount_price;
            $subdata['coupon_code'] = $coupon_code;
            $subdata['coupon_data'] = $coupon_data;

            $insert = [
                'user_id' => $user_id,
                'order_id' => $order_id,
                'transaction_id' => $transaction_id,
                'type' => 'manually',
                'price' => $original_price,
                'amount_to_be_paid' => $total_price,
                'discount_code' => $coupon_code,
                'discount' => $total_discount_price,
                'payment_mode' => 'Free Coupon',
                'payment_status' => 'SUCCESS',
                'subscription_data' => json_encode($subdata),
                'status' => '1',
            ];

            $ins = Payment::create($insert);
            $all_course = [];
            foreach ($subdata['subscription'] as $row) {
                $all_course[] = $row->course_id;
                if (!isset($row->subscription_day) && !empty($row->subscription_day)) {
                    $date = strtotime("+" . $row->subscription_day . " day");
                } else {
                    $date = strtotime("+120 day");
                }
                $expiry = date('Y-m-d', $date);
                $ins = [
                    'course_id' => $row->course_id,
                    'user_id' => $user_id,
                    'payment_id' => $ins->id,
                    'no_of_licence' => '1',
                    'sub_expire_date' => $expiry,
                    'status' => '1',
                ];
                if ($row->is_discount == '1') {
                    $ins['discount_code'] = $coupon_code;
                    $ins['amount_to_be_paid'] = $row->dicount_course_price;
                } else {
                    $ins['discount_code'] = NULL;
                    $ins['amount_to_be_paid'] = currencyConvert($row->course_price);
                }
                $ins_subscription = CourseSubscription::create($ins);

                $licence = [
                    'course_subscription_id' => $ins_subscription->id,
                    'user_id' => $user_id,
                    'course_id' => $row->course_id,
                    'status' => '1',
                ];
                if (!Auth::user()->hasRole('organization')) {

                    $lic = CourseSubscriptionLicence::create($licence);
                }
            }

            $delete = Cart::where('user_id', $user_id)->delete();
            session()->put('coupon_code', '');
            session()->put('orderId', '');

            /*             * **********************************Start Email ******************************* */
            $user = User::select('name', 'email')->find($user_id);
            $courses = Course::select('course_name')->whereIn("course_id", $all_course)->get();
            $data = [
                'template' => 'CourseSubscribe',
                'html_body' => [
                    'name' => $user->name,
                    'course' => $courses
                ],
                'subject' => 'Thank you for subscribing'
            ];
            \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
            /*             * **********************************End Email ******************************* */
            return redirect()->route('thank_you_payment', ['id' => encrypt(rand())]);
        }
    }

    /**
     * Razipay Callback method
     * @return response()
     */
    public function razorpayStore(Request $request) {
        $input = $request->all();
        $api = new Api(env('RAZORPAY_KEY', 'rzp_live_xEvpWXVcg1Knbn'), env('RAZORPAY_SECRET', 'Uv9GvPEQE41oyJYkKVEN0Vi5'));
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        $order_id = $input['razorpay_order_id'];


        if (isset($order_id) && $order_id != '') {
            $data = Payment::where('order_id', $order_id)->get()->first();
            $transaction_id = $input['razorpay_payment_id'];
            $payment_status = "success";
            $generated_response = json_encode($input);

            $update = [
                'transaction_id' => $transaction_id,
                'payment_status' => $payment_status,
                'status' => '1',
                'generated_response' => $generated_response,
            ];
            $update_payment = Payment::where('order_id', $order_id)->update($update);

            $sub = json_decode($data['subscription_data']);
            $subscription_data = $sub->subscription;
            $all_course = [];
            foreach ($subscription_data as $row) {
                $all_course[] = $row->course_id;
                if (!isset($row->subscription_day) && !empty($row->subscription_day)) {
                    $date = strtotime("+" . $row->subscription_day . " day");
                } else {
                    $date = strtotime("+120 day");
                }
                $expiry = date('Y-m-d', $date);

                $ins = [
                    'course_id' => $row->course_id,
                    'user_id' => $sub->user_id,
                    'payment_id' => $data['id'],
                    'no_of_licence' => '1',
                    'sub_expire_date' => $expiry,
                    'status' => '1',
                ];

                if ($row->is_discount == '1') {
                    $ins['discount_code'] = $sub->coupon_code;
                    $ins['amount_to_be_paid'] = $row->dicount_course_price;
                } else {
                    $ins['discount_code'] = NULL;
                    $ins['amount_to_be_paid'] = currencyConvert($row->course_price);
                }
                $ins_subscription = CourseSubscription::create($ins);
                $licence = [
                    'course_subscription_id' => $ins_subscription->id,
                    'user_id' => $sub->user_id,
                    'course_id' => $row->course_id,
                    'status' => '1',
                ];
                if (!Auth::user()->hasRole('organization')) {

                    $lic = CourseSubscriptionLicence::create($licence);
                }
            }
            $delete = Cart::where('user_id', $sub->user_id)->delete();
            $user = User::select('name', 'email')->find($sub->user_id);
            $courses = Course::select('course_name')->whereIn("course_id", $all_course)->get();
            /*             * **********************************Start Email ******************************* */
            $data = [
                'template' => 'CourseSubscribe',
                'html_body' => [
                    'name' => $user->name,
                    'course' => $courses
                ],
                'subject' => 'Thank you for subscribing'
            ];
            \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
            /*             * **********************************End Email ******************************* */
            if (count($input) && !empty($input['razorpay_payment_id'])) {
                session()->put('success', 'Payment successful');
                session()->put('coupon_code', '');
                session()->put('coupon_code', '');
                session()->put('orderId', '');
            }
            return redirect()->route('thank_you_payment', ['id' => encrypt(rand())]);
        }
    }

    /**
     * Redirect the user to the Payment Gateway.
     * @return Response
     */
    public function paytmPayment(Request $request) {
        $payment = PaytmWallet::with('receive');

        if (Auth::user()->mobile_no == '') {
            $mobile_number = '11122233344';
        } else {
            $mobile_number = Auth::user()->mobile_no;
        }

        $update = [
            'payment_mode' => 'paytm',
        ];

        $up = Payment::where('order_id', $request->order_id)->update($update);
        $country = session()->get('db_country_data', '');
        $payment->prepare([
            'order' => $request->order_id,
            'user' => Auth::user()->id,
            'mobile_number' => $mobile_number,
            'email' => Auth::user()->email,
            'amount' => $request->amount,
            'currency' => $country['short_name'],
            'callback_url' => route('paytm.callback'),
        ]);

        return $payment->receive();
    }

    /**
     * Obtain the payment information/Callback.
     * @return Object
     */
    public function paytmCallback() {
        // echo "here"; exit;
        $transaction = PaytmWallet::with('receive');

        //get important parameters via public methods
        $order_id = $transaction->getOrderId(); // Get order id
        $transaction_id = $transaction->getTransactionId(); // Get transaction id

        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        $generated_response = json_encode($response);

        if ($transaction->isSuccessful()) {
            if (isset($order_id) && $order_id != '') {
                $data = Payment::where('order_id', $order_id)->get()->first();
                $transaction_id = $transaction_id;
                $payment_status = "success";

                $update = [
                    'transaction_id' => $transaction_id,
                    'payment_status' => $payment_status,
                    'status' => '1',
                    'generated_response' => $generated_response,
                ];

                $update_payment = Payment::where('order_id', $order_id)->update($update);

                $sub = json_decode($data['subscription_data']);
                $subscription_data = $sub->subscription;
                $all_course = [];
                foreach ($subscription_data as $row) {
                    $all_course[] = $row->course_id;
                    if (!isset($row->subscription_day) && !empty($row->subscription_day)) {
                        $date = strtotime("+" . $row->subscription_day . " day");
                    } else {
                        $date = strtotime("+120 day");
                    }
                    $expiry = date('Y-m-d', $date);

                    $ins = [
                        'course_id' => $row->course_id,
                        'user_id' => $sub->user_id,
                        'payment_id' => $data['id'],
                        'no_of_licence' => '1',
                        'sub_expire_date' => $expiry,
                        'status' => '1',
                    ];

                    if ($row->is_discount == '1') {
                        $ins['discount_code'] = $sub->coupon_code;
                        $ins['amount_to_be_paid'] = $row->dicount_course_price;
                    } else {
                        $ins['discount_code'] = NULL;
                        $ins['amount_to_be_paid'] = currencyConvert($row->course_price);
                    }
                    $ins_subscription = CourseSubscription::create($ins);

                    $licence = [
                        'course_subscription_id' => $ins_subscription->id,
                        'user_id' => $sub->user_id,
                        'course_id' => $row->course_id,
                        'status' => '1',
                    ];
                    if (!Auth::user()->hasRole('organization')) {
                        $lic = CourseSubscriptionLicence::create($licence);
                    }
                }

                $delete = Cart::where('user_id', $sub->user_id)->delete();

                $msg = "Your payment has been done successfully.";
                session()->put('success', $msg);
                session()->put('coupon_code', '');
                session()->put('orderId', '');

                /*                 * **********************************Start Email ******************************* */
                $user = User::select('name', 'email')->find($sub->user_id);
                $courses = Course::select('course_name')->whereIn("course_id", $all_course)->get();
                $data = [
                    'template' => 'CourseSubscribe',
                    'html_body' => [
                        'name' => $user->name,
                        'course' => $courses
                    ],
                    'subject' => 'Thank you for subscribing'
                ];
                \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
                /*                 * **********************************End Email ******************************* */

                return redirect()->route('thank_you_payment', ['id' => encrypt(rand())]);
            }
        } else if ($transaction->isFailed()) {
            $update = [
                'generated_response' => $generated_response,
                'transaction_id' => $transaction_id,
                'payment_status' => 'FAILURE',
                'remark' => $response['RESPMSG'],
            ];

            $up = Payment::where('order_id', $order_id)->update($update);


            session()->put('coupon_code', '');
            session()->put('coupon_code', '');
            session()->put('orderId', '');

            $msg = $response['RESPMSG'];
            return view('page.error')->with(['message' => $msg]);
        } else if ($transaction->isOpen()) {
            $update = [
                'generated_response' => $generated_response,
                'transaction_id' => $transaction_id,
                'payment_status' => 'FAILURE',
                'remark' => $response['RESPMSG'],
            ];

            $up = Payment::where('order_id', $order_id)->update($update);

            session()->put('coupon_code', '');
            session()->put('orderId', '');

            $msg = $response['RESPMSG'];
            return view('page.thankyou')->with(['message' => $msg]);
        }
    }

    /**
     * Paytm Payment Page
     * @return Object
     */
    public function paytmPurchase() {
        return view('paytm.payment-page');
    }

    /**
     * Generate Order ID
     * @return string
     */
    public function generateOrderId() {
        $number = "order_" . Str::random(14);
        // call the same function if the barcode exists already
        if ($this->orderIdExists($number)) {
            return $this->generateOrderId();
        }
        return $number;
    }

    /**
     * Generate Transaction ID
     * @return string
     */
    public function generateTransactionId() {
        $number = "pay_" . Str::random(14); // better than rand()
        // call the same function if the barcode exists already
        if ($this->orderIdExists($number)) {
            return $this->generateOrderId();
        }

        // otherwise, it's valid and can be used
        return $number;
    }

    /**
     * Check Order ID Exists
     * @param type $number
     * @return type
     */
    public function orderIdExists($number) {
        return Payment::where('order_id', $number)->exists();
    }

    /**
     * Thank you page After Payment
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function thankYouPayment(Request $request) {
        $msg = "Your Course Subscribed Successfully!";
        return view('page.thankyou')->with(['message' => $msg]);
    }

}
