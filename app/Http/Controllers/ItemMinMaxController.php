<?php

namespace App\Http\Controllers;

use App\ItemMinMax;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ItemMinMaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ItemMinMax::where('recipient_id',Auth::user()->recipient_id)->with('vaccine')->get();
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
        $rec_id = Auth::user()->recipient_id;
        if(count(ItemMinMax::where('recipient_id',$rec_id)->where('item_id',$request->input("item_id"))->get()) != 0){
            $item = ItemMinMax::where('recipient_id',$rec_id)->where('item_id',$request->input("item_id"))->first();
        }else{
            $item = new ItemMinMax;
        }
        $item->recipient_id   = $rec_id;
        $item->item_id     = $request->input("item_id");
        $item->min_value     = $request->input("min_value");
        $item->max_value     = $request->input("max_value");
        $item->save();
        return $item->load('vaccine');
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

        $item = ItemMinMax::find($id);
        $item->item_id     = $request->input("item_id");
        $item->min_value     = $request->input("min_value");
        $item->max_value     = $request->input("max_value");
        $item->save();
        return $item->load('vaccine');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $recipient = ItemMinMax::find($id);
        $recipient->delete();
    }

}
