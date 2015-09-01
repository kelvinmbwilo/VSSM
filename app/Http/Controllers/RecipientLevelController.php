<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RecipientLevel;
use App\Recipient;
use App\Http\Controllers\Controller;

class RecipientLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return RecipientLevel::all();
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
        $item = new RecipientLevel;

        $item->order    = count(RecipientLevel::all())+1;
        $item->status   = "active";
        $item->name     = $request->input("name");
        $item->code     = $request->input("code");

        $item->save();
        return $item;
    }


    /**
     * Display the number of items in recipient level.
     *
     * @param  int  $order
     * @return Response
     */
    public function getRecipientsNumber($order)
    {
        return count(Recipient::where('level',$order)->get());
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

        $recipient = RecipientLevel::find($id);
        $recipient->name = $request->input('name');
        $recipient->code = $request->input('code');
        $recipient->status = $request->input('status');
        $recipient->save();
        return $recipient;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $recipient = RecipientLevel::find($id);
        $recipient->delete();
    }

}
