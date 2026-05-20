<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Currency;
use Illuminate\Support\Collection;

class CurrencyDataTable extends DataTable {

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
                        ->rawColumns(['symbol', 'action'])
                        ->addColumn('action', function (Collection $model) {

                            $btn = "";
                            $btn .= '<a href="javascript:void(0)" data-id = "' . encrypt($model->get('id')) . '" onclick="editCurrency(this)" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>';
                            if ($model->get('id') != 1 && $model->get('id') != 2) {
                                $btn.= '<a href = "javascript:void(0)" data-id = "' . encrypt($model->get('id')) . '" onclick = "deleteCurrency(this)" class = "btn btn-sm btn-danger px-4 me-2" data-bs-toggle = "tooltip" data-bs-custom-class = "tooltip-dark" data-bs-placement = "top" title = "Delete"><i class = "bi bi-trash fs-3"></i></a>';
                            }
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param  Activity  $model
     * @return \Illuminate\Database\Eloquent\Builder
     * @param \App\Models\Currency $model
     * @return type
     */
    public function query(Currency $model) {
        $data = collect();
        $data = $model->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'name', 'inr_value', 'rate', 'symbol', 'short_name']);
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
                        ->setTableId('currency-table')
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
            Column::make('short_name'),
            Column::make('inr_value')->title(__('INR Value')),
            Column::make('rate'),
            Column::make('symbol'),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
