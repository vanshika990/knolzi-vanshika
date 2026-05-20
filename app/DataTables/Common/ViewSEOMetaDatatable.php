<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\SEOmeta;
use Illuminate\Support\Collection;

class ViewSEOMetaDatatable extends DataTable {

    /**
     * Build DataTable class.
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['action'])
                        ->editColumn('slug', function (Collection $model) {
                            return ucwords(str_replace('-', ' ', $model->get('slug')));
                        })
                        ->addColumn('action', function (Collection $model) {
                            $edit = route('admin.seometa.edit', ['seometum' => encrypt($model->get('id'))]);
                            $btn = '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Details"><i class="bi bi-eye pe-0 fs-3"></i></button>
                            <a href="javascript:void(0)" data-id = "' . encrypt($model->get('id')) . '" onclick="editSEOmeta(this)"  class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>        
                            <a href="javascript:void(0)" data-id = "' . encrypt($model->get('id')) . '" onclick="deleteSEOmeta(this)" class="btn btn-sm btn-danger  px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete"><i class="bi bi-trash fs-3"></i></a>';

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
    public function query(SEOmeta $model) {
        $data = collect();
        $data = $model::select('id', 'slug')->get();
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
                        ->setTableId('seometa-table')
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
            Column::make('slug')->title('Page Name'),
            Column::computed('action')->exportable(false)->printable(false)->addClass('text-center')->responsivePriority(-1),
        ];
    }

}
