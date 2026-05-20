<?php

namespace App\DataTables\Front;

use Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\CourseSubscription;
use App\Models\CourseSubscriptionLicence;

class ViewCourseLicenceDataTable extends DataTable {

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
                        ->addColumn('user_name', function (Collection $model) {

                            $name = $model->get('user')['name'];
                            return $name;
                        })
                        ->addColumn('action', function (Collection $model) {
                            $btn = "";
                            if ($this->user->can('view-user-details')) {
                                $btn .= '<button type="button" class="btn btn-primary me-2" data-id = "' . encrypt($model->get('user')['id']) . '" onclick="ViewUserDetail(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="View User Detail"><i class="fas fa-eye"></i></button>';
                            }
                            if ($this->user->can('remove-licence-org')) {
                                $btn .= '<button type="button" class="btn btn-danger me-2" data-id = "' . encrypt($model->get('id')) . '" onclick="DeleteCourseUser(this)" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete User"><i class="fas fa-trash"></i></button>';
                            }
                            return $btn;
                        });
    }

    /**
     * Get query source of dataTable.
     * @param \App\Models\User $model
     * @return type
     */
    public function query(Request $request, CourseSubscription $model) {
        $data = collect();
        $user_id = $this->user->id;
        $course_subscription_id = decrypt($request->id);
        $course_sub_data = CourseSubscriptionLicence::with('user')->where('course_subscription_id', $course_subscription_id)->where('status', '1')->get();

        $data = $course_sub_data->map(function ($row) {
            return (collect($row))->only(['id', 'user']);
        });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     * @return type
     */
    public function html() {
        return $this->builder()
                ->setTableId('org-subscribe-course-user-table')
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
            Column::make('user.email')->title(__('User Email')),
                    Column::computed('action')->width(200)
                    ->exportable(false)
                    ->printable(false)
                    ->addClass('text-center')
                    ->responsivePriority(-1),
        ];
    }

}
