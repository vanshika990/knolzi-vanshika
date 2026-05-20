<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CourseCategory;
use DataTables;
use Illuminate\Http\Request;
use App\DataTables\Common\CategoryDataTable;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CategoryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CategoryDataTable $dataTable) {
        return $dataTable->render('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            $parent_data = Category::all();
            return view('admin.category.create')->with(['parents' => $parent_data]);
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
                'category_sub_description' => 'required',
                'category_description' => 'required',
                'meta_title' => 'required',
                'meta_keyword' => 'required',
                'meta_description' => 'required',
            ]);

            $data = $request->all();

            $data["slug"] = SlugService::createSlug(Category::class, 'slug', $data['name']);
            $data["parent_id"] = $data["parent_id"];
            $data["category_sub_description"] = $data["category_sub_description"];
            $data["category_description"] = $data["category_description"];
            $data['meta_title'] = $request->meta_title;
            $data['meta_keyword'] = $request->meta_keyword;
            $data['meta_description'] = $request->meta_description;

            Category::create($data);

            return ["success" => true, "message" => "Category created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $category = Category::find($id);
            $parent_data = Category::where('id', '!=', $id)->get();
            return view('admin.category.edit')->with(['category' => $category, 'parents' => $parent_data
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
                'category_sub_description' => 'required',
                'category_description' => 'required',
                'meta_title' => 'required',
                'meta_keyword' => 'required',
                'meta_description' => 'required',
            ]);
            $data = $request->all();
            $data["slug"] = SlugService::createSlug(Category::class, 'slug', $data['name']);
            $data["parent_id"] = $data["parent_id"];
            $data["category_sub_description"] = $data["category_sub_description"];
            $data["category_description"] = $data["category_description"];
            $data['meta_title'] = $data['meta_title'];
            $data['meta_keyword'] = $data['meta_keyword'];
            $data['meta_description'] = $data['meta_description'];

            $category->update($data);
            return ["success" => true, "message" => "Category updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);

            $assigned = CourseCategory::where('cat_id', $id)->count();

            if ($assigned <= '0') {
                $category = Category::find($id);
                $category->delete();
                return ["success" => true, "message" => "Category deleted successfully"];
            } else {
                return ["success" => false, "message" => "Category already assigned in course"];
            }
        }
        abort(404);
    }

    /**
     * Update Category Status
     * @param  \App\Models\Category  $category
     * @param \Illuminate\Http\Request $request
     */
    public function categoryChangeStatus(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'id' => 'required',
            ]);
            $category_id = decrypt($request->id);
            $categorydetail = Category::find($category_id);
            $label = "activated";
            if ($categorydetail->status == 1) {
                $status = '0';
                $label = "deactivated ";
            }
            if ($categorydetail->status == 0) {
                $status = '1';
            }
            $data = [];
            $data['status'] = $status;
            $categorydetail->update($data);
            return ["success" => true, "message" => "Category $label successfully."];
        }
        abort(404);
    }

}
