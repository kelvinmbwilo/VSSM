<?php

namespace App\Http\Controllers;

use App\RecipientAnnualQuota;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AnnualQuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return RecipientAnnualQuota::all();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $item = new RecipientAnnualQuota;
        $item->recipient_id   = "recipient_id";
        $item->item_id    = $request->input("item_id");
        $item->expected_annual_need  = $request->input("expected_annual_need");
        $item->save();
        return $item;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id)
    {

        $item = RecipientAnnualQuota::find($id);
        $item->recipient_id   = "recipient_id";
        $item->item_id    = $request->input("item_id");
        $item->expected_annual_need  = $request->input("expected_annual_need");
        $item->save();
        return $item;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = RecipientAnnualQuota::find($id);
        $item->delete();
    }

}
