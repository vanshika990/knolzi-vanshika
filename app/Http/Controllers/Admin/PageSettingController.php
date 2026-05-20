<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CmsPages;
use App\Models\Options;
use Illuminate\Http\Request;
use App\Helper\DocumentUploadS3Helper;
use App\Models\Teaching;
use App\Helper\GetOptionDataHelper;

class PageSettingController extends Controller {

    /**
     * Get data of homepage settings
     *
     * @return \Illuminate\Http\Response
     */
    public function GetHomePagesettings(Request $request) {
        $data = [];
        $option_names = [
            'homepage_hero_section',
            'homepage_slogan_section',
            'homepage_sell_course_online_section',
            'homepage_digital_classroom_section',
            'homepage_blog_section',
            'footer_section'
        ];

        $page_setting_data = GetOptionDataHelper::getOptionData($option_names);

        if (!empty($page_setting_data)) {
            $board_course = $page_setting_data['homepage_hero_section']['hero_broad_selection_course'];
            $board_course_data = Category::select(['id', 'name'])->whereIn('id', $board_course)->get()->toarray();
        }
        $selected_board_course = [];
        if (!empty($board_course_data)) {
            $selected_board_course = $board_course_data;
        }

        if (!empty($page_setting_data)) {
            $data = $page_setting_data;
        }

        return view('admin.pages.homepagesetting')->with(['data' => $data, 'selected_board_course' => $selected_board_course]);
    }

