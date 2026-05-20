<?php

namespace App\DataTables\Front;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\CourseSubscription;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\User;

class OrganizationSubscribeCourseDataTable extends DataTable {

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
                        ->rawColumns(['action', 'course_image'])
                        ->addColumn('action', function (Collection $model) {
                            $btn = "";
                            if ($this->user->can('add-licence-org')) {
                                $btn = '<button type="button" class=" btn btn-primary btn-sm px-4 me-2" data-id = "' . encrypt($model->get('course_id')) . '" onclick="ViewCourseUser(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Add License"><i class="far fa-id-badge"></i></button>';
                            }
                            return $btn;
                        })
                        ->addColumn('course_image', function (Collection $model) {
                            $img = "";
                            if ($model->get('course_image') != '') {
                                $img = '<img src="' . $model->get('course_image') . '" alt="Course Image" title="Course Image" width="100" height="50" />';
                            } else {
                                $img = "<img src='" . asset('assets/img/noimage.jpg') . "' alt='No Image' title='No Image' width='100' height='50' />";
                            }
                            return $img;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\User $model
     * @return type
     */
    public function query(CourseSubscription $model) {
        $data = collect();
        $user_id = $this->user->id;
        $data = CourseSubscription::leftJoin('tbl_course', 'tbl_course_subscription.course_id', '=', 'tbl_course.course_id')
                ->where('tbl_course.status', '1')->where('tbl_course.is_delete', '0')
                ->where('tbl_course_subscription.user_id', $user_id)
                ->where('tbl_course_subscription.status', '1')
                ->where('tbl_course_subscription.sub_expire_date', '>=', \Carbon\Carbon::now()->toDateString())
                ->groupBy('tbl_course.course_id')
                ->get(['tbl_course_subscription.id as course_id', 'tbl_course.course_name as course_name', 'tbl_course.course_image']);
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
                        ->setTableId('org-subscribe-course-table')
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
            Column::make('course_image')->title(__('Image')),
            Column::make('course_name')->title(__('Name')),
            Column::computed('action')->width(200)->exportable(false)->printable(false)->addClass('text-center')->responsivePriority(-1),
        ];
    }

}
