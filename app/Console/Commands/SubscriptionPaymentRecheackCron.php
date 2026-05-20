<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\Cart;
use PaytmWallet;
use App\Models\Payment;
use Razorpay\Api\Api;
use App\Mail\SendEmail;
use paytm\paytmchecksum\PaytmChecksum;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class SubscriptionPaymentRecheackCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subpayrecheack:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        \Log::info("Payment=" . date('Y-m-d H:i:s'));
        $last_1hour = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $payment_data = Payment::whereIn('payment_status', ['INPROGRESS', 'PROCESSING', 'PENDING'])->whereNull('transaction_id')
                        ->where('created_at', '<=', $last_1hour)->get();
        if (!empty($payment_data)) {
            foreach ($payment_data as $value) {
                if ($value->payment_mode == 'paytm' || $value->payment_mode == 'Paytm') {
                    $paytmParams = array();
                    $paytmParams1 = array();
                    $paytmParams1["body"] = array("mid" => env('PAYTM_MERCHANT_ID'), "orderId" => $value->order_id);

                    $paytmParams["MID"] = env('PAYTM_MERCHANT_ID');
                    $paytmParams["ORDERID"] = $value->order_id;
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
                        $payment_success = $this->payment_success($value->order_id, $responseData, 1);
                    } elseif ($payment_status == 'TXN_FAILURE') {
                        $payment_fail = $this->payment_fail($value->order_id, $responseDat);
                    }
                } elseif ($value->payment_mode == 'razorpay') {
                    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                    $order = $api->order->fetch($value->order_id)->toarray();
                    $payment_status = $order['status'];
                    if ($payment_status == 'paid') {
                        $payment_success = $this->payment_success($value->order_id, $order, 2);
                    } elseif ($payment_status != 'paid' && $order['amount_paid'] == '0') {
                        $payment_fail = $this->payment_fail($value->order_id, $order);
                    }
                } elseif ($value->payment_mode == 'paypal') {
                    $provider = new PayPalClient;
                    $provider->setApiCredentials(config('paypal'));
                    $provider->getAccessToken();
                    $response = $provider->showOrderDetails($value->transaction_id);
                    if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                        $payment_success = $this->payment_success($value->order_id, $response, 3);
                    } else {
                        $this->payment_fail($value->order_id, $response);
                    }
                }
            }
        }
        \Log::info("Payment cron run successfully...");
    }

    /**
     * If Payment Success
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
//            $date = strtotime("+" . $row->subscription_day . " day");
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
                $ins['amount_to_be_paid'] = $row->course_price;
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
     * If payment Failed
     * @param type $orderId
     * @param type $data
     * @param type $type
     */
    private function payment_fail($orderId, $data) {
        $update = [
            'generated_response' => $data,
            'payment_status' => 'FAILED',
        ];
        $update_payment = Payment::where('order_id', $orderId)->update($update);

        /* $get_order_data = Payment::where('order_id', $orderId)->first();
          $sub = json_decode($get_order_data['subscription_data']);
          $subscription_data = $sub->subscription;

          $course_array = [];
          foreach ($subscription_data as $value) {
          array_push($course_array, $value->course_id);
          }

          $user = User::select('name', 'email')->where('id', $sub->user_id)->first();
          $courses = Course::select('course_name')->whereIn("course_id", $course_array)->get(); */

//        $data = [
//            'template' => 'CourseSubscribe',
//            'html_body' => [
//                'name' => $user->name,
//                'course' => $courses
//            ],
//            'subject' => 'Your Payment was failed'
//        ];
//        \Mail::to($user->email)->send(new \App\Mail\SendEmail($data));
    }

}
