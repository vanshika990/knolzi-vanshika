<?php

die("hh");
ini_set('max_execution_time ', '-1');
ini_set('memory_limit ', '1024M');
$link = mysqli_connect("edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com", "edupmedbroot", "meedup23$%", "edupme_prod") or die(mysqli_connect_error());
$link2 = mysqli_connect("edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com", "edupmedbroot", "meedup23$%", "edupme_devapp_migration") or die(mysqli_connect_error());

//$query_course_data = "SELECT * FROM tbl_course";
//$result_course_data = query2($query_course_data);
//foreach ($result_course_data as $row) {
//    try {
//        for ($i = 0; $i <= 10; $i++) {
//            $randIP = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
////        echo 'INSERT INTO `tbl_course_views`(`course_id`,`ip`,`created_at`,`updated_at`) VALUES(' . $row['course_id'] . ',"' . $randIP . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '"'; die;
////        mysqli_query($link2, 'INSERT INTO `tbl_course_views`(`course_id`,`ip`,`created_at`,`updated_at`) VALUES(' . $row['course_id'] . ',"' . $randIP . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
//
//            mysqli_query($link2, 'INSERT INTO `tbl_course_has_review_rate`(`course_id`,`user_id`,`rate`,`created_at`,`updated_at`) VALUES(' . $row['course_id'] . ',"' . $i . '",5,"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
//        }
//    } catch (Exception $e) {
//        
//    }
//}
//die("gg");

function query($q) {
    global $link;
    $sq = $q;
    $sq_res = mysqli_query($link, $sq) or die(mysqli_error($link));
    $sq_row = array();
    while ($sq_row1 = mysqli_fetch_assoc($sq_res)) {
        $sq_row[] = $sq_row1;
    }
    return $sq_row;
}

