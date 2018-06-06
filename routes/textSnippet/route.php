<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TextSnippet\TextSnippetController;

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
    Route::post('/textSnippet', 'TextSnippet\TextSnippetController@insertTextSnippet');
    Route::get('/textSnippet/{id}', 'TextSnippet\TextSnippetController@getTextSnippet');
    Route::get('/textSnippet', 'TextSnippet\TextSnippetController@getTextSnippets');
    Route::delete('/textSnippet/{id}', 'TextSnippet\TextSnippetController@deleteTextSnippet');
});

Route::group(['middleware' => ['DeviceLogin', 'api', 'cors']] , function () {
    Route::post('/deviceapi/textSnippet', 'TextSnippet\TextSnippetController@insertTextSnippet');
});