    /**
     * Store data of homepage settings
     *
     * @return \Illuminate\Http\Response
     */
    public function HomePagesettingsPost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'hero_sec_title' => 'required',
                'hero_sec_btn_url' => 'required',
                'hero_sec_btn_name' => 'required',
                'hero_broad_selection_description' => 'required',
                'hero_broad_selection_course' => 'required',
                'hero_broad_selection_course.*' => 'numeric',
                'hero_sec_description' => 'required',
                'slogan_first' => 'required',
                'slogan_second' => 'required',
                'slogan_third' => 'required',
                'sell_course_online_sec_title' => 'required',
                'sell_course_online_sec_btn_name' => 'required',
                'sell_course_online_sec_btn_url' => 'required',
                'sell_course_online_sec_description' => 'required',
                'digital_sec_title' => 'required',
                'digital_sec_btn_url' => 'required',
                'digital_sec_description' => 'required',
                'blog_sec_title' => 'required',
                'blog_sec_btn_name' => 'required',
                'blog_sec_description' => 'required',
                'facebook_url' => 'required|url',
                'twitter_url' => 'required|url',
                'youtube_url' => 'required|url',
                'instagram_url' => 'required|url',
                'linkedin_url' => 'required|url',
            ]);

            // Hero section image upload
            if (empty($request->hero_sec_image)) {
                if (!empty($request->hero_sec_oldimage)) {
                    $hero_image_url = $request->hero_sec_oldimage;
                } else {
                    $request->validate([
                        'hero_sec_image' => 'required|mimes:jpg,jpeg,png|max:200'
                    ]);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            } else {
                $request->validate([
                    'hero_sec_image' => 'required|mimes:jpg,jpeg,png|max:200'
                ]);
                if (!empty($request->hero_sec_oldimage)) {
                    $old_image_remove = $request->hero_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                } else {
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            }
            $hero_sec = [
                'hero_sec_title' => $request->hero_sec_title,
                'hero_sec_btn_url' => $request->hero_sec_btn_url,
                'hero_sec_btn_name' => $request->hero_sec_btn_name,
                'hero_sec_description' => $request->hero_sec_description,
                'hero_sec_image' => $hero_image_url,
                'hero_broad_selection_description' => $request->hero_broad_selection_description,
                'hero_broad_selection_course' => $request->hero_broad_selection_course,
            ];
            $hero_encode_data = json_encode($hero_sec);
            $insert_hero_data = [
                'option_name' => 'homepage_hero_section',
                'option_value' => $hero_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'homepage_hero_section'], $insert_hero_data);

            // sell_course_online section image upload
            if (empty($request->sell_course_online_sec_image)) {
                if (!empty($request->sell_course_online_sec_oldimage)) {
                    $sell_course_online_image_url = $request->sell_course_online_sec_oldimage;
                } else {
                    $request->validate([
                        'sell_course_online_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $sell_course_online_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->sell_course_online_sec_image);
                }
            } else {
                $request->validate([
                    'sell_course_online_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->sell_course_online_sec_oldimage)) {
                    $old_image_remove = $request->sell_course_online_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $sell_course_online_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->sell_course_online_sec_image);
                } else {
                    $sell_course_online_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->sell_course_online_sec_image);
                }
            }
            $sell_course_online_sec = [
                'sell_course_online_sec_title' => $request->sell_course_online_sec_title,
                'sell_course_online_sec_btn_name' => $request->sell_course_online_sec_btn_name,
                'sell_course_online_sec_btn_url' => $request->sell_course_online_sec_btn_url,
                'sell_course_online_sec_description' => $request->sell_course_online_sec_description,
                'sell_course_online_sec_image' => $sell_course_online_image_url,
            ];
            $sell_course_online_encode_data = json_encode($sell_course_online_sec);
            $insert_sell_course_online_data = [
                'option_name' => 'homepage_sell_course_online_section',
                'option_value' => $sell_course_online_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'homepage_sell_course_online_section'], $insert_sell_course_online_data);

            // Digital section image upload
            if (empty($request->digital_sec_image)) {
                if (!empty($request->digital_sec_oldimage)) {
                    $digital_image_url = $request->digital_sec_oldimage;
                } else {
                    $request->validate([
                        'digital_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $digital_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->digital_sec_image);
                }
            } else {
                $request->validate([
                    'digital_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->digital_sec_oldimage)) {
                    $old_image_remove = $request->digital_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $digital_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->digital_sec_image);
                } else {
                    $digital_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->digital_sec_image);
                }
            }
            // Digital section insert update
            $digital_sec = [
                'digital_sec_title' => $request->digital_sec_title,
                'digital_sec_btn_name' => $request->digital_sec_btn_name,
                'digital_sec_btn_url' => $request->digital_sec_btn_url,
                'digital_sec_description' => $request->digital_sec_description,
                'digital_sec_image' => $digital_image_url,
            ];
            $digital_encode_data = json_encode($digital_sec);
            $insert_digital_data = [
                'option_name' => 'homepage_digital_classroom_section',
                'option_value' => $digital_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'homepage_digital_classroom_section'], $insert_digital_data);

            // Slogan section image upload
            if (empty($request->slogan_first_image)) {
                if (!empty($request->slogan_first_oldimage)) {
                    $slogan_first_image_url = $request->slogan_first_oldimage;
                } else {
                    $request->validate([
                        'slogan_first_image' => 'required|mimes:jpg,jpeg,png|max:200'
                    ]);
                    $slogan_first_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_first_image);
                }
            } else {
                $request->validate([
                    'slogan_first_image' => 'required|mimes:jpg,jpeg,png|max:200'
                ]);
                if (!empty($request->slogan_first_oldimage)) {
                    $old_image_remove = $request->slogan_first_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $slogan_first_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_first_image);
                } else {
                    $slogan_first_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_first_image);
                }
            }

            if (empty($request->slogan_second_image)) {
                if (!empty($request->slogan_second_oldimage)) {
                    $slogan_second_image_url = $request->slogan_second_oldimage;
                } else {
                    $request->validate([
                        'slogan_second_image' => 'required|mimes:jpg,jpeg,png|max:200'
                    ]);
                    $slogan_second_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_second_image);
                }
            } else {
                $request->validate([
                    'slogan_second_image' => 'required|mimes:jpg,jpeg,png|max:200'
                ]);
                if (!empty($request->slogan_second_oldimage)) {
                    $old_image_remove = $request->slogan_second_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $slogan_second_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_second_image);
                } else {
                    $slogan_second_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_second_image);
                }
            }

            if (empty($request->slogan_third_image)) {
                if (!empty($request->slogan_third_oldimage)) {
                    $slogan_third_image_url = $request->slogan_third_oldimage;
                } else {
                    $request->validate([
                        'slogan_third_image' => 'required|mimes:jpg,jpeg,png|max:200'
                    ]);
                    $slogan_third_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_third_image);
                }
            } else {
                $request->validate([
                    'slogan_third_image' => 'required|mimes:jpg,jpeg,png|max:200'
                ]);
                if (!empty($request->slogan_third_oldimage)) {
                    $old_image_remove = $request->slogan_third_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $slogan_third_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_third_image);
                } else {
                    $slogan_third_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->slogan_third_image);
                }
            }

            // Slogan section insert update
            $slogan_sec = [
                'slogan_first' => $request->slogan_first,
                'slogan_first_image' => $slogan_first_image_url,
                'slogan_second' => $request->slogan_second,
                'slogan_second_image' => $slogan_second_image_url,
                'slogan_third' => $request->slogan_third,
                'slogan_third_image' => $slogan_third_image_url
            ];
            $slogan_encode_data = json_encode($slogan_sec);
            $insert_slogan_data = [
                'option_name' => 'homepage_slogan_section',
                'option_value' => $slogan_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'homepage_slogan_section'], $insert_slogan_data);

            // Blog section image upload
            if (empty($request->blog_sec_image)) {
                if (!empty($request->blog_sec_oldimage)) {
                    $blog_image_url = $request->blog_sec_oldimage;
                } else {
                    $request->validate([
                        'blog_sec_image' => 'required|mimes:jpg,jpeg,png|max:200'
                    ]);
                    $blog_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->blog_sec_image);
                }
            } else {
                $request->validate([
                    'blog_sec_image' => 'required|mimes:jpg,jpeg,png|max:200'
                ]);
                if (!empty($request->blog_sec_oldimage)) {
                    $old_image_remove = $request->blog_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $blog_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->blog_sec_image);
                } else {
                    $blog_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->blog_sec_image);
                }
            }

            // Blog section insert update
            $blog_sec = [
                'blog_sec_title' => $request->blog_sec_title,
                'blog_sec_btn_name' => $request->blog_sec_btn_name,
                'blog_sec_image' => $blog_image_url,
                'blog_sec_description' => $request->blog_sec_description,
            ];
            $blog_encode_data = json_encode($blog_sec);
            $insert_blog_data = [
                'option_name' => 'homepage_blog_section',
                'option_value' => $blog_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'homepage_blog_section'], $insert_blog_data);

            // footer social media url
            $footer_sec = [
                'facebook_url' => $request->facebook_url,
                'twitter_url' => $request->twitter_url,
                'youtube_url' => $request->youtube_url,
                'instagram_url' => $request->instagram_url,
                'linkedin_url' => $request->linkedin_url,
            ];
            $footer_encode_data = json_encode($footer_sec);
            $insert_footer_data = [
                'option_name' => 'footer_section',
                'option_value' => $footer_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'footer_section'], $insert_footer_data);

            return ["success" => true, "message" => "Homepage setting save successfully"];
        }
        abort(404);
    }

    /**
     * Get data of digital classroom settings
     *
     * @return \Illuminate\Http\Response
     */
    public function GetDigitalClassroomPagesettings(Request $request) {
        $data = [];
        $option_names = [
            'digital_classroom_hero',
            'digital_classroom_how_it_work',
            'digital_classroom_teaching_cycle',
            'digital_classroom_learning_cycle',
        ];

        $page_setting_data = GetOptionDataHelper::getOptionData($option_names);
        if (!empty($page_setting_data)) {
            $data = $page_setting_data;
        }

        return view('admin.pages.digitalclassroompagesetting')->with(['data' => $data]);
    }

    /**
     * update or create data of digital classroom settings
     *
     * @return \Illuminate\Http\Response
     */
    public function DigitalClassroomPagesettingsPost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'hero_sec_title' => 'required',
                'hero_sec_description' => 'required',
                'how_it_work_sec_title' => 'required',
                'how_it_work_sec_sub_title' => 'required',
                'teaching_cycle_sec_title' => 'required',
                'teaching_cycle_sec_sub_title' => 'required',
                'learning_cycle_sec_title' => 'required',
                'learning_cycle_sec_sub_title' => 'required',
            ]);

            // Hero section
            if (empty($request->hero_sec_image)) {
                if (!empty($request->hero_sec_oldimage)) {
                    $hero_image_url = $request->hero_sec_oldimage;
                } else {
                    $request->validate([
                        'hero_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            } else {
                $request->validate([
                    'hero_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->hero_sec_oldimage)) {
                    $old_image_remove = $request->hero_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                } else {
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            }
            $hero_sec = [
                'hero_sec_title' => $request->hero_sec_title,
                'hero_sec_description' => $request->hero_sec_description,
                'hero_sec_image' => $hero_image_url,
            ];
            $hero_encode_data = json_encode($hero_sec);
            $insert_hero_data = [
                'option_name' => 'digital_classroom_hero',
                'option_value' => $hero_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'digital_classroom_hero'], $insert_hero_data);

            // How it work section
            if (empty($request->how_it_work_sec_image)) {
                if (!empty($request->how_it_work_sec_oldimage)) {
                    $how_it_work_image_url = $request->how_it_work_sec_oldimage;
                } else {
                    $request->validate([
                        'how_it_work_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $how_it_work_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->how_it_work_sec_image);
                }
            } else {
                $request->validate([
                    'how_it_work_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->how_it_work_sec_oldimage)) {
                    $old_image_remove = $request->how_it_work_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $how_it_work_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->how_it_work_sec_image);
                } else {
                    $how_it_work_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->how_it_work_sec_image);
                }
            }

            $how_it_work_sec = [
                'how_it_work_sec_title' => $request->how_it_work_sec_title,
                'how_it_work_sec_sub_title' => $request->how_it_work_sec_sub_title,
                'how_it_work_sec_image' => $how_it_work_image_url,
            ];
            $how_it_work_encode_data = json_encode($how_it_work_sec);
            $insert_how_it_work_data = [
                'option_name' => 'digital_classroom_how_it_work',
                'option_value' => $how_it_work_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'digital_classroom_how_it_work'], $insert_how_it_work_data);

            // Teaching cycle section
            if (empty($request->teaching_cycle_sec_image)) {
                if (!empty($request->teaching_cycle_sec_oldimage)) {
                    $teaching_cycle_image_url = $request->teaching_cycle_sec_oldimage;
                } else {
                    $request->validate([
                        'teaching_cycle_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $teaching_cycle_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->teaching_cycle_sec_image);
                }
            } else {
                $request->validate([
                    'teaching_cycle_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->teaching_cycle_sec_oldimage)) {
                    $old_image_remove = $request->teaching_cycle_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $teaching_cycle_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->teaching_cycle_sec_image);
                } else {
                    $teaching_cycle_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->teaching_cycle_sec_image);
                }
            }

            $teaching_cycle_sec = [
                'teaching_cycle_sec_title' => $request->teaching_cycle_sec_title,
                'teaching_cycle_sec_sub_title' => $request->teaching_cycle_sec_sub_title,
                'teaching_cycle_sec_image' => $teaching_cycle_image_url,
            ];
            $teaching_cycle_encode_data = json_encode($teaching_cycle_sec);
            $insert_teaching_cycle_data = [
                'option_name' => 'digital_classroom_teaching_cycle',
                'option_value' => $teaching_cycle_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'digital_classroom_teaching_cycle'], $insert_teaching_cycle_data);

            // Learning cycle section
            if (empty($request->learning_cycle_sec_image)) {
                if (!empty($request->learning_cycle_sec_oldimage)) {
                    $learning_cycle_image_url = $request->learning_cycle_sec_oldimage;
                } else {
                    $request->validate([
                        'learning_cycle_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $learning_cycle_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->learning_cycle_sec_image);
                }
            } else {
                $request->validate([
                    'learning_cycle_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->learning_cycle_sec_oldimage)) {
                    $old_image_remove = $request->learning_cycle_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $learning_cycle_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->learning_cycle_sec_image);
                } else {
                    $learning_cycle_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->learning_cycle_sec_image);
                }
            }

            $lerning_cycle_sec = [
                'learning_cycle_sec_title' => $request->learning_cycle_sec_title,
                'learning_cycle_sec_sub_title' => $request->learning_cycle_sec_sub_title,
                'learning_cycle_sec_image' => $learning_cycle_image_url,
            ];
            $lerning_cycle_encode_data = json_encode($lerning_cycle_sec);
            $insert_lerning_cycle_data = [
                'option_name' => 'digital_classroom_learning_cycle',
                'option_value' => $lerning_cycle_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'digital_classroom_learning_cycle'], $insert_lerning_cycle_data);

            return ["success" => true, "message" => "Digital classroom setting save successfully"];
        }
        abort(404);
    }

    /**
     * Get data of homepage settings
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetTeachingPagesettings(Request $request) {
        $data = [];
        $option_names = [
            'teachingpage_hero_section',
            'teachingpage_boost_income_section'
        ];

        $page_data = new \App\Helper\GetOptionDataHelper();
        $page_setting_data = $page_data->getOptionData($option_names);

        if (!empty($page_setting_data)) {
            $data = $page_setting_data;
            return view('admin.pages.teachingpagesetting')->with(['data' => $data]);
        }
        abort(404);
    }

    /**
     * Store data of teachingpage settings
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function TeachingPagesettingsPost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'hero_sec_title' => 'required',
                'hero_sec_btn_url' => 'required',
                'hero_sec_description' => 'required',
                'teachingpage_boost_income_sec_title' => 'required',
                'teachingpage_boost_income_sec_btnUrl' => 'required',
                'teachingpage_boost_income_sec_description' => 'required',
            ]);

            // Hero section image upload
            if (empty($request->hero_sec_image)) {
                if (!empty($request->hero_sec_oldimage)) {
                    $hero_image_url = $request->hero_sec_oldimage;
                } else {
                    $request->validate([
                        'hero_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            } else {
                $request->validate([
                    'hero_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->hero_sec_oldimage)) {
                    $old_image_remove = $request->hero_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                } else {
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            }
            $hero_sec = [
                'hero_sec_title' => $request->hero_sec_title,
                'hero_sec_btn_url' => $request->hero_sec_btn_url,
                'hero_sec_description' => $request->hero_sec_description,
                'hero_sec_image' => $hero_image_url,
            ];
            $hero_encode_data = json_encode($hero_sec);
            $insert_hero_data = [
                'option_name' => 'teachingpage_hero_section',
                'option_value' => $hero_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'teachingpage_hero_section'], $insert_hero_data);

            // boost_income section image upload
            if (empty($request->teachingpage_boost_income_sec_image)) {
                if (!empty($request->teachingpage_boost_income_sec_oldimage)) {
                    $teachingpage_boost_income_sec_online_imageUrl = $request->teachingpage_boost_income_sec_oldimage;
                } else {
                    $request->validate([
                        'teachingpage_boost_income_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $teachingpage_boost_income_sec_online_imageUrl = DocumentUploadS3Helper::uploadToBucketNew('images', $request->teachingpage_boost_income_sec_image);
                }
            } else {
                $request->validate([
                    'teachingpage_boost_income_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->teachingpage_boost_income_sec_oldimage)) {
                    $old_image_remove = $request->teachingpage_boost_income_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $teachingpage_boost_income_sec_online_imageUrl = DocumentUploadS3Helper::uploadToBucketNew('images', $request->teachingpage_boost_income_sec_image);
                } else {
                    $teachingpage_boost_income_sec_online_imageUrl = DocumentUploadS3Helper::uploadToBucketNew('images', $request->teachingpage_boost_income_sec_image);
                }
            }
            $boost_income_sec_data = [
                'teachingpage_boost_income_sec_title' => $request->teachingpage_boost_income_sec_title,
                'teachingpage_boost_income_sec_btnUrl' => $request->teachingpage_boost_income_sec_btnUrl,
                'teachingpage_boost_income_sec_description' => $request->teachingpage_boost_income_sec_description,
                'teachingpage_boost_income_sec_image' => $teachingpage_boost_income_sec_online_imageUrl,
            ];
            $boost_income_encoded_data = json_encode($boost_income_sec_data);
            $insert_boost_income_data = [
                'option_name' => 'teachingpage_boost_income_section',
                'option_value' => $boost_income_encoded_data
            ];
            Options::updateOrCreate(['option_name' => 'teachingpage_boost_income_section'], $insert_boost_income_data);

            return ["success" => true, "message" => "Teachingpage setting save successfully"];
        }
        abort(404);
    }

    /**
     * Get data of Contact us page settings
     *
     * @return \Illuminate\Http\Response
     */
    public function GetContactUsPagesettings(Request $request) {
        $data = [];
        $page_setting_data = GetOptionDataHelper::getOptionData(['contactuspage_hero_section']);
        if (!empty($page_setting_data)) {
            $data = $page_setting_data;
        }
        return view('admin.pages.contactuspagesetting')->with(['data' => $data]);
    }

    /**
     * Store data of contact us page settings
     *
     * @return \Illuminate\Http\Response
     */
    public function ContactUsPagesettingsPost(Request $request) {
        if ($request->ajax()) {
            $request->validate([
                'hero_sec_title' => 'required',
                'hero_sec_description' => 'required',
            ]);

            // Hero section image upload
            if (empty($request->hero_sec_image)) {
                if (!empty($request->hero_sec_oldimage)) {
                    $hero_image_url = $request->hero_sec_oldimage;
                } else {
                    $request->validate([
                        'hero_sec_image' => 'required|mimes:jpg,jpeg,png'
                    ]);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            } else {
                $request->validate([
                    'hero_sec_image' => 'required|mimes:jpg,jpeg,png'
                ]);
                if (!empty($request->hero_sec_oldimage)) {
                    $old_image_remove = $request->hero_sec_oldimage;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                } else {
                    $hero_image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->hero_sec_image);
                }
            }
            $hero_sec = [
                'hero_sec_title' => $request->hero_sec_title,
                'hero_sec_description' => $request->hero_sec_description,
                'hero_sec_image' => $hero_image_url,
            ];
            $hero_encode_data = json_encode($hero_sec);
            $insert_hero_data = [
                'option_name' => 'contactuspage_hero_section',
                'option_value' => $hero_encode_data
            ];
            Options::updateOrCreate(['option_name' => 'contactuspage_hero_section'], $insert_hero_data);
            return ["success" => true, "message" => "Contact-us page setting save successfully"];
        }
        abort(404);
    }

}
