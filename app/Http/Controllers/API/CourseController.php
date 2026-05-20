<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Front\AkgecskillsController;
use App\Http\Controllers\API\SubmitAnswer\SubmitAnswerController;
use App\Models\User;
use App\Models\Usercourseattempt;
use App\Models\CourseQuestion;
use App\Models\CourseSubscription;
use App\Models\Userquestionattempthistory;
use App\Models\QuestionHashelp;
use App\Models\QuestionhasImagehelp;
use App\Models\QuestionHashint;
use App\Models\QuestionhasImagehint;
use App\Models\Questionanswer;
use App\Models\CourseHasQA;
use App\Models\UserHasCourseComment;
use App\Models\CourseHasReview;
use App\Models\Course;
use App\Models\Category;
use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class CourseController extends BaseController {

    public $paginationlimit = 10;

    /**
     * Get list of company user
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function GetListOfCompanyUser(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:App\Models\User,id,status,1',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $company_id = $request->id;
        $data = User::where('company_id', $company_id)->where('status', '1')->paginate($this->paginationlimit, ['*']);
        $success['data'] = $data;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * I learn/My Module
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getMyCourses(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id,status,1',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user_id = $request->user_id;
        $both_user_id = [$user_id];
        $company_id = $request->company_id;
        if (!empty($company_id)) {
            array_push($both_user_id, $company_id);
        }
        $query = CourseSubscription::leftJoin('tbl_course', 'tbl_course_subscription.course_id', '=', 'tbl_course.course_id')
                ->leftJoin('tbl_course_has_user', 'tbl_course_has_user.course_id', '=', 'tbl_course_subscription.course_id')
                ->leftJoin('tbl_user', 'tbl_user.id', '=', 'tbl_course_has_user.user_id')
                ->leftJoin(DB::Raw('(SELECT r.course_id, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record FROM tbl_course_has_review_rate AS r) s'), 's.course_id', '=', 'tbl_course.course_id')
                ->where('tbl_course.status', '1')
                ->whereIn('tbl_course_subscription.user_id', $both_user_id)
                ->where('tbl_course_subscription.status', '1')
                ->where('tbl_course_subscription.sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                ->groupBy('tbl_course.course_id')
                ->select('tbl_course_subscription.id as subscription_id', DB::Raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") author_name'), DB::Raw('IFNULL(s.rate, 0) AS rate'), DB::Raw('IFNULL(s.total_record, 0) AS total_record'), 'tbl_course.course_id', 'tbl_course.course_name', 'tbl_course.course_image', 'tbl_course.slug', 'tbl_course.course_price');
        if (!Auth::user()->hasRole('organization')) {
            $query->leftJoin('tbl_course_subscription_licence', 'tbl_course_subscription.id', '=', 'tbl_course_subscription_licence.course_subscription_id');
            $query->where('tbl_course_subscription_licence.status', '1');
            $query->where('tbl_course_subscription_licence.user_id', $user_id);
        }
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
        $success['data'] = $subscribe_course;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Get Last State of course
     * @param type $user_id
     * @param type $course_id
     * @return type
     */
    public function GetUserCourseState($user_id, $course_id) {
        return Usercourseattempt::Where(['user_id' => $user_id, 'course_id' => $course_id, 'state' => 'process'])->first();
    }

    public function GetUserLastQuestion($course_attempt_id) {
        return Userquestionattempthistory::leftJoin('tbl_course_question', 'tbl_user_question_attempt_history.question_id', '=', 'tbl_course_question.id')->where('tbl_user_question_attempt_history.course_attempt_id', $course_attempt_id)->where('tbl_course_question.status', '1')->where('tbl_course_question.is_delete', '0')->orderBy('tbl_user_question_attempt_history.id', 'desc')
                        ->first(['question_id']);
    }

    /**
     * Get Question by Course ID
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getNextQuestion(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'attempt_id' => 'required|exists:App\Models\Usercourseattempt,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $akg = new AkgecskillsController;
        $queDetail = [];
        $input = $request->all();
        $get_que_id_matlab = "";
        $counter = 1;
        $course_id = (int) $input['course_id'];
        $user_id = (int) $input['user_id'];
        $attempt_id = (int) $input['attempt_id'];
        $last_question = $this->GetUserLastQuestion($attempt_id);
        dd($course_id);
        if ($course_id == '171') {
            if (!empty($last_question)) {
                $get_que_id_matlab = $akg->GetAkgDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
                //$this->GetDataFromMatlab($last_question->question_id, $counter, $course_id, $attempt_id, $user_id);
            } else {
                $get_que_id_matlab = $akg->GetAkgDataFromMatlab(1, $course_id, $attempt_id, $user_id);
                //$this->GetDataFromMatlab(1, $counter, $course_id, $attempt_id, $user_id);
            }
        } else {
            if (!empty($last_question)) {
                $get_que_id_matlab = $this->GetDataFromMatlab($last_question['question_id'], $course_id, $attempt_id, $user_id);
                //$this->GetDataFromMatlab($last_question->question_id, $counter, $course_id, $attempt_id, $user_id);
            } else {
                $get_que_id_matlab = $this->GetDataFromMatlab(1, $course_id, $attempt_id, $user_id);
                //$this->GetDataFromMatlab(1, $counter, $course_id, $attempt_id, $user_id);
            }
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
            $html_head = "<!DOCTYPE html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, user-scalable=no'>
                    <style>html, body { overflow-x: hidden !important;} table { width: 100% !important; }  img { vertical-align: middle; width:100% !important; height: auto !important; text-align: center; margin: auto; display: block; margin-bottom: 10px;
                    } p,span,li,b { font-size:23px !important; text-align: justify; line-break: auto; width:100%; color: #000 !important;line-height: 1.5; }
                        table, td, th { border: 1px solid #ddd; text-align: left; } table { border-collapse: collapse; width: 100%; } th, td { padding: 15px; }</style></head><body>";
//        $question['question_name'] = '<style>mjx-container { display: contents !important;}</style><script type="text/javascript" id="MathJax-script" src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>' . $question['question_name'];
            if (trim($question['question_media']) != "") {
                if (strpos($question['question_media'], 'math-tex') !== false) {
                    $question['question_media'] = 'r"""' . $html_head . '<style>mjx-container { display: contents !important;} p,span,li,b { font-size:20px !important;}</style><script type="text/x-mathjax-config"> MathJax.Hub.Config({ showMathMenu: false, extensions: ["tex2jax.js"], jax: ["input/TeX", "output/HTML-CSS"], tex2jax: { processClass: "equation" } });</script><script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js"></script>' . $question['question_media'] . '</body></html>"""';
                } else {
                    $question['question_media'] = $html_head . $question['question_media'] . '</body></html>';
//                    $question['question_media'] = $html_head."<style>p,span,li,b { font-size:30px !important;} body { overflow-y: hidden;overflow-x: hidden;}</style>" . $question['question_media'];
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
        $queDetail['progressCount'] = 0;
        if (!empty($multiRightCount) && $multiRightCount[0]->question_count != 0) {
            $rightMultiQuestionPercen = round(($multiRightCount[0]->question_count / $multiQuestionCount) * 100);
            $queDetail['progressCount'] = round($rightMultiQuestionPercen, 2);
        }

        $que_toc_no = $question['que_toc_no'];
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
        $queDetail['chapter_progress'] = $chapter_progress;
        $success['data'] = $queDetail;
        return $this->sendResponse($queDetail, 'Success.');
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
     * Get Question From Matlab/Local
     * @param type $last_id
     * @param type $counter
     * @param type $course_id
     * @param type $attempt_id
     * @param type $user_id
     * @return type
     */
    public function GetDataFromMatlab($sqlCounter, $courseID, $attemptID, $userID) {
        //dd($sqlCounter, $courseID, $attemptID, $userID); exit;
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
        return ['que_id' => $nextQuestion, 'is_complete' => $CourseComplete];
    }

    /**
     * Get Next TOC
     * @param type $userID
     * @param type $courseID
     * @param type $sqlCounter
     * @return type
     */
    public function getNextToC($userID, $courseID, $sqlCounter) {
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
    public function GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
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
    public function isAllCorrectAtTOC($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
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
    public function getPastCorrect($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
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
    public function getPastFalse($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
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
    public function getIntent($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID) {
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
    public function getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
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
    public function GetGyanIndex1($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID) {
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
    public function getStartTOCIndex($course_id, $nextToC) {
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
    public function getcurrentTocQuestion($course_id, $nextToC) {
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
    public function getQuestionHelp($question_id) {
        $help = [];
        $helps = QuestionHashelp::where('question_id', $question_id)->first();
        unset($helps['image']);
        if (!empty($helps)) {
            $help = $helps->toArray();
        }
        $help['images'] = $this->getQuestionHelpImages($question_id);
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
     * Get Question Hint
     * @param type $question_id
     * @return type
     */
    public function getQuestionHint($question_id) {
        $hint = [];
        $hints = QuestionHashint::where('question_id', $question_id)->first();
        unset($hints['image']);
        if (!empty($hints)) {
            $hint = $hints->toArray();
        }
        $hint['images'] = $this->getQuestionHintImages($question_id);
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
     * Start Course attempt
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function startCourseAttempt(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id,status,1,is_delete,0',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $courseAttempt = Usercourseattempt::select(['id AS course_attempt_id', 'state', 'start_time'])->where(['user_id' => $request->user_id, 'course_id' => $request->course_id, 'state' => 'process'])->first();
        if (empty($courseAttempt)) {
            $insert = [
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
                'state' => 'process',
                'start_time' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $lastInsertedData = Usercourseattempt::create($insert);
            $courseAttempt = [
                'course_attempt_id' => $lastInsertedData['id'],
                'state' => $lastInsertedData['state'],
                'start_time' => $lastInsertedData['start_time']
            ];
        }

        $success['data'] = $courseAttempt;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Submit Question Data
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SubmitQuizData(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id,status,1',
                    'course_id' => 'required|exists:App\Models\Course,course_id,status,1,is_delete,0',
                    'question_id' => 'required|exists:App\Models\CourseQuestion,id,status,1,is_delete,0',
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
        if (isset($request->usable_help)) {
            $insertData['usable_help'] = $request->usable_help;
        }
        $questionHelp = Userquestionattempthistory::create($insertData);
        $rightanswertotal = Userquestionattempthistory::select(['rightanswer'])->where(['course_id' => $request->course_id, 'question_id' => $request->question_id, 'user_id' => $request->user_id, 'course_attempt_id' => $request->course_attempt_id, 'rightanswer' => '1']);
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
    public function FinishCourseAttempt(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'course_attempt_id' => 'required|exists:App\Models\Usercourseattempt,id,course_id,' . $request->course_id . ',user_id,' . $request->user_id . '',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $update = [
            'state' => 'complete',
            'end_time' => now()
        ];
        $updated = Usercourseattempt::where('id', $input['course_attempt_id'])->update($update);

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

        $mailsenddata = Usercourseattempt::select(['user_id', 'course_id', 'is_mail_send'])->where('state', 'complete')->where('user_id', $request->user_id)->where('course_id', $request->course_id)->where('is_mail_send', '1')->count();
        if ($mailsenddata <= 0) {
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
            Usercourseattempt::where('course_id', $request->course_id)->where('user_id', $request->user_id)->where('state', 'complete')->update(['is_mail_send' => '1']);
        }

        /*         * **********************************End Email ******************************* */
        $success['success'] = true;
        return $this->sendResponse($success, 'You have successfully completed this course.');
    }

    /**
     * Search with term
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function searchTerm(Request $request) {
        $validator = Validator::make($request->all(), [
                    'term' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $search = $request->term;
        $result = Course::select('course_id', 'course_name', 'course_image')->where('course_name', 'LIKE', '%' . $search . '%')->where('status', '1')->get()->toArray();
        $res = Category::select('name', 'id')->where('name', 'LIKE', '%' . $search . '%')->where('status', '1')->get()->toArray();

        $success['course'] = $result;
        $success['category'] = $res;

        $success['success'] = true;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * Search with course
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function searchCourse(Request $request) {
        $validator = Validator::make($request->all(), [
                    'term' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $search = $request->term;
        $all_course = Course::select(DB::raw('GROUP_CONCAT(tbl_course_category.name SEPARATOR ",") as cat_name'), DB::raw('GROUP_CONCAT(tbl_user.name SEPARATOR ",") as author_name'), DB::Raw('IFNULL( `cv`.`views` , 0 ) as views'), DB::Raw('IFNULL( `s`.`rate` , 0 ) as rate'), DB::Raw('IFNULL( `s`.`total_record` , 0 ) as total_record'), 'tbl_course.*')
                ->leftjoin('tbl_has_course_category', 'tbl_course.course_id', '=', 'tbl_has_course_category.course_id')
                ->leftjoin('tbl_course_category', 'tbl_has_course_category.cat_id', '=', 'tbl_course_category.id')
                ->leftJoin('tbl_course_has_user', 'tbl_course.course_id', '=', 'tbl_course_has_user.course_id')
                ->leftJoin('tbl_user', 'tbl_course_has_user.user_id', '=', 'tbl_user.id')
                ->leftJoin(DB::raw('(SELECT r.`course_id`, SUM(r.`rate`) AS rate, COUNT("r.*") AS total_record
                FROM `tbl_course_has_review_rate` AS r
                GROUP BY r.`course_id`) AS s'), 'tbl_course.course_id', '=', 's.course_id')
                ->leftJoin(DB::raw('(SELECT v.`course_id`, COUNT(v.`course_id`) AS views
                FROM `tbl_course_views` AS v
                GROUP BY v.`course_id`) AS cv'), 'tbl_course.course_id', '=', 'cv.course_id')
                ->where('tbl_course.course_name', 'like', '%' . $search . '%')
                ->orwhere('tbl_course_category.name', 'like', '%' . $search . '%')
                ->orwhere('tbl_user.name', 'like', '%' . $search . '%')
                ->where('tbl_course.status', "1")
                ->where('tbl_course.is_delete', "0")
                ->groupBy('tbl_course.course_id')
                ->paginate(10);
        if (!empty($all_course)) {
            for ($i = 0; $i < count($all_course); $i++) {
                $all_course[$i]->course_price = (string) currencyConvert($all_course[$i]->course_price);
                $all_course[$i]->symbol = getCurrencySymbol();
            }
        }

        $success['course'] = $all_course;
        $success['success'] = true;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * add user review/rating
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function addUserRating(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'rate' => 'required|numeric',
                    'review' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            $insertdata = [
                'course_id' => $request->course_id,
                'user_id' => $request->user_id,
                'rate' => $request->rate,
                'review' => $request->review,
            ];
            CourseHasReview::create($insertdata);
            $success['success'] = true;
            return $this->sendResponse($success, 'Review added successfully.');
        } catch (Exception $e) {
            return response()->json(['errors' => ["some_worng" => ['Already Submited Rate and review.']]], 422);
        }
    }

    /**
     * get user review/rating
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getUserRating(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $data = CourseHasReview::select()->where(['user_id' => $request->user_id, 'course_id' => $request->course_id])->get();
        $success['data'] = $data;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * add Course QA
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function addCourseQA(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'question' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $insertdata = [
            'course_id' => $request->course_id,
            'user_id' => $request->user_id,
            'question_name' => $request->question,
        ];
        CourseHasQA::create($insertdata);
        $success['success'] = true;
        return $this->sendResponse($success, 'Q & A added successfully.');
    }

    /**
     * get Course QA
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getCoursQA(Request $request) {
        $validator = Validator::make($request->all(), [
                    'course_id' => 'required|exists:App\Models\Course,course_id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $data = CourseHasQA::select()->where(['course_id' => $request->course_id])->get();
        $success['data'] = $data;
        return $this->sendResponse($success, 'Success.');
    }

    /**
     * add User Comment
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function addUserComment(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:App\Models\User,id',
                    'course_id' => 'required|exists:App\Models\Course,course_id',
                    'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $insertdata = [
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'comment' => $request->comment,
        ];
        UserHasCourseComment::create($insertdata);
        $success['success'] = true;
        return $this->sendResponse($success, 'Comment added successfully.');
    }

}
