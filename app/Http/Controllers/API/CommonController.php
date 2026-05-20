<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Cart;
use App\Models\Category;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\CourseHasReview;
use App\Models\UserHasOrganization;
use App\Models\CourseViews;
use App\Models\CourseHasLanguage;
use App\Models\CourseQuestion;
use App\Models\Wishlist;
use App\Models\ReviewerCourse;
use App\Models\ReviewerUserCourseAttempt;
use Stevebauman\Location\Facades\Location;

class CommonController extends BaseController {

    public $paginationlimit = 10;

    /**
     * guest user page api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getGuestUserHomepagePage(Request $request) {
        $section_data = new \App\Helper\GetOptionDataHelper();
        $page_all_data = [];
        $cat_aar = [];

        $data = $section_data->getOptionData(['homepage_hero_section', 'homepage_slogan_section', 'homepage_sell_course_online_section', 'homepage_digital_classroom_section', 'homepage_blog_section']);
        $hero_sec_data = (array) $data['homepage_hero_section'];

        // hero section data
        if (!empty($hero_sec_data)) {
            $page_all_data['hero_image_title'] = "";
            $page_all_data['hero_image_btn_url'] = "";
            $page_all_data['hero_image_description'] = "";
            $page_all_data['hero_image_image'] = "";

            if (!empty($hero_sec_data['hero_sec_title'])) {
                $page_all_data['hero_image_title'] = strip_tags($hero_sec_data['hero_sec_title']);
            }
            if (!empty($hero_sec_data['hero_sec_btn_url'])) {
                $page_all_data['hero_image_btn_url'] = strip_tags($hero_sec_data['hero_sec_btn_url']);
            }
            if (!empty($hero_sec_data['hero_sec_description'])) {
                $page_all_data['hero_image_description'] = strip_tags($hero_sec_data['hero_sec_description']);
            }
            if (!empty($hero_sec_data['hero_sec_image'])) {
                $page_all_data['hero_image_image'] = $hero_sec_data['hero_sec_image'];
            } else {
                $page_all_data['hero_image_image'] = "https://edupmquestionhelp.s3.ap-south-1.amazonaws.com/tmp/images/16296932921629693292.jpg";
            }
        }

        // category and course section data
        $category_id = implode(',', $hero_sec_data['hero_broad_selection_course']);
        $category = DB::select("SELECT IFNULL(`cv`.`views`, 0) AS `courseView`, IFNULL(`s`.`rate`, 0) AS `rates`, IFNULL(`s`.`total_record`, 0) AS `total_record`,`tbl_course_category`.`id` AS `course_category_id`,`tbl_course_category`.`category_sub_description`,`tbl_course_category`.`slug`,`tbl_course_category`.`category_description`,`tbl_course_category`.`name` AS `cat_name`,`tbl_course`.`slug` AS `course_slug`,`tbl_course`.`course_name`,`tbl_course`.`created_at`,`tbl_course`.`course_id`,`tbl_course`.`course_sub_description`,`tbl_course`.`course_image`,`tbl_course`.`course_tag`,`tbl_course`.`course_price`,`tbl_course`.`course_sub_description`,`tbl_course`.`course_applications`, GROUP_CONCAT(tbl_user.name ORDER BY tbl_user.id SEPARATOR ', ') author_name 
                                FROM `tbl_has_course_category` LEFT JOIN `tbl_course_category` ON `tbl_has_course_category`.`cat_id` = `tbl_course_category`.`id` LEFT JOIN `tbl_course` 
                                ON `tbl_has_course_category`.`course_id` = `tbl_course`.`course_id` LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id` 
                                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id` LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate, COUNT('r.*') AS total_record 
                                FROM `tbl_course_has_review_rate` AS r where status='1' GROUP BY r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id) LEFT JOIN (SELECT v.`course_id`,COUNT(v.`course_id`) AS views
                                FROM `tbl_course_views` AS v GROUP BY v.`course_id`) cv ON (`tbl_course`.`course_id` = cv.course_id) WHERE `tbl_has_course_category`.`cat_id` IN ($category_id) 
                                AND `tbl_course`.`status` = '1' GROUP BY `tbl_has_course_category`.`id`");

        $page_all_data['categories'] = [];
        $cat_data = [];
        if (!empty($category)) {
            foreach ($category as $row) {
                $cat_data[$row->cat_name]['category']['category_id'] = $row->course_category_id;
                $cat_data[$row->cat_name]['category']['category_sub_description'] = $row->category_sub_description;
                $cat_data[$row->cat_name]['category']['category_description'] = $row->category_description;
                $cat_data[$row->cat_name]['courses'][] = (array) $row;
            }
            if (!empty($cat_data)) {
                $i = 0;
                foreach ($cat_data as $k => $row) {
                    if (!empty($row['courses'])) {
                        foreach ($row['courses'] as $key => $sub_row) {
                            $sub_row['course_price'] = (string) currencyConvert($sub_row['course_price']);
                            $sub_row['symbol'] = getCurrencySymbol();
                            $row['courses'][$key] = $sub_row;
                        }
                    }
                    $page_all_data['categories'][$i] = $row['category'];
                    $page_all_data['categories'][$i]['name'] = $k;
                    $page_all_data['categories'][$i]['courses'] = $row['courses'];
                    $i++;
                }
            }
        }

        $page_all_data['student_view_course'] = [];
        $student_view_course = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ", ") author_name,IFNULL(`cv`.`views`, 0) AS `views`, IFNULL(`s`.`rate`, 0) AS `rate`,IFNULL(`s`.`total_record`, 0) AS `total_record`,`tbl_course`.* FROM `tbl_course` 
                        LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id` 
                        LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id` 
                        LEFT JOIN (SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r where status="1" GROUP BY r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id) 
                        LEFT JOIN (SELECT v.`course_id`, COUNT(v.`course_id`) AS views FROM `tbl_course_views` AS v GROUP BY v.`course_id`) cv ON (`tbl_course`.`course_id` = cv.course_id) 
                        WHERE `tbl_course`.`status` = "1" GROUP BY tbl_course.course_id ORDER BY views DESC LIMIT 10 ');

        if (!empty($student_view_course)) {
            foreach ($student_view_course as $key => $row) {
                $row->course_price = (string) currencyConvert($row->course_price);
                $row->symbol = getCurrencySymbol();
                $student_view_course[$key] = $row;
            }
            $page_all_data['student_view_course'] = $student_view_course;
        }

        // top category section data
        $page_all_data['top_category'] = [];
        $crs_id = array_column($student_view_course, 'course_id');
        $crs_cat_arr = CourseCategory::select('cat_id', 'name')->leftjoin('tbl_course_category', 'tbl_course_category.id', '=', 'tbl_has_course_category.cat_id')->whereIn('course_id', $crs_id)->groupby('cat_id')->limit(10)->get()->toArray();

        $page_all_data['top_category'] = $crs_cat_arr;

        return $this->sendResponse($page_all_data, 'Success.');
    }

    /**
     * After LoginHomepage api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getUserLoginWithHomepagePage(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:tbl_user,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $section_data = new \App\Helper\GetOptionDataHelper();
        $page_all_data = [];
        $cat_aar = [];

        /* use detail data* */
        /* $user_data = User::select(['id', 'name', 'email', 'profile_image'])->where('id', $user_id)->first();
          $page_all_data['user_detail']['name'] = $user_data->name;
          $page_all_data['user_detail']['email'] = $user_data->email;
          $page_all_data['user_detail']['profile_image'] = $user_data->profile_image; */

