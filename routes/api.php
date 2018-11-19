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
Route::get('cities/{id}', 'CityController@getCitiesByUf');
Route::get('state','StateController@index');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('category', 'CategoryController@index');
    Route::get('advertisement/awaiting-approval', 'AdvertisementController@awaitingApprovalAdvertisement');
    Route::get('advertisement/awaiting-approval/{id}', 'AdvertisementController@showPending');
    Route::put('advertisement/update-status/{id}','AdvertisementController@updateAdvertisementStatus');
    Route::get('current/user', 'UserController@getCurrentUser');
    Route::resource('users','UserController');
    Route::get('public-page', 'AdvertisementController@publicPage');
    Route::resource('advertisement','AdvertisementController');
});
