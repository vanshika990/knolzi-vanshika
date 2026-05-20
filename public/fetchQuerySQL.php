<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
/* * ***********************************************************************************************************
 * Copyright (C) 2020-2022 APTRaise Technologies Private Limited <info@aptraise.com>.
 * 
 * This file is part of edupme.
 * 
 * edupme can not be copied and/or distributed without the express
 * permission of APTRaise Technologies Private Limited.
 * ***********************************************************************************************************
  This function returns the id of the next interaction to be displayed.
  All the four inputs must be supplied with datatype double.
  Usage:
  [nextQuestion, attemptComplete] = fetchQuerySQL(sqlCounter, courseID, attemptID, userID)
 */

$sqlCounter = '60';
$userID = '1539';
$attemptID = '24';
$courseID = '75';
$maxLevel = '1';    #Only one level
$database = 'edupme_knolzi_prod';

#Fetch current and next TOC index

function fetchQuerySQL($sqlCounter, $courseID, $attemptID, $userID, $database) {

    function getNextToC($userID, $courseID, $sqlCounter, $database) {
        $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
        $query1 = $con->query("
            SELECT tbl_course_question.que_toc_no as allTOC FROM tbl_course_question WHERE  tbl_course_question.is_delete='0' AND tbl_course_question.status ='1' AND course_id= $courseID
            ");
        foreach ($query1 as $data) {
            $allTOC[] = $data['allTOC'];
        }
        if (empty($allTOC)) {
            $allTOC[] = 0;
        }
        $query2 = $con->query("
            SELECT tbl_course_question.que_toc_no as currentToC FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND id= $sqlCounter AND tbl_course_question.course_id IN (SELECT course_id FROM tbl_reviewer_user_question_attempt_history  WHERE tbl_reviewer_user_question_attempt_history .user_id= $userID)
            ");
        foreach ($query2 as $data) {
            $currentToC[] = $data['currentToC'];
        }

        if ($sqlCounter == 1) {
            $query2 = $con->query("
                SELECT tbl_course_question.que_toc_no as currentToC FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID
                 AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' ORDER BY que_toc_no ASC LIMIT 1
                ");
            foreach ($query2 as $data) {
                $currentToC[] = $data['currentToC'];
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
        $con->close();
        return [$currentToC, $nextToC, $maxTOC, $isEndToC, $sortedToC];
    }

//        echo "<pre>"; print_r(getNextToC($userID, $courseID,$sqlCounter,$database)); die;
    [$currentToC, $nextToC, $maxTOC, $isEndToC, $sortedToC] = getNextToC($userID, $courseID, $sqlCounter, $database);
    $maxLevel = 1;  # Only one level
    $qCounter = $sqlCounter;
    $currentLevel = '1';
    $currentToC = $currentToC[0];

    function getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database) {
        $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
        $query1 = $con->query("
            SELECT id AS question_id FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_level= $currentLevel AND tbl_course_question.id NOT IN (SELECT question_id FROM tbl_reviewer_user_question_attempt_history  WHERE tbl_reviewer_user_question_attempt_history.course_id = $courseID  AND tbl_reviewer_user_question_attempt_history.user_id= $userID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID AND tbl_reviewer_user_question_attempt_history.rightanswer='1' GROUP BY question_id)
            ");
        foreach ($query1 as $data) {
            $indices[] = $data['question_id'];
        }
        if (empty($indices)) {
            $indices = NULL;
        }
        $con->close();
        return [$indices];
    }

    #Find indices of questions
    [$indices] = getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database);
    $levelIndices = $indices;
    if (is_array($levelIndices)) {
        $b = array_rand($levelIndices, 1);
        $levelIndices = $levelIndices[$b];
    }

    function GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database) {
        $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
        $query1 = $con->query("
            SELECT tbl_course_question.id as id , tbl_course_question.que_level as que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)
            ");
        foreach ($query1 as $data) {
            $GyanIndices = $data['id'];
        }
        foreach ($query1 as $data) {
            $GyanLevel = $data['que_level'];
        }
        if (empty($GyanLevel)) {
            $GyanLevel = 0;
        }
        if (empty($GyanIndices)) {
            $GyanIndices = 0;
        }
        $con->close();
        return [$GyanIndices, $GyanLevel];
    }

    [$GyanIndices, $GyanLevel] = GetGyanIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database); #Start index for Gyan only questions 
    $startLevel = $GyanIndices;

    function isAllCorrectAtTOC($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database) {
        $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
        $query1 = $con->query("
        select COUNT(*) as total_cur_toc from tbl_course_question where que_toc_no=  '$currentToC'  AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.course_id= $courseID
        ");
        foreach ($query1 as $data) {
            $check_toc = $data['total_cur_toc'];
        }
        $query2 = $con->query("
        SELECT id as question_id FROM tbl_course_question WHERE tbl_course_question.course_id = $courseID AND tbl_course_question.status ='1' AND tbl_course_question.is_delete ='0' AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.id IN (SELECT question_id FROM tbl_reviewer_user_question_attempt_history  WHERE tbl_reviewer_user_question_attempt_history.course_id = $courseID AND tbl_reviewer_user_question_attempt_history.user_id= $userID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID AND tbl_reviewer_user_question_attempt_history.rightanswer='1' GROUP BY question_id)
        ");
        foreach ($query2 as $data) {
            $Question_ID[] = $data['question_id'];
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

    $sortedToC = array_values($sortedToC);
    $clength = count($sortedToC);
    # Fetch number of correct and incorrect answers in the most recent attempt


    for ($x = 0; $x < $clength; $x++) {
        $Loop_currentToC = $sortedToC[$x];
        $isTOCFinished = isAllCorrectAtTOC($courseID, $Loop_currentToC, $currentLevel, $userID, $attemptID, $database);
        $isTOCFinished1[$x] = $isTOCFinished;
    }

    function getPastCorrect($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database) {
        $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
        $query1 = $con->query("
        SELECT COUNT(id) as Count_id FROM (SELECT * FROM tbl_reviewer_user_question_attempt_history WHERE tbl_reviewer_user_question_attempt_history.user_id = $userID AND tbl_reviewer_user_question_attempt_history.course_id= $courseID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID ORDER BY tbl_reviewer_user_question_attempt_history.id DESC LIMIT 1) sub WHERE rightanswer='1'
        ");
        foreach ($query1 as $data) {
            $nTrue = $data['Count_id'];
        }
        $con->close();
        return [$nTrue];
    }

    [$nTrue] = getPastCorrect($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database);
    $nPastCorrect = $nTrue;

    function getPastFalse($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database) {
        $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
        $query1 = $con->query("
        SELECT COUNT(id) as Count_id FROM (SELECT * FROM tbl_reviewer_user_question_attempt_history WHERE tbl_reviewer_user_question_attempt_history.user_id = $userID AND tbl_reviewer_user_question_attempt_history.course_id= $courseID AND tbl_reviewer_user_question_attempt_history.course_attempt_id= $attemptID ORDER BY tbl_reviewer_user_question_attempt_history.id DESC LIMIT 1) sub WHERE rightanswer='0'
        ");
        foreach ($query1 as $data) {
            $nFalse = $data['Count_id'];
        }
        $con->close();
        return [$nFalse];
    }

    [$nFalse] = getPastFalse($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database);
    $nPastFalse = $nFalse;

    function getIntent($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID, $database) {
        $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
        $query1 = $con->query("
        SELECT question_intent_id FROM tbl_course_question WHERE tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.id= $sqlCounter AND tbl_course_question.course_id= $courseID
        ");
        foreach ($query1 as $data) {
            $getIntent[] = $data['question_intent_id'];
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
        $con->close();
        return $isRememberUnderstan;
    }

    if (empty($levelIndices) == 0) {
        if (is_array($levelIndices)) {
            $startLevel = min($levelIndices);
        } else {
            $startLevel = $levelIndices;
        }
    }

    if ($sqlCounter == 1) {
        $isRememberUnderstan = getIntent($courseID, $currentToC, $startLevel[0], $currentLevel, $userID, $attemptID, $database);
    } else {
        $isRememberUnderstan = getIntent($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID, $database);
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

                function GetGyanIndex1($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID, $database) {
                    $con = new mysqli('edupmedb.cwmtu36ih0mk.ap-south-1.rds.amazonaws.com', 'edupmedbroot', 'meedup23$%', $database);
                    $query1 = $con->query("
                            SELECT tbl_course_question.id as question_ID , tbl_course_question.que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)
                            ");
                    foreach ($query1 as $data) {
                        $GyanIndices = $data['question_ID'];
                    }
                    $query2 = $con->query("
                            SELECT tbl_course_question.que_level as que_level FROM tbl_course_question WHERE (tbl_course_question.course_id = $courseID AND tbl_course_question.que_level>0 AND tbl_course_question.que_toc_no= '$currentToC' AND tbl_course_question.status= '1'  AND tbl_course_question.is_delete= '0' ) AND tbl_course_question.id IN (SELECT tbl_question_answer.question_id FROM tbl_question_answer GROUP BY tbl_question_answer.question_id HAVING COUNT(*)=1)
                            ");
                    foreach ($query2 as $data) {
                        $GyanLevel = $data['que_level'];
                    }
                    if (!empty($GyanIndices)) {
                        $startLevel = $GyanIndices;
                    } else {
                        #Detect if user has pressed "Start". "Resume" means isFirst=0.  isFirst = isFirstAttempt(userID, courseID, conn);
                        $startLevel = 0;
                    }
                    $con->close();
                    return [$startLevel, $GyanLevel, $GyanIndices];
                }

                # Update indices of questions at current level
                [$startLevel] = GetGyanIndex1($courseID, $currentToC, $sqlCounter, $currentLevel, $userID, $attemptID, $database);

                [$indices] = getQuestionIndex($courseID, $currentToC, $currentLevel, $userID, $attemptID, $database);
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
    return[$nextQuestion, $CourseComplete];
}

[$nextQuestion, $CourseComplete] = fetchQuerySQL($sqlCounter, $courseID, $attemptID, $userID, $database);
var_dump($nextQuestion);
echo '<br>';
print_r($CourseComplete);
?>
