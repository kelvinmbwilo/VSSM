<?php

namespace App\Http\Controllers;

use App\Recipient;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function userRecipients()
    {
        return Recipient::find(Auth::user()->recipient_id)->childrens()->where('status','active')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function userRecipientLevel()
    {
        return Recipient::find(Auth::user()->recipient_id)->level;
    }

    /**
     *Get the root of adminUnits
     *
     * @return Response
     */
    public function getRoot()
    {
        return Recipient::where('parent_id',0)->where('status','!=','deleted')->first();
    }

    /**
     *Get the recipients in a certain level
     *
     * @param $levelId
     * @return Response
     */
    public function getRecipientOnLevel($levelId)
    {
        return Recipient::where('level',$levelId)->where('status','!=','deleted')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $item = new Recipient;
        $item->name                 = $request->input("name");
        $item->code                 = $request->input("code");
        $item->distance             = ($request->has("distance"))?$request->input("distance"):"";
        $item->notes                = ($request->has("notes"))?$request->input("notes"):"";
        $item->level                = $request->input("level");
        $item->parent_id            = $request->input("parent_id");
        $item->status               = "active";

        $item->save();
        return $item;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Recipient::find($id);
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
    public function update(Request $request, $id)
    {
        $item = Recipient::find($id);
        $item->name                 = $request->input("name");
        $item->code                 = $request->input("code");
        $item->distance             = ($request->has("distance"))?$request->input("distance"):"";
        $item->notes                = ($request->has("notes"))?$request->input("notes"):"";
        $item->status               = $request->input("status");

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
        $item = Recipient::find($id);
        $item->status    ="deleted";
        $item->save();
        return $item;
    }
}
