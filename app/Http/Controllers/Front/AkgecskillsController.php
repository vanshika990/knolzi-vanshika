<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use DB;

class AkgecskillsController extends Controller {

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
        $unique_toc = sort($allTOC);
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
     * Get Question Index
     * @param type $courseID
     * @param type $currentToC
     * @param type $currentLevel
     * @param type $userID
     * @param type $attemptID
     * @return type
     */
    public function getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID) {
        $query1 = DB::select("SELECT id AS question_id FROM tbl_course_question WHERE tbl_course_question.course_id ='$courseID' AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_level='1'");

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
     * Get Question From Matlab/Local
     * @param type $last_id
     * @param type $counter
     * @param type $course_id
     * @param type $attempt_id
     * @param type $user_id
     * @return type
     */
    public function GetAkgDataFromMatlab($sqlCounter, $courseID, $attemptID, $userID) {
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
        /*if (is_array($levelIndices)) {
            $b = array_rand($levelIndices, 1);
            $levelIndices = $levelIndices[$b];
        }*/

        [$GyanIndices, $GyanLevel] = $this->GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID); #Start index for Gyan only questions
        $startLevel = min($levelIndices);

        $sortedToC = array_values($sortedToC);
        $clength = count($sortedToC);

        # Fetch number of correct and incorrect answers in the most recent attempt
        /*for ($x = 0; $x < $clength; $x++) {
            $Loop_currentToC = $sortedToC[$x];
            $isTOCFinished = $this->isAllCorrectAtTOC($courseID, $Loop_currentToC, $currentLevel, $userID, $attemptID);
            $isTOCFinished1[$x] = $isTOCFinished;
        }*/

        array_unshift($levelIndices,"");
        unset($levelIndices[0]);
        $currentIndex = array_search("$sqlCounter",$levelIndices);
         if($currentIndex == count($levelIndices))
         {
            $isTOCFinished = 1;
         }
        else
        {
             $isTOCFinished = 0;
        }

        // print_r($isTOCFinished);

         if(empty($startLevel))
         {
            $startLevel = min($levelIndices);
         }

        [$nTrue] = $this->getPastCorrect($courseID, $currentToC, $currentLevel, $userID, $attemptID);
        $nPastCorrect = $nTrue;

        [$nFalse] = $this->getPastFalse($courseID, $currentToC, $currentLevel, $userID, $attemptID);
        $nPastFalse = $nFalse;

        /* additional */
        if(empty($levelIndices)==0)
        {
            if(is_array($levelIndices))
               {
                $startLevel = min($levelIndices);
               }
            else
            {
                $startLevel = $levelIndices;
            }
        }

        if(empty($levelIndices)== 0)
            {
                if(is_array($levelIndices))
                {
                    $startLevel = min($levelIndices);
                }
                else
                {
                    $startLevel = $levelIndices;
                }
            }


            if (($currentToC) == $maxTOC)
             {
                array_unshift($levelIndices,"");
                unset($levelIndices[0]);
                $currentIndex = $levelIndices[1] == $sqlCounter;
                if($currentIndex == count($levelIndices))
                {
                    $CourseComplete = 1;
                    $nextQuestion =  min($levelIndices);
                }
            }
            else
            {

                if($isTOCFinished)
                {
                    $currentToC = $nextToC;
                    $current_level = 1;
                    if(is_array($currentToC))
                    {
                    $currentToC = implode(',', $currentToC);
                    }

                        [$indices] = $this->getQuestionIndex($courseID, $currentToC,$currentLevel,$userID,$attemptID);
                        $levelIndices = $indices;
                        $startLevel = min($levelIndices);
                        $nextQuestion = $startLevel;
                        $CourseComplete = 0;
                }
                else
                {
                    $CourseComplete = 0;

                    $nextQuestion =  min($levelIndices);
                    $attemptComplete = $CourseComplete ;
                }
            }




        return ['que_id' => $nextQuestion, 'is_complete' => $CourseComplete];
    }

}
