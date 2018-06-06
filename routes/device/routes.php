<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Device\DeviceController;

/*
|--------------------------------------------------------------------------
| Device API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api', 'cors']], function(){
    Route::get('/getDeviceToken', 'Device\DeviceController@sendDeviceToken');
    Route::post('/device', 'Device\DeviceController@addDevice');
    Route::get('/device', 'Device\DeviceController@getDevices');
    Route::put('/device/{id}', 'Device\DeviceController@editDevice');
    Route::delete('/device/{id}', 'Device\DeviceController@removeDevice');
    Route::get('/device/{id}', 'Device\DeviceController@detailDevice');
});

Route::group(['middleware' => ['DeviceLogin', 'api', 'cors']] , function () {
    Route::get('/deviceapi/detail', 'Device\DeviceController@detailViewDevice');
});
