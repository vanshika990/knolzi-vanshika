<?php

namespace App\Http\Controllers\API\SubmitAnswer;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller {

   
    ##Static methods##

    /*
        Normalize the score to be between -1 and 1 using an alpha that
        approximates the max expected value
    */
    public static function normalize($score, $alpha = 15)
    {
        $norm_score = $score/sqrt(($score*$score) + $alpha);
        return $norm_score;
    }

}
