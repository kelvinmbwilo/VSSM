<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Store;
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
        return Store::where('recipient_id',Auth::user()->recipient_id)->get();
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

        $item = Store::find($id);
        $item->status           = $request->input('status');
        $item->name             = $request->input("name");
        $item->code             = $request->input("code");
        $item->net_volume       = $request->input("net_volume");
        $item->store_type       = $request->input("store_type");
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
        $item = Store::find($id);
        $item->delete();
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
            $array[] = array('name'=>Vaccine::find($stock->vaccine_id)->name,'amount'=>$stock->total);
        };
        return json_encode($array);
    }



}
