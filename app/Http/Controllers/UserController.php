<?php

namespace App\Http\Controllers;

use App\Log;
use App\Recipient;
use App\SystemSettings;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return User::where('status','!=','deleted')->with('recipient')->with('roles')->get();

    }


    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return Response
     */
    public function userlogs($id)
    {
        return Log::where('user_id',$id)->orderBy('id','DESC')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->first_name   = $request->input('first_name');
        $user->middle_name   = $request->has('middle_name')?$request->input('middle_name'):'';
        $user->last_name    = $request->input('last_name');
        $user->phone        = $request->has('phone')?$request->input('phone'):'';
        $user->email        = $request->input('email');
        $user->user_name    = $request->input('user_name');
        $user->role         = $request->input('role');
        $user->password     = Hash::make($request->input('password'));
        $user->recipient_id = $request->input('recipient_id');
        $user->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Add user named ".$user->first_name," ".$user->last_name
        ));
        return $user->load('recipient', 'roles');
    }


    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return User::find(Auth::user()->id)->load('recipient', 'roles');
    }


    /**
     * Show the form for editing the specified resource.
     *
     *  @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function updatePassword(Request $request,$id)
    {
        $user = User::find($id);
        if($user && Hash::check($request->input('oldPassword'), $user->password)){
            $user->password = Hash::make($request->input('password'));
            $user->save();
            Log::create(array(
                "user_id"=>  Auth::user()->id,
                "action"  =>"Updates Password"
            ));
            return "success";
        }else{
            return "error";
        }
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
        $user = User::find($id);
        $user->first_name   = $request->input('first_name');
        $user->middle_name   = $request->has('middle_name')?$request->input('middle_name'):'';
        $user->last_name    = $request->input('last_name');
        $user->phone        = $request->has('phone')?$request->input('phone'):'';
        $user->email        = $request->input('email');
//        $user->user_name    = $request->input('user_name');
        $user->role         = $request->input('role');
//        $user->recipient_id = $request->input('recipient_id');
        $user->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Update user named ".$user->first_name," ".$user->last_name
        ));
        return $user->load('recipient', 'roles');;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->status = "deleted";
        $user->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Delete user named ".$user->first_name," ".$user->last_name
        ));

    }

    /**
     * authanticate user during login.
     *
     * @param  Request  $request
     * @return view
     */
    public function validateUser(Request $request)
    {
//        $user = User::where("email",Input::get('email'))->first();
        $user = User::where("user_name",$request->input('username'))->where('status','!=','deleted')->first();
        if($user && Hash::check($request->input('password'), $user->password)){
            Auth::login($user,TRUE);

            if(Auth::check()){
                Log::create(array(
                    "user_id"=>  Auth::user()->id,
                    "action"  =>"Logged In"
                ));
                return Redirect::to("/");
            }

        }
        else{
            return View::make("welcome")->with("error","Incorrect Username or Password");
        }
    }

    /**
     * loging out a user
     *
     * @return view
     */
    public function logout(){
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Logged Out"
        ));
        Auth::logout();

        return Redirect::to("login");
    }

     /**
     * return system settings
     *
     * @return view
     */
    public function getSettings(){
        return SystemSettings::all();
    }

     /**
     * save new system settings
     *
      * @param  Request  $request
     * @return view
     */
    public function saveSettings(Request $request){
        if(count(SystemSettings::all()) != 0){
            $item = SystemSettings::first();
        }else{
            $item = new SystemSettings;
        }
        $item->language     = $request->input("language");
        $item->main_currency     = $request->input("main_currency");
        $item-> start_year    = $request->input("start_year");
        $item->save();
        $central = Recipient::where('parent_id',0)->first();
        $central->name = $request->input("central_level_name");
        $central->save();
        Log::create(array(
            "user_id"=>  Auth::user()->id,
            "action"  =>"Update System Settings"
        ));
        return $item;
    }



}
