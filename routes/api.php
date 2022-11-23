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

Route::get('test', 'App\Http\Controllers\API\TestController@index');
Route::post('login', 'App\Http\Controllers\API\AuthController@login');
Route::group(['middleware' => 'auth:api', 'except' => 'test'], function () {//middleware auth:api sesuai yang telah diberikan di construct authCOntroller di webnya jwt jadi tidak usah menggunakan construct
    Route::post('register', 'App\Http\Controllers\API\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\API\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\API\AuthController@me');
    Route::get('user-data', 'App\Http\Controllers\API\AuthController@data');

    Route::group(['prefix' => 'presensi'], function(){
        Route::post('open', 'App\Http\Controllers\API\PresensiController@open');
        Route::post('close', 'App\Http\Controllers\API\PresensiController@close');
        Route::get('detail/{id}', 'App\Http\Controllers\API\PresensiController@detail');
        Route::put('update/{id}', 'App\Http\Controllers\API\PresensiController@update');
        Route::delete('delete/{id}', 'App\Http\Controllers\API\PresensiController@delete');
    });

    Route::group(['prefix' => 'absen'], function (){
        Route::post('create/{id}', 'App\Http\Controllers\API\AbsenController@create');
        Route::get('detail/{id}', 'App\Http\Controllers\API\AbsenController@detail');
    });

    Route::group(['prefix' => 'kelas'], function (){
        Route::post('create', 'App\Http\Controllers\API\kelasController@create');
        Route::get('detail/{id}', 'App\Http\Controllers\API\kelasController@detail');
        Route::get('index', 'App\Http\Controllers\API\kelasController@index');
        Route::put('update/{id}', 'App\Http\Controllers\API\kelasController@update');
        Route::delete('delete/{id}', 'App\Http\Controllers\API\kelasController@delete');
    });

    Route::group(['prefix' => 'matkul'], function (){
        Route::post('create','App\Http\Controllers\API\MatkulController@create');
        Route::get('detail/{id}','App\Http\Controllers\API\MatkulController@detail');
        Route::get('index','App\Http\Controllers\API\MatkulController@index');
        Route::put('update/{id}','App\Http\Controllers\API\MatkulController@update');
        Route::delete('delete/{id}','App\Http\Controllers\API\MatkulController@delete');
    });
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
