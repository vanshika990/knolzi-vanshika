<?php

namespace App\DataTables\Front;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseQuestion;
use App\Models\CourseHasUser;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class QuestionDataTable extends DataTable {

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
                        ->rawColumns(['action', 'status', 'question_name'])
                        ->editColumn('course_name', function (Collection $model) {
                            $course_name = "";
                            if (!empty($model->get('course'))) {
                                $course_name = $model->get('course')['course_name'];
                            }
                            return $course_name;
                        })
                        ->addColumn('question_name', function (Collection $model) {
                            $question_name = $model->get('question_name');
                            return $question_name;
                        })
                        ->addColumn('status', function (Collection $model) {
                            $label = 'Deactive';
                            $tooltip = "Active";
                            $class = 'danger';
                            if ($model->get('status') == '1') {
                                $label = 'Active';
                                $class = 'success';
                                $tooltip = "Deactive";
                            }
                            return '<a href="javascript:void(0)" id="status_changed"  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="You need to ' . $tooltip . ' this question?" uid = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        })
                        ->addColumn('action', function (Collection $model) {
                            $btn = "";
                            if ($this->user->can('view-course-question-details')) {
                                $btn .= '<button type="button" class="btn btn-sm btn-info px-4 me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="ViewquestionDetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Question Details"><i class="bi bi-eye pe-0 fs-3"></i></button>';
                            }
                            if ($this->user->can('edit-question')) {
                                $edit = route('user.my-course-question.edit', ['my_course_question' => encrypt($model->get('id'))]);
                                $btn .= '<a href="' . $edit . '" class="btn btn-sm btn-success px-4 me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit question"><i class="bi bi-pencil fs-3"></i></a>';
                            }

                            if ($this->user->can('delete-question')) {
                                $btn .= '<a href="javascript:void(0)" id="" data-id = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-danger px-4 me-2" onclick="deletequestion(this)"  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete question"><i class="bi bi-trash fs-3"></i></a>';
                            }
                            /* if ($this->user->can('clone-question')) {
                              $btn .= '<a href="javascript:void(0)" id="" data-id = "' . encrypt($model->get('id')) . '" class="btn btn-sm btn-secondary px-4 me-2" onclick="clonequestion(this)"  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Clone question"><i class="bi bi-journals fs-3"></i></a>';
                              } */
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\User $model
     * @return type
     */
    public function query(CourseQuestion $model, Request $request) {

        $data = collect();

        $id = auth()->user()->id;
        $courses = CourseHasUser::select('id', 'course_id')->with(array('course' => function($query) {
                $query->select('course_id', 'course_name', 'course_code', 'status')->where('is_delete', '0');
            }))->where('user_id', $id)->get();

        $course = [];
        foreach ($courses as $value) {
            $course[] = $value['course']['course_id'];
        }

        if (isset($request->id) && !empty($request->id)) {
            $data = $model->select()->with('course')->where('course_id', decrypt($request->id))->where('is_delete', '0')->get();
        } else {
            $data = $model->select()->with('course')->whereIN('course_id', $course)->where('is_delete', '0')->get();
        }

        $data = $data->map(function ($row) {
            return (collect($row))->only(['id', 'question_name', 'status', 'course']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId('my-course-question-table')
                        ->columns($this->getColumns())
                        ->minifiedAjax()
                        ->stateSave(true)
                        ->orderBy(0)
                        ->responsive()
                        ->autoWidth(false)
                        ->parameters(['scrollX' => false])
                        ->addTableClass('align-middle table-row-dashed table-striped fs-6 gy-5');
    }

    /**
     * Get columns.
     * @return type
     */
    protected function getColumns() {
        return [
            Column::make('course_name')->title(__('Course name'))->width(100),
            Column::make('question_name')->title(__('Question name')),
            Column::make('status')->title(__('Status')),
                    Column::computed('action')->width(200)
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
