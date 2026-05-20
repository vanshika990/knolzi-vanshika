<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\API\SubmitAnswer\SubmitAnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Validator;
use App\Mail\SendEmail;
use App\Models\User;
use App\Models\ReviewerCourse;
use App\Models\ReviewerUserCourseAttempt;
use App\Models\CourseQuestion;
use App\Models\QuestionHashelp;
use App\Models\QuestionHashint;
use App\Models\Questionanswer;
use App\Models\CourseHasQA;
use App\Models\UserHasCourseComment;
use App\Models\CourseHasReview;
use App\Models\ReviewerUserQuestionAttemptHistory;
use App\Models\Course;
use App\Models\QuestionhasImagehint;
use App\Models\QuestionhasImagehelp;

class ReviewerCourseController extends BaseController {

    public $paginationlimit = 10;

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
//                ->where('tbl_course.status', '1')
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
     * Start Course attempt 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function startReviewerCourseAttempt(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id,is_delete,0',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $courseAttempt = ReviewerUserCourseAttempt::select(['id AS course_attempt_id', 'state', 'start_time'])->where(['user_id' => $request->user_id, 'course_id' => $request->course_id, 'state' => 'process'])->first();
        if (empty($courseAttempt)) {
            $insert = [
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
                'state' => 'process',
                'start_time' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $lastInsertedData = ReviewerUserCourseAttempt::create($insert);
            $courseAttempt = [
                'course_attempt_id' => $lastInsertedData['id'],
                'state' => $lastInsertedData['state'],
                'start_time' => $lastInsertedData['start_time']
            ];
        }
        $success['data'] = $courseAttempt;
        return $this->sendResponse($success, 'Success.');
    }

    public function GetUserLastQuestion($course_attempt_id) {
        return ReviewerUserQuestionAttemptHistory::leftJoin('tbl_course_question', 'tbl_reviewer_user_question_attempt_history.question_id', '=', 'tbl_course_question.id')->where('tbl_reviewer_user_question_attempt_history.course_attempt_id', $course_attempt_id)->where('tbl_course_question.is_delete', '0')->orderBy('tbl_reviewer_user_question_attempt_history.id', 'desc')
                        ->first(['question_id']);
    }

    /**
     * Get Question by Course ID  
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getReviewerNextQuestion(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'attempt_id' => 'required|exists:App\Models\ReviewerUserCourseAttempt,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $queDetail = [];
        $input = $request->all();
        $get_que_id_matlab = "";
        $counter = 1;
        $course_id = $input['course_id'];
        $user_id = $input['user_id'];
        $attempt_id = $input['attempt_id'];
        $last_question = $this->GetUserLastQuestion($attempt_id);
        if (!empty($last_question)) {
            $get_que_id_matlab = $this->GetDataFromMatlab($last_question->question_id, $course_id, $attempt_id, $user_id);
        } else {
            $get_que_id_matlab = $this->GetDataFromMatlab(1, $course_id, $attempt_id, $user_id);
        }
        if (isset($get_que_id_matlab['error'])) {
            return $this->sendError('Error.', ['status' => $get_que_id_matlab['error']]);
        }
        $question_id = $get_que_id_matlab['que_id'];

        $question = CourseQuestion::find($question_id);
        if (empty($question)) {
            return $this->sendError('Error.', ['status' => "Question not found!"]);
        }
        if (strpos($question['question_name'], 'math-tex') !== false) {
            $question['question_name'] = 'r"""<style>mjx-container { display: contents !important;}</style><script type="text/x-mathjax-config"> MathJax.Hub.Config({ showMathMenu: false, extensions: ["tex2jax.js"], jax: ["input/TeX", "output/HTML-CSS"], tex2jax: { processClass: "equation" } });</script><script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js"></script>' . $question['question_name'] . '"""';
        }
        if ($question['question_media_type'] == "html") {
            if (trim($question['question_media']) != "") {
                $html_head = "<!DOCTYPE html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, user-scalable=no'>
                    <style>html, body { overflow-x: hidden !important; } table { width: 100% !important; } 
                    img { vertical-align: middle; width:100% !important; height: auto !important; text-align: center; margin: auto; display: block; margin-bottom: 10px;
                    } p,span,li,b { font-size:23px !important; text-align: justify; line-break: auto; width:100%; color: #000 !important;line-height: 1.5; } 
                        table, td, th { border: 1px solid #ddd; text-align: left; } table { border-collapse: collapse; width: 100%; } th, td { padding: 15px; }</style></head><body>";
                if (strpos($question['question_media'], 'math-tex') !== false) {
                    $question['question_media'] = $html_head . '<style>mjx-container { display: contents !important;} p,span,li,b { font-size:20px !important;}</style><script type="text/x-mathjax-config"> MathJax.Hub.Config({ showMathMenu: false, extensions: ["tex2jax.js"], jax: ["input/TeX", "output/HTML-CSS"], tex2jax: { processClass: "equation" } });</script><script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js"></script>' . $question['question_media'] . '</body></html>';
                } else {
                    $question['question_media'] = $html_head . str_replace('&nbsp;', ' ', $question['question_media']) . '</body></html>';
                }
            }
        }
        if ($question['question_type'] == "single" || $question['question_type'] == "multi") {
            $question['question_type'] = "radio";
        }

        if ($question['question_media_type'] == "multi") {
            $question['question_media_multi'] = explode(',', $question['question_media_multi']);
        } else {
            $question['question_media_multi'] = [];
        }
        $help = $this->getQuestionHelp($question_id);
        $hint = $this->getQuestionHint($question_id);
        $question_ans = $this->getQuestionAnswer($question_id);
        $course_qa = CourseHasQA::select('question_name', 'answer')->where(['course_id' => $course_id, 'status' => '1'])->get()->toArray();
        $course_comment = UserHasCourseComment::select('comment')->where(['course_id' => $course_id, 'user_id' => $user_id])->get()->toArray();
        $review = CourseHasReview::select('rate', 'review')->where(['user_id' => $user_id, 'course_id' => $course_id])->get()->toArray();
        unset($question['correct_question_ans']);
        $queDetail['question'] = $question;
        $queDetail['option'] = [];
        if ($question['question_type'] == "radio") {
            foreach ($question_ans as $opt) {
                if ($opt['choice_type'] == '1') {
                    $html_head = "<!DOCTYPE html><html><head><meta charset='utf-8'><meta http-equiv='X-UA-Compatible' content='IE=edge' /><meta charset='UTF-8' /><meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no'><style>html, body { width:100% !important; height:100% !important; overflow-x: hidden !important; color: #000; background-color: #e2e2e2 !important;}  
                        div#MathJax_Message { display: none; } body { visibility: hidden; } body:after { visibility: visible; content: 'Loading...'; margin-left: 0.5pc; }
                        body.no-after:after{content:'';} body.no-after { visibility: visible; }</style>";
                    $opt['answer_name'] = $html_head . 'r"""<style>mjx-container { color: #000; font-weight:bold;  display: contents !important;}</style><script type="text/x-mathjax-config"> MathJax.Hub.Config({ showMathMenu: false, extensions: ["tex2jax.js"], jax: ["input/TeX", "output/HTML-CSS"], tex2jax: { processClass: "equation" } }); 
                        MathJax.Hub.Queue(function () { document.body.classList.add("no-after"); });</script><script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js"></script></head><body>' . $opt['answer_name'] . '</body></html>"""';
                }
                $queDetail['option'][] = $opt;
            }
        }
        $queDetail['is_complete'] = $get_que_id_matlab['is_complete'];
        $queDetail['help'] = $help;
        $queDetail['hint'] = $hint;
        $queDetail['course_qa'] = $course_qa;
        $queDetail['course_comment'] = $course_comment;
        $queDetail['review'] = $review;

        // QuestionCount new
        $multiRightCount = ReviewerUserQuestionAttemptHistory::select(DB::raw('count(distinct tbl_reviewer_user_question_attempt_history.question_id) as question_count'))
                ->leftJoin('tbl_course_question', 'tbl_course_question.id', '=', 'tbl_reviewer_user_question_attempt_history.question_id')
                ->where('tbl_course_question.status', '1')
                ->where('tbl_course_question.is_delete', '0')
                ->where('tbl_course_question.question_type', 'multi')
                ->where('tbl_reviewer_user_question_attempt_history.user_id', $user_id)
                ->where('tbl_reviewer_user_question_attempt_history.course_attempt_id', $attempt_id)
                ->where('tbl_reviewer_user_question_attempt_history.rightanswer', '1')
                ->get();
        $multiQuestionCount = CourseQuestion::select('id')->where('course_id', $course_id)->where('question_type', 'multi')->where('is_delete', '0')->where('status', '1')->count();
        $queDetail['progressCount'] = 0;
        if (!empty($multiRightCount) && $multiRightCount[0]->question_count != 0) {
            $rightMultiQuestionPercen = round(($multiRightCount[0]->question_count / $multiQuestionCount) * 100);
            $queDetail['progressCount'] = round($rightMultiQuestionPercen, 2);
        }
        $success['data'] = $queDetail;
        return $this->sendResponse($queDetail, 'Success.');
    }

    /**
     * Submit Question Data 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SubmitQuestion(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id,status,1',
                    'course_id' => 'required|exists:App\Models\Course,course_id,is_delete,0',
                    'question_id' => 'required|exists:App\Models\CourseQuestion,id,is_delete,0',
                    'course_attempt_id' => 'required',
                    'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($request->has('time_taken')) {
            $validator = Validator::make($request->all(), [
                        'time_taken' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }
        $questionDetail = CourseQuestion::select(['correct_question_ans as rightanswer', 'question_type'])->where(['status' => '1', 'is_delete' => '0', 'id' => $request->question_id])->first();
        if ($questionDetail->question_type == 'single' || $questionDetail->question_type == "multi") {
            $validator = Validator::make($request->all(), [
                        'choice' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            if (!empty($questionDetail)) {
                if ($questionDetail['rightanswer'] == $request->choice) {
                    $rightanswer = '1';
                } else {
                    $rightanswer = '0';
                }
            } else {
                $rightanswer = '0';
            }
            $insertData = [
                'course_id' => $request->course_id,
                'question_id' => $request->question_id,
                'user_id' => $request->user_id,
                'course_attempt_id' => $request->course_attempt_id,
                'time_taken' => $request->time_taken,
                'rightanswer' => $rightanswer,
                'answer' => $request->answer,
            ];
        } else {
            $ans = Questionanswer::select('answer_name')->where('question_id',$request->question_id)->first();
            $correct = $ans['answer_name'];
            $answer = $request->answer;
            $sa = New SubmitAnswerController;
            //$ans = $this->submitAnswerToMatlab($request->question_id, $answer);

            try{
                $ans = $sa->submitAnswer($correct, $answer);
            }catch(\Exception $e){
                return response()->json(['errors' => ["some_worng" => ['Please enter text answer without special characters.']]], 422);                   
            }
            if (isset($ans['error'])) {
                return response()->json(['errors' => ["some_worng" => [$ans['error']]]], 422);
            }
            $rightanswer = (string) $ans['answer'];
            $sentiment = $ans['sentiment'];
            // dd((string) $rightanswer);
            $insertData = [
                'course_id' => $request->course_id,
                'question_id' => $request->question_id,
                'user_id' => $request->user_id,
                'course_attempt_id' => $request->course_attempt_id,
                'time_taken' => $request->time_taken,
                'rightanswer' => $rightanswer,
                'answer' => $request->answer,
                'rightanswer' => $rightanswer,
                'sentiment' => $sentiment,
            ];
        }
       // dd($insertData);
        if (isset($request->usable_help)) {
            $insertData['usable_help'] = $request->usable_help;
        }
        $questionHelp = ReviewerUserQuestionAttemptHistory::create($insertData);
        $rightanswertotal = ReviewerUserQuestionAttemptHistory::select(['rightanswer'])->where(['course_id' => $request->course_id, 'question_id' => $request->question_id, 'user_id' => $request->user_id, 'course_attempt_id' => $request->course_attempt_id, 'rightanswer' => '1']);

        $totalright = 0;
        if (!empty($rightanswertotal)) {
            $totalright = $rightanswertotal->count();
        }

        $data = [
            'totalrightanswer' => $totalright,
            'rightanswer' => $rightanswer,
        ];

        $success['data'] = $data;
        return $this->sendResponse($success, 'Answer successfully submitted.');
    }

    /**
     * Submit Answer to matlab
     * @param type $que_id
     * @param type $answer
     */
    public function submitAnswerToMatlab($que_id, $answer) {
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

    /**
     * FInish Course Attempt
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function ReviewerFinishCourseAttempt(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'course_attempt_id' => 'required|exists:App\Models\ReviewerUserCourseAttempt,id,course_id,' . $request->course_id . ',user_id,' . $request->user_id . '',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $update = [
            'state' => 'complete',
            'end_time' => now()
        ];
        $updated = ReviewerUserCourseAttempt::where('id', $input['course_attempt_id'])->update($update);

        /*         * **********************************Start Email ******************************* */
        $course = Course::select(['course_price', 'course_name'])->where('course_id', $request->course_id)->first();
        $users = User::find($request->user_id);
        $data = [
            'template' => 'CourseComplete',
            'html_body' => [
                'name' => $users->name,
                'course' => $course->course_name
            ],
            'subject' => 'Congratulations for completing the module'
        ];

        \Mail::to($users->email)->send(new \App\Mail\SendEmail($data));

        /*         * **********************************End Email ******************************************************************************************************** */
        $success['success'] = true;
        return $this->sendResponse($success, 'You have successfully completed this course.');
    }

    /**
     * getNextToC
     * @param type $userID
     * @param type $courseID
     * @param type $sqlCounter
     * @return type
     */
    private function getNextToC($userID, $courseID, $sqlCounter) {
        $query1 = DB::select("
            SELECT tbl_course_question.que_toc_no as allTOC FROM tbl_course_question WHERE  tbl_course_question.is_delete='0' AND tbl_course_question.status ='1' AND course_id= $courseID
            ");
        foreach ($query1 as $data) {
            $allTOC[] = $data->allTOC;
        }
        if (empty($allTOC)) {
            $allTOC[] = 0;
        }
        $query2 = DB::select("
            SELECT tbl_course_question.que_toc_no as currentToC FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND id= $sqlCounter AND tbl_course_question.course_id IN (SELECT course_id FROM tbl_reviewer_user_question_attempt_history  WHERE tbl_reviewer_user_question_attempt_history .user_id= $userID)
            ");
        foreach ($query2 as $data) {
            $currentToC[] = $data->currentToC;
        }

        if ($sqlCounter == 1) {
            $query2 = DB::select("
                SELECT tbl_course_question.que_toc_no as currentToC FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID
                 AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' ORDER BY que_toc_no ASC LIMIT 1
                ");
            foreach ($query2 as $data) {
                $currentToC[] = $data->currentToC;
            }
        }
        $unique_toc = natsort($allTOC);
        $clength = count($allTOC);
        for ($x = 0; $x < $clength; $x++) {
            $sorted_array[$x] = $allTOC[$x];
        }
        $sortedToC = array_unique($sorted_array);
        array_unshift($sortedToC, "");
        unset($sortedToC[0]);
        $maxTOC = count($sortedToC);
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
     * getQuestionIndex
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    private function getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("
            SELECT id AS question_id FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_level= $currentLevel AND tbl_course_question.id NOT IN (SELECT question_id FROM tbl_reviewer_user_question_attempt_history  WHERE tbl_reviewer_user_question_attempt_history.course_id = $courseID  AND tbl_reviewer_user_question_attempt_history.user_id= $userID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID AND tbl_reviewer_user_question_attempt_history.rightanswer='1' GROUP BY question_id)
            ");
        foreach ($query1 as $data) {
            $indices[] = $data->question_id;
        }
        if (empty($indices)) {
            $indices = NULL;
        }
        return [$indices];
    }

    /**
     * GetGyanIndex
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    private function GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("
            SELECT tbl_course_question.id as id , tbl_course_question.que_level as que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)
            ");
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
     * isAllCorrectAtTOC
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return int
     */
    private function isAllCorrectAtTOC($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("
        select COUNT(*) as total_cur_toc from tbl_course_question where que_toc_no=  '$currentToC'  AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.course_id= $courseID
        ");
        foreach ($query1 as $data) {
            $check_toc = $data->total_cur_toc;
        }
        $query2 = DB::select("
        SELECT id as question_id FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.id IN (SELECT question_id FROM tbl_reviewer_user_question_attempt_history  WHERE tbl_reviewer_user_question_attempt_history.course_id = $courseID AND tbl_reviewer_user_question_attempt_history.user_id= $userID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID AND tbl_reviewer_user_question_attempt_history.rightanswer='1' GROUP BY question_id)
        ");
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
     * getPastCorrect
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    private function getPastCorrect($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("
        SELECT COUNT(id) as Count_id FROM (SELECT * FROM tbl_reviewer_user_question_attempt_history WHERE tbl_reviewer_user_question_attempt_history.user_id = $userID AND tbl_reviewer_user_question_attempt_history.course_id= $courseID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID ORDER BY tbl_reviewer_user_question_attempt_history.id DESC LIMIT 1) sub WHERE rightanswer='1'
        ");
        foreach ($query1 as $data) {
            $nTrue = $data->Count_id;
        }
        return [$nTrue];
    }

    /**
     * getPastFalse
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    function getPastFalse($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("
        SELECT COUNT(id) as Count_id FROM (SELECT * FROM tbl_reviewer_user_question_attempt_history WHERE tbl_reviewer_user_question_attempt_history.user_id = $userID AND tbl_reviewer_user_question_attempt_history.course_id= $courseID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID ORDER BY tbl_reviewer_user_question_attempt_history.id DESC LIMIT 1) sub WHERE rightanswer='0'
        ");
        foreach ($query1 as $data) {
            $nFalse = $data->Count_id;
        }
        return [$nFalse];
    }

    /**
     * getIntent
     * @param type $courseID
     * @param type $currentToC
     * @param type $sqlCounter
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return int
     */
    private function getIntent($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("
        SELECT question_intent_id FROM tbl_course_question WHERE tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.id= $sqlCounter AND tbl_course_question.course_id= $courseID
        ");
        foreach ($query1 as $data) {
            $getIntent[] = $data->question_intent_id;
        }
        if (empty($getIntent)) {
            $getIntent[] = 0;
        }
        $isRememberUnderstan = $getIntent[0];
        if ($isRememberUnderstan == 1 || $isRememberUnderstan == 3) {
            $$isRememberUnderstan = $getIntent;
        } else {
            $isRememberUnderstan = 0;
        }
        return $isRememberUnderstan;
    }

    /**
     * GetGyanIndex1
     * @param type $courseID
     * @param type $currentToC
     * @param type $sqlCounter
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    private function GetGyanIndex1($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("
        SELECT tbl_course_question.id as question_ID , tbl_course_question.que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)
        ");
        foreach ($query1 as $data) {
            $GyanIndices = $data->question_ID;
        }
        $query2 = DB::select("
        SELECT tbl_course_question.que_level as que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)
        ");
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
     * Get Question From Matlab
     * @param type $last_id
     * @param type $counter
     * @param type $course_id
     * @param type $attempt_id
     * @param type $user_id
     * @return type
     */
    public function GetDataFromMatlab($sqlCounter, $courseID, $attemptID, $userID) {
        /* echo "counter = ".$sqlCounter;
          echo "courseID = ".$courseID;
          echo "attemptID = ".$attemptID;
          echo "userID = ".$userID; exit; */
        [$currentToC, $nextToC, $maxTOC, $isEndToC, $sortedToC] = $this->getNextToC($userID, $courseID, $sqlCounter);
        $maxLevel = 1;  # Only one level
        $qCounter = $sqlCounter;
        $currentLevel = '1';
        $currentToC = $currentToC[0];

        #Find indices of questions
        [$indices] = $this->getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID);

        $levelIndices = $indices;
        if (is_array($levelIndices)) {
            $b = array_rand($levelIndices, 1);
            $levelIndices = $levelIndices[$b];
        }

        [$GyanIndices, $GyanLevel] = $this->GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID);
        #Start index for Gyan only questions 
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

        if (empty($levelIndices) == 0) {
            if (is_array($levelIndices)) {
                $startLevel = min($levelIndices);
            } else {
                $startLevel = $levelIndices;
            }
        }

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

        return ['que_id' => $nextQuestion, 'is_complete' => $CourseComplete];
    }

    /**
     * Get Question Hint
     * @param type $question_id
     * @return type
     */
    public function getQuestionHint($question_id) {
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
    public function getQuestionHintImages($question_id) {
        $image_hint = QuestionhasImagehint::select('image')->where('question_id', $question_id)->get()->toArray();
        return $image_hint;
    }

    /**
     * Get Question
     * @param type $question_id
     * @return type
     */
    public function getQuestionAnswer($question_id) {
        return Questionanswer::where('question_id', $question_id)->orderBy('answer_order', 'ASC')->get()->toArray();
    }

    /**
     * Get Question Help
     * @param type $question_id
     * @return type
     */
    public function getQuestionHelp($question_id) {
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
    public function getQuestionHelpImages($question_id) {
        $image_help = QuestionhasImagehelp::select('image')->where('question_id', $question_id)->get()->toArray();
        return $image_help;
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
