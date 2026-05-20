<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use App\Models\Category;
use App\Models\Course;
use App\Models\CmsPages;
use App\Models\Options;
use App\Models\CourseHasReview;
use App\Models\CourseViews;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use App\Models\UserHasOrganization;
use App\Models\Topfeatures;
use App\Models\Help;
use App\Models\User;
use App\Models\RequestDemo;
use App\Models\CourseHasLanguage;
use App\Models\Wishlist;
use App\Models\CourseQuestion;
use App\Models\Teaching;
use App\Models\Roles;
use App\Models\Contactus;
use App\Models\SEOmeta;
use App\Helper\DocumentUploadS3Helper;
use Illuminate\Http\Request;
use App\DataTables\Common\GetTeachingDataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Stevebauman\Location\Facades\Location;
use App\Helper\GetOptionDataHelper;
use App\Mail\ContactUsEmail;
use App\Models\Payment;
use App\Models\Subscriber;

class PageController extends Controller {

    /**
     * Homepage after login and before login
     * @return type
     */
    public function index(Request $request) {
//        $position = Location::get($request->ip());
//        dd($position);
        $cat_aar = [];
        $section_data = new \App\Helper\GetOptionDataHelper();
        $page_all_data = [];

        //// student view section data for both page /////////
        $student_view_course = DB::select('SELECT GROUP_CONCAT(tbl_user.name SEPARATOR ", ") author_name,IFNULL(`cv`.`views`, 0) AS `views`, IFNULL(`s`.`rate`, 0) AS `rate`,IFNULL(`s`.`total_record`, 0) AS `total_record`,`tbl_course`.* FROM `tbl_course`
                        LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                        LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id`
                        LEFT JOIN (SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r where status="1" GROUP BY r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id)
                        LEFT JOIN (SELECT v.`course_id`, COUNT(v.`course_id`) AS views FROM `tbl_course_views` AS v GROUP BY v.`course_id`) cv ON (`tbl_course`.`course_id` = cv.course_id)
                        WHERE `tbl_course`.`status` = "1" GROUP BY tbl_course.course_id ORDER BY views DESC LIMIT 10 ');

        if (!empty($student_view_course)) {
            $page_all_data['view_course'] = $student_view_course;
        }

        ///// end of student view section data ///////////////
        if (Auth::check()) {
            $data = $section_data->getOptionData(['homepage_hero_section', 'homepage_slogan_section']);
            $cart = getCartData();
            $page_all_data['cart'] = $cart;
            $hero_sec_data = (array) $data['homepage_hero_section'];
            $slogan_sec_data = (array) $data['homepage_slogan_section'];

            $page_all_data['username'] = Auth::user()->name;
            $user_id = Auth::user()->id;
            $id[0] = $user_id;

            /// loggedIn user's org Id
            $org = UserHasOrganization::select('org_id')
                    ->where('user_id', $user_id)
                    ->first();
            if (!empty($org)) {
                $org_id = $org->org_id;
                $id[1] = $org_id;
            }
            /// LoggedIn user's subscribed course for i Learn section/////
            $query = CourseSubscription::select('tbl_course.*')
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

            $page_all_data['subscribed_course'] = $sub;
            $course_id = array_column($sub, 'course_id');
            $page_all_data['subscribed_course_id'] = $course_id;

            // parent category data for Category section
            $parent_cat_arr = CourseCategory::select('cat_id', 'name', 'slug')->leftjoin('tbl_course_category', 'tbl_course_category.id', '=', 'tbl_has_course_category.cat_id')->whereIn('course_id', $course_id)->groupby('cat_id')->limit(10)->get()->toArray();
            $page_all_data['parent_cat'] = $parent_cat_arr;

            $parent_cat = array_unique(array_column($parent_cat_arr, 'cat_id'));

            // child category data for recommended course section category
            $child_cat = Category::select('id', 'name', 'slug')->whereIn('parent_id', $parent_cat)->limit(10)->get()->toArray();
            $child_cat_id = array_column($child_cat, 'id');
            $page_all_data['child_cat'] = $child_cat;

            // Recommended courses for loggedIn User
            $recommended_course = Course::select(DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as courseView'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
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
//                            ->where('tbl_course.is_delete', "0")
                            ->whereIn('tbl_has_related_course.course_id', $course_id)
                            ->whereNotIn('tbl_has_related_course.related_course_id', $course_id)
                            ->orderBy('tbl_course.course_id', 'desc')->groupBy('tbl_has_related_course.related_course_id')->limit(10)->get()->toArray();

            $page_all_data['recommended_course'] = $recommended_course;

            // Next In Line section Data
            $next_line = Course::select(DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as courseView'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
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
//                            ->where('tbl_course.is_delete', "0")
                            ->whereIn('tbl_has_course_category.cat_id', $child_cat_id)
                            ->whereNotIn('tbl_course.course_id', $course_id)
                            ->orderBy('tbl_course.course_id', 'desc')->groupBy('tbl_has_course_category.course_id')->limit(10)->get()->toArray();
            $page_all_data['next_line'] = $next_line;
            $seometa = SEOmeta::where('slug', 'homepage-after-login')->first();
            $page_all_data['seo_meta'] = $seometa;
        } else {
            $data = $section_data->getOptionData(['homepage_hero_section', 'homepage_slogan_section', 'homepage_sell_course_online_section', 'homepage_digital_classroom_section', 'homepage_blog_section']);
            $hero_sec_data = (array) $data['homepage_hero_section'];
            $slogan_sec_data = (array) $data['homepage_slogan_section'];
            $teaching_sec_data = (array) $data['homepage_sell_course_online_section'];
            $digital_sec_data = (array) $data['homepage_digital_classroom_section'];
            $blog_sec_data = (array) $data['homepage_blog_section'];
            // Broad selection of category section data
            $category_id = implode(',', $hero_sec_data['hero_broad_selection_course']);
            $category = DB::select("SELECT IFNULL(`cv`.`views`, 0) AS `courseView`, IFNULL(`s`.`rate`, 0) AS `rates`, IFNULL(`s`.`total_record`, 0) AS `total_record`, `tbl_course_category`.`category_sub_description`,`tbl_course_category`.`slug`,`tbl_course_category`.`category_description`,
                                `tbl_course_category`.`name` AS `cat_name`,`tbl_course`.`slug` AS `course_slug`,`tbl_course`.`course_name`,`tbl_course`.`created_at`,`tbl_course`.`course_id`,`tbl_course`.`course_sub_description`,
                                `tbl_course`.`course_image`,`tbl_course`.`course_tag`,`tbl_course`.`course_price`,`tbl_course`.`course_sub_description`,`tbl_course`.`course_applications`, GROUP_CONCAT(tbl_user.name ORDER BY tbl_user.id SEPARATOR ', ') author_name
                                FROM `tbl_has_course_category` LEFT JOIN `tbl_course_category` ON `tbl_has_course_category`.`cat_id` = `tbl_course_category`.`id` LEFT JOIN `tbl_course`
                                ON `tbl_has_course_category`.`course_id` = `tbl_course`.`course_id` LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                                LEFT JOIN `tbl_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id` LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate, COUNT('r.*') AS total_record
                                FROM `tbl_course_has_review_rate` AS r where status='1' GROUP BY r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id) LEFT JOIN (SELECT v.`course_id`,COUNT(v.`course_id`) AS views
                                FROM `tbl_course_views` AS v GROUP BY v.`course_id`) cv ON (`tbl_course`.`course_id` = cv.course_id) WHERE `tbl_has_course_category`.`cat_id` IN ($category_id)
                                AND `tbl_course`.`status` = '1' GROUP BY `tbl_has_course_category`.`id`");
            $page_all_data['categories'] = [];
            if (!empty($category)) {
                foreach ($category as $row) {
                    $page_all_data['categories'][$row->cat_name]['courses'][] = (array) $row;
                    $page_all_data['categories'][$row->cat_name]['category']['category_sub_description'] = $row->category_sub_description;
                    $page_all_data['categories'][$row->cat_name]['category']['slug'] = $row->slug;
                    $page_all_data['categories'][$row->cat_name]['category']['category_description'] = $row->category_description;
                }
            }
            // top category section data
            $crs_id = array_column($student_view_course, 'course_id');
            $crs_cat_arr = CourseCategory::select('cat_id', 'name', 'slug')->leftjoin('tbl_course_category', 'tbl_course_category.id', '=', 'tbl_has_course_category.cat_id')->whereIn('course_id', $crs_id)->groupby('cat_id')->limit(10)->get()->toArray();
            $i = 0;
            $cnt = count($crs_cat_arr);
            for ($j = 0; $j < $cnt; $j++) {
                if ($i < 10 && !in_array($crs_cat_arr[$j]['name'], $cat_aar)) {
                    $cat_aar[$crs_cat_arr[$j]['name']] = $crs_cat_arr[$j]['slug'];
                    $i++;
                }
            }

            $page_all_data['cat_arr'] = $cat_aar;
            if (!empty($teaching_sec_data)) {
                $page_all_data['teaching_sec_title'] = "";
                $page_all_data['teaching_sec_btn_name'] = "";
                $page_all_data['teaching_sec_btn_url'] = "";
                $page_all_data['teaching_sec_description'] = "";
                $page_all_data['teaching_sec_image'] = "";

                if (!empty($teaching_sec_data['sell_course_online_sec_title'])) {
                    $page_all_data['teaching_sec_title'] = $teaching_sec_data['sell_course_online_sec_title'];
                }
                if (!empty($teaching_sec_data['sell_course_online_sec_btn_name'])) {
                    $page_all_data['teaching_sec_btn_name'] = $teaching_sec_data['sell_course_online_sec_btn_name'];
                }
                if (!empty($teaching_sec_data['sell_course_online_sec_btn_url'])) {
                    $page_all_data['teaching_sec_btn_url'] = $teaching_sec_data['sell_course_online_sec_btn_url'];
                }
                if (!empty($teaching_sec_data['sell_course_online_sec_description'])) {
                    $page_all_data['teaching_sec_description'] = strip_tags($teaching_sec_data['sell_course_online_sec_description']);
                }
                if (!empty($teaching_sec_data['sell_course_online_sec_image'])) {
                    $page_all_data['teaching_sec_image'] = $teaching_sec_data['sell_course_online_sec_image'];
                }
            }

            if (!empty($digital_sec_data)) {
                $page_all_data['digital_sec_title'] = "";
                $page_all_data['digital_sec_subtitle'] = "";
                $page_all_data['digital_sec_btn_name'] = "";
                $page_all_data['digital_sec_btn_url'] = "";
                $page_all_data['digital_sec_description'] = "";
                $page_all_data['digital_sec_image'] = "";

                $page_all_data['digital_sec_title'] = "BUSINESS";
                $page_all_data['digital_sec_subtitle'] = "Digital Classroom";

                if (!empty($digital_sec_data['digital_sec_title'])) {
                    $page_all_data['digital_title'] = $digital_sec_data['digital_sec_title'];
                }
                if (!empty($digital_sec_data['digital_sec_btn_name'])) {
                    $page_all_data['digital_sec_btn_name'] = $digital_sec_data['digital_sec_btn_name'];
                }
                if (!empty($digital_sec_data['digital_sec_btn_url'])) {
                    $page_all_data['digital_sec_btn_url'] = $digital_sec_data['digital_sec_btn_url'];
                }
                if (!empty($digital_sec_data['digital_sec_description'])) {
                    $page_all_data['digital_sec_description'] = strip_tags($digital_sec_data['digital_sec_description']);
                }
                if (!empty($digital_sec_data['digital_sec_image'])) {
                    $page_all_data['digital_sec_image'] = $digital_sec_data['digital_sec_image'];
                }
            }

            if (!empty($blog_sec_data)) {
                $page_all_data['blog_sec_title'] = "";
                $page_all_data['blog_sec_btn_name'] = "";
                $page_all_data['blog_sec_btn_url'] = "https://blog.edupme.com";
                $page_all_data['blog_sec_image'] = "";
                $page_all_data['blog_sec_description'] = "";
                $page_all_data['blog_sec_image'] = "";

                if (!empty($blog_sec_data['blog_sec_title'])) {
                    $page_all_data['blog_sec_title'] = $blog_sec_data['blog_sec_title'];
                }
                if (!empty($blog_sec_data['blog_sec_btn_name'])) {
                    $page_all_data['blog_sec_btn_name'] = $blog_sec_data['blog_sec_btn_name'];
                }
                if (!empty($blog_sec_data['blog_sec_image'])) {
                    $page_all_data['blog_sec_image'] = $blog_sec_data['blog_sec_image'];
                }
                if (!empty($blog_sec_data['blog_sec_btn_url'])) {
                    $page_all_data['blog_sec_btn_url'] = $blog_sec_data['blog_sec_btn_url'];
                }
                if (!empty($blog_sec_data['blog_sec_image'])) {
                    $page_all_data['blog_sec_image'] = $blog_sec_data['blog_sec_image'];
                }
                if (!empty($blog_sec_data['blog_sec_description'])) {
                    $page_all_data['blog_sec_description'] = strip_tags($blog_sec_data['blog_sec_description']);
                }
            }
            $seometa = SEOmeta::where('slug', 'homepage-before-login')->first();
            $page_all_data['seo_meta'] = $seometa;
        }

        if (!empty($hero_sec_data)) {
            $page_all_data['hero_image_title'] = "";
            $page_all_data['hero_image_btn_url'] = "";
            $page_all_data['hero_image_btn_name'] = "";
            $page_all_data['hero_image_description'] = "";
            $page_all_data['hero_image_image'] = "";
            $page_all_data['hero_broad_selection_description'] = "";

            if (!empty($hero_sec_data['hero_sec_title'])) {
                $page_all_data['hero_image_title'] = $hero_sec_data['hero_sec_title'];
            }
            if (!empty($hero_sec_data['hero_sec_btn_url'])) {
                $page_all_data['hero_image_btn_url'] = $hero_sec_data['hero_sec_btn_url'];
            }
            if (!empty($hero_sec_data['hero_sec_btn_name'])) {
                $page_all_data['hero_image_btn_name'] = $hero_sec_data['hero_sec_btn_name'];
            }
            if (!empty($hero_sec_data['hero_sec_description'])) {
                $page_all_data['hero_image_description'] = $hero_sec_data['hero_sec_description'];
            }
            if (!empty($hero_sec_data['hero_sec_image'])) {
                $page_all_data['hero_image_image'] = $hero_sec_data['hero_sec_image'];
            } else {
                $page_all_data['hero_image_image'] = "https://edupmquestionhelp.s3.ap-south-1.amazonaws.com/tmp/images/16296932921629693292.jpg";
            }
            if (!empty($hero_sec_data['hero_broad_selection_description'])) {
                $page_all_data['hero_broad_selection_description'] = $hero_sec_data['hero_broad_selection_description'];
            }
        }

        if (!empty($slogan_sec_data)) {
            $page_all_data['slogan_first'] = "";
            $page_all_data['slogan_first_image'] = "";
            $page_all_data['slogan_second'] = "";
            $page_all_data['slogan_second_image'] = "";
            $page_all_data['slogan_third'] = "";
            $page_all_data['slogan_third_image'] = "";

            if (!empty($slogan_sec_data['slogan_first'])) {
                $page_all_data['slogan_first'] = $slogan_sec_data['slogan_first'];
            }
            if (!empty($slogan_sec_data['slogan_first_image'])) {
                $page_all_data['slogan_first_image'] = $slogan_sec_data['slogan_first_image'];
            }
            if (!empty($slogan_sec_data['slogan_second'])) {
                $page_all_data['slogan_second'] = $slogan_sec_data['slogan_second'];
            }
            if (!empty($slogan_sec_data['slogan_second_image'])) {
                $page_all_data['slogan_second_image'] = $slogan_sec_data['slogan_second_image'];
            }
            if (!empty($slogan_sec_data['slogan_third'])) {
                $page_all_data['slogan_third'] = $slogan_sec_data['slogan_third'];
            }
            if (!empty($slogan_sec_data['slogan_third_image'])) {
                $page_all_data['slogan_third_image'] = $slogan_sec_data['slogan_third_image'];
            }
        }
        $wishlist = getWhishlistCourse();

        if (Auth::check()) {
            // return view("page.after-login-home")->with(['page_all_data' => $page_all_data, 'wishlist' => $wishlist]);
            return view("frontend.user-home")->with(['page_all_data' => $page_all_data, 'wishlist' => $wishlist]);
        } else {
            // return view("page.guest-home")->with(['page_all_data' => $page_all_data, 'wishlist' => $wishlist]);
            return view("frontend.guest-home")->with(['page_all_data' => $page_all_data, 'wishlist' => $wishlist]);
        }
    }

    /**
     * Category Courses page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function CategoryCourses(Request $request) {
        $section_data = new \App\Helper\GetOptionDataHelper();
        $data = $section_data->getOptionData(['homepage_hero_section']);

        $hero_sec_data = $data['homepage_hero_section'];

        $page_all_data['hero_image_title'] = "";
        $page_all_data['hero_image_btn_url'] = "";
        $page_all_data['hero_image_btn_name'] = "";
        $page_all_data['hero_image_description'] = "";
        $page_all_data['hero_image_image'] = "";

        if (!empty($hero_sec_data['hero_sec_title'])) {
            $page_all_data['hero_image_title'] = $hero_sec_data['hero_sec_title'];
        }
        if (!empty($hero_sec_data['hero_sec_btn_url'])) {
            $page_all_data['hero_image_btn_url'] = $hero_sec_data['hero_sec_btn_url'];
        }
        if (!empty($hero_sec_data['hero_sec_btn_name'])) {
            $page_all_data['hero_image_btn_name'] = $hero_sec_data['hero_sec_btn_name'];
        }
        if (!empty($hero_sec_data['hero_sec_description'])) {
            $page_all_data['hero_image_description'] = $hero_sec_data['hero_sec_description'];
        }
        if (!empty($hero_sec_data['hero_sec_image'])) {
            $page_all_data['hero_image_image'] = $hero_sec_data['hero_sec_image'];
        } else {
            $page_all_data['hero_image_image'] = "https://edupmquestionhelp.s3.ap-south-1.amazonaws.com/tmp/images/16296932921629693292.jpg";
        }

        $category = Category::select('id', 'name', 'category_sub_description', 'meta_title', 'meta_keyword', 'meta_description')->where('slug', $request->slug)->first();
        if (!empty($category)) {
            $page_all_data['cat_name'] = $category->name;
            $page_all_data['category_sub_description'] = $category->category_sub_description;
            $page_all_data['meta_title'] = $category->meta_title;
            $page_all_data['meta_keyword'] = $category->meta_keyword;
            $page_all_data['meta_description'] = $category->meta_description;


            $most_popular = Course::select('tbl_has_course_category.cat_id', DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as views'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
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
//                    ->where('tbl_course.is_delete', "0")
                    ->groupBy('tbl_course.course_id')
                    ->orderBy('views', 'DESC')
                    ->limit(10)
                    ->get();

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
//                    ->where('tbl_course.is_delete', "0")
                    ->groupBy('tbl_course.course_id')
                    ->paginate(8);

            $page_all_data['most_popular'] = $most_popular;
            $page_all_data['all_course'] = $all_course;
            $page_all_data['cart'] = getCartData();
            $page_all_data['sub_course'] = getSubscriptCourse();

            $wishlist = getWhishlistCourse();
            return view("frontend.category-courses")->with(['page_all_data' => $page_all_data, 'wishlist' => $wishlist]);
        }
        abort(404);
    }

    /**
     * fetch autocomplete suggestions
     * @param \Illuminate\Http\Request $request
     */
    public function fetchAuto(Request $request) {
        $data = [];
        $final = [];
        $search = $request->get('term');
        $result = Course::select('course_name', 'course_image', 'slug')->where('course_name', 'LIKE', '%' . $search . '%')->get();
        $res = Category::select('name', 'slug')->where('name', 'LIKE', '%' . $search . '%')->get();

        $tmp = [];
        foreach ($result as $value) {
            $tmp['name'] = $value['course_name'];
            $tmp['tag'] = "course";
            $tmp['image'] = $value['course_image'];
            $tmp['slug'] = $value['slug'];
            $final[] = $tmp;
        }

        $tmp = [];
        foreach ($res as $value) {
            $tmp['name'] = $value['name'];
            $tmp['tag'] = "category";
            $tmp['image'] = "";
            $tmp['slug'] = $value['slug'];
            $final[] = $tmp;
        }
        if (!empty($final)) {
            $output = '<ul class="search-suggestion-list">';
            foreach ($final as $row) {
                if ($row['tag'] == 'course') {
                    $imgSrc = !empty($row["image"]) ? $row["image"] : url('/assets/img/default.png');
                    $output .= '<li class="search-suggestion-item">
                            <img src="' . $imgSrc . '" class="search-suggestion-img" alt="Course Image" onerror="this.onerror=null;this.src=\'' . url('/assets/img/default.png') . '\';">
                            <a href="' . url('/course/' . $row["slug"]) . '" class="search-suggestion-link">' . htmlspecialchars($row["name"]) . ' <small>Course</small></a>
                        </li>';
                } else {
                    $output .= '<li class="search-suggestion-item">
                            <span class="icon-search"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span><a href="' . url('/category/' . $row["slug"]) . '" class="search-suggestion-link">' . htmlspecialchars($row["name"]) . '</a>
                        </li>';
                }
            }
            $output .= '</ul>';
        } else {
            $output = "";
        }

        echo $output;
    }

    /**
     * Privacy Policy
     * @return type
     */
    public function getPrivacyPolicy() {
        $seometa = SEOmeta::where('slug', 'privacy-policy')->first();
        // return view("page.privacy_policy")->with(['seometa' => $seometa]);
        return view("frontend.privacy")->with(['seometa' => $seometa]);
    }

    /**
     * Disclaimer
     * @return type
     */
    public function getDisclaimer() {
        $seometa = SEOmeta::where('slug', 'disclaimer')->first();
        // return view("page.disclaimer")->with(['seometa' => $seometa]);
        return view("frontend.disclaimer")->with(['seometa' => $seometa]);
    }

    /**
     * Terms and condition
     * @return type
     */
    public function getTerms() {
        $seometa = SEOmeta::where('slug', 'term-&-condition')->first();
        // return view("page.term_and_condition")->with(['seometa' => $seometa]);
        return view("frontend.terms")->with(['seometa' => $seometa]);
    }

    /**
     * Auto Search suggession
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getSearch(Request $request) {
        $page_all_data = [];
        $search = $request->q;
        $all_course = Course::select(DB::raw('GROUP_CONCAT(tbl_course_category.name SEPARATOR ",") as cat_name'), DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as views'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
                ->leftjoin('tbl_has_course_category', 'tbl_course.course_id', '=', 'tbl_has_course_category.course_id')
                ->leftjoin('tbl_course_category', 'tbl_has_course_category.cat_id', '=', 'tbl_course_category.id')
                ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                ->leftJoin(DB::raw('(SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record
                FROM `tbl_course_has_review_rate` AS r where status="1"
                GROUP BY r.`course_id`) AS s'), 'tbl_course.course_id', '=', 's.course_id')
                ->leftJoin(DB::raw('(SELECT v.`course_id`, COUNT(v.`course_id`) AS views
                FROM `tbl_course_views` AS v
                GROUP BY v.`course_id`) AS cv'), 'tbl_course.course_id', '=', 'cv.course_id')
                ->where('tbl_course.status', "1")
                ->where(function ($query) use ($search) {
                    $query->where('tbl_course.course_name', 'like', '%' . $search . '%')
                    ->orwhere('tbl_course_category.name', 'like', '%' . $search . '%')
                    ->orwhere('tbl_user.name', 'like', '%' . $search . '%');
                })
//                ->where('tbl_course.is_delete', "0")
                ->groupBy('tbl_course.course_id')
                ->paginate(8);
        $seometa = SEOmeta::where('slug', 'search-page')->first();

        $page_all_data['all_course'] = $all_course;
        $page_all_data['cart'] = getCartData();
        $page_all_data['sub_course'] = getSubscriptCourse();
        $page_all_data['seo_meta'] = $seometa;
        $wishlist = getWhishlistCourse();

        // return view("page.search-course")->with(['page_all_data' => $page_all_data, 'wishlist' => $wishlist]);
        return view("frontend.search-course")->with(['page_all_data' => $page_all_data, 'wishlist' => $wishlist]);
    }

    /**
     * Instructor Details page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function InstructorDetails(Request $request) {
        $user = User::where('author_slug', $request->slug)
                        ->where('status', '1')->first();
        if (!empty($user)) {
            $student = CourseSubscriptionLicence::select('tbl_course_subscription_licence.id')
                            ->leftJoin('tbl_course_has_user', 'tbl_course_subscription_licence.course_id', '=', 'tbl_course_has_user.course_id')
                            ->where('tbl_course_has_user.user_id', $user['id'])->groupBy('tbl_course_subscription_licence.user_id')->get();

            $review = CourseHasReview::select(DB::raw('COUNT(tbl_course_has_review_rate.id) as total_review'))
                            ->leftJoin('tbl_course_has_user', 'tbl_course_has_review_rate.course_id', '=', 'tbl_course_has_user.course_id')
                            ->where('tbl_course_has_user.user_id', $user['id'])->where('tbl_course_has_review_rate.status', '1')->first();

            $cart = getCartData();
            $my_course = Course::select(DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as courseView'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
                            ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                            ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                            ->leftJoin(DB::raw('(SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record
            FROM `tbl_course_has_review_rate` AS r where status="1"
            GROUP BY r.`course_id`) AS s'), 'tbl_course.course_id', '=', 's.course_id')
                            ->leftJoin(DB::raw('(SELECT v.`course_id`, COUNT(v.`course_id`) AS views
            FROM `tbl_course_views` AS v
            GROUP BY v.`course_id`) AS cv'), 'tbl_course.course_id', '=', 'cv.course_id')
                            ->where('tbl_course.status', "1")
//                            ->where('tbl_course.is_delete', "0")
                            ->where('tbl_course_has_user.user_id', $user['id'])
                            ->orderBy('tbl_course.course_id', 'desc')->groupBy('tbl_course_has_user.course_id')->paginate(12);
            $course_count = $my_course->total();
            $subscribe_course = getSubscriptCourse();
            $wishlist = getWhishlistCourse();
            // return view("page.instructor_details")->with(['user' => $user, 'student' => $student, 'review' => $review, 'wishlist' => $wishlist, 'my_course' => $my_course, 'cart' => $cart, 'subscribe_course' => $subscribe_course, 'course_count' => $course_count]);
            return view("frontend.instructor_details")->with(['user' => $user, 'student' => $student, 'review' => $review, 'wishlist' => $wishlist, 'my_course' => $my_course, 'cart' => $cart, 'subscribe_course' => $subscribe_course, 'course_count' => $course_count]);
        }
        abort(404);
    }

    /**
     * Course Details
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function CourseDetails(Request $request) {
        $slug = $request->slug;
        $course = DB::select('SELECT GROUP_CONCAT(`tbl_course_has_user`.`user_id` SEPARATOR ",") user_id,Ifnull(`cv`.`views`, 0) AS `courseView`,Ifnull(`s`.`rate`, 0) AS `rate`,
                            Ifnull(`s`.`total_record`, 0) AS `total_record`,`tbl_course`.* FROM  `tbl_course` LEFT JOIN `tbl_course_has_user` ON `tbl_course`.`course_id` = `tbl_course_has_user`.`course_id`
                            LEFT JOIN (SELECT r.`course_id`, Sum(r.`rate`) AS rate, Count("r.*")  AS total_record FROM   `tbl_course_has_review_rate` AS r where status="1" GROUP  BY r.`course_id`) s ON ( `tbl_course`.`course_id` = s.course_id )
                            LEFT JOIN (SELECT v.`course_id`, Count(v.`course_id`) AS views FROM   `tbl_course_views` AS v GROUP  BY v.`course_id`) cv ON ( `tbl_course`.`course_id` = cv.course_id ) WHERE  `tbl_course`.`slug` = "' . $slug . '"
                            AND `tbl_course`.`status` = "1" LIMIT  1');

        if (!empty($course[0]) && !empty($course[0]->course_id)) {
            $course = $course[0];
            $course_id = $course->course_id;
            $coupon_code = session()->get('coupon_code', '');
            if (!empty($coupon_code)) {
                $coupon_data = DB::table("tbl_coupon")
                        ->select('tbl_coupon.coupon_type', 'tbl_coupon.coupon_duration', 'tbl_coupon.coupon_percentage', 'tbl_coupon_has_course.course_id')
                        ->leftJoin("tbl_coupon_has_course", function($join) {
                            $join->on("tbl_coupon_has_course.coupon_id", "tbl_coupon.coupon_id", "=");
                        })
                        ->where("tbl_coupon_has_course.course_id", $course_id)
                        ->whereRaw('CURDATE() >= DATE(tbl_coupon.coupon_start_date)')->whereRaw('CURDATE() <= DATE(tbl_coupon.coupon_end_date)')
                        ->where('tbl_coupon.coupon_code', $coupon_code)
                        ->first();
                if (!empty($coupon_data)) {
                    $total_dis = currencyConvert($course->course_price) * $coupon_data->coupon_percentage / 100;
                    if ($coupon_data->coupon_type == '1') {
                        $total_dis_price = currencyConvert($course->course_price) - $total_dis;
                    } else {
                        $total_dis_price = 0;
                    }
                    $course->total_dis_price = $total_dis_price;
                }
            }

            $course_review = CourseHasReview::select('user_id', 'review')->with(array('user' => function($query) {
                    $query->select('id', 'name');
                }))->where('course_id', $course_id)->where('status', '1')->paginate(10);
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
                                        `tbl_course_has_review_rate` AS r where status="1" GROUP BY  r.`course_id`) s ON (`tbl_course`.`course_id` = s.course_id) where tbl_course.status="1" and tbl_course.course_id != "' . $course_id . '" GROUP BY tbl_course.course_id ORDER BY views DESC LIMIT 10');

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
            $author = DB::select('SELECT IFNULL(r.rate, 0) AS rate,IFNULL(r.total_record, 0) AS total_record,COUNT("`tbl_course_views`.`course_id") AS views,`tbl_user`.`name`,
                                    `tbl_user`.`author_slug`,`tbl_user`.`profile_image`,`tbl_user`.`about_me`,`tbl_user`.`id` FROM `tbl_user` LEFT JOIN `tbl_course_has_user` ON `tbl_course_has_user`.`user_id` = `tbl_user`.`id` LEFT JOIN `tbl_course_views`
                                      ON `tbl_course_has_user`.`course_id` = `tbl_course_views`.`course_id` LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate,COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r where status="1"
                                      GROUP BY r.`course_id`) r ON (`tbl_course_has_user`.`course_id` = r.course_id) WHERE `tbl_user`.`id` IN (' . $author_id . ') AND `tbl_user`.`status` = "1" GROUP BY `tbl_course_has_user`.`id`');
            $author_data = [];
            if (!empty($author)) {
                $rate_a = 0;
                $rate_v = 0;
                $total_record = 0;
                $auth_id = [];
                foreach ($author as $row) {
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
            }
            $language = CourseHasLanguage::select('tbl_language.name')
                    ->leftJoin('tbl_language', 'tbl_course_has_language.language_id', '=', 'tbl_language.id')
                    ->where('tbl_course_has_language.course_id', $course_id)
                    ->get();
//            session()->put('cart', []);
            $section_data = new \App\Helper\GetOptionDataHelper();
            $slogan_section = $section_data->getOptionData(['homepage_slogan_section']);
            $wishlist = getWhishlistCourse();

            $course_content = $this->getToctext($course_id);
            $subscribe_course = getSubscriptCourse();
            $where = '';
            if (!empty($subscribe_course)) {
                $where = 'AND tbl_has_related_course.course_id NOT IN(' . implode(',', $subscribe_course) . ')';
            }
            $bundle_course = DB::select('SELECT  IFNULL(s.rate, 0) AS rate,IFNULL(s.total_record, 0) AS total_record,COUNT("`tbl_course_views`.`course_id") AS views,`tbl_course`.course_id,`tbl_course`.slug,`tbl_course`.course_image,
                        `tbl_course`.course_name,`tbl_course`.course_featured,`tbl_course`.course_price FROM `tbl_course_views` LEFT JOIN `tbl_course` ON `tbl_course_views`.`course_id` = `tbl_course`.`course_id` LEFT JOIN `tbl_has_related_course` ON `tbl_has_related_course`.`related_course_id` = `tbl_course`.`course_id`
                        LEFT JOIN (SELECT r.`course_id`,SUM(r.`rate`) AS rate,COUNT("r.*") AS total_record FROM `tbl_course_has_review_rate` AS r where status="1" GROUP BY r.`course_id`) s
                          ON (`tbl_course`.`course_id` = s.course_id) WHERE tbl_course.status = "1" AND tbl_has_related_course.course_id = ' . $course_id . ' ' . $where . ' GROUP BY tbl_course.course_id');
            $cart = getCartData();
            return view("frontend.course_details")->with(['cart' => $cart, 'course' => (array) $course, 'languages' => $language, 'authors' => $author_data, 'student_view_course' => $student_view_course, 'categories' => $category, 'rate' => $rate, 'related_category' => $related_category, 'slogan_section' => $slogan_section, 'course_review' => $course_review, 'wishlist' => $wishlist, 'bundle_course' => $bundle_course, 'course_content' => $course_content, 'subscribe_course' => $subscribe_course]);
        }
        abort(404);
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
        $get_parent_data = CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('course_id', $course_id)->orderBy('que_toc_no', 'ASC')->get()->toArray();
        $all_data = [];
        if (!empty($get_parent_data)) {
            $toc_no = [];
            foreach ($get_parent_data as $key => $value) {
                $toc_no[] = $value['que_toc_no'];
            }
            $res = [];
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
        return $all_data;
    }

    /**
     * Digital Details page
     * @return type
     */
    public function DigitalSectionData() {
        $section_data = new \App\Helper\GetOptionDataHelper();
        $data = $section_data->getOptionData(['digital_classroom_hero', 'digital_classroom_how_it_work', 'digital_classroom_teaching_cycle', 'digital_classroom_learning_cycle', 'digital_classroom_features', 'digital_classroom_help']);
        if (!empty($data)) {
            if (isset($data['digital_classroom_hero'])) {
                $hero_sec_data = $data['digital_classroom_hero'];
            }
            if (isset($data['digital_classroom_how_it_work'])) {
                $how_it_work = $data['digital_classroom_how_it_work'];
            }
            if (isset($data['digital_classroom_teaching_cycle'])) {
                $teaching_cycle = $data['digital_classroom_teaching_cycle'];
            }
            if (isset($data['digital_classroom_learning_cycle'])) {
                $learning_cycle = $data['digital_classroom_learning_cycle'];
            }
            if (isset($data['digital_classroom_features'])) {
                $feature_data = $data['digital_classroom_features'];
            }
            if (isset($data['digital_classroom_help'])) {
                $classroom_help = $data['digital_classroom_help'];
            }

            $page_all_data['hero_sec_title'] = '';
            $page_all_data['hero_sec_description'] = '';
            $page_all_data['hero_sec_image'] = '';

            if (!empty($hero_sec_data['hero_sec_title'])) {
                $page_all_data['hero_sec_title'] = $hero_sec_data['hero_sec_title'];
            }

            if (!empty($hero_sec_data['hero_sec_description'])) {
                $page_all_data['hero_sec_description'] = $hero_sec_data['hero_sec_description'];
            }

            if (!empty($hero_sec_data['hero_sec_image'])) {
                $page_all_data['hero_sec_image'] = $hero_sec_data['hero_sec_image'];
            }

            $page_all_data['how_it_work_sec_title'] = '';
            $page_all_data['how_it_work_sec_sub_title'] = '';
            $page_all_data['how_it_work_sec_image'] = '';

            if (!empty($how_it_work['how_it_work_sec_title'])) {
                $page_all_data['how_it_work_sec_title'] = $how_it_work['how_it_work_sec_title'];
            }

            if (!empty($how_it_work['how_it_work_sec_sub_title'])) {
                $page_all_data['how_it_work_sec_sub_title'] = $how_it_work['how_it_work_sec_sub_title'];
            }

            if (!empty($how_it_work['how_it_work_sec_image'])) {
                $page_all_data['how_it_work_sec_image'] = $how_it_work['how_it_work_sec_image'];
            }

            $page_all_data['learning_cycle_sec_title'] = '';
            $page_all_data['learning_cycle_sec_sub_title'] = '';
            $page_all_data['learning_cycle_sec_image'] = '';

            if (!empty($learning_cycle['learning_cycle_sec_title'])) {
                $page_all_data['learning_cycle_sec_title'] = $learning_cycle['learning_cycle_sec_title'];
            }

            if (!empty($learning_cycle['learning_cycle_sec_sub_title'])) {
                $page_all_data['learning_cycle_sec_sub_title'] = $learning_cycle['learning_cycle_sec_sub_title'];
            }

            if (!empty($learning_cycle['learning_cycle_sec_image'])) {
                $page_all_data['learning_cycle_sec_image'] = $learning_cycle['learning_cycle_sec_image'];
            }

            $page_all_data['teaching_cycle_sec_title'] = '';
            $page_all_data['teaching_cycle_sec_sub_title'] = '';
            $page_all_data['teaching_cycle_sec_image'] = '';

            if (!empty($teaching_cycle['teaching_cycle_sec_title'])) {
                $page_all_data['teaching_cycle_sec_title'] = $teaching_cycle['teaching_cycle_sec_title'];
            }

            if (!empty($teaching_cycle['teaching_cycle_sec_sub_title'])) {
                $page_all_data['teaching_cycle_sec_sub_title'] = $teaching_cycle['teaching_cycle_sec_sub_title'];
            }

            if (!empty($teaching_cycle['teaching_cycle_sec_image'])) {
                $page_all_data['teaching_cycle_sec_image'] = $teaching_cycle['teaching_cycle_sec_image'];
            }

            $page_all_data['features_sec_title'] = '';
            $page_all_data['features_sec_sub_title'] = '';
            $page_all_data['features_features'] = [];

            if (!empty($feature_data['features_sec_title'])) {
                $page_all_data['features_sec_title'] = $feature_data['features_sec_title'];
            }

            if (!empty($feature_data['features_sec_sub_title'])) {
                $page_all_data['features_sec_sub_title'] = $feature_data['features_sec_sub_title'];
            }

            $top_features = Topfeatures::select(['title', 'sub_title', 'image'])->get();

            if (!$top_features->isempty()) {
                $page_all_data['features_features'] = $top_features;
            }

            $page_all_data['help_sec_title'] = '';
            $page_all_data['help_help'] = [];

            if (!empty($classroom_help['help_sec_title'])) {
                $page_all_data['help_sec_title'] = $classroom_help['help_sec_title'];
            }

            $help = Help::select(['title', 'image'])->get();

            if (!$help->isempty()) {
                $page_all_data['help_help'] = $help;
            }
            $seometa = SEOmeta::where('slug', 'digital-class')->first();
            $page_all_data['seo_meta'] = $seometa;
            return view("frontend.digital-class")->with(['page_all_data' => $page_all_data]);
        }
        abort(404);
    }

    /**
     * Start Teaching page
     * @return type
     */
    public function StartTeachingData() {
        $page_all_data = [];
        $section_data = new \App\Helper\GetOptionDataHelper();

        $option_names = [
            'teachingpage_hero_section',
            'teachingpage_boost_income_section'
        ];

        $data = $section_data->getOptionData($option_names);

        if (isset($data['teachingpage_hero_section'])) {
            $hero_section = $data['teachingpage_hero_section'];
        }
        if (isset($data['teachingpage_boost_income_section'])) {
            $boost_income_section = $data['teachingpage_boost_income_section'];
        }

        $page_all_data['hero_sec_title'] = '';
        $page_all_data['hero_sec_description'] = '';
        $page_all_data['hero_sec_image'] = '';

        if (!empty($hero_section['hero_sec_title'])) {
            $page_all_data['hero_sec_title'] = $hero_section['hero_sec_title'];
        }

        if (!empty($hero_section['hero_sec_description'])) {
            $page_all_data['hero_sec_description'] = $hero_section['hero_sec_description'];
        }

        if (!empty($hero_section['hero_sec_image'])) {
            $page_all_data['hero_sec_image'] = $hero_section['hero_sec_image'];
        }

        $page_all_data['teachingpage_boost_income_sec_title'] = '';
        $page_all_data['teachingpage_boost_income_sec_description'] = '';
        $page_all_data['teachingpage_boost_income_sec_image'] = '';

        if (!empty($boost_income_section['teachingpage_boost_income_sec_title'])) {
            $page_all_data['teachingpage_boost_income_sec_title'] = $boost_income_section['teachingpage_boost_income_sec_title'];
        }

        if (!empty($boost_income_section['teachingpage_boost_income_sec_description'])) {
            $page_all_data['teachingpage_boost_income_sec_description'] = $boost_income_section['teachingpage_boost_income_sec_description'];
        }

        if (!empty($boost_income_section['teachingpage_boost_income_sec_image'])) {
            $page_all_data['teachingpage_boost_income_sec_image'] = $boost_income_section['teachingpage_boost_income_sec_image'];
        }
        //echo "<pre>"; print_r($page_all_data); exit;
        $top_features = Topfeatures::select(['title', 'sub_title', 'image'])->get();

        if (!$top_features->isempty()) {
            $page_all_data['features_features'] = $top_features;
        }

        $help = Help::select(['title', 'image'])->get();

        if (!$help->isempty()) {
            $page_all_data['help_help'] = $help;
        }
        $seometa = SEOmeta::where('slug', 'start-teaching')->first();
        $page_all_data['seo_meta'] = $seometa;
        return view("frontend.start-teaching")->with(['page_all_data' => $page_all_data]);
    }

    /**
     * Store book Demo
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function storeBookYourFreeDemo(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'contact_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required',
                'institute_name' => 'required',
                'state' => 'required',
            ]);
            $data = $request->all();
            RequestDemo::create($data);
            return ["success" => true, "message" => "<p>Thank you for booking a free demo!</p><p>While we do our best to answer your queries quickly, it may take about 24 hours to receive a response from us. </p><p>Thanks in advance for your patience.  </p><p>Have a great day!</p>"];
        }
        abort(404);
    }

    /**
     * Store Start Teaching
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function storeStartTeaching(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'contact_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required',
                'online_teaching_experience' => 'required',
                'own_audience' => 'required',
                'teaching_provide' => 'required',
                'hear_about_us' => 'required',
            ]);

            $insert_data = [
                'contact_name' => $request->contact_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'online_teaching_experience' => $request->online_teaching_experience,
                'own_audience' => $request->own_audience,
                'hear_about_us' => $request->hear_about_us,
            ];

            if ($request->teaching_provide == 'Other') {
                $validatedData = $request->validate([
                    'other_teaching' => 'required',
                ]);
                $insert_data['teaching_provide'] = $request->other_teaching;
            } else {
                $insert_data['teaching_provide'] = $request->teaching_provide;
            }

            Teaching::create($insert_data);
            return ["success" => true, "message" => "<p>Thank you for submitting the details!</p><p>While we do our best to answer your queries quickly, it may take about 24 hours to receive a response from us.</p><p>Thanks in advance for your patience.</p><p>Have a great day!</p>"];
        }
        abort(404);
    }

    /**
     * Contact us page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getContactus(Request $request) {
        $page_setting_data = GetOptionDataHelper::getOptionData(['contactuspage_hero_section']);
        // return view('page.contact-us')->with(['data' => $page_setting_data]);
        return view('frontend.contact-us')->with(['data' => $page_setting_data]);
    }

    /**
     * About us page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getAboutus(Request $request) {
        return view('frontend.about-us');
        // return view('page.about-us');
    }

    /**
     * sitemap page
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function Sitemap() {
        $category = Category::where('status', '1')->get();
        $course = Course::select(['course_name', 'slug'])->where(['status' => '1', 'is_delete' => '0'])->get();

        // return view('page.sitemap')->with(['categorys' => $category, 'courses' => $course]);
        return view('frontend.sitemap')->with(['categorys' => $category, 'courses' => $course]);
    }

    /**
     * Contact us form submit
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function ContactusForm(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'subject' => 'required',
                'name' => 'required',
                'email' => 'required',
                'mobile' => 'required|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10',
                'hear_about_us' => 'required',
                'message' => 'required',
            ]);

            $insert_data = [
                'subject' => $request->subject,
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->mobile,
                'hear_about_us' => $request->hear_about_us,
                'message' => $request->message
            ];

            Contactus::create($insert_data);

            $admin_mail = env('ADMIN_MAIL', 'info@edupme.com');
            \Mail::to($admin_mail)->send(new ContactUsEmail($insert_data));
            return ["success" => true, "message" => "<p>Thank you for getting in touch!</p><p>While we do our best to answer your queries quickly, it may take about 24-36 hours to receive a response from us.</p><p>Thanks in advance for your patience.</p><p>Have a great day!</p>"];
        }
        abort(404);
    }

    /**
     * Purchase History
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getPurchase(Request $request) {
        $user_id = Auth::user()->id;
        $payment_data = Payment::with(array('subscription' => function($query) {
                $query->select('id', 'amount_to_be_paid');
            }))->with(array('subscription.course' => function($query) {
                $query->select('course_id', 'course_name');
            }))->whereHas('subscription', function($q) {
                    $q->where('id', '!=', "");
                })->where('user_id', $user_id)->paginate(10);

        // return view('front.purchasehistory.index')->with('payment_data', $payment_data);
        return view('frontend.purchase-history')->with('payment_data', $payment_data);
    }

    /**
     * Newslatter Subscribe
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function Subscriber(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'email' => 'required|email|unique:tbl_subscriber,email',
                    ], [
                'email.required' => "Email field is required"
            ]);
            Subscriber::create(['email' => $request->email]);
            return ["success" => true, "message" => "Newsletter subscribed successfully. Thanks!"];
        }
        abort(404);
    }

}
