<?php

namespace App\Http\Controllers;

use App\Adjustment;
use App\Arrival;
use App\ArrivalItem;
use App\PackagingInformation;
use App\PreShipment;
use App\Recipient;
use App\RecipientPackage;
use App\RecipientPackageItem;
use App\Stock;
use App\Store;
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
        return Vaccine::where('status','!=','deleted')->get();
    }

    /**
     * Display a stockItems for user.
     *
     * @return Response
     */
    public function stock_items()
    {
        return Stock::where('recipient_id',Auth::user()->recipient_id)->get();
    }
 /**
     * Display a arrivals for user.
     *
     * @return Response
     */
    public function arrivals()
    {
        return Arrival::where('recipient_destination_id',Auth::user()->recipient_id)->get()->load('fromSource','source','arrivalItems');
    }

    public function arrivals1()
    {
        return ArrivalItem::where('recipient_destination_id',Auth::user()->recipient_id)->get()->load('vaccine','packaging','arrival');
    }
 /**
     * Display a packages for user.
     *
     * @return Response
     */
    public function packages()
    {
        return RecipientPackageItem::where('recipient_id',Auth::user()->recipient_id)->get()->load('recipient','vaccine','packaging','receiver','package');
    }

    /**
     * Display a expectedPackages  items for user.
     *
     * @return Response
     */
    public function expectedPackagesItems()
    {

        return RecipientPackageItem::where('receiver_id',Auth::user()->recipient_id)->get()->load('recipient','vaccine','packaging','package');
    }
    /**
     * Display a expectedPackages for user.
     *
     * @return Response
     */
    public function expectedPackages()
    {
        return RecipientPackage::where('recipient_id',Auth::user()->recipient_id)->get()->load('items','destination');
//        return RecipientPackageItem::where('receiver_id',Auth::user()->recipient_id)->get()->load('recipient','vaccine','packaging','package');
    }

    /**
     * Display a expectedPackages for user.
     *
     * @return Response
     */
    public function pendingExpectedPackages()
    {
        return RecipientPackage::where('recipient_id',Auth::user()->recipient_id)->where('receiving_status','pending')->get()->load('items','destination');
//        return RecipientPackageItem::where('receiver_id',Auth::user()->recipient_id)->get()->load('recipient','vaccine','packaging','package');
    }
    /**
     * Display a expectedPackages for user.
     *
     * @return Response
     */
    public function dispatchedPackages()
    {
        return RecipientPackage::where('source_id',Auth::user()->recipient_id)->get()->load('items','destination');
//        return RecipientPackageItem::where('receiver_id',Auth::user()->recipient_id)->get()->load('recipient','vaccine','packaging','package');
    }

    /**
     * Display a arrivals for user.
     *
     * @return Response
     */
//    public function arrivals()
//    {
//        return Arrival::where('recipient_id',Auth::user()->recipient_id)->get()->load('items');
////        return RecipientPackageItem::where('receiver_id',Auth::user()->recipient_id)->get()->load('recipient','vaccine','packaging','package');
//    }



    /**
     * get the last package number
     *
     * @return Response
     */
    public function getNextPackageNumber()
    {
        if(count(RecipientPackage::all()) != 0){
            return RecipientPackage::orderBy('order_number','DESC')->first()->order_number +1;
        }else{
            return 1;

        }
    }

 /**
     * get the last package number for adjustments
     *
     * @return Response
     */
    public function getNextAdjustmentNumber()
    {
        if(count(Adjustment::all()) != 0){
            return Adjustment::orderBy('order_no','DESC')->first()->order_no +1;
        }else{
            return 1;
        }
    }