        $data = $section_data->getOptionData(['homepage_hero_section']);
        $hero_sec_data = (array) $data['homepage_hero_section'];

        /* hoero section data */
        $page_all_data['hero_image_title'] = "";
        $page_all_data['hero_image_btn_url'] = "";
        $page_all_data['hero_image_description'] = "";
        $page_all_data['hero_image_image'] = "";
        if (!empty($hero_sec_data)) {
            if (!empty($hero_sec_data['hero_sec_title'])) {
                $page_all_data['hero_image_title'] = strip_tags($hero_sec_data['hero_sec_title']);
            }
            if (!empty($hero_sec_data['hero_sec_btn_url'])) {
                $page_all_data['hero_image_btn_url'] = strip_tags($hero_sec_data['hero_sec_btn_url']);
            }
            if (!empty($hero_sec_data['hero_sec_description'])) {
                $page_all_data['hero_image_description'] = strip_tags($hero_sec_data['hero_sec_description']);
            }
            if (!empty($hero_sec_data['hero_sec_image'])) {
                $page_all_data['hero_image_image'] = $hero_sec_data['hero_sec_image'];
            } else {
                $page_all_data['hero_image_image'] = "https://edupmquestionhelp.s3.ap-south-1.amazonaws.com/tmp/images/16296932921629693292.jpg";
            }
        }

