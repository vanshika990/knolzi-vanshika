<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseSubscription;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class getCouponUsedUserDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
            ->collection($query)
            ->editColumn('user', function (Collection $model) {
                $user_name = "";
                if (!empty($model->get('user'))) {
                    $company_name = $model->get('user')['name'];
                }
                return $company_name;
            });
    }

    /**
     * Get query source of dataTable.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CourseSubscription $model
     * @return type
     */
    public function query(Request $request, CourseSubscription $model) {
        $data = collect();
        $code = decrypt($request->id);
        $data = $model->with('user')->where('discount_code', $code)->where('status','1')->get();
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
            ->setTableId('couponuseduser-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(true)
            ->orderBy(0)
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
            Column::make('user')->title(__('User Name')),
        ];
    }

}
