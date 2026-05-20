<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use App\Models\CourseImageGallary;
use App\Models\CourseCategory;
use App\Models\RelatedCourse;
use App\Models\Usercourseattempt;
use App\Models\CourseHasUser;
use App\Models\Language;
use App\Models\CourseHasLanguage;
use Illuminate\Http\Request;
use App\DataTables\Common\ViewCourseDatatable;
use App\DataTables\Common\CoursecompleteDataTable;
use App\Mail\CompleteCourseUser;
use App\Helper\DocumentUploadS3Helper;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\DataTables\Common\QuestionDataTable;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ViewCourseDatatable $dataTable)
    {
        return $dataTable->render('admin.course.index');
    }

    public function getCourseTypes()
    {
        // Replace `your_table_name` with the actual table name
        $enumValues = DB::select(DB::raw("SHOW COLUMNS FROM tbl_course WHERE Field = 'course_type'"));

        // Extract the ENUM values
        $typeString = $enumValues[0]->Type; // Example: enum('skippable','not-skippable')
        preg_match("/^enum\((.*)\)$/", $typeString, $matches);

        // Convert ENUM values to an array
        $courseTypes = [];
        if (!empty($matches[1])) {
            $courseTypes = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));
        }

        return $courseTypes;
        // return view('your-view-name', compact('courseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $language = Language::all();
        $courseTypes = self::getCourseTypes();
        return view('admin.course.create')->with(['languages' => $language, "course_types" => $courseTypes]);
    }

    /**
     * search course author
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SearchCourseAuthor(Request $request)
    {
        $response = array();
        if (!empty($request->searchTerm)) {
            $users = User::select('id', 'name')->role(['author', 'institute'])->where('name', 'like', '%' . $request->searchTerm . '%')->get();
            if (!empty($users)) {
                foreach ($users as $row) {
                    $response[] = array(
                        "id" => $row['id'],
                        "text" => $row['name']
                    );
                }
            }
        }
        return json_encode($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'course_name' => 'required',
                'course_price' => 'required|numeric',
                'course_sub_description' => 'required',
                'course_code' => 'required',
                'course_image' => 'mimes:jpeg,jpg,png,gif|max:200',
                'user_id' => 'required',
                'user_id.*' => 'numeric',
                'course_category_id' => 'required',
                'related_course_id' => 'required',
                'course_description' => 'required',
                'course_requirement' => 'required',
                'course_applications' => 'required',
                'status' => 'required|in:0,1',
                'course_type' => 'required',
                'course_featured' => 'required|in:0,1',
                'subscription_day' => 'required|numeric|min:1',
                'course_include' => 'required',
                'course_language_id' => 'required',
                'meta_title' => 'required',
                'meta_keyword' => 'required',
                'meta_description' => 'required',
            ], [
                'user_id.required' => 'The course author field is required.',
                'course_category_id.required' => 'The category field is required.',
                'related_course_id.required' => 'The related course field is required.',
            ]);

            $courseImage = '';
            if ($request->hasFile('course_image')) {
                $courseImage = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('course_image'));
            }

            $insert = [
                'course_name' => $request['course_name'],
                'course_code' => $request['course_code'],
                'course_description' => $request['course_description'],
                'course_sub_description' => $request['course_sub_description'],
                'course_requirement' => $request['course_requirement'],
                'course_applications' => $request['course_applications'],
                'course_price' => $request['course_price'],
                'course_image' => $courseImage,
                'status' => $request['status'],
                'course_include' => $request['course_include'],
                'course_type' => $request['course_type'],
                'course_featured' => $request['course_featured'],
                'subscription_day' => $request['subscription_day'],
                'slug' => SlugService::createSlug(Course::class, 'slug', $request['course_name']),
                'meta_title' => $request['meta_title'],
                'meta_keyword' => $request['meta_keyword'],
                'meta_description' => $request['meta_description'],
            ];

            if (!empty($request->course_tag)) {
                $insert['course_tag'] = $request->course_tag;
            }

            $add_data = Course::create($insert);
            $course_id = $add_data->course_id;

            // insert course category data
            $insert_category_Data = [];
            $categories = $request['course_category_id'];
            if (!empty($categories)) {
                foreach ($categories as $key => $value) {
                    $insert_category_Data[] = [
                        'cat_id' => $value,
                        'course_id' => $course_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_category = CourseCategory::insert($insert_category_Data);
            }

            // insert related course data
            $insert_related_Data = [];
            $courses = $request['related_course_id'];
            if (!empty($courses)) {
                foreach ($courses as $key => $value) {
                    $insert_related_Data[] = [
                        'related_course_id' => $value,
                        'course_id' => $course_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_category = RelatedCourse::insert($insert_related_Data);
            }

            // insert course language data
            $insert_language_Data = [];
            $language = $request['course_language_id'];
            if (!empty($language)) {
                foreach ($language as $key => $value) {
                    $insert_language_Data[] = [
                        'course_id' => $course_id,
                        'language_id' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_language = CourseHasLanguage::insert($insert_language_Data);
            }

            // for insert course author user
            $insert_course_author = [];
            $user_id = $request['user_id'];
            if (!empty($user_id)) {
                foreach ($user_id as $key => $value) {
                    $insert_course_author[] = [
                        'course_id' => $course_id,
                        'user_id' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $insert_course_author = CourseHasUser::insert($insert_course_author);
            }

            // insert image gallary data
            $insert_imageGallery_Data = [];
            if (!empty($imgGallary)) {
                $i = 0;
                foreach ($imgGallary as $key => $value) {
                    $insert_imageGallery_Data[] = [
                        'course_id' => $course_id,
                        'image_path' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_image = CourseImageGallary::insert($insert_imageGallery_Data);
            }

            return ["success" => true, "message" => "Course created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = decrypt($id);
            $course = Course::where('course_id', $id)->first();
            $category = Category::all();
            $course_category_array = CourseCategory::where('course_id', $id)->get();
            $array = $course_category_array->toarray();
            $course_category = array_column($array, 'cat_id');
            $course_image = CourseImageGallary::where('course_id', $id)->get();
            $user = CourseHasUser::with('user')->where('course_id', $id)->get();
            $course_language = CourseHasLanguage::where('course_id', $id)->with('course_language')->get();

            $user_data = [];
            foreach ($user as $key => $value) {
                $user_data[] = $value->user->name . '(' . $value->user->getRoleNames()->first() . ')';
            }

            return view('admin.course.show')->with(['course' => $course, 'user' => $user_data, 'categories' => $category, 'course_category' => $course_category, 'imgGallary' => $course_image, 'course_language' => $course_language]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $id = decrypt($id);
        $course = Course::where('course_id', $id)->first();
        $course_image = CourseImageGallary::where('course_id', $id)->get();

        $course_category = CourseCategory::with('category')->where('course_id', $id)->get();
        $course_category_data = [];
        foreach ($course_category as $key => $value) {
            $course_category_data[] = [
                'id' => $value['category']['id'],
                'name' => $value['category']['name'],
            ];
        }

        $enumValues = DB::select(DB::raw("SHOW COLUMNS FROM tbl_course WHERE Field = 'course_type'"));
        preg_match("/^enum\((.*)\)$/", $enumValues[0]->Type, $matches);
        $courseTypes = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        $related_course = RelatedCourse::with('course')->where('course_id', $id)->get();
        $related_course_data = [];
        foreach ($related_course as $key => $value) {
            $related_course_data[] = [
                'id' => $value['course']['course_id'],
                'name' => $value['course']['course_name'],
            ];
        }


        $course_author = CourseHasUser::with('user')->where('course_id', $id)->get()->toArray();
        $course_author_data = [];
        foreach ($course_author as $key => $value) {
            $course_author_data[] = [
                'id' => $value['user']['id'],
                'name' => $value['user']['name'],
            ];
        }
        $language = Language::all();
        $course_language = CourseHasLanguage::select('language_id')->where('course_id', $id)->get()->toarray();
        $course_languages = array_column($course_language, 'language_id');
        return view('admin.course.edit')->with(['course' => $course, 'course_category' => $course_category_data, 'related_course' => $related_course_data, 'imgGallary' => $course_image, 'course_author' => $course_author_data, 'languages' => $language, 'course_language' => $course_languages, "course_types" => $courseTypes]);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return type
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'course_name' => 'required',
                'course_code' => 'required',
                'course_description' => 'required',
                'course_requirement' => 'required',
                'course_applications' => 'required',
                'course_price' => 'required',
                'course_type' => 'required',
                'course_slug' => 'required',
                'status' => 'required|in:0,1',
                'user_id' => 'required',
                'user_id.*' => 'numeric',
                'course_featured' => 'required|in:0,1',
                'course_include' => 'required',
                'course_language_id' => 'required',
                'subscription_day' => 'required|numeric|min:1',
                //                'meta_title' => 'required',
//                'meta_keyword' => 'required',
//                'meta_description' => 'required',
            ], [
                'user_id.required' => 'The course author field is required.',
            ]);

            $courseImage = '';
            if (!empty($request->hasFile('course_image'))) {
                $request->validate([
                    'course_image' => 'mimes:jpeg,jpg,png,gif|max:200',
                ]);
                $courseData = Course::where('course_id', $id)->first();
                $deleteToBucket = DocumentUploadS3Helper::deleteToBucket($courseData->course_image);
                $courseImage = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('course_image'));
            }

            $imgGallary = [];
            if ($request->hasFile('files')) {
                $imgGallary = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('files'));
            }

            if (!empty(trim($request->oldImageRemove))) {
                $arr = explode(',', $request->oldImageRemove);
                $imgPath = CourseImageGallary::whereIn('id', $arr)->get();
                $deleteToBucketArray = [];
                foreach ($imgPath as $imgs) {
                    $deleteToBucketArray[] = $imgs->image_path;
                }
                $deleteToBucket = DocumentUploadS3Helper::deleteToBucket($deleteToBucketArray);
                CourseImageGallary::whereIn('id', $arr)->delete();
            }
            // update course
            $updateData = [
                'course_name' => $request['course_name'],
                'course_code' => $request['course_code'],
                'course_description' => $request['course_description'],
                'course_sub_description' => $request['course_sub_description'],
                'course_requirement' => $request['course_requirement'],
                'course_applications' => $request['course_applications'],
                'course_price' => $request['course_price'],
                'course_type' => $request['course_type'],
                'status' => $request['status'],
                'course_include' => $request['course_include'],
                'course_featured' => $request['course_featured'],
                'subscription_day' => $request['subscription_day'],
                'slug' => $request['course_slug'],
                'course_tag' => $request->course_tag,
                'meta_title' => $request->meta_title,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description,
            ];
            if (!empty(trim($courseImage))) {
                $updateData['course_image'] = $courseImage;
            }

            $update_data = Course::where('course_id', $id)->update($updateData);

            // delete category
            $category = CourseCategory::where('course_id', $id)->delete();
            $update_coursecategory_Data = [];

            $categories = $request['course_category_id'];
            if (!empty($categories)) {
                $i = 0;
                foreach ($categories as $key => $value) {
                    $update_coursecategory_Data[] = [
                        'cat_id' => $value,
                        'course_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_category = CourseCategory::insert($update_coursecategory_Data);
            }

            // delete related courses
            $courses = RelatedCourse::where('course_id', $id)->delete();
            $update_relatedcourse_Data = [];
            $related_course = $request['related_course_id'];
            if (!empty($related_course)) {
                $i = 0;
                foreach ($related_course as $key => $value) {
                    $update_relatedcourse_Data[] = [
                        'related_course_id' => $value,
                        'course_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_related_course = RelatedCourse::insert($update_relatedcourse_Data);
            }

            // delete language
            $language = CourseHasLanguage::where('course_id', $id)->delete();
            $update_course_language_Data = [];
            $language = $request['course_language_id'];
            if (!empty($language)) {
                $i = 0;
                foreach ($language as $key => $value) {
                    $update_course_language_Data[] = [
                        'course_id' => $id,
                        'language_id' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                CourseHasLanguage::insert($update_course_language_Data);
            }

            // delete old course author
            $course_author = CourseHasUser::where('course_id', $id)->delete();
            // add new course author
            $insert_course_author_user = [];
            $course_author_user = $request['user_id'];
            if (!empty($course_author_user)) {
                foreach ($course_author_user as $key => $value) {
                    $insert_course_author_user[] = [
                        'user_id' => $value,
                        'course_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_course_user = CourseHasUser::insert($insert_course_author_user);
            }

            $update_imgGallery = [];
            if (!empty($imgGallary)) {
                foreach ($imgGallary as $key => $value) {
                    $update_imgGallery[] = [
                        'course_id' => $id,
                        'image_path' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $add_image = CourseImageGallary::insert($update_imgGallery);
            }

            return ["success" => true, "message" => "Course updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = decrypt($id);
            $course_data = Course::find($id);
            DocumentUploadS3Helper::deleteToBucket($course_data['course_image']);
            Course::where('course_id', $id)->delete();
            CourseCategory::where('course_id', $id)->delete();
            CourseHasUser::where('course_id', $id)->delete();
            return ["success" => true, "message" => "Course deleted successfully"];
        }
        abort(404);
    }

    /**
     * Update Course Status
     * @param  \App\Models\Course  $course
     * @param \Illuminate\Http\Request $request
     */
    public function coursechangestatus(Request $request)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'id' => 'required',
            ]);
            $course_id = decrypt($request->id);
            $coursedetail = Course::find($course_id);
            $label = "published";
            if ($coursedetail->status == 1) {
                $status = '0';
                $label = "unpublished ";
            }
            if ($coursedetail->status == 0) {
                $status = '1';
            }
            $data = [];
            $data['status'] = $status;
            $coursedetail->update($data);
            return ["success" => true, "message" => "Course $label successfully."];
        }
        abort(404);
    }

    /**
     * fetch auto complete user
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function ajaxUser(Request $request)
    {
        if ($request->ajax()) {
            if (!isset($request->name)) {
                $users = User::role('individual')->orderBy('name', 'asc')->get();
            } else {
                $users = User::role('individual')->where('name', 'LIKE', "%{$request->name}%")->get();
            }
            $data = array();
            foreach ($users as $row) {
                $data[] = array("id" => $row['id'], "name" => $row['name']);
            }
            return json_encode($data);
        }
    }

    /**
     * Complete Course List Of User
     * @param \Illuminate\Http\Request $request
     * @param \App\DataTables\Common\CoursecompleteDataTable $dataTable
     * @return type
     */
    public function CompleteCourse(Request $request, CoursecompleteDataTable $dataTable)
    {
        return $dataTable->render('admin.course.completecourse');
    }

    /**
     * After Complete Course Send Email to user
     * @param \Illuminate\Http\Request $request
     */
    public function CompleteCoursemailsend(Request $request)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'email' => 'required',
                'certificate' => 'mimes:pdf'
            ]);

            $data = [
                'user_email' => $request->email,
                'certificate' => $request->certificate,
            ];
            \Mail::to($request->email)->send(new CompleteCourseUser($data));
            Usercourseattempt::where('course_id', $request->course_id)->where('user_id', $request->user_id)->where('state', 'complete')->update(['is_mail_send' => '1']);
            return ["success" => true, "message" => "Mail send successfully"];
        }
        abort(404);
    }

}
