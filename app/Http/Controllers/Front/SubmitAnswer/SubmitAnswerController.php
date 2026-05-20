<?php

namespace App\Http\Controllers\Front\SubmitAnswer;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\SubmitAnswer\ConfigController;
use App\Http\Controllers\Front\SubmitAnswer\AnalyzerController;



class SubmitAnswerController extends Controller {

   function submitAnswer($idea_ans,$user_ans){


   		$idea_ans = preg_replace('/\p{P}/', '', $idea_ans);
        $user_ans = preg_replace('/\p{P}/', '', $user_ans);
		$idea_ans =  strtolower($idea_ans);
		$user_ans =strtolower($user_ans);

		$array = preg_split('/[^[:alnum:]]+/', strtolower($idea_ans));
		foreach($array as $item)
            {
                if(strlen($item)>2)
                @$tokens1[$item]++;
        }
		$array = preg_split('/[^[:alnum:]]+/', strtolower($user_ans));

		foreach($array as $item)
            {
                if(strlen($item)>2)
                @$tokens2[$item]++;
        }

	    $similarity  = $this->cosineSimilarity($tokens1, $tokens2);
		$CORRECT_THRESHOLD = 0.5;

		if($similarity > $CORRECT_THRESHOLD)
		{
            $isAnsCorrect = 1;
		}
		else
		{
            $isAnsCorrect = 0;
		}

		$analyzer = new AnalyzerController;
		$output_text = $analyzer->getSentiment($user_ans);
		$sentiment = array_search(max($output_text), $output_text);
		return ['answer' => $isAnsCorrect, 'sentiment' => $sentiment];
   }

   private function cosineSimilarity(&$tokensA, &$tokensB)
{
	$a = $b = $c = 0;
	$uniqueTokensA = $uniqueTokensB = array();
	$uniqueMergedTokens = array_merge($tokensA, $tokensB);
	foreach ($tokensA as $token=>$val) $uniqueTokensA[$token] = $val;
	foreach ($tokensB as $token=>$val) $uniqueTokensB[$token] = $val;
	$x2=0;
	$y2=0;
	$xArray=array();
	$yArray=array();
	$address=0;
	foreach ($uniqueMergedTokens as $token=>$v) {
		$xArray[$address] = isset($tokensA[$token]) ?  $tokensA[$token]: 0;
		$yArray[$address] = isset($tokensB[$token]) ?  $tokensB[$token]: 0;
		$x2+=$xArray[$address]*$xArray[$address];
		$y2+=$yArray[$address]*$yArray[$address];
		$address++;
		}
	$x2=sqrt($x2);
	$y2=sqrt($y2);
	for($k=0;$k<$address;$k++)
		{
		$xArray[$k]/=$x2;
		$yArray[$k]/=$y2;
		$a+=$xArray[$k]*$yArray[$k];
		$b+=$xArray[$k]*$xArray[$k];
		$c+=$yArray[$k]*$yArray[$k];
		}
	return $b * $c != 0 ? $a / sqrt($b * $c) : 0;
}

}