/**
     * get the last package number for adjustments
     *
     * @return Response
     */
    public function getNextArrivalNumber()
    {
        if(count(Arrival::all()) != 0){
            return Arrival::orderBy('order_no','DESC')->first()->order_no +1;
        }else{
            return 1;
        }
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
        $item->status = "deleted";
        $item->save();
    }

    /**
     * Function to receive item in national level & lower levels
     *
     * @param  Request  $request
     * @return Response
     */
    public function receive(Request $request)
    {
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $nextNumber = $this->getNextArrivalNumber();
        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $arrival = new Arrival;
        $arrival->reference                 = date('Y')."1".$str+"".$nextNumber;
        $arrival->order_no                  = $nextNumber;
        $arrival->recipient_source_id       = $recipient->parent_id;
        $arrival->recipient_destination_id  = $recipient->id;
        $arrival->source_id                 = $request->input('source_id');
        $arrival->arrival_report_number     = $request->input('arrival_report_no');
        $arrival->package_volume            = ($request->has('packed_volume'))?$request->input('packed_volume'):'';
        $arrival->total_weight              = ($request->has('total_weight'))?$request->input('total_weight'):'';
        $arrival->arrival_date              = $request->input('arrival_date');
        $arrival->freight_cost              = ($request->has('freight_cost'))?$request->input('freight_cost'):'';
        $arrival->receiving_user            = Auth::user()->id;
        $arrival->notes                     = ($request->has('notes'))?$request->input('notes'):'';
        $arrival->main_currency             = ($request->has('main_currency'))?$request->input('main_currency'):'';
        $arrival->used_currency             = ($request->has('currency_of_bill'))?$request->input('currency_of_bill'):'';
        $arrival->exchange_rate             = ($request->has('exchange_rate'))?$request->input('exchange_rate'):'';
        $arrival->save();

        foreach($request->input('items') as $item){
            $ArrivalItem = new ArrivalItem;
            $ArrivalItem->recipient_source_id       = $recipient->parent_id;
            $ArrivalItem->recipient_destination_id  = $recipient->id;
            $ArrivalItem->vaccine_id                = $item['item_id'];
            $ArrivalItem->source_id                 = $request->input('source_id');
            $ArrivalItem->packaging_id              = $item['packaging_id'];
            $ArrivalItem->activity_id               = $item['activity'];
            $ArrivalItem->store_id                  = $item['store_id'];
            $ArrivalItem->vvm_status                = (isset($item['vvm_stage']))?$item['u_price']:'';
            $ArrivalItem->arrival_id                = $arrival->id;
            $ArrivalItem->package_number            = $request->input('arrival_report_no');
            $ArrivalItem->lot_number                = $item['lot_number'];
            $ArrivalItem->number_received           = $item['doses'];
            $ArrivalItem->receiving_user            = Auth::user()->id;
            $ArrivalItem->unit_price                = (isset($item['u_price']))?$item['u_price']:'';
            $ArrivalItem->total_price               = (isset($item['t_price']))?$item['t_price']:'';
            $ArrivalItem->volume                    = (isset($item['total_volume']))?$item['total_volume']:'';
            $ArrivalItem->manufacture_id            = (isset($item['manufacture_id']))?$item['manufacture_id']:0;
            $ArrivalItem->prduction_date            = (isset($item['prod_date']))?$item['prod_date']:'';
            $ArrivalItem->expiry_date               = (isset($item['expired_date']))?$item['expired_date']:'';
            $ArrivalItem->arrival_date              = $request->input('arrival_date');

            $ArrivalItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume + $item['total_volume'] ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->vaccine_id      = $item['item_id'];
                $stock->recipient_id    = $recipient->id;
                $stock->source_id       = $request->input('source_id');
                $stock->amount          = $item['doses']+$stock->amount;
                $stock->lot_number      = $item['lot_number'];
                $stock->packaging_id    = $item['packaging_id'];
                $stock->expiry_date     = $item['expired_date'];
                $stock->store_id  = $item['store_id'];
                $stock->unit_price      = $item['u_price'];
                $stock->activity_id     = $item['activity'];
                $stock->save();
            }else{
                $stock = new Stock;
                $stock->vaccine_id      = $item['item_id'];
                $stock->recipient_id    = $recipient->id;
                $stock->amount          = $item['doses'];
                $stock->lot_number      = $item['lot_number'];
                $stock->packaging_id    = $item['packaging_id'];
                $stock->source_id       = $request->input('source_id');
                $stock->expiry_date     = $item['expired_date'];
                $stock->store_id  = $item['store_id'];
                $stock->unit_price      = $item['u_price'];
                $stock->activity_id     = $item['activity'];
                $stock->save();
            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->vaccine_id     = $item['item_id'];
                $storeStock->store_id       = $item['store_id'];
                $storeStock->amount         = $item['doses']+$storeStock->amount;
                $storeStock->lot_number     = $item['lot_number'];
                $storeStock->packaging_id   = $item['packaging_id'];
                $storeStock->expiry_date    = $item['expired_date'];
                $storeStock->unit_price     = $item['u_price'];
                $storeStock->source_id       = $request->input('source_id');
                $storeStock->activity_id    = $item['activity'];
                $storeStock->save();
            }else{
                $storeStock = new StoreStock;
                $storeStock->vaccine_id     = $item['item_id'];
                $storeStock->store_id       = $item['store_id'];
                $storeStock->amount         = $item['doses'];
                $storeStock->lot_number     = $item['lot_number'];
                $storeStock->packaging_id   = $item['packaging_id'];
                $storeStock->expiry_date    = $item['expired_date'];
                $storeStock->unit_price     = $item['u_price'];
                $storeStock->source_id       = $request->input('source_id');
                $storeStock->activity_id    = $item['activity'];
                $storeStock->save();
            }
        }

        return $arrival->reference;
    }

    /**
     * Function to open stock for items in national level & lower levels
     *
     * @param  Request  $request
     * @return Response
     */
    public function open(Request $request)
    {
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $arrival = new Arrival;
        $nextNumber = $this->getNextArrivalNumber();
        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $arrival->reference                 = date('Y')."1".$str+"".$nextNumber;
        $arrival->order_no                  = $nextNumber;
        $arrival->recipient_source_id       = $recipient->parent_id;
        $arrival->recipient_destination_id  = $recipient->id;
//        $arrival->source_id                 = $request->input('source_id');
//        $arrival->arrival_report_number     = $request->input('arrival_report_no');
//        $arrival->package_volume            = $request->input('packed_volume');
//        $arrival->total_weight              = $request->input('total_weight');
        $arrival->arrival_date              = ($request->has('arrival_date'))?$request->input('arrival_date'):date('Y-m-d');
//        $arrival->freight_cost              = $request->input('freight_cost');
//        $arrival->receiving_user            = Auth::user()->id;
//        $arrival->notes                     = $request->input('notes');
//        $arrival->main_currency             = $request->input('main_currency');
//        $arrival->used_currency             = $request->input('currency_of_bill');
//        $arrival->exchange_rate             = $request->input('exchange_rate');
        $arrival->save();

        foreach($request->input('items') as $item){
            $ArrivalItem = new ArrivalItem;
            $ArrivalItem->recipient_source_id       = $recipient->parent_id;
            $ArrivalItem->recipient_destination_id  = $recipient->id;
            $ArrivalItem->vaccine_id                = $item['item_id'];
            $ArrivalItem->packaging_id              = $item['packaging_id'];
            $ArrivalItem->activity_id               = $item['activity'];
            $ArrivalItem->store_id                  = $item['store_id'];
            $ArrivalItem->vvm_status                = (isset($item['vvm_stage']))?$item['u_price']:'';
            $ArrivalItem->arrival_id                = $arrival->id;
            $ArrivalItem->arrival_date                  = ($request->has('arrival_date'))?$request->input('arrival_date'):date('Y-m-d');
//            $ArrivalItem->package_number            = $request->input('arrival_report_no');
            $ArrivalItem->lot_number                = $item['lot_number'];
            $ArrivalItem->number_received           = $item['doses'];
            $ArrivalItem->receiving_user            = Auth::user()->id;
            $ArrivalItem->unit_price                = (isset($item['u_price']))?$item['u_price']:'';
            $ArrivalItem->total_price               = (isset($item['t_price']))?$item['t_price']:'';
            $ArrivalItem->volume                    = (isset($item['total_volume']))?$item['total_volume']:'';
            $ArrivalItem->manufacture_id            = (isset($item['manufacture_id']))?$item['manufacture_id']:0;
            $ArrivalItem->prduction_date            = (isset($item['prod_date']))?$item['prod_date']:'';
            $ArrivalItem->expiry_date               = (isset($item['expired_date']))?$item['expired_date']:'';
            $ArrivalItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume + $item['total_volume'] ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->vaccine_id      = $item['item_id'];
                $stock->recipient_id    = $recipient->id;
                $stock->amount          = $item['doses']+$stock->amount;
                $stock->lot_number      = $item['lot_number'];
                $stock->packaging_id    = $item['packaging_id'];
                $stock->expiry_date     = $item['expired_date'];
                $stock->store_id        = $item['store_id'];
                $stock->unit_price      = $item['u_price'];
                $stock->activity_id     = $item['activity'];
                $stock->save();
            }else{
                $stock = new Stock;
                $stock->vaccine_id      = $item['item_id'];
                $stock->recipient_id    = $recipient->id;
                $stock->amount          = $item['doses'];
                $stock->lot_number      = $item['lot_number'];
                $stock->packaging_id    = $item['packaging_id'];
                $stock->expiry_date     = $item['expired_date'];
                $stock->store_id        = $item['store_id'];
                $stock->unit_price      = $item['u_price'];
                $stock->activity_id     = $item['activity'];
                $stock->save();
            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->vaccine_id     = $item['item_id'];
                $storeStock->store_id       = $item['store_id'];
                $storeStock->amount         = $item['doses']+$storeStock->amount;
                $storeStock->lot_number     = $item['lot_number'];
                $storeStock->packaging_id   = $item['packaging_id'];
                $storeStock->expiry_date    = $item['expired_date'];
                $storeStock->unit_price      = $item['u_price'];
                $storeStock->activity_id     = $item['activity'];
                $storeStock->save();
            }else{
                $storeStock = new StoreStock;
                $storeStock->vaccine_id     = $item['item_id'];
                $storeStock->store_id       = $item['store_id'];
                $storeStock->amount         = $item['doses'];
                $storeStock->lot_number     = $item['lot_number'];
                $storeStock->packaging_id   = $item['packaging_id'];
                $storeStock->expiry_date    = $item['expired_date'];
                $storeStock->unit_price      = $item['u_price'];
                $storeStock->activity_id     = $item['activity'];
                $storeStock->save();
            }
        }
        return $arrival->reference;
    }

    /**
     * Function to receive item in national level & lower levels
     *
     * @param  Request  $request
     * @return Response
     */
    public function pre_receive(Request $request)
    {
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $arrival = new Arrival;
        $nextNumber = $this->getNextArrivalNumber();
        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $arrival->reference                 = date('Y')."1".$str+"".$nextNumber;
        $arrival->recipient_source_id       = $recipient->parent_id;
        $arrival->recipient_destination_id  = $recipient->id;
        $arrival->source_id                 = $request->input('source_id');
        $arrival->arrival_report_number     = $request->input('arrival_report_no');
        $arrival->package_volume            = ($request->has('packed_volume'))?$request->input('packed_volume'):'';
        $arrival->total_weight              = ($request->has('total_weight'))?$request->input('total_weight'):'';
        $arrival->arrival_date              = $request->input('arrival_date');
        $arrival->freight_cost              = ($request->has('freight_cost'))?$request->input('freight_cost'):'';
        $arrival->receiving_user            = Auth::user()->id;
        $arrival->order_no                  = $nextNumber;
        $arrival->notes                     = ($request->has('notes'))?$request->input('notes'):'';
        $arrival->main_currency             = ($request->has('main_currency'))?$request->input('main_currency'):'';
        $arrival->used_currency             = ($request->has('currency_of_bill'))?$request->input('currency_of_bill'):'';
        $arrival->exchange_rate             = ($request->has('exchange_rate'))?$request->input('exchange_rate'):'';
        $arrival->save();

        foreach($request->input('items') as $item){
            $amount = 0;
            if(isset($item['number_received'])){
                if(isset($item['damaged_amount'])){
                    $amount = $item['number_received'] - $item['damaged_amount'];
                    $amount = $amount <= 0?0:$amount;
                }else{
                    $amount = $item['number_received'];
                }
            }else{
                if(isset($item['damaged_amount'])){
                    $amount = $item['doses'] - $item['damaged_amount'];
                    $amount = $amount <= 0?0:$amount;
                }else{
                    $amount = $item['doses'];
                }
            }
            $ArrivalItem = new ArrivalItem;
            $ArrivalItem->recipient_source_id       = $recipient->parent_id;
            $ArrivalItem->recipient_destination_id  = $recipient->id;
            $ArrivalItem->vaccine_id                = $item['item_id'];
            $ArrivalItem->packaging_id              = $item['packaging_id'];
            $ArrivalItem->activity_id               = $item['activity'];
            $ArrivalItem->store_id                  = $item['store_id'];
            $ArrivalItem->vvm_status                = (isset($item['vvm_stage']))?$item['u_price']:'';
            $ArrivalItem->arrival_id                = $arrival->id;
            $ArrivalItem->package_number            = $request->input('arrival_report_no');
            $ArrivalItem->lot_number                = $item['lot_number'];
            $ArrivalItem->number_expected           = $item['doses'];
            $ArrivalItem->number_received           = $amount;
            $ArrivalItem->physical_damage           = $item['physical_damage'];
            $ArrivalItem->number_as_expected        = $item['number_as_expected'];
            $ArrivalItem->damaged_amount            = (isset($item['damaged_amount']))?$item['damaged_amount']:0;
            $ArrivalItem->receiving_user            = Auth::user()->id;
            $ArrivalItem->unit_price                = (isset($item['u_price']))?$item['u_price']:'';
            $ArrivalItem->total_price               = (isset($item['t_price']))?$item['t_price']:'';
            $ArrivalItem->volume                    = (isset($item['total_volume']))?$item['total_volume']:'';
            $ArrivalItem->manufacture_id            = (isset($item['manufacture_id']))?$item['manufacture_id']:0;
            $ArrivalItem->prduction_date            = (isset($item['prod_date']))?$item['prod_date']:'';
            $ArrivalItem->expiry_date               = (isset($item['expired_date']))?$item['expired_date']:'';
            $ArrivalItem->arrival_date              = $request->input('arrival_date');
            $ArrivalItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume + ($amount*$item['cm_per_dose']*0.001) ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->vaccine_id      = $item['item_id'];
                $stock->recipient_id    = $recipient->id;
                $stock->source_id       = $request->input('source_id');
                $stock->amount          = $amount+$stock->amount;
                $stock->lot_number      = $item['lot_number'];
                $stock->packaging_id    = $item['packaging_id'];
                $stock->expiry_date     = $item['expired_date'];
                $stock->store_id  = $item['store_id'];
                $stock->unit_price      = $item['u_price'];
                $stock->activity_id     = $item['activity'];
                $stock->save();
            }else{
                $stock = new Stock;
                $stock->vaccine_id      = $item['item_id'];
                $stock->recipient_id    = $recipient->id;
                $stock->amount          = $amount;
                $stock->lot_number      = $item['lot_number'];
                $stock->packaging_id    = $item['packaging_id'];
                $stock->source_id       = $request->input('source_id');
                $stock->expiry_date     = $item['expired_date'];
                $stock->store_id  = $item['store_id'];
                $stock->unit_price      = $item['u_price'];
                $stock->activity_id     = $item['activity'];
                $stock->save();
            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->vaccine_id     = $item['item_id'];
                $storeStock->store_id       = $item['store_id'];
                $storeStock->amount         = $amount+$storeStock->amount;
                $storeStock->lot_number     = $item['lot_number'];
                $storeStock->packaging_id   = $item['packaging_id'];
                $storeStock->expiry_date    = $item['expired_date'];
                $storeStock->unit_price     = $item['u_price'];
                $storeStock->source_id       = $request->input('source_id');
                $storeStock->activity_id    = $item['activity'];
                $storeStock->save();
            }else{
                $storeStock = new StoreStock;
                $storeStock->vaccine_id     = $item['item_id'];
                $storeStock->store_id       = $item['store_id'];
                $storeStock->amount         = $amount;
                $storeStock->lot_number     = $item['lot_number'];
                $storeStock->packaging_id   = $item['packaging_id'];
                $storeStock->expiry_date    = $item['expired_date'];
                $storeStock->unit_price     = $item['u_price'];
                $storeStock->source_id       = $request->input('source_id');
                $storeStock->activity_id    = $item['activity'];
                $storeStock->save();
            }

            if($request->input('from_type') == 'pre_advice'){
                foreach(PreShipment::where('package_id',$request->input('arrival_report_no'))->get() as $pre_shipment){
                    $pre_shipment->status = 'received';
                    $pre_shipment->save();
                }
            }
        }
        if($request->input('from_type') == 'other'){
        foreach(RecipientPackage::where('voucher_number',$request->input('arrival_report_no'))->get() as $pre_shipment){
            $pre_shipment->receiving_user = Auth::user()->id;
            $pre_shipment->receiving_status = 'received';
            $pre_shipment->save();
        }
        }

        return $arrival->reference;
    }

    /**
     * Function to dispatch Items
     *
     * @param  Request  $request
     * @return Response
     */
    public function dispatchVaccine(Request $request){
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $dispatch = new RecipientPackage;
        $nextNumber = $this->getNextPackageNumber();

        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $dispatch->voucher_number            = date('Y')."2".$str+"".$nextNumber;
        $dispatch->source_id                 = $recipient->id;
        $dispatch->recipient_id              = $request->input('recipients');
        $dispatch->date_sent                 = $request->input('dispatch_date');
        $dispatch->transport_mode_id         = $request->input('transport_mode');
        $dispatch->sending_user              = Auth::user()->id;
        $dispatch->receiving_status          = 'pending';
        $dispatch->year                      = date('Y');
        $dispatch->order_number              = $nextNumber;
        $dispatch->save();

        foreach($request->input('items') as $item){
            $dispatchItem = new RecipientPackageItem;
            $dispatchItem->vaccine_id                = $item['item_id'];
            $dispatchItem->recipient_id              = $recipient->id;
            $dispatchItem->receiver_id               = $request->input('recipients');
            $dispatchItem->source_id                 = $item['source_id'];
            $dispatchItem->packaging_id              = $item['packaging_id'];
            $dispatchItem->activity                  = $item['activity_id'];
            $dispatchItem->store_id                  = $item['store_id'];
            $dispatchItem->package_id                = $dispatch->id;
            $dispatchItem->batch_number              = $item['lot_number'];
            $dispatchItem->amount                    = $item['doses'];
            $dispatchItem->date_sent                 = $request->input('dispatch_date');
            $dispatchItem->unit_price                = (isset($item['u_price']))?$item['u_price']:'';
            $dispatchItem->expiry_date               = (isset($item['expired_date']))?$item['expired_date']:'';
            $dispatchItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume - $item['total_volume'] ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->amount          = $stock->amount - $item['doses'];
                $stock->save();
            }else{

            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->amount         = $storeStock->amount - $item['doses'];
                $storeStock->save();
            }else{

            }
        }
        return $dispatch->voucher_number;
    }

    /**
     * Dealing with item adjustment basic info
     * @param Request $request
     * @return Response
     */

    public function arrival_adjust1(Request $request){
        $arrival = Arrival::find($request->input('arrival_id'));
        $arrival->source_id = ($request->has('source_id'))?$request->input('source_id'):$arrival->source_id;
        $arrival->freight_cost = ($request->has('freight_cost'))?$request->input('freight_cost'):$arrival->freight_cost;
        $arrival->notes = ($request->has('notes'))?$request->input('notes'):$arrival->notes;
        $arrival->save();
    }
    /**
     * Dealing with item adjustment item info
     * @param Request $request
     * @return Response
     */

    public function arrival_adjust(Request $request){
        $arrivalItem = ArrivalItem::find($request->input('id'));
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$arrivalItem->vaccine_id)->where('lot_number',$arrivalItem->lot_number)->first();
        $storeStock = StoreStock::where('store_id',$stock->store_id)->where('vaccine_id',$arrivalItem->vaccine_id)->where('lot_number',$arrivalItem->lot_number)->first();

        $difference = ($request->has('doses'))?$request->input('doses')-$arrivalItem->number_received:0;
        $volume = $difference* PackagingInformation::find($arrivalItem->packaging_id)->cm_per_dose * 0.01;
        $arrivalItem->lot_number = ($request->has('lot_number'))?$request->input('lot_number'):$arrivalItem->lot_number;
        $arrivalItem->expiry_date = ($request->has('expired_date'))?$request->input('expired_date'):$arrivalItem->expiry_date;
        $arrivalItem->total_price = ($request->has('t_price'))?$request->input('t_price'):$arrivalItem->total_price;
        $arrivalItem->number_received = ($request->has('doses'))?$request->input('doses'):$arrivalItem->number_received;
        $arrivalItem->activity_id = ($request->has('activity'))?$request->input('activity'):$arrivalItem->activity_id;
        $arrivalItem->save();
        $u_price = 0;
        if($request->has('t_price')){
            $u_price = $request->input('t_price')/$request->input('doses');
        }
        $arrivalItem->unit_price = ($u_price == 0)?$stock->unit_price:$u_price;
        $arrivalItem->save();

        $stock->amount = $difference + $stock->amount;
        $stock->lot_number = $arrivalItem->lot_number;
        $stock->expiry_date = $arrivalItem->expiry_date;
        $stock->unit_price = ($u_price == 0)?$stock->unit_price:$arrivalItem->$u_price;
        $stock->save();

        $storeStock->amount = $difference + $storeStock->amount;
        $storeStock->lot_number = $arrivalItem->lot_number;
        $storeStock->expiry_date = $arrivalItem->expiry_date;
        $storeStock->unit_price = ($u_price == 0)?$stock->unit_price:$arrivalItem->$u_price;
        $storeStock->save();

        $adjustment = new Adjustment;
        $nextNumber = $this->getNextAdjustmentNumber();

        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $adjustment->reference         = date('Y')."3".$str+"".$nextNumber;
        $adjustment->adjustment_reason = $request->input('adjustment_reason');
        $adjustment->adjustment_type   = 'arrival';
        $adjustment->resource_id       = $arrivalItem->id;
        $adjustment->order_no           = $nextNumber;
        $adjustment->save();

        $store = Store::find($arrivalItem->store_id);
        $store->used_volume = $store->used_volume + $volume ;
        $store->save();
    }

    public function dispatch_adjust(Request $request){
        $package = RecipientPackageItem::find($request->input('item'));
        $difference = $package->amount - $request->input('doses');
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $package->amount = $package->amount + $difference;
        $package->save();
        $adjustment = new Adjustment;
        $nextNumber = $this->getNextAdjustmentNumber();

        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $adjustment->reference         = date('Y')."3".$str+"".$nextNumber;
        $adjustment->adjustment_reason = $request->input('adjustment_reason');
        $adjustment->adjustment_type   = 'dispatch';
        $adjustment->resource_id       = $package->id;
        $adjustment->save();

        $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$package->vaccine_id)->where('lot_number',$package->batch_number)->first();
        $storeStock = StoreStock::where('store_id',$stock->store_id)->where('vaccine_id',$package->vaccine_id)->where('lot_number',$package->batch_number)->first();

        if($stock){
            $stock->amount = $difference + $stock->amount;
            $stock->save();
        }
        if($storeStock){
            $storeStock->amount = $difference + $storeStock->amount;
            $storeStock->save();
        }
    }

    public function stock_adjust(Request $request){
        $stock = Stock::find($request->input('stock_id'));
        $storeStock = StoreStock::where('store_id',$stock->store_id)->where('vaccine_id',$request->input('item_id'))->where('lot_number',$request->input('lot_number'))->first();
        $stock->amount = $request->has('doses')?$request->input('doses'):$stock->amount - $request->input('change');
        $storeStock->amount = $request->has('doses')?$request->input('doses'):$storeStock->amount - $request->input('change');
        $stock->save();
        $storeStock->save();

        $adjustment = new Adjustment;
        $nextNumber = $this->getNextAdjustmentNumber();

        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $adjustment->reference         = date('Y')."3".$str+"".$nextNumber;
        $adjustment->adjustment_reason = $request->input('adjustment_reason');
        $adjustment->adjustment_type   = 'stock';
        $adjustment->resource_id       = $stock->id;
        $adjustment->save();
    }
}
