<?php

namespace App\Http\Controllers;

use App\Log;
use App\Manufacture;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ManufactureModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Manufacture::where('status',"!=",'deleted')->get();
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
        $item = new Manufacture;
        $item->status           = "active";
        $item->name             = $request->input("name");
        $item->code             = $request->input("code");
        $item->country          = ($request->has('country'))?$request->input("country"):"";
        $item->physical_address = ($request->has('physical_address'))?$request->input("physical_address"):"";
        $item->product_type     = $request->input("product_type");
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Add Manufacture named ".$item->name
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

        $item = Manufacture::find($id);
        $item->status           = $request->input('status');;
        $item->name             = $request->input("name");
        $item->code             = $request->input("code");
        $item->country          = $request->input("country");
        $item->physical_address = $request->input("physical_address");
        $item->product_type     = $request->input("product_type");
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Update Manufacture named ".$item->name
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
        $item = Manufacture::find($id);
        $item->status = 'deleted';
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Delete Manufacture named ".$item->name
        ));
    }

}
