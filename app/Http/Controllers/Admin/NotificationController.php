<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationHistory;
use App\Models\User;
use App\Models\UserHasOrganization;
use Illuminate\Http\Request;
use DataTables;
use App\DataTables\Common\NotificationDataTable;
use App\DataTables\Common\NotificationHistoryDataTable;

class NotificationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, NotificationDataTable $dataTable) {
        return $dataTable->render('admin.notification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            $organization = User::select('id', 'name')->role('organization')->get();
            $user = User::select('id', 'name')->role('individual')->get();
            return view('admin.notification.create')->with(['organization' => $organization, 'user' => $user]);
        }
        abourt(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'title' => 'required',
                'description' => 'required',
            ]);
            $data = $request->all();

            $insert["title"] = $data['title'];
            $insert["body"] = $data['description'];
            $insert["created_by"] = '0';
            $notification = notification::create($insert);

            if ($data['send_to'] == 0) {
                $send_user = User::select('id', 'name')->role(['organization', 'individual'])->get()->toarray();
            } elseif ($data['send_to'] == 1) {
                if ($data['organization'] == 0 || in_array('0', $data['organization'])) {
                    $send_user = User::select('id', 'name')->role('organization')->get()->toarray();
                } else {
                    $send_user = User::select('id', 'name')->whereIn('id', $data['organization'])->get()->toarray();
                }
            } else {
                if ($data['user'] == 0 || in_array('0', $data['user'])) {
                    $send_user = User::select('id', 'name')->role('individual')->get()->toarray();
                } else {
                    $send_user = User::select('id', 'name')->whereIn('id', $data['user'])->get()->toarray();
                }
            }

            foreach ($send_user as $value) {
                $insert_history["notification_id"] = $notification['id'];
                $insert_history["user_id"] = $value['id'];
                $insert_history["status"] = '0';
                NotificationHistory::create($insert_history);
            }

            if ($data['send_to'] == 1 && $data['with_user'] == 1) {
                if ($data['organization'] == 0 || in_array('0', $data['organization'])) {
                    $send_org_user = UserHasOrganization::select('id', 'user_id')->get()->toarray();
                } else {
                    $send_org_user = UserHasOrganization::select('id', 'user_id')->whereIn('org_id', $data['organization'])->get()->toarray();
                }

                foreach ($send_org_user as $value) {
                    $insert_orghistory["notification_id"] = $notification['id'];
                    $insert_orghistory["user_id"] = $value['user_id'];
                    $insert_orghistory["status"] = '0';
                    NotificationHistory::create($insert_orghistory);
                }
            }

            return ["success" => true, "message" => "Notification created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function GetNotificationHistory(Request $request, NotificationHistoryDataTable $dataTable) {
        if ($request->ajax()) {
            $id = decrypt($request->id);
            $notification = Notification::where('id', $id)->first();
            return $dataTable->render('admin.notification.show', ['notification' => $notification]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification) {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification) {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification) {
        abort(404);
    }

    /**
     * search Organization
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SearchOrganization(Request $request) {
        $response = array();
        if (!empty($request->searchTerm)) {
            $users = User::select('id', 'name')->role('organization')->where('name', 'like', '%' . $request->searchTerm . '%')->get();
            if (!empty($users)) {
                $response[] = array(
                    "id" => '0',
                    "text" => 'All'
                );
                foreach ($users as $row) {
                    $response[] = array(
                        "id" => $row['id'],
                        "text" => $row['name']
                    );
                }
            }
        }
        return json_encode($response);
    }

    /**
     * search User
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function SearchUser(Request $request) {
        $response = array();
        if (!empty($request->searchTerm)) {
            $users = User::select('id', 'name')->role('individual')->where('name', 'like', '%' . $request->searchTerm . '%')->get();
            if (!empty($users)) {
                $response[] = array(
                    "id" => '0',
                    "text" => 'All'
                );
                foreach ($users as $row) {
                    $response[] = array(
                        "id" => $row['id'],
                        "text" => $row['name']
                    );
                }
            }
        }
        return json_encode($response);
    }

}
