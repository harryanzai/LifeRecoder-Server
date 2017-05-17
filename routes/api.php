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
    Route::post('users/{user}', 'UsersController@update');

    Route::post('follow','UsersController@doFollow');

    Route::get('users/{user}/following', 'UsersController@following')->name('users.following');
    Route::get('users/{user}/followers', 'UsersController@followers')->name('users.followers');


    Route::resource('galleries','GalleriesController');


    Route::post('galleries/{gallery}/photos','PhotosController@store');

    Route::put('photos/{photo}','PhotosController@update');

    Route::delete('photos/{photo}','PhotosController@destroy');

    Route::post('commentable/{commentable}/comments','CommentsController@store');

    Route::get('galleries/{gallery}/comments','CommentsController@index')->name('gallery.comments');

    Route::delete('comments/{comment}','CommentsController@destroy');

    Route::post('galleries/{gallery}/favorites','FavoritesController@store')
        ->name('gallery.favorite');
    Route::post('articles/{article}/favorites','FavoritesController@store')
        ->name('article.favorite');

    Route::delete('galleries/{gallery}/favorites','FavoritesController@destroy')
        ->name('gallery.favorite');

    Route::post('comments/{comment}/votes','VotesController@store')
        ->name('comment.vote');

    Route::delete('comments/{comment}/votes','VotesController@destroy')
        ->name('comment.vote');

    Route::get('messages','NotificationsController@index');

    // 关注者的动态
    Route::get('status','FollowsController@index');

    // 获取文章
    Route::get('articles','ArticlesController@index');

    // 获取所有的话题
    Route::get('topics','TopicsController@index');


});
