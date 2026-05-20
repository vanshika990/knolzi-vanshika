<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryDataTable extends DataTable {

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
                        ->rawColumns(['action', 'status'])
                        ->editColumn('parent_id', function (Collection $model) {
                            $parent_name = $model->get('parents');
                            if ($parent_name == '') {
                                return '-';
                            } else {
                                return $parent_name['name'];
                            }
                        })
                        ->addColumn('status', function (Collection $model) {
                            $label = 'Deactive';
                            $class = 'danger';
                            $tooltip = "Activate";
                            if ($model->get('status') == '1') {
                                $label = 'Active';
                                $class = 'success';
                                $tooltip = "Deactive";
                            }
                            return '<a href="javascript:void(0)" id="status_changed" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this category?" uid = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        })
                        ->addColumn('action', function (Collection $model) {

                            $btn = '<a href="javascript:void(0)" data-id = "' . encrypt($model->get('id')) . '" onclick="editCategory(this)" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>
                        <a href="javascript:void(0)" data-id = "' . encrypt($model->get('id')) . '" onclick="deleteCategory(this)" class="btn btn-sm btn-danger px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete"><i class="bi bi-trash fs-3"></i></a>';

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
    public function query(Category $model) {
        $data = collect();
        $data = $model->with('parents')->get();
        $data = $data->map(function ($a) {
            return (collect($a))->only(['id', 'name', 'parent_id', 'status', 'parents']);
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
                        ->setTableId('category-table')
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
            Column::make('name'),
            Column::make('parent_id')->title(__('Parent Category')),
            Column::make('status'),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
