<?php

namespace App\Http\Controllers;

use App\Adjustment;
use App\ArrivalItem;
use App\ItemMovement;
use App\Log;
use App\Recipient;
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

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Store::where('recipient_id',Auth::user()->recipient_id)->where('status','!=','deleted')->get();
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
        $item = new Store;
        $item->status           = "active";
        $item->name             = $request->input("name");
        $item->code             = $request->input("code");
        $item->recipient_id     = Auth::user()->recipient_id;
        $item->net_volume       = $request->input("net_volume");
        $item->store_type       = $request->input("store_type");
        $item->notes            = $request->has("notes")?$request->input("notes"):"";
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Add Store named ".$item->name
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

        $item = Store::find($id);
        $item->status           = $request->input('status');
        $item->name             = $request->input("name");
        $item->code             = $request->input("code");
        $item->net_volume       = $request->input("net_volume");
        $item->store_type       = $request->input("store_type");
        $item->notes            = $request->has("notes")?$request->input("notes"):"";
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Update Store named ".$item->name
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
        $item = Store::find($id);
        $item->status = "deleted";
        $item->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Delete Store named ".$item->name
        ));
    }

    /**
     * get storage volume of specific store.
     *
     * @param  int  $id
     * @return Response
     */
    public function storeStocks($id)
    {
        $item = Store::where('status','active')->get();
        $arr = array();
        foreach ($item as $store){
            $arr[] = array('name'=>$store->name,'id'=>$store->id,'volume'=>$store->net_volume,'used_volume'=>$store->used_volume);
        }
        return json_encode($arr);
    }


     /**
     * get storage volume of specific store.
     *
     * @param  int  $id
     * @return Response
     */
    public function vaccineStocks($id)
    {
        $item = Stock::where('recipient_id',Auth::user()->recipient_id)->select('vaccine_id', DB::raw('sum(amount) as total'))
            ->groupBy('vaccine_id')
            ->get();
        $array = array();
        foreach($item as $stock){
            $array[] = array('id'=>$stock->vaccine_id,'name'=>Vaccine::find($stock->vaccine_id)->name,'amount'=>$stock->total);
        };
        return json_encode($array);
    }

    /**
     * get storeItems.
     *
     * @return Response
     */
    public function storeItems()
    {
        $store = Store::where('recipient_id',Auth::user()->recipient_id)->get();
        $arr = array();
        foreach($store as $stock){
            $arr[$stock->id] = StoreStock::where('store_id',$stock->id)->select('vaccine_id', DB::raw('sum(amount) as total'))
                ->groupBy('vaccine_id')
                ->get();
        }
        return json_encode($arr);
    }

    /**
     * get disaptchedItems.
     *
     * @return Response
     */
    public function disaptchedItems()
    {
            $dispatch =  RecipientPackageItem::where('recipient_id',Auth::user()->recipient_id)->select('vaccine_id', DB::raw('sum(amount) as total'))
                ->groupBy('vaccine_id')
                ->get();
//        $dispatch =  RecipientPackageItem::where('recipient_id',Auth::user()->recipient_id)->select('vaccine_id', DB::raw('sum(amount) as total'),DB::raw('month(created_at) as month'))
//                ->groupBy('month')
//                ->get();
        return $dispatch;

    }

    /**
     * get disaptchedItemsMonth.
     *
     * @return Response
     */
    public function disaptchedItemsMonth()
    {
        $dispatch =  RecipientPackageItem::where('recipient_id',Auth::user()->recipient_id)->select('vaccine_id', DB::raw('sum(amount) as total'),DB::raw('month(created_at) as month'))
                ->groupBy('month')
                ->get();
        return $dispatch;

    }

    /**
     * get arrivalItemsMonth.
     *
     * @return Response
     */
    public function arrivalItemsMonth()
    {
        $dispatch =  ArrivalItem::where('recipient_destination_id',Auth::user()->recipient_id)->select('vaccine_id', DB::raw('sum(number_received) as total'),DB::raw('month(created_at) as month'),DB::raw('year(created_at) as year'))
                ->groupBy('month','vaccine_id','year')
                ->get();
        return $dispatch;

    }

    /**
     * get arrivalItems.
     *
     * @return Response
     */
    public function arrivalItems()
    {
        $dispatch =  ArrivalItem::where('recipient_destination_id',Auth::user()->recipient_id)->get();
        return $dispatch;

    }

    /**
     * get arrivalItems.
     * @param $recipient
     * @param $level
     * @return Response
     */
    public function arrivalItems1($recipient,$level)
    {

        if($level == '1'){
            $dispatch =  ArrivalItem::where('recipient_destination_id',$recipient)->get();
        }elseif($level == '2'){
            $orgunit = Recipient::find($recipient);
            $arr = [0,$recipient];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
            }
            $dispatch = DB::table('arrival_items')
                ->whereIn('recipient_destination_id', $arr)
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
            $dispatch = DB::table('arrival_items')
                ->whereIn('recipient_destination_id', $arr)
                ->get();

        }else{
            $dispatch =  ArrivalItem::where('recipient_destination_id',Auth::user()->recipient_id)->get();
        }

        return $dispatch;

    }

    /**
     * get canceledarrivalItems.
     *
     * @return Response
     */
    public function canceledarrivalItems()
    {
        $dispatch =  ArrivalItem::where('recipient_destination_id',Auth::user()->recipient_id)->where('status','canceled')->get()->load('arrival');
        return $dispatch;
    }

    /**
     * get canceledarrivalItems.
     *
     *@param $recipient
     * @param $level
     * @return Response
     */
    public function canceledarrivalItems1($recipient,$level)
    {
        if($level == '1'){
            $dispatch =  ArrivalItem::where('recipient_destination_id',$recipient)->where('status','canceled')->get()->load('arrival');
        }elseif($level == '2'){
            $orgunit = Recipient::find($recipient);
            $arr = [0,$recipient];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
            }
            $dispatch = ArrivalItem::where('status','canceled')
                ->whereIn('recipient_destination_id', $arr)
                ->get()->load('arrival');

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
            $dispatch = ArrivalItem::where('status','canceled')
                ->whereIn('recipient_destination_id', $arr)
                ->get()->load('arrival');

        }else{
            $dispatch =  ArrivalItem::where('recipient_destination_id',$recipient)->where('status','canceled')->get()->load('arrival');
        }

        return $dispatch;
    }

    /**
     * get dispatchedItems.
     *
     * @param $recipient
     * @param $level
     * @return Response
     */
    public function disItems($recipient,$level)
    {
        if($level == '1'){
            $dispatch =  RecipientPackageItem::where('recipient_id',$recipient)->get();
        }elseif($level == '2'){
            $orgunit = Recipient::find($recipient);
            $arr = [0,$recipient];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
            }
            $dispatch = DB::table('recipients_package_items')
                ->whereIn('recipient_id', $arr)
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
            $dispatch = DB::table('recipients_package_items')
                ->whereIn('recipient_id', $arr)
                ->get();

        }else{
            $dispatch =  RecipientPackageItem::where('recipient_id',$recipient)->get();
        }

        return $dispatch;

    }

    /**
     * get dispatchedItems.
     *
     * @return Response
     */
    public function canceledDisItems()
    {
        $dispatch =  RecipientPackageItem::where('recipient_id',Auth::user()->recipient_id)->where('status','canceled')->get()->load('package');
        return $dispatch;

    }

    /**
     * get dispatchedItems.
     *@param $recipient
     * @param $level
     * @return Response
     */
    public function canceledDisItems1($recipient,$level)
    {
        if($level == '1'){
            $dispatch =  RecipientPackageItem::where('recipient_id',$recipient)->where('status','canceled')->get()->load('package');
        }elseif($level == '2'){
            $orgunit = Recipient::find($recipient);
            $arr = [0,$recipient];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
            }
            $dispatch = RecipientPackageItem::where('status','canceled')
                ->whereIn('recipient_id', $arr)
                ->get()->load('package');

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
            $dispatch = RecipientPackageItem::where('status','canceled')
                ->whereIn('recipient_id', $arr)
                ->get()->load('package');

        }else{
            $dispatch =  RecipientPackageItem::where('recipient_id',$recipient)->where('status','canceled')->get()->load('package');
        }

        return $dispatch;

    }

    /**
     * get received_items.
     *
     * @return Response
     */
    public function receivItems()
    {
            $dispatch =  ArrivalItem::where('recipient_destination_id',Auth::user()->recipient_id)->select('vaccine_id', DB::raw('sum(number_received) as total'))
                ->groupBy('vaccine_id')
                ->get();
//        $dispatch =  RecipientPackageItem::where('recipient_id',Auth::user()->recipient_id)->select('vaccine_id', DB::raw('sum(amount) as total'),DB::raw('month(created_at) as month'))
//                ->groupBy('month')
//                ->get();
        return $dispatch;

    }

    /**
     * get storeItemsDetails.
     *
     * @return Response
     */
    public function storeItemsDetails()
    {
        $store = Store::where('recipient_id',Auth::user()->recipient_id)->get();
        $arr = array();
        foreach($store as $stock){
            $arr[$stock->id] = StoreStock::where('store_id',$stock->id)->get()->load('packaging');
        }
        return json_encode($arr);
    }

