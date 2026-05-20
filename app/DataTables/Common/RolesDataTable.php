<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Roles;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class RolesDataTable extends DataTable {

    /**
     * Build DataTable class.
     *
     * @param  mixed  $query  Results from query() method.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->addIndexColumn()
                        ->rawColumns(['action'])
                        ->addColumn('action', function (Collection $model) {
                            $btn = '<a href="javascript:void(0)" onclick="editRole(' . $model->get('id') . ')" class="btn btn-sm btn-success " data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>
                                    <a href="javascript:void(0)" onclick="deleteRole(' . $model->get('id') . ')" class="btn btn-sm btn-danger " data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete"><i class="bi bi-trash fs-3"></i></a>';
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param  Activity  $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Roles $model) {
        $data = collect();
        $data = $model::all();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'name', 'display_name']);
        });
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html() {
        return $this->builder()
                        ->setTableId('roles-table')
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
     *
     * @return array
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
