<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topfeatures;
use App\DataTables\Common\TopFeaturesDataTable;
use App\Helper\DocumentUploadS3Helper;

class TopFeaturesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TopFeaturesDataTable $dataTable) {
        return $dataTable->render('admin.topfeatures.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            return view('admin.topfeatures.create');
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
                'sub_title' => 'required',
                'image' => 'required|mimes:jpeg,png,jpg',
            ]);

            $image_url = DocumentUploadS3Helper::uploadToBucketNew('images', $request->image);
            $insert_data = [
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'image' => $image_url,
            ];
            Topfeatures::create($insert_data);
            return ["success" => true, "message" => "Features created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topfeatures  $topfeatures
     * @return \Illuminate\Http\Response
     */
    public function show(Topfeatures $topfeatures) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Topfeatures  $topfeatures
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $feature = Topfeatures::find($id);
            return view('admin.topfeatures.edit')->with(['feature' => $feature]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topfeatures  $topfeatures
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $topfeatures) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'title' => 'required',
                'sub_title' => 'required',
            ]);

            $update_data = [
                'title' => $request->title,
                'sub_title' => $request->sub_title,
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
            $id = decrypt($topfeatures);
            Topfeatures::where('id', $id)->update($update_data);
            return ["success" => true, "message" => "Feature updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topfeatures  $topfeatures
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            Topfeatures::where('id', $id)->delete();
            return ["success" => true, "message" => "Feature deleted successfully"];
        }
        abort(404);
    }

}
