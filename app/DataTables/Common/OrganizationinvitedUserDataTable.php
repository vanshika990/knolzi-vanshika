<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Userinvitation;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class OrganizationinvitedUserDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['status'])
                        ->addColumn('status', function (Collection $model) {
                            $label = 'Pending';
                            $class = 'danger';
                            if ($model->get('status') == '1') {
                                $label = 'Accepted';
                                $class = 'success';
                            }
                            return '<a href="#" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        });
    }

    /**
     *  Get query source of dataTable.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Userinvitation $model
     * @return type
     */
    public function query(Request $request, Userinvitation $model) {
        $data = collect();
        $user_id = decrypt($request->id);
        $data = $model->where('company_id', $user_id)->get();

        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'user_email', 'company_code', 'status']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId(rand() . '-inviteduser-table')
                        ->columns($this->getColumns())
                        ->minifiedAjax()
                        ->stateSave(true)
                        ->orderBy(2)
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
            Column::make('user_email')->title(__('Email')),
            Column::make('company_code')->title(__('Codes')),
            Column::make('status')->title(__('Status')),
        ];
    }

}
