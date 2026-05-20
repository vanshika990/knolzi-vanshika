<?php

namespace App\DataTables\Front;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseSubscription;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\User;

class UsersubscribecourseDataTable extends DataTable {

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
     * @param \App\Models\User $model
     * @return type
     */
    public function query(CourseSubscription $model) {
        $data = collect();
        $user_id = auth()->user()->id;
        $data = $model->with('course')->whereHas('course', function($query) {
                    $query->where('status', '1');
                    $query->where('is_delete', '0');
                })->where('user_id', $user_id)->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'course']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId('subscribe-course-table')
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
            Column::make('course.course_name')->title(__('course Name')),
            Column::make('course.course_code')->title(__('course code')),
        ];
    }

}
