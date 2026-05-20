<?php

namespace App\DataTables\Common;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseHasUser;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class AuthorCreatedCourseDataTable extends DataTable {

    protected $user;

    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->user = Auth::user();
    }

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
            ->collection($query);
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\Course $model
     * @return type
     */
    public function query(Request $request, CourseHasUser $model) {
        $data = collect();

        $user_id = decrypt($request->id);

        $data = $model->select('id', 'course_id')->with(array('course' => function($query) {
            $query->select('course_id', 'course_name', 'course_code')->where('is_delete', '0');
        }))->where('user_id', $user_id)->get();
        $data = $data->map(function ($row) {
            return (collect($row));
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
            ->setTableId('get-author-course-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(true)
            ->orderBy(0)
            ->responsive()
            ->autoWidth(false)
            ->parameters(['scrollX' => true])
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5');
    }

    /**
     * Get columns.
     * @return type
     */
    protected function getColumns() {
        return [
            Column::make('course.course_name')->title(__('Course Name')),
            Column::make('course.course_code')->title(__('Course Code')),
        ];
    }

}
