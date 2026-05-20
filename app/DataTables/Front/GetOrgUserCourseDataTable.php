<?php

namespace App\DataTables\Front;

use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class GetOrgUserCourseDataTable extends DataTable {

    /**
     * Build DataTable class.
     * @param type $query
     * @return type
     */
    public function dataTable($query) {
        return datatables()
                        ->collection($query)
                        ->rawColumns(['course_image'])
                        ->addColumn('course_image', function (Collection $model) {
                            $course_image = $model->get('course_image');
                            if ($model->get('course_image') != '') {
                                $img = '<img src="' . $course_image . '" alt="Course Image" title="Course Image" width="100" height="50" />';
                            } else {
                                $img = "<img src='" . asset('assets/img/noimage.jpg') . "' alt='No Image' title='No Image' width='100' height='50' />";
                            }
                            return $img;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\CourseHasReview $model
     * @return type
     */
    public function query(Request $request, CourseSubscription $model) {
        $data = collect();
        $data = $request->all();
        $id = decrypt($request->id);

        $data = $model->join('tbl_course_subscription_licence', 'tbl_course_subscription.id', '=', 'tbl_course_subscription_licence.course_subscription_id')->join('tbl_course', 'tbl_course_subscription_licence.course_id', '=', 'tbl_course.course_id')->where('tbl_course_subscription.user_id', auth()->user()->id)->where('tbl_course_subscription_licence.user_id', $id)->select('tbl_course_subscription.*', 'tbl_course.course_name', 'tbl_course.course_image')->get();

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
                        ->setTableId('get-orguser-course-table')
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
            Column::computed('course_image')->exportable(false)->printable(false)->responsivePriority(-1),
            Column::make('course_name')->title(__('Course Name')),
        ];
    }

}
