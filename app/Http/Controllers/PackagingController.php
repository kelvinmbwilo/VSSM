<?php

namespace App\Http\Controllers;

use App\PackagingInformation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PackagingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return PackagingInformation::where('status','!=','deleted')->with('vaccine','manufacture')->get();
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
        if(count(PackagingInformation::where('GTIN',$request->input("GTIN"))->get()) != 0){

        }else{
            $item = new PackagingInformation;
            $item->vaccine_id         = $request->input("vaccine_id");
            $item->GTIN               = $request->input("GTIN");
            $item->dose_per_vial      = $request->input("dose_per_vial");
            $item->vials_per_box      = $request->input("vials_per_box");
            $item->cm_per_dose        = $request->input("cm_per_dose");
            $item->manufacture_id     = $request->input("manufacture_id");
            $item->commercial_name    = $request->has("commercial_name")?$request->input("commercial_name"):"";
            $item->status               = "active";
            $item->save();
            return $item->load('vaccine','manufacture');
        }

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
        $item->commercial_name    = $request->has("commercial_name")?$request->input("commercial_name"):"";
        $item->status               = $request->input('status');
        $item->save();
        return $item->load('vaccine','manufacture');
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
        $item->status = "deleted";
        $item->save();
    }

}