        /* $cart = Cart::select('course_id')->where('user_id', $user_id)->get();
          $cart_data = [];
          if (!empty($cart)) {
          foreach ($cart as $row) {
          $cart_data[$row->course_id] = $row->course_id;
          }
          }
          $page_all_data['cart'] = $cart_data; */


        /* loggedIn user's org Id */
        $id = [];
        $id[0] = $user_id;
        $org = UserHasOrganization::select('org_id')
                ->where('user_id', $user_id)
                ->first();
        if (!empty($org)) {
            $org_id = $org->org_id;
            $id[1] = $org_id;
        }

        /* LoggedIn user's subscribed course for i Learn section */
        $query = CourseSubscription::select('tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_sub_description', 'tbl_course.course_image', 'tbl_course.course_featured', 'tbl_course.course_tag', 'tbl_course.course_price')
                ->leftJoin('tbl_course', 'tbl_course_subscription.course_id', '=', 'tbl_course.course_id')
                ->where('tbl_course.status', '1')
                ->whereIn('tbl_course_subscription.user_id', $id)
                ->where('tbl_course_subscription.status', '1')
                ->where('tbl_course_subscription.sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                ->groupBy('tbl_course.course_id');

        if (!Auth::user()->hasRole('organization')) {
            $query->leftJoin('tbl_course_subscription_licence', 'tbl_course_subscription.id', '=', 'tbl_course_subscription_licence.course_subscription_id');
            $query->where('tbl_course_subscription_licence.status', '1');
            $query->where('tbl_course_subscription_licence.user_id', $user_id);
        }
        $sub = $query->get()->toArray();
        if (!empty($sub)) {
            foreach ($sub as $k => $row) {
                $row['course_price'] = (string) currencyConvert($row['course_price']);
                $row['symbol'] = getCurrencySymbol();
                $sub[$k] = $row;
            }
        }
        $page_all_data['subscribed_course'] = $sub;
        $course_id = array_column($sub, 'course_id');

        // parent category data for Category section
        $parent_cat_arr = CourseCategory::select('cat_id as id', 'name')->leftjoin('tbl_course_category', 'tbl_course_category.id', '=', 'tbl_has_course_category.cat_id')->whereIn('course_id', $course_id)->groupby('cat_id')->limit(10)->get()->toArray();
        $page_all_data['top_first_cat'] = $parent_cat_arr;

        $parent_cat = array_unique(array_column($parent_cat_arr, 'id'));

        // child category data for recommended course section category 
        $child_cat = Category::select('id', 'name')->whereIn('parent_id', $parent_cat)->limit(10)->get()->toArray();
        $child_cat_id = array_column($child_cat, 'id');
        $page_all_data['recommended_cat'] = $child_cat;

        // Recommended courses for loggedIn User  
        $recommended_course = Course::select(DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as courseView'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_sub_description', 'tbl_course.course_image', 'tbl_course.course_featured', 'tbl_course.course_tag', 'tbl_course.course_price')
                        ->leftJoin('tbl_has_related_course', 'tbl_has_related_course.related_course_id', '=', 'tbl_course.course_id')
                        ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                        ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                        ->leftJoin(DB::raw('(SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record 
            FROM `tbl_course_has_review_rate` AS r where status="1" 
            GROUP BY r.`course_id`) AS s'), 'tbl_course.course_id', '=', 's.course_id')
                        ->leftJoin(DB::raw('(SELECT v.`course_id`, COUNT(v.`course_id`) AS views 
            FROM `tbl_course_views` AS v 
            GROUP BY v.`course_id`) AS cv'), 'tbl_course.course_id', '=', 'cv.course_id')
                        ->where('tbl_course.status', "1")
                        ->where('tbl_course.is_delete', "0")
                        ->whereIn('tbl_has_related_course.course_id', $course_id)
                        ->whereNotIn('tbl_has_related_course.related_course_id', $course_id)
                        ->orderBy('tbl_course.course_id', 'desc')->groupBy('tbl_has_related_course.related_course_id')->limit(10)->get()->toArray();
        if (!empty($recommended_course)) {
            foreach ($recommended_course as $k => $row) {
                $row['course_price'] = (string) currencyConvert($row['course_price']);
                $row['symbol'] = getCurrencySymbol();
                $recommended_course[$k] = $row;
            }
        }
        $page_all_data['recommended_course'] = $recommended_course;

        $next_line = Course::select(DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as courseView'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_sub_description', 'tbl_course.course_image', 'tbl_course.course_featured', 'tbl_course.course_tag', 'tbl_course.course_price')
                        ->leftJoin('tbl_has_course_category', 'tbl_has_course_category.course_id', '=', 'tbl_course.course_id')
                        ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                        ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                        ->leftJoin(DB::raw('(SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record 
                FROM `tbl_course_has_review_rate` AS r where status="1" 
                GROUP BY r.`course_id`) AS s'), 'tbl_course.course_id', '=', 's.course_id')
                        ->leftJoin(DB::raw('(SELECT v.`course_id`, COUNT(v.`course_id`) AS views 
                FROM `tbl_course_views` AS v 
                GROUP BY v.`course_id`) AS cv'), 'tbl_course.course_id', '=', 'cv.course_id')
                        ->where('tbl_course.status', "1")
                        ->whereIn('tbl_has_course_category.cat_id', $child_cat_id)
                        ->whereNotIn('tbl_course.course_id', $course_id)
                        ->orderBy('tbl_course.course_id', 'desc')->groupBy('tbl_has_course_category.course_id')->limit(10)->get()->toArray();
        if (!empty($next_line)) {
            foreach ($next_line as $k => $row) {
                $row['course_price'] = (string) currencyConvert($row['course_price']);
                $row['symbol'] = getCurrencySymbol();
                $next_line[$k] = $row;
            }
        }
        $page_all_data['next_line'] = $next_line;

        /* student view section data for both page */
        $student_view_course = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ", ") author_name,IFNULL(`cv`.`views`, 0) AS `views`, IFNULL(`s`.`rate`, 0) AS `rate`,IFNULL(`s`.`total_record`, 0) AS `total_record`,tbl_course.course_id,tbl_course.course_name,tbl_course.course_sub_description,tbl_course.course_image,tbl_course.course_featured,tbl_course.course_tag,tbl_course.course_price FROM `tbl_course` 
                        LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id` 
                        LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id` 
                        LEFT JOIN (SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r where status="1" GROUP BY r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id) 
                        LEFT JOIN (SELECT v.`course_id`, COUNT(v.`course_id`) AS views FROM `tbl_course_views` AS v GROUP BY v.`course_id`) cv ON (`tbl_course`.`course_id` = cv.course_id) 
                        WHERE `tbl_course`.`status` = "1" GROUP BY tbl_course.course_id ORDER BY views DESC LIMIT 10');

        if (!empty($student_view_course)) {
            foreach ($student_view_course as $k => $row) {
                $row->course_price = (string) currencyConvert($row->course_price);
                $row->symbol = getCurrencySymbol();
                $student_view_course[$k] = $row;
            }
        }
        $page_all_data['student_view_course'] = $student_view_course;
        return $this->sendResponse($page_all_data, 'Success.');
    }

    /**
     * course category page api
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getCourseCategory(Request $request) {
        $validator = Validator::make($request->all(), [
                    'category_id' => 'required|exists:tbl_course_category,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $cat_id = $request->category_id;
        $user_id = $request->user_id;

        $section_data = new \App\Helper\GetOptionDataHelper();
        $data = $section_data->getOptionData(['homepage_hero_section']);
        $page_all_data = [];

        $hero_sec_data = $data['homepage_hero_section'];

        $page_all_data['hero_sec_title'] = '';
        $page_all_data['hero_sec_description'] = '';
        $page_all_data['hero_sec_image'] = '';

        if (!empty($hero_sec_data['hero_sec_title'])) {
            $page_all_data['hero_sec_title'] = strip_tags($hero_sec_data['hero_sec_title']);
        }

        if (!empty($hero_sec_data['hero_sec_description'])) {
            $page_all_data['hero_sec_description'] = strip_tags($hero_sec_data['hero_sec_description']);
        }

        if (!empty($hero_sec_data['hero_sec_image'])) {
            $page_all_data['hero_sec_image'] = $hero_sec_data['hero_sec_image'];
        }

        $category = Category::select('id', 'name', 'category_sub_description')->where('id', $cat_id)->first();
        $page_all_data['cat_name'] = $category->name;
        $page_all_data['category_sub_description'] = $category->category_sub_description;

        $all_course = Course::select('tbl_has_course_category.cat_id', DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as views'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
                ->leftjoin('tbl_has_course_category', 'tbl_course.course_id', '=', 'tbl_has_course_category.course_id')
                ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                ->leftJoin(DB::raw('(SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record 
                FROM `tbl_course_has_review_rate` AS r where status="1" 
                GROUP BY r.`course_id`) AS s'), 'tbl_course.course_id', '=', 's.course_id')
                ->leftJoin(DB::raw('(SELECT v.`course_id`, COUNT(v.`course_id`) AS views 
                FROM `tbl_course_views` AS v 
                GROUP BY v.`course_id`) AS cv'), 'tbl_course.course_id', '=', 'cv.course_id')
                ->where('tbl_has_course_category.cat_id', $category->id)
                ->where('tbl_course.status', "1")
                ->where('tbl_course.is_delete', "0")
                ->groupBy('tbl_course.course_id')
                ->paginate(8);
        if (!empty($all_course)) {
            for ($i = 0; $i < count($all_course); $i++) {
                $all_course[$i]->course_price = (string) currencyConvert($all_course[$i]->course_price);
                $all_course[$i]->symbol = getCurrencySymbol();
            }
        }
        $page_all_data['all_course'] = $all_course;

        $cart = Cart::select('course_id')->where('user_id', $user_id)->get();
        $cart_data = [];
        if (!empty($cart)) {
            foreach ($cart as $row) {
                $cart_data[$row->course_id] = $row->course_id;
            }
        }
        $page_all_data['cart'] = $cart_data;

        $company_data = User::select('tbl_user_has_org.org_id')->leftJoin('tbl_user_has_org', 'tbl_user.id', '=', 'tbl_user_has_org.user_id')->where('tbl_user_has_org.user_id', $user_id)->first();
        $company_id = [$user_id];
        if (!empty($company_data)) {
            array_push($company_id, $company_data['org_id']);
        }
        $subscribe_course = CourseSubscription::leftJoin('tbl_course', 'tbl_course_subscription.course_id', '=', 'tbl_course.course_id')
                        ->leftJoin('tbl_course_subscription_licence', 'tbl_course_subscription.id', '=', 'tbl_course_subscription_licence.course_subscription_id')
                        ->where('tbl_course.status', '1')
                        ->whereIn('tbl_course_subscription.user_id', $company_id)
                        ->where('tbl_course_subscription_licence.user_id', $user_id)
                        ->where('tbl_course_subscription.status', '1')
                        ->where('tbl_course_subscription.sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                        ->groupBy('tbl_course.course_id')
                        ->select('tbl_course.course_id')->pluck('tbl_course.course_id')->toArray();

        $page_all_data['sub_course'] = $subscribe_course;

        return $this->sendResponse($page_all_data, 'Success.');
    }

    /**
     * Course Details
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getCourseDetails(Request $request) {
        $validator = Validator::make($request->all(), [
                    'course_id' => 'required|exists:tbl_course,course_id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $data = [];
        $course_id = $request->course_id;
        $course_review = CourseHasReview::select('user_id', 'review')->with(array('user' => function($query) {
                $query->select('id', 'name');
            }))->where('course_id', $course_id)->where('status', '1')->paginate(10);
        $data['course_review'] = $course_review;
        if ($course_review->currentPage() != 1) {
            return $this->sendResponse((array) $data, 'Success.');
        }
        $is_cart_added_course = 0;
        $is_subscribe_course = 0;
        $is_wishlist_added_course = 0;
        $wishlist_id = 0;
        if ($request->has('user_id')) {
            $validator = Validator::make($request->all(), [
                        'user_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $user_id = $request->user_id;
            $cart = Cart::where(['user_id' => $user_id, 'course_id' => $course_id])->count();
            if ($cart > 0) {
                $is_cart_added_course = 1;
            }
            $company_id = [$user_id];
            if ($request->has('company_id')) {
                array_push($company_id, $request->company_id);
            }
            $query = CourseSubscription::leftJoin('tbl_course', 'tbl_course_subscription.course_id', '=', 'tbl_course.course_id')
                    ->where('tbl_course.status', '1')
                    ->whereIn('tbl_course_subscription.user_id', $company_id)
                    ->where('tbl_course_subscription.status', '1')
                    ->where('tbl_course.course_id', $course_id)
                    ->where('tbl_course_subscription.sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                    ->groupBy('tbl_course.course_id');
            if ($request->role_id != 3) {
                $query->leftJoin('tbl_course_subscription_licence', 'tbl_course_subscription.id', '=', 'tbl_course_subscription_licence.course_subscription_id');
                $query->where('tbl_course_subscription_licence.status', '1');
                $query->where('tbl_course_subscription_licence.user_id', $user_id);
            }
            $subscribe_course = $query->select('tbl_course.course_id')->count();
            if ($subscribe_course > 0) {
                $is_subscribe_course = 1;
            }

            $wishlist = Wishlist::where(['user_id' => $user_id, 'course_id' => $course_id])->first();
            if (!empty($wishlist)) {
                $is_wishlist_added_course = 1;
                $wishlist_id = $wishlist->id;
            }
        }

        $course = DB::select('SELECT GROUP_CONCAT(`tbl_course_has_user`.`user_id` SEPARATOR ",") user_id,Ifnull(`cv`.`views`, 0) AS `courseView`,Ifnull(`s`.`rate`, 0) AS `rate`,
                            Ifnull(`s`.`total_record`, 0) AS `total_record`,`tbl_course`.* FROM  `tbl_course` LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                            LEFT JOIN (SELECT r.`course_id`, Sum(r.`rate`) AS rate, Count("r.*")  AS total_record FROM `tbl_course_has_review_rate` AS r where status="1" GROUP  BY r.`course_id`) s ON ( `tbl_course`.`course_id` = s.course_id )
                            LEFT JOIN (SELECT v.`course_id`, Count(v.`course_id`) AS views FROM   `tbl_course_views` AS v GROUP  BY v.`course_id`) cv ON ( `tbl_course`.`course_id` = cv.course_id ) WHERE  `tbl_course`.`course_id` = "' . $course_id . '"
                            AND `tbl_course`.`status` = "1" LIMIT  1');

        $course = $course[0];
        $ip = $request->getClientIp();
        $courseview = CourseViews::where([['course_id', '=', $course_id], ['ip', '=', $ip], ['created_at', '>', DB::raw('CURDATE()')]])->count();
        if ($courseview <= 0) {
            CourseViews::create(['course_id' => $course_id, 'ip' => $ip]);
        }
        $rate = 0;
        if ($course->total_record != 0) {
            $rate = $course->rate / $course->total_record;
            unset($course->total_record);
        }
        $course->rate = number_format((float) $rate, 1, '.', '');
        $course->course_price = (string) currencyConvert($course->course_price);
        $course->symbol = getCurrencySymbol();
        $author_id = $course->user_id;
        if (!empty($author_id)) {
            $author = DB::select('SELECT IFNULL(r.rate, 0) AS rate,IFNULL(r.total_record, 0) AS total_record,COUNT("`tbl_course_views`.`course_id") AS views,`tbl_user`.`name`,
                                    `tbl_user`.`author_slug`,`tbl_user`.`profile_image`,`tbl_user`.`about_me`,`tbl_user`.`id` FROM `tbl_user` LEFT JOIN `tbl_course_has_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id` LEFT JOIN `tbl_course_views` 
                                      ON `tbl_course_has_user`.`course_id` = `tbl_course_views`.`course_id` LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate,COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r where status="1" 
                                      GROUP BY r.`course_id`) r ON (`tbl_course_has_user`.`course_id` = r.course_id) WHERE `tbl_user`.`id` IN (' . $author_id . ') AND `tbl_user`.`status` = "1" GROUP BY `tbl_course_has_user`.`id`');
        }
        $author_data = [];
        $authors = [];
        if (!empty($author)) {
            $u_id = 0;
            $rate_a = 0;
            $rate_v = 0;
            $total_record = 0;
            foreach ($author as $key => $row) {
                $rate_a = (isset($author_data[$row->id]['rate'])) ? $row->rate + $author_data[$row->id]['rate'] : $row->rate;
                $rate_v = (isset($author_data[$row->id]['views'])) ? $row->views + $author_data[$row->id]['views'] : $row->views;
                $total_record = (isset($author_data[$row->id]['total_record'])) ? $row->total_record + $author_data[$row->id]['total_record'] : $row->total_record;
                $author_data[$row->id]['rate'] = $rate_a;
                $author_data[$row->id]['views'] = $rate_v;
                $author_data[$row->id]['total_record'] = $total_record;
                $author_data[$row->id]['name'] = $row->name;
                $author_data[$row->id]['author_slug'] = $row->author_slug;
                $author_data[$row->id]['profile_image'] = $row->profile_image;
                $author_data[$row->id]['about_me'] = $row->about_me;
            }
            if (!empty($author_data)) {
                foreach ($author_data as $row_a) {
                    $authors[] = $row_a;
                }
            }
        }

        $language = CourseHasLanguage::select('tbl_language.name')
                ->leftJoin('tbl_language', 'tbl_course_has_language.language_id', '=', 'tbl_language.id')
                ->where('tbl_course_has_language.course_id', $course_id)
                ->get();
        $course_content = $this->getToctext($course_id);
        $student_view_course = DB::select('SELECT IFNULL(s.rate, 0) AS rate, IFNULL(s.total_record, 0) AS total_record, COUNT("`tbl_course_views`.`course_id") AS views,`tbl_course`.course_id,`tbl_course`.slug,`tbl_course`.course_image,`tbl_course`.course_name,`tbl_course`.course_featured,`tbl_course`.course_price  FROM `tbl_course_views` 
                                        LEFT JOIN `tbl_course` ON `tbl_course_views`.`course_id` = `tbl_course`.`course_id` LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM
                                        `tbl_course_has_review_rate` AS r where status="1" GROUP BY  r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id) where tbl_course.status="1" and tbl_course.course_id != "' . $course_id . '" GROUP BY tbl_course.course_id ORDER BY views DESC LIMIT 10');
        if (!empty($student_view_course)) {
            foreach ($student_view_course as $k => $row) {
                $row->course_price = (string) currencyConvert($row->course_price);
                $row->symbol = getCurrencySymbol();
                $student_view_course[$k] = $row;
            }
        }
        $category = CourseCategory::select('tbl_course_category.id', 'tbl_course_category.name')
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

        $data['course'] = $course;
        $data['course_category'] = $category;
        $data['related_category'] = $related_category;
        $data['author_data'] = $authors;
        $data['language'] = $language;
        $data['course_content'] = $course_content;
        $data['student_view_course'] = $student_view_course;
        $data['is_cart_added_course'] = $is_cart_added_course;
        $data['is_subscribe_course'] = $is_subscribe_course;
        $data['is_wishlist_added_course'] = $is_wishlist_added_course;
        $data['wishlist_id'] = $wishlist_id;
        return $this->sendResponse((array) $data, 'Success.');
    }

    /**
     * Get Sub Category
     * @param type $id
     * @return type
     */
    public function getSubcategory($id = []) {
        $category = CourseCategory::select('tbl_course_category.name', 'tbl_course_category.id')
                ->leftJoin('tbl_course_category', 'tbl_has_course_category.cat_id', '=', 'tbl_course_category.id')
                ->whereIn('tbl_course_category.parent_id', $id)
                ->where('tbl_course_category.status', '1')
                ->orderBy('tbl_course_category.id', 'DESC')
                ->groupBy('tbl_course_category.id')
                ->get();
        return $category;
    }

    /**
     * Get Toc/Content in course
     * @param type $course_id
     * @return type
     */
    public function getToctext($course_id) {
        $get_parent_data = CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('course_id', $course_id)->get()->toArray();
        $all_data = [];
        if (!empty($get_parent_data)) {
            $toc_no = [];
            foreach ($get_parent_data as $key => $value) {
                $toc_no[] = $value['que_toc_no'];
            }

            $res = [];
//            array_walk($toc_no, function($item) use ( &$res ) {
//                if (strpos($item, '.') !== false) {
//                    $key = substr($item, 0, 1);
//                } else {
//                    $key = $item;
//                }
//                if (isset($res[$key]))
//                    $res[$key][] = $item;
//                else
//                    $res[$key] = [$item];
//            });

            array_walk($toc_no, function($item) use ( &$res ) {
                if (strpos($item, '.') !== false) {
                    $str_arr = explode('.', $item);
                    if (!empty($str_arr)) {
                        $key = $str_arr[0];
                    }
                } else {
                    $key = $item;
                }

                if (isset($res[$key])) {
                    $res[$key][] = $item;
                } else {
                    $res[$key] = [$item];
                }
            });

            if (!empty($res)) {
                ksort($res);
                foreach ($res as $k => $row) {
                    $i = 0;
                    foreach ($get_parent_data as $key => $value) {
                        if (in_array($value['que_toc_no'], $res[$k]) && !empty($value['que_toc_text'])) {
                            if ($i == 0) {
                                $all_data[$k] = $value;
                            } else {
                                $all_data[$k]['child'][] = $value;
                            }
                            $i++;
                        }
                    }
                }
            }
        }
        return array_values($all_data);
    }

    /**
     * Get Toc/Content in course
     * @param type $course_id
     * @return type
     */
    public function getToctexts($course_id) {
        $get_parent_data = CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('course_id', $course_id)->get()->toArray();

        $parent_array = [];
        $main_parent_array = [];
        foreach ($get_parent_data as $key => $value) {
            $toc_no_array = explode('.', $value['que_toc_no']);
            $toc_parent_no = $toc_no_array[0];
            $toc_child_no = "";
            if (isset($toc_no_array[1])) {
                $toc_child_no = $toc_no_array[1];
            }
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
        $next_toc_no = (int) $toc_parent_no + 1;
        return CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('que_toc_no', '>', $toc_parent_no)->where('que_toc_no', '<', $next_toc_no)->get()->toArray();
    }

    /**
     * Course Category
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getCourseCatagory(Request $request) {
        $categories = Category::select('id', 'name', 'parent_id')->where("status", '1')->get()->toArray();
        $array = $this->GenerateNavArray($categories);
        $new_array = $this->array_flatten($array);
        return $this->sendResponse($new_array, 'Success.');
    }

    public function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();

        foreach ($array as $key => $value) {
            if (isset($value['sub'])) {
                $array_count = count($value['sub']);
                for ($i = 0; $i < $array_count; $i++) {
                    if (!empty($value['sub'][$i])) {
                        if ($value['sub'][$i]['sub'] != '') {
                            foreach ($value['sub'][$i]['sub'] as $key => $data) {
                                array_push($value['sub'], $data);
                            }
                            unset($value['sub'][$i]['sub']);
                            $array_count++;
                        }
                    }
                }
                $result[$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function GenerateNavArray($arr, $parent = 0) {
        $pages = Array();
        foreach ($arr as $page) {
            if ($page['parent_id'] == $parent) {
                $page['sub'] = isset($page['sub']) ? $page['sub'] : $this->GenerateNavArray($arr, $page['id']);
                $pages[] = $page;
            }
        }
        return $pages;
    }

    /**
     * Get Reviewer courses
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getReviewerCourse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:tbl_user,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $query = ReviewerCourse::leftJoin('tbl_course', 'tbl_reviewer_course.course_id', '=', 'tbl_course.course_id')
                ->leftJoin('tbl_course_has_user', 'tbl_course_has_user.course_id', '=', 'tbl_reviewer_course.course_id')
                ->leftJoin('tbl_user', 'tbl_user.id', '=', 'tbl_course_has_user.user_id')
                ->leftJoin(DB::Raw('(SELECT r.course_id, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM tbl_course_has_review_rate AS r) s'), 's.course_id', '=', 'tbl_course.course_id')
                ->where('tbl_course.status', '1')
                ->where('tbl_reviewer_course.user_id', $user_id)
                ->groupBy('tbl_course.course_id')
                ->select(DB::Raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name'), DB::Raw('IFNULL(s.rate, 0) AS rate'), DB::Raw('IFNULL(s.total_record, 0) AS total_record'), 'tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_image', 'tbl_course.slug', 'tbl_course.course_price');

        $subscribe_course = $query->get();
        $subscribe_courses = [];
        if (!empty($subscribe_course)) {
            foreach ($subscribe_course as $key => $course) {
                $course->course_price = (string) currencyConvert($course->course_price);
                $course->symbol = getCurrencySymbol();
                $subscribe_courses[$key] = $course;
                $state = $this->GetUserCourseState($user_id, $course->course_id);
                if (empty($state)) {
                    $subscribe_courses[$key]['state'] = "todo";
                } else {
                    $subscribe_courses[$key]['state'] = ($state->state == "" || $state->state == "complete" ) ? "todo" : $state->state;
                }
                $subscribe_courses[$key] = $course;
            }
        }
        if (count($subscribe_courses) > 0) {
            $success = $subscribe_courses;
            return $this->sendResponse($success, 'Success.');
        } else {
            return $this->sendError('No data.', 'Courses not found.');
        }
    }

    /**
     * Get Last State of course
     * @param type $user_id
     * @param type $course_id
     * @return type
     */
    public function GetUserCourseState($user_id, $course_id) {
        return ReviewerUserCourseAttempt::Where(['user_id' => $user_id, 'course_id' => $course_id, 'state' => 'process'])->first();
    }

}
