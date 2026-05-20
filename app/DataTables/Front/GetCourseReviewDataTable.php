<?php

namespace App\DataTables\Front;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseHasReview;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class GetCourseReviewDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['action', 'status'])
                        ->editColumn('user_id', function (Collection $model) {
                            $user_name = $model->get('user');
                            if (isset($user_name['name'])) {
                                return $user_name['name'];
                            }
                            return "";
                        })
                        ->editColumn('review', function (Collection $model) {
                            $revirw_length = strlen($model->get('review'));
                            if ($revirw_length > 25) {
                                return substr($model->get('review'), 0, 25) . '.....';
                            } else {
                                return $model->get('review');
                            }
                        })
                        ->addColumn('status', function (Collection $model) {
                            if ($model->get('status') == '1') {
                                $label = 'Approved';
                                $class = 'success';
                                $tooltip = "Reject";
                            } elseif ($model->get('status') == '0') {
                                $label = 'Pending';
                                $class = 'primary';
                                $tooltip = "Deactive";
                            } else {
                                $label = 'Rejected';
                                $class = 'danger';
                                $tooltip = "Approve";
                            }

                            return '<a href="javascript:void(0)" id="status_changed" class="btn btn-sm btn-' . $class . '">' . $label . '</a>';
                        })
                        ->addColumn('action', function (Collection $model) {
                            $btn = '<button type="button" class="btn btn-success me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="EditStatus(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit Status"><i class="bi bi-pencil"></i></button>
                                    <button type="button" class="btn btn-primary me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="ViewReviewDetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View Review Details"><i class="bi bi-eye"></i></button>';
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\CourseHasReview $model
     * @return type
     */
    public function query(Request $request, CourseHasReview $model) {
        $data = collect();
        $data = $request->all();
        $id = decrypt($request->id);

        if ($request->status != "" && $request->status != "4") {
            $status = $request->status;
            $review_data = $model->where('course_id', $id)->where('status', $status)->with('user')->get();
        } else {
            $review_data = $model->where('course_id', $id)->with('user')->get();
        }

        $data = $review_data->map(function ($row) {
            return (collect($row))->only(['id', 'course_id', 'user_id', 'rate', 'review', 'status', 'user']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                        ->setTableId('get-course-review-table')
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
            Column::make('user_id')->title(__('User Name')),
            Column::make('rate')->title(__('Rating')),
            Column::make('review')->title(__('Review')),
            Column::make('status'),
                    Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
