<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Common\ViewCouponDatatable;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponHasCourse;
use App\Models\Course;
use Illuminate\Http\Request;
use App\DataTables\Common\getCouponUsedUserDataTable;

class CouponController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ViewCouponDatatable $dataTable) {
        return $dataTable->render('admin.coupon.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $courseData = Course::select(['course_id', 'course_name'])->where('is_delete', '0')->where('status', '1')->get();
        return view('admin.coupon.create')->with(['AllCourse' => $courseData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            $today = date('Y-m-d');
            $code = Coupon::where('coupon_code', $request->code)->where('coupon_end_date', '>', $today)->get()->toArray();
            if (isset($code) && !empty($code)) {
                return ["error" => true, "message" => "Coupon code already exist"];
            } else {
                // validation
                $request->validate([
                    'coupon_title' => 'required',
                    'course_id' => 'required|exists:tbl_course,course_id',
                    'code' => 'required',
                    'start_date' => 'required|date|after:yesterday',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'coupon_type' => 'required|integer|between:0,1',
                        ], [
                    'coupon_type.between' => "Select valid coupon type",
                ]);

                $insertData = [
                    'coupon_title' => $request->coupon_title,
                    'coupon_code' => $request->code,
                    'coupon_start_date' => $request->start_date,
                    'coupon_end_date' => $request->end_date,
                    'coupon_type' => $request->coupon_type,
                    'status' => '1',
                ];

                if (isset($request->coupon_type) && $request->coupon_type == '0') {
                    $request->validate([
                        'coupon_duration' => 'required|integer|min:1',
                    ]);
                    $insertData['coupon_duration'] = $request->coupon_duration;
                }

                if (isset($request->coupon_type) && $request->coupon_type == '1') {
                    $request->validate([
                        'percentage' => 'required|integer|min:1|max:100',
                    ]);
                    $insertData['coupon_percentage'] = $request->percentage;
                }

                $add_data = Coupon::create($insertData);
                $coupon_id = $add_data->coupon_id;

                // insert course data
                $insert_coupon_data = [];
                $language = $request['course_id'];
                if (!empty($language)) {
                    foreach ($language as $key => $value) {
                        $insert_coupon_data[] = [
                            'coupon_id' => $coupon_id,
                            'course_id' => $value,
                            'status' => '1',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    $add_language = CouponHasCourse::insert($insert_coupon_data);
                }
                return ["success" => true, "message" => "Coupon created successfully"];
            }
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $coupon = Coupon::where('coupon_id', $id)->first();
            $course = CouponHasCourse::with('course')->where('coupon_id', $id)->get()->toArray();
            return view('admin.coupon.show')->with(['coupon' => $coupon, 'course' => $course]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $id = decrypt($id);
        $coupon = Coupon::where('coupon_id', $id)->first();
//        dd($coupon);
        $courses = Course::select('course_id', 'course_name')->where('is_delete', '0')->where('status', '1')->get();
        $course_data = [];
        foreach ($courses as $key => $value) {
            $course_data[] = [
                'id' => $value['course_id'],
                'name' => $value['course_name'],
            ];
        }
        $coupon_course = CouponHasCourse::with('course')->where('coupon_id', $id)->get();
        $coupon_course_data = [];
        foreach ($coupon_course as $key => $value) {
            if (!empty($value['course']['course_id'])) {
                $coupon_course_data[] = [
                    'id' => $value['course']['course_id'],
                ];
            }
        }
        $coupon_course_data = array_column($coupon_course_data, 'id');
        return view('admin.coupon.edit')->with([ 'coupon' => $coupon, 'coupon_course' => $coupon_course_data, 'course' => $course_data]);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return type
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $coupon = Coupon::where('coupon_id', $id)->get()->first();
            $coupon_code = $coupon->coupon_code;
            // validation
            $request->validate([
                'coupon_title' => 'required',
                'course_id' => 'required|exists:tbl_course,course_id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'coupon_type' => 'required|integer|between:0,1',
                    ], [
                'coupon_type.between' => "Select valid coupon type",
            ]);

            $insertData = [
                'coupon_title' => $request->coupon_title,
                'coupon_code' => $request->code,
                'coupon_start_date' => $request->start_date,
                'coupon_end_date' => $request->end_date,
                'coupon_type' => $request->coupon_type,
                'status' => '1',
            ];
            if (isset($request->coupon_type) && $request->coupon_type == '0') {
                $request->validate([
                    'coupon_duration' => 'required|integer|min:1',
                ]);
                $insertData['coupon_duration'] = $request->coupon_duration;
                $insertData['coupon_percentage'] = NULL;
            }

            if (isset($request->coupon_type) && $request->coupon_type == '1') {
                $request->validate([
                    'percentage' => 'required|min:1|max:100',
                ]);
                $insertData['coupon_percentage'] = $request->percentage;
                $insertData['coupon_duration'] = NULL;
            }

            $add_data = Coupon::where('coupon_id', $id)->update($insertData);

            // delete course
            $delete = CouponHasCourse::where('coupon_id', $id)->delete();

            // insert course data
            $insert_coupon_data = [];
            $course = $request['course_id'];
            if (!empty($course)) {
                foreach ($course as $key => $value) {
                    $insert_coupon_data[] = [
                        'coupon_id' => $id,
                        'course_id' => $value,
                        'status' => '1',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_language = CouponHasCourse::insert($insert_coupon_data);
            }

            return ["success" => true, "message" => "Coupon updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);

            Coupon::where('coupon_id', $id)->delete();
            CouponHasCourse::where('coupon_id', $id)->delete();

            return ["success" => true, "message" => "Coupon deleted successfully"];
        }
        abort(404);
    }

    /**
     * Update Course Status
     * @param  \App\Models\Course  $course
     * @param \Illuminate\Http\Request $request
     */
    public function CouponUsedUser(Request $request, getCouponUsedUserDataTable $dataTable) {
        if ($request->ajax()) {

            $validatedData = $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);

            return $dataTable->render('admin.coupon.couponuseduser');
        }
        abort(404);
    }

    /**
     * Update Course Status
     * @param  \App\Models\Course  $course
     * @param \Illuminate\Http\Request $request
     */
    public function coursechangestatus(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'id' => 'required',
            ]);
            $coupon_id = decrypt($request->id);
            $coupondata = Coupon::find($coupon_id);
            $label = "active";
            if ($coupondata->status == 1) {
                $status = '0';
                $label = "inactive ";
            }
            if ($coupondata->status == 0) {
                $status = '1';
            }
            $data = [];
            $data['status'] = $status;
            $coupondata->update($data);
            return ["success" => true, "message" => "Coupon $label successfully."];
        }
        abort(404);
    }

}
