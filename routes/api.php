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

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
        Route::get('logout', 'AuthController@logout')->middleware('auth_api');
    });

    Route::group(['middleware' => ['auth_api'], 'prefix' => 'book'], function() {
        Route::get('/', 'BookController@index');
        Route::post('/', 'BookController@store');
        Route::get('/{book}', 'BookController@show');
        Route::post('/{book}/rating', 'BookController@store_rating');
        Route::post('/{book}/review', 'BookController@store_review');
    });
});
