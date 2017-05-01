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

Route::group(['prefix'=>'v1','namespace' => 'Api'],function (){

    Route::post('seedCode.json','AuthenticateController@sendCheckCode');

    Route::post('register.json','AuthenticateController@registerUser');
    Route::post('login.json','AuthenticateController@loginUser');

    Route::post('logout.json','AuthenticateController@logout');

    Route::get('state','AuthenticateController@state');

    Route::get('users/{user}','UsersController@show');


});
