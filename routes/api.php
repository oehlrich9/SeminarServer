<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api','cors']], function () {
    Route::post('auth/login', 'Auth\PassportLoginController@login');
    Route::post('auth/register', 'Auth\PassportRegisterController@register');
});



Route::group(['middleware' => ['auth:api', 'cors']], function(){
    Route::post('/changePassword', 'Auth\PassportChangePasswordController@changePassword');
    Route::put('/user', 'User\UserController@updateUser');
    Route::get('/user', 'User\UserController@userDetail');
});
