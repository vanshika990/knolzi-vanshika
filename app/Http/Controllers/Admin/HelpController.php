<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Help;
use Illuminate\Http\Request;
use App\DataTables\Common\HelpDataTable;
use App\Helper\DocumentUploadS3Helper;

class HelpController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, HelpDataTable $dataTable) {
        return $dataTable->render('admin.help.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            return view('admin.help.create');
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
                'title' => 'required',
                'image' => 'required|mimes:jpeg,png,jpg',
            ]);

            $image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->image);

            $insert_data = [
                'title' => $request->title,
                'image' => $image_url,
            ];
            Help::create($insert_data);

            return ["success" => true, "message" => "Help created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Help  $help
     * @return \Illuminate\Http\Response
     */
    public function show(Help $help) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Help  $help
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $help = Help::find($id);
            return view('admin.help.edit')->with(['help' => $help]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Help  $help
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $help) {
        if ($request->ajax()) {

            $validatedData = $request->validate([
                'title' => 'required',
            ]);
            $update_data = [
                'title' => $request->title,
            ];
            if ($request->hasFile('image')) {
                $validatedData = $request->validate([
                    'image' => 'required|mimes:jpeg,png,jpg',
                ]);

                if (!empty($request->old_image)) {
                    $old_image_remove = $request->old_image;
                    DocumentUploadS3Helper::deleteToBucket($old_image_remove);
                }
                $update_data['image'] = DocumentUploadS3Helper::uploadToBucketNew('images', $request->image);
            }

            $id = decrypt($help);
            Help::where('id', $id)->update($update_data);
            return ["success" => true, "message" => "Help updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Help  $help
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            Help::where('id', $id)->delete();
            return ["success" => true, "message" => "Help deleted successfully"];
        }
        abort(404);
    }

}
