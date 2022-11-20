<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::resource('test', 'App\Http\Controllers\API\TestController');
Route::post('login', 'App\Http\Controllers\API\AuthController@login');
Route::group(['middleware' => 'api'], function () {
    Route::post('register', 'App\Http\Controllers\API\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\API\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\API\AuthController@me');
    Route::get('user-data', 'App\Http\Controllers\API\AuthController@data');

    Route::group(['prefix' => 'presensi'], function(){
        Route::post('open', 'App\Http\Controllers\API\PresensiController@open');
        Route::post('close', 'App\Http\Controllers\API\PresensiController@close');
        Route::delete('delete/{id}', 'App\Http\Controllers\API\PresensiController@delete');
    });

    Route::group(['prefix' => 'absen'], function (){
        Route::post('create/{id}', 'App\Http\Controllers\API\AbsenController@create');
    });

    Route::group(['prefix' => 'kelas'], function (){
        Route::post('create', 'App\Http\Controllers\API\kelasController@create');
        Route::get('detail/{id}', 'App\Http\Controllers\API\kelasController@detail');
        Route::get('index', 'App\Http\Controllers\API\kelasController@index');
        Route::put('update/{id}', 'App\Http\Controllers\API\kelasController@update');
        Route::delete('delete/{id}', 'App\Http\Controllers\API\kelasController@delete');
    });
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
