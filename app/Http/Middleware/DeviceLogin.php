<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Device;
use App\Http\Controllers\Device\DeviceController;
use Illuminate\Support\Facades\Auth;


class DeviceLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        error_log("Arrived at middleware");
        $deviceToken = $request->header('deviceToken');
        error_log($deviceToken);
        $query = Device::where('secret', $deviceToken);
        if(!$query->exists()){
            return response()->json(['error' => 'Device Token not known or unauthorized', 401]);
        }
        $user = $query->first()->user;

        Auth::login($user);
        DeviceController::setDevice($query->first());

        //Auth::setDevice($query->first());
        return $next($request);
    }
}
