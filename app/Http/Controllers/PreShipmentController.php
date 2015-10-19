<?php

namespace App\Http\Controllers;

use App\PreShipment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PreShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return PreShipment::where('status','!=','deleted')->with('vaccine','packaging','source')->get();
    }

    /**
     * get the last package number for item movements
     *
     * @return Response
     */
    public function getNextNumber()
    {
        if(count(PreShipment::all()) != 0){
            return PreShipment::orderBy('order_no','DESC')->first()->order_no +1;
        }else{
            return 1;
        }
    }

    /**
     * Display a listing of the pending_shipments.
     *
     * @return Response
     */
    public function pending_shipments()
    {
        return PreShipment::where('status','pending')->with('vaccine','packaging','source')->get();
    }


    /**
     * Show the pre shipment with specific status.
     *
     * @param $status
     * @return Response
     */
    public function getWithStatus($status)
    {
        return PreShipment::where('status',$status)->with('vaccine','packaging','source')->get();
    }

    /**
     * Show the preshiment with specific id
     *
     * @param $id
     * @return Response
     */
    public function getWithPackId($id)
    {
        return PreShipment::where('package_id',$id)->with('vaccine','packaging','source')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $item = new PreShipment();
        $nextNumber = $this->getNextNumber();

        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $item->reference                = date('Y')."5".$str+"".$nextNumber;
        $item->status                   = "pending";
        $item->source_id                = $request->has("source_id")?$request->input("source_id"):"";
        $item->package_id               = $request->has("package_id")?$request->input("package_id"):"";
        $item->expected_time_of_arrival = $request->has("expected_time_of_arrival")?$request->input("expected_time_of_arrival"):"";
        $item->total_weight             = $request->has("total_weight")?$request->input("total_weight"):'';
        $item->packed_volume            = $request->has("packed_volume")?$request->input("packed_volume"):'';
        $item->item_id                  = $request->has("item_id")?$request->input("item_id"):"";
        $item->packaging_id             = $request->has("packaging_id")?$request->input("packaging_id"):"";
        $item->number_of_doses          = $request->has("number_of_doses")?$request->input("number_of_doses"):"";
        $item->lot_number               = $request->has("lot_number")?$request->input("lot_number"):"";
        $item->manufacture_date         = $request->has("manufacture_date")?$request->input("manufacture_date"):'';
        $item->expired_date             = $request->has("expired_date")?$request->input("expired_date"):"";
        $item->year                     = date('Y');
        $item->order_no                  = $nextNumber;

        $item->save();
        return $item->load('vaccine','packaging','source');
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

        $item = PreShipment::find($id);
        $item->source_id                = $request->has("source_id")?$request->input("source_id"):"";
        $item->package_id               = $request->has("package_id")?$request->input("package_id"):"";
        $item->expected_time_of_arrival = $request->has("expected_time_of_arrival")?$request->input("expected_time_of_arrival"):"";
        $item->total_weight             = $request->has("total_weight")?$request->input("total_weight"):'';
        $item->packed_volume            = $request->has("packed_volume")?$request->input("packed_volume"):'';
        $item->item_id                  = $request->has("item_id")?$request->input("item_id"):"";
        $item->packaging_id             = $request->has("packaging_id")?$request->input("packaging_id"):"";
        $item->number_of_doses          = $request->has("number_of_doses")?$request->input("number_of_doses"):"";
        $item->lot_number               = $request->has("lot_number")?$request->input("lot_number"):"";
        $item->manufacture_date         = $request->has("manufacture_date")?$request->input("manufacture_date"):'';
        $item->expired_date             = $request->has("expired_date")?$request->input("expired_date"):"";
        $item->save();
        return $item->load('vaccine','packaging','source');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = PreShipment::find($id);
        $item->status = 'deleted';
        $item->save();
    }

}
