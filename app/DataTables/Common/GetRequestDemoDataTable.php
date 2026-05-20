<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\RequestDemo;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;


class GetRequestDemoDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                ->collection($query)
                ->addColumn('action', function (Collection $model) {
                    $btn = '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="ViewDetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Details"><i class="bi bi-eye pe-0 fs-3"></i></button>';
                    return $btn;
                });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\User $model
     * @return type
     */
    public function query(Request $request,RequestDemo $model) {

        $from_date = $request->from_date;
        $to_date = date('Y-m-d', strtotime("+1 day", strtotime($request->to_date)));

        $data = collect();
        
        if(!empty($from_date) && !empty($to_date)){
            $data = $model->whereBetween('created_at', [$from_date, $to_date])->get();
        }
        else{
            $data = $model->get();
        }
        
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
                    ->setTableId('requestdemo-table')
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
            Column::make('contact_name')->title(__('Name')),
            Column::make('email'),
            Column::make('phone_number')->title(__('Contact')),
            Column::computed('action')->exportable(false)->printable(false)->addClass('text-center')->responsivePriority(-1),
        ];
    }

}
