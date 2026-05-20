<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseSubscription;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class getSubscribeCourseDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['action'])
                        ->editColumn('course_name', function (Collection $model) {
                            $company_name = "";
                            if (!empty($model->get('course'))) {
                                $company_name = $model->get('course')['course_name'];
                            }
                            return $company_name;
                        })
                        ->editColumn('course_code', function (Collection $model) {
                            $company_name = "";
                            if (!empty($model->get('course'))) {
                                $company_name = $model->get('course')['course_code'];
                            }
                            return $company_name;
                        })
                        ->addColumn('action', function (Collection $model) {
                            $btn = '<a href="javascript:void(0)" data-id="' . encrypt($model->get('id')) . '" onclick="editSubscription(this)" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit subscription"><i class="bi bi-pencil fs-3"></i></a>';
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CourseSubscription $model
     * @return type
     */
    public function query(Request $request, CourseSubscription $model) {
        $data = collect();
        $user_id = decrypt($request->id);
        $data = $model->with('course')->whereHas('course', function($query) {
                    $query->where('status', '1');
                    $query->where('is_delete', '0');
                })->where('user_id', $user_id)->where('status','1')->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'course', 'sub_expire_date']);
        });
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId('getsubscribecourse-table')
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
            Column::make('course_name')->title(__('Course Name')),
            Column::make('course_code')->title(__('Course code')),
            Column::make('sub_expire_date')->title(__('Subscription Expire Date')),
            Column::computed('action')->exportable(false)->printable(false)->addClass('text-center')->responsivePriority(-1),

        ];
    }

}
