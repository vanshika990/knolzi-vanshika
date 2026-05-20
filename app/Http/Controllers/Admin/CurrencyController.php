<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use DataTables;
use App\DataTables\Common\CurrencyDataTable;

class CurrencyController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CurrencyDataTable $dataTable) {
        return $dataTable->render('admin.currency.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($request->ajax()) {
            $currency = Currency::all()->toarray();
            $currency_list = config('currency.currency_list');

            $currency_array = array_column($currency, 'name');
            $remove_currency_array = implode(',', $currency_array);
            foreach ($currency_list as $key => $data) {
                if (in_array($data['name'], $currency_array)) {
                    unset($currency_list[$key]);
                }
            }

            return view('admin.currency.create')->with(['currency_list' => $currency_list]);
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
                'name' => 'required',
                'inr_value' => 'required',
                'rate' => 'required',
                'symbol' => 'required',
            ]);

            $data = $request->all();

            $data["name"] = $data["name"];
            $data["short_name"] = $data["short_name"];
            $data["inr_value"] = $data["inr_value"];
            $data["rate"] = $data["rate"];
            $data["symbol"] = $data["symbol"];
            $data["created_at"] = date('Y-m-d H:i:s');
            $data["updated_at"] = date('Y-m-d H:i:s');
            Currency::create($data);

            return ["success" => true, "message" => "Currency created successfully"];
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $currency_data = Currency::find($id);

            $currency = Currency::all()->toarray();
            $currency_list = config('currency.currency_list');

            $currency_array = array_column($currency, 'name');
            $remove_currency_array = implode(',', $currency_array);
            foreach ($currency_list as $key => $data) {
                if (in_array($data['name'], $currency_array) && $data['name'] != $currency_data['name']) {
                    unset($currency_list[$key]);
                }
            }

            return view('admin.currency.edit')->with([
                        'currency' => $currency_data, 'currency_list' => $currency_list
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency) {
        if ($request->ajax()) {
            $validatedData = $request->validate([
                'name' => 'required',
                'inr_value' => 'required',
                'rate' => 'required',
                'symbol' => 'required',
            ]);

            $data = $request->all();
            $data["name"] = $data["name"];
            $data["short_name"] = $data["short_name"];
            $data["inr_value"] = $data["inr_value"];
            $data["rate"] = $data["rate"];
            $data["symbol"] = $data["symbol"];
            $data["updated_at"] = date('Y-m-d H:i:s');

            $currency->update($data);
            return ["success" => true, "message" => "Currency updated successfully"];
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $id = decrypt($id);
            $currency = Currency::find($id);
            $currency->delete();
            return ["success" => true, "message" => "Currency deleted successfully"];
        }
        abort(404);
    }

}
