<?php

namespace App\DataTables\Common;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\Usercourseattempt;
use Illuminate\Support\Collection;
use DB;

class CoursecompleteDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['status'])
                        ->addColumn('user_name', function (Collection $model) {
                            $user_name = "";
                            if (!empty($model->get('user'))) {
                                $user_name = $model->get('user')['name'];
                            }
                            return $user_name;
                        })
                        ->addColumn('course_name', function (Collection $model) {
                            $course_name = "";
                            if (!empty($model->get('course'))) {
                                $course_name = $model->get('course')['course_name'];
                            }
                            return $course_name;
                        })
                        ->addColumn('status', function (Collection $model) {

                            $email_id = ($model->get('user')['email']);
                            $user_id = $model->get('user')['id'];
                            $course_id = $model->get('course')['course_id'];

                            if ($model->get('is_mail_send') == '0') {
                                $label = 'Upload';
                                $class = 'danger';
                                return '<a id="send_mail" data-uid = "' . $user_id . '" data-cid="' . $course_id . '" data-email_id="' . $email_id . '" onclick="uploaddata(this)" class=" btn btn-xl btn-' . $class . '">' . $label . '</a>';
                            }

                            if ($model->get('is_mail_send') == '1') {
                                $label = 'Send';
                                $class = 'success';
                                return '<button id="send_mail" qid = "" class="btn btn-xl btn-' . $class . '">' . $label . '</button>';
                            }
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\Usercourseattempt $model
     * @return type
     */
    public function query(Usercourseattempt $model) {
        $data = collect();
        $data = $model->select([DB::raw('COUNT(user_id) as user_count'), DB::raw('COUNT(course_id) as courser_count'), 'user_id', 'course_id', 'is_mail_send'])->with('course')->with('user')->where('state', 'complete')->groupBy(['user_id', 'course_id'])->get();
        $data = $data->map(function ($row) {
            return (collect($row))->only(['user_count', 'user_id', 'is_mail_send', 'course', 'user']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId('coursecomplete-table')
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
            Column::make('user_name')->title(__('User Name')),
            Column::make('course_name')->title(__('Course Name')),
            Column::make('status')->title(__('Status')),
        ];
    }

}
