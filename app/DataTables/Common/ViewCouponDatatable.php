<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Coupon;
use Illuminate\Support\Collection;

class ViewCouponDatatable extends DataTable {

    /**
     * Build DataTable class.
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['action', 'status'])
                        /*->addColumn('status', function (Collection $model) {
                            $label = 'InActive';
                            $class = 'danger';
                            $tooltip = "Active";
                            if ($model->get('status') == '1') {
                                $label = 'Active';
                                $class = 'success';
                                $tooltip = "InActive";
                            }
                            return '<a href="javascript:void(0)" id="status_changed" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this coupon?" uid = "' . encrypt($model->get('coupon_id')) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        })*/
                        ->addColumn('action', function (Collection $model) {
                            $edit = route('admin.coupon.edit', ['coupon' => encrypt($model->get('coupon_id'))]);
                            $btn = '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('coupon_id')) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Coupon Details"><i class="bi bi-eye pe-0 fs-3"></i></button>
                                    <button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('coupon_code')) . '" onclick="ViewCouponused(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Coupon Used User"><i class="bi bi-eye pe-0 fs-3"></i></button>
                                    <a href="' . $edit . '" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>
                                    <a href="javascript:void(0)" data-id = "' . encrypt($model->get('coupon_id')) . '" onclick="deleteCoupon(this)" class="btn btn-sm btn-danger px-4 me-2 data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete"><i class="bi bi-trash fs-3"></i></a>';

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
    public function query(Coupon $model) {
        $data = collect();
        $data = $model::select('coupon_id', 'coupon_title', 'coupon_code', 'status')->where('status', '1')->get();
        $data = $data->map(function ($row) {
            return (collect($row));
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
                        ->setTableId('coupon-table')
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
            Column::make('coupon_id')->name('Coupon id'),
            Column::make('coupon_title'),
            Column::make('coupon_code'),
            //Column::make('status'),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
