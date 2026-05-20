<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\QuestionIntent;
use Illuminate\Support\Collection;

class QuestionIntentDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['action'])
                        ->editColumn('created_at', function (Collection $model) {
                            $created_at = date('Y-m-d H:i:s', strtotime($model['created_at']));
                            return $created_at;
                        })
                        ->editColumn('updated_at', function (Collection $model) {
                            $updated_at = date('Y-m-d H:i:s', strtotime($model['updated_at']));
                            return $updated_at;
                        })
                        ->addColumn('action', function (Collection $model) {

                            $btn = '<a href="javascript:void(0)" data-id = "' . encrypt($model->get('id')) . '" onclick="editQuestionIntent(this)" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>';

                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param  Activity  $model
     * @return \Illuminate\Database\Eloquent\Builder
     * @param \App\Models\Category $model
     * @return type
     */
    public function query(QuestionIntent $model) {
        $data = collect();
        $data = $model->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'name', 'created_at', 'updated_at']);
        });
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return \Yajra\DataTables\Html\Builder
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId('course_intent-table')
                        ->columns($this->getColumns())
                        ->minifiedAjax()
                        ->stateSave(true)
                        ->orderBy(0)
                        ->responsive()
                        ->autoWidth(false)
                        ->parameters(['scrollX' => true])
                        ->addTableClass('align-middle table-row-dashed table-striped fs-6 gy-5');
    }

    /**
     * Get columns.
     * @return type
     */
    protected function getColumns() {
        return [
            Column::make('name'),
            Column::make('created_at')->title(__('Created Date')),
            Column::make('updated_at')->title(__('Updated Date')),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
