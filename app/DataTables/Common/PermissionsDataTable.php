<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Permissions;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class PermissionsDataTable extends DataTable {

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
                        ->addColumn('action', function (Collection $model) {
                            $btn = '<a href="javascript:void(0)" onclick="editPermission(' . $model->get('id') . ')" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>
                                            <a href="javascript:void(0)" onclick="deletePermission(' . $model->get('id') . ')" class="btn btn-sm btn-danger " data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete"><i class="bi bi-trash fs-3"></i></a>';
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param  Activity  $model
     * @return \Illuminate\Database\Eloquent\Builder
     * @param \App\Models\Permissions $model
     * @return type
     */
    public function query(Permissions $model) {
        $data = collect();
        $data = $model::all();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'name', 'display_name']);
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
                        ->setTableId('permissions-table')
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
            Column::make('name')->title(__('Name')),
            Column::make('display_name'),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
