<?php

namespace App\Http\Controllers\Front\Course;

use Auth;
use Illuminate\Http\Request;
use App\DataTables\Front\GetAuthorCreatedCourseDataTable;
use App\DataTables\Front\GetCourseUserDetailDataTable;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Language;
use App\Models\Category;
use App\Models\User;
use App\Models\CourseImageGallary;
use App\Models\CourseCategory;
use App\Models\RelatedCourse;
use App\Models\Usercourseattempt;
use App\Models\CourseHasUser;
use App\Models\InstituteHasAuthor;
use App\Models\CourseHasLanguage;
use App\Helper\DocumentUploadS3Helper;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{

    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetAuthorCreatedCourseDataTable $dataTable)
    {
        if ($this->user->can('view-my-course')) {
            return $dataTable->render('front.author.course.index');
        }
        abort(403);
    }

    public function dashboard()
    {
        // $language = Language::all();
        // // $courseID = "171";
        // $finished_course_students = DB::table('tbl_user_course_attempt as uca')
        //     ->join('tbl_user as u', 'uca.user_id', '=', 'u.id')
        //     ->join('tbl_course as c', 'uca.course_id', '=', 'c.course_id')
        //     ->select('c.course_id', 'c.course_name', DB::raw('COUNT(DISTINCT u.id) as student_count'))
        //     ->where('uca.state', 'complete')
        //     ->groupBy('c.course_id', 'c.course_name')
        //     ->get();

        // $courses = DB::table('tbl_course')
        //     ->join('tbl_user_course_attempt', 'tbl_course.id', '=', 'tbl_user_course_attempt.course_id')
        //     ->select('tbl_course.id as course_id', 'tbl_course.name as course_name', DB::raw('COUNT(DISTINCT tbl_user_course_attempt.user_id) as completed_count'))
        //     ->where('tbl_user_course_attempt.state', 'complete')
        //     ->groupBy('tbl_course.id', 'tbl_course.name')
        //     ->get();

        // // Step 2: Find the first attempt ID for each student who completed each course
        // foreach ($courses as $course) {
        //     $userIDs = DB::table('tbl_user_course_attempt')
        //         ->where('course_id', $course->course_id)
        //         ->where('state', 'complete')
        //         ->pluck('user_id')
        //         ->unique();

        //     $firstAttempts = [];
        //     foreach ($userIDs as $userID) {
        //         $minID = DB::table('tbl_user_course_attempt')
        //             ->where('course_id', $course->course_id)
        //             ->where('user_id', $userID)
        //             ->where('state', 'complete')
        //             ->min('id');

        //         $firstAttempts[] = [
        //             'user_id' => $userID,
        //             'min_id' => $minID
        //         ];
        //     }

        //     // Add first attempts to course object
        //     $course->first_attempts = $firstAttempts;
        // }
        if (!$this->user->can('author-dashboard')) {
            abort(403);
        }
        $courses = DB::table('tbl_course')->get();

        $id = auth()->user()->id;

        // Fetch course IDs for the logged-in user
        $userCourseIds = CourseHasUser::where('user_id', $id)
            ->pluck('course_id'); // Get only the course_id as a collection

        $completedStudentsQuery = DB::table('tbl_user_course_attempt as uca')
            ->select('uca.course_id', DB::raw('COUNT(DISTINCT uca.user_id) AS completed_students_count'))
            ->where('uca.state', 'complete')
            ->whereIn('uca.course_id', $userCourseIds) // Filter by user's courses
            ->groupBy('uca.course_id');

        // Subquery for subscribed students
        $subscribedStudentsQuery = DB::table('tbl_course_subscription as cs')
            ->join('tbl_payment as tp', 'cs.payment_id', '=', 'tp.id')
            ->where('tp.payment_status', '=', 'success')
            ->whereIn('cs.course_id', $userCourseIds) // Filter by user's courses
            ->select('cs.course_id', DB::raw('COUNT(DISTINCT cs.user_id) AS subscribed_students_count'))
            ->groupBy('cs.course_id');

        // Main query combining both subqueries
        $statistics = DB::table('tbl_course as c')
            ->leftJoinSub($subscribedStudentsQuery, 'subscribed', function ($join) {
                $join->on('c.course_id', '=', 'subscribed.course_id');
            })
            ->leftJoinSub($completedStudentsQuery, 'completed', function ($join) {
                $join->on('c.course_id', '=', 'completed.course_id');
            })
            ->select(
                'c.course_id as course_id',
                'c.course_name as course_name',
                DB::raw('COALESCE(subscribed.subscribed_students_count, 0) AS subscribed_students_count'),
                DB::raw('COALESCE(completed.completed_students_count, 0) AS completed_students_count')
            )
            ->whereIn('c.course_id', $userCourseIds) // Filter by user's courses
            ->orderBy('course_name')
            ->get();

        return view('front.author.course.dashboard', compact('courses', 'statistics'));
    }

    // CourseController.php
    public function showStatistics($courseId)
    {
        // dd($courseId);
        // Fetch the statistics
        if (!$this->user->can('author-course-statistics')) {
            abort(403);
        }
        $statistics = DB::table('tbl_user')
            ->select('id', 'name', 'email', 'mobile_no')
            ->whereIn('id', function ($query) use ($courseId) {
                $query->select('user_id')
                    ->from('tbl_user_course_attempt')
                    ->where('course_id', $courseId)
                    ->where('state', 'complete');
            })
            ->get();


        $userIds = $statistics->pluck('id');
        $userEmail = $statistics->pluck('email');
        $userName = $statistics->pluck('name');
        $userPhone = $statistics->pluck('mobile_no');

        // dd($userEmail);

        $firstAttempts = [];
        foreach ($userIds as $userID) {
            $minID = DB::table('tbl_user_course_attempt')
                ->where('course_id', $courseId)
                ->where('user_id', $userID)
                ->where('state', 'complete')
                ->min('id');

            $firstAttempts[] = [
                'user_id' => $userID,
                'min_id' => $minID
            ];
        }

        // Get course name
        $course = DB::table('tbl_course')
            ->where('course_id', $courseId)
            ->first();
        $course->first_attempts = $firstAttempts;
        // dd($course->first_attempts);
        $i = 0;
        $percentages = [];

        $total_ques_query = DB::select("
                SELECT count(*) as nTotal_Questions
                FROM tbl_course_question
                WHERE status='1' AND is_delete='0' AND course_id=?

            ", [$courseId]);

        $total_questions = $total_ques_query[0]->nTotal_Questions ?? 0;

        foreach ($userIds as $user_id) {
            $firstAttempt1 = $course->first_attempts[$i];
            $userIDComplete1 = $userIds[$i];
            $i++;

            $query = "
            SELECT cast(count(id)/ ? *100 as decimal (3,1)) AS Percent
            FROM tbl_user_question_attempt_history
            WHERE tbl_user_question_attempt_history.course_id = ?
            AND tbl_user_question_attempt_history.user_id = ?
            AND tbl_user_question_attempt_history.rightanswer = '1'
            AND course_attempt_id = ?
        ";
            $results = DB::select($query, [$total_questions, $courseId, $userIDComplete1, $firstAttempt1['min_id']]);
            foreach ($results as $data) {
                $percentages[] = $data->Percent;
            }
        }

        // dd($percentages);

        $i = 0;

        $br = 0; // Bright student count
        $av = 0; // Average performance
        $fk = 0; // Did not pay attention
        $sw = 0; // Need teacher's attention the most
        $pr = 0; // Perseverant but cannot understand the subject

        $applWeak = 0;
        $anaWeak = 0;

        $data = [
            'weakAppl' => [],
            'weakAna' => [],
            'emailBright' => [],
            'emailAverage' => [],
            'emailLackAt' => [],
            'emailSlow' => [],
            'emailPer' => [],
            'timeTaken' => []
        ];

        foreach ($userIds as $user_id) {

            $firstAttempt1 = $firstAttempts[$i];
            $userIDComplete1 = $userIds[$i];

            $query6 = DB::select("
                SELECT count(*) as nTotal_Understand
                FROM tbl_course_question
                WHERE status='1' AND is_delete='0' AND course_id=? AND question_intent_id LIKE '%4%'
            ", [$courseId]);

            $query7 = DB::select("
            SELECT count(id) AS nWrong_Understand
            FROM tbl_course_question
            WHERE course_id=? AND status='1' AND is_delete='0' AND question_intent_id LIKE '%4%' AND id IN (
                SELECT question_id
                FROM tbl_user_question_attempt_history
                WHERE course_id=? AND user_id=? AND course_attempt_id=? AND rightanswer='0'
                GROUP BY question_id
                )
                ", [$courseId, $courseId, $userIDComplete1, $firstAttempt1['min_id']]);

            $nWrong_Understand = $query7[0]->nWrong_Understand ?? 0;
            $nTotal_Understand = $query6[0]->nTotal_Understand ?? 0;

            $query8 = DB::select("
                SELECT count(*) as nTotal_Analysis
                FROM tbl_course_question
                WHERE status='1' AND is_delete='0' AND course_id=? AND question_intent_id LIKE '%4%'
                ", [$courseId]);

            $query9 = DB::select("
                SELECT count(id) AS nWrong_Analysis
                FROM tbl_course_question
                WHERE course_id=? AND status='1' AND is_delete='0' AND question_intent_id LIKE '%4%' AND id IN (
                    SELECT question_id
                    FROM tbl_user_question_attempt_history
                    WHERE course_id=? AND user_id=? AND course_attempt_id=? AND rightanswer='0'
                    GROUP BY question_id
                    )
                    ", [$courseId, $courseId, $userIDComplete1, $firstAttempt1['min_id']]);

            $nWrong_Analysis = $query9[0]->nWrong_Analysis ?? 0;
            $nTotal_Analysis = $query8[0]->nTotal_Analysis ?? 0;
            if (($nWrong_Understand && $nTotal_Understand && $nWrong_Analysis && $nTotal_Analysis) > 0) {
                if ($nWrong_Understand / $nTotal_Understand * 100 < 15) {
                    $data['weakAppl'][$applWeak] = $userEmail[$i];
                    $applWeak++;
                }

                if ($nWrong_Analysis / $nTotal_Analysis * 100 < 30) {
                    $data['weakAna'][$anaWeak] = $userEmail[$i];
                    $anaWeak++;
                }
            }

            $query4 = DB::select("
            SELECT cast(time_to_sec(timediff(end_time, start_time)) / 60 as decimal(5,1)) as timeTaken
            FROM tbl_user_course_attempt
            WHERE user_id=? AND course_id=? AND id=?
            ", [$userIDComplete1, $courseId, $firstAttempt1['min_id']]);

            foreach ($query4 as $record) {
                $data['timeTaken'][] = $record->timeTaken;
                $timeTakenUser = $record->timeTaken;
                // dd($percentages[$i]);
                if ($timeTakenUser < 10) {
                    if ($percentages[$i] >= 80) {
                        $data['emailBright'][$br] = $userEmail[$i]; // Bright students
                        $br++;
                    }
                    if ($percentages[$i] >= 20 && $percentages[$i] < 80) {
                        $data['emailAverage'][$av] = $userEmail[$i]; // Average students
                        $av++;
                    }
                    if ($percentages[$i] < 20) {
                        $data['emailLackAt'][$fk] = $userEmail[$i]; // Did not pay attention
                        $fk++;
                    }
                }

                if ($timeTakenUser >= 10 && $timeTakenUser < 60) {
                    if ($percentages[$i] < 30) {
                        $data['emailSlow'][$sw] = $userEmail[$i]; // Need attention
                        $sw++;
                    }
                    if ($percentages[$i] >= 30) {
                        $data['emailAverage'][$av] = $userEmail[$i]; // Average students
                        $av++;
                    }
                }

                if ($timeTakenUser >= 60) {
                    if ($percentages[$i] < 30) {
                        $data['emailPer'][$pr] = $userEmail[$i]; // Need the most attention
                        $pr++;
                    }
                    if ($percentages[$i] >= 30) {
                        $data['emailAverage'][$av] = $userEmail[$i]; // Average students
                        $av++;
                    }
                }
            }

            $i++;
        }


        $data = [
            'weakAppl' => $data['weakAppl'] ?? [],
            'weakAna' => $data['weakAna'] ?? [],
            'emailBright' => $data['emailBright'] ?? [],
            'emailAverage' => $data['emailAverage'] ?? [],
            'emailLackAt' => $data['emailLackAt'] ?? [],
            'emailSlow' => $data['emailSlow'] ?? [],
            'emailPer' => $data['emailPer'] ?? [],
            'timeTaken' => $data['timeTaken'] ?? [],
            'fk' => $fk ?? 0,
            'av' => $av ?? 0,
            'pr' => $pr ?? 0,
            'sw' => $sw ?? 0,
            'br' => $br ?? 0,
            'anaWeak' => $anaWeak ?? 0,
            'applWeak' => $applWeak ?? 0,
        ];
        // dd($data);

        $totalSubscription = $i;

        $totalStudents = DB::table('tbl_course_subscription as cs')
            ->join('tbl_payment as tp', 'cs.payment_id', '=', 'tp.id')
            ->join('tbl_user as u', 'tp.user_id', '=', 'u.id')
            ->join('tbl_course as c', 'c.course_id', '=', 'cs.course_id')
            ->where('c.course_id', '=', $courseId)
            ->where('tp.payment_status', '=', 'success')
            ->orderBy('cs.updated_at', 'desc')
            ->count('cs.user_id');

        return view('front.author.course.statistics', compact('statistics', 'course', 'percentages', 'data', 'totalStudents', 'totalSubscription'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $language = Language::all();
        return view('front.author.course.create')->with(['languages' => $language]);
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
                'course_category_id' => 'required',
                'related_course_id' => 'required',
                'course_description' => 'required',
                'course_requirement' => 'required',
                'course_applications' => 'required',
                'status' => 'required|in:0,1',
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

            if (Auth::user()->hasRole('institute')) {
                $validatedData = $request->validate([
                    'user_id' => 'required',
                    'user_id.*' => 'numeric',
                ], [
                    'user_id.required' => 'The course author field is required.',
                ]);
            }

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

            if (Auth::user()->hasRole('institute')) {
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
            } else {

                $user_id = Auth::user()->id;
                if (!empty($user_id)) {

                    $insert_course_author[] = [
                        'course_id' => $course_id,
                        'user_id' => $user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $insert_course_author = CourseHasUser::insert($insert_course_author);
                }
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
     *
     * @param  \App\Models\Course  $course
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

            return view('front.author.course.show')->with(['course' => $course, 'user' => $user_data, 'categories' => $category, 'course_category' => $course_category, 'imgGallary' => $course_image, 'course_language' => $course_language]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
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

        return view('front.author.course.edit')->with(['course' => $course, 'course_category' => $course_category_data, 'related_course' => $related_course_data, 'imgGallary' => $course_image, 'course_author' => $course_author_data, 'languages' => $language, 'course_language' => $course_languages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
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
                'status' => 'required|in:0,1',
                'course_featured' => 'required|in:0,1',
                'course_include' => 'required',
                'course_language_id' => 'required',
                'subscription_day' => 'required|numeric|min:1',
                'meta_title' => 'required',
                'meta_keyword' => 'required',
                'meta_description' => 'required',
            ], [
                'user_id.required' => 'The course author field is required.',
            ]);

            if (Auth::user()->hasRole('institute')) {
                $validatedData = $request->validate([
                    'user_id' => 'required',
                    'user_id.*' => 'numeric',
                ], [
                    'user_id.required' => 'The course author field is required.',
                ]);
            }

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
                'status' => $request['status'],
                'course_include' => $request['course_include'],
                'course_featured' => $request['course_featured'],
                'subscription_day' => $request['subscription_day'],
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

            if (Auth::user()->hasRole('institute')) {
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
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
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
     * search course author
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function frontsearchcourseauthor(Request $request)
    {
        $response = array();
        if (!empty($request->searchTerm)) {
            $institute_id = auth()->user()->id;
            $author_ids = InstituteHasAuthor::select('author_id')->where('institute_id', $institute_id)->get();

            $author_id = [];
            foreach ($author_ids as $value) {
                $author_id[] = $value['author_id'];
            }

            $users = User::select('id', 'name')->role(['author', 'institute'])->where('name', 'like', '%' . $request->searchTerm . '%')->whereIN('id', $author_id)->get();
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
     * Get Course QA for institute and author
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetCourseUserDetail(Request $request, GetCourseUserDetailDataTable $dataTable)
    {
        if ($request->ajax()) {
            $validatedData = $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);
            return $dataTable->render('front.author.course.viewcourseuserdetail');
        }
        abort(404);
    }

}
