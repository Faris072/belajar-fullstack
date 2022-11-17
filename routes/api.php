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

Route::post('/login', 'App\Http\Controllers\API\AuthController@login');
Route::post('/register', 'App\Http\Controllers\API\AuthController@register');
Route::group(['middleware' => 'api'], function ($router) {
    Route::post('/logout', 'App\Http\Controllers\API\AuthController@logout');
    Route::post('/refresh', 'App\Http\Controllers\API\AuthController@refresh');
    Route::post('/me', 'App\Http\Controllers\API\AuthController@me');
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
