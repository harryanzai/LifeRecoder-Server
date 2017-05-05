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


    Route::post('seedCode','AuthenticateController@sendCheckCode');

    Route::post('register','AuthenticateController@registerUser');
    Route::post('login','AuthenticateController@loginUser');

    Route::get('refresh/token','AuthenticateController@token');

    Route::post('logout','AuthenticateController@logout');

    Route::get('state','AuthenticateController@state');

    Route::get('users/{user}','UsersController@show');

    Route::resource('galleries','GalleriesController');


    Route::post('galleries/{gallery}/photos','PhotosController@store');

    Route::put('photos/{photo}','PhotosController@update');

});
