<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\SubmitAnswer\GroqAnswerController;
use Auth;
use DataTables;
use DB;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\AkgecskillsController;
use App\Http\Controllers\Front\SubmitAnswer\SubmitAnswerController;
use App\Models\Course;
use App\Models\CourseHasQA;
use App\Models\Questionanswer;
use App\Models\CourseQuestion;
use App\Models\User;
use App\Models\Usercourseattempt;
use App\Models\CourseCompleteHasUserRate;
use App\Models\Userquestionattempthistory;
use App\Models\QuestionHashelp;
use App\Models\QuestionHashint;
use App\Models\QuestionhasImagehelp;
use App\Models\QuestionhasImagehint;
use App\Models\UserHasCourseComment;
use App\Models\CourseSubscription;
use App\Models\CourseCategory;
use App\Models\CourseHasReview;
use App\DataTables\Front\UsersubscribecourseDataTable;
use App\DataTables\Front\GetCourseQADataTable;
use App\Mail\SendEmail;
use Illuminate\Http\Request;

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
     * Get Course QA for institute and author
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetCourseQA(Request $request, GetCourseQADataTable $dataTable)
    {
        if ($this->user->can('view-course-qa')) {
            if ($request->ajax()) {
                $validatedData = $request->validate(['id' => $request->id], [
                    'id' => 'required',
                ]);
                return $dataTable->render('front.course.viewcourseqa');
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Edit Course QA for institute and author
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function EditCourseQA(Request $request, $id)
    {
        if ($this->user->can('view-course-qa-edit')) {
            if ($request->ajax()) {
                $validatedData = $request->validate(['id' => $request->id], [
                    'id' => 'required',
                ]);

                $id = decrypt($request->id);
                $courseQA_data = CourseHasQA::where('id', $id)->get();
                return view('front.course.editcourseQA')->with(['QA_data' => $courseQA_data[0]]);
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Edit Course QA for institute
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function UpdateCourseQA(Request $request)
    {
        if ($this->user->can('view-course-qa-edit')) {
            if ($request->ajax()) {
                $request->validate([
                    'answer' => 'required',
                    'status' => 'required|in:1,2',
                ]);

                $id = decrypt($request->id);

                $update_data = [
                    'answer' => $request->answer,
                    'status' => $request->status,
                    'author_id' => Auth::user()->id,
                ];

                $update_data = CourseHasQA::where('id', $id)->update($update_data);

                return ["success" => true, "message" => "Course QA updated successfully"];
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Get my course
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetMyCourse(Request $request)
    {
        if ($this->user->can('get-my-course')) {
            $user_id = auth()->user()->id;
            $company_data = User::select('tbl_user_has_org.org_id')->leftJoin('tbl_user_has_org', 'tbl_user.id', '=', 'tbl_user_has_org.user_id')->where('tbl_user_has_org.user_id', $user_id)->first();
            $company_id = [$user_id];
            if (!empty($company_data)) {
                array_push($company_id, $company_data['org_id']);
            }
            $query = CourseSubscription::leftJoin('tbl_course', 'tbl_course_subscription.course_id', '=', 'tbl_course.course_id')
                ->leftJoin('tbl_course_has_user', 'tbl_course_has_user.course_id', '=', 'tbl_course_subscription.course_id')
                ->leftJoin('tbl_user', 'tbl_user.id', '=', 'tbl_course_has_user.user_id')
                ->leftJoin(DB::Raw('(SELECT r.course_id, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM tbl_course_has_review_rate AS r) s'), 's.course_id', '=', 'tbl_course.course_id')
                ->where('tbl_course.status', '1')
                ->whereIn('tbl_course_subscription.user_id', $company_id)
                ->where('tbl_course_subscription.status', '1')
                ->where('tbl_course_subscription.sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                ->groupBy('tbl_course.course_id')
                ->select(DB::Raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name'), DB::Raw('IFNULL(s.rate, 0) AS rate'), DB::Raw('IFNULL(s.total_record, 0) AS total_record'), 'tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_image', 'tbl_course.slug', 'tbl_course.course_price');
            if (!Auth::user()->hasRole('organization')) {
                $query->leftJoin('tbl_course_subscription_licence', 'tbl_course_subscription.id', '=', 'tbl_course_subscription_licence.course_subscription_id');
                $query->where('tbl_course_subscription_licence.status', '1');
                $query->where('tbl_course_subscription_licence.user_id', $user_id);
            }
            $subscribe_course = $query->get();
            $subscribe_courses = [];
            if (!empty($subscribe_course)) {
                foreach ($subscribe_course as $key => $course) {
                    $subscribe_courses[$key] = $course;
                    $state = $this->GetUserCourseState($user_id, $course->course_id);
                    if (empty($state)) {
                        $subscribe_courses[$key]['state'] = "todo";
                    } else {
                        $subscribe_courses[$key]['state'] = ($state->state == "" || $state->state == "complete") ? "todo" : $state->state;
                    }
                    $subscribe_courses[$key] = $course;
                }
            }
            return view('frontend.front.course.my-course')->with(['subscribe_courses' => $subscribe_courses]);
        }
        abort(403);
    }

    /**
     * Get Last State of course
     * @param type $user_id
     * @param type $course_id
     * @return type
     */
    public function GetUserCourseState($user_id, $course_id)
    {
        return Usercourseattempt::Where(['user_id' => $user_id, 'course_id' => $course_id, 'state' => 'process'])->first();
    }

    /**
     * Get Last User Attempt Question ID
     * @param type $course_attempt_id
     * @return type
     */
    public function GetUserLastQuestion($course_attempt_id)
    {
        return Userquestionattempthistory::leftJoin('tbl_course_question', 'tbl_user_question_attempt_history.question_id', '=', 'tbl_course_question.id')->where('tbl_user_question_attempt_history.course_attempt_id', $course_attempt_id)->where('tbl_course_question.status', '1')->where('tbl_course_question.is_delete', '0')->orderBy('tbl_user_question_attempt_history.id', 'desc')
            ->first(['question_id']);
    }

    /**
     * learn my course
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function CourseLearn(Request $request, $id)
    {
        $akg = new AkgecskillsController;
        $course_id = decrypt($id);
        // dd($course_id);
        $getCourseType = Course::where('course_id', $course_id)->first()->course_type;
        // $course_id = '171';
        $user_id = auth()->user()->id;
        $get_que_id_matlab = "";
        $counter = 1;
        /* echo "cid = ".$course_id;
        echo "uid = ".$user_id; exit; */
        /*         * ****************************** Start Or resume Attempt ****************** */
        $courseAttempt = Usercourseattempt::select(['id AS course_attempt_id', 'state', 'start_time'])->where(['user_id' => $user_id, 'course_id' => $course_id, 'state' => 'process'])->first();

        if (empty($courseAttempt)) {
            $insert = [
                'user_id' => $user_id,
                'course_id' => $course_id,
                'state' => 'process',
                'start_time' => now()
            ];
            $courseAttempt = Usercourseattempt::create($insert);
            $courseAttempt->course_attempt_id = $courseAttempt->id;
        }
        $attempt_id = $courseAttempt->course_attempt_id;
        // echo "att = ".$attempt_id; exit;
        $last_question = $this->GetUserLastQuestion($attempt_id);
        // if ($course_id == '171') {
        //     if (empty($last_question)) {
        //         $get_que_id_matlab = $akg->GetAkgDataFromMatlab(1, $course_id, $attempt_id, $user_id);
        //     } else {
        //         $get_que_id_matlab = $akg->GetAkgDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
        //     }
        // }
        // dd($last_question);

        if ($getCourseType == 'skippable') {
            if (empty($last_question)) {
                $get_que_id_matlab = $akg->GetAkgDataFromMatlab(1, $course_id, $attempt_id, $user_id);
            } else {
                $get_que_id_matlab = $akg->GetAkgDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
            }
        } else {
            if (empty($last_question)) {
                $get_que_id_matlab = $this->GetDataFromMatlab(1, $course_id, $attempt_id, $user_id);
            } else {
                $get_que_id_matlab = $this->GetDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
            }
        }

        $multiRightCount = Userquestionattempthistory::select(DB::raw('count(distinct tbl_user_question_attempt_history.question_id) as question_count'))
            ->leftJoin('tbl_course_question', 'tbl_course_question.id', '=', 'tbl_user_question_attempt_history.question_id')
            ->where('tbl_course_question.status', '1')
            ->where('tbl_course_question.is_delete', '0')
            ->where('tbl_course_question.question_type', 'multi')
            ->where('tbl_user_question_attempt_history.user_id', $user_id)
            ->where('tbl_user_question_attempt_history.course_attempt_id', $attempt_id)
            ->where('tbl_user_question_attempt_history.rightanswer', '1')
            ->get();
        $multiQuestionCount = CourseQuestion::select('id')->where('course_id', $course_id)->where('question_type', 'multi')->where('is_delete', '0')->where('status', '1')->count();
        $progressCount = 0;
        if (!empty($multiRightCount) && $multiRightCount[0]->question_count != 0) {
            $rightMultiQuestionPercen = round(($multiRightCount[0]->question_count / $multiQuestionCount) * 100);
            $progressCount = round($rightMultiQuestionPercen, 2);
        }
        if (empty($get_que_id_matlab)) {
            if (env('APP_ENV') == 'production') {
                $html = "<p>Error:" . $get_que_id_matlab['error'] . "</p><p>Last Question Id : " . $last_question['question_id'] . "</p><p>Course Id : " . $course_id . "</p><p>Attempt ID : " . $attempt_id . "</p><p>User Id : " . $user_id . "</p>";
                \Mail::send([], [], function ($message) use ($html) {
                    $message->to(['developer1@cecs.co.in', 'harin.dcd@gmail.com'])
                        ->subject("Error From matlab")
                        ->setBody($html, 'text/html');
                });
            }
            return view('front.course-delivery.index')->with(['question' => [], 'rand_progress' => rand(0, 9), 'progresscount' => $progressCount]);
        }

        $category = CourseCategory::select('tbl_course_category.id', 'tbl_course_category.name', 'tbl_course_category.slug')
            ->leftJoin('tbl_course_category', 'tbl_has_course_category.cat_id', '=', 'tbl_course_category.id')
            ->where('tbl_has_course_category.course_id', $course_id)
            ->where('tbl_course_category.status', '1')
            ->orderBy('tbl_course_category.parent_id', 'DESC')
            ->get();
        $review = CourseHasReview::select('rate', 'review')->where(['user_id' => $user_id, 'course_id' => $course_id])->first();
        if ($get_que_id_matlab['is_complete'] == 1) {
            return view('front.course-delivery.complete_course')->with(['review' => $review, 'category' => $category, 'course_id' => $course_id, 'attempt_id' => $attempt_id]);
        }
        $question_id = $get_que_id_matlab['que_id'];
        //        $question_id = 1475;
        $question = CourseQuestion::find($question_id);
        if (empty($question)) {
            return view('front.course-delivery.index')->with(['question' => [], 'rand_progress' => rand(0, 9), 'progresscount' => $progressCount]);
        }

        if (!empty($question->question_media_multi)) {
            $question->question_media_multi = explode(',', $question->question_media_multi);
        }
        $help = $this->getQuestionHelp($question_id);
        $hint = $this->getQuestionHint($question_id);
        $question_ans = $this->getQuestionAnswer($question_id);
        $course_qa = CourseHasQA::select('question_name', 'answer')->where(['course_id' => $course_id, 'status' => '1'])->get()->toArray();
        $course_comment = UserHasCourseComment::select('comment')->where(['course_id' => $course_id, 'user_id' => $user_id])->get()->toArray();

        /*         * *********************************************Chapter Progress ******************************************************** */

        $que_toc_no = $question->que_toc_no;
        $chapter_progress = 0;
        $get_parent_data = CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('course_id', $course_id)->orderBy('que_toc_no', 'ASC')->get()->toArray();
        $all_data = [];
        if (!empty($get_parent_data)) {
            $toc_no = [];
            foreach ($get_parent_data as $key => $value) {
                $toc_no[] = $value['que_toc_no'];
            }

            $res = [];
            array_walk($toc_no, function ($item) use (&$res) {
                $key = substr($item, 0, 1);
                if (isset($res[$key]))
                    $res[$key][] = $item;
                else
                    $res[$key] = [$item];
            });
            if (isset($res[substr($que_toc_no, 0, 1)])) {
                $toc = $res[substr($que_toc_no, 0, 1)];
                $i = 1;
                if (!empty($toc)) {
                    foreach ($toc as $row) {
                        if ($row == $que_toc_no) {
                            break;
                        }
                        $i++;
                    }
                }
                $chapter_progress = round($i / count($toc) * 100, 0);
            }
        }
        // dd($question);
        return view('front.course-delivery.index')->with(['question' => $question, 'chepter_progress' => $chapter_progress, 'rand_progress' => rand(0, 9), 'progresscount' => $progressCount, 'review' => $review, 'category' => $category, "courseAttempt" => $courseAttempt, 'help' => $help, 'hint' => $hint, 'question_ans' => $question_ans, 'course_qa' => $course_qa, 'course_comment' => $course_comment]);
    }

    /**
     * Get Next Que
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return type
     */
    public function getNextQuestion(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'course_id' => 'required'
            ]);
            $course_id = decrypt($request->course_id);
            //$course_id = '171';

            $getCourseType = Course::where('course_id', $course_id)->first()->course_type;

            $user_id = auth()->user()->id;
            $get_que_id_matlab = "";
            $counter = 1;
            $akg = new AkgecskillsController;
            /*             * ****************************** Start Or resume Attempt ****************** */
            $courseAttempt = Usercourseattempt::select(['id AS course_attempt_id', 'state', 'start_time'])->where(['user_id' => $user_id, 'course_id' => $course_id, 'state' => 'process'])->first();
            if (empty($courseAttempt)) {
                $insert = [
                    'user_id' => $user_id,
                    'course_id' => $course_id,
                    'state' => 'process',
                    'start_time' => now()
                ];
                $lastInsertedData = Usercourseattempt::create($insert);
                $courseAttempt = [
                    'course_attempt_id' => $lastInsertedData['id'],
                    'state' => $lastInsertedData['state'],
                    'start_time' => $lastInsertedData['start_time']
                ];
            }
            $attempt_id = $courseAttempt->course_attempt_id;
            $last_question = $this->GetUserLastQuestion($attempt_id);

            // if ($course_id == '171') {
            //     if (empty($last_question)) {
            //         $get_que_id_matlab = $akg->GetAkgDataFromMatlab(1, $course_id, $attempt_id, $user_id);
            //     } else {
            //         $get_que_id_matlab = $akg->GetAkgDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
            //     }
            // }
            if ($getCourseType == 'skippable') {
                if (empty($last_question)) {
                    $get_que_id_matlab = $akg->GetAkgDataFromMatlab(1, $course_id, $attempt_id, $user_id);
                } else {
                    $get_que_id_matlab = $akg->GetAkgDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
                }
            } else {
                if (empty($last_question)) {
                    $get_que_id_matlab = $this->GetDataFromMatlab(1, $course_id, $attempt_id, $user_id);
                } else {
                    $get_que_id_matlab = $this->GetDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
                }
            }


            if (isset($get_que_id_matlab['error'])) {
                return view('front.course-delivery.index')->with(['question' => []]);
                return response()->json(['error' => ["some_worng" => $get_que_id_matlab['error']]], 401);
            }
            if ($get_que_id_matlab['is_complete'] == 1) {
                $html = view('front.course-delivery.next_complete_course')->with(['course_id' => $course_id, 'attempt_id' => $attempt_id]);
                return response()->json(['success' => true, 'html' => $html->render()], 200);
            }
            $question_id = $get_que_id_matlab['que_id'];
            $question = CourseQuestion::find($question_id);
            if (empty($question)) {
                $html = view('front.course-delivery.next-question')->with(['question' => []]);
                return response()->json(['success' => true, 'html' => $html->render()], 200);
            }

            if (!empty($question->question_media_multi)) {
                $question->question_media_multi = explode(',', $question->question_media_multi);
            }
            $help = $this->getQuestionHelp($question_id);
            $hint = $this->getQuestionHint($question_id);
            $question_ans = $this->getQuestionAnswer($question_id);
            $course_qa = CourseHasQA::select('question_name', 'answer')->where(['course_id' => $course_id, 'status' => '1'])->get()->toArray();
            $course_comment = UserHasCourseComment::select('comment')->where(['course_id' => $course_id, 'user_id' => $user_id])->get()->toArray();
            $category = CourseCategory::select('tbl_course_category.id', 'tbl_course_category.name', 'tbl_course_category.slug')
                ->leftJoin('tbl_course_category', 'tbl_has_course_category.cat_id', '=', 'tbl_course_category.id')
                ->where('tbl_has_course_category.course_id', $course_id)
                ->where('tbl_course_category.status', '1')
                ->orderBy('tbl_course_category.parent_id', 'DESC')
                ->get();
            $review = CourseHasReview::select('rate', 'review')->where(['user_id' => $user_id, 'course_id' => $course_id])->first();

            $multiRightCount = Userquestionattempthistory::select(DB::raw('count(distinct tbl_user_question_attempt_history.question_id) as question_count'))
                ->leftJoin('tbl_course_question', 'tbl_course_question.id', '=', 'tbl_user_question_attempt_history.question_id')
                ->where('tbl_course_question.status', '1')
                ->where('tbl_course_question.is_delete', '0')
                ->where('tbl_course_question.question_type', 'multi')
                ->where('tbl_user_question_attempt_history.user_id', $user_id)
                ->where('tbl_user_question_attempt_history.course_attempt_id', $attempt_id)
                ->where('tbl_user_question_attempt_history.rightanswer', '1')
                ->get();
            $multiQuestionCount = CourseQuestion::select('id')->where('course_id', $course_id)->where('question_type', 'multi')->where('is_delete', '0')->where('status', '1')->count();
            $progressCount = 0;
            if (!empty($multiRightCount) && $multiRightCount[0]->question_count != 0) {
                $rightMultiQuestionPercen = ($multiRightCount[0]->question_count / $multiQuestionCount) * 100;
                $progressCount = round($rightMultiQuestionPercen, 2);
            }
            $rand_progress = rand(0, 9);

            /*             * *********************************************Chapter Progress ******************************************************** */
            $que_toc_no = $question->que_toc_no;
            $chapter_progress = 0;
            $get_parent_data = CourseQuestion::select(['id', 'que_toc_no', 'que_toc_text'])->where('course_id', $course_id)->orderBy('que_toc_no', 'ASC')->get()->toArray();
            $all_data = [];
            if (!empty($get_parent_data)) {
                $toc_no = [];
                foreach ($get_parent_data as $key => $value) {
                    $toc_no[] = $value['que_toc_no'];
                }

                $res = [];
                array_walk($toc_no, function ($item) use (&$res) {
                    $key = substr($item, 0, 1);
                    if (isset($res[$key]))
                        $res[$key][] = $item;
                    else
                        $res[$key] = [$item];
                });
                if (isset($res[substr($que_toc_no, 0, 1)])) {
                    $toc = $res[substr($que_toc_no, 0, 1)];
                    $i = 1;
                    if (!empty($toc)) {
                        foreach ($toc as $row) {
                            if ($row == $que_toc_no) {
                                break;
                            }
                            $i++;
                        }
                    }
                    $chapter_progress = round($i / count($toc) * 100, 0);
                }
            }
            $html = view('front.course-delivery.next-question')->with(['question' => $question, 'rand_progress' => $rand_progress, 'progresscount' => $progressCount, 'chepter_progress' => $chapter_progress, 'review' => $review, 'category' => $category, "courseAttempt" => $courseAttempt, 'help' => $help, 'hint' => $hint, 'question_ans' => $question_ans, 'course_qa' => $course_qa, 'course_comment' => $course_comment]);
            $progress_html = view('front.course-delivery.next-question-progress')->with(['progresscount' => $progressCount, 'rand_progress' => $rand_progress]);
            return response()->json(['success' => true, 'html' => $html->render(), 'progress_html' => $progress_html->render()], 200);
        }
        abort(404);
    }

    /**
     * Get Question
     * @param type $question_id
     * @return type
     */
    public function getQuestionAnswer($question_id)
    {
        return Questionanswer::where('question_id', $question_id)->orderBy('answer_order', 'ASC')->get()->toArray();
    }

    /**
     * Get Question From Matlab/Local
     * @param type $last_id
     * @param type $counter
     * @param type $course_id
     * @param type $attempt_id
     * @param type $user_id
     * @return type
     */
    public function GetDataFromMatlab($sqlCounter, $courseID, $attemptID, $userID)
    {
        //        \Log::info(json_encode(['sqlCounter' => $sqlCounter, 'courseID' => $courseID, 'attemptID' => $attemptID, 'userID' => $userID]));
        // dd($sqlCounter, $courseID, $attemptID, $userID); exit;
        [$currentToC, $nextToC, $maxTOC, $isEndToC, $sortedToC] = $this->getNextToC($userID, $courseID, $sqlCounter);

        $maxLevel = 1;  # Only one level
        $qCounter = $sqlCounter;
        $currentLevel = '1';
        if (!empty($currentToC)) {
            $currentToC = $currentToC[0];
        } else {
            $currentToC = "";
        }

        #Find indices of questions
        [$indices] = $this->getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID);
        $levelIndices = $indices;
        if (is_array($levelIndices)) {
            $b = array_rand($levelIndices, 1);
            $levelIndices = $levelIndices[$b];
        }

        [$GyanIndices, $GyanLevel] = $this->GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID); #Start index for Gyan only questions
        $startLevel = $GyanIndices;

        $sortedToC = array_values($sortedToC);
        $clength = count($sortedToC);

        # Fetch number of correct and incorrect answers in the most recent attempt
        for ($x = 0; $x < $clength; $x++) {
            $Loop_currentToC = $sortedToC[$x];
            $isTOCFinished = $this->isAllCorrectAtTOC($courseID, $Loop_currentToC, $currentLevel, $userID, $attemptID);
            $isTOCFinished1[$x] = $isTOCFinished;
        }

        [$nTrue] = $this->getPastCorrect($courseID, $currentToC, $currentLevel, $userID, $attemptID);
        $nPastCorrect = $nTrue;

        [$nFalse] = $this->getPastFalse($courseID, $currentToC, $currentLevel, $userID, $attemptID);
        $nPastFalse = $nFalse;

        /* additional */
        if (empty($levelIndices) == 0) {
            if (is_array($levelIndices)) {
                $startLevel = min($levelIndices);
            } else {
                $startLevel = $levelIndices;
            }
        }

        /* additional */
        if ($sqlCounter == 1) {
            $isRememberUnderstan = $this->getIntent($courseID, $currentToC, $startLevel, $currentLevel, $userID, $attemptID);
        } else {
            $isRememberUnderstan = $this->getIntent($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID);
        }

        if (empty($levelIndices) == 0) {
            if (is_array($levelIndices)) {
                $startLevel = min($levelIndices);
            } else {
                $startLevel = $levelIndices;
            }
        }

        if (($nPastCorrect == 0) && $isRememberUnderstan) {
            $nextQuestion = $GyanIndices;
            $CourseComplete = 0;
        } else {
            # Fetch next question using AI
            if (array_sum($isTOCFinished1) == $maxTOC) {
                $nextQuestion = $sqlCounter;  #if we want same question when its complete
                $CourseComplete = 1;
            } else {
                $sortedToC = array_unique($sortedToC);
                array_unshift($sortedToC, "");
                unset($sortedToC[0]);
                $indexCurrent = array_search($currentToC, $sortedToC);
                array_unshift($isTOCFinished1, "");
                unset($sortedToC[0]);
                if ($isTOCFinished1[$indexCurrent]) {
                    $currentToC = $nextToC;
                    $current_level = 1;
                    if (is_array($currentToC)) {
                        $currentToC = implode(',', $currentToC);
                    }
                    # Update indices of questions at current level
                    [$startLevel] = $this->GetGyanIndex1($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID);

                    [$indices] = $this->getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID);
                    $levelIndices = $indices;
                    $nextQuestion = $startLevel;
                    $CourseComplete = 0;
                } else {
                    if (is_array($levelIndices)) {
                        $b = array_rand($levelIndices, 1);
                        $nextID = $levelIndices[$b];
                    } else {
                        $nextID = $levelIndices;
                    }
                    $CourseComplete = 0;
                    $nextQuestion = $nextID;
                    $attemptComplete = $CourseComplete;
                }
            }
        }
        //        \Log::info(json_encode(['que_id' => $nextQuestion, 'is_complete' => $CourseComplete]));
        return ['que_id' => $nextQuestion, 'is_complete' => $CourseComplete];
        if (empty($last_id)) {
            $data = array("nargout" => 2, "rhs" => array((int) $course_id, (int) $attempt_id, (int) $user_id));
        } else {
            $data = array("nargout" => 2, "rhs" => array((int) $last_id, (int) $course_id, (int) $attempt_id, (int) $user_id));
        }


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MATLAB_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);
        $return_response = array();
        if ($err) {
            $return_response['error'] = "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);

            if (isset($response['error'])) {
                $return_response['error'] = isset($response['error']['message']) ? $response['error']['message'] : "Something went wrong";
            } else if (!isset($response['lhs'][0]['mwdata'][0])) {
                $return_response['error'] = "Question Data not found!";
            } else if (!isset($response['lhs'][1]['mwdata'][0])) {
                $return_response['error'] = "Question Data not found!";
            } else {
                $return_response['que_id'] = $response['lhs'][0]['mwdata'][0];
                $return_response['is_complete'] = $response['lhs'][1]['mwdata'][0];
            }
        }
        return $return_response;
    }

    /**
     * Get Next TOC
     * @param type $userID
     * @param type $courseID
     * @param type $sqlCounter
     * @return type
     */
    public function getNextToC($userID, $courseID, $sqlCounter)
    {
        $query1 = DB::select("SELECT tbl_course_question.que_toc_no as allTOC FROM tbl_course_question WHERE  tbl_course_question.is_delete='0' AND tbl_course_question.status ='1' AND course_id= $courseID");

        $allTOC = [];
        foreach ($query1 as $data) {
            $allTOC[] = $data->allTOC;
        }
        if (empty($allTOC)) {
            $allTOC[] = 0;
        }
        $query2 = DB::select("SELECT tbl_course_question.que_toc_no as currentToC FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND id= $sqlCounter AND tbl_course_question.course_id IN (SELECT course_id FROM tbl_user_question_attempt_history WHERE tbl_user_question_attempt_history.user_id= $userID)");

        $currentToC = [];
        foreach ($query2 as $data) {
            $currentToC[] = $data->currentToC;
        }

        /* additional */
        if ($sqlCounter == 1) {
            $query22 = DB::select("
                SELECT tbl_course_question.que_toc_no as currentToC FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID
                 AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' ORDER BY que_toc_no ASC LIMIT 1");
            foreach ($query22 as $data) {
                $currentToC[] = $data->currentToC;
            }
        }
        /* additional */
        $unique_toc = natsort($allTOC);
        $clength = count($allTOC);
        $sorted_array = [];
        for ($x = 0; $x < $clength; $x++) {
            $sorted_array[$x] = $allTOC[$x];
        }
        $sortedToC = array_unique($sorted_array);
        array_unshift($sortedToC, "");
        unset($sortedToC[0]);
        $maxTOC = count($sortedToC);
        $nextToC = 1;
        $isEndToC = 0;
        if ($sqlCounter == 1) {
            $currentToC[] = $sortedToC[1];
            if ($maxTOC == 1) {
                $nextToC = $currentToC;
            } else {
                $nextToC = $sortedToC[2];
            }
            $isEndToC = 0;
        } else {
            $index_current = array_search("$currentToC[0]", $sortedToC);
            if ($index_current == $maxTOC) {
                $nextToC = $currentToC;
                $isEndToC = 1;
            } else {
                $nextToC = $sortedToC[$index_current + 1];
                $isEndToC = 0;
            }
        }
        return [$currentToC, $nextToC, $maxTOC, $isEndToC, $sortedToC];
    }

    /**
     * Get Gyan Index
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    public function GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID)
    {
        $query1 = DB::select("SELECT tbl_course_question.id as id , tbl_course_question.que_level as que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)");

        foreach ($query1 as $data) {
            $GyanIndices = $data->id;
        }

        foreach ($query1 as $data) {
            $GyanLevel = $data->que_level;
        }

        if (empty($GyanLevel)) {
            $GyanLevel = 0;
        }
        if (empty($GyanIndices)) {
            $GyanIndices = 0;
        }
        return [$GyanIndices, $GyanLevel];
    }

    /**
     * Check is All Correct At TOC
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return int\
     */
    public function isAllCorrectAtTOC($courseID, $currentToC, $currentLevel, $userID, $attemptID)
    {
        $query1 = DB::select("select COUNT(*) as total_cur_toc from tbl_course_question where que_toc_no=  '$currentToC'  AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.course_id= $courseID");
        foreach ($query1 as $data) {
            $check_toc = $data->total_cur_toc;
        }
        $query2 = DB::select("SELECT id as question_id FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.id IN (SELECT question_id FROM tbl_user_question_attempt_history WHERE tbl_user_question_attempt_history.course_id = $courseID AND tbl_user_question_attempt_history.user_id= $userID AND tbl_user_question_attempt_history.course_attempt_id= $attemptID AND tbl_user_question_attempt_history.rightanswer='1' GROUP BY question_id)");
        foreach ($query2 as $data) {
            $Question_ID[] = $data->question_id;
        }
        if (empty($Question_ID)) {
            $isTOCFinished = 0;
        } else {
            $check_toc_len = (float) $check_toc;
            $clength = count($Question_ID);
            $isTOCFinished = $clength == $check_toc_len;
            if ($isTOCFinished == 1) {
                $isTOCFinished = 1;
            } else {
                $isTOCFinished = 0;
            }
        }
        return $isTOCFinished;
    }

    /**
     * get Past Correct
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    public function getPastCorrect($courseID, $currentToC, $currentLevel, $userID, $attemptID)
    {
        $query1 = DB::select("SELECT COUNT(id) as Count_id FROM (SELECT * FROM tbl_user_question_attempt_history WHERE tbl_user_question_attempt_history.user_id = $userID AND tbl_user_question_attempt_history.course_id= $courseID AND tbl_user_question_attempt_history.course_attempt_id= $attemptID ORDER BY tbl_user_question_attempt_history.id DESC LIMIT 1) sub WHERE rightanswer='1'");
        foreach ($query1 as $data) {
            $nTrue = $data->Count_id;
        }
        return [$nTrue];
    }

    /**
     * get Past False
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    public function getPastFalse($courseID, $currentToC, $currentLevel, $userID, $attemptID)
    {
        $query1 = DB::select("SELECT COUNT(id) as Count_id FROM (SELECT * FROM tbl_user_question_attempt_history WHERE tbl_user_question_attempt_history.user_id = $userID AND tbl_user_question_attempt_history.course_id= $courseID AND tbl_user_question_attempt_history.course_attempt_id= $attemptID ORDER BY tbl_user_question_attempt_history.id DESC LIMIT 1) sub WHERE rightanswer='0'");
        foreach ($query1 as $data) {
            $nFalse = $data->Count_id;
        }
        return [$nFalse];
    }

    /**
     * get Intent
     * @param type $courseID
     * @param type $currentToC
     * @param type $sqlCounter
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return int
     */
    public function getIntent($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID)
    {
        $query1 = DB::select("SELECT question_intent_id FROM tbl_course_question WHERE tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.id= $sqlCounter AND tbl_course_question.course_id= $courseID");
        $getIntent = [];
        foreach ($query1 as $data) {
            $getIntent[] = $data->question_intent_id;
        }
        if (empty($getIntent)) {
            $getIntent[] = 0;
        }
        $isRememberUnderstan = $getIntent[0];
        if ($isRememberUnderstan == 1 || $isRememberUnderstan == 3) {
            $isRememberUnderstan = $getIntent;
        } else {
            $isRememberUnderstan = 0;
        }
        return $isRememberUnderstan;
    }

    /**
     * Get Question Index
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    public function getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID)
    {
        $query1 = DB::select("SELECT id AS question_id FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_level= $currentLevel AND tbl_course_question.id NOT IN (SELECT question_id FROM tbl_user_question_attempt_history WHERE tbl_user_question_attempt_history.course_id = $courseID  AND tbl_user_question_attempt_history.user_id= $userID AND tbl_user_question_attempt_history.course_attempt_id= $attemptID AND tbl_user_question_attempt_history.rightanswer='1' GROUP BY question_id)");

        $indices = [];
        foreach ($query1 as $data) {
            $indices[] = $data->question_id;
        }
        if (empty($indices)) {
            $indices = NULL;
        }

        return [$indices];
    }

    /**
     * Get/Check Gyan another Index
     * @param type $courseID
     * @param type $currentToC
     * @param type $sqlCounter
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    public function GetGyanIndex1($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID)
    {
        $query1 = DB::select("SELECT tbl_course_question.id as question_ID , tbl_course_question.que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)");
        foreach ($query1 as $data) {
            $GyanIndices = $data->question_ID;
        }
        $query2 = DB::select("SELECT tbl_course_question.que_level as que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)");
        foreach ($query2 as $data) {
            $GyanLevel = $data->que_level;
        }
        if (!empty($GyanIndices)) {
            $startLevel = $GyanIndices;
        } else {
            #Detect if user has pressed "Start". "Resume" means isFirst=0.  isFirst = isFirstAttempt(userID, courseID, conn);
            $startLevel = 0;
        }
        return [$startLevel, $GyanLevel, $GyanIndices];
    }

    /**
     *
     * @param type $course_id
     * @param type $nextToC
     * @return type
     */
    public function getStartTOCIndex($course_id, $nextToC)
    {
        $startTOCIndex = DB::select("SELECT tbl_course_question.id, tbl_course_question.que_level
                                FROM tbl_course_question WHERE (tbl_course_question.course_id ='$course_id'
                                AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no='$nextToC'
                                AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' )
                                AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id
                                FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)");
        return $startTOCIndex[0]->id;
    }

    /**
     *
     * @param type $course_id
     * @param type $nextToC
     * @return type
     */
    public function getcurrentTocQuestion($course_id, $nextToC)
    {
        $current_que_toc = DB::select("SELECT id AS question_id,question_name FROM tbl_course_question
                        WHERE tbl_course_question.course_id ='$course_id'
                        AND tbl_course_question.que_toc_no='$nextToC' AND tbl_course_question.status ='1'
                        AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_level='1'");
        return $current_que_toc[0]->question_id;
    }

    /**
     * Get Question Help
     * @param type $question_id
     * @return type
     */
    public function getQuestionHelp($question_id)
    {
        $help = [];
        $helps = QuestionHashelp::where('question_id', $question_id)->first();
        if (!empty($helps)) {
            $help = $helps->toArray();
        }
        $help['image'] = $this->getQuestionHelpImages($question_id);
        return $help;
    }

    /**
     * Get Question Help Images
     * @param type $question_id
     * @return type
     */
    public function getQuestionHelpImages($question_id)
    {
        $image_help = QuestionhasImagehelp::select('image')->where('question_id', $question_id)->get()->toArray();
        return $image_help;
    }

    /**
     * Get Question Hint
     * @param type $question_id
     * @return type
     */
    public function getQuestionHint($question_id)
    {
        $hint = [];
        $hints = QuestionHashint::where('question_id', $question_id)->first();
        if (!empty($hints)) {
            $hint = $hints->toArray();
        }
        $hint['image'] = $this->getQuestionHintImages($question_id);
        return $hint;
    }

    /**
     * Get Question Hint Images
     * @param type $question_id
     * @return type
     */
    public function getQuestionHintImages($question_id)
    {
        $image_hint = QuestionhasImagehint::select('image')->where('question_id', $question_id)->get()->toArray();
        return $image_hint;
    }

    /**
     * Add New Course Comment
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function AddNewCourseComment(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'course_comment' => 'required',
                'course_id' => 'required',
            ]);
            $course_id = decrypt($request->course_id);
            $user_id = auth()->user()->id;
            UserHasCourseComment::create(['course_id' => $course_id, 'user_id' => $user_id, 'comment' => $request->course_comment]);
            $course_comment = UserHasCourseComment::select('comment')->where(['course_id' => $course_id, 'user_id' => $user_id])->get()->toArray();
            $html = view('front.course-delivery.get-comment')->with(['course_comment' => $course_comment]);
            return response()->json(['success' => true, "message" => 'Comment Added successfully.', 'html' => $html->render()], 200);
        }
        abort(404);
    }

    /**
     * Add New Q & A
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function AddNewQAndA(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'question' => 'required',
                'course_id' => 'required',
            ]);
            $course_id = decrypt($request->course_id);
            $user_id = auth()->user()->id;
            CourseHasQA::create(['course_id' => $course_id, 'user_id' => $user_id, 'question_name' => $request->question]);
            $course_qa = CourseHasQA::select('question_name', 'answer')->where(['course_id' => $course_id, 'status' => '1'])->get()->toArray();
            $html = view('front.course-delivery.get-q-and-a')->with(['course_qa' => $course_qa]);
            return response()->json(['success' => true, "message" => 'Q & A Submited successfully.', 'html' => $html->render()], 200);
        }
        abort(404);
    }

    /**
     * Submit Rate Review
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function submitRateReview(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'review' => 'required',
                'rate' => 'required',
                'course_id' => 'required',
            ]);
            try {
                $course_id = decrypt($request->course_id);
                $user_id = auth()->user()->id;
                CourseHasReview::create(['course_id' => $course_id, 'user_id' => $user_id, 'rate' => $request->rate, 'review' => $request->review]);
                $course_review = CourseHasReview::select('rate', 'review')->where(['course_id' => $course_id, 'user_id' => $user_id])->first()->toArray();
                $html = view('front.course-delivery.get-rate-and-review')->with(['course_review' => $course_review]);
                return response()->json(['success' => true, "message" => 'Q & A Submited successfully.', 'html' => $html->render()], 200);
            } catch (Exception $e) {
                return response()->json(['errors' => ["some_worng" => ['Already Submited Rate and review.']]], 422);
            }
        }
        abort(404);
    }

    /**
     * Finish Course
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function FinishCourse(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'course_id' => 'required',
                'attempt_id' => 'required',
                'course_rate' => 'required',
                'author_rate' => 'required',
                'new_skill_rate' => 'required',
                'overall_rate' => 'required',
                'accessing_rate' => 'required',
                'recommend_rate' => 'required',
            ]);

            $user_id = auth()->user()->id;
            $course_id = $request->course_id;
            $attempt_id = $request->attempt_id;
            $insertData = [
                'course_id' => $course_id,
                'user_id' => $user_id,
                'attempt_id' => $attempt_id,
                'course_rate' => $request->course_rate,
                'author_rate' => $request->author_rate,
                'new_skill_rate' => $request->new_skill_rate,
                'overall_rate' => $request->overall_rate,
                'accessing_rate' => $request->accessing_rate,
                'recommend_rate' => $request->recommend_rate,
            ];

            $insert_complete_course = CourseCompleteHasUserRate::create($insertData);
            $update_course_attempt = Usercourseattempt::where('id', $attempt_id)->update(['state' => 'complete', 'end_time' => now()]);

            /*             * **********************************Start Email ******************************* */
            $mailsenddata = Usercourseattempt::select(['user_id', 'course_id', 'is_mail_send'])->where('state', 'complete')->where('user_id', $user_id)->where('course_id', $course_id)->where('is_mail_send', '1')->count();
            if ($mailsenddata <= 0) {
                $course = Course::select(['course_price', 'course_name'])->where('course_id', $course_id)->first();
                $users = User::find($user_id);
                $data = [
                    'template' => 'CourseComplete',
                    'html_body' => [
                        'name' => $users->name,
                        'course' => $course->course_name
                    ],
                    'subject' => 'Congratulations for completing the module'
                ];
                \Mail::to($users->email)->send(new SendEmail($data));
                $data_admin = [
                    'template' => 'CourseComplete',
                    'html_body' => [
                        'name' => $users->name,
                        'course' => $course->course_full_name
                    ],
                    'subject' => $users->email . ' Has Completed Course Recently'
                ];
                \Mail::to("info@edupme.com")->send(new \App\Mail\SendEmail($data_admin));
            } else {
                Usercourseattempt::where('course_id', $course_id)->where('user_id', $user_id)->where('state', 'complete')->update(['is_mail_send' => '1']);
            }
            /*             * **********************************End Email ******************************* */
            return response()->json(['success' => true, "message" => 'You have successfully completed this course.'], 200);
        }
        abort(404);
    }

    /**
     * Submit Question Answer
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function submitQuestionAnswer(Request $request)
    {

        if ($request->ajax()) {
            $request->validate([
                'course_id' => 'required',
                'question_id' => 'required',
                'course_attempt_id' => 'required',
                'answer' => 'required',
                'choice' => 'required',
                'time_taken' => 'required',
            ]);
            $course_id = decrypt($request->course_id);
            $getCourseType = Course::where('course_id', $course_id)->first()->course_type;
            $question_id = decrypt($request->question_id);
            $course_attempt_id = decrypt($request->course_attempt_id);
            $time_taken = $request->time_taken;
            $user_id = auth()->user()->id;
            $score = 0;
           // echo "here = ".$course_id; exit;
            if ($request->type == "radio") {
                $choice = $request->choice;
                $answer = decrypt($request->answer);
                $queDetail = CourseQuestion::select(['correct_question_ans as rightanswer'])->where(['status' => '1', 'is_delete' => '0', 'id' => $question_id])->first();
                $rightanswer = '0';
                if (!empty($queDetail)) {
                    if ($queDetail['rightanswer'] == $choice) {
                        $rightanswer = '1';
                    }
                }
                $insertData = [
                    'course_id' => $course_id,
                    'question_id' => $question_id,
                    'user_id' => $user_id,
                    'course_attempt_id' => $course_attempt_id,
                    'time_taken' => $time_taken,
                    'rightanswer' => $rightanswer,
                    'answer' => $answer,
                ];
            } else {
                $ans = Questionanswer::select('answer_name')->where('question_id', $question_id)->first();
                $correct = $ans['answer_name'];
                $answer = $request->answer;
                // $sa = New SubmitAnswerController;
                $sa = new GroqAnswerController;
                //$ans = $this->submitAnswerToMatlab($question_id, $answer);
                try {
                    $ans = $sa->getScore($correct, $answer);
                } catch (\Exception $e) {
                    return response()->json(['errors' => ["some_worng" => ['Please enter text answer without special characters.']]], 422);
                }

                if (isset($ans['error'])) {
                    return response()->json(['errors' => ["some_worng" => [$ans['error']]]], 422);
                }
                $rightanswer = $ans['answer'];
                // $sentiment = $ans['sentiment'];
                $score = $ans['score'];
                $insertData = [
                    'course_id' => $course_id,
                    'question_id' => $question_id,
                    'user_id' => $user_id,
                    'course_attempt_id' => $course_attempt_id,
                    'time_taken' => $time_taken,
                    'rightanswer' => (string) $rightanswer,
                    'answer' => $answer,
                    // 'sentiment' => $sentiment,
                    'sentiment' => "",
                ];
            }
            if (isset($request->usable_help) && $request->usable_help != "") {
                $insertData['usable_help'] = $request->usable_help;
            }
            //echo "<pre>"; print_r($insertData); exit;
            $questionHelp = Userquestionattempthistory::create($insertData);
            $rightanswertotal = Userquestionattempthistory::select(['rightanswer'])->where(['course_id' => $course_id, 'question_id' => $question_id, 'user_id' => $user_id, 'course_attempt_id' => $course_attempt_id, 'rightanswer' => '1']);
            $totalright = 0;
            if (!empty($rightanswertotal)) {
                $totalright = $rightanswertotal->count();
            }

            if ($totalright == 3) {
                return response()->json(['success' => true, "message" => ' you are doing well!', 'status' => 'success'], 200);
            } else if ($rightanswer == '0') {
                if ($request->type == "radio") {
                    return response()->json(['success' => true, "message" => "The answer wasn't accurate.", 'status' => 'info'], 200);
                } else {
                    if ($getCourseType == "skippable") {
                        return response()->json(['success' => true, "message" => "Answer successfully submitted.", 'status' => 'info'], 200);
                    } else {
                        return response()->json(['success' => true, "message" => "The answer wasn't accurate your, accuracy is $score%, the question might appear again", 'status' => 'info'], 200);
                    }
                }
            }
            return response()->json(['success' => true, "message" => 'Answer successfully submitted.', 'status' => 'success'], 200);
        }
        abort(404);
    }

    /**
     * Submit Answer to matlab
     * @param type $que_id
     * @param type $answer
     */
    public function submitAnswerToMatlab($que_id, $answer)
    {
        $data = array("nargout" => 2, "rhs" => array($answer, $que_id));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "3000",
            CURLOPT_URL => env('MATLAB_SUBMIT_ANS_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $return_response = array();
        $return_response['answer'] = "0";
        $return_response['sentiment'] = "";
        if ($err) {
            $return_response['error'] = "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            if (isset($response['error'])) {
                $return_response['error'] = isset($response['error']['message']) ? $response['error']['message'] : "Something went wrong";
            } else if (isset($response['lhs'][0]['mwdata'][0])) {
                $return_response['answer'] = (string) $response['lhs'][0]['mwdata'][0];
                $return_response['sentiment'] = $response['lhs'][1]['mwdata'][0];
            }
        }
        return $return_response;
    }

}
