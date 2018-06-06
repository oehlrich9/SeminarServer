<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller{


    /**
     * Handle a user detail request for the application.
     *
     * @override
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userDetail(Request $request){
        return response()->json(["user" => Auth::user()], 200);
    }

    /**
     * Handle a user detail request for the application.
     * Unknown fields throws 400 response;
     *
     * @override
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request){
        //return response()->json(["User" => Auth::user()], 200);
        $input = $request->all();
        $user = Auth::user();
        foreach($input as $key => $value){
            if(!isset($user->$key)){
                return response()->json(["error" => "Property $key does not exist in User, Aborting"], 400);
            }
            $user->$key = $value;
        }
        $user->save();
        return response()->json(["success" => $user], 200);
    }
}
