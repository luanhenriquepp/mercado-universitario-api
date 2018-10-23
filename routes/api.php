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
Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path('app/advertisement/' . $filename);

    if (!File::exists($path)) {

        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('category', 'CategoryController@index');
    Route::resource('users','UserController');
    Route::get('public-page', 'AdvertisementController@publicPage');
    Route::resource('advertisement','AdvertisementController');
});
