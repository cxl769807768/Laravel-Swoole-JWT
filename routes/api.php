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


Route::group(['middleware' => ['auth.api']], function () {
    Route::get('/userCenter', 'userCenterController@show');
});
/**
Route::any('unAuth', function () {
    return response()->json([
        'code'=>4001,
        'msg'=>"token验证失败",
        'data'=>[]
    ]);
})->name('unAuth');
Route::middleware('auth:api')->group(function() {
    Route::post('/logout', 'LoginController@logout');
    Route::get('/userCenter', 'userCenterController@show');
});
**/

Route::post('/register', 'RegisterController@store');
