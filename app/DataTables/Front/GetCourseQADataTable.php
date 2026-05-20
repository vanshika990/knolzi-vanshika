<?php

namespace App\DataTables\Front;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseHasQA;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class GetCourseQADataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
            ->collection($query)
            ->rawColumns(['action','status'])
            
            ->addColumn('status', function (Collection $model) {
                $label = 'Pending';
                $class = 'primary';
                if ($model->get('status') == '1') {
                    $label = 'Approve';
                    $class = 'success';
                }
                if ($model->get('status') == '2') {
                    $label = 'Reject';
                    $class = 'danger';
                }
                return '<button id="status" class="btn btn-sm btn-' . $class . '">' . $label . '</button>';
            })
            ->addColumn('action', function (Collection $model) {

                $btn = '<button type="button" data-id = "' .  encrypt($model->get('id')) . '" onclick="editCourseQA(this)" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></button>';
                return $btn;

            });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\Course $model
     * @return type
     */
    public function query(Request $request,CourseHasQA $model) {

        $data = collect();
        $id = decrypt($request->id);

        if(!empty($request->filter)){
            
            $status = $request->filter;

            if($status == '1'){
                $status = '0';
            }
            if($status == '2'){
                $status = '1';
            }
            if($status == '3'){
                $status = '2';
            }

            $qa_data = $model->with('user')->where('course_id', $id)->where('status', $status)->get();

        }
        else{
            $qa_data = $model->with('user')->where('course_id', $id)->get();
        }

        $data = $qa_data->map(function ($row) {
            return (collect($row))->only(['id', 'question_name', 'status', 'user']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
            ->setTableId('get-course-qa-table')
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
            Column::make('question_name')->title(__('Question')),
            Column::make('status')->title(__('Status')),
            Column::computed('action')->width(200)
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
        ];
    }

}
