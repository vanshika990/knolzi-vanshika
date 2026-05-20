<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Feedback;
use Illuminate\Support\Collection;

class FeedbackDataTable extends DataTable {

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
                        ->rawColumns(['feedback_message'])
                        ->editColumn('created_at', function (Collection $model) {
                            $created_at = date('Y-m-d H:i:s', strtotime($model['created_at']));
                            return $created_at;
                        })
                        ->addColumn('feedback_message', function (Collection $model) {

                            $btn = '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Message"><i class="bi bi-eye pe-0 fs-2"></i></button>';

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
    public function query(Feedback $model) {
        $data = collect();
        $data = $model->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'user_email', 'feedback_type', 'feedback_message', 'created_at']);
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
                        ->setTableId('feedback-table')
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
            Column::make('user_email')->title(__('Email')),
            Column::make('feedback_type'),
            Column::make('feedback_message'),
                    Column::make('created_at')->title(__('Created Date'))
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
