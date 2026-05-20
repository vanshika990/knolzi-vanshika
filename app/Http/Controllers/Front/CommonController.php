<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

use Auth;

class CommonController extends Controller {

    /**
     * search category 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function FrontSearchCategory(Request $request) {
        $response = array();
        if (!empty($request->searchTerm)) {
            $users = Category::select('id', 'name')->where('status', '1')->where('name', 'like', '%' . $request->searchTerm . '%')->get();
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
     * search related courses 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function FrontSearchRelatedCourses(Request $request) {
        $response = array();
        if (!empty($request->id)) {
            if (!empty($request->searchTerm)) {
                $course = Course::select('course_id', 'course_name')->where('is_delete', '0')->where('status', '1')->where('course_id', '!=', $request->id)->where('course_name', 'like', '%' . $request->searchTerm . '%')->get();
            }
        } else {
            if (!empty($request->searchTerm)) {
                $course = Course::select('course_id', 'course_name')->where('is_delete', '0')->where('status', '1')->where('course_name', 'like', '%' . $request->searchTerm . '%')->get();
            }
        }
        if (!empty($course)) {
            foreach ($course as $row) {
                $response[] = array(
                    "id" => $row['course_id'],
                    "text" => $row['course_name']
                );
            }
        }
        return json_encode($response);
    }

    public function getLocation(Request $request){
        $request_uri = 'https://ipfind.co';
        $ip_address = $request->ip();
        $auth = '0bf3cb6d-af20-488f-964a-ea416aac97f7';
        $url = $request_uri . "?ip=" . $ip_address . "&auth=" . $auth;
        $document = file_get_contents($url);
        $result = json_decode($document);
        echo "<pre>"; print_r($result);
    }

}
