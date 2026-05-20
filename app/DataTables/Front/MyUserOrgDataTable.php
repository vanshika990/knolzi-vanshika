<?php

namespace App\DataTables\Front;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\UserHasOrganization;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class MyUserOrgDataTable extends DataTable {

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
                            $btn = '<button type="button" class="btn btn-primary me-2" data-id = "' . encrypt($model->get('user_id')) . '" onclick="ViewUserDetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View User Details"><i class="fas fa-eye"></i></button>';
                            if ($this->user->can('view-org-user-course')) {
                                $btn .= '<button type="button" class="btn btn-success me-2" data-id = "' . encrypt($model->get('user_id')) . '" onclick="ViewUserCourseDetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Course Details"><i class="fas fa-list-ul"></i></button>';
                            }
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\CourseHasReview $model
     * @return type
     */
    public function query(Request $request, UserHasOrganization $model) {
        $data = collect();
        $data = $request->all();
        $data = $model->select('id', 'user_id', 'org_id')->where('org_id', auth()->user()->id)->with(array('user' => function($query) {
                $query->select('id', 'name', 'email');
            }))->get();
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
                        ->setTableId('get-my-user-table')
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
            Column::make('user.name')->title(__('User Name')),
            Column::make('user.email')->title(__('Email')),
            Column::computed('action')->exportable(false)->printable(false)->addClass('text-center')->responsivePriority(-1),
        ];
    }

}