function query2($q) {
    global $link2;
    $sq = $q;
    $sq_res = mysqli_query($link2, $sq) or die(mysqli_error($link2));
    $sq_row = array();
    while ($sq_row1 = mysqli_fetch_assoc($sq_res)) {
        $sq_row[] = $sq_row1;
    }
    return $sq_row;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo "<pre>";
mysqli_query($link2, 'SET FOREIGN_KEY_CHECKS=0') or die(mysqli_error($link2));

/* * *********************User ************************* */

//$sq_user = "SELECT * FROM tbl_user group by email";
//$result_user = query($sq_user);
////echo count($result_user); die;
//if (!empty($result_user)) {
//    mysqli_query($link2, 'delete from tbl_user');
//    mysqli_query($link2, 'delete from tbl_model_has_roles');
//    foreach ($result_user as $row) {
////        print_r($row); die;
//        $id = $row['id'];
//        $name = $row['name'];
//        $email = $row['email'];
//        $mobile_no = $row['mobile_no'];
//        $age_group = $row['age_group'];
//        $skillstest = $row['skillstest'];
//        $goal = $row['goal'];
//        $time_frame = $row['time_frame'];
//        $profile_image = $row['profile_image'];
//        $company_address = $row['company_address'];
//        $company_code = $row['company_code'];
//        $email_verified_at = $row['email_verified_at'];
//        $password = $row['password'];
//        $remember_token = $row['remember_token'];
//        $status = $row['status'];
//        $source_from = $row['source_from'];
//        $last_login_time = $row['last_login_time'];
//        $created_at = $row['created_at'];
//        $updated_at = $row['updated_at'];
//        $model_type = mysqli_real_escape_string($link2, "App\Models\User");
//        mysqli_query($link2, 'INSERT INTO `tbl_user`(`id`, `name`,`email`,`mobile_no`,`age_group`,`skillstest`,`goal`,`time_frame`,`profile_image`,`address`,`company_code`,`email_verified_at`,`password`,`remember_token`,`status`,`source_from`,`last_login_time`,`created_at`,`updated_at`) VALUES(' . $id . ',"' . $name . '","' . $email . '","' . $mobile_no . '","' . $age_group . '","' . $skillstest . '","' . $goal . '","' . $time_frame . '","' . $profile_image . '","' . $company_address . '","' . $company_code . '","' . $email_verified_at . '","' . $password . '","' . $remember_token . '","' . $status . '","' . $source_from . '","' . $last_login_time . '","' . $created_at . '","' . $updated_at . '")') or die(mysqli_error($link2));
//        mysqli_query($link2, 'INSERT INTO `tbl_model_has_roles`(`role_id`, `model_type`,`model_id`) VALUES(2,"' . $model_type . '",' . $link2->insert_id . ')') or die(mysqli_error($link2));
//    }
//}
//$sq_pro_qualification = "SELECT * FROM tbl_pro_qualification";
//$result_pro_qualification = query($sq_pro_qualification);
//if (!empty($result_pro_qualification)) {
//    mysqli_query($link2, 'delete from tbl_pro_qualification');
//    foreach ($result_pro_qualification as $row) {
//        $user_id = $row['user_id'];
//        $company_name = $row['company_name'];
//        $domain = $row['domain'];
//        $role = $row['role'];
//        $designation = $row['designation'];
//        $year = $row['year'];
//        $experience = $row['experience'];
//        $created_at = $row['created_at'];
//        $updated_at = $row['updated_at'];
//        mysqli_query($link2, 'INSERT INTO `tbl_pro_qualification`(`user_id`, `company_name`,`domain`,`role`,`designation`,`year`,`experience`,`created_at`,`updated_at`)'
//                        . ' VALUES("' . $user_id . '","' . $company_name . '","' . $domain . '","' . $role . '","' . $designation . '","' . $year . '","' . $experience . '","' . $created_at . '","' . $updated_at . '")') or die(mysqli_error($link2));
//    }
//}
//
//$sq_edu_qualification = "SELECT * FROM tbl_edu_qualification";
//$result_edu_qualification = query($sq_edu_qualification);
//if (!empty($result_edu_qualification)) {
//    mysqli_query($link2, 'delete from tbl_edu_qualification');
//    foreach ($result_edu_qualification as $row) {
//        $user_id = $row['user_id'];
//        $degree = $row['degree'];
//        $university = $row['university'];
//        $institute = $row['institute'];
//        $stream = $row['stream'];
//        $year = $row['year'];
//        $grade = $row['grade'];
//        $created_at = $row['created_at'];
//        $updated_at = $row['updated_at'];
//        mysqli_query($link2, 'INSERT INTO `tbl_edu_qualification`(`user_id`, `degree`,`university`,`institute`,`stream`,`year`,`grade`,`created_at`,`updated_at`)'
//                        . ' VALUES("' . $user_id . '","' . $degree . '","' . $university . '","' . $institute . '","' . $stream . '","' . $year . '","' . $grade . '","' . $created_at . '","' . $updated_at . '")') or die(mysqli_error($link2));
//    }
//}
//
///* * *********************Category ************************* */
//
//
//$sq_category = "SELECT * FROM tbl_course_category";
//$result_category = query($sq_category);
//
//if (!empty($result_category)) {
//    mysqli_query($link2, 'delete from tbl_course_category');
//    foreach ($result_category as $row) {
//        $id = $row['id'];
//        $name = $row['name'];
//        $category_order = $row['category_order'];
//        $parent_id = $row['parent_id'];
//        $status = $row['status'];
//        $created_at = $row['created_at'];
//        $updated_at = $row['updated_at'];
//        $slug = str_replace(array(" ", ":", ";"), array("-", "", ""), strtolower($row['name']));
//        mysqli_query($link2, 'INSERT INTO `tbl_course_category`(`id`, `name`,`parent_id`,`slug`,`status`,`created_at`,`updated_at`) VALUES(' . $id . ',"' . $name . '",' . $parent_id . ',"' . $slug . '","' . $status . '","' . $created_at . '","' . $updated_at . '")') or die(mysqli_error($link2));
//    }
//}
//$sq_course_intent = "SELECT * FROM tbl_course_intent";
//$result_course_intent = query($sq_course_intent);
//
//if (!empty($result_course_intent)) {
//    mysqli_query($link2, 'delete from tbl_question_intent') or die(mysqli_error($link2));
//
//    foreach ($result_course_intent as $row) {
//        $id = $row['id'];
//        $name = $row['name'];
//        mysqli_query($link2, 'INSERT INTO `tbl_question_intent`(`id`,`name`,`created_at`,`updated_at`) VALUES(' . $id . ',"' . $name . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
//    }
//}
//
//
///* * ********************* insert course data ************************* */
//
//$query_course_data = "SELECT * FROM tbl_course";
//$result_course_data = query($query_course_data);
//if (!empty($result_course_data)) {
//    mysqli_query($link2, 'delete from tbl_course') or die(mysqli_error($link2));
//    mysqli_query($link2, 'delete from tbl_has_course_category') or die(mysqli_error($link2));
//    mysqli_query($link2, 'delete from tbl_course_has_language') or die(mysqli_error($link2));
//    mysqli_query($link2, 'delete from tbl_course_has_user') or die(mysqli_error($link2));
//
//    foreach ($result_course_data as $row) {
//        $course_id = $row['course_id'];
//        $course_code = mysqli_real_escape_string($link2, $row['course_id_number']);
//        $course_name = mysqli_real_escape_string($link2, $row['course_full_name']);
//        $course_description = mysqli_real_escape_string($link2, $row['course_description']);
//        $course_image = mysqli_real_escape_string($link2, $row['course_image']);
//        $course_applications = mysqli_real_escape_string($link2, $row['course_applications']);
//        $course_price = mysqli_real_escape_string($link2, $row['course_price']);
//        $status = mysqli_real_escape_string($link2, $row['status']);
//        $seo_title = mysqli_real_escape_string($link2, $row['course_full_name']);
//        $seo_course_description = mysqli_real_escape_string($link2, strip_tags($row['course_description']));
//        $is_delete = mysqli_real_escape_string($link2, $row['is_delete']);
//        if ($is_delete != "1" || $is_delete != 1) {
//            $created_at = mysqli_real_escape_string($link2, $row['created_at']);
//            $updated_at = mysqli_real_escape_string($link2, $row['updated_at']);
//            $slug = str_replace(array(" ", ":", ";"), array("-", "", ""), strtolower($row['course_full_name']));
//            if (!empty($row['course_category_id'])) {
//                $cat_id = $row['course_category_id'];
//                $course_id = $row['course_id'];
//                mysqli_query($link2, 'INSERT INTO `tbl_course`(`course_id`,`course_code`,`course_name`,`slug`,`meta_title`,`meta_description`,`course_description`,`course_image`,`course_applications`,`course_price`,`status`,`is_delete`,`created_at`,`updated_at`) VALUES(' . $course_id . ',' . $course_code . ',"' . $course_name . '","' . $slug . '","' . $seo_title . '","' . $seo_course_description . '","' . $course_description . '","' . $course_image . '","' . $course_applications . '",' . $course_price . ',"' . $status . '","' . $is_delete . '","' . $updated_at . '","' . $created_at . '")') or die(mysqli_error($link2));
//                mysqli_query($link2, 'INSERT INTO `tbl_has_course_category`(`cat_id`,`course_id`,`created_at`,`updated_at`) VALUES(' . $cat_id . ',' . $course_id . ',"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
//                mysqli_query($link2, 'INSERT INTO `tbl_course_has_language`(`course_id`,`language_id`,`created_at`,`updated_at`) VALUES(' . $course_id . ',1,"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
//                mysqli_query($link2, 'INSERT INTO `tbl_course_has_user`(`course_id`,`user_id`,`created_at`,`updated_at`) VALUES(' . $course_id . ',1,"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
//            }
//        }
//    }
//}
//die("oook");
/* * ********************* EOF insert course data ************************* */

/* * ********************* insert question data ************************* */

$query_question_data = "SELECT * FROM tbl_course_question group by id LIMIT 4000,1000";
$result_question_data = query($query_question_data);
if (!empty($result_question_data)) {
//    mysqli_query($link2, 'delete from tbl_question_has_image_help') or die(mysqli_error($link2));
//    mysqli_query($link2, 'delete from tbl_course_question') or die(mysqli_error($link2));
//    mysqli_query($link2, 'delete from tbl_question_answer') or die(mysqli_error($link2));
//    mysqli_query($link2, 'delete from tbl_question_has_help') or die(mysqli_error($link2));
    foreach ($result_question_data as $row) {
        $question_id = mysqli_real_escape_string($link2, $row['id']);
        $course_id = mysqli_real_escape_string($link2, $row['course_id']);
        $question_name = mysqli_real_escape_string($link2, $row['question_name']);
        $question_desc = mysqli_real_escape_string($link2, $row['question_desc']);
        $question_type = mysqli_real_escape_string($link2, $row['question_type']);
        $question_media_type = mysqli_real_escape_string($link2, $row['question_media_type']);
        $question_media = mysqli_real_escape_string($link2, $row['question_media']);
        $que_level = mysqli_real_escape_string($link2, $row['que_level']);
        $que_toc_no = mysqli_real_escape_string($link2, $row['que_toc_no']);
        $correct_question_ans = mysqli_real_escape_string($link2, $row['correct_question_ans']);
        $question_intent_id = mysqli_real_escape_string($link2, $row['question_intent_id']);
        $status = mysqli_real_escape_string($link2, $row['status']);
        $is_delete = mysqli_real_escape_string($link2, $row['is_delete']);
        $created_at = mysqli_real_escape_string($link2, $row['created_at']);
        $updated_at = mysqli_real_escape_string($link2, $row['updated_at']);
        if ($question_media_type == 'html') {

            $query_media_help = "SELECT * FROM tbl_question_media_help WHERE question_id = $question_id";
            $result_media_help = query($query_media_help);
            if (!empty($result_media_help)) {
                $question_media = mysqli_real_escape_string($link2, $result_media_help[0]['media_url']);
            }
        }
        $question_media_multi = "";
        if ($question_media_type == 'multi') {
            $query_media_help = "SELECT * FROM tbl_question_media_help WHERE question_id = $question_id order by `order` asc";
            $result_media_help = query($query_media_help);
            if (!empty($result_media_help)) {
                $multi_media = [];
                foreach ($result_media_help as $row) {
                    $multi_media[] = $row['media_url'];
                }
                $question_media_multi = implode(',', $multi_media);
            }
        }
        mysqli_query($link2, 'INSERT INTO `tbl_course_question`(`id`, `course_id`,`question_name`, `question_desc`, `question_type`, `question_media_type`, `question_media`,`question_media_multi`, `que_level`, `que_toc_no`,`correct_question_ans`, `question_intent_id`, `status`, `is_delete`, `created_at`, `updated_at`) '
                        . 'VALUES(' . $question_id . ',' . $course_id . ',"' . $question_name . '","' . $question_desc . '","' . $question_type . '","' . $question_media_type . '","' . $question_media . '","' . $question_media_multi . '","' . $que_level . '","' . $que_toc_no . '",' . $correct_question_ans . ',"' . $question_intent_id . '","' . $status . '","' . $is_delete . '","' . $created_at . '","' . $updated_at . '")') or die(mysqli_error($link2));

        $query_question_answer_data = "SELECT * FROM tbl_question_answer WHERE question_id = $question_id";
        $result_question_answer_data = query($query_question_answer_data);
        if (!empty($result_question_answer_data)) {
            foreach ($result_question_answer_data as $que_ans) {
                $question_id = mysqli_real_escape_string($link2, $que_ans['question_id']);
                $answer_name = mysqli_real_escape_string($link2, $que_ans['answer_name']);
                $answer_order = mysqli_real_escape_string($link2, $que_ans['answer_order']);
                $choice_type = mysqli_real_escape_string($link2, $que_ans['choice_type']);
                $created_at = mysqli_real_escape_string($link2, $que_ans['created_at']);
                $updated_at = mysqli_real_escape_string($link2, $que_ans['updated_at']);
                mysqli_query($link2, 'INSERT INTO `tbl_question_answer`(`question_id`,`answer_name`, `answer_order`,  `choice_type`, `created_at`, `updated_at`) VALUES(' . $question_id . ',"' . $answer_name . '","' . $answer_order . '","' . $choice_type . '","' . $created_at . '","' . $updated_at . '")') or die(mysqli_error($link2));
            }
        }
        $query_question_data_help = "SELECT * FROM tbl_question_help WHERE question_id = $question_id";
        $result_question_data_help = query($query_question_data_help);
        if (!empty($result_question_data_help)) {
            foreach ($result_question_data_help as $row) {
                $question_id = mysqli_real_escape_string($link2, $row['question_id']);
                $image = mysqli_real_escape_string($link2, $row['image']);
                $video_help = mysqli_real_escape_string($link2, $row['video_help']);
                if ($image != "") {
                    mysqli_query($link2, 'INSERT INTO `tbl_question_has_image_help`(`question_id`,`image`, `created_at`, `updated_at`) VALUES(' . $question_id . ',"' . $image . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
                }
                if ($video_help != "") {
                    $video_type = 0;
                    if (strpos($video_help, 'www.youtube.com') !== false) {
                        $video_type = 1;
                    }
                    mysqli_query($link2, 'INSERT INTO `tbl_question_has_help`(`question_id`,`video`, `video_type`,`created_at`, `updated_at`) VALUES(' . $question_id . ',"' . $video_help . '","' . $video_type . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
                }
            }
        }

        $query_question_data_help = "SELECT * FROM tbl_question_data_help WHERE question_id = $question_id";
        $result_question_data_help = query($query_question_data_help);
        if (!empty($result_question_data_help)) {
            foreach ($result_question_data_help as $row) {
                $question_id = $row['question_id'];
                $image = $row['media_url'];
                $created_at = $row['created_at'];
                $updated_at = $row['updated_at'];
                mysqli_query($link2, 'INSERT INTO `tbl_question_has_image_help`(`question_id`,`image`, `created_at`, `updated_at`) VALUES(' . $question_id . ',"' . $image . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")') or die(mysqli_error($link2));
            }
        }
    }
}

/* * ********************* EOF question course data ************************* */
die("sss");
