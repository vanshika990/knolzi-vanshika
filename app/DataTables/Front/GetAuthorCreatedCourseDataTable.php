<?php

namespace App\DataTables\Front;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseHasUser;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class GetAuthorCreatedCourseDataTable extends DataTable {

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
                        ->rawColumns(['action', 'status'])
                        ->addColumn('status', function (Collection $model) {
                            $label = 'Unpublish';
                            $class = 'danger';
                            $tooltip = "Publish";
                            if ($model->get('course')['status'] == '1') {
                                $label = 'Publish';
                                $class = 'success';
                                $tooltip = "Unpublish";
                            }
                            return '<a href="javascript:void(0)" id="status_changed" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this course?" uid = "' . encrypt($model->get('course')['course_id']) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        })
                        ->addColumn('action', function (Collection $model) {
                            $btn = "";
                            if ($this->user->can('view-course-review')) {
                                $btn .= '<a href="javascript:void(0)" class="btn btn-sm btn-info me-2" data-id = "' . encrypt($model->get('course')['course_id']) . '" onclick="Viewallreview(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Course Review"><i class="fas fa-file-alt"></i></i></a>';
                            }
                            if ($this->user->can('view-course-qa')) {
                                $btn .= '<a type="button" class="btn btn-sm btn-warning me-2" data-id = "' . encrypt($model->get('course')['course_id']) . '" onclick="Viewcourseqa(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Course QA"><i class="bi bi-card-checklist"></i></a>';
                            }
                            if ($this->user->can('view-course-detail')) {
                                $btn .= '<a type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('course')['course_id']) . '" onclick="Viewdetails(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Course Details"><i class="bi bi-eye pe-0 fs-3"></i></button>';
                            }

                            if ($this->user->can('view-course-question')) {
                                $coursequestion = route('user.my-course-question.index', ['id' => encrypt($model->get('course')['course_id'])]);
                                $btn .= '<a href="' . $coursequestion . '" class="btn btn-sm btn-info px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Course Question"><i class="las la-question-circle fs-3"></i></a>';
                            }
                            $btn .= '<a type="button" class="btn btn-sm btn-warning me-2" data-id = "' . encrypt($model->get('course')['course_id']) . '" onclick="Viewcourseuserdetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Subscription User"><i class="bi bi-person pe-0 fs-3"></i></a>';


                            if ($this->user->can('edit-course')) {
                                $edit = route('user.view-my-course.edit', ['view_my_course' => encrypt($model->get('course')['course_id'])]);
                                $btn .= '<a href="' . $edit . '" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit"><i class="bi bi-pencil fs-3"></i></a>';
                            }

                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\Course $model
     * @return type
     */
    public function query(CourseHasUser $model) {
        $data = collect();
        $id = auth()->user()->id;
        $data = $model->select('id', 'course_id')->with(array('course' => function($query) {
                $query->select('course_id', 'course_name', 'course_code', 'status')->where('is_delete', '0');
            }))->where('user_id', $id)->get();
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
                        ->setTableId('view-my-course-table')
                        ->columns($this->getColumns())
                        ->minifiedAjax()
                        ->stateSave(true)
                        ->orderBy(0)
                        ->responsive()
                        ->autoWidth(false)
                        ->parameters(['scrollX' => false])
                        ->addTableClass('align-middle table-row-dashed fs-6 gy-5');
    }

    /**
     * Get columns.
     * @return type
     */
    protected function getColumns() {
        return [
            Column::make('course.course_id')->title(__('Course id')),
            Column::make('course.course_name')->title(__('Course name')),
            Column::make('course.course_code')->title(__('Course code')),
            Column::make('status'),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
