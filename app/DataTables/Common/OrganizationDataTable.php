<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\User;
use Illuminate\Support\Collection;

class OrganizationDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
            ->collection($query)
            ->rawColumns(['action', 'subscription', 'status'])
            ->editColumn('company_name', function (Collection $model) {
                $company_name = $model->get('company_name');
                return $company_name;
            })
            ->addColumn('status', function (Collection $model) {
                $label = 'Deactive';
                $tooltip = "Active";
                $class = 'danger';
                if ($model->get('status') == '1') {
                    $label = 'Active';
                    $class = 'success';
                    $tooltip = "Deactive";
                }
                return '<a href="javascript:void(0)" id="status_changed"  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this user?" uid = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
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


                $btn = '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View User Details"><i class="bi bi-eye pe-0 fs-2"></i></button>'
                        . '<a href="javascript:void(0)" class="btn btn-sm btn-warning px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="getIndividualUser(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Individual User"><i class="bi bi-person fs-2"></i></a>'// Individual User
                        . '<a href="javascript:void(0)" id="" data-id = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-primary px-4 me-2" onclick="getSubscribeCourse(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Subscription"><i class="bi bi-journal-bookmark-fill fs-2"></i></a>' //Get subscribe course
                        . '<a href="javascript:void(0)" id="" data-id = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-secondary px-4 me-2" onclick="getinviteduser(this)"  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View invitation"><i class="bi bi-person-bounding-box fs-2"></i></a>' //view send user invitation
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
        $data = $model->role('organization')->select(['id', 'name', 'email', 'company_name', 'status', 'email_verified_at'])->whereIn('status', ['1', '2'])->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'name', 'email', 'company_name', 'status', 'email_verified_at']);
        });
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId('organization-table')
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
            Column::make('email'),
            Column::make('company_name'),
            Column::make('status')->title(__('Status')),
            Column::computed('subscription')->title(__('Subscription'))
                ->orderable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
        ];
    }

}
