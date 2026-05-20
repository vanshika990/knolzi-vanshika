<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\InstituteHasAuthor;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class InstituteAuthorDataTable extends DataTable {

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
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $model
     * @return type
     */
    public function query(Request $request, InstituteHasAuthor $model) {
        $data = collect();
        $id = decrypt($request->id);

        $data = InstituteHasAuthor::with('user')->where('institute_id',$id)->get();
        $data = $data->map(function ($row) {
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
                        ->setTableId(rand() . '-instituteauthor-table')
                        ->columns($this->getColumns())
                        ->minifiedAjax()
                        ->stateSave(true)
                        ->responsive(true)
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
            Column::make('user.name')->title(__('Author Name')),
            Column::make('user.email')->title(__('Author Email')),
        ];
    }

}
