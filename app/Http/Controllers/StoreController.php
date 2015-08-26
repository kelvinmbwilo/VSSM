<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Store::all();
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
        $item->recipient_id     = $request->input("");
        $item->net_volume       = $request->input("");
        $item->store_type       = $request->input("");
        $item->temperature      = $request->input("");
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
        $item->recipient_id     = $request->input("");
        $item->net_volume       = $request->input("");
        $item->store_type       = $request->input("");
        $item->temperature      = $request->input("");
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

}
