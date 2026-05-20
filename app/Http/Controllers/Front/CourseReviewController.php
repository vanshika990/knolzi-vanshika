<?php

namespace App\Http\Controllers\Front;

use Auth;
use App\Http\Controllers\Controller;
use DataTables;
use App\Models\Course;
use App\Models\CourseQuestion;
use App\Models\CourseHasReview;
use App\DataTables\Front\GetCourseReviewDataTable;
use Illuminate\Http\Request;

class CourseReviewController extends Controller {

    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Get Course all review for Institute/Author
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function index(Request $request, GetCourseReviewDataTable $dataTable) {
        if ($this->user->can('view-course-review')) {
            if ($request->ajax()) {
                $validatedData = $request->validate(['id' => $request->id], [
                    'id' => 'required',
                ]);

                return $dataTable->render('front.coursereview.viewcoursereview');
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Get Course review details for institute and author
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetCourseReviewDetail(Request $request) {
        if ($this->user->can('view-course-review')) {
            if ($request->ajax()) {
                $id = decrypt($request->id);
                $data = CourseHasReview::where('id', $id)->with('user')->first();
                return view('front.coursereview.getcoursereviewdetails')->with(['reviewData' => $data]);
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Edit Course review status for institute and author
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function EditCourseReviewStatus(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $course_review = CourseHasReview::find($id);
            return view('front.coursereview.editcoursereviewstatus')->with([
                        'review_data' => $course_review
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function UpdateReviewStatus(Request $request, GetCourseReviewDataTable $dataTable) {
        if ($request->ajax()) {
            $data = $request->all();
            $review_id = decrypt($request->id);
            $update_review = CourseHasReview::find($review_id);
            $data["status"] = $request->status;
            $update_review->update($data);
            return ["success" => true, "message" => "Status updated successfully"];
        }
        abort(404);
    }

}
