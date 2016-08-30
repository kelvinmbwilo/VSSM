<?php

namespace App\Http\Controllers;

use App\Log;
use App\TransportMode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransportModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return TransportMode::where('status','!=','deleted')->get();
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
        $item = new TransportMode;
        $item->status   = "active";
        $item->name     = $request->input("name");
        $item->code     = $request->input("code");

        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Add Transport Mode named ".$item->name
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

        $item = TransportMode::find($id);
        $item->name = $request->input('name');
        $item->code = $request->input('code');
        $item->status = $request->input('status');
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Update Transport Mode named ".$item->name
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
        $item = TransportMode::find($id);
        $item->status = "deleted";
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Delete Transport Mode named ".$item->name
        ));
    }

}
