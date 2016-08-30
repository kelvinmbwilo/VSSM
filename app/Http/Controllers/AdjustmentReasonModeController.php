<?php

namespace App\Http\Controllers;

use App\AdjustmentReason;
use App\Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdjustmentReasonModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return AdjustmentReason::where('status',"!=",'deleted')->get();
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
        $item = new AdjustmentReason;
        $item->status               = "active";
        $item->name                 = $request->input("name");
        $item->code                 = $request->input("code");
        $item->decrease_amount      = $request->has("decrease_amount")?$request->input("decrease_amount"):'no';
        $item->increase_amount      = $request->has("increase_amount")?$request->input("increase_amount"):'no';
        $item->consider_wastage     = $request->has("consider_wastage")?$request->input("consider_wastage"):'no';
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Add Adjustment Reason named ".$item->name
        ));
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

        $item = AdjustmentReason::find($id);
        $item->name = $request->input('name');
        $item->code = $request->input('code');
        $item->status = $request->input('status');
        $item->decrease_amount      = $request->has("decrease_amount")?$request->input("decrease_amount"):'no';
        $item->increase_amount      = $request->has("increase_amount")?$request->input("increase_amount"):'no';
        $item->consider_wastage     = $request->has("consider_wastage")?$request->input("consider_wastage"):'no';
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Update Adjustment Reason named ".$item->name
        ));
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
        $item = AdjustmentReason::find($id);
        $item->status = 'deleted';
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Delete Adjustment Reason named ".$item->name
        ));
    }

}
