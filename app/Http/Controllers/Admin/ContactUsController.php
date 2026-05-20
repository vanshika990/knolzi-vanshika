<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Common\ContactusDataTable;
use App\Models\Contactus;

class ContactUsController extends Controller {

    /**
     * Display a listing of the contact-us.
     *
     * @return \Illuminate\Http\Response
     */
    public function Getcontactus(Request $request, ContactusDataTable $dataTable) {
        return $dataTable->render('admin.contactus.index');
    }

    /**
     * Display the data specified contact-us.
     *
     * @param  \App\Models\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function GetDetail(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $data = Contactus::get();
            return view('admin.contactus.show')->with(['data' => $data[0]]);
        }
        abort(404);
    }

}
