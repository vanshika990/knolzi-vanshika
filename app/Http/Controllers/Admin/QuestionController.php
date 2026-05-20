<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseQuestion;
use App\Models\Questionanswer;
use App\Models\QuestionHashelp;
use App\Models\QuestionhasImagehelp;
use App\Models\QuestionHashint;
use App\Models\QuestionhasImagehint;
use App\Models\Course;
use App\Models\QuestionIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\DataTables\Common\QuestionDataTable;
use DB;
use DocumentUploadS3Helper;

class QuestionController extends Controller {

    /**
     * Display a listing of the questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, QuestionDataTable $dataTable) {
        return $dataTable->render('admin.question.index');
    }

    /**
     * Show the form for creating a new qustion.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $courseData = [];
        $courseData = Course::select(['*'])->where('is_delete', '0')->get();
        $courseintent = [];
        $courseintent = QuestionIntent::select()->get();

        return view('admin.question.create')->with(['courseData' => $courseData, 'intentDetail' => $courseintent]);
    }

    /**
     * Store a newly created resource in question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            ini_set('memory_limit', -1);
                $request->validate([
                    'question_name' => 'required',
                    'question_type' => 'required',
                    'choice.*' => 'required',
                    'order.*' => 'required',
                    'ctype.*' => 'required',
                    'correct_question_ans' => 'required',
                    'course_id' => 'required|exists:tbl_course,course_id',
                    'question_intent' => 'required',
                    'status' => 'required|in:0,1',
                    'question_level' => 'required',
                    'toc_no' => 'required',
                    'question_media_type' => 'required',
                ]);

            ini_set('memory_limit', '-1');
            if (isset($request->video_help) && is_string($request->video_help)) {
                $valid = preg_match("/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/watch\?v\=\w+$/", $request->video_help);
                if (!$valid) {
                    $msg['message'] = 'The given data was invalid.';
                    $avil = 'Please enter valid YouTube url';
                    $msg['errors']['video_help'] = array($avil);
                    return response()->json($msg, 422);
                }
            }

            if ($request->question_media_type == 'single') {
                if ($request->hasFile('question_media')) {
                    $request->validate([
                        'question_media' => 'mimes:mp4,pdf,jpeg,jpg,png,gif',
                    ]);
                    $question_array['question_media'] = DocumentUploadS3Helper::uploadToBucketNew('images', $request->question_media);
                }
            } else {
                $question_array['question_media'] = $request->question_media_data;
            }



            $question_intent = implode(',', $request->question_intent);
            $question_array['course_id'] = $request->course_id;
            $question_array['question_name'] = $request->question_name;
            $question_array['question_type'] = $request->question_type;
            $question_array['correct_question_ans'] = $request->correct_question_ans;
            $question_array['question_intent_id'] = $question_intent;
            $question_array['status'] = $request->status;
            $question_array['que_level'] = $request->question_level;
            $question_array['que_toc_no'] = $request->toc_no;
            if ($request->has('toc_text')) {
                $question_array['que_toc_text'] = $request->toc_text;
            }
            $question_array['question_media_type'] = $request->question_media_type;
            $question = CourseQuestion::create($question_array);
            $question_id = $question->id;
            $all_data = $request->all();
            $all_help = [];


            //Code related for scorm zip file upload
            if ($request->question_media_type == 'scorm') {
                $request->validate([
                    'scorm_zip' => 'required|mimes:zip',
                ]);

               if ($request->hasFile('scorm_zip')) {
                   $zipFile = $request->file('scorm_zip');
                   //Local save zip and unzfip
                   $fileName = time().'.'.$zipFile->getClientOriginalExtension();
                   $path = public_path().'/uploads/question_' . $question_id;
                   File::makeDirectory($path, $mode = 0777, true, true);
                   $zipFile->move($path, $fileName);
                   $zip = new \ZipArchive;
                   if ( $zip->open($path.'/' . $fileName) === TRUE ) {
                       $zip->extractTo($path.'/');
                       $zip->close();

                       //$question_media_path = url('/').'/uploads/question_' . $question_id.'/story.html';
                   } else {
                       //$question_media_path = '';
                   }
                   $question_media_path = DocumentUploadS3Helper::uploadToScormBucket($zipFile, $question_id);

                   //for updating question media_path
                   $update = DB::table('tbl_course_question')->where('id', $question_id)->update(array('question_media' => $question_media_path));

                   //If update scorm file url succesfully then that directry will remove from local storage
                   if($update){
                    File::deleteDirectory(public_path('/uploads/question_' . $question_id));
                   }
               }
           }

            if (isset($request->upload_video_help) && isset($request->video_help)) {
                if ($request->hasFile('video_help')) {
                    $request->validate([
                        'video_help' => 'mimes:mp4',
                    ]);

                    $all_help['video'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->video_help);
                    $all_help['video_type'] = '0';
                }
            }

            if (isset($request->url_help) && isset($request->video_help)) {
                if (!empty($request->video_help)) {
                    $all_help['video'] = $request->video_help;
                    $all_help['video_type'] = '1';
                }
            }
            if (isset($request->audio_help)) {
                if ($request->hasFile('audio_help')) {
                    $request->validate([
                        'audio_help' => 'mimes:mp3',
                    ]);

                    $all_help['audio'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->audio_help);
                }
            }
            if (isset($request->pdf_help)) {
                if ($request->hasFile('pdf_help')) {
                    $request->validate([
                        'pdf_help' => 'mimes:pdf',
                    ]);
                    $all_help['pdf'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->pdf_help);
                }
            }
            if (isset($request->link_help)) {
                $request->validate([
                    'link_help' => 'url',
                ]);
                $all_help['link'] = $request->link_help;
            }
            $all_hint = [];

            if (isset($request->upload_video_hint) && isset($request->video_hint)) {
                if ($request->hasFile('video_hint')) {
                    $request->validate([
                        'video_hint' => 'mimes:mp4',
                    ]);
                    $all_hint['video'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->video_hint);
                    $all_hint['video_type'] = '0';
                }
            }
            if (isset($request->url_hint) && isset($request->video_hint)) {
                if (!empty($request->video_hint)) {
                    $request->validate([
                        'video_hint' => 'url',
                    ]);
                    $all_hint['video'] = $request->video_hint;
                    $all_hint['video_type'] = '1';
                }
            }
            if (isset($request->audio_hint)) {
                if ($request->hasFile('audio_hint')) {
                    $request->validate([
                        'audio_hint' => 'mimes:mp3',
                    ]);
                    $all_hint['audio'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->audio_hint);
                }
            }
            if (isset($request->pdf_hint)) {
                if ($request->hasFile('pdf_hint')) {
                    $request->validate([
                        'pdf_hint' => 'mimes:pdf',
                    ]);
                    $all_hint['pdf'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->pdf_hint);
                }
            }
            if (isset($request->link_hint)) {
                $request->validate([
                    'link_hint' => 'url',
                ]);
                $all_hint['link'] = $request->link_hint;
            }
            $all_data = $request->all();

            $order = $request->order;
            $choice = $request->choice;
            $ctype = $request->ctype;
            for ($i = 0; $i < count($order); $i++) {
                $answer_data[] = [
                    'question_id' => $question_id,
                    'answer_order' => $order[$i],
                    'answer_name' => $choice[$i],
                    'choice_type' => $ctype[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Questionanswer::insert($answer_data);

            if (!empty($all_help)) {
                $all_help['question_id'] = $question_id;
                $questionHelp = QuestionHashelp::create($all_help);
            }

            if ($request->hasFile('image_help')) {
                $request->validate([
                    'image_help' => 'max:20',
                    'image_help.*' => 'mimes:jpeg,png,jpg,gif|max:7000',
                        ], [
                    "image_help.max" => "Sorry! Only 20 images are allowed"
                        ]
                );
                $image_help = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('image_help'));

                foreach ($image_help as $ihelp) {
                    $imagehelp[] = [
                        'question_id' => $question_id,
                        'image' => $ihelp,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuestionhasImagehelp::insert($imagehelp);
            }

            if (!empty($all_hint)) {
                $all_hint['question_id'] = $question_id;
                $questionHelp = QuestionHashint::create($all_hint);
            }

            if ($request->hasFile('image_hint')) {
                $request->validate([
                    'image_hint' => 'max:20',
                    'image_hint.*' => 'mimes:jpeg,png,jpg,gif|max:7000',
                        ], [
                    "image_hint.max" => "Sorry! Only 20 images are allowed"
                        ]
                );
                $image_hint = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('image_hint'));
                foreach ($image_hint as $ihint) {
                    $imagehint[] = [
                        'question_id' => $question_id,
                        'image' => $ihint,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuestionhasImagehint::insert($imagehint);
            }

            return ["success" => true, "message" => "Question added successfully."];
        }
        abort(404);
    }

    /**
     * Display the specified question.
     *
     * @param  \App\Models\CourseQuestion  $courseQuestion
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = CourseQuestion::with('course', 'questionanswer', 'questionhashelp', 'questionhashint', 'quehasimghelp', 'quehasimghint')->where('id', $id)->get();

            $intent_id = explode(",", $data[0]->question_intent_id);
            $intent_data = QuestionIntent::select('name')->whereIn('id', $intent_id)->get();

            $question_intent = [];
            foreach ($intent_data as $value) {
                $question_intent[] = $value['name'];
            }
            $intent = implode(',', $question_intent);

            return view('admin.question.show')->with(['questionData' => $data[0], 'questionintent' => $intent]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified question.
     *
     * @param  \App\Models\CourseQuestion  $courseQuestion
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        //phpinfo(); die;
        $question_id = decrypt($id);
        $question_data = CourseQuestion::with(['course', 'questionanswer', 'questionhashelp', 'questionhashint', 'quehasimghelp', 'quehasimghint'])->where('id', $question_id)->get();
        $intentDetail = QuestionIntent::get();
        $course = Course::get();
        return view('admin.question.edit')->with(['questionDetail' => $question_data[0], 'intentDetail' => $intentDetail, 'courseData' => $course]);
    }

    /**
     * Update the specified question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseQuestion  $courseQuestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $request->validate([
                'question_name' => 'required',
                'question_type' => 'required',
                'choice.*' => 'required',
                'order.*' => 'required',
                'correct_question_ans' => 'required',
                'course_id' => 'required|exists:tbl_course,course_id',
                'question_intent' => 'required',
                'status' => 'required|in:0,1',
                'question_level' => 'required',
                'toc_no' => 'required',
                'question_media_type' => 'required'
            ]);

            ini_set('memory_limit', '-1');
            $question_intent = implode(',', $request->question_intent);
            $question_array = [
                'course_id' => $request->course_id,
                'question_name' => $request->question_name,
                'question_type' => $request->question_type,
                'correct_question_ans' => $request->correct_question_ans,
                'question_intent_id' => $question_intent,
                'status' => $request->status,
                'que_level' => $request->question_level,
                'que_toc_no' => $request->toc_no,
                'question_media_type' => $request->question_media_type,
            ];
            if ($request->has('toc_text')) {
                $question_array['que_toc_text'] = $request->toc_text;
            }

            $updateQuestion = CourseQuestion::find($id);
            if ($request->question_media_type == 'single') {
                if ($request->hasFile('question_media')) {
                    $request->validate([
                        'question_media' => 'mimes:mp4,pdf,jpeg,jpg,png,gif',
                    ]);
                    if (strpos($updateQuestion->question_media, 'edupmquestionhelp.s3') !== false) {
                        DocumentUploadS3Helper::deleteToBucket($updateQuestion->question_media);
                    }

                    $question_array['question_media'] = DocumentUploadS3Helper::uploadToBucketNew('images', $request->question_media);
                } else {
                    if (strpos($request->question_media, 'edupmquestionhelp.s3') === false) {
                        $question_array['question_media'] = "";
                    }
                }
                if ($request->media_status == '0') {
                    if (strpos($updateQuestion->question_media, 'edupmquestionhelp.s3') !== false) {
                        DocumentUploadS3Helper::deleteToBucket($updateQuestion->question_media);
                    }
                    $question_array['question_media'] = "";
                }
            } elseif( $request->question_media_type == 'scorm' ){

                if ($request->hasFile('scorm_zip')) {
                    $request->validate([
                        'scorm_zip' => 'required|mimes:zip',
                    ]);

                    $zipFile = $request->file('scorm_zip');

                    if (strpos($updateQuestion->question_media, 's3') !== false) {
                        DocumentUploadS3Helper::deleteScormToBucket($zipFile, $id);
                    }
                   //Local save zip and unzfip
                   $fileName = time().'.'.$zipFile->getClientOriginalExtension();
                   $path = public_path().'/uploads/question_' . $id;
                   File::makeDirectory($path, $mode = 0777, true, true);
                   $zipFile->move($path, $fileName);
                   $zip = new \ZipArchive;
                   if ( $zip->open($path.'/' . $fileName) === TRUE ) {
                       $zip->extractTo($path.'/');
                       $zip->close();
                   }
                   $question_array['question_media'] = DocumentUploadS3Helper::uploadToScormBucket($zipFile, $id);
                   File::deleteDirectory(public_path('/uploads/question_' . $id));
                } else {

                    if (strpos($request->question_media, 's3') === false) {
                        $question_array['question_media'] = "";
                    }
                }
            }else {
                if (strpos($updateQuestion->question_media, 'edupmquestionhelp.s3') !== false) {
                    DocumentUploadS3Helper::deleteToBucket($updateQuestion->question_media);
                }
                $question_array['question_media'] = $request->question_media_data;
            }
            $updateQuestion->update($question_array);

            Questionanswer::where('question_id', $id)->delete();
            $order = $request->order;
            $choice = $request->choice;
            $ctype = $request->ctype;
            for ($i = 0; $i < count($order); $i++) {
                $answer_data[] = [
                    'question_id' => $id,
                    'answer_order' => $order[$i],
                    'answer_name' => $choice[$i],
                    'choice_type' => $ctype[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Questionanswer::insert($answer_data);

            $all_help = [];
            if ($request->video_help_status == '0') {
                $all_help['video'] = '';
                $all_help['video_type'] = '';
            }
            if ($request->audio_help_status == 0) {
                $all_help['audio'] = '';
            }
            if ($request->pdf_help_status == 0) {
                $all_help['pdf'] = '';
            }

            if (isset($request->upload_video_help)) {
                $all_help['video_type'] = '0';
                $all_help['video'] = '';
            }
            if (isset($request->url_help)) {
                $all_help['video_type'] = '1';
                $all_help['video'] = '';
            }
            if (isset($request->upload_video_help) && isset($request->video_help)) {
                if ($request->hasFile('video_help')) {
                    $request->validate([
                        'video_help' => 'mimes:mp4',
                    ]);
                    $all_help['video'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->file('video_help'));
                }
            }
            if (isset($request->url_help) && isset($request->video_help)) {
                if (!empty($request->video_help)) {
                    $all_help['video'] = $request->video_help;
                }
            }
            if (isset($request->audio_help)) {
                if ($request->hasFile('audio_help')) {
                    $request->validate([
                        'audio_help' => 'mimes:mp3',
                    ]);
                    $all_help['audio'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->audio_help);
                }
            }
            if (isset($request->pdf_help)) {
                if ($request->hasFile('pdf_help')) {
                    $request->validate([
                        'pdf_help' => 'mimes:pdf',
                    ]);
                    $all_help['pdf'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->pdf_help);
                }
            }
            if (isset($request->link_help)) {
                $request->validate([
                    'link_help' => 'url',
                ]);
                $all_help['link'] = $request->link_help;
            }
            if ((isset($all_help['video']) && $all_help['video'] != "") || (isset($all_help['video_type']) && $all_help['video_type'] != "") || (isset($all_help['image']) && $all_help['image'] != "") || (isset($all_help['audio']) && $all_help['audio'] != "") || (isset($all_help['pdf']) && $all_help['pdf'] != "")) {
                $help_data = QuestionHashelp::select()->where('question_id', $id)->first();
                if (!empty($help_data)) {
                    $updateQuestionHelp = QuestionHashelp::where('question_id', $id)->update($all_help);
                } else {
                    $all_help['question_id'] = $id;
                    $updateQuestionHelp = QuestionHashelp::create($all_help);
                }
            }

            if (!empty(trim($request->imghelpRemove))) {
                $arr = explode(',', $request->imghelpRemove);
                $imgPath = QuestionhasImagehelp::select(['image'])->whereIn('id', $arr)->get();
                $deleteToBucketArray = [];
                foreach ($imgPath as $imgs) {
                    $deleteToBucketArray[] = $imgs->image;
                }
                $deleteToBucket = DocumentUploadS3Helper::deleteToBucket($deleteToBucketArray);
                QuestionhasImagehelp::whereIn('id', $arr)->delete();
            }
            if ($request->hasFile('image_help')) {
                $request->validate([
                    'image_help' => 'max:20',
                    'image_help.*' => 'mimes:jpeg,png,jpg,gif|max:7000',
                        ], [
                    "image_help.max" => "Sorry! Only 20 images are allowed"
                        ]
                );
                $image_help = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('image_help'));
                foreach ($image_help as $ihelp) {
                    $imagehelp[] = [
                        'question_id' => $id,
                        'image' => $ihelp,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuestionhasImagehelp::insert($imagehelp);
            }

            $all_hint = [];
            if ($request->video_hint_status == 0) {
                $all_hint['video'] = '';
                $all_hint['video_type'] = '';
            }
            if ($request->image_hint_status == 0) {
                $all_hint['image'] = '';
            }
            if ($request->audio_hint_status == 0) {
                $all_hint['audio'] = '';
            }
            if ($request->pdf_hint_status == 0) {
                $all_hint['pdf'] = '';
            }

            if (isset($request->upload_video_hint)) {
                $all_hint['video_type'] = '0';
                $all_hint['video'] = '';
            }
            if (isset($request->url_hint)) {
                $all_hint['video_type'] = '1';
                $all_hint['video'] = '';
            }
            if (isset($request->upload_video_hint) && isset($request->video_hint)) {
                if ($request->hasFile('video_hint')) {
                    $request->validate([
                        'video_hint' => 'mimes:mp4',
                    ]);
                    $all_hint['video'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->file('video_hint'));
                }
            }
            if (isset($request->url_hint) && isset($request->video_hint)) {
                if (!empty($request->video_hint)) {
                    $all_hint['video'] = $request->video_hint;
                }
            }
            if (isset($request->audio_hint)) {
                if ($request->hasFile('audio_hint')) {
                    $request->validate([
                        'audio_hint' => 'mimes:mp3',
                    ]);
                    $all_hint['audio'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->audio_hint);
                }
            }
            if (isset($request->pdf_hint)) {
                if ($request->hasFile('pdf_hint')) {
                    $request->validate([
                        'pdf_hint' => 'mimes:pdf',
                    ]);
                    $all_hint['pdf'] = DocumentUploadS3Helper::uploadToBucketNew('videos', $request->pdf_hint);
                }
            }
            if (isset($request->link_hint)) {
                $request->validate([
                    'link_hint' => 'url',
                ]);
                $all_hint['link'] = $request->link_hint;
            }
            if ((isset($all_hint['video']) && $all_hint['video'] != "") || (isset($all_hint['video_type']) && $all_hint['video_type'] != "") || (isset($all_hint['image']) && $all_hint['image'] != "") || (isset($all_hint['audio']) && $all_hint['audio'] != "") || (isset($all_hint['pdf']) && $all_hint['pdf'] != "")) {
                $hint_data = QuestionHashint::select()->where('question_id', $id)->first();
                if (!empty($hint_data)) {
                    $updateQuestionHelp = QuestionHashint::where('question_id', $id)->update($all_hint);
                } else {
                    $all_hint['question_id'] = $id;
                    $updateQuestionHelp = QuestionHashint::create($all_hint);
                }
            }
            if (!empty(trim($request->imghintRemove))) {
                $arr = explode(',', $request->imghintRemove);
                $imgPath = QuestionhasImagehint::select(['image'])->whereIn('id', $arr)->get();
                $deleteToBucketArray = [];
                foreach ($imgPath as $imgs) {
                    $deleteToBucketArray[] = $imgs->image;
                }
                $deleteToBucket = DocumentUploadS3Helper::deleteToBucket($deleteToBucketArray);
                QuestionhasImagehint::whereIn('id', $arr)->delete();
            }
            if ($request->hasFile('image_hint')) {
                $request->validate([
                    'image_hint' => 'max:20',
                    'image_hint.*' => 'mimes:jpeg,png,jpg,gif|max:7000',
                        ], [
                    "image_hint.max" => "Sorry! Only 20 images are allowed"
                        ]
                );
                $image_hint = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('image_hint'));
                foreach ($image_hint as $ihint) {
                    $imagehint[] = [
                        'question_id' => $id,
                        'image' => $ihint,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuestionhasImagehint::insert($imagehint);
            }

            return "true";
        }
        abort(404);
    }

    /**
     * Remove the specified question from storage.
     *
     * @param  \App\Models\CourseQuestion  $courseQuestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);

            //delete scorm file from s3 bucket
            DocumentUploadS3Helper::deleteScormFolder($id);

            $data = CourseQuestion::where('id', $id)->delete();

            return ["success" => true, "message" => "Question deleted successfully"];
        }
        abort(404);
    }

    /**
     * Update Question Status
     * @param  \App\Models\CourseQuestion  $courseQuestion
     * @param \Illuminate\Http\Request $request
     */
    public function questionChangeStatus(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'id' => 'required',
            ]);
            $question_id = decrypt($request->id);
            $courseQuestion = CourseQuestion::find($question_id);
            $label = "activated";
            if ($courseQuestion->status == 1) {
                $status = '0';
                $label = "deactivated ";
            }
            if ($courseQuestion->status == 0) {
                $status = '1';
            }
            $data = [];
            $data['status'] = $status;
            $courseQuestion->update($data);
            return ["success" => true, "message" => "Question $label successfully."];
        }
        return abort(404);
    }

    /**
     * Ckeditor file upload
     * @param \Illuminate\Http\Request $request
     */
    public function fileupload(Request $request) {
        if ($request->hasFile('upload')) {
            $url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->file('upload'));
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    /**
     * for clone question [ duplicate question ]
     * @param \Illuminate\Http\Request $request
     */
    public function CloneQuestion(Request $request) {
        if ($request->ajax()) {
            $id = decrypt($request->id);
            // for clone question
            $courseQuestion = CourseQuestion::find($id);
            $courseQuestion = $courseQuestion->replicate()->fill(
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
            );
            $courseQuestion->save();

            // for clone question answer data
            $que_answer_data = Questionanswer::where('question_id', $id)->get()->toarray();
            if (!empty($que_answer_data)) {
                $que_answer = [];

                foreach ($que_answer_data as $key => $value) {
                    $que_answer[] = [
                        'question_id' => $courseQuestion->id,
                        'answer_name' => $value['answer_name'],
                        'answer_order' => $value['answer_order'],
                        'choice_type' => $value['choice_type'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Questionanswer::insert($que_answer);
            }

            // Clone question help
            $questionhelp = QuestionHashelp::where('question_id', '=', $id)->get()->toarray();
            if (!empty($questionhelp)) {
                $questionhelp = $questionhelp->replicate()->fill(
                        [
                            'question_id' => $courseQuestion->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                );
                $questionhelp->save();
            }

            // Clone question image-help
            $queimghelp = QuestionhasImagehelp::where('question_id', '=', $id)->get()->toarray();
            if (!empty($queimghelp)) {
                $que_image_help = [];

                foreach ($queimghelp as $key => $value) {
                    $que_image_help[] = [
                        'question_id' => $courseQuestion->id,
                        'image' => $value['image'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuestionhasImagehelp::insert($que_image_help);
            }

            // Clone question hint
            $questionhint = QuestionHashint::where('question_id', '=', $id)->get()->toarray();
            if (!empty($questionhint)) {
                $questionhint = $questionhint->replicate()->fill(
                        [
                            'question_id' => $courseQuestion->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                );
                $questionhint->save();
            }

            // Clone question image-hint
            $queimghint = QuestionhasImagehint::where('question_id', '=', $id)->get()->toarray();
            if (!empty($queimghint)) {
                $que_image_hint = [];

                foreach ($queimghint as $key => $value) {
                    $que_image_hint[] = [
                        'question_id' => $courseQuestion->id,
                        'image' => $value['image'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuestionhasImagehint::insert($que_image_hint);
            }
            return ["success" => true, "message" => "Question clone successfully."];
        }
        abort(404);
    }

}
