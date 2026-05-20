<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\Common\AuthorDataTable;
use App\DataTables\Common\AuthorCreatedCourseDataTable;


class AuthorController extends Controller {

    /**
     * Display a listing of the author user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AuthorDataTable $dataTable) {
        return $dataTable->render('admin.author.index');
    }

    /**
     * Display a listing of the author own course.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuthorCourse(Request $request, AuthorCreatedCourseDataTable $dataTable) {
        if ($request->ajax()) {
            $validatedData = $request->validate(['id' => $request->id], [
                'id' => 'required',
            ]);
            
            return $dataTable->render('admin.author.owncourse');
        }
        abort(404);
    }

}
