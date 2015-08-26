<?php

namespace App\Http\Controllers;

use App\PackagingInformation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PackagingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return PackagingInformation::all();
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
        $item = new PackagingInformation;
        $item->vaccine_id     = $request->input("vaccine_id");
        $item->GTIN     = $request->input("GTIN");
        $item->dose_per_vial     = $request->input("dose_per_vial");
        $item->vials_per_box     = $request->input("vials_per_box");
        $item->cm_per_dose     = $request->input("cm_per_dose");
        $item->manufacture_id     = $request->input("manufacture_id");
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
        $item = PackagingInformation::find($id);
        $item->vaccine_id     = $request->input("vaccine_id");
        $item->GTIN     = $request->input("GTIN");
        $item->dose_per_vial     = $request->input("dose_per_vial");
        $item->vials_per_box     = $request->input("vials_per_box");
        $item->cm_per_dose     = $request->input("cm_per_dose");
        $item->manufacture_id     = $request->input("manufacture_id");
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
        $item = PackagingInformation::find($id);
        $item->delete();
    }

}
