<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Device;
use App\Models\DeviceToken;
use App\User;

use DateTime;
use DatePeriod;
use DateInterval;

class DeviceController extends Controller{


    private static $authDevice = null;


    public static function device(){
        return DeviceController::$authDevice;
    }

    public static function setDevice(Device $device){
        DeviceController::$authDevice = $device;
    }
    /**
     * Handle a deviceToken request for the application.
     *
     * @override
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendDeviceToken(Request $request){
        $token = new DeviceToken();
        error_log(json_encode(Carbon::now()));
        DeviceToken::where('expiry', '<=', Carbon::now())->delete();
        //Create Token string as long as one in not taken
        $bytes = random_bytes(30);
        $tokenString = bin2hex($bytes);
        //Save new token with new expiricy
        $token->token = $tokenString;
        $datetime = new Datetime();
        $token->expiry = $datetime->add(new DateInterval('PT2M'));
        Auth::user()->deviceToken()->save($token);
        return response()->json(["device_token" => $token], 201);
    }

    public function addDevice(Request $request){
        if(!$request->has('deviceToken') || !$request->has('name')){
            return response()->json(["error" => "Token or Name not given"], 400);
        }
        $userid = Auth::user()->id;
        $deviceToken = DeviceToken::where('expiry', '>=', Carbon::now())
                                ->where('token', $request->get('deviceToken'))
                                ->where('user_id', $userid)->exists();
        if(!$deviceToken){
            DeviceToken::where('token', $request->get('deviceToken'))->delete();
            return response()->json(["error" => "DeviceToken expired or not existing"], 404);
        }
        DeviceToken::where('token', $request->get('deviceToken'))->delete();
        $device = new Device();
        $bytes = random_bytes(40);
        $device->secret = bin2hex($bytes);
        $device->name = $request->get('name');
        Auth::user()->devices()->save($device);
        return response()->json(["device" => $device, "secret" => $device->secret], 201);
    }

    public function getDevices(){
        $devices = Auth::user()->devices()->get(['id','name']);
        return response()->json(["devices" => $devices], 200);
    }

    public function removeDevice(Request $request, $id){
        if(!is_numeric($id)){
            return response()->json(['error' => 'id is not an integer'], 400);
        }
        $device = Auth::user()->devices()->where('id', $id)->firstOrFail();
        $device->delete();
        return response()->json(["success" => "Device removed"], 200);
    }

    public function editDevice(Request $request){
        if(!is_numeric($id)){
            return response()->json(['error' => 'id is not an integer'], 400);
        }
        if(!$request->has('name')){
            return response()->json(["error" => "Device name not set"], 400);
        }
        $device = Auth::user()->devices()->where('id', $id)->firstOrFail();
        $device->name = $request->get('name');
        $device->save();
        return response()->json(["success" => "Device updated"]);
    }

    public function detailDevice(Request $request, $id){
        if(!is_numeric($id)){
            return response()->json(['error' => 'id is not an integer'], 400);
        }
        $device = Auth::user()->devices()->findOrFail($id);
        return response()->json(['device' => $device], 200);
    }

    public function detailViewDevice(Request $request){
        return response()->json(['device' => DeviceController::device()], 200);
    }
}
