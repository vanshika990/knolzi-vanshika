<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class NotificationDataTable extends DataTable {

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
                        ->editColumn('sender_name', function (Collection $model) {
                            if($model['created_by'] == '0'){
                                return Auth::guard('admin')->user()->name;
                            }else{
                                return $model['sender_name'];
                            }
                        })
                        ->editColumn('created_at', function (Collection $model) {
                            $created_at = date('Y-m-d H:i:s', strtotime($model['created_at']));
                            return $created_at;
                        })
                        ->addColumn('action', function (Collection $model) {

                            $btn = '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Notification Details"><i class="bi bi-eye pe-0 fs-2"></i></button>';

                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param  Activity  $model
     * @return \Illuminate\Database\Eloquent\Builder
     * @param \App\Models\Category $model
     * @return type
     */
    public function query(Notification $model) {
        $data = collect();
        $data = $model->select('tbl_notification.*', 'tbl_user.name as sender_name')
            ->leftjoin('tbl_user','tbl_notification.created_by','tbl_user.id')->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'sender_name', 'title', 'created_by', 'created_at']);
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
                        ->setTableId('notification-table')
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
            Column::make('sender_name'),
            Column::make('title'),
            Column::make('created_at')->title(__('Created Date')),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
