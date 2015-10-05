<?php

namespace App\Http\Controllers;

use App\RecipientAnnualQuota;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnnualQuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return RecipientAnnualQuota::where('parent_id',Auth::user()->recipient_id)->with('recipient','vaccine')->get();
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
        if(count(RecipientAnnualQuota::where('recipient_id',$request->input("recipient_id"))->where('item_id',$request->input("item_id"))->get()) != 0){
          $item = RecipientAnnualQuota::where('recipient_id',$request->input("recipient_id"))->where('item_id',$request->input("item_id"))->first();
        }else{
            $item = new RecipientAnnualQuota;
        }
        $item->recipient_id   = $request->input("recipient_id");
        $item->item_id    = $request->input("item_id");
        $item->expected_annual_need  = $request->input("expected_annual_need");
        $item->year  = $request->input("year");
        $item->parent_id  = Auth::user()->recipient_id;
        $item->save();
        return $item->load('recipient','vaccine');
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
        $item->recipient_id   = $request->input("recipient_id");
        $item->item_id    = $request->input("item_id");
        $item->expected_annual_need  = $request->input("expected_annual_need");
        $item->year  = $request->input("year");
        $item->parent_id  = Auth::user()->recipient_id;
        $item->save();
        return $item->load('recipient','vaccine')->get();
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
