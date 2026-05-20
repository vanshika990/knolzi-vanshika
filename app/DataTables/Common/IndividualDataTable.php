<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\User;
use Illuminate\Support\Collection;

class IndividualDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
            ->collection($query)
            ->rawColumns(['action', 'subscription' ,'status'])
            ->editColumn('company_name', function (Collection $model) {
                $company_name = "";
                if (!empty($model->get('org_data'))) {
                    $company_name = $model->get('org_data')['company_name'];
                }
                return $company_name;
            })
            ->addColumn('status', function (Collection $model) {
                $label = 'Deactive';
                $class = 'danger';
                $tooltip = "Active";
                if ($model->get('status') == '1') {
                    $label = 'Active';
                    $class = 'success';
                    $tooltip = "Deactive";
                }
                return '<a href="javascript:void(0)" id="status_changed" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this user?" uid = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
            })
            ->addColumn('subscription', function (Collection $model) {
                return '<a href="javascript:void(0)" id="" data-id = "' . encrypt($model->get('id')) . '" onclick="addMenuallySubscription(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Add manually subscription" class="btn btn-sm btn-primary"><i class="bi bi-journal-bookmark fs-2"></i></a>';
            })
            ->addColumn('action', function (Collection $model) {
                
                if (!empty($model->get('email_verified_at'))) {
                    $email_verify = '<button class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Already Verify"><i class="bi bi-check2-all fs-2"></i></button>';
                } else {

                    $email_id = $model->get('email');
                    $email_verify = '<a href="javascript:void(0)" id="" data-email_verify = "' . $email_id . '" class="btn btn-sm btn-danger" onclick="emailverify(this)"  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Resend Verification Link"><i class="bi bi-bootstrap-reboot fs-2"></i></a>';
                }

                $btn = '<a href="javascript:void(0)" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="GetDetails(this)"data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View User Details"><i class="bi bi-eye pe-0 fs-2"></i></a>'
                        . '<a href="javascript:void(0)" class="btn btn-sm btn-primary px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="getSubscribeCourse(this)"data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Own Course"><i class="bi bi-file-ruled fs-2"></i></a>'
                        . $email_verify;
                return $btn;
            });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\User $model
     * @return type
     */
    public function query(User $model) {
        $data = collect();
        $data = $model->role('individual')->with('OrgData')->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'name', 'email', 'company_name', 'status', 'org_data']);
        });
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
            ->setTableId('individual-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(true)
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
            Column::make('name')->title(__('Name')),
            Column::make('email'),
            Column::make('company_name'),
            Column::make('status')->title(__('Status')),
            Column::make('subscription')->title(__('Subscription'))
                ->orderable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
        ];
    }

}
