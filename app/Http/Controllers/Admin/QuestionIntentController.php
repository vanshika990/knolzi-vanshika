<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionIntent;
use Illuminate\Http\Request;
use DataTables;
use App\DataTables\Common\QuestionIntentDataTable;

class QuestionIntentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, QuestionIntentDataTable $dataTable) {
        return $dataTable->render('admin.question_intent.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            return view('admin.question_intent.create');
        }
        abourt(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
            ]);
            $data = $request->all();
            $data["created_at"] = date('Y-m-d H:i:s');
            $data["updated_at"] = date('Y-m-d H:i:s');
            QuestionIntent::create($data);

            return ["success" => true, "message" => "Question Intent created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionIntent  $questionsintent
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionIntent $questionsintent) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionIntent  $questionsintent
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $question_intent = QuestionIntent::find($id);
            return view('admin.question_intent.edit')->with([
                        'question_intent' => $question_intent
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionIntent  $questionsintent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
            ]);
            $data = $request->all();
            $questionsintent = QuestionIntent::find($id);
            $questionsintent->update($data);
            return ["success" => true, "message" => "Question Intent updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionIntent  $questionsintent
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionIntent $questionsintent) {
        abort(404);
    }

}
