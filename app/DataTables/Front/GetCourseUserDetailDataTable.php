<?php

namespace App\DataTables\Front;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseSubscriptionLicence;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class GetCourseUserDetailDataTable extends DataTable {

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
    public function query(Request $request,CourseSubscriptionLicence $model) {

        $data = collect();
        $id = decrypt($request->id);

        $user_data = $model->with('user')->where(['course_id' => $id,'status' => '1'])->groupBy('user_id')->get();

        $data = $user_data->map(function ($row) {
            return (collect($row))->only(['id', 'user']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
            ->setTableId('get-course-user-detail-table')
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
            Column::make('user.name')->title(__('User Name')),
            Column::make('user.email')->title(__('Email')),
            Column::make('user.mobile_no')->title(__('Mobile Number')),
        ];
    }

}
