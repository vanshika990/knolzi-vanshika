<?php

namespace App\DataTables\Front;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\InstituteHasAuthor;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class GetUserAuthorDataTable extends DataTable {

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
                        ->rawColumns(['action'])
                        ->addColumn('action', function (Collection $model) {
                            $btn = "";
                            if ($this->user->can('view-author-details')) {
                                if (!empty($model->get('user'))) {
                                    $btn = '<button type="button" class="btn btn-primary me-2" data-id = "' . encrypt($model->get('user')['id']) . '" onclick="ViewauthorDetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Author Details"><i class="fas fa-eye"></i></button>';
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
    public function query(Request $request, InstituteHasAuthor $model) {

        $data = collect();
        $institute_id = auth()->user()->id;
        $data = $model->select('id', 'author_id', 'institute_id')->with(array('user' => function($query) {
                $query->select('id', 'name', 'email');
            }))->where('institute_id', $institute_id)->get();
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
                        ->setTableId('own-author-table')
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
            Column::make('user.name')->title(__('Author Name')),
            Column::make('user.email')->title(__('Author Email')),
                    Column::computed('action')->width(200)
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
