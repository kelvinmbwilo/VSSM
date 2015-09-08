<?php

namespace App\Http\Controllers;

use App\Arrival;
use App\ArrivalItem;
use App\PackagingInformation;
use App\Recipient;
use App\Stock;
use App\StoreStock;
use App\Vaccine;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function receive(Request $request)
    {
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $arrival = new Arrival;
        $arrival->recipient_source_id       = $recipient->parent_id;
        $arrival->recipient_destination_id  = $recipient->id;
        $arrival->source_id                 = $request->input('source_id');
        $arrival->arrival_report_number     = $request->input('arrival_report_no');
        $arrival->package_volume            = $request->input('packed_volume');
        $arrival->total_weight              = $request->input('total_weight');
        $arrival->arrival_date              = $request->input('arrival_date');
        $arrival->freight_cost              = $request->input('freight_cost');
        $arrival->receiving_user            = Auth::user()->id;
        $arrival->notes                     = $request->input('notes');
        $arrival->main_currency             = $request->input('main_currency');
        $arrival->used_currency             = $request->input('currency_of_bill');
        $arrival->exchange_rate             = $request->input('exchange_rate');
        $arrival->save();

        foreach($request->input('items') as $item){
            $ArrivalItem = new ArrivalItem;
            $ArrivalItem->recipient_source_id       = $recipient->parent_id;
            $ArrivalItem->recipient_destination_id  = $recipient->id;
            $ArrivalItem->vaccine_id                = $item['item_id'];
            $ArrivalItem->packaging_id              = $item['packaging_id'];
            $ArrivalItem->activity_id               = $item['activity'];
            $ArrivalItem->store_id                  = $item['store_id'];
            $ArrivalItem->arrival_id                = $arrival->id;
            $ArrivalItem->package_number            = $request->input('arrival_report_no');
            $ArrivalItem->lot_number                = $item['lot_number'];
            $ArrivalItem->number_received           = $item['doses'];
            $ArrivalItem->receiving_user            = Auth::user()->id;
            $ArrivalItem->unit_price                = $item['u_price'];
            $ArrivalItem->total_price               = $item['t_price'];
            $ArrivalItem->volume                    = $item['total_volume'];
            $ArrivalItem->manufacture_id            = $item['manufacture_id'];
            $ArrivalItem->prduction_date            = $item['prod_date'];
            $ArrivalItem->expiry_date               = $item['expired_date'];
            $ArrivalItem->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->vaccine_id = $item['item_id'];
                $stock->recipient_id = $recipient->id;
                $stock->amount = $item['doses']+$stock->amount;
                $stock->lot_number = $item['packaging_id'];
                $stock->packaging_id = $item['packaging_id'];
                $stock->expiry_date = $item['expired_date'];
                $stock->save();
            }else{
                $stock = new Stock;
                $stock->vaccine_id = $item['item_id'];
                $stock->recipient_id = $recipient->id;
                $stock->amount = $item['doses'];
                $stock->lot_number = $item['packaging_id'];
                $stock->packaging_id = $item['packaging_id'];
                $stock->expiry_date = $item['expired_date'];
                $stock->save();
            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->vaccine_id = $item['item_id'];
                $storeStock->recipient_id = $recipient->id;
                $storeStock->amount = $item['doses']+$storeStock->amount;
                $storeStock->lot_number = $item['packaging_id'];
                $storeStock->packaging_id = $item['packaging_id'];
                $storeStock->expiry_date = $item['expired_date'];
                $storeStock->save();
            }else{
                $storeStock = new Stock;
                $storeStock->vaccine_id = $item['item_id'];
                $storeStock->recipient_id = $recipient->id;
                $storeStock->amount = $item['doses'];
                $storeStock->lot_number = $item['packaging_id'];
                $storeStock->packaging_id = $item['packaging_id'];
                $storeStock->expiry_date = $item['expired_date'];
                $storeStock->save();
            }
        }
    }

}
