<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\UserHasOrganization;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class OrganizationindividualDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['action', 'status'])
                        ->addColumn('status', function (Collection $model) {
                            $label = 'Deactive';
                            $class = 'danger';
                            $tooltip = "Active";
                            if ($model->get('user')['status'] == '1') {
                                $label = 'Active';
                                $class = 'success';
                                $tooltip = "Deactive";
                            }
                            return '<a href="javascript:void(0)" id="status_changed" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this user?" uid = "' . encrypt($model->get('user')['id']) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        })
                        ->addColumn('action', function (Collection $model) {
                            $btn = '<a href="javascript:void(0)" class="btn btn-sm btn-warning px-4 me-2" data-id = "' . encrypt($model->get('user')['id']) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View User Details"><i class="bi bi-eye pe-0 fs-2"></i></a>';
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $model
     * @return type
     */
    public function query(Request $request, UserHasOrganization $model) {
        $data = collect();
        $id = decrypt($request->id);
        $data = $model->select()->with('user')->where(['org_id' => $id])->get();
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
                        ->setTableId(rand() . '-organizationindividual-table')
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
            Column::make('user.name')->title(__('Name')),
            Column::make('user.email')->title(__('Email')),
            Column::make('status')->title(__('Status')),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
