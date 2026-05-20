<?php

namespace App\DataTables\Front;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Userinvitation;
use Illuminate\Support\Collection;

class InvitationDataTable extends DataTable {

    protected $user;

    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->user = Auth::user();
    }

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
                            if ($model->get('status') == '1') {
                                $status = "<button class='btn btn-success'>Approove</button>";
                            } else {
                                $status = "<button class='btn btn-warning'>Pending</button>";
                            }
                            return $status;
                        })
                        ->addColumn('action', function (Collection $model) {
                            $btn = "<button class='btn btn-success'>Already approved</button>";
                            if ($this->user->can('resend-invitation-org')) {
                                if ($model->get('status') != '1') {
                                    $btn = '<a href="javascript:void(0)" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="resendInvitation(this)"data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Resend"><i class="fas fa-share-square"></i></a>';
                                }
                            }
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\User $model
     * @return type
     */
    public function query(Userinvitation $model) {
        $data = collect();
        $id = auth()->user()->id;
        $data = Userinvitation::select('id', 'user_email', 'resend', 'status')->where('company_id', $id)->get();
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
                        ->setTableId('invitation-table')
                        ->columns($this->getColumns())
                        ->minifiedAjax()
                        ->stateSave(true)
                        ->orderBy(2)
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
            Column::make('user_email')->title(__('Email Address')),
            Column::make('resend')->title(__('Resend Count')),
            Column::make('status')->title(__('Status')),
            Column::computed('action')->exportable(false)->printable(false)->addClass('text-center')->responsivePriority(-1),
        ];
    }

}
