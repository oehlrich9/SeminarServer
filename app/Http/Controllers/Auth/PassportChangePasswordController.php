<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PassportChangePasswordController extends Controller
{
    /**
     * Handle a change password request for the application.
     *
     * @override
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request){
        $input = $request->all();

        if (!(Hash::check($input['currentPassword'], Auth::user()->password))) {
            // The passwords matches
            return response()->json(["error" => "Your current password does not matches with the password you provided. Please try again."], 401);
        }

        if(strcmp($input['currentPassword'], $input['newPassword']) == 0){
            //Current password and new password are same
            return response()->json(['error' => "New Password cannot be same as your current password. Please choose a different password."], 401);
        }

        $validator = Validator::make($request->all(), [
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($input['newPassword']);
        $user->save();
        return response()->json(['success' => $user], 200);
    }
}
