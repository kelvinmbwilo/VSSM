<?php

namespace App\Http\Controllers;

use App\Vaccine;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Vaccine::all();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getDiluents()
    {
        return Vaccine::where('type','diluent')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $item = new Vaccine;
        $item->status               = "active";
        $item->name                 = $request->input("name");
        $item->code                 = $request->input("code");
        $item->days_before_expiry   = $request->input("days_before_expiry");
        $item->type                 = $request->input("type");
        $item->require_diluent      = ($request->has('require_diluent'))?$request->input("require_diluent"):"no";
        $item->storage_type         = $request->input("storage_type");
        $item->diluent_id           = ($request->has('diluent_id'))?$request->input("diluent_id"):0;

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

        $item = Vaccine::find($id);
        $item->status               = $request->input('status');
        $item->name                 = $request->input("name");
        $item->code                 = $request->input("code");
        $item->days_before_expiry   = $request->input("days_before_expiry");
        $item->type                 = $request->input("type");
        $item->require_diluent      = ($request->has('require_diluent'))?$request->input("require_diluent"):"no";
        $item->storage_type         = $request->input("storage_type");
        $item->diluent_id           = ($request->has('diluent_id'))?$request->input("diluent_id"):0;

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
        $item = Vaccine::find($id);
        $item->delete();
    }

}
