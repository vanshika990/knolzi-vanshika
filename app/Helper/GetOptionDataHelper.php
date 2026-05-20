<?php

namespace App\Helper;

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;
use App\Models\Options;
use DB;

class GetOptionDataHelper extends Facade {


    public static function getOptionData($keyarray) {

    	$tmp = [];
        $key = implode("','", $keyarray);
        $data = DB::select("SELECT `option_name`,`option_value` FROM `tbl_options` WHERE `option_name` IN ('$key')");

    	foreach($data as $value){

            $tmp[$value->option_name] = json_decode($value->option_value,true);
    	}
            $final= $tmp;
            return $final;

    }







}
