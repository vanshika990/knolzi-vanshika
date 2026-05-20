<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseQuestion;

class DashboardController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $total_organization = User::role('organization')->whereIn('status', ['1', '2'])->count();
        $total_individual = User::role('individual')->whereIn('status', ['1', '2'])->count();
        $total_course = Course::where(['is_delete' => '0'])->count();
        $total_question = CourseQuestion::where('is_delete', '0')->count();

        return view('admin.dashboard.index')->with(['total_organization' => $total_organization, 'total_individual' => $total_individual, 'total_course' => $total_course, 'total_question' => $total_question]);
    }

}
