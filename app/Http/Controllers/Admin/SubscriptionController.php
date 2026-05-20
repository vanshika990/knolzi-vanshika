<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\Payment;
use Illuminate\Http\Request;
use Auth;

class SubscriptionController extends Controller {

    /**
     * Get Course List resource.
     * @param \Illuminate\Http\Request $request
     */
    public function Getcourseajax(Request $request) {
        if ($request->ajax()) {
            if (!isset($request->name)) {
                $fetchData = Course::select(['course_id', 'course_name', 'course_price'])->where('status', '1')->where('is_delete', '0')->orderBy('course_name', 'asc')->get();
            } else {
                $fetchData = Course::select(['course_id', 'course_name', 'course_price'])->where('status', '1')->where('is_delete', '0')->where('course_name', 'LIKE', "%{$request->name}%")->get();
            }
            $data = array();
            foreach ($fetchData as $row) {
                $data[] = array("id" => $row['course_id'], "name" => $row['course_name'], 'price' => $row['course_price']);
            }
            echo json_encode($data);
        }
        abort(404);
    }

    /**
     * organization Add manually subscription
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function OrgAddManuallySubscription(Request $request, $id) {
        if ($request->ajax()) {
            $course = Course::select(['course_id', 'course_name', 'course_price', 'subscription_day'])->where('status', '1')->where('is_delete', '0')->get();
            return view('admin.organization.addManuallySubscription')->with(['user_id' => $id, 'course' => $course]);
        }
        abort(404);
    }

    /**
     * organization store manually subscription
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function OrgAddManuallySubscriptionpost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'course_id' => 'required',
                'price' => 'required',
                'licence_price' => 'required',
                'licence' => 'required',
                'payment_mode' => 'required',
                'transaction' => 'required',
                'remark' => 'required',
                'plan_days' => 'required|integer|min:1'
            ]);

            $uid = decrypt($request['user_id']);
            $checkexist = CourseSubscription::Where('sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())->where('course_id', $request->course_id)->where('user_id', $uid)->where('status', '1')->count();

            if ($checkexist <= 0) {
                $insert_payment = [
                    'user_id' => $uid,
                    'order_id' => $request['transaction'],
                    'transaction_id' => $request['transaction'],
                    'type' => "manually",
                    'per_licence_amount' => $request['licence_price'],
                    'actual_price' => $request['price'],
                    'amount_to_be_paid' => $request['amount'],
                    'payment_status' => "Success",
                    'payment_mode' => $request['payment_mode'],
                    'remark' => $request['remark'],
                    'status' => '1',
                ];

                $payment = Payment::create($insert_payment);

                $sub_exp = date('Y-m-d', strtotime(date("Y-m-d", time()) . " + " . $request->plan_days . " day"));
                $insert_subscription = [
                    'course_id' => $request['course_id'],
                    'user_id' => $uid,
                    'payment_id' => $payment->id,
                    'no_of_licence' => $request['licence'],
                    'sub_expire_date' => $sub_exp,
                    'status' => '1',
                ];

                CourseSubscription::where([['user_id', '=', $uid], ['course_id', '=', $request['course_id']]])->update(['status' => '0']);
                $subscription = CourseSubscription::create($insert_subscription);

                $licence = [
                    'course_subscription_id' => $subscription->id,
                    'user_id' => $uid,
                    'course_id' => $request['course_id'],
                    'status' => '1',
                ];
                $lic = CourseSubscriptionLicence::create($licence);

                return ["success" => true, "message" => "Subscription added successfully."];
            } else {
                return response()->json(['errors' => ["allreadyexists" => ["The course is Already subscribed if you need to add more licenses or edit expire date the go to this user all course and edit the active subscription."]]], 422);
            }
        }
        abort(404);
    }

    /**
     * organization edit manually subscription
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function OrgEditManuallySubscription(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = CourseSubscription::find($id);
            return view('admin.organization.editManuallySubscription')->with(['data' => $data]);
        }
        abort(404);
    }

    /**
     * organization update manually subscription
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function OrgupdateManuallySubscription(Request $request) {
        if ($request->ajax()) {
            $id = decrypt($request->id);
            $coursesubscription = CourseSubscription::with('coursesublicence')->where('id', $id)->first();
            $licence_used = count($coursesubscription->coursesublicence);

            if ($licence_used <= $request->licence) {
                $update_data = [
                    'no_of_licence' => $request['licence'],
                    'sub_expire_date' => $request['licence_expire'],
                ];
                CourseSubscription::where('id', $id)->update($update_data);

                return ["success" => true, "message" => "Subscription update successfully."];
            } else {
                return response()->json(['errors' => ["allreadyexists" => ["you already used " . $licence_used . " licenses, so please remove the current license, and then after you will be able to decrease the license"]]], 422);
            }
        }
        abort(404);
    }

    /**
     * individual Add manually subscription
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function IndvAddManuallySubscription(Request $request, $id) {
        if ($request->ajax()) {
            $course = Course::select(['course_id', 'course_name', 'course_price', 'subscription_day'])->where('status', '1')->where('is_delete', '0')->get();
            return view('admin.individual.addManuallySubscription')->with(['user_id' => $id, 'course' => $course]);
        }
        abort(404);
    }

    /**
     * individual store manually subscription
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function IndvAddManuallySubscriptionpost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'course_id' => 'required',
                'plan_days' => 'required|integer|min:1',
                'remark' => 'required',
            ]);

            $uid = decrypt($request['user_id']);
            $insert_payment = [
                'user_id' => $uid,
                'type' => "manually",
                'per_licence_amount' => $request['price'],
                'actual_price' => $request['price'],
                'amount_to_be_paid' => $request['price'],
                'payment_status' => "Success",
                'remark' => $request['remark'],
                'status' => '1',
            ];

            $payment = Payment::create($insert_payment);
            $sub_exp = date('Y-m-d', strtotime(date("Y-m-d", time()) . " + " . $request->plan_days . " day"));
            $insert_subscription = [
                'course_id' => $request['course_id'],
                'user_id' => $uid,
                'payment_id' => $payment->id,
                'no_of_licence' => '1',
                'sub_expire_date' => $sub_exp,
                'status' => '1',
                'discount_code' => NULL,
                'amount_to_be_paid' => $request['price'],
            ];

            CourseSubscription::where([['user_id', '=', $uid], ['course_id', '=', $request['course_id']]])->update(['status' => '0']);
            $subscription = CourseSubscription::create($insert_subscription);

            $licence = [
                'course_subscription_id' => $subscription->id,
                'user_id' => $uid,
                'course_id' => $request['course_id'],
                'status' => '1',
            ];
            $lic = CourseSubscriptionLicence::create($licence);

            return ["success" => true, "message" => "Subscription added successfully."];
        }
        abort(404);
    }

}
