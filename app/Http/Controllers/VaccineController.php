<?php

namespace App\Http\Controllers;

use App\Adjustment;
use App\Arrival;
use App\ArrivalItem;
use App\ItemMovement;
use App\Log;
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
use Illuminate\Support\Facades\DB;

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
     * Display a stockItems for user.
     *
     * @return Response
     */
    public function expired_stock_items()
    {
        $date = date('Y-m-d');
        return Stock::where('recipient_id',Auth::user()->recipient_id)->where('expiry_date','<=',$date)->get();
    }

    /**
     * Display a stockItems for user.
     *
     *@param $recipient
     * @param $level
     * @return Response
     */
    public function expired_stock_items1($recipient,$level)
    {

        $date = date('Y-m-d');
        if($level == '1'){
        $dispatch =  Stock::where('recipient_id',$recipient)->where('expiry_date','<=',$date)->get();
    }elseif($level == '2'){
        $orgunit = Recipient::find($recipient);
        $arr = [0,$recipient];
        array_push($arr,$recipient);
        foreach($orgunit->childrens as $val){
            array_push($arr,$val->id);
        }
        $dispatch = DB::table('stock')
            ->whereIn('recipient_id', $arr)
            ->where('expiry_date','<=',$date)
            ->get();

    }elseif($level == '3'){
        $orgunit = Recipient::find($recipient);
        $arr = [0];
        array_push($arr,$recipient);
        foreach($orgunit->childrens as $val){
            array_push($arr,$val->id);
            $orgunit1 = Recipient::find($val->id);
            foreach($orgunit1->childrens as $val){
                array_push($arr,$val->id);
            }
        }
        $dispatch = DB::table('stock')
            ->whereIn('recipient_id', $arr)
            ->where('expiry_date','<=',$date)
            ->get();

    }else{
        $dispatch = Stock::where('recipient_id',$recipient)->where('expiry_date','<=',$date)->get();
    }


        return $dispatch;
    }

    /**
     * Display a stockItems for user.
     *
     * @return Response
     */
    public function near_expired_stock_items()
    {
        $stock = Stock::where('recipient_id',Auth::user()->recipient_id)->get();
        $arr = array();
        foreach($stock as $st){
            $days = $st->vaccine->days_before_expiry;
            $date = strtotime("+".$days." day");
            $date1 = date('Y-m-d', $date);
            if($st->expiry_date <= $date1 ){
                $arr[] = $st;
            }

        }
        return json_encode($arr);
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

    /**
     * Display a arrivalsforcancel for user.
     *
     * @return Response
     */
    public function arrivalsforcancel()
    {
        $recipient_id = Auth::user()->recipient_id;
        $stock = Stock::where('recipient_id',$recipient_id)->get();
        $arrivals = Arrival::where('recipient_destination_id',Auth::user()->recipient_id)->where('status','!=','canceled')->get()->load('fromSource','source','arrivalItems');
        $arr = array();

            foreach($arrivals as $arrival){
                $k = 0;
                $j = 0;
                foreach($arrival->arrivalItems as $item){
                    $stk = Stock::where('recipient_id',$recipient_id)->where('vaccine_id',$item->vaccine_id)->where('lot_number',$item->lot_number)->first();
                    $j ++;
                    if($stk){
                        if($stk->amount >= $item->number_received){
                            $k++;
                        }
                    }
                }
                if($k == $j){
                    $arr[] = $arrival;
                }

        }
        return json_encode($arr);
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
     * Display a packages for user.
     *@param $recipient
     * @param $level
     * @return Response
     */
    public function packages1($recipient,$level)
    {
        if($level == '1'){
            $dispatch =  RecipientPackageItem::where('recipient_id',$recipient)->get()->load('recipient','vaccine','packaging','receiver','package');
        }elseif($level == '2'){
            $orgunit = Recipient::find($recipient);
            $arr = [0,$recipient];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
            }
            $dispatch = $dispatch = RecipientPackageItem::whereIn('recipient_id', $arr)
                ->get()->load('recipient','vaccine','packaging','receiver','package');

        }elseif($level == '3'){
            $orgunit = Recipient::find($recipient);
            $arr = [0];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
                $orgunit1 = Recipient::find($val->id);
                foreach($orgunit1->childrens as $val){
                    array_push($arr,$val->id);
                }
            }
            $dispatch = RecipientPackageItem::whereIn('recipient_id', $arr)
                ->get()->load('recipient','vaccine','packaging','receiver','package');

        }else{
            $dispatch =  RecipientPackageItem::where('recipient_id',$recipient)->get()->load('recipient','vaccine','packaging','receiver','package');
        }
        return $dispatch;
    }

    /**
     * Display a expectedPackages  items for user.
     * @TODO Limit the result
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
     * Display a expectedPackages for user.
     *
     * @return Response
     */
    public function packagesToCancel()
    {
        return RecipientPackage::where('source_id',Auth::user()->recipient_id)->where('receiving_status','pending')->get()->load('items','destination');
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
     * get the last package number for item movements
     *
     * @return Response
     */
    public function getNextMovementNumber()
    {
        if(count(ItemMovement::all()) != 0){
            return ItemMovement::orderBy('order_no','DESC')->first()->order_no +1;
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
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Add Product named ".$item->name
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
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Update Product named ".$item->name
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
        $item = Vaccine::find($id);
        $item->status = "deleted";
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Delete Product named ".$item->name
        ));
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
        $arrival->year                      = date('Y');
        $arrival->recipient_source_id       = $recipient->parent_id;
        $arrival->recipient_destination_id  = $recipient->id;
        $arrival->source_id                 = $request->has('source_id')?$request->input('source_id'):'';
        $arrival->arrival_report_number     = $request->has('arrival_report_no')?$request->input('arrival_report_no'):'';
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
            $ArrivalItem->source_id                 = $request->has('source_id')?$request->input('source_id'):'';
            $ArrivalItem->packaging_id              = $item['packaging_id'];
            $ArrivalItem->activity_id               = $item['activity'];
            $ArrivalItem->store_id                  = $item['store_id'];
            $ArrivalItem->vvm_status                = (isset($item['vvm_stage']))?$item['u_price']:'';
            $ArrivalItem->arrival_id                = $arrival->id;
            $ArrivalItem->package_number            = $request->has('arrival_report_no')?$request->input('arrival_report_no'):'';
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
            $ArrivalItem->voucher_number            = $arrival->reference;

            $ArrivalItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume + $item['total_volume'] ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->vaccine_id      = $item['item_id'];
                $stock->recipient_id    = $recipient->id;
                $stock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
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
                $stock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
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
                $storeStock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
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
                $storeStock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
                $storeStock->activity_id    = $item['activity'];
                $storeStock->save();
            }
        }
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Receive Products, Reference Number ".$arrival->reference
        ));
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
        $arrival->year                      = date('Y');
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
            $ArrivalItem->voucher_number            = $arrival->reference;
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
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Open Stock for Products, Reference Number ".$arrival->reference
        ));
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
        $arrival->source_id                 = $request->has('source_id')?$request->input('source_id'):'';
        $arrival->arrival_report_number     = $request->has('arrival_report_no')?$request->input('arrival_report_no'):'';
        $arrival->package_volume            = ($request->has('packed_volume'))?$request->input('packed_volume'):'';
        $arrival->total_weight              = ($request->has('total_weight'))?$request->input('total_weight'):'';
        $arrival->arrival_date              = $request->input('arrival_date');
        $arrival->freight_cost              = ($request->has('freight_cost'))?$request->input('freight_cost'):'';
        $arrival->receiving_user            = Auth::user()->id;
        $arrival->order_no                  = $nextNumber;
        $arrival->year                      = date('Y');
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
            $ArrivalItem->vaccine_id                = (isset($item['item_id']))?$item['item_id']:"";
            $ArrivalItem->packaging_id              = (isset($item['packaging_id']))?$item['packaging_id']:"";
            $ArrivalItem->activity_id               = (isset($item['activity']))?$item['activity']:"";
            $ArrivalItem->store_id                  = (isset($item['store_id']))?$item['store_id']:"";
            $ArrivalItem->vvm_status                = (isset($item['vvm_stage']))?$item['u_price']:'';
            $ArrivalItem->arrival_id                = $arrival->id;
            $ArrivalItem->package_number            = $request->has('arrival_report_no')?$request->input('arrival_report_no'):'';
            $ArrivalItem->lot_number                = (isset($item['lot_number']))?$item['lot_number']:"";
            $ArrivalItem->number_expected           = (isset($item['doses']))?$item['doses']:"";
            $ArrivalItem->number_received           = $amount;
            $ArrivalItem->physical_damage           = (isset($item['physical_damage']))?$item['physical_damage']:"";
            $ArrivalItem->number_as_expected        = (isset($item['number_as_expected']))?$item['number_as_expected']:"";
            $ArrivalItem->damaged_amount            = (isset($item['damaged_amount']))?$item['damaged_amount']:0;
            $ArrivalItem->receiving_user            = Auth::user()->id;
            $ArrivalItem->unit_price                = (isset($item['u_price']))?$item['u_price']:'';
            $ArrivalItem->total_price               = (isset($item['t_price']))?$item['t_price']:'';
            $ArrivalItem->volume                    = (isset($item['total_volume']))?$item['total_volume']:'';
            $ArrivalItem->manufacture_id            = (isset($item['manufacture_id']))?$item['manufacture_id']:0;
            $ArrivalItem->prduction_date            = (isset($item['prod_date']))?$item['prod_date']:'';
            $ArrivalItem->expiry_date               = (isset($item['expired_date']))?$item['expired_date']:'';
            $ArrivalItem->voucher_number            = $arrival->reference;
            $ArrivalItem->arrival_date              = $request->input('arrival_date');
            $ArrivalItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume + ($amount*$item['cm_per_dose']*0.001) ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->vaccine_id      = (isset($item['item_id']))?$item['item_id']:"";
                $stock->recipient_id    = $recipient->id;
                $stock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
                $stock->amount          = $amount+$stock->amount;
                $stock->lot_number      = (isset($item['lot_number']))?$item['lot_number']:"";
                $stock->packaging_id    = (isset($item['packaging_id']))?$item['packaging_id']:"";
                $stock->expiry_date     = (isset($item['expired_date']))?$item['expired_date']:"";
                $stock->store_id        = (isset($item['store_id']))?$item['store_id']:"";
                $stock->unit_price      = (isset($item['u_price']))?$item['u_price']:"";
                $stock->activity_id     = (isset($item['activity']))?$item['activity']:"";
                $stock->save();
            }else{
                $stock = new Stock;
                $stock->vaccine_id      = (isset($item['item_id']))?$item['item_id']:"";
                $stock->recipient_id    = $recipient->id;
                $stock->amount          = $amount;
                $stock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
                $stock->lot_number      = (isset($item['lot_number']))?$item['lot_number']:"";
                $stock->packaging_id    = (isset($item['packaging_id']))?$item['packaging_id']:"";
                $stock->expiry_date     = (isset($item['expired_date']))?$item['expired_date']:"";
                $stock->store_id        = (isset($item['store_id']))?$item['store_id']:"";
                $stock->unit_price      = (isset($item['u_price']))?$item['u_price']:"";
                $stock->activity_id     = (isset($item['activity']))?$item['activity']:"";
                $stock->save();
            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->vaccine_id     = (isset($item['item_id']))?$item['item_id']:"";
                $storeStock->store_id       = (isset($item['store_id']))?$item['store_id']:"";
                $storeStock->amount         = $amount+$storeStock->amount;
                $storeStock->lot_number     = (isset($item['lot_number']))?$item['lot_number']:"";
                $storeStock->packaging_id   = (isset($item['packaging_id']))?$item['packaging_id']:"";
                $storeStock->expiry_date    = (isset($item['expired_date']))?$item['expired_date']:"";
                $storeStock->unit_price     = (isset($item['u_price']))?$item['u_price']:"";
                $storeStock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
                $storeStock->activity_id    = (isset($item['activity']))?$item['activity']:"";
                $storeStock->save();
            }else{
                $storeStock = new StoreStock;
                $storeStock->vaccine_id     = (isset($item['item_id']))?$item['item_id']:"";
                $storeStock->store_id       = (isset($item['store_id']))?$item['store_id']:"";
                $storeStock->amount         = $amount;
                $storeStock->lot_number     = (isset($item['lot_number']))?$item['lot_number']:"";
                $storeStock->packaging_id   = (isset($item['packaging_id']))?$item['packaging_id']:"";
                $storeStock->expiry_date    = (isset($item['expired_date']))?$item['expired_date']:"";
                $storeStock->unit_price     = (isset($item['u_price']))?$item['u_price']:"";
                $storeStock->source_id       = $request->has('source_id')?$request->input('source_id'):'';
                $storeStock->activity_id    = (isset($item['activity']))?$item['activity']:"";
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

        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Receive Products, Reference Number ".$arrival->reference
        ));
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
            $dispatchItem->voucher_number            = $dispatch->voucher_number;
            $dispatchItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume - $item['total_volume'] ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->amount          = $stock->amount - $item['doses'];
                $stock->save();
                if($stock->amount == 0){
                    $stock->delete();
                }
            }else{

            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->amount         = $storeStock->amount - $item['doses'];
                $storeStock->save();
                if($storeStock->amount == 0){
                    $storeStock->delete();
                }
            }else{

            }
        }
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Dispatch Products Reference Number ".$dispatch->voucher_number
        ));
        return $dispatch->voucher_number;
    }

    /**
     * Dealing with item adjustment item info
     * @param Request $request
     * @return Response
     */

    public function arrival_adjust(Request $request){
        $arrival = Arrival::find($request->input('id'));
        $recipient = Recipient::find(Auth::user()->recipient_id);
        foreach($arrival->arrivalItems as $items){
            $volume = $items->number_received * PackagingInformation::find($items->packaging_id)->cm_per_dose * 0.001;
            $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$items->vaccine_id)->where('lot_number',$items->lot_number)->first();
            $storeStock = StoreStock::where('store_id',$items->store_id)->where('vaccine_id',$items->vaccine_id)->where('lot_number',$items->lot_number)->first();
            $stock->amount = $stock->amount - $items->number_received;
            $storeStock->amount = $storeStock->amount - $items->number_received;
            $stock->save();
            $storeStock->save();

            //reduce volume in store
            $store = Store::find($items->store_id);
            $store->used_volume = $store->used_volume - $volume ;
            $store->save();

            //update line item status
            $items->status = 'canceled';
            $items->save();

            //delete stock item if amount has turned to zero
            if($stock->amount == 0){
                $stock->delete();
            }
            if($storeStock->amount == 0){
                $storeStock->delete();
            }
        }
        //update arrival status
        $arrival->status = 'canceled';
        $arrival->notes = $request->has('notes')?$request->input('notes'):"";
        if($recipient->level == 1){
          foreach(PreShipment::where('package_id',$arrival->arrival_report_number)->get() as $package){
              $package->status = 'pending';
              $package->save();
          };
        }else{
          foreach(RecipientPackage::where('voucher_number',$arrival->arrival_report_number)->get() as $package){
              $package->receiving_status = 'pending';
              $package->save();
          };
        }
        $arrival->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Cancel Arrival with reference Number ".$arrival->reference
        ));

    }

    public function dispatch_adjust(Request $request){

        //canceling the previous dispatch
        $dispatch = RecipientPackage::find($request->input('id'));
        $recipient = Recipient::find(Auth::user()->recipient_id);
        foreach($dispatch->items as $items){
            $volume = $items->amount * PackagingInformation::find($items->packaging_id)->cm_per_dose * 0.001;
            $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$items->vaccine_id)->where('lot_number',$items->batch_number)->first();
            $storeStock = StoreStock::where('store_id',$items->store_id)->where('vaccine_id',$items->vaccine_id)->where('lot_number',$items->batch_number)->first();
            $stock->amount = $stock->amount + $items->amount;
            $storeStock->amount = $storeStock->amount + $items->amount;
            $stock->save();
            $storeStock->save();

            //reduce volume in store
            $store = Store::find($items->store_id);
            $store->used_volume = $store->used_volume + $volume ;
            $store->save();

            //deleting all line item status
            $items->delete();

        }

        //creating the new dispatch
        $recipient = Recipient::find(Auth::user()->recipient_id);
        $dispatch->recipient_id              = $request->input('recipients');
        $dispatch->date_sent                 = $request->input('dispatch_date');
        $dispatch->transport_mode_id         = $request->input('transport_mode');
        $dispatch->sending_user              = Auth::user()->id;
        $dispatch->receiving_status          = 'pending';
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
            $dispatchItem->voucher_number            = $dispatch->voucher_number;
            $dispatchItem->save();

            $store = Store::find($item['store_id']);
            $store->used_volume = $store->used_volume - $item['total_volume'] ;
            $store->save();

            //adding the item to stock
            if(count(Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $stock->amount      = $stock->amount - $item['doses'];
                $stock->save();
                if($stock->amount == 0){
                    $stock->delete();
                }
            }else{

            }

            //adding the item to store
            if(count(StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->get()) != 0){
                $storeStock = StoreStock::where('store_id',$item['store_id'])->where('vaccine_id',$item['item_id'])->where('lot_number',$item['lot_number'])->first();
                $storeStock->amount         = $storeStock->amount - $item['doses'];
                $storeStock->save();
                if($storeStock->amount == 0){
                    $storeStock->delete();
                }
            }else{

            }
        }
        return $dispatch->voucher_number;
    }

    public function stock_adjust(Request $request){
        $stock = Stock::find($request->input('stock_id'));
        $amount = $stock->amount;
        $storeStock = StoreStock::where('store_id',$stock->store_id)->where('vaccine_id',$request->input('item_id'))->where('lot_number',$request->input('lot_number'))->first();
        $stock->amount = $request->has('doses')?$request->input('doses'):$stock->amount - $request->input('change');
        $storeStock->amount = $request->has('doses')?$request->input('doses'):$storeStock->amount - $request->input('change');
        if($request->has('doses')){
            $volume = $request->input('doses') * PackagingInformation::find($stock->packaging_id)->cm_per_dose * 0.001;
        }if($request->has('change')){
            $volume = $request->input('change') * PackagingInformation::find($stock->packaging_id)->cm_per_dose * 0.001;
        }

        $stock->save();
        $storeStock->save();
        if($stock->amount == 0){
            $stock->delete();
        }
        if($storeStock->amount == 0){
            $storeStock->delete();
        }

        //updating volumes
        $store = Store::find($stock->store_id);
        $store->used_volume = $store->used_volume - $volume ;
        $store->save();


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
        $adjustment->year              = date('Y');
        $adjustment->order_no          = $nextNumber;
        $adjustment->vaccine_id        = $stock->vaccine_id;
        $adjustment->store_id          = $stock->store_id;
        $adjustment->lot_number        = $stock->lot_number;
        $adjustment->packaging_id      = $stock->packaging_id;
        $adjustment->expiry_date       = $stock->expiry_date;
        $adjustment->source_id         = $stock->source_id;
        $adjustment->activity_id       = $stock->activity_id;
        $adjustment->recipient_id      = $stock->recipient_id;
        $adjustment->previous_amount   = $amount;
        $adjustment->current_amount    = $stock->amount;
        $adjustment->adjusted_volume    = $volume;
        $adjustment->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Stock Adjustment with reference Number ".$adjustment->reference
        ));
        return $adjustment->reference;
    }

    public function move_item(Request $request){
        $storeStock = StoreStock::find($request->input('item_id'));
        $volume = $request->input('doses') * PackagingInformation::find($storeStock->packaging_id)->cm_per_dose * 0.001;
        $recipient = Recipient::find(Auth::user()->recipient_id);

        $storeStock->amount = $request->has('doses')?$storeStock->amount - $request->input('doses'):$storeStock->amount;
        $storeStock->save();
        if($storeStock->amount == 0){
            $storeStock->delete();
        }
        if(count(StoreStock::where('store_id',$request->input('to_store'))->where('vaccine_id',$storeStock->vaccine_id)->where('lot_number',$storeStock->lot_number)->get()) != 0){
            $storeStock1 = StoreStock::where('store_id',$request->input('to_store'))->where('vaccine_id',$storeStock->vaccine_id)->where('lot_number',$storeStock->lot_number)->first();
            $storeStock1->amount = $request->has('doses')?$storeStock1->amount + $request->input('doses'):$storeStock1->amount;
            $storeStock1->save();
        }else{
            $storeStock1 = new StoreStock;
            $storeStock1->vaccine_id     = $storeStock->vaccine_id;
            $storeStock1->store_id       = $request->input('to_store');
            $storeStock1->amount         = $request->input('doses');
            $storeStock1->lot_number     = $storeStock->lot_number;
            $storeStock1->packaging_id   = $storeStock->packaging_id;
            $storeStock1->expiry_date    = $storeStock->expiry_date;
            $storeStock1->unit_price     = $storeStock->unit_price ;
            $storeStock1->source_id       = $storeStock->source_id;
            $storeStock1->activity_id    = $storeStock->activity_id;
            $storeStock1->save();
        }

        $movement = new ItemMovement;
        $nextNumber = $this->getNextMovementNumber();

        $str = "";
        for($sj = 6; $sj > strlen($nextNumber);$sj--){
            $str.="0";
        }
        $movement->reference         = date('Y')."4".$str+"".$nextNumber;
        $movement->from_store       = $storeStock->store_id;
        $movement->to_store         = $storeStock1->store_id;
        $movement->store_item_id    = $storeStock->id;
        $movement->user_id          = Auth::user()->id;
        $movement->amount           = $request->input('doses');
        $movement->recipient_id     = Auth::user()->recipient_id;
        $movement->vaccine_id       = $storeStock->vaccine_id;
        $movement->lot_number       = $storeStock->lot_number;
        $movement->expiry_date      = $storeStock->expiry_date;
        $movement->moved_volume     = $volume;
        $movement->year              = date('Y');
        $movement->order_no          = $nextNumber;
        $movement->save();

        $store = Store::find($storeStock->store_id);
        $store1 = Store::find($storeStock1->store_id);
        $store->used_volume = $store->used_volume - $volume ;
        $store->save();
        $store1->used_volume = $store1->used_volume + $volume ;
        $store1->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Move ".$request->input('doses')." Doses of ".Vaccine::find($storeStock->vaccine_id)->name." from ".$store->name." To ". $store1->name
        ));
        return $movement->reference;
    }

    public function cancelDispatch($id,Request $request){
        $dispatch = RecipientPackage::find($id);
        $recipient = Recipient::find(Auth::user()->recipient_id);
        foreach($dispatch->items as $items){
            $volume = $items->amount * PackagingInformation::find($items->packaging_id)->cm_per_dose * 0.001;
            $stock = Stock::where('recipient_id',$recipient->id)->where('vaccine_id',$items->vaccine_id)->where('lot_number',$items->batch_number)->first();
            $storeStock = StoreStock::where('store_id',$items->store_id)->where('vaccine_id',$items->vaccine_id)->where('lot_number',$items->batch_number)->first();
            $stock->amount = $stock->amount + $items->amount;
            $storeStock->amount = $storeStock->amount + $items->amount;
            $stock->save();
            $storeStock->save();

            //reduce volume in store
            $store = Store::find($items->store_id);
            $store->used_volume = $store->used_volume + $volume ;
            $store->save();

            //update line item status
            $items->status = 'canceled';
            $items->save();

            //delete stock item if amount has turned to zero
            if($stock->amount == 0){
                $stock->delete();
            }
            if($storeStock->amount == 0){
                $storeStock->delete();
            }
        }
        //update arrival status
        $dispatch->receiving_status = 'canceled';
        $dispatch->comments = $request->has('notes')?$request->input('notes'):"";
        $dispatch->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Cancel Dispatch with reference Number ".$dispatch->voucher_number
        ));
        return $dispatch->voucher_number;
    }

public function updateItems(){
     foreach(RecipientPackage::all() as $package){
         
         foreach($package->items as $items){
             echo $items->voucher_number;
             $items->voucher_number = $package->voucher_number;
             $items->save();
         }
     }

    }


}
