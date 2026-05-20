<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Common\ViewSEOMetaDatatable;
use App\Models\SEOmeta;

class SEOmetaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ViewSEOMetaDatatable $dataTable) {
        return $dataTable->render('admin.seometa.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.seometa.create');
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
                'meta_title' => 'required',
                'meta_keyword' => 'required',
                'meta_description' => 'required',
                'page_slug' => 'required',
            ]);

            $data = SEOmeta::select()->where(['slug' => $request->page_slug])->get();

            if (!$data->isEmpty()) {
                $error = ['Error' => ['SEO meta already available']];
                return response()->json(array('errors' => $error), 422);
            } else {
                $insert_data = [
                    'title' => $request->meta_title,
                    'keyword' => $request->meta_keyword,
                    'description' => $request->meta_description,
                    'slug' => $request->page_slug,
                ];
                SEOmeta::create($insert_data);
                return ["success" => true, "message" => "SEO meta created successfully"];
            }
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SEOmeta  $sEOmeta
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = SEOmeta::where('id', $id)->first();
            return view('admin.seometa.show')->with([ 'data' => $data]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SEOmeta  $sEOmeta
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = SEOmeta::where('id', $id)->first();
            return view('admin.seometa.edit')->with(['data' => $data]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SEOmeta  $sEOmeta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $validatedData = $request->validate([
                'meta_title' => 'required',
                'meta_keyword' => 'required',
                'meta_description' => 'required',
            ]);

            $update_data = [
                'title' => $request->meta_title,
                'keyword' => $request->meta_keyword,
                'description' => $request->meta_description,
            ];
            SEOmeta::where(['id' => $id])->update($update_data);
            return ["success" => true, "message" => "SEO meta updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SEOmeta  $sEOmeta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            SEOmeta::where('id', $id)->delete();
            return ["success" => true, "message" => "SEO meta deleted successfully"];
        }
        abort(404);
    }

}
