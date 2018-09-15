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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('users/{id}','UserController@show');

Route::group(['middleware' =>
                 ['jwt.verify']
            ], function() {
    Route::get('authenticate_user', 'UserController@getAuthenticatedUser');
    Route::put('user/{id}','UserController@update');
    Route::get('users','UserController@index');
    Route::resource('advertisement','AdvertisementController');
});
