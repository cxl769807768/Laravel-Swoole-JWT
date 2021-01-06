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

Route::get('/ss', 'RegisterController@store');
//Route::any('/products/index', 'ProductsController@index');
Route::get('/line','ChatController@index');
Route::any('/mod/index','ModController@index');

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


//多个方法公用一个路由
Route::any('products/{action}', function(App\Http\Controllers\ProductsController $Products, $action,Request $request){
    return $Products->$action($request);
});

//Route::group(['middleware' => ['api.auth']], function () {
////    Route::get('/userCenter', 'userCenterController@show');
//    Route::get('/userCenter','userCenterController@show')->middleware('RefreshToken');
//
//});
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


