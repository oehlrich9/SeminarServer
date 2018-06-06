<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Image\ImageController;

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
    Route::post('/image', 'Image\ImageController@uploadImage');
    Route::get('/image/{id}', 'Image\ImageController@getImage');
    Route::get('/image/{id}/detail', 'Image\ImageController@getImageDetail');
    Route::get('/image', 'Image\ImageController@getImages');
    Route::delete('/image/{id}', 'Image\ImageController@deleteImage');
});

Route::group(['middleware' => ['DeviceLogin', 'api', 'cors']] , function () {
    Route::post('/deviceapi/image', 'Image\ImageController@uploadImage');
});