/**
     * get storage volume of specific store.
     *
     * @param  int  $id
     * @return Response
     */
    public function storeStockItems($id)
    {
        $store = Store::find($id);
        return $store->stock_items;
    }

/**
     * get adjustedItems.
     *
     * @return Response
     */
    public function adjustedItems()
    {
        return Adjustment::where('recipient_id',Auth::user()->recipient_id)->get();
    }

    /**
     * get adjustedItems.
     *@param $recipient
     * @param $level
     * @return Response
     */
    public function adjustedItems1($recipient,$level)
    {
        if($level == '1'){
            return Adjustment::where('recipient_id',$recipient)->get();
        }elseif($level == '2'){
            $orgunit = Recipient::find($recipient);
            $arr = [0,$recipient];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
            }
            $dispatch = Adjustment::whereIn('recipient_id', $arr)
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
            $dispatch = Adjustment::whereIn('recipient_id', $arr)
                ->get();

        }else{
            return Adjustment::where('recipient_id',$recipient)->get();
        }

        return $dispatch;
    }

/**
     * get movedItems.
     *
     * @return Response
     */
    public function movedItems()
    {

        return ItemMovement::where('recipient_id',Auth::user()->recipient_id)->get();
    }

    /**
     * get movedItems.
     *@param $recipient
     * @param $level
     * @return Response
     */
    public function movedItems1($recipient,$level)
    {
        if($level == '1'){
            return ItemMovement::where('recipient_id',$recipient)->get();
        }elseif($level == '2'){
            $orgunit = Recipient::find($recipient);
            $arr = [0,$recipient];
            array_push($arr,$recipient);
            foreach($orgunit->childrens as $val){
                array_push($arr,$val->id);
            }
            $dispatch = ItemMovement::whereIn('recipient_id', $arr)
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
            $dispatch = ItemMovement::whereIn('recipient_id', $arr)
                ->get();

        }else{
            return ItemMovement::where('recipient_id',$recipient)->get();
        }

        return $dispatch;
    }

}
