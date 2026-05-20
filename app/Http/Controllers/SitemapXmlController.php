<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;

class SitemapXmlController extends Controller
{
    public function index() {
      $category = Category::where('status', '1')->get();
      $course = Course::select(['course_name', 'slug','created_at'])->where(['status' => '1', 'is_delete' => '0'])->get();

      return response()->view('page.sitemapxml', ['categorys' => $category, 'courses' => $course])->header('Content-Type', 'text/xml');
    }

    public function test(){
      return view('test');
    }
}
