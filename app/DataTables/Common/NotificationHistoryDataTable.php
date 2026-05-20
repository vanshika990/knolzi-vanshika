<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\NotificationHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationHistoryDataTable extends DataTable {

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
                        ->rawColumns(['status'])
                        ->addColumn('status', function (Collection $model) {
                            $label = 'Unread';
                            $class = 'danger';
                            if ($model->get('status') == '1') {
                                $label = 'Read';
                                $class = 'success';
                            }
                            return '<a class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        });
    }

    /**
     * Get query source of dataTable.
     * @param  Activity  $model
     * @return \Illuminate\Database\Eloquent\Builder
     * @param \App\Models\Category $model
     * @return type
     */
    public function query(Request $request, NotificationHistory $model) {
        $data = collect();
        $id = decrypt($request->id);
        $data = $model->select('tbl_notification_history.*', 'tbl_user.name')
            ->leftjoin('tbl_user','tbl_notification_history.user_id','tbl_user.id')->where('tbl_notification_history.notification_id', $id)->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'name', 'status']);
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
                        ->setTableId('notification-history-table')
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
            Column::make('name')->title(__('Recipients Name')),
            Column::make('status')->title(__('Notification Status'))
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
