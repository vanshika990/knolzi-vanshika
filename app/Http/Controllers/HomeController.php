<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\CmsPages;
use App\Models\CourseHasReview;
use App\Models\CourseViews;
use App\Models\User;
use App\Models\CourseHasLanguage;
use App\Models\CourseQuestion;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    /**
     * Privacy Policy
     * @return type
     */
    public function getPrivacyPolicy() {
        return view("page.privacy_policy");
    }

    /**
     * Disclaimer
     * @return type
     */
    public function getDisclaimer() {
        return view("page.disclaimer");
    }

    /**
     * Terms and condition
     * @return type
     */
    public function getTerms() {
        return view("page.term_and_condition");
    }

    /**
     * Instructor Details page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function InstructorDetails(Request $request) {
        $user = User::where('author_slug', $request->slug)
                        ->where('status', '1')->first();
        //echo "<pre>"; print_r($user); exit;

        $student = CourseSubscriptionLicence::select('tbl_course_subscription_licence.id')
                        ->leftJoin('tbl_course_has_user', 'tbl_course_subscription_licence.course_id', '=', 'tbl_course_has_user.course_id')
                        ->where('tbl_course_has_user.user_id', $user['id'])->groupBy('tbl_course_subscription_licence.user_id')->get();

        $review = CourseHasReview::select(DB::raw('COUNT(tbl_course_has_review_rate.id) as total_review'))
                        ->leftJoin('tbl_course_has_user', 'tbl_course_has_review_rate.course_id', '=', 'tbl_course_has_user.course_id')
                        ->where('tbl_course_has_user.user_id', $user['id'])->first();

        $cart = getCartData();
        $my_course = Course::select(DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as courseView'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
                        ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                        ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                        ->leftJoin(DB::raw('(SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record
            FROM `tbl_course_has_review_rate` AS r
            GROUP BY r.`course_id`) AS s'), 'tbl_course.course_id', '=', 's.course_id')
                        ->leftJoin(DB::raw('(SELECT v.`course_id`, COUNT(v.`course_id`) AS views
            FROM `tbl_course_views` AS v
            GROUP BY v.`course_id`) AS cv'), 'tbl_course.course_id', '=', 'cv.course_id')
                        ->where('tbl_course.status', "1")
                        ->where('tbl_course.is_delete', "0")
                        ->where('tbl_course_has_user.user_id', $user['id'])
                        ->orderBy('tbl_course.course_id', 'desc')->groupBy('tbl_course_has_user.course_id')->paginate(6);

        $course_count = $my_course->total();
        $subscribe_course = getSubscriptCourse();
        return view("page.instructor_details")->with(['user' => $user, 'student' => $student, 'review' => $review, 'my_course' => $my_course, 'cart' => $cart, 'subscribe_course' => $subscribe_course,'course_count'=>$course_count]);

        abort(404);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        DB::enableQueryLog();
        $data0 = \DB::select('select `tbl_course`.`course_name`, `tbl_course`.`course_code` from `tbl_course_has_user` inner join `tbl_course` on `tbl_course_has_user`.`course_id` = `tbl_course`.`course_id`');
        dd(DB::getQueryLog());
        $data1 = CourseHasUser::join('tbl_course', 'tbl_course_has_user.course_id', '=', 'tbl_course.course_id')->get(['tbl_course.course_name', 'tbl_course.course_code']);
//        dd($data1);
        dd(DB::getQueryLog());
        $data = CourseHasUser::with('course')->get();
//         dd($data);
        dd(DB::getQueryLog());
        dd("gg");
        return view('home');
    }

    public function CourseDetails(Request $request) {
        /* $course = Course::select('tbl_course.*', 'tbl_user.name', 'tbl_user.author_slug')
          ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
          ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
          ->where('tbl_course.slug', $request->slug)
          ->where('tbl_course.status', '1')
          ->first(); */

        $slug = $request->slug;
        $course = DB::select('SELECT GROUP_CONCAT(`tbl_course_has_user`.`user_id` SEPARATOR ",") user_id,Ifnull(`cv`.`views`, 0) AS `courseView`,Ifnull(`s`.`rate`, 0) AS `rate`,
                            Ifnull(`s`.`total_record`, 0) AS `total_record`,`tbl_course`.* FROM  `tbl_course` LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                            LEFT JOIN (SELECT r.`course_id`, Sum(r.`rate`) AS rate, Count("r.*")  AS total_record FROM   `tbl_course_has_review_rate` AS r GROUP  BY r.`course_id`) s ON ( `tbl_course`.`course_id` = s.course_id )
                            LEFT JOIN (SELECT v.`course_id`, Count(v.`course_id`) AS views FROM   `tbl_course_views` AS v GROUP  BY v.`course_id`) cv ON ( `tbl_course`.`course_id` = cv.course_id ) WHERE  `tbl_course`.`slug` = "' . $slug . '"
                            AND `tbl_course`.`status` = "1" LIMIT  1');

        if (!empty($course[0])) {
            $course = $course[0];
            $course_id = $course->course_id;

            $course_review = CourseHasReview::select('user_id', 'review')->with(array('user' => function($query) {
                    $query->select('id', 'name');
                }))->where('course_id', $course_id)->where('status', 1)->paginate(1);
            if ($request->ajax()) {
                $finish = 0;
                if ($course_review->total() == $course_review->currentPage()) {
                    $finish = 1;
                }
                $view = view('page.course_review', compact('course_review'))->render();
                return response()->json(['html' => $view, 'finish' => $finish]);
            }
            $ip = $request->getClientIp();
            $courseview = CourseViews::where([['course_id', '=', $course_id], ['ip', '=', $ip], ['created_at', '>', DB::raw('CURDATE()')]])->count();
            if ($courseview <= 0) {
                CourseViews::create(['course_id' => $course_id, 'ip' => $ip]);
            }
            $rate = 0;
            if ($course->total_record != 0) {
                $rate = $course->rate / $course->total_record;
            }

            $student_view_course = DB::select('SELECT IFNULL(s.rate, 0) AS rate, IFNULL(s.total_record, 0) AS total_record, COUNT("`tbl_course_views`.`course_id") AS views,`tbl_course`.course_id,`tbl_course`.slug,`tbl_course`.course_image,`tbl_course`.course_name,`tbl_course`.course_featured,`tbl_course`.course_price  FROM `tbl_course_views`
                                        LEFT JOIN `tbl_course` ON `tbl_course_views`.`course_id` = `tbl_course`.`course_id` LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM
                                        `tbl_course_has_review_rate` AS r GROUP BY  r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id) where tbl_course.status="1" and tbl_course.course_id != "' . $course_id . '" GROUP BY tbl_course.course_id ORDER BY views DESC LIMIT 10');

            $category = CourseCategory::select('tbl_course_category.id', 'tbl_course_category.name', 'tbl_course_category.slug')
                    ->leftJoin('tbl_course_category', 'tbl_has_course_category.cat_id', '=', 'tbl_course_category.id')
                    ->where('tbl_has_course_category.course_id', $course_id)
                    ->where('tbl_course_category.status', '1')
                    ->orderBy('tbl_course_category.parent_id', 'DESC')
                    ->get();
            $related_category = [];
            if (!empty($category)) {
                $cat_id = [];
                foreach ($category as $row) {
                    $cat_id[] = $row->id;
                }
                $related_category = $this->getSubcategory($cat_id);
            }

            $author_id = $course->user_id;
            $author = DB::select('SELECT
                                    IFNULL(r.rate, 0) AS rate,
                                    IFNULL(r.total_record, 0) AS total_record,
                                    COUNT(
                                      "`tbl_course_views`.`course_id"
                                    ) AS views,
                                    `tbl_user`.`name`,
                                    `tbl_user`.`author_slug`,
                                    `tbl_user`.`profile_image`,
                                    `tbl_user`.`about_me`,
                                    `tbl_user`.`id`
                                  FROM
                                    `tbl_user`
                                    LEFT JOIN `tbl_course_has_user`
                                      ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                                    LEFT JOIN `tbl_course_views`
                                      ON `tbl_course_has_user`.`course_id` = `tbl_course_views`.`course_id`
                                    LEFT JOIN
                                      (SELECT
                                        r.`course_id`,
                                        SUM(r.`rate`) AS rate,
                                        COUNT("r.*") AS total_record
                                      FROM
                                        `tbl_course_has_review_rate` AS r
                                      GROUP BY r.`course_id`) r
                                      ON (
                                        `tbl_course_has_user`.`course_id` = r.course_id
                                      )
                                  WHERE `tbl_user`.`id` IN (' . $author_id . ')
                                    AND `tbl_user`.`status` = "1"
                                  GROUP BY `tbl_course_has_user`.`course_id`');
            $author_data = [];
            if (!empty($author)) {
                $u_id = 0;
                $rate_a = 0;
                $rate_v = 0;
                $total_record = 0;
                foreach ($author as $row) {
                    $rate_a+=$row->rate;
                    $rate_v+=$row->views;
                    $total_record+=$row->total_record;
                    $author_data[$row->id]['rate'] = $rate_a;
                    $author_data[$row->id]['views'] = $rate_v;
                    $author_data[$row->id]['total_record'] = $total_record;
                    $author_data[$row->id]['name'] = $row->name;
                    $author_data[$row->id]['author_slug'] = $row->author_slug;
                    $author_data[$row->id]['profile_image'] = $row->profile_image;
                    $author_data[$row->id]['about_me'] = $row->about_me;
                }
            }
            $language = CourseHasLanguage::select('tbl_language.name')
                    ->leftJoin('tbl_language', 'tbl_course_has_language.language_id', '=', 'tbl_language.id')
                    ->where('tbl_course_has_language.course_id', $course_id)
                    ->get();
//            session()->put('cart', []);
            $section_data = new \App\Helper\GetOptionDataHelper();
            $slogan_section = $section_data->getOptionData(['homepage_slogan_section']);
            $wishlist = $this->getWhishlistCourse();

            $course_content = $this->getToctext($course_id);
            $subscribe_course = getSubscriptCourse();
            $where = '';
            if (!empty($subscribe_course)) {
                $where = 'AND tbl_has_related_course.course_id NOT IN(' . implode(',', $subscribe_course) . ')';
            }
            $bundle_course = DB::select('SELECT  IFNULL(s.rate, 0) AS rate,IFNULL(s.total_record, 0) AS total_record,COUNT("`tbl_course_views`.`course_id") AS views,`tbl_course`.course_id,`tbl_course`.slug,`tbl_course`.course_image,
                        `tbl_course`.course_name,`tbl_course`.course_featured,`tbl_course`.course_price FROM `tbl_course_views` LEFT JOIN `tbl_course` ON `tbl_course_views`.`course_id` = `tbl_course`.`course_id` LEFT JOIN `tbl_has_related_course` ON `tbl_has_related_course`.`related_course_id` = `tbl_course`.`course_id`
                        LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate,COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r GROUP BY r.`course_id`) s
                          ON (`tbl_course`.`course_id` = s.course_id) WHERE tbl_course.status = "1" AND tbl_has_related_course.course_id = ' . $course_id . ' ' . $where . ' GROUP BY tbl_course.course_id');
            $cart = getCartData();
            return view("page.course_details")->with(['cart' => $cart, 'course' => (array) $course, 'languages' => $language, 'authors' => $author_data, 'student_view_course' => $student_view_course, 'categories' => $category, 'rate' => $rate, 'related_category' => $related_category, 'slogan_section' => $slogan_section, 'course_review' => $course_review, 'wishlist' => $wishlist, 'bundle_course' => $bundle_course, 'course_content' => $course_content, 'subscribe_course' => $subscribe_course]);
        }
        abort(404);
    }

    public function getWhishlistCourse() {
        $wishlist = [];
        if (Auth::check()) {
            $wishlist = Wishlist::select('course_id')->where('user_id', auth()->user()->id)->get()->pluck('course_id')->toArray();
        }
        return $wishlist;
    }

    /**
     * Get Sub Category
     * @param type $id
     * @return type
     */
    public function getSubcategory($id = []) {
        $category = CourseCategory::select('tbl_course_category.name', 'tbl_course_category.slug')
                ->leftJoin('tbl_course_category', 'tbl_has_course_category.cat_id', '=', 'tbl_course_category.id')
                ->whereIn('tbl_course_category.parent_id', $id)
                ->where('tbl_course_category.status', '1')
                ->orderBy('tbl_course_category.id', 'DESC')
                ->groupBy('tbl_course_category.id')
                ->get();
        return $category;
    }

    /**
     * Get Courese Review
     * @param \Illuminate\Http\Request $request
     */
    public function getCourseReview(Request $request) {
        $course_review = CourseHasReview::select('user_id', 'review')->with('user')->where('course_id', $course_id)->paginate(1);
    }

    /**
     * Get Toc/Content in course
     * @param type $course_id
     * @return type
     */
    public function getToctext($course_id) {
        $get_parent_data = CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('course_id', $course_id)->get()->toArray();

        $parent_array = [];
        $main_parent_array = [];
        foreach ($get_parent_data as $key => $value) {
            $toc_no_array = explode('.', $value['que_toc_no']);
            $toc_parent_no = $toc_no_array[0];
            $toc_child_no = $toc_no_array[1];

            if ($toc_child_no == 0) {
                $parent_array['id'] = $value['id'];
                $parent_array['que_toc_text'] = $value['que_toc_text'];
                $parent_array['que_toc_no'] = $value['que_toc_no'];
                $get_child_data = $this->getChildTOC($toc_parent_no);
                $child_array = [];
                if ($get_child_data != '') {
                    foreach ($get_child_data as $key => $child_value) {
                        if ($value['id'] != $child_value['id']) {
                            $child_array[] = $child_value;
                            $parent_array['child_array'] = $child_array;
                        }
                    }
                }
                $main_parent_array[] = $parent_array;
            }
        }
        return $main_parent_array;
    }

    /**
     * Get Child TOC Text
     * @param type $toc_parent_no
     * @return type
     */
    public function getChildTOC($toc_parent_no) {
        $toc_parent_no;
        $next_toc_no = $toc_parent_no + 1;
        return CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('que_toc_no', '>', $toc_parent_no)->where('que_toc_no', '<', $next_toc_no)->get()->toArray();
    }

}
