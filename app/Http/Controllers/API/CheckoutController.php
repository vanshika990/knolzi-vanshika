<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\CourseSubscription;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Coupon;
use Razorpay\Api\Api;
use App\Models\CourseSubscriptionLicence;
use App\Mail\SendEmail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use paytm\paytmchecksum\PaytmChecksum;
use Stevebauman\Location\Facades\Location;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CheckoutController extends BaseController {

    public $paginationlimit = 10;

    /**
     * Initialize Payment Cart Page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function cartInitializePaymentPaytm(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'payment_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        if ($request->payment_type == 'paytm' || $request->payment_type == 'razorpay') {
            $position = Location::get($request->ip());
            $country = session()->get('country', '');
            if ($country != "India") {
                return $this->sendError('Validation Error.', ['not_able_to_paid' => ['You cannot be paid outside of India']]);
            }
        }
        $user_id = $request->user_id;
        $subscribe_course = getSubscriptCourse();
        if (!empty($subscribe_course)) {
            Cart::where(['user_id' => $user_id])->whereIn("course_id", $subscribe_course)->delete();
        }
        $coupon_data = [];
        $coupon_course = [];
        $coupon_code = "";

        if ($request->has('coupon_code')) {
            $validator = Validator::make($request->all(), [
                        'coupon_code' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $coupon_code = $request->coupon_code;
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
            if (empty($coupon_data)) {
                return $this->sendError('Validation Error.', ['coupon_code_expire' => ['Coupon is expired or not applicable to selected courses.']]);
            }
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
        if (empty($data)) {
            return $this->sendError('Validation Error.', ['allready_exist_not' => ['Course Already subscribe or not found the course in the cart.']]);
        }
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
        if ($total_price <= 0) {
            $orderId = $this->generateOrderId();
            $transaction_id = $this->generateTransactionId();
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
                'transaction_id' => $transaction_id,
                'type' => 'app',
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
            $p_id = $ins->id;
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
                    'payment_id' => $p_id,
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
            $success = [];
            $success['flag'] = 1;
            return $this->sendResponse($success, 'Course subscribed successfully.');
        } else {
            if ($request->payment_type == 'paytm') {
                $country = session()->get('db_country_data', '');
                if (env('PAYTM_ENVIRONMENT') == "LOCAL") {
                    $callback_url = "https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID=";
                } else {
                    $callback_url = "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=";
                }
                $orderId = $this->generateOrderId();
                $transaction_id = $this->generateTransactionId();
                $paytmParams = array();

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
                    'type' => 'app',
                    'price' => $original_price,
                    'amount_to_be_paid' => $total_price,
                    'discount_code' => $coupon_code,
                    'discount' => $total_discount_price,
                    'payment_mode' => 'paytm',
                    'payment_status' => 'INPROGRESS',
                    'subscription_data' => json_encode($subdata),
                    'status' => '1',
                ];
                $payment = Payment::updateOrCreate(["user_id" => $user_id, 'payment_status' => 'INPROGRESS'], $insert);

                $CUST_ID = 'CUSTOMER' . rand(111, 999) . "" . $user_id;
                $PAYTM_MERCHANT_WEBSITE = env('PAYTM_MERCHANT_WEBSITE');
                $MID = env('PAYTM_MERCHANT_ID', 'EQbrCA56351182876695');
                $paytmParams["body"] = array(
                    "requestType" => "Payment",
                    "mid" => $MID,
                    "websiteName" => $PAYTM_MERCHANT_WEBSITE,
                    "orderId" => $orderId,
                    "callbackUrl" => $callback_url . $orderId,
                    "txnAmount" => array(
                        "value" => $total_price,
                        "currency" => $country['short_name'],
                    ),
                    "userInfo" => array(
                        "custId" => $CUST_ID,
                    ),
                );
                $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), env('PAYTM_MERCHANT_KEY'));
                $paytmParams["head"] = array(
                    "signature" => $checksum,
                );
                $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

                if (env('PAYTM_ENVIRONMENT') == "LOCAL") {
                    $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=" . $MID . "&orderId=" . $orderId;
                } else {
                    $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=" . $MID . "&orderId=" . $orderId;
                }

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $response = curl_exec($ch);
                $arr = (array) (json_decode($response));

                if (isset($arr['body']->txnToken)) {
                    $token = $arr['body']->txnToken;
                } else {
                    $token = "";
                }

                $success['checksum'] = $checksum;
                $success['ORDER_ID'] = $orderId;
                $success['CUST_ID'] = $CUST_ID;
                $success['MID'] = $MID;
                $success['CHANNEL_ID'] = 'WAP';
                $success['TXN_AMOUNT'] = $total_price;
                $success['WEBSITE'] = $PAYTM_MERCHANT_WEBSITE;
                $success['CALLBACK_URL'] = $callback_url . $orderId;
                $success['INDUSTRY_TYPE_ID'] = env('PAYTM_INDUSTRY_TYPE', 'Retail');
                $success['response'] = $arr;
                $success['txnToken'] = $token;
                $success['flag'] = 0;
                return $this->sendResponse($success, 'Success.');
            } elseif ($request->payment_type == 'razorpay') {
                $country = session()->get('db_country_data', '');
                $user = User::find($request->user_id);
                $api = new Api(env('RAZORPAY_KEY', 'rzp_test_8OMKgXooPydof8'), env('RAZORPAY_SECRET', 'r1J7gA03kvOcWxIlKMjTRacS'));
                $order = $api->order->create(array('receipt' => $user->email, 'amount' => $total_price * 100, 'currency' => $country['short_name'])); // Creates order

                $sub_data = [];
                $subdata['subscription'] = $data;
                $subdata['order_id'] = $order['id'];
                $subdata['user_id'] = $user_id;
                $subdata['final_total'] = $total_price;
                $subdata['total_discount_price'] = $total_discount_price;
                $subdata['coupon_code'] = $coupon_code;
                $subdata['coupon_data'] = $coupon_data;

                $insert = [
                    'user_id' => $user_id,
                    'order_id' => $order['id'],
                    'type' => 'app',
                    'price' => $original_price,
                    'amount_to_be_paid' => $total_price,
                    'discount_code' => $coupon_code,
                    'discount' => $total_discount_price,
                    'payment_mode' => 'razorpay',
                    'payment_status' => 'INPROGRESS',
                    'subscription_data' => json_encode($subdata),
                    'status' => '1',
                ];
                $payment = Payment::updateOrCreate(["user_id" => $user_id, 'payment_status' => 'INPROGRESS'], $insert);
                $success['order_id'] = $order['id'];
                $success['amount'] = $order['amount'];
                $success['currency'] = $order['currency'];
                $success['key_id'] = env('RAZORPAY_KEY', 'rzp_test_8OMKgXooPydof8');
                $success['merchant'] = 'Knolzi';
                $success['flag'] = 0;
                return $this->sendResponse($success, 'Success.');
            } elseif ($request->payment_type == 'paypal') {
                try {
                    $country = session()->get('db_country_data', '');
                    $country['short_name'] = "USD";
                    $provider = new PayPalClient;
                    $provider->setApiCredentials(config('paypal'));
                    $paypalToken = $provider->getAccessToken();

                    $response = $provider->createOrder([
                        "intent" => "CAPTURE",
                        "purchase_units" => [
                            0 => [
                                "amount" => [
                                    "currency_code" => $country['short_name'],
                                    "value" => $total_price
                                ]
                            ]
                        ]
                    ]);
                    if (isset($response['id']) && $response['id'] != null) {
                        $link = "";
                        foreach ($response['links'] as $links) {
                            if ($links['rel'] == 'approve') {
                                $link = $links['href'];
                            }
                        }
                        if (!empty($link)) {
                            $orderId = $this->generateOrderId();
                            $user = User::find($request->user_id);
                            $sub_data = [];
                            $subdata['subscription'] = $data;
                            $subdata['transaction_id'] = $response['id'];
                            $subdata['order_id'] = $orderId;
                            $subdata['user_id'] = $user_id;
                            $subdata['final_total'] = $total_price;
                            $subdata['total_discount_price'] = $total_discount_price;
                            $subdata['coupon_code'] = $coupon_code;
                            $subdata['coupon_data'] = $coupon_data;

                            $insert = [
                                'user_id' => $user_id,
                                'order_id' => $orderId,
                                'type' => 'app',
                                'price' => $original_price,
                                'amount_to_be_paid' => $total_price,
                                'discount_code' => $coupon_code,
                                'discount' => $total_discount_price,
                                'payment_mode' => 'paypal',
                                'transaction_id' => $response['id'],
                                'payment_status' => 'INPROGRESS',
                                'subscription_data' => json_encode($subdata),
                                'generated_response' => json_encode($response),
                                'status' => '1',
                            ];

                            $payment = Payment::updateOrCreate(["user_id" => $user_id, 'payment_status' => 'INPROGRESS'], $insert);
                            $success['link'] = $link;
                            $success['unique_id'] = $orderId;
                            $success['amount_to_be_paid'] = $total_price;
                            return $this->sendResponse($success, 'Success.');
                        }
                    }
                    return $this->sendError('Validation Error.', ['error' => ['Something went wrong.']]);
                } catch (Exception $e) {
                    return $this->sendError('Validation Error.', ['error' => [$e->getMessage()]]);
                }
            }
        }
    }

    /**
     * Check Transaction Status
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function checkTransactionStatus(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'payment_type' => 'required',
                    'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($request->payment_type == 'paytm') {
            $paytmParams = array();
            $paytmParams1 = array();
            $paytmParams1["body"] = array("mid" => env('PAYTM_MERCHANT_ID'), "orderId" => $request->order_id);

            $paytmParams["MID"] = env('PAYTM_MERCHANT_ID', 'EQbrCA56351182876695');
            $paytmParams["ORDERID"] = $request->order_id;
            $paytmChecksum = PaytmChecksum::generateSignature(json_encode($paytmParams1["body"], JSON_UNESCAPED_SLASHES), env('PAYTM_MERCHANT_KEY'));
            $paytmParams['CHECKSUMHASH'] = urlencode($paytmChecksum);
            $postData = "JsonData=" . json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            $paytmParams["head"] = array("signature" => $paytmChecksum);
            if (env('PAYTM_ENVIRONMENT') == "LOCAL") {
                $url = "https://securegw-stage.paytm.in/merchant-status/getTxnStatus";
            } else {
                $url = "https://securegw.paytm.in/merchant-status/getTxnStatus";
            }
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $response = curl_exec($ch);
            $responseData = json_decode($response, true);
            $payment_status = $responseData['STATUS'];

            if ($payment_status == 'TXN_SUCCESS') {
                $payment_success = $this->payment_success($request->order_id, $responseData, 1);
            } elseif ($payment_status == 'TXN_FAILURE') {
                $payment_fail = $this->payment_fail($request->order_id, $responseData, 1);
            }

            if ($payment_status == 'TXN_SUCCESS') {
                $payment_status = "SUCCESS";
                $success['payment_status'] = $payment_status;
                return $this->sendResponse($success, 'Course subscribed successfully.');
            } else {
                $payment_status = "FAILED";
                $success['payment_status'] = $payment_status;
                return $this->sendResponse($success, $responseData['RESPMSG']);
            }
        } elseif ($request->payment_type == 'razorpay') {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $order = $api->order->fetch($request->order_id)->toarray();

            $payment_status = $order['status'];

            if ($payment_status == 'paid') {
                $payment_success = $this->payment_success($request->order_id, $order, 2);
            } elseif ($payment_status != 'paid' && $order['amount_paid'] == '0') {
                $payment_fail = $this->payment_fail($request->order_id, $order, 2);
            }

            if ($payment_status == 'paid') {
                $payment_status = "SUCCESS";
                $success['payment_status'] = $payment_status;
                return $this->sendResponse($success, 'Course subscribed successfully.');
            } else {
                $payment_status = "FAILED";
                $success['payment_status'] = $payment_status;
                return $this->sendResponse($success, 'Transaction failed.');
            }
        } elseif ($request->payment_type == 'paypal') {
            $validator = Validator::make($request->all(), [
                        'token' => 'required',
                        'unique_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $update = [
                'order_id' => $request->order_id,
                'transaction_id' => $request->token,
            ];

            $update_payment = Payment::where('order_id', $request->unique_id)->update($update);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                $payment_success = $this->payment_success($request->order_id, $response, 2);
                $payment_status = "SUCCESS";
                $success['payment_status'] = $payment_status;
                return $this->sendResponse($success, 'Course subscribed successfully.');
            } else {
                $payment_fail = $this->payment_fail($request->order_id, $response, 3);
                return $this->sendError(['error' => [$response['message']]]);
            }
        }
    }

    /**
     * If Payment success
     * @param type $orderId
     * @param type $data
     * @param type $type
     */
    private function payment_success($orderId, $data, $type) {
        if ($type == 1) {
            $transaction_id = $data['TXNID'];
        } elseif ($type == 2) {
            $transaction_id = null;
        } else {
            $transaction_id = $data['id'];
        }

        $update = [
            'transaction_id' => $transaction_id,
            'generated_response' => $data,
            'payment_status' => 'SUCCESS',
            'status' => '1',
        ];
        $update_payment = Payment::where('order_id', $orderId)->update($update);

        $get_order_data = Payment::where('order_id', $orderId)->first();
        $sub = json_decode($get_order_data['subscription_data']);
        $subscription_data = $sub->subscription;

        $course_array = [];
        foreach ($subscription_data as $row) {
            array_push($course_array, $row->course_id);

            if (!isset($row->subscription_day) && !empty($row->subscription_day)) {
                $date = strtotime("+" . $row->subscription_day . " day");
            } else {
                $date = strtotime("+120 day");
            }
            $expiry = date('Y-m-d', $date);

            $ins = [
                'course_id' => $row->course_id,
                'user_id' => $sub->user_id,
                'payment_id' => $get_order_data['id'],
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
            $lic = CourseSubscriptionLicence::create($licence);
        }
        $delete = Cart::where('user_id', $sub->user_id)->delete();

        $user = User::select('name', 'email')->where('id', $sub->user_id)->first();
        $courses = Course::select('course_name')->whereIn("course_id", $course_array)->get();

        $data = [
            'template' => 'CourseSubscribe',
            'html_body' => [
                'name' => $user->name,
                'course' => $courses
            ],
            'subject' => 'Thank you for subscribing'
        ];
        \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
    }

    /**
     * If Payment Fail
     * @param type $orderId
     * @param type $data
     * @param type $type
     */
    private function payment_fail($orderId, $data, $type) {

        $update = [
            'order_id' => $orderId,
            'generated_response' => $data,
            'payment_status' => 'FAILED',
        ];

        $update_payment = Payment::where('order_id', $orderId)->update($update);

        $get_order_data = Payment::where('order_id', $orderId)->first();
        $sub = json_decode($get_order_data['subscription_data']);
        $subscription_data = $sub->subscription;

        $course_array = [];
        foreach ($subscription_data as $value) {
            array_push($course_array, $value->course_id);
        }

        $user = User::select('name', 'email')->where('id', $sub->user_id)->first();
        $courses = Course::select('course_name')->whereIn("course_id", $course_array)->get();

        $data = [
            'template' => 'CourseSubscribeFail',
            'html_body' => [
                'name' => $user->name,
                'course' => $courses
            ],
            'subject' => 'Your Payment was failed'
        ];
        \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
    }

    /**
     * Generate Transaction ID
     * @return string
     */
    public function generateTransactionId() {
        $number = "pay_" . Str::random(14);

        if ($this->transactionIdExists($number)) {
            return $this->generateTransactionId();
        }
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
     * Check Transaction ID Exists
     * @param type $number
     * @return type
     */
    public function transactionIdExists($number) {
        return Payment::where('transaction_id', $number)->exists();
    }

    /**
     * Generate Order ID
     * @return string
     */
    public function generateOrderId() {
        $number = "order_" . Str::random(14);
        if ($this->orderIdExists($number)) {
            return $this->generateOrderId();
        }
        return $number;
    }

    /**
     * Initialize Payment Cart Page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function singleCourseInitializePaymentPaytm(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'payment_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($request->payment_type == 'paytm') {
            $position = Location::get($request->ip());
            $country = session()->get('country', '');
            if ($country != "India") {
                return $this->sendError('Validation Error.', ['not_able_to_paid' => ['You cannot be paid with Paytm outside of India']]);
            }
        }
        $user_id = $request->user_id;
        $course_id = $request->course_id;

        $subscribe_course = getSubscriptCourse();
        if (!empty($subscribe_course)) {
            Cart::where(['user_id' => $user_id])->whereIn("course_id", $subscribe_course)->delete();
        }
        $coupon_data = [];
        $coupon_course = [];
        $coupon_code = "";

        if ($request->has('coupon_code')) {
            $validator = Validator::make($request->all(), [
                        'coupon_code' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $coupon_code = $request->coupon_code;
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
            if (empty($coupon_data)) {
                return $this->sendError('Validation Error.', ['coupon_code_expire' => ['Coupon is expired or not applicable to selected courses.']]);
            }
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
            $coupon_code = '';
        }
        $cart_data = DB::select('SELECT `tbl_course`.`subscription_day`, `tbl_course`.`course_id`,`tbl_course`.`course_name`,`tbl_course`.`course_image`,`tbl_course`.`slug`,`tbl_course`.`course_price`, `tbl_course`.`course_id` FROM `tbl_course`
                WHERE `tbl_course`.`course_id` = "' . $course_id . '"');
        if (empty($cart_data)) {
            return $this->sendError('Validation Error.', ['allready_exist_not' => ['Course Already subscribe or not found the course in the cart.']]);
        }
        $total_price = 0;
        $total_discount_price = 0;
        $original_price = 0;
        if (!empty($data)) {
            foreach ($data as $price) {
                $original_price+=currencyConvert($price->course_price);
            }
        }
        foreach ($cart_data as $key => $value) {
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
        if ($total_price <= 0) {
            $orderId = $this->generateOrderId();
            $transaction_id = $this->generateTransactionId();

            $sub_data = [];
            $subdata['subscription'] = $cart_data;
            $subdata['order_id'] = $orderId;
            $subdata['user_id'] = $user_id;
            $subdata['final_total'] = $total_price;
            $subdata['total_discount_price'] = $total_discount_price;
            $subdata['coupon_code'] = $coupon_code;
            $subdata['coupon_data'] = $coupon_data;

            $insert = [
                'user_id' => $user_id,
                'order_id' => $orderId,
                'transaction_id' => $transaction_id,
                'type' => 'app',
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
            $success = [];
            $success['flag'] = 1;
            return $this->sendResponse($success, 'Course subscribed successfully.');
        } else {
            if ($request->payment_type == 'paytm') {
                $country = session()->get('db_country_data', '');
                if (env('PAYTM_ENVIRONMENT') == "LOCAL") {
                    $callback_url = "https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID=";
                } else {
                    $callback_url = "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=";
                }
                $orderId = $this->generateOrderId();
                $transaction_id = $this->generateTransactionId();
                $paytmParams = array();

                $sub_data = [];
                $subdata['subscription'] = $cart_data;
                $subdata['order_id'] = $orderId;
                $subdata['user_id'] = $user_id;
                $subdata['final_total'] = $total_price;
                $subdata['total_discount_price'] = $total_discount_price;
                $subdata['coupon_code'] = $coupon_code;
                $subdata['coupon_data'] = $coupon_data;

                $insert = [
                    'user_id' => $user_id,
                    'order_id' => $orderId,
                    'type' => 'app',
                    'price' => $original_price,
                    'amount_to_be_paid' => $total_price,
                    'discount_code' => $coupon_code,
                    'discount' => $total_discount_price,
                    'payment_mode' => 'paytm',
                    'payment_status' => 'INPROGRESS',
                    'subscription_data' => json_encode($subdata),
                    'status' => '1',
                ];

                $payment = Payment::updateOrCreate(["user_id" => $user_id, 'payment_status' => 'INPROGRESS'], $insert);

                $CUST_ID = 'CUSTOMER' . rand(111, 999) . "" . $user_id;
                $PAYTM_MERCHANT_WEBSITE = env('PAYTM_MERCHANT_WEBSITE');
                $MID = env('PAYTM_MERCHANT_ID', 'EQbrCA56351182876695');
                $paytmParams["body"] = array(
                    "requestType" => "Payment",
                    "mid" => $MID,
                    "websiteName" => $PAYTM_MERCHANT_WEBSITE,
                    "orderId" => $orderId,
                    "callbackUrl" => $callback_url . $orderId,
                    "txnAmount" => array(
                        "value" => $total_price,
                        "currency" => $country['short_name'],
                    ),
                    "userInfo" => array(
                        "custId" => $CUST_ID,
                    ),
                );
                $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), env('PAYTM_MERCHANT_KEY'));
                $paytmParams["head"] = array(
                    "signature" => $checksum,
                );
                $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

                if (env('PAYTM_ENVIRONMENT') == "LOCAL") {
                    $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=" . $MID . "&orderId=" . $orderId;
                } else {
                    $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=" . $MID . "&orderId=" . $orderId;
                }

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $response = curl_exec($ch);
                $arr = (array) (json_decode($response));

                if (isset($arr['body']->txnToken)) {
                    $token = $arr['body']->txnToken;
                } else {
                    $token = "";
                }

                $success['checksum'] = $checksum;
                $success['ORDER_ID'] = $orderId;
                $success['CUST_ID'] = $CUST_ID;
                $success['MID'] = $MID;
                $success['CHANNEL_ID'] = 'WAP';
                $success['TXN_AMOUNT'] = $total_price;
                $success['WEBSITE'] = $PAYTM_MERCHANT_WEBSITE;
                $success['CALLBACK_URL'] = $callback_url . $orderId;
                $success['INDUSTRY_TYPE_ID'] = env('PAYTM_INDUSTRY_TYPE', 'Retail');
                $success['response'] = $arr;
                $success['txnToken'] = $token;
                $success['flag'] = 0;
                return $this->sendResponse($success, 'Success.');
            }
            if ($request->payment_type == 'razorpay') {
                $country = session()->get('db_country_data', '');
                $user = User::find($request->user_id);
                $api = new Api(env('RAZORPAY_KEY', 'rzp_test_8OMKgXooPydof8'), env('RAZORPAY_SECRET', 'r1J7gA03kvOcWxIlKMjTRacS'));
                $order = $api->order->create(array('receipt' => $user->email, 'amount' => $total_price * 100, 'currency' => $country['short_name'])); // Creates order

                $sub_data = [];
                $subdata['subscription'] = $cart_data;
                $subdata['order_id'] = $order['id'];
                $subdata['user_id'] = $user_id;
                $subdata['final_total'] = $total_price;
                $subdata['total_discount_price'] = $total_discount_price;
                $subdata['coupon_code'] = $coupon_code;
                $subdata['coupon_data'] = $coupon_data;

                $insert = [
                    'user_id' => $user_id,
                    'order_id' => $order['id'],
                    'type' => 'app',
                    'price' => $original_price,
                    'amount_to_be_paid' => $total_price,
                    'discount_code' => $coupon_code,
                    'discount' => $total_discount_price,
                    'payment_mode' => 'pazorpay',
                    'payment_status' => 'INPROGRESS',
                    'subscription_data' => json_encode($subdata),
                    'status' => '1',
                ];

                $payment = Payment::updateOrCreate(["user_id" => $user_id, 'payment_status' => 'INPROGRESS'], $insert);

                $success['order_id'] = $order['id'];
                $success['amount'] = $total_price;
                $success['currency'] = $order['currency'];
                $success['key_id'] = env('RAZORPAY_KEY', 'rzp_test_8OMKgXooPydof8');
                $success['merchant'] = 'Knolzi';
                $success['flag'] = 0;
                return $this->sendResponse($success, 'Success.');
            }
            if ($request->payment_type == 'paypal') {

                try {
                    $country = session()->get('db_country_data', '');
                    $country['short_name'] = "USD";
                    $provider = new PayPalClient;
                    $provider->setApiCredentials(config('paypal'));
                    $paypalToken = $provider->getAccessToken();

                    $response = $provider->createOrder([
                        "intent" => "CAPTURE",
                        "purchase_units" => [
                            0 => [
                                "amount" => [
                                    "currency_code" => $country['short_name'],
                                    "value" => $total_price
                                ]
                            ]
                        ]
                    ]);

                    if (isset($response['id']) && $response['id'] != null) {
                        $link = "";
                        foreach ($response['links'] as $links) {
                            if ($links['rel'] == 'approve') {
                                $link = $links['href'];
                            }
                        }
                        if (!empty($link)) {
                            $orderId = $this->generateOrderId();
                            $user = User::find($request->user_id);
                            $sub_data = [];
                            $subdata['subscription'] = $cart_data;
                            $subdata['transaction_id'] = $response['id'];
                            $subdata['order_id'] = $orderId;
                            $subdata['user_id'] = $user_id;
                            $subdata['final_total'] = $total_price;
                            $subdata['total_discount_price'] = $total_discount_price;
                            $subdata['coupon_code'] = $coupon_code;
                            $subdata['coupon_data'] = $coupon_data;

                            $insert = [
                                'user_id' => $user_id,
                                'order_id' => $orderId,
                                'type' => 'app',
                                'price' => $original_price,
                                'amount_to_be_paid' => $total_price,
                                'discount_code' => $coupon_code,
                                'discount' => $total_discount_price,
                                'payment_mode' => 'paypal',
                                'transaction_id' => $response['id'],
                                'payment_status' => 'INPROGRESS',
                                'subscription_data' => json_encode($subdata),
                                'generated_response' => json_encode($response),
                                'status' => '1',
                            ];

                            $payment = Payment::updateOrCreate(["user_id" => $user_id, 'payment_status' => 'INPROGRESS'], $insert);
                            $success['link'] = $link;
                            $success['unique_id'] = $orderId;
                            $success['amount_to_be_paid'] = $total_price;
                            return $this->sendResponse($success, 'Success.');
                        }
                    }
                    return $this->sendError('Validation Error.', ['error' => ['Something went wrong.']]);
                } catch (Exception $e) {
                    return $this->sendError('Validation Error.', ['error' => [$e->getMessage()]]);
                }
            }
        }
    }

}
