<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Course;
use Illuminate\Support\Collection;

class ViewCourseDatatable extends DataTable {

    /**
     * Build DataTable class.
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['action', 'status'])
                        ->addColumn('status', function (Collection $model) {
                            $label = 'Unpublish';
                            $class = 'danger';
                            $tooltip = "Publish";
                            if ($model->get('status') == '1') {
                                $label = 'Publish';
                                $class = 'success';
                                $tooltip = "Unpublish";
                            }
                            return '<a href="javascript:void(0)" id="status_changed" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this course?" uid = "' . encrypt($model->get('course_id')) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        })
                        ->addColumn('action', function (Collection $model) {
                            $edit = route('admin.course.edit', ['course' => encrypt($model->get('course_id'))]);
                            $coursequestion = route('admin.question.index', ['id' => encrypt($model->get('course_id'))]);
                            $btn = '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('course_id')) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Course Details"><i class="bi bi-eye pe-0 fs-3"></i></button>
                            <a href="' . $coursequestion . '" class="btn btn-sm btn-info px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Course Question"><i class="bi bi-eye pe-0 fs-3"></i></a>
                            <a href="' . $edit . '" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>
                            <a href="javascript:void(0)" data-id = "' . encrypt($model->get('course_id')) . '" onclick="deleteCourse(this)" class="btn btn-sm btn-danger px-4 me-2 data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete"><i class="bi bi-trash fs-3"></i></a>';
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
    public function query(Course $model) {
        $data = collect();
        $data = $model::select('course_id', 'course_name', 'course_code', 'status')->where('is_delete', '0')->get();
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
                        ->setTableId('course-table')
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
            Column::make('course_id'),
            Column::make('course_name'),
            Column::make('course_code'),
            Column::make('status'),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
